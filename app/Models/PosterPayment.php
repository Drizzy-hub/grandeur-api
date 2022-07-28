<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PosterPayment extends Model
{
    use Notifiable;

    protected $guard = "poster";

    protected $fillable = [
        
    ];
}
