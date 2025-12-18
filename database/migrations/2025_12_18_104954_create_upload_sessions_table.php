<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('upload_sessions', function (Blueprint $table) {
            $table->id();   

            // Kis user ne upload start kiya
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Unique session identifier (frontend + backend sync)
            $table->uuid('session_uuid')->unique();

            // Original file info
            $table->string('original_filename');
            $table->unsignedBigInteger('total_size');

            // Upload tracking
            $table->integer('total_chunks');
            $table->integer('uploaded_chunks')->default(0);

            // Integrity check
            $table->string('checksum')->nullable();

            // Status lifecycle
            $table->enum('status', [
                'initiated',
                'uploading',
                'completed',
                'failed'
            ])->default('initiated');

            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upload_sessions');
    }
};
