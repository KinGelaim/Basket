<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReconciliationProtocol extends Model
{
    protected $fillable = ['id_protocol','id_user','check_reconciliation','date_check_reconciliation','check_agree_reconciliation','date_check_agree_reconciliation'];
}
