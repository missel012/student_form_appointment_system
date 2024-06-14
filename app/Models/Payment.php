<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'email', 'amount', 'payment_mode', 'payer_name', 'transaction_id'
    ];

    // Optionally, define timestamps if they are not auto-handled by Laravel
    public $timestamps = false;
}
