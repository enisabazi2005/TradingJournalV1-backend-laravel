<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trade_screenshots', function (Blueprint $table) {
            $table->id();

            $table->foreignId('trade_id')
                ->constrained('trades')
                ->cascadeOnDelete();

            $table->string('disk', 50)->default('local');

            $table->string('path', 500);

            $table->string('original_name', 255)->nullable();

            $table->string('mime_type', 100)->nullable();

            $table->unsignedBigInteger('size')->nullable();

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index([
                'trade_id',
                'sort_order',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_screenshots');
    }
};