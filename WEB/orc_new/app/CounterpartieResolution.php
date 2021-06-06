<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CounterpartieResolution extends Model
{
    protected $connection = 'mysql2';
	
	protected $fillable = ['id_title_document', 'real_name_resolution', 'path_resolution', 'date_resolution'];
}
