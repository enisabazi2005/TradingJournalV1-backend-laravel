<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TradingAccount extends Model
{
    protected $fillable = [
        'broker',
        'server',
        'account_number',
        'currency',
        'balance',
        'equity',
        'margin',
        'free_margin',
        'is_active',
    ];

    protected $casts = [
        'balance' => 'decimal:8',
        'equity' => 'decimal:8',
        'margin' => 'decimal:8',
        'free_margin' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    public function trades(): HasMany
    {
        return $this->hasMany(Trade::class);
    }
}