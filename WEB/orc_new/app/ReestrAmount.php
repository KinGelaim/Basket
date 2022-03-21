<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReestrAmount extends Model
{
    protected $fillable = ['id_contract', 'name_amount', 'value_amount', 'unit_amount', 'vat_amount', 'approximate_amount', 'fixed_amount'];
}
