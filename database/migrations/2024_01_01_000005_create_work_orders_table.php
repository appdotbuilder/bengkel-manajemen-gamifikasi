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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('wo_number')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_mechanic_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['menunggu', 'dikerjakan', 'pengecekan', 'selesai'])->default('menunggu');
            $table->enum('service_type', ['ringan', 'berat'])->default('ringan');
            $table->text('complaint')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('work_done')->nullable();
            $table->text('additional_findings')->nullable();
            $table->decimal('estimated_cost', 10, 2)->default(0);
            $table->decimal('final_cost', 10, 2)->default(0);
            $table->boolean('approved_by_head')->default(false);
            $table->boolean('no_revision')->default(false);
            $table->integer('customer_rating')->nullable();
            $table->text('customer_feedback')->nullable();
            $table->boolean('overtime_work')->default(false);
            $table->decimal('overtime_hours', 4, 2)->default(0);
            $table->datetime('started_at')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->datetime('approved_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('wo_number');
            $table->index('status');
            $table->index('service_type');
            $table->index('assigned_mechanic_id');
            $table->index('customer_id');
            $table->index(['status', 'assigned_mechanic_id']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};