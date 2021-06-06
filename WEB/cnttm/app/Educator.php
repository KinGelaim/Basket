<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Educator extends Model
{
    protected $fillable = ['id_user','position','photo','short_information','full_information'];
}
