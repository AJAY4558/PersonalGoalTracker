<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Categories Table
 * Stores goal categories (e.g., Health, Career, Finance)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // Category name
            $table->string('slug')->unique(); // URL-friendly name
            $table->string('color', 7)->default('#6366f1'); // Hex color for UI badge
            $table->string('icon')->default('bi-tag'); // Bootstrap icon class
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
