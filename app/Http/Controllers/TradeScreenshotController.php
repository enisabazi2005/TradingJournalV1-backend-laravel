<?php

namespace App\Http\Controllers;

use App\Models\TradeScreenshot;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class TradeScreenshotController extends Controller
{
    public function show(TradeScreenshot $screenshot): Response
    {
        if (
            !Storage::disk($screenshot->disk)->exists(
                $screenshot->path
            )
        ) {
            abort(404);
        }

        $contents = Storage::disk($screenshot->disk)
            ->get($screenshot->path);

        return response(
            $contents,
            200,
            [
                'Content-Type' => $screenshot->mime_type
                    ?: 'image/png',

                'Content-Disposition' => 'inline; filename="' .
                    ($screenshot->original_name ?: 'trade.png') .
                    '"',

                'Cache-Control' => 'public, max-age=86400',
            ]
        );
    }
}