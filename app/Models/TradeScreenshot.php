<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TradeScreenshot extends Model
{
    protected $fillable = [
        'trade_id',
        'disk',
        'path',
        'original_name',
        'mime_type',
        'size',
        'sort_order',
    ];

    protected $appends = [
        'url',
    ];

    public function trade(): BelongsTo
    {
        return $this->belongsTo(Trade::class);
    }

    public function getUrlAttribute(): string
    {
        return url(
            '/api/trade-screenshots/' . $this->id
        );
    }
}