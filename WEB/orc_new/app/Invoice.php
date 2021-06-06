<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
	use SoftDeletes;
	
    protected $fillable = ['id_contract', 'id_name_invoice', 'is_prepayment_invoice', 'number_deed_invoice', 'number_invoice', 'date_invoice', 'amount_p_invoice', 'id_view_invoice', 'name_invoice', 'name_date_invoice', 'amount_invoice', 'date_payment_invoice', 'amount_payment_invoice', 'updated_at', 'created_at'];
}
