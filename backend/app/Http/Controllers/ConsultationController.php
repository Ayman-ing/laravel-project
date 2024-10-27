<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultation;

class ConsultationController extends Controller
{
    public function getConsultations()
    {
        $consultations = Consultation::all();
        return response()->json($consultations);
    }
}
