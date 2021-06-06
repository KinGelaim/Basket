<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComponentsContract extends Model
{
    protected $fillable = ['id_component', 'id_contract', 'count_components'];
}
