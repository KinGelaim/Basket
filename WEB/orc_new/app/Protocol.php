<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Protocol extends Model
{
	protected $fillable = ['id_contract', 'is_protocol', 'is_additional_agreement', 'position_additional_document', 'application_protocol', 
							'name_protocol', 'name_work_protocol', 'date_protocol', 'date_on_first_protocol', 'date_registration_protocol', 'date_signing_protocol', 
							'date_signing_counterpartie_protocol', 'date_signing_counterpartie_additional_agreement', 'date_entry_ento_force_additional_agreement',
							'is_oud', 'is_oud_el', 'is_dep', 'is_dep_el', 
							'date_oud_protocol', 'date_oud_el_protocol', 'date_dep_protocol', 'date_dep_el_protocol', 
							'amount_protocol', 'amount_year_protocol'];
}
