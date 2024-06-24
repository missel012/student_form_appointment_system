<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable; // Import Authenticatable trait
use Laravel\Lumen\Auth\Authorizable; // Import Authorizable trait
use Illuminate\Database\Eloquent\Model; // Import Eloquent Model class
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract; // Import Authenticatable contract
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract; // Import Authorizable contract
use Tymon\JWTAuth\Contracts\JWTSubject; // Import JWTSubject contract

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject 
{
    use Authenticatable, Authorizable; // Use Authenticatable and Authorizable traits

    /**
     * The attributes that are mass assignable.
     *
     * These attributes can be filled using mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'name', // User's name (fillable attribute)
        'email', // User's email address (fillable attribute)
        'password', // User's password (fillable attribute)
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * These attributes are hidden when converting the model to an array or JSON response.
     *
     * @var array
     */
    protected $hidden = [
        'password', // Hide user's password
        'remember_token', // Hide remember token (if applicable)
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Return the user's primary key as JWT identifier
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return []; // Return an empty array for custom JWT claims
    }
}
