<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledEmail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * These attributes can be filled using mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'to', // Email address of the recipient (fillable attribute)
        'to_name', // Name of the recipient (fillable attribute)
        'scheduled_datetime', // Date and time when the email is scheduled to be sent (fillable attribute)
        'message', // Content of the email message (fillable attribute)
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * These attributes are automatically cast to native PHP types.
     *
     * @var array
     */
    protected $casts = [
        'scheduled_datetime' => 'datetime', // Casts `scheduled_datetime` attribute to datetime format
    ];
}
