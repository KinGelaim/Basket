<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['author', 'id_new_application', 'id_application', 'is_protocol', 'is_document', 'id_contract', 'message'];
}
