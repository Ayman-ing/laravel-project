<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultation;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;


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
            'date' => 'required|date', 
            'time' => 'required|date_format:H:i', 
            'duration' => 'nullable|integer|min:1', 
            'symptoms' => 'nullable|string',
            'diagnosis' => 'required|string|max:255',
            'treatment_plan' => 'nullable|string',
            'prescription' => 'nullable|string|max:255',
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'required|exists:appointments,id',
        ]);

        $consultation = Consultation::create($validatedData);

        return response()->json([
            'message' => 'Consultation created successfully',
            'consultation' => $consultation,
        ], 201);
    }


    public function update(Request $request, $id)
    {
        DB::beginTransaction(); // Start a transaction
    
        try {
            // Lock the consultation record for update
            $consultation = Consultation::where('id', $id)->lockForUpdate()->first();
    
            if (!$consultation) {
                return response()->json(['error' => 'Consultation not found.'], 404);
            }
    
            // Validate the request data
            $data = $request->validate([
                'date' => 'sometimes|date|after_or_equal:today',
                'time' => 'sometimes|date_format:H:i', 
                'duration' => 'nullable|integer|min:1', 
                'symptoms' => 'nullable|string|max:255', 
                'diagnosis' => 'sometimes|string|max:255',
                'treatment_plan' => 'nullable|string', 
                'prescription' => 'nullable|string|max:255', 
                'test_results' => 'nullable|string', 
                'referrals' => 'nullable|string',
                'consultation_notes' => 'nullable|string|max:500', 
                'patient_id' => 'sometimes|exists:patients,id', 
                'appointment_id' => 'sometimes|exists:appointments,id', 
            ]);
    
            $consultation->update($data);
    
            // Reload consultation with relations for the response
            $consultation = Consultation::with(['patient', 'appointment'])->findOrFail($id);
    
            DB::commit(); // Commit the transaction
    
            return response()->json([
                'message' => 'Consultation updated successfully.',
                'consultation' => $consultation,
            ], 200); // 200 for successful update
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack(); // Rollback the transaction for validation errors
            return response()->json(['error' => 'Validation error.', 'details' => $e->errors()], 422); // 422 for validation errors
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction for general errors
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500); // 500 for server errors
        }
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