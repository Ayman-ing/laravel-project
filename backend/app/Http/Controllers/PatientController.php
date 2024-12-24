<?php

namespace App\Http\Controllers;
use App\Models\Patient;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $products = Patient::all();
        return response()->json($products);
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

        $product = Patient::create($validatedData);
        return response()->json($product, 201);
    }

    public function show($id)
    {
        $product = Patient::findOrFail($id);
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = Patient::findOrFail($id);

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

        $product->update($validatedData);
        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Patient::findOrFail($id);
        $product->delete();
        return response()->json(null, 204);
    }
}