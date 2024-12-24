<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = \App\Models\Appointment::class;

    public function definition()
    {
        return [
            'patient_id' => $this->faker->numberBetween(1, 10), // Placeholder IDs for now
            'appointment_date' => $this->faker->dateTimeBetween('now', '+30 days'),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'in_progress', 'completed', 'canceled', 'no_show']),
      
            'reason_for_visit' => $this->faker->sentence(5),
            'notes' => $this->faker->text(200),
            'total_fee' => $this->faker->randomFloat(2, 50, 500), // Random fee between $50 and $500
            'canceled_at' => null,
            'cancellation_reason' => null,
        ];
    }
}
