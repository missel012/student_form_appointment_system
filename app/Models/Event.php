<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * These attributes can be filled using mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'appointment_for', // Name of the appointment (fillable attribute)
        'appointment_datetime', // Date and time of the appointment (fillable attribute)
        'google_calendar_event_id', // ID of the Google Calendar event (fillable attribute)
        'email' // Email associated with the event (fillable attribute)
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * These attributes will be automatically casted to the specified types.
     *
     * @var array
     */
    protected $casts = [
        'appointment_datetime' => 'datetime:Y-m-d\TH:i:sP', // Casts appointment_datetime to datetime format
    ];
}
