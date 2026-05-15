<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_task_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('progress')->default(0);
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->timestamps();

            $table->unique(['group_task_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_task_assignments');
    }
};
