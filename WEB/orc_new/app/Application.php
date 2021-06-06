<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
	use SoftDeletes;
	
    protected $fillable = ['id_document_application','id_contract_application','id_counterpartie_application','number_application','date_application','number_outgoing','date_outgoing','number_incoming','date_incoming','directed_application','date_directed','theme_application','is_protocol', 'name_protocol', 'comment_application','executor'];
}
