<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Validator;

class PosterRefund extends Model
{
    use Notifiable;

    protected $guard = "poster";

    protected $fillable = [
        'matricNo', 'email',
        'subject', 'file_id','status',
        'paycode', 'amount','desc'
    ];
}
