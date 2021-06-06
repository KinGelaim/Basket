<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Postponement extends Model
{
	protected $fillable = ['id_order', 'date_service', 'date_postponement'];
}
