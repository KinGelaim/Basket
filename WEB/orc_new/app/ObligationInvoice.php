<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ObligationInvoice extends Model
{
	protected $fillable = ['id_contract', 'type_invoice', 'name_organization', 'type_incoming_obligation', 'number_pp', 'date_pp', 'amount'];
}
