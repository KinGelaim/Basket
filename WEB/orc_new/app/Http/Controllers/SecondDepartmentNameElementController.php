<?php

namespace App\Http\Controllers;

use Auth;
use App\SecondDepartmentNameElement;
use Illuminate\Http\Request;

class SecondDepartmentNameElementController extends Controller
{
    public function index()
    {
        $name_elements = SecondDepartmentNameElement::select()->orderBy('name_element')->get();
        return view('administrator.second_department_name_element.main', ['name_elements'=>$name_elements]);
    }
	
	public function create()
    {
        return view('administrator.second_department_name_element.name_element', ['name_element'=>'']);
    }
	
	public function store(Request $request)
    {
        $name_element = new SecondDepartmentNameElement();
		$name_element->name_element = $request['name_element'];
		$name_element->save();
		JournalController::store(Auth::User()->id,'Добавлен новое наименование для второго отдела с id = ' . $name_element->id);
		return redirect()->route('second_department_name_element.main');
    }
	
	public function edit($id)
    {
        $name_element = SecondDepartmentNameElement::findOrFail($id);
        return view('administrator.second_department_name_element.name_element', ['name_element'=>$name_element]);
    }

    public function update(Request $request, $id)
    {
        $name_element = SecondDepartmentNameElement::findOrFail($id);
		$name_element->name_element = $request['name_element'];
		$name_element->update();
		JournalController::store(Auth::User()->id,'Обновлено наименование для второго отдела с id = ' . $name_element->id);
		return redirect()->route('second_department_name_element.main');
    }

    public function destroy($id)
    {
        $name_element = SecondDepartmentNameElement::findOrFail($id);
		JournalController::store(Auth::User()->id,'Удаление наименования для второго отдела с id = ' . $name_element->id);
		$name_element->delete();
		return redirect()->back();
    }
}
