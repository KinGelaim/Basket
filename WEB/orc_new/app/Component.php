<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $fillable = ['id_contract', 'id_pack', 'id_element_component', 'id_party', 'need_count'];
}
