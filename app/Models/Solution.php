<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Solution extends Model
{
    use Notifiable;

    protected $guard = "poster";

    protected $fillable = [
        'matricNo','email','level','budget',
        'title', 'summary', 'date_upload','deadline','file_id'
    ];
}
