<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewApplicationContraction extends Model
{
    protected $fillable = ['id_new_application', 'department', 'executor', 'result', 'date', 'FIO'];
}
