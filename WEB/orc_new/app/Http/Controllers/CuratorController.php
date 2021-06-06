<?php

namespace App\Http\Controllers;

use Auth;
use App\Curator;
use App\User;
use App\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CuratorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $curators = Curator::withTrashed()->get();
        return view('administrator.curator.main', ['curators'=>$curators]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$departments = Department::all();
		$users = User::select(['id', 'surname', 'name', 'patronymic'])->orderBy('surname', 'asc')->get();
        return view('administrator.curator.register')->with(['users'=>$users,'departments'=>$departments]);
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
			'id_user' => 'required',
			'FIO' => 'required'
		])->validate();
        $curator = Curator::create([
			'id_user' => $request['id_user'],
            'FIO' => $request['FIO'],
			'telephone' => $request['telephone'],
			'id_department' => $request['id_department']
        ]);
		JournalController::store(Auth::User()->id,'Создан новый куратор с id = ' . $curator->id);
		return redirect()->route('curator.main');
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
		$curator = Curator::findOrFail($id);
		$users = User::select(['id', 'surname', 'name', 'patronymic'])->orderBy('surname', 'asc')->get();
        $departments = Department::all();
		return view('administrator.curator.edit', ['curator'=>$curator, 'users'=>$users, 'departments'=>$departments]);
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
		$val = Validator::make($request->all(),[
			'id_user' => 'required',
			'FIO' => 'required'
		])->validate();
		$curator = Curator::findOrFail($id);
		$curator->fill([
			'id_user' => $request['id_user'],
			'FIO' => $request['FIO'],
			'telephone' => $request['telephone'],
			'id_department' => $request['id_department']
		]);
		$curator->save();
		JournalController::store(Auth::User()->id,'Обновлен куратор с id = ' . $curator->id);
		return redirect()->route('curator.main');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$curator = Curator::findOrFail($id);
		JournalController::store(Auth::User()->id,'Удален куратор с id = ' . $curator->id);
		$curator->delete();
		return redirect()->back()->with('success','Успешно удален!');
    }
}
