<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReestrDateContract extends Model
{
    protected $fillable = ['id_contract', 'name_date_contract', 'term_date_contract', 'end_date_contract'];
}
