<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'fname',
        'lname',
        'division',
        'contact',
        'email',
        'designation'
    ];
}
