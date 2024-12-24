<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Import the HasFactory trait

class Patient extends Model
{
    use HasFactory;
    protected $fillable = [
        'firstName',
        'lastName',
        'sex',
        'groupS',
        'address',
        'birthDate',
        'civilization',
        'email',
        'phone',
        
    ];
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

}
