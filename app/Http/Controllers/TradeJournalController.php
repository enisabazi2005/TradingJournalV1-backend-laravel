<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TradeJournalController extends Controller
{
    public function update(
        Request $request,
        Trade $trade
    ): JsonResponse {
        $validated = $request->validate([
            'setup' => ['nullable', 'string', 'max:100'],
            'strategy' => ['nullable', 'string', 'max:100'],
            'market_condition' => ['nullable', 'string', 'max:100'],
            'emotion' => ['nullable', 'string', 'max:100'],
            'what_went_well' => ['nullable', 'string'],
            'what_went_wrong' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
        ]);

        $journal = $trade->journal()->updateOrCreate(
            [
                'trade_id' => $trade->id,
            ],
            $validated
        );

        return response()->json(
            $journal->fresh()
        );
    }
}