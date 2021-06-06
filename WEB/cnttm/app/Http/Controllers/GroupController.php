<?php

namespace App\Http\Controllers;

use App\Group;
use App\Laboratory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::select(['*','groups.id as id','groups.name as name','laboratories.name as nameLaba'])->join('laboratories','laboratories.id','groups.id_laba')->get();
		return view('administration.group.main', ['groups'=>$groups]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $laboratories = Laboratory::select(['*'])->get();
        return view('administration.group.register', ['laboratories'=>$laboratories]);
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
			'name' => 'required',
			'id_laba' => 'required'
		])->validate();
        $group = Group::create([
			'name' => $request['name'],
			'id_laba' => $request['id_laba']
        ]);
		return redirect()->route('group.main')->with('success','Успешно добавлена!');
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
		$group = Group::findOrFail($id);
		$laboratories = Laboratory::select(['*'])->get();
        return view('administration.group.edit', ['group'=>$group, 'laboratories'=>$laboratories]);
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
		$group = Group::findOrFail($id);
		$val = Validator::make($request->all(),[
			'name' => 'required',
			'id_laba' => 'required'
		])->validate();
		$group->fill([
			'name' => $request['name'],
			'id_laba' => $request['id_laba']
		]);
		$group->save();
		return redirect()->route('group.main')->with('success','Успешно изменена!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$group = Group::findOrFail($id);
		$group->delete();
		return redirect()->back()->with('success','Успешно удалена!');
    }
}
