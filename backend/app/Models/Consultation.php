<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Consultation extends Model
{
    use HasFactory;
    protected $fillable = [
        'appointment_id',
        'patient_id',
        'date',
        'time',
        'duration',
        'symptoms',
        'diagnosis',
        'treatment_plan',
        'prescription',
        'test_results',
        'referrals',
        'consultation_notes',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}

