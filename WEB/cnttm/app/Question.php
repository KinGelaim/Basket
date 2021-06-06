<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
	protected $fillable = [
        'id_test','part','position','question','description','id_view','first_answer','second_answer','third_answer','fourth_answer','finally_answer'
    ];
}
