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
        Schema::create('upload_sessions', function (Blueprint $table) {
            $table->id();
            
            // Unique identifier to resume upload
            $table->uuid('session_uuid')->unique();

            // Original filename
            $table->string('filename');

            // Total file size
            $table->unsignedBigInteger('total_size');

            // Upload status
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])
                ->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_sessions');
    }
};
