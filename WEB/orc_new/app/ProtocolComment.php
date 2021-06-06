<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProtocolComment extends Model
{
    protected $fillable = ['author', 'id_protocol', 'message'];
}
