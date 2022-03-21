<?php

namespace App\Http\Controllers;

use Auth;
use App\ReestrSpecify;
use Illuminate\Http\Request;

class ReestrSpecifyController extends Controller
{
    public function store($id_contract, Request $request)
    {
        $specify = new ReestrSpecify();
		$specify->fill($request->all());
		if($request['amount'])
			$specify->amount = str_replace(' ','',$request['amount']);
		if($request['amount_vat'])
			$specify->amount_vat = str_replace(' ','',$request['amount_vat']);
		$specify->id_contract = $id_contract;
		$specify->vat = $request['vat'] ? 1 : 0;
		$specify->save();
		JournalController::store(Auth::User()->id,'Добавлена спецификация для контракта с id = ' . $id_contract);
		return redirect()->back()->with('success', 'Спецификация добавлена!');
    }
	
    public function show($id_contract)
    {
        $specifies = ReestrSpecify::select('*')->where('id_contract', $id_contract)->get();
		foreach($specifies as $specify){
			if(is_numeric($specify->amount))
				$specify->amount = number_format($specify->amount, 2, '.', ' ');
			if(is_numeric($specify->amount_vat))
				$specify->amount_vat = number_format($specify->amount_vat, 2, '.', ' ');
		}
		return view('reestr.specifies', ['id_contract'=>$id_contract, 'specifies'=>$specifies]);
    }
	
    public function update($id_specify, Request $request)
    {
        $specify = ReestrSpecify::findOrFail($id_specify);
		$specify->fill($request->all());
		if($request['amount'])
			$specify->amount = str_replace(' ','',$request['amount']);
		if($request['amount_vat'])
			$specify->amount_vat = str_replace(' ','',$request['amount_vat']);
		$specify->vat = $request['vat'] ? 1 : 0;
		$specify->save();
		JournalController::store(Auth::User()->id,'Обновлена спецификация для контракта с id = ' . $specify->id_contract);
		return redirect()->back()->with('success', 'Спецификация обновлена!');
    }
}