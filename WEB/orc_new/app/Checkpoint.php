<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checkpoint extends Model
{
    protected $fillable = ['id_contract', 'date_checkpoint', 'message_checkpoint', 'check_checkpoint'];
}
