<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Trade extends Model
{
    protected $fillable = [
        'trading_account_id',
        'mt5_position_id',
        'mt5_order_id',
        'mt5_deal_id',
        'symbol',
        'direction',
        'volume',
        'entry_price',
        'exit_price',
        'stop_loss',
        'take_profit',
        'profit',
        'commission',
        'swap',
        'currency',
        'opened_at',
        'closed_at',
        'status',
        'comment',
        'screenshot_note',
    ];

    protected $casts = [
        'volume' => 'decimal:8',
        'entry_price' => 'decimal:8',
        'exit_price' => 'decimal:8',
        'stop_loss' => 'decimal:8',
        'take_profit' => 'decimal:8',
        'profit' => 'decimal:8',
        'commission' => 'decimal:8',
        'swap' => 'decimal:8',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function tradingAccount(): BelongsTo
    {
        return $this->belongsTo(
            TradingAccount::class
        );
    }

    public function screenshots(): HasMany
    {
        return $this->hasMany(
            TradeScreenshot::class
        )->orderBy('sort_order');
    }

    public function journal(): HasOne
    {
        return $this->hasOne(
            TradeJournal::class
        );
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'tag_trade'
        );
    }

    public function isOpen(): bool
    {
        return $this->status === 'OPEN';
    }

    public function isClosed(): bool
    {
        return $this->status === 'CLOSED';
    }
}