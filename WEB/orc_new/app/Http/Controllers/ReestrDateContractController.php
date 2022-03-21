<?php

namespace App\Http\Controllers;

use Auth;
use App\ReestrContract;
use App\ReestrDateContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReestrDateContractController extends Controller
{
	// Добавление нового срока действия Д/К
    public function store(Request $request, $id_contract)
    {
		// Валидируем
		$val = Validator::make($request->all(),[
			'name_date_contract' => 'required',
			'term_date_contract' => 'required',
			'end_date_contract' => 'nullable|date'
		])->validate();
	
		// Получаем договор
		$reestr = ReestrContract::firstOrNew(['id_contract_reestr'=>$id_contract]);
		
		// Создаём срок действия Д/К
		$date_contract = new ReestrDateContract();
		$date_contract->id_contract = $id_contract;
		$date_contract->fill($request->all());
		$all_dirty = JournalController::getMyChanges($date_contract);
		$date_contract->save();
		JournalController::store(Auth::User()->id, 'Добавлен срок действия для контракта с id = ' . $id_contract . '~' . json_encode($all_dirty));
		
		// Добавляем изменения в договор
		$reestr->fill([
			'date_contract_reestr' => $request['term_date_contract'],
			'date_e_contract_reestr' => $request['end_date_contract']
		]);
		$reestr->save();
		
		return redirect()->back();
    }

	// Обновляем срок действия Д/К
    public function update(Request $request, $id_date_contract)
    {
		// Валидируем
		$val = Validator::make($request->all(),[
			'name_date_contract' => 'required',
			'term_date_contract' => 'required',
			'end_date_contract' => 'nullable|date'
		])->validate();
	
		// Получаем срок действия Д/К
        $date_contract = ReestrDateContract::findOrFail($id_date_contract);
		
		// Изменяем срок действия
		$date_contract->fill($request->all());
		$all_dirty = JournalController::getMyChanges($date_contract);
		$date_contract->save();
		
		// Находим контракт
		$reestr = ReestrContract::firstOrNew(['id_contract_reestr'=>$date_contract->id_contract]);
		
		// Исправляем срок действия Д/К в договоре для этого находим последний срок действия
		$all_date_contract = ReestrDateContract::select()->where('id_contract', $date_contract->id_contract)->get();
		$last_date_contract = $all_date_contract[count($all_date_contract) - 1];
		
		// Вносим изменения в договор
		$reestr->fill([
			'date_contract_reestr' => $last_date_contract['term_date_contract'],
			'date_e_contract_reestr' => $last_date_contract['end_date_contract']
		]);
		$all_dirty = JournalController::getMyChanges($reestr, $all_dirty);
		$reestr->save();
		
		// Сохраняем в историю изменения
		JournalController::store(Auth::User()->id, 'Изменён срок действия для контракта с id = ' . $date_contract->id_contract . '~' . json_encode($all_dirty));
		
		return redirect()->back();
    }

	// Удаляем срок действия Д/К
    public function destroy($id_date_contract)
    {
		// Получаем срок действия Д/К
        $date_contract = ReestrDateContract::findOrFail($id_date_contract);
		
		// Получаем ID контракта
		$id_contract = $date_contract->id_contract;
		
		// Удаляем срок действия
		$date_contract->delete();
		
		// Находим контракт
		$reestr = ReestrContract::firstOrNew(['id_contract_reestr'=>$id_contract]);
		
		// Исправляем срок действия Д/К в договоре для этого находим последний срок действия
		$all_date_contract = ReestrDateContract::select()->where('id_contract', $id_contract)->get();
		if (count($all_date_contract) >= 1)
			$last_date_contract = $all_date_contract[count($all_date_contract) - 1];
		else
			$last_date_contract = null;
		
		// Вносим изменения в договор
		$reestr->fill([
			'date_contract_reestr' => $last_date_contract['term_date_contract'],
			'date_e_contract_reestr' => $last_date_contract['end_date_contract']
		]);
		$all_dirty = JournalController::getMyChanges($reestr);
		$reestr->save();
		
		// Сохраняем в историю изменения
		JournalController::store(Auth::User()->id, 'Удален срок действия для контракта с id = ' . $id_contract . '~' . json_encode($all_dirty));
		
		return redirect()->back();
    }
}
