<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Counterpartie extends Model
{
	use SoftDeletes;
	
    protected $connection = 'mysql2';
	
	protected $table = 'contr';
	
	protected $fillable = ['name','name_full','curator','is_sip_counterpartie','dishonesty','lider','legal_address',
							'mailing_address','actual_address','telephone','email','inn','ogrn','kpp','okpo','okved',
							'statement_egryl','order_counterpartie','protocol_meeting','statement_egrip','statement_charter',
							'map','copy_passport','statement_rosstata','proxys','licences','certificates','other_documents',
							'number_file'];
}
