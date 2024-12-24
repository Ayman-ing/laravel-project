<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AppointmentController extends Controller
{
    // Get all appointments
    public function index()
    {
       
        $appointments = Appointment::with('patient')->get();
        return response()->json($appointments);
    }

    // Get a specific appointment by ID
    public function show($id)
    {
        $appointment = Appointment::with('patient')->findOrFail($id);

        return response()->json($appointment);
    }

    // Create a new appointment
    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $appointment = Appointment::create($data);
        return response()->json($appointment, 201);
    }



    // Update an existing appointment
   // Update an existing appointment
public function update(Request $request, $id)
{
    DB::beginTransaction(); // Start a transaction

    try {
        $appointment = Appointment::where('id', $id)->lockForUpdate()->first();

        if (!$appointment) {
            return response()->json(['error' => 'Appointment not found.'], 404);
        }

        $data = $request->validate([
            'patient_id' => 'sometimes|exists:patients,id', // Ensure patient exists
            'appointment_date' => 'sometimes|date|after:now', // Ensure valid date and in the future
            'status' => 'sometimes|in:scheduled,completed,canceled,no_show', // Restrict to allowed statuses
            'reason_for_visit' => 'nullable|string|max:255', // Optional and max length
            'notes' => 'nullable|string|max:500', // Optional and max length
            'total_fee' => 'nullable|numeric|min:0', // Optional and should be positive
            'cancellation_reason' => 'nullable|string|max:255', // Optional and max length
        ]);

        $appointment->update($data); // Update the appointment with validated data

     
        $appointment = Appointment::with('patient')->findOrFail($id);


        DB::commit(); // Commit the transaction

        return response()->json(['message' => 'Appointment updated successfully.', 'appointment' => $appointment], 200);
    } catch (\Exception $e) {
        DB::rollBack(); // Rollback the transaction in case of an error

        return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}


    // Delete an appointment
public function destroy($id)
{
    DB::beginTransaction(); // Start a transaction

    try {
        // Retrieve the appointment with a pessimistic lock
        $appointment = Appointment::where('id', $id)->lockForUpdate()->first();

        if (!$appointment) {
            return response()->json(['error' => 'Appointment not found.'], 404);
        }

        // Proceed to delete the appointment
        $appointment->delete();

        DB::commit(); // Commit the transaction

        return response()->json(['message' => 'Appointment deleted successfully.'], 200);
    } catch (\Exception $e) {
        DB::rollBack(); // Rollback the transaction in case of an error

        return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}

}


