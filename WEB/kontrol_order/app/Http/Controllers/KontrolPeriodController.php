<?php

namespace App\Http\Controllers;

use App\KontrolPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KontrolPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$kontrol_periods = KontrolPeriod::all();
		return view('administration.kontrol_period.main', ['kontrol_periods'=>$kontrol_periods]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administration.kontrol_period.register');
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
			'count_day' => 'required|numeric'
		])->validate();
        $kontrol_periods = KontrolPeriod::create([
			'name' => $request['name'],
			'count_day' => $request['count_day']
        ]);
		return redirect()->route('kontrol_period.main')->with('success','Успешно добавлен!');
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
		$kontrol_period = KontrolPeriod::findOrFail($id);
        return view('administration.kontrol_period.edit', ['kontrol_period'=>$kontrol_period]);
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
		$kontrol_period = KontrolPeriod::findOrFail($id);
		$val = Validator::make($request->all(),[
			'name' => 'required',
			'count_day' => 'required|numeric'
		])->validate();
		$kontrol_period->fill([
			'name' => $request['name'],
			'count_day' => $request['count_day']
		]);
		$kontrol_period->save();
		return redirect()->route('kontrol_period.main')->with('success','Успешно изменен!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$kontrol_period = KontrolPeriod::findOrFail($id);
		$kontrol_period->delete();
		return redirect()->back()->with('success','Успешно удален!');
    }
}
