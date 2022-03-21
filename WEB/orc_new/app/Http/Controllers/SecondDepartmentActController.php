<?php

namespace App\Http\Controllers;

use Auth;
use App\SecondDepartmentAct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SecondDepartmentActController extends Controller
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
    public function create($id_second_tour)
    {
        return view('department.second.act',['act'=>'','id_second_tour'=>$id_second_tour]);
    }
	
	public function create_sb($id_second_tour)
    {
        return view('department.second.act_sb',['act'=>'','id_second_tour'=>$id_second_tour]);
    }
	
	public function create_us($id_second_tour)
    {
        return view('department.second.act_us',['act'=>'','id_second_tour'=>$id_second_tour]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id_second_tour)
    {
        $val = Validator::make($request->all(),[
			//'number_act' => 'required',
			//'date_act' => 'required|date',
			'amount_act' => 'required'
		])->validate();
		$act = new SecondDepartmentAct();
		$act->fill(['id_second_tour' => $id_second_tour,
					'number_act' => $request['number_act'],
					'date_act' => $request['date_act'],
					'number_outgoing_act' => $request['number_outgoing_act'],
					'date_outgoing_act' => $request['date_outgoing_act'],
					'number_incoming_act' => $request['number_incoming_act'],
					'date_incoming_act' => $request['date_incoming_act'],
					'amount_act' => $request['amount_act']
		]);
		if($request['amount_act'])
			$act->amount_act = str_replace(' ','',$act->amount_act);
		$act->save();
		JournalController::store(Auth::User()->id,'Добавлен новый акт для испытания с id = ' . $id_second_tour);
		return redirect()->back()->with('success','Акт успешно добавлен!');
    }
	
	public function store_sb(Request $request, $id_second_tour)
    {
        $val = Validator::make($request->all(),[
			//'number_act' => 'required',
			//'date_act' => 'required|date',
			'amount_act' => 'required'
		])->validate();
		$act = new SecondDepartmentAct();
		$act->fill(['id_second_sb_tour' => $id_second_tour,
					'number_act' => $request['number_act'],
					'date_act' => $request['date_act'],
					'number_outgoing_act' => $request['number_outgoing_act'],
					'date_outgoing_act' => $request['date_outgoing_act'],
					'number_incoming_act' => $request['number_incoming_act'],
					'date_incoming_act' => $request['date_incoming_act'],
					'amount_act' => $request['amount_act']
		]);
		if($request['amount_act'])
			$act->amount_act = str_replace(' ','',$act->amount_act);
		$act->save();
		JournalController::store(Auth::User()->id,'Добавлен новый акт для сборки с id = ' . $id_second_tour);
		return redirect()->back()->with('success','Акт успешно добавлен!');
    }
	
	public function store_us(Request $request, $id_second_tour)
    {
        $val = Validator::make($request->all(),[
			//'number_act' => 'required',
			//'date_act' => 'required|date',
			'amount_act' => 'required'
		])->validate();
		$act = new SecondDepartmentAct();
		$act->fill(['id_second_us_tour' => $id_second_tour,
					'number_act' => $request['number_act'],
					'date_act' => $request['date_act'],
					'number_outgoing_act' => $request['number_outgoing_act'],
					'date_outgoing_act' => $request['date_outgoing_act'],
					'number_incoming_act' => $request['number_incoming_act'],
					'date_incoming_act' => $request['date_incoming_act'],
					'amount_act' => $request['amount_act']
		]);
		if($request['amount_act'])
			$act->amount_act = str_replace(' ','',$act->amount_act);
		$act->save();
		JournalController::store(Auth::User()->id,'Добавлен новый акт для услуги с id = ' . $id_second_tour);
		return redirect()->back()->with('success','Акт успешно добавлен!');
    }
	
	public function store_for_contract(Request $request, $id_contract)
    {
        $val = Validator::make($request->all(),[
			//'number_act' => 'required',
			//'date_act' => 'required|date',
			'amount_act' => 'required'
		])->validate();
		$act = new SecondDepartmentAct();
		$act->fill(['id_contract' => $id_contract,
					'number_act' => $request['number_act'],
					'date_act' => $request['date_act'],
					'number_outgoing_act' => $request['number_outgoing_act'],
					'date_outgoing_act' => $request['date_outgoing_act'],
					'number_incoming_act' => $request['number_incoming_act'],
					'date_incoming_act' => $request['date_incoming_act'],
					'amount_act' => $request['amount_act']
		]);
		if($request['amount_act'])
			$act->amount_act = str_replace(' ','',$act->amount_act);
		$act->save();
		JournalController::store(Auth::User()->id,'Добавлен новый акт c id контракта = ' . $id_contract);
		return redirect()->back()->with('success','Акт успешно добавлен!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SecondDepartmentAct  $secondDepartmentAct
     * @return \Illuminate\Http\Response
     */
    public function show($id_second_tour)
    {
        $acts = SecondDepartmentAct::select()->where('id_second_tour', $id_second_tour)->get();
		return view('department.second.acts',['acts'=>$acts,'id_second_tour'=>$id_second_tour]);
    }
	
	public function show_sb($id_second_tour)
    {
        $acts = SecondDepartmentAct::select()->where('id_second_sb_tour', $id_second_tour)->get();
		return view('department.second.acts_sb',['acts'=>$acts,'id_second_tour'=>$id_second_tour]);
    }
	
	public function show_us($id_second_tour)
    {
        $acts = SecondDepartmentAct::select()->where('id_second_us_tour', $id_second_tour)->get();
		return view('department.second.acts_us',['acts'=>$acts,'id_second_tour'=>$id_second_tour]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SecondDepartmentAct  $secondDepartmentAct
     * @return \Illuminate\Http\Response
     */
    public function edit($id_second_act)
    {
		$act = SecondDepartmentAct::findOrFail($id_second_act);
        return view('department.second.act',['act'=>$act]);
    }
	
	public function edit_sb($id_second_act)
    {
		$act = SecondDepartmentAct::findOrFail($id_second_act);
        return view('department.second.act',['act'=>$act]);
    }
	
	public function edit_us($id_second_act)
    {
		$act = SecondDepartmentAct::findOrFail($id_second_act);
        return view('department.second.act',['act'=>$act]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SecondDepartmentAct  $secondDepartmentAct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_second_act)
    {
        $val = Validator::make($request->all(),[
			//'number_act' => 'required',
			//'date_act' => 'required|date',
			'amount_act' => 'required'
		])->validate();
		$act = SecondDepartmentAct::findOrFail($id_second_act);
		$act->fill(['number_act' => $request['number_act'],
					'date_act' => $request['date_act'],
					'number_outgoing_act' => $request['number_outgoing_act'],
					'date_outgoing_act' => $request['date_outgoing_act'],
					'number_incoming_act' => $request['number_incoming_act'],
					'date_incoming_act' => $request['date_incoming_act'],
					'amount_act' => $request['amount_act'],
		]);
		if($request['amount_act'])
			$act->amount_act = str_replace(' ','',$act->amount_act);
		$act->save();
		JournalController::store(Auth::User()->id,'Изменен акт для испытания с id = ' . $id_second_act);
		return redirect()->back()->with('success','Акт успешно изменен!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SecondDepartmentAct  $secondDepartmentAct
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_second_act)
    {
        $act = SecondDepartmentAct::findOrFail($id_second_act);
		JournalController::store(Auth::User()->id,'Удален акт для испытания с id = ' . $act->id_second_tour);
		$act->delete();
		return redirect()->back()->with('success','Акт успешно удален!');
    }
}
