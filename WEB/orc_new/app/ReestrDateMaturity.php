<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReestrDateMaturity extends Model
{
    protected $fillable = ['id_contract', 'name_date_maturity', 'term_date_maturity', 'end_date_maturity'];
}
