<?php
use App\Http\Middleware;
use Illuminate\Support\Facades\Route;
Route::get('/test', function () {
    return response()->json(['message' => 'Hello from Laravel API!']);
});

use App\Http\Controllers\ConsultationController;
Route::get('/consultations', [ConsultationController::class, 'getConsultations']);




use App\Http\Controllers\AppointmentController;

Route::get('appointments', [AppointmentController::class, 'index']);
Route::get('appointments/{id}', [AppointmentController::class, 'show']);
Route::post('appointments', [AppointmentController::class, 'store']);
Route::put('appointments/{id}', [AppointmentController::class, 'update']);
Route::delete('appointments/{id}', [AppointmentController::class, 'destroy']);

