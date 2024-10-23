<?php
use App\Http\Middleware;
use Illuminate\Support\Facades\Route;

Route::middleware([CorsMiddleware::class])->group(function () {
    // Your API routes here
    Route::get('api/test', function () {
        return response()->json(['message' => 'Hello from Laravel API!']);
    });
    
});
