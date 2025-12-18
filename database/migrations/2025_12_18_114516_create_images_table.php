<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained();

            $table->string('original_name');
            $table->string('path');

            // Variants
            $table->string('thumbnail_path')->nullable();
            $table->string('medium_path')->nullable();

            $table->unsignedBigInteger('size');
            $table->string('mime_type');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
