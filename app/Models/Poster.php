<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Poster extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $guard = 'poster';

    protected $fillable = [
        'fname', 'lname', 'matricNo', 
        'email', 'password', 'phoneNum',
        'school', 'depart', 'level', 'avail',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     * 
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any cudtom claims to be added to the JWT.
     * 
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }
}
