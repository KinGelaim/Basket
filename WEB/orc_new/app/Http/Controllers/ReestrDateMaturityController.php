<?php

namespace App\Http\Controllers;

use Auth;
use App\ReestrContract;
use App\ReestrDateMaturity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReestrDateMaturityController extends Controller
{
	// Добавление нового срока исполнения обязательств
    public function store(Request $request, $id_contract)
    {
		// Валидируем
		$val = Validator::make($request->all(),[
			'name_date_maturity' => 'required',
			'term_date_maturity' => 'required',
			'end_date_maturity' => 'nullable|date'
		])->validate();
	
		// Получаем договор
		$reestr = ReestrContract::firstOrNew(['id_contract_reestr'=>$id_contract]);
		
		// Создаём срок исполнения обязательств
		$date_maturity = new ReestrDateMaturity();
		$date_maturity->id_contract = $id_contract;
		$date_maturity->fill($request->all());
		$all_dirty = JournalController::getMyChanges($date_maturity);
		$date_maturity->save();
		JournalController::store(Auth::User()->id, 'Добавлен срок исполнения обязательств для контракта с id = ' . $id_contract . '~' . json_encode($all_dirty));
		
		// Добавляем изменения в договор
		$reestr->fill([
			'date_maturity_reestr' => $request['term_date_maturity'],
			'date_e_maturity_reestr' => $request['end_date_maturity']
		]);
		$reestr->save();
		
		return redirect()->back();
    }

	// Обновляем срок исполнения обязательств
    public function update(Request $request, $id_date_contract)
    {
		// Валидируем
		$val = Validator::make($request->all(),[
			'name_date_maturity' => 'required',
			'term_date_maturity' => 'required',
			'end_date_maturity' => 'nullable|date'
		])->validate();
	
		// Получаем срок исполнения обязательств
        $date_maturity = ReestrDateMaturity::findOrFail($id_date_contract);
		
		// Изменяем срок исполнения обязательств
		$date_maturity->fill($request->all());
		$all_dirty = JournalController::getMyChanges($date_maturity);
		$date_maturity->save();
		
		// Находим контракт
		$reestr = ReestrContract::firstOrNew(['id_contract_reestr'=>$date_maturity->id_contract]);
		
		// Исправляем срок исполнения обязательств в Д/К
		$all_date_maturity = ReestrDateMaturity::select()->where('id_contract', $date_maturity->id_contract)->get();
		$last_date_maturity = $all_date_maturity[count($all_date_maturity) - 1];
		
		// Вносим изменения в договор
		$reestr->fill([
			'date_maturity_reestr' => $last_date_maturity['term_date_maturity'],
			'date_e_maturity_reestr' => $last_date_maturity['end_date_maturity']
		]);
		$all_dirty = JournalController::getMyChanges($reestr, $all_dirty);
		$reestr->save();
		
		// Сохраняем в историю изменения
		JournalController::store(Auth::User()->id, 'Изменён срок исполнения обязательств для контракта с id = ' . $date_maturity->id_contract . '~' . json_encode($all_dirty));
		
		return redirect()->back();
    }

	// Удаляем срок исполнения обязательств
    public function destroy($id_date_contract)
    {
		// Получаем срок исполнения обязательств
        $date_maturity = ReestrDateMaturity::findOrFail($id_date_contract);
		
		// Получаем ID контракта
		$id_contract = $date_maturity->id_contract;
		
		// Удаляем срок исполнения обязательств
		$date_maturity->delete();
		
		// Находим контракт
		$reestr = ReestrContract::firstOrNew(['id_contract_reestr'=>$id_contract]);
		
		// Исправляем срок действия Д/К в договоре для этого находим последний срок действия
		$all_date_maturity = ReestrDateMaturity::select()->where('id_contract', $id_contract)->get();
		if (count($all_date_maturity) >= 1)
			$last_date_maturity = $all_date_maturity[count($all_date_maturity) - 1];
		else
			$last_date_maturity = null;
		
		// Вносим изменения в договор
		$reestr->fill([
			'date_maturity_reestr' => $last_date_maturity['term_date_maturity'],
			'date_e_maturity_reestr' => $last_date_maturity['end_date_maturity']
		]);
		$all_dirty = JournalController::getMyChanges($reestr);
		$reestr->save();
		
		// Сохраняем в историю изменения
		JournalController::store(Auth::User()->id, 'Удален срок исполнения обязательств для контракта с id = ' . $id_contract . '~' . json_encode($all_dirty));
		
		return redirect()->back();
    }
}
