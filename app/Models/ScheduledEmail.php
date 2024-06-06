<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledEmail extends Model
{
    protected $fillable = [
        'to', 'to_name', 'scheduled_datetime', 'message',
    ];

    protected $casts = [
        'scheduled_datetime' => 'datetime',
    ];
}
