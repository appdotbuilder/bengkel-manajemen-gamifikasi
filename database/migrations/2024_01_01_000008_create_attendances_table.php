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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mechanic_id')->constrained('users')->onDelete('cascade');
            $table->date('date');
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->boolean('is_present')->default(false);
            $table->boolean('is_on_time')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('mechanic_id');
            $table->index('date');
            $table->index('is_present');
            $table->index('is_on_time');
            $table->index(['mechanic_id', 'date']);
            $table->unique(['mechanic_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};