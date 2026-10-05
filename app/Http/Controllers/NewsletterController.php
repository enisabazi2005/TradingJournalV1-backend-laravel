<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterMail;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class NewsletterController extends Controller
{
    /**
     * Upload an image used inside the newsletter body (WYSIWYG editor).
     * Returns a public URL to embed in the composed HTML.
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'max:5120'],
        ]);

        $path = $validated['image'] instanceof \Illuminate\Http\UploadedFile
            ? $request->file('image')->store('newsletter-images', 'public')
            : null;

        return response()->json([
            'url' => Storage::disk('public')->url($path),
        ]);
    }

    /**
     * Send the composed newsletter to either all subscribers or a
     * chosen subset.
     */
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'body_html' => ['required', 'string'],
            'send_to_all' => ['required', 'boolean'],
            'subscriber_ids' => ['required_if:send_to_all,false', 'array', 'min:1'],
            'subscriber_ids.*' => ['integer', 'exists:newsletter_subscribers,id'],
        ]);

        $subscribers = $validated['send_to_all']
            ? NewsletterSubscriber::all()
            : NewsletterSubscriber::whereIn('id', $validated['subscriber_ids'])->get();

        if ($subscribers->isEmpty()) {
            return response()->json([
                'message' => 'No subscribers to send to.',
            ], 422);
        }

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->queue(
                new NewsletterMail($validated['subject'], $validated['body_html'])
            );
        }

        return response()->json([
            'message' => 'Newsletter queued for sending.',
            'recipient_count' => $subscribers->count(),
        ]);
    }
}