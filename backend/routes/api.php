<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PatientController;

// Test Route
Route::get('/test', function () {
    return response()->json(['message' => 'Hello from Laravel API!']);
});

// Routes pour le ConsultationController
Route::get('consultations', [ConsultationController::class, 'index'])->name("consultations.index");
Route::get('consultations/{id}', [ConsultationController::class, 'show'])->name("consultations.show");
Route::post('consultations', [ConsultationController::class, 'store'])->name("consultations.store");
Route::put('consultations/{id}', [ConsultationController::class, 'update'])->name("consultations.update");
Route::delete('consultations/{id}', [ConsultationController::class, 'destroy'])->name("consultations.destroy");

Route::get('appointments', [AppointmentController::class, 'index'])->name("appointments.index");
Route::get('appointments/{id}', [AppointmentController::class, 'show'])->name("appointments.show");
Route::post('appointments', [AppointmentController::class, 'store'])->name("appointments.store");
Route::put('appointments/{id}', [AppointmentController::class, 'update'])->name("appointments.update");
Route::delete('appointments/{id}', [AppointmentController::class, 'destroy'])->name("appointments.destroy");

Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
Route::get('/patients/{id}', [PatientController::class, 'show'])->name('patients.show');
Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
Route::put('/patients/{id}', [PatientController::class, 'update'])->name('patients.update');
Route::delete('/patients/{id}', [PatientController::class, 'destroy'])->name('patients.destroy');
