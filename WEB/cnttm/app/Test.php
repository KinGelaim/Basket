<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
	protected $fillable = [
        'name','name_first_part','name_second_part','name_third_part','name_fourth_part'
    ];
}
