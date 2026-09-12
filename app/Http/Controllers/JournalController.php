<?php

namespace App\Http\Controllers;

use App\Models\DailyStatistic;
use App\Models\JournalEntry;
use App\Models\Trade;
use App\Models\TradingAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $from = $request->date('from');
        $to = $request->date('to');

        $account = TradingAccount::query()
            ->where('is_active', true)
            ->first();

        $trades = Trade::query()
            ->with([
                'screenshots',
                'journal',
                'tags',
            ])
            ->when(
                $from,
                fn ($query) => $query->whereDate('opened_at', '>=', $from)
            )
            ->when(
                $to,
                fn ($query) => $query->whereDate('opened_at', '<=', $to)
            )
            ->orderByDesc('opened_at')
            ->get();

        $journalEntries = JournalEntry::query()
            ->when(
                $from,
                fn ($query) => $query->whereDate('journal_date', '>=', $from)
            )
            ->when(
                $to,
                fn ($query) => $query->whereDate('journal_date', '<=', $to)
            )
            ->orderByDesc('journal_date')
            ->get();

        $statistics = DailyStatistic::query()
            ->when(
                $from,
                fn ($query) => $query->whereDate('stat_date', '>=', $from)
            )
            ->when(
                $to,
                fn ($query) => $query->whereDate('stat_date', '<=', $to)
            )
            ->orderByDesc('stat_date')
            ->get();

        return response()->json([
            'account' => $account,
            'trades' => $trades,
            'journal_entries' => $journalEntries,
            'daily_statistics' => $statistics,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'journal_date' => ['required', 'date'],
            'trading_plan' => ['nullable', 'string'],
            'market_conditions' => ['nullable', 'string'],
            'reflection' => ['nullable', 'string'],
            'what_went_well' => ['nullable', 'string'],
            'what_to_improve' => ['nullable', 'string'],
        ]);

        $entry = JournalEntry::updateOrCreate(
            [
                'journal_date' => $validated['journal_date'],
            ],
            $validated
        );

        return response()->json($entry);
    }

    public function update(
        Request $request,
        JournalEntry $entry
    ): JsonResponse {
        $validated = $request->validate([
            'journal_date' => ['sometimes', 'date'],
            'trading_plan' => ['nullable', 'string'],
            'market_conditions' => ['nullable', 'string'],
            'reflection' => ['nullable', 'string'],
            'what_went_well' => ['nullable', 'string'],
            'what_to_improve' => ['nullable', 'string'],
        ]);

        $entry->update($validated);

        return response()->json($entry->fresh());
    }
}