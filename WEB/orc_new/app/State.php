<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
	use SoftDeletes;
	
    protected $fillable = ['id_contract','is_work_state','id_protocol','id_user','name_state','comment_state','date_state'];
}
