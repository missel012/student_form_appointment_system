<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormsRequest extends Model
{
    // Specify the attributes that are mass assignable
    protected $fillable = [
        'first_name', 'last_name', 'student_id', 'document_type_id', 'status'
    ];
}
