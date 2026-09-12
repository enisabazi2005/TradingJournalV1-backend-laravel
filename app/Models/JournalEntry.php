<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $fillable = [
        'journal_date',
        'trading_plan',
        'market_conditions',
        'reflection',
        'what_went_well',
        'what_to_improve',
    ];

    protected $casts = [
        'journal_date' => 'date',
    ];
}