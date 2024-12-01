<?php
use App\Http\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\PatientController;

Route::get('Patients', [PatientController::class, 'index']);
Route::get('Patients/{id}', [PatientController::class, 'show']);
Route::post('Patients', [PatientController::class, 'store']);
Route::put('Patients/{id}', [PatientController::class, 'update']);
Route::delete('Patients/{id}', [PatientController::class, 'destroy']);

Route::middleware([CorsMiddleware::class])->group(function () {
    // Your API routes here
    Route::get('api/test', function () {
        return response()->json(['message' => 'Hello from Laravel API!']);
    });

    Route::get('/consultations', [ConsultationController::class, 'getConsultations']);
    
});
