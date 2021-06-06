<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KontrolPeriod extends Model
{
    protected $fillable = [
        'name', 'count_day'
    ];
}
