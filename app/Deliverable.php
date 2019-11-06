<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deliverable extends Model
{
    protected $table = 'deliverable';
    protected $fillable = [
        'name',
        'target',
        'target_month',
        'target_year'
    ];
}
