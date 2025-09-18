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
        Schema::create('mechanic_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mechanic_id')->constrained('users')->onDelete('cascade');
            $table->enum('badge_type', [
                'mechanic_of_month',
                'specialist_matic',
                'specialist_2tak',
                'specialist_4tak',
                'specialist_all'
            ]);
            $table->string('title');
            $table->text('description');
            $table->string('icon')->nullable();
            $table->datetime('earned_at');
            $table->timestamps();
            
            // Indexes
            $table->index('mechanic_id');
            $table->index('badge_type');
            $table->index('earned_at');
            $table->index(['mechanic_id', 'badge_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mechanic_badges');
    }
};