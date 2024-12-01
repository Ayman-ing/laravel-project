<?php

namespace App\Http\Controllers;

use App\Services\PatientService;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    protected $PatientService;

    public function __construct(PatientService $PatientService)
    {
        $this->PatientService = $PatientService;
    }

    public function index()
    {
        $Patients = $this->PatientService->getAllPatients();
        return response()->json($Patients);
    }

    public function show($id)
    {
        $Patient = $this->PatientService->getPatientById($id);
        return response()->json($Patient);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'Patient_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $Patient = $this->PatientService->createPatient($data);
        return response()->json($Patient, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'client_id' => 'sometimes|exists:clients,id',
            'Patient_date' => 'sometimes|date',
            'notes' => 'nullable|string',
        ]);

        $Patient = $this->PatientService->updatePatient($id, $data);
        return response()->json($Patient);
    }

    public function destroy($id)
    {
        $this->PatientService->deletePatient($id);
        return response()->json(['message' => 'Patient deleted successfully'], 200);
    }
}

