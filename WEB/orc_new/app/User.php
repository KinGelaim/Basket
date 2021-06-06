<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
	use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'surname', 'name', 'patronymic', 'role', 'position_department', 'login', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	public function hasRole()
	{
		return DB::SELECT('SELECT role FROM roles WHERE id=:id',['id'=>$this->role])[0];
	}
	
	public static function getAllFIO()
	{
		return User::select(['id','surname','name','patronymic'])->orderBy('surname','asc')->get();
	}
	
	public static function getAllLogins()
	{
		return User::select(['login'])->orderBy('login','asc')->get();
	}
}
