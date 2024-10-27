<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    

    protected $table = 'appointments';
    //protected $primaryKey = 'client_id';
    protected $fillable = ['appointment_date', 'notes'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
