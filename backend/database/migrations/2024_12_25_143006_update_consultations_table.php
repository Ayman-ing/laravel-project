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
        Schema::table('consultations', function (Blueprint $table) {
            $table->string('medication')->nullable()->after('diag_cons'); // Add medication column
            $table->unsignedBigInteger('patient_id')->after('id_cons'); // Add patient_id column
            $table->unsignedBigInteger('appointment_id')->after('patient_id'); // Add appointment_id column

            // Set foreign keys
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['appointment_id']);
            $table->dropColumn(['medication', 'patient_id', 'appointment_id']);
        });
    }
};
