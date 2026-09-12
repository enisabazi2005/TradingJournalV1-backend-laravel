<?php

namespace App\Services;

use App\Models\Trade;
use App\Models\TradingAccount;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class Mt5SyncService
{
    /**
     * Synchronize one normalized MT5 trade.
     *
     * This service NEVER communicates with MT5 directly.
     *
     * Python is responsible for reading MT5.
     * Laravel is responsible for persistence.
     */
    public function syncTrade(
        int $tradingAccountId,
        array $tradeData
    ): Trade {
        return DB::transaction(function () use (
            $tradingAccountId,
            $tradeData
        ) {
            $account = TradingAccount::query()
                ->lockForUpdate()
                ->find($tradingAccountId);

            if (!$account) {
                throw new RuntimeException(
                    "Trading account {$tradingAccountId} was not found."
                );
            }

            $positionId = $tradeData['mt5_position_id'] ?? null;

            if (!$positionId) {
                throw new RuntimeException(
                    'MT5 position ID is required.'
                );
            }

            /*
             * The real identity of an MT5 trade is:
             *
             * trading_account_id + mt5_position_id
             *
             * This allows different MT5 accounts to have
             * overlapping position IDs.
             */
            $trade = Trade::query()
                ->where('trading_account_id', $tradingAccountId)
                ->where('mt5_position_id', $positionId)
                ->lockForUpdate()
                ->first();

            if (!$trade) {
                $trade = new Trade();

                $trade->trading_account_id = $tradingAccountId;
                $trade->mt5_position_id = $positionId;
            }

            $trade->fill([
                'mt5_order_id' => $tradeData['mt5_order_id'] ?? null,
                'mt5_deal_id' => $tradeData['mt5_deal_id'] ?? null,

                'symbol' => $tradeData['symbol'],
                'direction' => $tradeData['direction'],
                'volume' => $tradeData['volume'],

                'entry_price' => $tradeData['entry_price'],
                'exit_price' => $tradeData['exit_price'] ?? null,

                'profit' => $tradeData['profit'] ?? 0,
                'commission' => $tradeData['commission'] ?? 0,
                'swap' => $tradeData['swap'] ?? 0,

                'currency' => $tradeData['currency']
                    ?? $account->currency,

                'opened_at' => $tradeData['opened_at'],
                'closed_at' => $tradeData['closed_at'] ?? null,

                'status' => $tradeData['status'],

                'comment' => $tradeData['comment'] ?? null,
            ]);

            $trade->save();

            return $trade->fresh([
                'tradingAccount',
                'screenshots',
                'journal',
                'tags',
            ]);
        });
    }
}