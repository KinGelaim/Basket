<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolchildrenTest extends Model
{
	protected $fillable = [
        'id_schoolchildren','id_test','first_part','second_part','third_part','fourth_part'
    ];
}
