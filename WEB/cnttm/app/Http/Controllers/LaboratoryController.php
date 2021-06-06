<?php

namespace App\Http\Controllers;

use App\Laboratory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LaboratoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$laboratories = Laboratory::select(['*'])->get();
        return view('administration.laboratory.main', ['laboratories'=>$laboratories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		return view('administration.laboratory.register');
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
			'name' => 'required'
		])->validate();
        $laboratory = Laboratory::create([
			'name' => $request['name']
        ]);
		return redirect()->route('laboratory.main')->with('success','Успешно добавлена!');
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
		$laboratory = Laboratory::findOrFail($id);
        return view('administration.laboratory.edit', ['laboratory'=>$laboratory]);
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
		$laboratory = Laboratory::findOrFail($id);
		$val = Validator::make($request->all(),[
			'name' => 'required'
		])->validate();
		$laboratory->fill([
			'name' => $request['name']
		]);
		$laboratory->save();
		return redirect()->route('laboratory.main')->with('success','Успешно изменена!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$laboratory = Laboratory::findOrFail($id);
		$laboratory->delete();
		return redirect()->back()->with('success','Успешно удалена!');
    }
}
