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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('subject');
            $table->text('description');
            $table->integer('qty')->default(1);
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('builder_id')->constrained()->onDelete('cascade');
            $table->foreignId('dweling_id')->constrained()->onDelete('cascade');
            $table->foreignId('status_id')->constrained('status')->onDelete('cascade');
            $table->decimal('duration', 8, 2)->default(0);
            $table->text('note')->nullable();
            $table->time('work_time')->nullable();
            $table->boolean('temp')->default(false);
            $table->boolean('approved')->default(false);
            $table->timestamp('approved_date')->nullable();
            $table->text('approved_note')->nullable();
            $table->string('approved_emoji')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
