<?php

namespace App\Http\Controllers;

use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function index()
    {
        $appointments = $this->appointmentService->getAllAppointments();
        return response()->json($appointments);
    }

    public function show($id)
    {
        $appointment = $this->appointmentService->getAppointmentById($id);
        return response()->json($appointment);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            //'client_id' => 'required|exists:clients,id',
            'appointment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $appointment = $this->appointmentService->createAppointment($data);
        
        return response()->json($appointment, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'client_id' => 'sometimes|exists:clients,id',
            'appointment_date' => 'sometimes|date',
            'notes' => 'nullable|string',
        ]);

        $appointment = $this->appointmentService->updateAppointment($id, $data);
        return response()->json($appointment);
    }

    public function destroy($id)
    {
        $this->appointmentService->deleteAppointment($id);
        return response()->json(['message' => 'Appointment deleted successfully'], 200);
    }
}

