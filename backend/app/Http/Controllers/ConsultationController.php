<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultation;

class ConsultationController extends Controller
{
    // List consultations with pagination
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10); // Default to 10 items per page
        $consultations = Consultation::paginate($perPage);

        return response()->json($consultations);
    }

    // Retrieve a consultation by ID
    public function show($id)
    {
        $consultation = Consultation::find($id);

        if (!$consultation) {
            return response()->json(['message' => 'Consultation not found'], 404);
        }

        return response()->json($consultation);
    }

    // Create a new consultation
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date_cons' => 'required|date',
            'diag_cons' => 'required|string|max:255',
            'medication' => 'nullable|string|max:255',
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'required|exists:appointments,id',
        ]);

        $consultation = Consultation::create($validatedData);

        return response()->json([
            'message' => 'Consultation created successfully',
            'consultation' => $consultation,
        ], 201);
    }

    // Update an existing consultation with locking
    public function update(Request $request, $id)
    {
        $consultation = Consultation::find($id);

        if (!$consultation) {
            return response()->json(['message' => 'Consultation not found'], 404);
        }

        // Lock the record for update
        $consultation->lockForUpdate();

        $validatedData = $request->validate([
            'date_cons' => 'sometimes|date',
            'diag_cons' => 'sometimes|string|max:255',
            'medication' => 'sometimes|string|max:255',
            'patient_id' => 'sometimes|exists:patients,id',
            'appointment_id' => 'sometimes|exists:appointments,id',
        ]);

        $consultation->update($validatedData);

        return response()->json([
            'message' => 'Consultation updated successfully',
            'consultation' => $consultation,
        ]);
    }

    // Delete a consultation with locking
    public function destroy($id)
    {
        $consultation = Consultation::find($id);

        if (!$consultation) {
            return response()->json(['message' => 'Consultation not found'], 404);
        }

        // Lock the record for deletion
        $consultation->lockForUpdate();

        $consultation->delete();

        return response()->json(['message' => 'Consultation deleted successfully']);
    }
}