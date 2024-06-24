<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormsRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * These attributes can be filled using mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', // First name of the requester (fillable attribute)
        'last_name', // Last name of the requester (fillable attribute)
        'student_id', // Student ID of the requester (fillable attribute)
        'document_type_id' // ID of the document type requested (fillable attribute)
    ];
}
