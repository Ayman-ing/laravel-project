<?php

namespace App\Http\Controllers;
use App\Models\Patient;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientController extends Controller
{
   
    public function index(Request $request)
    {
        $query = Patient::query();
        $allowedSortFields = ["firstName", "lastName","sex","address","birthDate","email","phone"];
        $sortField = $request->get('sortField', null); // Get the sortField from the request
        $sortOrder = $request->get('sortOrder', 'asc'); // Default to ascending order
        if (in_array($sortField, $allowedSortFields)) {
            // Sort by the requested field
            $query->orderBy($sortField, $sortOrder);
        } else {
            // Default sorting by date and then time
            $query->orderBy("id", "asc");
        }
        return $query->paginate($request->get('rows', 5));
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'firstName' => 'required|max:255',
            'lastName' => 'required|max:255',
            'sex' => 'required|max:255',
            'groupS' => 'nullable',
            'address' => 'required|max:255',
            'civilization' => 'required|max:255',
            'birthDate' => 'required',
            'email' => 'required',
            'phone' => 'required',
            
        ]);

        $patient = Patient::create($validatedData);
        return response()->json($patient, 201);
    }

    public function show($id)
    {
        $patient = Patient::findOrFail($id);
        return response()->json($patient);
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $validatedData = $request->validate([
            'firstName' => 'required|max:255',
            'lastName' => 'required|max:255',
            'sex' => 'required|max:255',
            'groupS' => 'nullable',
            'address' => 'required|max:255',
            'civilization' => 'required|max:255',
            'birthDate' => 'required',
            'email' => 'required',
            'phone' => 'required',
            
        ]);

        $patient->update($validatedData);
        return response()->json($patient);
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();
        return response()->json(null, 204);
    }
    public function patientNames()
    {
        $patients = Patient::select(['id', 'firstName', 'lastName'])->get(); // Use 'select' to specify columns
        return response()->json($patients);
    }
    
}
