<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Obligation extends Model
{
	protected $fillable = ['id_contract', 'date_incoming_application', 'date_outgoing_application', 'date_incoming_complete', 'date_outgoing_complete',
							'date_b_contract', 'date_e_contract', 'cena_contract', 'date_b_complete', 'date_e_complete', 'cena_complete', 'date_b_comment',
							'date_e_comment', 'cena_comment', 'date_complete', 'date_full_complete'];
}
