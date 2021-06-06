<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComponentElement extends Model
{
    protected $fillable = ['id_counterpartie', 'name_component', 'count_element', 'need_count_element'];
}
