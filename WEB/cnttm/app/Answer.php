<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
	protected $fillable = [
        'id_user','id_question','answer','check_question'
    ];
}
