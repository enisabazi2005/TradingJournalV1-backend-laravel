<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsletterSubscriberController extends Controller
{
    public function index(): JsonResponse
    {
        $subscribers = NewsletterSubscriber::query()
            ->orderBy('email')
            ->get(['id', 'email', 'created_at']);

        return response()->json([
            'subscribers' => $subscribers,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:newsletter_subscribers,email',
            ],
        ]);

        $subscriber = NewsletterSubscriber::create($validated);

        return response()->json([
            'message' => 'Subscriber added.',
            'subscriber' => $subscriber,
        ], 201);
    }

    public function update(Request $request, NewsletterSubscriber $subscriber): JsonResponse
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:newsletter_subscribers,email,' . $subscriber->id,
            ],
        ]);

        $subscriber->update($validated);

        return response()->json([
            'message' => 'Subscriber updated.',
            'subscriber' => $subscriber,
        ]);
    }

    public function destroy(NewsletterSubscriber $subscriber): JsonResponse
    {
        $subscriber->delete();

        return response()->json([
            'message' => 'Subscriber deleted.',
        ]);
    }
}