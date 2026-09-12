<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trade_journals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('trade_id')
                ->unique()
                ->constrained('trades')
                ->cascadeOnDelete();

            $table->string('setup', 100)->nullable();
            $table->string('strategy', 100)->nullable();

            $table->string('market_condition', 100)->nullable();

            $table->string('emotion', 100)->nullable();

            $table->text('what_went_well')->nullable();
            $table->text('what_went_wrong')->nullable();
            $table->text('notes')->nullable();

            $table->unsignedTinyInteger('rating')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_journals');
    }
};