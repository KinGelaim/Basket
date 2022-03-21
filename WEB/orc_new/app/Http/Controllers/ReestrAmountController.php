<?php

namespace App\Http\Controllers;

use Auth;
use App\ReestrContract;
use App\ReestrAmount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReestrAmountController extends Controller
{
	// Добавление новой суммы по Д/К
    public function store(Request $request, $id_contract)
    {
		// Валидируем
		$val = Validator::make($request->all(),[
			'name_amount' => 'required',
			'value_amount' => 'required'
		])->validate();
	
		// Получаем договор
		$reestr = ReestrContract::firstOrNew(['id_contract_reestr'=>$id_contract]);
		
		// Создаём сумму
		$amount = new ReestrAmount();
		$amount->id_contract = $id_contract;
		$amount->fill($request->all());
		$amount->value_amount = str_replace(' ','',$amount->value_amount);
		$amount->vat_amount = $request['vat_amount'] ? 1 : 0;
		$amount->approximate_amount = $request['approximate_amount'] ? 1 : 0;
		$amount->fixed_amount = $request['fixed_amount'] ? 1 : 0;
		$all_dirty = JournalController::getMyChanges($amount);
		$amount->save();
		JournalController::store(Auth::User()->id, 'Добавленна сумма для контракта с id = ' . $id_contract . '~' . json_encode($all_dirty));
		
		// Добавляем изменения в договор
		$reestr->fill([
			'amount_reestr' => $request['value_amount'],
			'unit_reestr' => $request['unit_amount'],
			'vat_reestr' => $request['vat_amount'] ? 1 : 0,
			'approximate_amount_reestr' => $request['approximate_amount'] ? 1 : 0,
			'fixed_amount_reestr' => $request['fixed_amount'] ? 1 : 0
		]);
		$reestr->amount_reestr = str_replace(' ','',$reestr->amount_reestr);
		$reestr->save();
		
		return redirect()->back();
    }

	// Обновляем сумму
    public function update(Request $request, $id_amount)
    {
		// Валидируем
		$val = Validator::make($request->all(),[
			'name_amount' => 'required',
			'value_amount' => 'required'
		])->validate();
	
		// Получаем сумму
        $amount = ReestrAmount::findOrFail($id_amount);
		
		// Изменяем сумму
		$amount->fill($request->all());
		$amount->value_amount = str_replace(' ','',$amount->value_amount);
		$amount->vat_amount = $request['vat_amount'] ? 1 : 0;
		$amount->approximate_amount = $request['approximate_amount'] ? 1 : 0;
		$amount->fixed_amount = $request['fixed_amount'] ? 1 : 0;
		$all_dirty = JournalController::getMyChanges($amount);
		$amount->save();
		
		// Находим контракт
		$reestr = ReestrContract::firstOrNew(['id_contract_reestr'=>$amount->id_contract]);
		
		// Исправляем сумму в Д/К
		$all_amount = ReestrAmount::select()->where('id_contract', $amount->id_contract)->get();
		$last_amount = $all_amount[count($all_amount) - 1];
		
		// Вносим изменения в договор
		$reestr->fill([
			'amount_reestr' => $last_amount['value_amount'],
			'unit_reestr' => $last_amount['unit_amount'],
			'vat_reestr' => $last_amount['vat_amount'],
			'approximate_amount_reestr' => $last_amount['approximate_amount'],
			'fixed_amount_reestr' => $last_amount['fixed_amount']
		]);
		$reestr->amount_reestr = str_replace(' ','',$reestr->amount_reestr);
		$all_dirty = JournalController::getMyChanges($reestr, $all_dirty);
		$reestr->save();
		
		// Сохраняем в историю изменения
		JournalController::store(Auth::User()->id, 'Изменёна сумма для контракта с id = ' . $amount->id_contract . '~' . json_encode($all_dirty));
		
		return redirect()->back();
    }

	// Удаляем сумму
    public function destroy($id_amount)
    {
		// Получаем сумму
        $amount = ReestrAmount::findOrFail($id_amount);
		
		// Получаем ID контракта
		$id_contract = $amount->id_contract;
		
		// Удаляем сумму
		$amount->delete();
		
		// Находим контракт
		$reestr = ReestrContract::firstOrNew(['id_contract_reestr'=>$id_contract]);
		
		// Исправляем сумму в Д/К
		$all_amount = ReestrAmount::select()->where('id_contract', $id_contract)->get();
		if (count($all_amount) >= 1)
			$last_amount = $all_amount[count($all_amount) - 1];
		else
			$last_amount = null;
		
		// Вносим изменения в договор
		$reestr->fill([
			'amount_reestr' => $last_amount['value_amount'],
			'unit_reestr' => $last_amount['unit_amount'],
			'vat_reestr' => $last_amount['vat_amount'],
			'approximate_amount_reestr' => $last_amount['approximate_amount'],
			'fixed_amount_reestr' => $last_amount['fixed_amount']
		]);
		$all_dirty = JournalController::getMyChanges($reestr);
		$reestr->save();
		
		// Сохраняем изменения в историю
		JournalController::store(Auth::User()->id, 'Удалена сумма для контракта с id = ' . $id_contract . '~' . json_encode($all_dirty));
		
		return redirect()->back();
    }
}
