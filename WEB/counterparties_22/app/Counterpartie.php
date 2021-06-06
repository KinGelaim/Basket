<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Counterpartie extends Model
{	
	protected $fillable = ['code','name','name_full','curator','dishonesty','lider','legal_address',
							'mailing_address','actual_address','telephone','email','inn','ogrn','kpp','okpo','okved',
							'statement_egryl','order_counterpartie','protocol_meeting','statement_egrip','statement_charter',
							'map','copy_passport','statement_rosstata','proxys','licences','certificates','other_documents',
							'number_file'];
}
