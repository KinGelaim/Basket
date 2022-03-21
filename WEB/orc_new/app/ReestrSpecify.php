<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReestrSpecify extends Model
{
    protected $fillable = ['id_contract','number', 'date', 'amount', 'amount_vat','vat','comment'];
}
