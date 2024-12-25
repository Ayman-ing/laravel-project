<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Appointment extends Model
{
    use HasFactory;
    protected $table = 'appointments';

    protected $fillable = [
        'patient_id',
        'date',
        'time',
        'status',
        'reason_for_visit',
        'notes',
        'total_fee',
        'canceled_at',
        'cancellation_reason',
    ];

    
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Get the formatted date
    public function getFormattedDateAttribute()
    {
        return $this->appointment_date->format('d/m/Y H:i');
    }

    // Get only the time
    public function getAppointmentTimeAttribute()
    {
        return $this->appointment_date->format('H:i');
    }


}
