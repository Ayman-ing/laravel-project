<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ConsultationFactory extends Factory
{
    /**
     * The name of the corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Consultation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        // Generate an appointment to ensure relationships exist
        $appointment = Appointment::factory()->create();
        $patient = $appointment->patient;

        return [
            'appointment_id' => $appointment->id,
            'patient_id' => $patient->id,
            'date' => $appointment->date, // Inherit from appointment
            'time' => $appointment->time, // Inherit from appointment
            'duration' => $this->faker->numberBetween(15, 60), // Random duration in minutes
            'symptoms' => $this->faker->sentence(6), // Random symptoms
            'diagnosis' => $this->faker->sentence(3), // Random diagnosis
            'treatment_plan' => $this->faker->paragraph(2), // Random treatment plan
            'prescription' => $this->faker->sentence(5), // Random prescription
            'test_results' => $this->faker->sentence(6), // Random test results
            'referrals' => $this->faker->sentence(4), // Random referrals
            'consultation_notes' => $this->faker->paragraph(3), // Random notes
        ];
    }
}
