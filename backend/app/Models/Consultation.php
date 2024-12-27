<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $table = 'consultations';
    protected $primaryKey = 'id_cons';

    protected $fillable = [
        'date_cons',
        'diag_cons',
        'medication',
        'patient_id',
        'appointment_id',
    ];

    // Define relationship with Patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Define relationship with Appointment
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

}
