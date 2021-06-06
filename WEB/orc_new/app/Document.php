<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
	use SoftDeletes;
	
	protected $fillable = ['id_application_document','id_old_document','date_document'];
}
