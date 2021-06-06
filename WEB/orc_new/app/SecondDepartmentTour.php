<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecondDepartmentTour extends Model
{
	use SoftDeletes;
	
	protected $fillable = ['id_contract','id_element','id_view_work_elements','id_caliber','id_name_element','id_result','number_duty','date_duty','date_incoming','count_elements',
							'id_unit','countable','targeting','warm','uncountable',
							'renouncement','date_worked','number_telegram','date_telegram','number_report','date_report','number_act','date_act'];
}
