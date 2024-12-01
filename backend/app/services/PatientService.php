<?php
namespace App\Services;

use App\Repositories\PatientRepositoryInterface;

class PatientService
{
    protected $PatientRepository;

    public function __construct(PatientRepositoryInterface $PatientRepository)
    {
        $this->PatientRepository = $PatientRepository;
    }

    public function getAllPatients()
    {
        return $this->PatientRepository->getAllPatients();
    }

    public function getPatientById($id)
    {
        return $this->PatientRepository->getPatientById($id);
    }

    public function createPatient(array $data)
    {
        return $this->PatientRepository->createPatient($data);
    }

    public function updatePatient($id, array $data)
    {
        return $this->PatientRepository->updatePatient($id, $data);
    }

    public function deletePatient($id)
    {
        return $this->PatientRepository->deletePatient($id);
    }
}
