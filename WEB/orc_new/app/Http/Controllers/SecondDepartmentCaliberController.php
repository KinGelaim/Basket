<?php

namespace App\Http\Controllers;

use Auth;
use App\SecondDepartmentCaliber;
use Illuminate\Http\Request;

class SecondDepartmentCaliberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $calibers = SecondDepartmentCaliber::select()->orderBy('name_caliber')->get();
        return view('administrator.second_department_caliber.main', ['calibers'=>$calibers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administrator.second_department_caliber.caliber', ['caliber'=>'']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $caliber = new SecondDepartmentCaliber();
		$caliber->name_caliber = $request['name_caliber'];
		$caliber->save();
		JournalController::store(Auth::User()->id,'Добавлен новый калибр для второго отдела с id = ' . $caliber->id);
		return redirect()->route('second_department_caliber.main');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $caliber = SecondDepartmentCaliber::findOrFail($id);
        return view('administrator.second_department_caliber.caliber', ['caliber'=>$caliber]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $caliber = SecondDepartmentCaliber::findOrFail($id);
		$caliber->name_caliber = $request['name_caliber'];
		$caliber->update();
		JournalController::store(Auth::User()->id,'Обновлен калибр для второго отдела с id = ' . $caliber->id);
		return redirect()->route('second_department_caliber.main');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $caliber = SecondDepartmentCaliber::findOrFail($id);
		JournalController::store(Auth::User()->id,'Удаление калибра с id = ' . $caliber->id);
		$caliber->delete();
		return redirect()->back();
    }
}
