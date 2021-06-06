<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resolution extends Model
{
    protected $fillable = ['id_user', 'id_order', 'real_name_resolution', 'path_resolution', 'date_resolution', 'deleted_at' ];
}
