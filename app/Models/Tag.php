<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = [
        'name',
    ];

    public function trades(): BelongsToMany
    {
        return $this->belongsToMany(
            Trade::class,
            'tag_trade'
        );
    }
}