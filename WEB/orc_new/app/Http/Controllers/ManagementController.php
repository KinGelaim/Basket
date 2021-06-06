<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Counterpartie;
use App\Contract;

class ManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$link = '';
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		$counterpartie = '';
		$counerpartie_name = '';
		if(isset($_GET['counterpartie'])) {
			$counerpartie_name = $_GET['counterpartie'];
			foreach($counterparties as $counter){
				if($counter->name == $counerpartie_name){
					$counterpartie = $counter->id;
					break;
				}
			}
		} else
			$counterpartie = '';
		$paginate_count = 10;
		if (isset($_GET["page"])) {
			$page  = $_GET["page"];
		} else {
			$page=1;
		};
		$start = ($page-1) * $paginate_count;
		$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','id_goz_contract','id_view_work_contract', 'view_works.name_view_work',
										'all_count_contract','concluded_count_contract','amount_concluded_contract','formalization_count_contract',
										'amount_formalization_contract','big_deal_contract','amoun_implementation_contract','comment_implementation_contract',
										'prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract','date_contact','year_contract',
										'name_view_contract'])
						->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
						->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
						->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
						->where('contracts.number_contract', null)
						->where('contracts.id_counterpartie_contract','>','-1')
						->orderBy('contracts.id', 'desc')
						->offset($start)
						->limit($paginate_count)
						->get();
		$contract_count = Contract::select()->join('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')->where('contracts.id_counterpartie_contract','>',-1)->where('contracts.number_contract', null)->count();
		$prev_page = $page - 1 > 0 ? (int)($page-1) : '';
		$next_page = $page + 1 <= (int)ceil($contract_count/$paginate_count) ? (int)($page+1) : '';
		foreach($contracts as $contract)
			foreach($counterparties as $counter)
				if($contract->id_counterpartie_contract == $counter->id)
					$contract->name_counterpartie_contract = $counter->name;
        return view('department.management.contracts',['contracts' => $contracts,
													'count_paginate' => (int)ceil($contract_count/$paginate_count),
													'prev_page' => $prev_page,
													'next_page' => $next_page,
													'page' => $page,
													'link' => $link
												]);
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
    public function store(Request $request, $id)
    {
		$val = Validator::make($request->all(),[
			'number_pp' => 'required',
			'index_dep' => 'required',
			'year_contract' => 'required',
			//'number_contract' => 'required|unique:contracts',
			'number_pp' => Rule::unique('contracts')->where('year_contract', $request['year_contract'])
		])->validate();
		$contract = Contract::findOrFail($id);
		$contract->number_pp = $request['number_pp'];
		$contract->number_contract = $request['number_contract'];
		$contract->year_contract = $request['year_contract'];
		$all_dirty = JournalController::getMyChanges($contract);
		$contract->save();
		JournalController::store(Auth::User()->id,'Присвоен новый номер для контракта с id = ' . $id . '~' . json_encode($all_dirty));
		return redirect()->back()->with('success','Номер успешно присвоен!');
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
