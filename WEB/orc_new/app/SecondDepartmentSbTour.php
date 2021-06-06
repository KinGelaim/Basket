<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecondDepartmentSbTour extends Model
{
	use SoftDeletes;
	
	protected $fillable = ['id_contract','id_element','id_view_work_elements','number_duty','date_duty','count_elements','addition_count_elements','id_unit','id_caliber',
							'number_party','date_worked','number_logbook','date_logbook','number_notification','date_notification'];
}
