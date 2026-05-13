<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Goals Table
 * Core table for the Personal Goal Tracker application
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();

            // Foreign keys (relationships)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');     // Goal owner
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete(); // Category

            // Core goal fields
            $table->string('title');                    // Goal title (required)
            $table->text('description')->nullable();    // Detailed description
            $table->date('deadline')->nullable();        // Target completion date

            // Status: pending | in_progress | completed | cancelled
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])
                  ->default('pending');

            // Progress percentage (0–100)
            $table->unsignedTinyInteger('progress')->default(0);

            // Priority: low | medium | high | critical
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])
                  ->default('medium');

            // Uploaded file/image path
            $table->string('image')->nullable();

            // Timestamps
            $table->timestamp('completed_at')->nullable(); // When goal was completed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
