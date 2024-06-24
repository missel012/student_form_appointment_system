<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * These attributes can be filled using mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'email', // Email of the payer (fillable attribute)
        'amount', // Amount of the payment (fillable attribute)
        'payment_mode', // Mode of payment (fillable attribute)
        'payer_name', // Name of the payer (fillable attribute)
        'transaction_id' // Unique transaction ID (fillable attribute)
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * Laravel Eloquent models by default expect the `created_at` and `updated_at`
     * columns to exist on your tables. If you wish to disable this behaviour,
     * set `$timestamps` to `false`.
     *
     * @var bool
     */
    public $timestamps = false;
}
