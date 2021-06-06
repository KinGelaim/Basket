<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SecondDepartmentComment extends Model
{
    protected $fillable = ['id_second_dep_comment','id_month_comment','message_comment','check_comment'];
}
