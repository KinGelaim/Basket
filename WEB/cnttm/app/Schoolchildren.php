<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schoolchildren extends Model
{
	protected $fillable = [
        'id_user','id_group','is_complete'
    ];
}
