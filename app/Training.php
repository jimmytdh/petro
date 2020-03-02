<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $fillable = [
        'name',
        'date_training',
        'hours',
        'deliverable',
        'problem',
        'venue',
    ];
}
