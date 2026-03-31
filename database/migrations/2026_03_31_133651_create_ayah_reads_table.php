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
        Schema::create('ayah_reads', function (Blueprint $blueprint) {
            $blueprint->uuid('id')->primary();
            $blueprint->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $blueprint->unsignedSmallInteger('surah_number');
            $blueprint->unsignedSmallInteger('ayah_number');
            $blueprint->timestamp('read_at')->useCurrent();
            $blueprint->timestamps();

            $blueprint->index(['user_id', 'read_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ayah_reads');
    }
};
