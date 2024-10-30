<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
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
}
