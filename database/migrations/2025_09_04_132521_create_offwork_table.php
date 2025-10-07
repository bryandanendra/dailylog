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
        Schema::create('offwork', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('date');
            $table->string('leave_type');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            $table->boolean('archive')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offwork');
    }
};
