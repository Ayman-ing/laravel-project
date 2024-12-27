<?php

namespace Database\Factories;

use App\Models\Consultation;
use App\Models\Patient; // Importer Patient
use App\Models\Appointment; // Importer Appointment
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Consultation>
 */
class ConsultationFactory extends Factory
{
    protected $model = \App\Models\Consultation::class;

    public function definition(): array
    {
        return [
            'date_cons' => $this->faker->dateTimeBetween('-1 year', 'now'), // Générer une date aléatoire
            'diag_cons' => $this->faker->sentence(), // Générer une phrase aléatoire pour le diagnostic
            'medication' => $this->faker->words(3, true), // Générer des mots aléatoires pour le médicament
            'patient_id' => Patient::factory(), // Associer à un patient existant ou nouvellement créé
            'appointment_id' => Appointment::factory(), // Associer à un rendez-vous existant ou nouvellement créé
        ];
    }
}
