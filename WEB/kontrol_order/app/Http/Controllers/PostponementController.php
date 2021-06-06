<?php

namespace App\Http\Controllers;

use Auth;
use App\Postponement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostponementController extends Controller
{
    public function store(Request $request, $id_order)
	{
		$val = Validator::make($request->all(),[
			'date_service' => 'required',
			'date_postponement' => 'required'
		])->validate();
		$postponement = new Postponement();
		$postponement->fill([
						'id_order' => $id_order,
						'date_service' => $request['date_service'] ? date('Y-m-d', strtotime($request['date_service'])) : null,
						'date_postponement' => $request['date_postponement'] ? date('Y-m-d', strtotime($request['date_postponement'])) : null
		]);
		$all_dirty = JournalController::getMyChanges($postponement);
		$postponement->save();
		JournalController::store(Auth::User()->id,'Создание переноса срока для приказа с id = ' . $id_order . '~' . json_encode($all_dirty));
		return redirect()->route('orders.show_order', $id_order)->with(['success'=>'Перенос сроков сохранен!','del_history'=>'1']);
	}
}
