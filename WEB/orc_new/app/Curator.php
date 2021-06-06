<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curator extends Model
{
	use SoftDeletes;
	
    protected $connection = 'mysql2';
	
	protected $fillable = ['id_user', 'FIO', 'telephone', 'id_department'];
}
