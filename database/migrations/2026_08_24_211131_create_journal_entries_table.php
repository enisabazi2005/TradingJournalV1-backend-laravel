<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();

            $table->date('journal_date')->unique();

            $table->text('trading_plan')->nullable();

            $table->text('market_conditions')->nullable();

            $table->text('reflection')->nullable();

            $table->text('what_went_well')->nullable();

            $table->text('what_to_improve')->nullable();

            $table->timestamps();

            $table->index('journal_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};