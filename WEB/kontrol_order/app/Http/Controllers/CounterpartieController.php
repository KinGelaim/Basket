<?php

namespace App\Http\Controllers;

use App\Counterpartie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CounterpartieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $counterparties = Counterpartie::all();
		return view('administration.counterpartie.main', ['counterparties'=>$counterparties]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administration.counterpartie.register');
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
        $counterpartie = Counterpartie::create([
			'name' => $request['name']
        ]);
		return redirect()->route('counterpartie.main')->with('success','Успешно добавлен!');
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
		$counterpartie = Counterpartie::findOrFail($id);
        return view('administration.counterpartie.edit', ['counterpartie'=>$counterpartie]);
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
			'name' => 'required'
		])->validate();
		$counterpartie = Counterpartie::findOrFail($id);
        $counterpartie->fill([
			'name' => $request['name']
        ]);
		$counterpartie->save();
		return redirect()->route('counterpartie.main')->with('success','Успешно изменён!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$counterpartie = Counterpartie::findOrFail($id);
		$counterpartie->delete();
		return redirect()->back()->with('success','Успешна удалена!');
    }
}
