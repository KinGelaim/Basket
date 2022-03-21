<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReconciliationUser extends Model
{
    protected $fillable = ['id_new_application', 'id_application', 'is_protocol', 'is_document', 'id_contract', 'id_user', 'check_reconciliation', 
							'date_check_reconciliation', 'check_agree_reconciliation', 'date_check_agree_reconciliation'];
}
