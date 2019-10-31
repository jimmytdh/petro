<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    protected $table = 'monitoring';
    protected $fillable = [
        'participant_id',
        'training_id',
        'cert',
        'self',
        'supervisor'
    ];
}
