<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyStatistic extends Model
{
    protected $fillable = [
        'stat_date',
        'trade_count',
        'winning_trades',
        'losing_trades',
        'gross_profit',
        'gross_loss',
        'net_profit',
        'win_rate',
        'average_trade',
    ];

    protected $casts = [
        'stat_date' => 'date',
        'gross_profit' => 'decimal:8',
        'gross_loss' => 'decimal:8',
        'net_profit' => 'decimal:8',
        'win_rate' => 'decimal:4',
        'average_trade' => 'decimal:8',
    ];
}