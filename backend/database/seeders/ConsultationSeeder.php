<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Consultation;

class ConsultationSeeder extends Seeder
{
    public function run()
    {
        // Générer 50 consultations
        Consultation::factory()->count(50)->create();
    }
}