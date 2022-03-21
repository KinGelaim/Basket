<?php

namespace App\Http\Controllers;

use Auth;
use App\NewApplicationContraction;
use Illuminate\Http\Request;

class NewApplicationContractionController extends Controller
{
    public function store($id_new_application, Request $request)
    {
        $newApplicationContraction = new NewApplicationContraction();
		$newApplicationContraction->id_new_application = $id_new_application;
		$newApplicationContraction->fill($request->all());
		$newApplicationContraction->save();
		JournalController::store(Auth::User()->id,'Добавлено заключение для заявки с id = ' . $id_new_application);
		return redirect()->back()->with('success', 'Заключение добавлено!');
    }
	
    public function update($id_new_application_contration, Request $request)
    {
		
		//JournalController::store(Auth::User()->id,'Обновлена спецификация для контракта с id = ' . $specify->id_contract);
		//return redirect()->back()->with('success', 'Спецификация обновлена!');
    }
	
	public function destroy($id_new_application_contration)
	{
		$newApplicationContraction = NewApplicationContraction::findOrFail($id_new_application_contration);
		$newApplicationContraction->delete();
		return redirect()->back()->with('success', 'Заключение удалено!');
	}
}
