<?php

namespace App\Http\Controllers;

use Auth;
use App\SecondDepartmentUnit;
use Illuminate\Http\Request;

class SecondDepartmentUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = SecondDepartmentUnit::select()->orderBy('name_unit')->get();
        return view('administrator.second_department_unit.main', ['units'=>$units]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administrator.second_department_unit.unit', ['unit'=>'']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $unit = new SecondDepartmentUnit();
		$unit->name_unit = $request['name_unit'];
		$unit->save();
		JournalController::store(Auth::User()->id,'Добавлена новая единица измерения для второго отдела с id = ' . $unit->id);
		return redirect()->route('second_department_unit.main');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SecondDepartmentUnit  $secondDepartmentUnit
     * @return \Illuminate\Http\Response
     */
    public function show(SecondDepartmentUnit $secondDepartmentUnit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SecondDepartmentUnit  $secondDepartmentUnit
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $unit = SecondDepartmentUnit::findOrFail($id);
        return view('administrator.second_department_unit.unit', ['unit'=>$unit]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SecondDepartmentUnit  $secondDepartmentUnit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $unit = SecondDepartmentUnit::findOrFail($id);
		$unit->name_unit = $request['name_unit'];
		$unit->update();
		JournalController::store(Auth::User()->id,'Обновлена единица измерения для второго отдела с id = ' . $unit->id);
		return redirect()->route('second_department_unit.main');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SecondDepartmentUnit  $secondDepartmentUnit
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $unit = SecondDepartmentUnit::findOrFail($id);
		JournalController::store(Auth::User()->id,'Удаление ед. измерения с id = ' . $unit->id);
		$unit->delete();
		return redirect()->back();
    }
}
