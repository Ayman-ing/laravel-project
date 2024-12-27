<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    protected $model = \App\Models\Patient::class;

    public function definition(): array
    {
        return [
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'sex' => $this->faker->randomElement(['Male', 'Female']),
            'groupS' => $this->faker->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'address' => $this->faker->address,
            'civilization' => $this->faker->randomElement(['Single', 'Married', 'Divorced', 'Widowed']),
            'birthDate' => $this->faker->date('Y-m-d', '2005-01-01'), // Random date of birth before 2005
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
        ];
    }
}
