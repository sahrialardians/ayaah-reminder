<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reminder_settings', function (Blueprint $blueprint) {
            $blueprint->uuid('id')->primary();
            $blueprint->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $blueprint->boolean('is_enabled')->default(true);
            $blueprint->time('reminder_time')->default('08:00:00');
            $blueprint->string('timezone')->default('UTC');
            $blueprint->timestamps();

            $blueprint->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminder_settings');
    }
};
