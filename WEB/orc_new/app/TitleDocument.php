<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TitleDocument extends Model
{
    protected $connection = 'mysql2';
	
	protected $fillable = ['id_counterpartie', 'date_title_document', 'text_title_document', 'type_title_document', 'relevance_document'];
}
