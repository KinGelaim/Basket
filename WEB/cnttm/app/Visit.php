<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
	protected $fillable = [
        'id_journal','id_user','id_state','date'
    ];
}
