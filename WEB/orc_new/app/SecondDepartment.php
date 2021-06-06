<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecondDepartment extends Model
{
	use SoftDeletes;
	
    protected $table = 'second_department';
	
	protected $fillable = ['id_contract','id_element','id_view_work_elements','count_isp','january','january_check','february','february_check','march','march_check','april','april_check','may','may_check','june','june_check','july','july_check','august','august_check','september','september_check','october','october_check','november','november_check','december','december_check','year'];
}
