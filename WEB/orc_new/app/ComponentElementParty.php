<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComponentElementParty extends Model
{
    protected $fillable = ['id_element', 'name_party', 'date_party', 'count_party'];
}
