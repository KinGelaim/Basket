<?php

namespace App\Http\Controllers;

//use App\Contract;
use Auth;
use App\State;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function set_cookie()
	{
		if(isset($_GET['setCookie'])){
			setcookie("TenCookie", $_GET['setCookie'], time()+3600);
			return 'true';
		}
		return 'false';
	}
	
	public function set_complete_contract($id_contract)
	{
		if(isset($_GET['isImplementation'])){
			if($_GET['isImplementation'] == 'true'){
				$state = new State();
				$state->fill(['id_contract' => $id_contract,
							'id_user' => Auth::User()->id,
							'name_state' => 'Заключён',
							'date_state' => date('d.m.Y', time()),
				]);
				$state->save();
				JournalController::store(Auth::User()->id,'Добавил новое состояние для контракста с id = ' . $id_contract);
			} else {
				$states = State::select('*')->where('id_contract', $id_contract)->where('name_state', 'Заключен')->delete();
			}
			return 'true';
		}
		return 'false';
	}
}
