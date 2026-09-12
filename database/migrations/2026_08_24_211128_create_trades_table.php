<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();

            $table->foreignId('trading_account_id')
                ->constrained('trading_accounts')
                ->cascadeOnDelete();

            /*
             * MT5 identifiers.
             *
             * These are deliberately separate from our internal ID.
             */
            $table->unsignedBigInteger('mt5_position_id')->nullable();
            $table->unsignedBigInteger('mt5_order_id')->nullable();
            $table->unsignedBigInteger('mt5_deal_id')->nullable();

            $table->string('symbol', 32);

            $table->enum('direction', ['BUY', 'SELL']);

            $table->decimal('volume', 20, 8);

            $table->decimal('entry_price', 20, 8);
            $table->decimal('exit_price', 20, 8)->nullable();

            $table->decimal('stop_loss', 20, 8)->nullable();
            $table->decimal('take_profit', 20, 8)->nullable();

            $table->decimal('profit', 20, 8)->default(0);
            $table->decimal('commission', 20, 8)->default(0);
            $table->decimal('swap', 20, 8)->default(0);

            $table->string('currency', 10)->default('USD');

            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();

            $table->enum('status', [
                'OPEN',
                'CLOSED',
            ])->default('OPEN');

            $table->string('comment', 255)->nullable();

            $table->timestamps();

            /*
             * Fast MT5 synchronization queries.
             */
            $table->index([
                'trading_account_id',
                'status',
            ]);

            $table->index([
                'symbol',
                'opened_at',
            ]);

            $table->index('mt5_position_id');
            $table->index('mt5_order_id');
            $table->index('mt5_deal_id');

            /*
             * We don't make MT5 identifiers globally unique because
             * different MT5 accounts can have overlapping ticket IDs.
             */
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};