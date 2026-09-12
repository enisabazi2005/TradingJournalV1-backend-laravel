<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tag_trade', function (Blueprint $table) {
            $table->foreignId('trade_id')
                ->constrained('trades')
                ->cascadeOnDelete();

            $table->foreignId('tag_id')
                ->constrained('tags')
                ->cascadeOnDelete();

            $table->primary([
                'trade_id',
                'tag_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tag_trade');
    }
};