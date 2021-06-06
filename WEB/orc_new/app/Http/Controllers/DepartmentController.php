<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::select(['departments.id','index_department','name_department','lider_department','users.surname','users.name','users.patronymic','users.position_department'])->leftjoin('users','users.id','departments.lider_department')->get();
        return view('administrator.departments.main', ['departments'=>$departments]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$users = User::select(['id', 'surname', 'name', 'patronymic'])->orderBy('surname', 'asc')->get();
        return view('administrator.departments.view', ['department'=>'','users'=>$users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$val = Validator::make($request->all(),[
			'index_department' => 'required',
			'name_department' => 'required'
		])->validate();
        $department = Department::create([
			'index_department' => $request['index_department'],
            'name_department' => $request['name_department'],
			'lider_department' => $request['lider_department']
        ]);
		JournalController::store(Auth::User()->id,'Создание нового подразделения с id = ' . $department->id);
		return redirect()->back()->with('success','Добавлено новое подразделение!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		$department = Department::findOrFail($id);
		$users = User::select(['id', 'surname', 'name', 'patronymic'])->orderBy('surname', 'asc')->get();
        return view('administrator.departments.view', ['department'=>$department, 'users'=>$users]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		$val = Validator::make($request->all(),[
			'index_department' => 'required',
			'name_department' => 'required'
		])->validate();
		$department = Department::findOrFail($id);
        $department->fill([
			'index_department' => $request['index_department'],
            'name_department' => $request['name_department'],
			'lider_department' => $request['lider_department']
        ]);
		$department->save();
		JournalController::store(Auth::User()->id,'Редактирование подразделение с id = ' . $department->id);
		return redirect()->back()->with('success','Подразделение успешно изменено!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$department = Department::findOrFail($id);
		JournalController::store(Auth::User()->id,'Подразделение удалено с id = ' . $department->id);
		$department->delete();
		return redirect()->back()->with('success','Успешно удалено!');
    }
}
