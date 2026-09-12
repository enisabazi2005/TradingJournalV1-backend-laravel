<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trading_accounts', function (Blueprint $table) {
            $table->id();

            $table->string('broker', 100);
            $table->string('server', 150)->nullable();

            $table->string('account_number', 100)->nullable();
            $table->string('currency', 10)->default('USD');

            $table->decimal('balance', 20, 8)->default(0);
            $table->decimal('equity', 20, 8)->default(0);
            $table->decimal('margin', 20, 8)->default(0);
            $table->decimal('free_margin', 20, 8)->default(0);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index('broker');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trading_accounts');
    }
};