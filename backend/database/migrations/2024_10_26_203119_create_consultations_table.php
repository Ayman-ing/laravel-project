<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultationsTable extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')
                ->nullable()
                ->constrained('appointments')
                ->onDelete('set null');
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->onDelete('cascade');
            $table->date('date');
            $table->time('time'); 
            $table->integer('duration')->nullable();
            $table->text('symptoms')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('treatment_plan')->nullable(); 
            $table->text('prescription')->nullable(); 
            $table->text('test_results')->nullable(); 
            $table->text('referrals')->nullable(); 
            $table->text('consultation_notes')->nullable(); 
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
}
