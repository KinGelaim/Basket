<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReconciliationContract extends Model
{
    protected $fillable = ['id_contract_reconciliation','process_reconciliation','b_date_reconciliation','e_date_reconciliation'];
}
