<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_statistics', function (Blueprint $table) {
            $table->id();

            $table->date('stat_date')->unique();

            $table->unsignedInteger('trade_count')->default(0);
            $table->unsignedInteger('winning_trades')->default(0);
            $table->unsignedInteger('losing_trades')->default(0);

            $table->decimal('gross_profit', 20, 8)->default(0);
            $table->decimal('gross_loss', 20, 8)->default(0);

            $table->decimal('net_profit', 20, 8)->default(0);

            $table->decimal('win_rate', 8, 4)->default(0);

            $table->decimal('average_trade', 20, 8)->default(0);

            $table->timestamps();

            $table->index('stat_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_statistics');
    }
};