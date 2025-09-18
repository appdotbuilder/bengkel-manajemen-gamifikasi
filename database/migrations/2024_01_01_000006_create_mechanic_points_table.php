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
        Schema::create('mechanic_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mechanic_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('work_order_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('point_type', [
                'service_light',
                'service_heavy', 
                'no_revision',
                'additional_finding',
                'overtime',
                'customer_rating',
                'repeat_customer',
                'attendance_daily',
                'attendance_monthly'
            ]);
            $table->integer('points');
            $table->text('description');
            $table->datetime('earned_at');
            $table->timestamps();
            
            // Indexes
            $table->index('mechanic_id');
            $table->index('point_type');
            $table->index('earned_at');
            $table->index(['mechanic_id', 'earned_at']);
            $table->index(['mechanic_id', 'point_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mechanic_points');
    }
};