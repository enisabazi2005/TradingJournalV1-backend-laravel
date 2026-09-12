<?php

namespace App\Http\Controllers\Api\Internal;

use App\Http\Controllers\Controller;
use App\Services\Mt5SyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class Mt5SyncController extends Controller
{
    public function __construct(
        private readonly Mt5SyncService $syncService
    ) {
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'trading_account_id' => [
                'required',
                'integer',
                'exists:trading_accounts,id',
            ],

            'trade' => [
                'required',
                'array',
            ],

            'trade.mt5_position_id' => [
                'required',
                'integer',
                'min:1',
            ],

            'trade.mt5_order_id' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'trade.mt5_deal_id' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'trade.symbol' => [
                'required',
                'string',
                'max:32',
            ],

            'trade.direction' => [
                'required',
                'in:BUY,SELL',
            ],

            'trade.volume' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'trade.entry_price' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'trade.exit_price' => [
                'nullable',
                'numeric',
                'gt:0',
            ],

            'trade.profit' => [
                'required',
                'numeric',
            ],

            'trade.commission' => [
                'required',
                'numeric',
            ],

            'trade.swap' => [
                'required',
                'numeric',
            ],

            'trade.currency' => [
                'required',
                'string',
                'max:10',
            ],

            'trade.opened_at' => [
                'required',
                'date',
            ],

            'trade.closed_at' => [
                'nullable',
                'date',
            ],

            'trade.status' => [
                'required',
                'in:OPEN,CLOSED',
            ],

            'trade.comment' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $trade = $this->syncService->syncTrade(
            $validated['trading_account_id'],
            $validated['trade']
        );

        return response()->json([
            'success' => true,
            'trade' => $trade,
        ]);
    }
}