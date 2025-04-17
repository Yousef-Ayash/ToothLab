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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // Order number for each doctor
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->string('center_name');
            $table->string('patient_name');
            $table->foreignId('tooth_id')->constrained();
            $table->foreignId('procedure_id')->constrained();
            $table->foreignId('color_id')->nullable()->constrained(); // For tooth color
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->boolean('is_paid')->default(false);
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
