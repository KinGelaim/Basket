<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecondDepartmentUsTour extends Model
{
	use SoftDeletes;
	
	protected $fillable = ['id_contract','number_duty','date_duty','date_worked','number_help_report','date_help_report'];
}
