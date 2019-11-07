<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deliverable extends Model
{
    protected $table = 'deliverable';
    protected $fillable = [
        'name',
        'division',
        'target',
        'target_date'
    ];
}
