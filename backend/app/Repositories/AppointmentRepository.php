<?php
namespace App\Repositories;

use App\Models\Appointment;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    public function getAllAppointments()
    {
        return Appointment::all();
    }

    public function getAppointmentById($id)
    {
        return Appointment::findOrFail($id);
    }

    public function createAppointment(array $data)
    {
        return Appointment::create($data);
    }

    public function updateAppointment($id, array $data)
    {
        $appointment = $this->getAppointmentById($id);
        $appointment->update($data);
        return $appointment;
    }

    public function deleteAppointment($id)
    {
        $appointment = $this->getAppointmentById($id);
        return $appointment->delete();
    }
}

