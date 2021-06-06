<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['author', 'id_application', 'is_protocol', 'is_document', 'id_contract', 'message'];
}
