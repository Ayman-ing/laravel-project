<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;

use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Get today's date
        $today = Carbon::today()->toDateString();

        // Fetch appointments for today
        $appointments = Appointment::with('patient') 
        ->whereDate('date', $today)
        ->get();

        return response()->json($appointments);
    }
}
