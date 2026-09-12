<?php

namespace App\Http\Controllers\Api\Internal;

use App\Http\Controllers\Controller;
use App\Models\TradingAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Mt5AccountSyncController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'broker' => [
                'required',
                'string',
                'max:100',
            ],

            'server' => [
                'nullable',
                'string',
                'max:150',
            ],

            'account_number' => [
                'required',
                'string',
                'max:100',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'balance' => [
                'required',
                'numeric',
            ],

            'equity' => [
                'required',
                'numeric',
            ],

            'margin' => [
                'required',
                'numeric',
            ],

            'free_margin' => [
                'required',
                'numeric',
            ],
        ]);

        $account = TradingAccount::query()
            ->updateOrCreate(
                [
                    'broker' => $validated['broker'],
                    'server' => $validated['server'] ?? null,
                    'account_number' => $validated['account_number'],
                ],
                [
                    'currency' => $validated['currency'],
                    'balance' => $validated['balance'],
                    'equity' => $validated['equity'],
                    'margin' => $validated['margin'],
                    'free_margin' => $validated['free_margin'],
                    'is_active' => true,
                ]
            );

        return response()->json([
            'success' => true,
            'account' => $account,
        ]);
    }
}