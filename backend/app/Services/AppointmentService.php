<?php
namespace App\Services;

use App\Repositories\AppointmentRepositoryInterface;

class AppointmentService
{
    protected $appointmentRepository;

    public function __construct(AppointmentRepositoryInterface $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    public function getAllAppointments()
    {
        return $this->appointmentRepository->getAllAppointments();
    }

    public function getAppointmentById($id)
    {
        return $this->appointmentRepository->getAppointmentById($id);
    }

    public function createAppointment(array $data)
    {
        return $this->appointmentRepository->createAppointment($data);
    }

    public function updateAppointment($id, array $data)
    {
        return $this->appointmentRepository->updateAppointment($id, $data);
    }

    public function deleteAppointment($id)
    {
        return $this->appointmentRepository->deleteAppointment($id);
    }
}
