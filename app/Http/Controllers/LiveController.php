<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use App\Models\TradingAccount;
use Illuminate\Http\JsonResponse;

class LiveController extends Controller
{
    public function index(): JsonResponse
    {
        $account = TradingAccount::query()
            ->where('is_active', true)
            ->first();

        $openTrades = Trade::query()
            ->where('status', 'OPEN')
            ->orderBy('opened_at')
            ->get([
                'id',
                'symbol',
                'direction',
                'volume',
                'entry_price',
                'stop_loss',
                'take_profit',
                'profit',
                'commission',
                'swap',
                'opened_at',
            ]);

        return response()->json([
            'account' => $account,
            'trades' => $openTrades,
        ]);
    }
}