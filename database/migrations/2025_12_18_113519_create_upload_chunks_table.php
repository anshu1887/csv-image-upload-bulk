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
        Schema::create('upload_chunks', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('upload_session_id')
                ->constrained()
                ->cascadeOnDelete();

            // Chunk identification
            $table->integer('chunk_index');
            $table->unsignedBigInteger('chunk_size');

            // Storage path
            $table->string('path');

            // Chunk integrity
            $table->string('checksum');

            $table->timestamps();

            // Prevent duplicate chunks
            $table->unique(['upload_session_id', 'chunk_index']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_chunks');
    }
};
