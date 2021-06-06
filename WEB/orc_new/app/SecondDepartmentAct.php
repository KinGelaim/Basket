<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SecondDepartmentAct extends Model
{
	protected $fillable = ['id_second_tour','id_second_sb_tour','id_second_us_tour','id_contract','number_act','date_act','amount_act'];
}
