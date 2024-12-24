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

    // Récupérer une consultation par son ID
    public function getConsultationById($id)
    {
        $consultation = Consultation::find($id);

        if (!$consultation) {
            return response()->json(['message' => 'Consultation not found'], 404);
        }

        return response()->json($consultation);
    }

    // Créer une nouvelle consultation
    public function createConsultation(Request $request)
    {
        $validatedData = $request->validate([
            'date_cons' => 'required|date',
            'diag_cons' => 'required|string|max:255',
        ]);

        $consultation = Consultation::create($validatedData);

        return response()->json([
            'message' => 'Consultation created successfully',
            'consultation' => $consultation,
        ], 201);
    }

    // Modifier une consultation existante
    public function editConsultation(Request $request, $id)
    {
        $consultation = Consultation::find($id);

        if (!$consultation) {
            return response()->json(['message' => 'Consultation not found'], 404);
        }

        $validatedData = $request->validate([
            'date_cons' => 'sometimes|date',
            'diag_cons' => 'sometimes|string|max:255',
        ]);

        $consultation->update($validatedData);

        return response()->json([
            'message' => 'Consultation updated successfully',
            'consultation' => $consultation,
        ]);
    }

    // Supprimer une consultation
    public function deleteConsultation($id)
    {
        $consultation = Consultation::find($id);

        if (!$consultation) {
            return response()->json(['message' => 'Consultation not found'], 404);
        }

        $consultation->delete();

        return response()->json(['message' => 'Consultation deleted successfully']);
    }
}
