<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsultationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('api/test', function () {
    return response()->json(['message' => 'Hello from Laravel API!']);
});

Route::get('/consultations', [ConsultationController::class, 'getConsultations']);