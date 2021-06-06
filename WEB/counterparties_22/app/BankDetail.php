<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
	protected $fillable = ['id_counterpartie','checking_account','bank_account','correspondent_account','personal_account','bik','bank_ca_pa'];
}
