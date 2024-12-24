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
        Schema::disableForeignKeyConstraints();
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade'); 
            $table->dateTime('appointment_date'); 
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'canceled', 'no_show'])
      ->default('pending');
            $table->text('reason_for_visit')->nullable(); 
            $table->text('notes')->nullable(); 
            $table->decimal('total_fee', 10, 2)->nullable(); 
            $table->timestamp('canceled_at')->nullable(); 
            $table->text('cancellation_reason')->nullable();
            $table->timestamps(); 
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('appointments'); // Corrected table name
        Schema::enableForeignKeyConstraints();
    }
};
