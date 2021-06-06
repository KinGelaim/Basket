<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $connection = 'mysql2';
	
	protected $fillable = ['id_counterpartie','FIO', 'post', 'telephone', 'email'];
}
