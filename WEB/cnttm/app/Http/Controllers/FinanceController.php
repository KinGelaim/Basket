<?php

namespace App\Http\Controllers;

use App\Finance;
use App\Visit;
use App\Schoolchildren;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id_user)
    {
        $val = Validator::make($request->all(),[
			'number' => 'required',
			'amount' => 'required',
			'date' => 'required'
		])->validate();
		$finance = Finance::create([
			'id_user' => $id_user,
			'number' => $request['number'],
			'amount' => $request['amount'],
			'date' => $request['date']
        ]);
		return redirect()->back()->with('success','Успешно добавлен!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_schoolchildren)
    {
		$pricelesson = 350;
		$schoolchildren = Schoolchildren::select(['users.id','surname','name','patronymic'])->join('users','schoolchildrens.id_user','users.id')->where('schoolchildrens.id', $id_schoolchildren)->first();
        $finances = Finance::select()->where('id_user', $schoolchildren->id)->get();
		$allvisits = Visit::select()->where('id_user', $schoolchildren->id)->count();
		$missvisits = Visit::select()->where('id_user', $schoolchildren->id)->where('id_state', 5)->count();
		$amissvisits = Visit::select()->where('id_user', $schoolchildren->id)->where('id_state', 6)->count();
		$all_amount = 0;
		foreach($finances as $finance)
			$all_amount += $finance->amount;
		$all_need_amount = $pricelesson * ($allvisits - $amissvisits);
		$need_amount = $pricelesson * ($allvisits - $amissvisits) - $all_amount;
		return view('administration.finance.show', ['schoolchildren' => $schoolchildren, 
													'finances' => $finances, 
													'allvisits' => $allvisits, 
													'missvisits' => $missvisits,
													'amissvisits' => $amissvisits,
													'pricelesson' => $pricelesson,
													'all_amount' => $all_amount,
													'all_need_amount' => $all_need_amount,
													'need_amount' => $need_amount
													]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
