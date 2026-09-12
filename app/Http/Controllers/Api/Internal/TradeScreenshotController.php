<?php

namespace App\Http\Controllers\Api\Internal;

use App\Http\Controllers\Controller;
use App\Models\Trade;
use App\Models\TradeScreenshot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TradeScreenshotController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'trading_account_id' => ['required', 'integer'],
            'mt5_position_id' => ['required', 'integer'],
            'screenshot' => [
                'required',
                'file',
                'mimes:png',
                'max:10240',
            ],
        ]);

        $trade = Trade::query()
            ->where(
                'trading_account_id',
                $validated['trading_account_id']
            )
            ->where(
                'mt5_position_id',
                $validated['mt5_position_id']
            )
            ->first();

        if (!$trade) {
            return response()->json([
                'message' => 'Trade not found.',
                'trading_account_id' => $validated['trading_account_id'],
                'mt5_position_id' => $validated['mt5_position_id'],
            ], 404);
        }

        /*
         * We only keep one generated chart screenshot
         * for each trade for now.
         *
         * If it already exists, replace it.
         */
        $existing = TradeScreenshot::query()
            ->where('trade_id', $trade->id)
            ->where('sort_order', 0)
            ->first();

        if ($existing) {
            if (
                $existing->disk &&
                $existing->path &&
                Storage::disk($existing->disk)->exists($existing->path)
            ) {
                Storage::disk($existing->disk)->delete(
                    $existing->path
                );
            }

            $existing->delete();
        }

        $file = $request->file('screenshot');

        $filename = sprintf(
            'trade_%d_%s.png',
            $trade->mt5_position_id,
            preg_replace(
                '/[^A-Za-z0-9_.-]/',
                '_',
                $trade->symbol
            )
        );

        $path = $file->storeAs(
            'trade-screenshots',
            $filename,
            'local'
        );

        $screenshot = TradeScreenshot::create([
            'trade_id' => $trade->id,
            'disk' => 'local',
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'sort_order' => 0,
        ]);

        return response()->json([
            'message' => 'Trade screenshot stored successfully.',
            'trade' => [
                'id' => $trade->id,
                'mt5_position_id' => $trade->mt5_position_id,
                'symbol' => $trade->symbol,
            ],
            'screenshot' => [
                'id' => $screenshot->id,
                'path' => $screenshot->path,
                'original_name' => $screenshot->original_name,
                'mime_type' => $screenshot->mime_type,
                'size' => $screenshot->size,
            ],
        ], 201);
    }

    public function status(
        int $tradingAccountId,
        int $mt5PositionId
    ): JsonResponse {
        $trade = Trade::query()
            ->where(
                'trading_account_id',
                $tradingAccountId
            )
            ->where(
                'mt5_position_id',
                $mt5PositionId
            )
            ->first();
    
        if (!$trade) {
            return response()->json([
                'exists' => false,
                'trade_exists' => false,
            ]);
        }
    
        $screenshot = TradeScreenshot::query()
            ->where('trade_id', $trade->id)
            ->orderBy('sort_order')
            ->first();
    
        return response()->json([
            'exists' => $screenshot !== null,
            'trade_exists' => true,
            'trade_id' => $trade->id,
            'screenshot_id' => $screenshot?->id,
        ]);
    }
}