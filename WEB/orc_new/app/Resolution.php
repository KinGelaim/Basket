<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resolution extends Model
{
    protected $fillable = ['id_user', 'id_document_resolution', 'id_contract_resolution', 'id_application_resolution', 'id_protocol_resolution', 'real_name_resolution', 'path_resolution', 'date_resolution', 'type_resolution', 'deleted_at'];
}
