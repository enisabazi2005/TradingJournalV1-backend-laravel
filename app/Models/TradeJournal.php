<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TradeJournal extends Model
{
    protected $fillable = [
        'trade_id',
        'setup',
        'strategy',
        'market_condition',
        'emotion',
        'what_went_well',
        'what_went_wrong',
        'notes',
        'rating',
    ];

    public function trade(): BelongsTo
    {
        return $this->belongsTo(Trade::class);
    }
}