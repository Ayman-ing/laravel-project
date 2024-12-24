<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\AppointmentController;

// Test Route
Route::get('/test', function () {
    return response()->json(['message' => 'Hello from Laravel API!']);
});

// Routes pour le ConsultationController
Route::get('consultations', [ConsultationController::class, 'getConsultations']);
Route::get('consultations/{id}', [ConsultationController::class, 'getConsultationById']);
Route::post('consultations', [ConsultationController::class, 'createConsultation']);
Route::put('consultations/{id}', [ConsultationController::class, 'editConsultation']);
Route::delete('consultations/{id}', [ConsultationController::class, 'deleteConsultation']);

// Routes pour le AppointmentController
Route::get('appointments', [AppointmentController::class, 'index']);
Route::get('appointments/{id}', [AppointmentController::class, 'show']);
Route::post('appointments', [AppointmentController::class, 'store']);
Route::put('appointments/{id}', [AppointmentController::class, 'update']);
Route::delete('appointments/{id}', [AppointmentController::class, 'destroy']);

