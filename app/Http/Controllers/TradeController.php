<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use Illuminate\Http\JsonResponse;

class TradeController extends Controller
{
    public function show(Trade $trade): JsonResponse
    {
        $trade->load([
            'tradingAccount',
            'screenshots',
            'journal',
            'tags',
        ]);

        return response()->json($trade);
    }
}