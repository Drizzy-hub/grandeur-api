<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PosterUploads extends Model
{
    use Notifiable;

    protected $guard = "poster";

    protected $fillable = [
        'matricNo','email', 'title','desc',
        'deadline','file_name','budget',
        'pay_status', 'isPicked', 'file_id'
    ];


}
