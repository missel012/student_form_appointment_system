<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'appointment_for', 'appointment_datetime', 'google_calendar_event_id', 'email'
    ];
    
    protected $casts = [
        'appointment_datetime' => 'datetime:Y-m-d\TH:i:sP',
    ];
}
