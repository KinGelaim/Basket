<?php

namespace App\Http\Controllers;

//ini_set("max_execution_time", 300);
set_time_limit(300);

use App\Invoice;
use App\Contract;
use App\ViewWork;
use App\Counterpartie;
use App\State;
use App\ViewContract;
use App\Department;
use App\Protocol;
use App\SecondDepartmentAct;
use App\SecondDepartmentTour;
use App\SecondDepartmentSbTour;
use App\SecondDepartmentUsTour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadershipDepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
	{
		$year_str = "contracts.id_counterpartie_contract";
		$year_equal = ">";
		$view_contract_str = "contracts.id_counterpartie_contract";
		$view_contract_equal = ">";
		$counterpartie_str = "contracts.id_counterpartie_contract";
		$counterpartie_equal = ">";
		$link = '';
		if(isset($_GET['year'])) {
			$year = ($_GET['year']);
			$year_str = "contracts.year_contract";
			$year_equal = "=";
			$link .= "&year=" . $_GET['year'];
		} else
			$year = '';
		if(isset($_GET['view'])) {
			$view_contract = ($_GET['view']);
			$view_contract_str = "view_contracts.name_view_contract";
			$view_contract_equal = "=";
			$link .= "&view=" . $_GET['view'];
		} else
			$view_contract = '';
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
			$counterpartie_str = "id_counterpartie_contract";
			$counterpartie_equal = "=";
			$link .= "&counterpartie=" . $_GET['counterpartie'];
		} else
			$counterpartie = '';
		//Контракты
		$paginate_count = 10;
		if (isset($_GET["page"])) {
			$page  = $_GET["page"];
		} else {
			$page=1;
		};
		$start = ($page-1) * $paginate_count;
		//$contracts = Contract::paginate($paginate_count);
		$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','id_goz_contract','id_view_contract', 'view_contracts.name_view_contract',
										'all_count_contract','concluded_count_contract','amount_concluded_contract','formalization_count_contract',
										'amount_formalization_contract','big_deal_contract','amoun_implementation_contract','comment_implementation_contract',
										'prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract','date_contact','year_contract',
										'name_view_contract'])
						->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', '=', 'contracts.id')
						->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', '=', 'view_contracts.id')
						->where('contracts.id_counterpartie_contract','>','-1')->where($year_str, $year_equal, $year)
						->where($view_contract_str, $view_contract_equal, $view_contract)->where($counterpartie_str, $counterpartie_equal, $counterpartie)->orderBy('contracts.id', 'desc')
						->offset($start)
						->limit($paginate_count)
						->get();
		$contract_count = Contract::select()->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', '=', 'contracts.id')
											->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', '=', 'view_contracts.id')->where('contracts.id_counterpartie_contract','>',-1)->where($year_str, $year_equal, $year)
											->where($view_contract_str, $view_contract_equal, $view_contract)->where($counterpartie_str, $counterpartie_equal, $counterpartie)->count();
		$prev_page = $page - 1 > 0 ? (int)($page-1) : '';
		$next_page = $page + 1 <= (int)ceil($contract_count/$paginate_count) ? (int)($page+1) : '';
		$years = DB::SELECT('SELECT year_contract FROM contracts GROUP BY year_contract ORDER BY year_contract DESC');
		$view_contracts = ViewContract::all();
		foreach($contracts as $contract)
			foreach($counterparties as $counter)
				if($contract->id_counterpartie_contract == $counter->id)
					$contract->name_counterpartie_contract = $counter->name;
		//Для разных отчетов
		$departments = Department::select()->orderBy('index_department', 'asc')->get();
		$all_view_contracts = ViewContract::select('*')->get();
		$sip_counterparties = Counterpartie::select(['*'])->where('is_sip_counterpartie', 1)->orderBy('name', 'asc')->get();
        return view('department.leadership.main',['contracts' => $contracts,
													'years' => $years,
													'year' => $year,
													'viewContracts' => $view_contracts,
													'viewContract' => $view_contract,
													'counterparties' => $counterparties,
													'counterpartie' => $counerpartie_name,
													'departments'=>$departments,
													'all_view_contracts'=>$all_view_contracts,
													'sip_counterparties'=>$sip_counterparties,
													'count_paginate' => (int)ceil($contract_count/$paginate_count),
													'prev_page' => $prev_page,
													'next_page' => $next_page,
													'page' => $page,
													'link' => $link,
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
    public function store(Request $request)
    {
        //
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
	
	public function peo()
	{
		$year_str = "contracts.id_counterpartie_contract";
		$year_equal = ">";
		$view_contract_str = "contracts.id_counterpartie_contract";
		$view_contract_equal = ">";
		$counterpartie_str = "contracts.id_counterpartie_contract";
		$counterpartie_equal = ">";
		$link = '';
		if(isset($_GET['year'])) {
			if(strlen($_GET['year']) > 0) {
				$year = ($_GET['year']);
				$year_str = "contracts.year_contract";
				$year_equal = "=";
				$link .= "&year=" . $_GET['year'];
			} else
				$year = '';
		} else
			$year = '';
		if(isset($_GET['view'])) {
			if(strlen($_GET['view']) > 0) {
				$view_contract = ($_GET['view']);
				$view_contract_str = "view_contracts.name_view_contract";
				$view_contract_equal = "=";
				$link .= "&view=" . $_GET['view'];
			} else
				$view_contract = '';
		} else
			$view_contract = '';
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		$counterpartie = '';
		$counerpartie_name = '';
		if(isset($_GET['counterpartie'])) {
			if(strlen($_GET['counterpartie']) > 0) {
				$counerpartie_name = $_GET['counterpartie'];
				foreach($counterparties as $counter){
					if($counter->name == $counerpartie_name){
						$counterpartie = $counter->id;
						break;
					}
				}
				$counterpartie_str = "id_counterpartie_contract";
				$counterpartie_equal = "=";
				$link .= "&counterpartie=" . $_GET['counterpartie'];
			} else
				$counterpartie = '';
		} else
			$counterpartie = '';
		$filter_str = "contracts.id_counterpartie_contract";
		$filter_equal = ">";
		$filter_value = "";
		if(isset($_GET['filter']))
			if(strlen($_GET['filter']) > 0)	{
				$filter_str = 'date_maturity_reestr';
				$filter_equal = "LIKE";
				$filter_value = '%' . $_GET['filter'] . '%';
			}
		$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','item_contract','id_goz_contract','goz_works.name_works_goz','id_view_contract', 'view_contracts.name_view_contract',
										'big_deal_reestr','amoun_implementation_contract','comment_implementation_contract',
										'amount_contract_reestr','amount_reestr','amount_year_reestr','amount_contract_year_reestr',
										'date_contact','year_contract','prepayment_reestr','percent_prepayment_reestr','date_maturity_reestr'])
						->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
						->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
						->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
						->join('goz_works', 'contracts.id_goz_contract', '=', 'goz_works.id')
						->where('is_sip_contract', 1)
						->where('archive_contract', 0)
						->where('renouncement_contract', '!=', 1)
						->where('contracts.id_counterpartie_contract','>','-1')
						->where($year_str, $year_equal, $year)
						->where($view_contract_str, $view_contract_equal, $view_contract)
						->where($counterpartie_str, $counterpartie_equal, $counterpartie)
						->where($filter_str, $filter_equal, $filter_value)
						->orderBy('reestr_contracts.id_view_contract', 'asc')
						->orderBy('contracts.id_counterpartie_contract', 'asc')
						->orderBy('contracts.id', 'desc')
						->get();
		foreach($contracts as $contract)
			foreach($counterparties as $counter)
				if($contract->id_counterpartie_contract == $counter->id)
					$contract->name_counterpartie_contract = $counter->name;

		list($result, $itogs, $view_itogs) = LeadershipDepartmentController::CreateResultForPEO($contracts);

		//dump($contracts);
		//dd($itogs);
		//dd($view_itogs);
		return view('department.leadership.peo',['contracts'=>$result, 'itogs'=>$itogs, 'view_itogs'=>$view_itogs]);
	}
	
	public function peoNoExecute(Request $request, $only_arr = false)
	{
		$view_contract_str = "(";
		$counterpartie_str = "contracts.id_counterpartie_contract";
		$counterpartie_equal = ">";
		if(isset($request['view'])) {
			for($i=0; $i < count($request['view']); $i++){
				if(strlen($request['view'][$i]) > 0)
					$view_contract_str .= "view_contracts.name_view_contract='" . $request['view'][$i] . "'";
				if($i != count($request['view']) - 1 && strlen($view_contract_str) > 2)
					$view_contract_str .= " OR ";
			}
			if(strlen($view_contract_str) < 2)
				$view_contract_str = "";
			else
				$view_contract_str .= ") AND ";
		}
		$view_contract_str .= " contracts.id_counterpartie_contract ";
		//dd($view_contract_str);
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		$counterpartie = '';
		$counerpartie_name = '';
		if(isset($request['counterpartie'])) {
			if(strlen($request['counterpartie']) > 0) {
				$counerpartie_name = $request['counterpartie'];
				foreach($counterparties as $counter){
					if($counter->name == $counerpartie_name){
						$counterpartie = $counter->id;
						break;
					}
				}
				$counterpartie_str = "id_counterpartie_contract";
				$counterpartie_equal = "=";
			} else
				$counterpartie = '';
		} else
			$counterpartie = '';
		//DB::enableQueryLog();
		$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','item_contract','id_goz_contract','goz_works.name_works_goz','id_view_contract', 'view_contracts.name_view_contract',
										'big_deal_reestr','amoun_implementation_contract','comment_implementation_contract',
										'amount_contract_reestr','amount_reestr','amount_year_reestr','amount_contract_year_reestr',
										'date_contact','year_contract','prepayment_reestr','percent_prepayment_reestr','date_maturity_reestr'])
						->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
						->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
						->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
						->join('goz_works', 'contracts.id_goz_contract', '=', 'goz_works.id')
						->where('is_sip_contract', 1)
						->where('archive_contract', 0)
						->where('renouncement_contract', '!=', 1)
						->where('contracts.id_counterpartie_contract','>','-1')
						->where(DB::raw($view_contract_str), '>', '-1')
						->where($counterpartie_str, $counterpartie_equal, $counterpartie)
						->where('contracts.number_contract', 'not like', '%‐23‐%')
						->orderBy('reestr_contracts.id_view_contract', 'asc')
						->orderBy('contracts.id_counterpartie_contract', 'asc')
						->orderBy('contracts.id', 'desc')
						->get();
		//dd(DB::getQueryLog());
		//dd($contracts);
		$new_contracts = [];
		foreach($contracts as $contract) {
			foreach($counterparties as $counter)
				if($contract->id_counterpartie_contract == $counter->id)
					$contract->name_counterpartie_contract = $counter->name;
			//dd($contract);
			$states = State::select(['*'])->where('id_contract', $contract->id)->where('is_work_state', 1)->get();
			if(count($states) > 0){
				if($states[count($states) - 1]->name_state != 'Выполнен')
					array_push($new_contracts, $contract);
			}else
				array_push($new_contracts, $contract);
		}
		//dd($new_contracts);
		list($result, $itogs, $view_itogs) = LeadershipDepartmentController::CreateResultForPEO($new_contracts);
		if($only_arr)
			return ['contracts'=>$result, 'itogs'=>$itogs, 'view_itogs'=>$view_itogs];

		//dump($contracts);
		//dd($itogs);
		//dd($view_itogs);
		return view('department.leadership.peo',['contracts'=>$result, 'itogs'=>$itogs, 'view_itogs'=>$view_itogs]);
	}
	
	function CreateResultForPEO($contracts)
	{
		$result = [];		//Переменная для всех контрактов
		$itogs = [];		//Переменная для итогов по ГОЗ, Экспорт ...		//TODO: выделять для итогов отдельную функцию дабы избежать повторение кода
		$view_itogs = [];	//Переменная для итогов по видам догворров		//TODO: не проверять на первое не первое действие, а просто проверять существование переменной (существует - добавляем, нет - создаём)
		foreach($contracts as $contract)
		{
			$states = State::select(['*'])->where('id_contract', $contract->id)->where('is_work_state', null)->get();
			//$states = $states->slice(count($contract->states)-3);
			if(count($states) > 0)
				$contract['states'] = $states;
			else
				$contract['states'] = [];
			
			$work_states = State::select(['*'])->where('id_contract', $contract->id)->where('is_work_state', 1)->get();
			$contract['work_states'] = $work_states;
				
			/*if(count($states) > 3){
				//dd($states->chunk(2)->toArray());
				$contract['states'] = $states->slice(count($states)-3);
					//dd($contract['states']);
			}*/
			
			//Счет-фактура
			$invoices = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
												->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
												->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
												->where('id_contract', $contract->id)
												->where('name', 'Счет-фактура')
												->orderBy('invoices.number_invoice', 'asc')
												->get();
			$full_invoices = 0;
			foreach($invoices as $invoice)
				$full_invoices += $invoice->amount_p_invoice;
			$contract->invoices = $full_invoices;
												
			//Счет на Аванс
			/*$prepayments = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
												->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
												->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
												->where('id_contract', $contract->id)
												->where('name', 'Аванс')
												->orderBy('invoices.number_invoice', 'asc')
												->get();
			*/
			
			//if(count($prepayments) > 0)
			//dd($prepayments);
			//Оплата
			$payments = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
												->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
												->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
												->where('id_contract', $contract->id)
												->where('name', 'Оплата')
												->orderBy('invoices.number_invoice', 'asc')
												->get();
			$full_prepayments = 0;
			/*foreach($prepayments as $prepayment)
				$full_prepayments += $prepayment->amount_p_invoice;
			$contract->prepayments = $full_prepayments;*/
			
			$full_payments = 0;
			foreach($payments as $payment)
				if($payment->is_prepayment_invoice == 1)
					$full_prepayments += $payment->amount_p_invoice;
				else
					$full_payments += $payment->amount_p_invoice;
			$contract->payments = $full_payments;
			$contract->prepayments = $full_prepayments;
			
			//Дополнительные соглашения
			$additional_agreements = Protocol::select()->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
			foreach($additional_agreements as $additional_agreement)
			{
				$additional_agreement_states = State::select(['*'])->where('id_protocol', $additional_agreement->id)->get();
				$additional_agreement->states = $additional_agreement_states;
			}
			$contract->additional_agreements = $additional_agreements;
			
			//Протоколы (22.12.2020)
			$protocols = Protocol::select()->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
			foreach($protocols as $protocol)
			{
				$protocol_states = State::select(['*'])->where('id_protocol', $protocol->id)->get();
				$protocol->states = $protocol_states;
			}
			$contract->protocols = $protocols;
			
			//Выполнение (акты из второго отдела)
			if($contract->name_view_contract == 'Услуги ГН' || $contract->name_view_contract == 'Услуги ВН')
			{
				$pr_amount_acts = 0;
				$pr_year_amount_acts = 0;
				$acts = SecondDepartmentAct::select(['second_department_acts.amount_act','second_department_acts.date_act'])
											->where('id_contract', $contract->id)
											->get();
				foreach($acts as $act){
					$pr_amount_acts += $act->amount_act;
					
					//Проверка на то, что акт ТЕКУЩЕГО года
					if(date('Y',strtotime($act->date_act)) == date('Y',time()))
						$pr_year_amount_acts += $act->amount_act;
				}
				
				//Для услуг ВН и ГН (на сам наряд!)
				$acts = SecondDepartmentAct::select(['second_department_acts.amount_act','second_department_acts.date_act','date_worked'])
											->join('second_department_us_tours','id_second_us_tour','second_department_us_tours.id')
											->where('second_department_us_tours.id_contract', $contract->id)
											->get();
				foreach($acts as $act){
					$pr_amount_acts += $act->amount_act;
					//Проверка на то, что акт составлен в 2019 году, но как?)))
					//if(date('Y',strtotime($act->date_act)) == date('Y',time()))
					//	$pr_year_amount_acts += $act->amount_act;
					
					//Проверка на то, что дата отработки в 2019 году
					if(date('Y',strtotime($act->date_worked)) == date('Y',time()))
						$pr_year_amount_acts += $act->amount_act;
				}
				
				$contract->amount_acts = $pr_amount_acts;
				$contract->year_amount_acts = $pr_year_amount_acts;
			}
			else
			{
				$pr_amount_acts = 0;
				$pr_year_amount_acts = 0;
				//Для испытаний
				$acts = SecondDepartmentAct::select(['second_department_acts.amount_act','second_department_acts.date_act','date_worked'])
											->join('second_department_tours','id_second_tour','second_department_tours.id')
											->where('second_department_tours.id_contract', $contract->id)
											->get();
				foreach($acts as $act){
					$pr_amount_acts += $act->amount_act;
					//Проверка на то, что акт составлен в 2019 году, но как?)))
					//if(date('Y',strtotime($act->date_act)) == date('Y',time()))
					//	$pr_year_amount_acts += $act->amount_act;
					
					//Проверка на то, что дата отработки в 2019 году
					if(date('Y',strtotime($act->date_worked)) == date('Y',time()))
						$pr_year_amount_acts += $act->amount_act;
				}
				//Для сборки
				$acts = SecondDepartmentAct::select(['second_department_acts.amount_act','second_department_acts.date_act','date_worked'])
											->join('second_department_sb_tours','id_second_sb_tour','second_department_sb_tours.id')
											->where('second_department_sb_tours.id_contract', $contract->id)
											->get();
				foreach($acts as $act){
					$pr_amount_acts += $act->amount_act;
					//Проверка на то, что акт составлен в 2019 году, но как?)))
					//if(date('Y',strtotime($act->date_act)) == date('Y',time()))
					//	$pr_year_amount_acts += $act->amount_act;
					
					//Проверка на то, что дата отработки в 2019 году
					if(date('Y',strtotime($act->date_worked)) == date('Y',time()))
						$pr_year_amount_acts += $act->amount_act;
				}
				//Для услуг ВН и ГН
				$acts = SecondDepartmentAct::select(['second_department_acts.amount_act','second_department_acts.date_act','date_worked'])
											->join('second_department_us_tours','id_second_us_tour','second_department_us_tours.id')
											->where('second_department_us_tours.id_contract', $contract->id)
											->get();
				foreach($acts as $act){
					$pr_amount_acts += $act->amount_act;
					//Проверка на то, что акт составлен в 2019 году, но как?)))
					//if(date('Y',strtotime($act->date_act)) == date('Y',time()))
					//	$pr_year_amount_acts += $act->amount_act;
					
					//Проверка на то, что дата отработки в 2019 году
					if(date('Y',strtotime($act->date_worked)) == date('Y',time()))
						$pr_year_amount_acts += $act->amount_act;
				}
				//Записываем
				$contract->amount_acts = $pr_amount_acts;
				$contract->year_amount_acts = $pr_year_amount_acts;
			}
			
			if(!in_array($contract->id_view_contract, $result))
				$result += [$contract->name_view_contract=>[]];
			if(!in_array($contract->name_counterpartie_contract, $result[$contract->name_view_contract]))
				$result[$contract->name_view_contract] += [$contract->name_counterpartie_contract=>[]];
			array_push($result[$contract->name_view_contract][$contract->name_counterpartie_contract], $contract);
			
			//Разбиение по ГОЗ, Экспорт, Межзаводские и Иные
			if(!isset($itogs[$contract->name_works_goz]))
			{
				$itogs[$contract->name_works_goz] = [];
				$itogs[$contract->name_works_goz] += ['result_all_contract' => 1];
				if(count($contract['states'])>0){
					if($contract['states'][count($contract['states'])-1]->name_state == 'Заключен' OR $contract['states'][count($contract['states'])-1]->name_state == 'Заключён') {					
						$itogs[$contract->name_works_goz] += ['result_all_concluded_contract' => 1];
						if(is_numeric($contract->amount_reestr))
							$itogs[$contract->name_works_goz] += ['result_amount_concluded_contract' => $contract->amount_reestr];
						else
							$itogs[$contract->name_works_goz] += ['result_amount_concluded_contract' => 0];
						if(is_numeric($contract->amount_year_reestr))
							$itogs[$contract->name_works_goz] += ['result_year_amount_concluded_contract' => $contract->amount_year_reestr];
						else
							$itogs[$contract->name_works_goz] += ['result_year_amount_concluded_contract' => 0];
							
						$itogs[$contract->name_works_goz] += ['result_all_formalization_contract' => 0];
						$itogs[$contract->name_works_goz] += ['result_amount_formalization_contract' => 0];
						$itogs[$contract->name_works_goz] += ['result_year_amount_formalization_contract' => 0];
					}else{
						$itogs[$contract->name_works_goz] += ['result_all_concluded_contract' => 0];
						$itogs[$contract->name_works_goz] += ['result_amount_concluded_contract' => 0];
						$itogs[$contract->name_works_goz] += ['result_year_amount_concluded_contract' => 0];
						$itogs[$contract->name_works_goz] += ['result_all_formalization_contract' => 1];
						if(is_numeric($contract->amount_reestr))
							$itogs[$contract->name_works_goz] += ['result_amount_formalization_contract' => $contract->amount_reestr];
						else
							$itogs[$contract->name_works_goz] += ['result_amount_formalization_contract' => 0];
						if(is_numeric($contract->amount_year_reestr))
							$itogs[$contract->name_works_goz] += ['result_year_amount_formalization_contract' => $contract->amount_year_reestr];
						else
							$itogs[$contract->name_works_goz] += ['result_year_amount_formalization_contract' => 0];
					}
				}else{
					$itogs[$contract->name_works_goz] += ['result_all_concluded_contract' => 0];
					$itogs[$contract->name_works_goz] += ['result_amount_concluded_contract' => 0];
					$itogs[$contract->name_works_goz] += ['result_year_amount_concluded_contract' => 0];
					$itogs[$contract->name_works_goz] += ['result_all_formalization_contract' => 1];
					if(is_numeric($contract->amount_reestr))
						$itogs[$contract->name_works_goz] += ['result_amount_formalization_contract' => $contract->amount_reestr];
					else
						$itogs[$contract->name_works_goz] += ['result_amount_formalization_contract' => 0];
					if(is_numeric($contract->amount_year_reestr))
						$itogs[$contract->name_works_goz] += ['result_year_amount_formalization_contract' => $contract->amount_year_reestr];
					else
						$itogs[$contract->name_works_goz] += ['result_year_amount_formalization_contract' => 0];
				}
				
				if($contract->big_deal_reestr != null)
					$itogs[$contract->name_works_goz] += ['result_big_count_contract' => 1];
				else
					$itogs[$contract->name_works_goz] += ['result_big_count_contract' => 0];
					
				$itogs[$contract->name_works_goz] += ['result_amount_implementation_contract' => $contract->amount_acts];
				$itogs[$contract->name_works_goz] += ['result_year_amount_implementation_contract' => $contract->year_amount_acts];
				
				if(is_numeric($contract->prepayment_reestr))
					$itogs[$contract->name_works_goz] += ['result_all_prepayment_reestr' => $contract->prepayment_reestr];
				else
					$itogs[$contract->name_works_goz] += ['result_all_prepayment_reestr' => 0];
				if(is_numeric($contract->invoices))
					$itogs[$contract->name_works_goz] += ['result_all_invoices' => $contract->invoices];
				else
					$itogs[$contract->name_works_goz] += ['result_all_invoices' => 0];
				
				$itogs[$contract->name_works_goz] += ['result_all_prepayment_contract' => $contract->prepayments];
				$itogs[$contract->name_works_goz] += ['result_all_payment_contract' => $contract->payments];
			}else{
				$itogs[$contract->name_works_goz]['result_all_contract'] = $itogs[$contract->name_works_goz]['result_all_contract'] + 1;
				if(count($contract['states'])>0){
					if($contract['states'][count($contract['states'])-1]->name_state == 'Заключен' OR $contract['states'][count($contract['states'])-1]->name_state == 'Заключён') {					
						$itogs[$contract->name_works_goz]['result_all_concluded_contract'] = $itogs[$contract->name_works_goz]['result_all_concluded_contract'] + 1;
						if(is_numeric($contract->amount_reestr))
							$itogs[$contract->name_works_goz]['result_amount_concluded_contract'] = $itogs[$contract->name_works_goz]['result_amount_concluded_contract'] + $contract->amount_reestr;
						if(is_numeric($contract->amount_year_reestr))
							$itogs[$contract->name_works_goz]['result_year_amount_concluded_contract'] = $itogs[$contract->name_works_goz]['result_year_amount_concluded_contract'] + $contract->amount_year_reestr;
					}else{
						$itogs[$contract->name_works_goz]['result_all_formalization_contract'] = $itogs[$contract->name_works_goz]['result_all_formalization_contract'] + 1;
						if(is_numeric($contract->amount_reestr))
							$itogs[$contract->name_works_goz]['result_amount_formalization_contract'] = $itogs[$contract->name_works_goz]['result_amount_formalization_contract'] + $contract->amount_reestr;
						if(is_numeric($contract->amount_year_reestr))
							$itogs[$contract->name_works_goz]['result_year_amount_formalization_contract'] = $itogs[$contract->name_works_goz]['result_year_amount_formalization_contract'] + $contract->amount_year_reestr;
					}
				}else{
					$itogs[$contract->name_works_goz]['result_all_formalization_contract'] = $itogs[$contract->name_works_goz]['result_all_formalization_contract'] + 1;
					if(is_numeric($contract->amount_reestr))
						$itogs[$contract->name_works_goz]['result_amount_formalization_contract'] = $itogs[$contract->name_works_goz]['result_amount_formalization_contract'] + $contract->amount_reestr;
					if(is_numeric($contract->amount_year_reestr))
						$itogs[$contract->name_works_goz]['result_year_amount_formalization_contract'] = $itogs[$contract->name_works_goz]['result_year_amount_formalization_contract'] + $contract->amount_year_reestr;
				}
				
				if($contract->big_deal_reestr != null)
					$itogs[$contract->name_works_goz]['result_big_count_contract'] = $itogs[$contract->name_works_goz]['result_big_count_contract'] + 1;
					
				$itogs[$contract->name_works_goz]['result_amount_implementation_contract'] = $itogs[$contract->name_works_goz]['result_amount_implementation_contract'] + $contract->amount_acts;
				$itogs[$contract->name_works_goz]['result_year_amount_implementation_contract'] = $itogs[$contract->name_works_goz]['result_year_amount_implementation_contract'] + $contract->year_amount_acts;
				
				if(is_numeric($contract->prepayment_reestr))
					$itogs[$contract->name_works_goz]['result_all_prepayment_reestr'] = $itogs[$contract->name_works_goz]['result_all_prepayment_reestr'] + $contract->prepayment_reestr;
				if(is_numeric($contract->invoices))
					$itogs[$contract->name_works_goz]['result_all_invoices'] = $itogs[$contract->name_works_goz]['result_all_invoices'] + $contract->invoices;
				
				$itogs[$contract->name_works_goz]['result_all_prepayment_contract'] = $itogs[$contract->name_works_goz]['result_all_prepayment_contract'] + $contract->prepayments;
				$itogs[$contract->name_works_goz]['result_all_payment_contract'] = $itogs[$contract->name_works_goz]['result_all_payment_contract'] + $contract->payments;
			}
			
			//Разбиение по виду договора
			if(!isset($view_itogs[$contract->name_view_contract]))
			{
				$view_itogs[$contract->name_view_contract] = [];
				$view_itogs[$contract->name_view_contract] += ['result_all_contract' => 1];
				if(count($contract['states'])>0){
					if($contract['states'][count($contract['states'])-1]->name_state == 'Заключен' OR $contract['states'][count($contract['states'])-1]->name_state == 'Заключён') {					
						$view_itogs[$contract->name_view_contract] += ['result_all_concluded_contract' => 1];
						if(is_numeric($contract->amount_reestr))
							$view_itogs[$contract->name_view_contract] += ['result_amount_concluded_contract' => $contract->amount_reestr];
						else
							$view_itogs[$contract->name_view_contract] += ['result_amount_concluded_contract' => 0];
						if(is_numeric($contract->amount_year_reestr))
							$view_itogs[$contract->name_view_contract] += ['result_year_amount_concluded_contract' => $contract->amount_year_reestr];
						else
							$view_itogs[$contract->name_view_contract] += ['result_year_amount_concluded_contract' => 0];
							
						$view_itogs[$contract->name_view_contract] += ['result_all_formalization_contract' => 0];
						$view_itogs[$contract->name_view_contract] += ['result_amount_formalization_contract' => 0];
						$view_itogs[$contract->name_view_contract] += ['result_year_amount_formalization_contract' => 0];
					}else{
						$view_itogs[$contract->name_view_contract] += ['result_all_concluded_contract' => 0];
						$view_itogs[$contract->name_view_contract] += ['result_amount_concluded_contract' => 0];
						$view_itogs[$contract->name_view_contract] += ['result_year_amount_concluded_contract' => 0];
						$view_itogs[$contract->name_view_contract] += ['result_all_formalization_contract' => 1];
						if(is_numeric($contract->amount_reestr))
							$view_itogs[$contract->name_view_contract] += ['result_amount_formalization_contract' => $contract->amount_reestr];
						else
							$view_itogs[$contract->name_view_contract] += ['result_amount_formalization_contract' => 0];
						if(is_numeric($contract->amount_year_reestr))
							$view_itogs[$contract->name_view_contract] += ['result_year_amount_formalization_contract' => $contract->amount_year_reestr];
						else
							$view_itogs[$contract->name_view_contract] += ['result_year_amount_formalization_contract' => 0];
					}
				}else{
					$view_itogs[$contract->name_view_contract] += ['result_all_concluded_contract' => 0];
					$view_itogs[$contract->name_view_contract] += ['result_amount_concluded_contract' => 0];
					$view_itogs[$contract->name_view_contract] += ['result_year_amount_concluded_contract' => 0];
					$view_itogs[$contract->name_view_contract] += ['result_all_formalization_contract' => 1];
					if(is_numeric($contract->amount_reestr))
						$view_itogs[$contract->name_view_contract] += ['result_amount_formalization_contract' => $contract->amount_reestr];
					else
						$view_itogs[$contract->name_view_contract] += ['result_amount_formalization_contract' => 0];
					if(is_numeric($contract->amount_year_reestr))
						$view_itogs[$contract->name_view_contract] += ['result_year_amount_formalization_contract' => $contract->amount_year_reestr];
					else
						$view_itogs[$contract->name_view_contract] += ['result_year_amount_formalization_contract' => 0];
				}
				
				if($contract->big_deal_reestr != null)
					$view_itogs[$contract->name_view_contract] += ['result_big_count_contract' => 1];
				else
					$view_itogs[$contract->name_view_contract] += ['result_big_count_contract' => 0];
					
				$view_itogs[$contract->name_view_contract] += ['result_amount_implementation_contract' => $contract->amount_acts];
				$view_itogs[$contract->name_view_contract] += ['result_year_amount_implementation_contract' => $contract->year_amount_acts];
				
				if(is_numeric($contract->prepayment_reestr))
					$view_itogs[$contract->name_view_contract] += ['result_all_prepayment_reestr' => $contract->prepayment_reestr];
				else
					$view_itogs[$contract->name_view_contract] += ['result_all_prepayment_reestr' => 0];
				if(is_numeric($contract->invoices))
					$view_itogs[$contract->name_view_contract] += ['result_all_invoices' => $contract->invoices];
				else
					$view_itogs[$contract->name_view_contract] += ['result_all_invoices' => 0];
				
				$view_itogs[$contract->name_view_contract] += ['result_all_prepayment_contract' => $contract->prepayments];
				$view_itogs[$contract->name_view_contract] += ['result_all_payment_contract' => $contract->payments];
			}else{
				$view_itogs[$contract->name_view_contract]['result_all_contract'] = $view_itogs[$contract->name_view_contract]['result_all_contract'] + 1;
				if(count($contract['states'])>0){
					if($contract['states'][count($contract['states'])-1]->name_state == 'Заключен' OR $contract['states'][count($contract['states'])-1]->name_state == 'Заключён') {					
						$view_itogs[$contract->name_view_contract]['result_all_concluded_contract'] = $view_itogs[$contract->name_view_contract]['result_all_concluded_contract'] + 1;
						if(is_numeric($contract->amount_reestr))
							$view_itogs[$contract->name_view_contract]['result_amount_concluded_contract'] = $view_itogs[$contract->name_view_contract]['result_amount_concluded_contract'] + $contract->amount_reestr;
						if(is_numeric($contract->amount_year_reestr))
							$view_itogs[$contract->name_view_contract]['result_year_amount_concluded_contract'] = $view_itogs[$contract->name_view_contract]['result_year_amount_concluded_contract'] + $contract->amount_year_reestr;
					}else{
						$view_itogs[$contract->name_view_contract]['result_all_formalization_contract'] = $view_itogs[$contract->name_view_contract]['result_all_formalization_contract'] + 1;
						if(is_numeric($contract->amount_reestr))
							$view_itogs[$contract->name_view_contract]['result_amount_formalization_contract'] = $view_itogs[$contract->name_view_contract]['result_amount_formalization_contract'] + $contract->amount_reestr;
						if(is_numeric($contract->amount_year_reestr))
							$view_itogs[$contract->name_view_contract]['result_year_amount_formalization_contract'] = $view_itogs[$contract->name_view_contract]['result_year_amount_formalization_contract'] + $contract->amount_year_reestr;
					}
				}else{
					$view_itogs[$contract->name_view_contract]['result_all_formalization_contract'] = $view_itogs[$contract->name_view_contract]['result_all_formalization_contract'] + 1;
					if(is_numeric($contract->amount_reestr))
						$view_itogs[$contract->name_view_contract]['result_amount_formalization_contract'] = $view_itogs[$contract->name_view_contract]['result_amount_formalization_contract'] + $contract->amount_reestr;
					if(is_numeric($contract->amount_year_reestr))
						$view_itogs[$contract->name_view_contract]['result_year_amount_formalization_contract'] = $view_itogs[$contract->name_view_contract]['result_year_amount_formalization_contract'] + $contract->amount_year_reestr;
				}
				
				if($contract->big_deal_reestr != null)
					$view_itogs[$contract->name_view_contract]['result_big_count_contract'] = $view_itogs[$contract->name_view_contract]['result_big_count_contract'] + 1;
					
				$view_itogs[$contract->name_view_contract]['result_amount_implementation_contract'] = $view_itogs[$contract->name_view_contract]['result_amount_implementation_contract'] + $contract->amount_acts;
				$view_itogs[$contract->name_view_contract]['result_year_amount_implementation_contract'] = $view_itogs[$contract->name_view_contract]['result_year_amount_implementation_contract'] + $contract->year_amount_acts;
				
				if(is_numeric($contract->prepayment_reestr))
					$view_itogs[$contract->name_view_contract]['result_all_prepayment_reestr'] = $view_itogs[$contract->name_view_contract]['result_all_prepayment_reestr'] + $contract->prepayment_reestr;
				if(is_numeric($contract->invoices))
					$view_itogs[$contract->name_view_contract]['result_all_invoices'] = $view_itogs[$contract->name_view_contract]['result_all_invoices'] + $contract->invoices;
				
				$view_itogs[$contract->name_view_contract]['result_all_prepayment_contract'] = $view_itogs[$contract->name_view_contract]['result_all_prepayment_contract'] + $contract->prepayments;
				$view_itogs[$contract->name_view_contract]['result_all_payment_contract'] = $view_itogs[$contract->name_view_contract]['result_all_payment_contract'] + $contract->payments;
			}
		}
		return array($result, $itogs, $view_itogs);
	}
	
	public function peoBackpack(Request $request)
	{
		$view_contract_str = "contracts.id_counterpartie_contract";
		$view_contract_equal = ">";
		$counterpartie_str = "contracts.id_counterpartie_contract";
		$counterpartie_equal = ">";
		if(isset($_GET['view'])) {
			if(strlen($_GET['view']) > 0) {
				$view_contract = ($_GET['view']);
				$view_contract_str = "view_contracts.name_view_contract";
				$view_contract_equal = "=";
				$link .= "&view=" . $_GET['view'];
			} else
				$view_contract = '';
		} else
			$view_contract = '';
		//$view_contract_str .= " contracts.id_counterpartie_contract ";
		//dd($view_contract_str);
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		$counterpartie = '';
		$counerpartie_name = '';
		if(isset($request['counterpartie'])) {
			if(strlen($request['counterpartie']) > 0) {
				$counerpartie_name = $request['counterpartie'];
				foreach($counterparties as $counter){
					if($counter->name == $counerpartie_name){
						$counterpartie = $counter->id;
						break;
					}
				}
				$counterpartie_str = "id_counterpartie_contract";
				$counterpartie_equal = "=";
			} else
				$counterpartie = '';
		} else
			$counterpartie = '';
		//DB::enableQueryLog();
		$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','number_counterpartie_contract_reestr','item_contract',
										'date_entry_into_force_reestr','date_contract_on_first_reestr','amount_reestr',
										'marketing_reestr', 'procurement_reestr'])
						->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
						->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
						->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
						->where('is_sip_contract', 1)
						->where('archive_contract', 0)
						->where('renouncement_contract', '!=', 1)
						->where('contracts.id_counterpartie_contract','>','-1')
						->where($view_contract_str, $view_contract_equal, $view_contract)
						->where($counterpartie_str, $counterpartie_equal, $counterpartie)
						->where('contracts.number_contract', 'not like', '%‐23‐%')
						//->where('contracts.number_contract', 'like', '%‐30‐%')
						->orderBy('reestr_contracts.id_view_contract', 'asc')
						->orderBy('contracts.id_counterpartie_contract', 'asc')
						->orderBy('contracts.id', 'desc')
						->get();
		//dd(DB::getQueryLog());
		//dd($contracts);
		$new_contracts = [];
		foreach($contracts as $contract) {
			foreach($counterparties as $counter)
				if($contract->id_counterpartie_contract == $counter->id)
					$contract->name_counterpartie_contract = $counter->name;
			
			//Исполнение
			$amount_scores = 0;
			$amount_prepayments = 0;
			$amount_invoices = 0;
			$amount_payments = 0;
			$amount_returns = 0;
			$invoices = Invoice::select(['invoices.amount_p_invoice', 'name_invoices.name', 'is_prepayment_invoice'])
										->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
										->where('id_contract', $contract->id)
										->get();
			foreach($invoices as $score)
				if($score->name == 'Счет на оплату')
					$amount_scores += $score->amount_p_invoice;
				else if($score->name == 'Счет-фактура')
					$amount_invoices += $score->amount_p_invoice;
				else if($score->name == 'Оплата')
					if($score->is_prepayment_invoice == 0)
						$amount_payments += $score->amount_p_invoice;
					else
						$amount_prepayments += $score->amount_p_invoice;
				else if($score->name == 'Возврат')
					$amount_returns += $score->amount_p_invoice;
			$contract->amount_scores = $amount_scores;
			$contract->amount_prepayments = $amount_prepayments;
			$contract->amount_invoices = $amount_invoices;
			$contract->amount_payments = $amount_payments;
			$contract->amount_returns = $amount_returns;
			
			// Убираем выполненные
			$states = State::select(['*'])->where('id_contract', $contract->id)->where('is_work_state', 1)->get();
			if(count($states) > 0) {
				if($states[count($states) - 1]->name_state != 'Выполнен')
					array_push($new_contracts, $contract);
			} else
				array_push($new_contracts, $contract);
		}
		
		//dd($new_contracts);
		//list($result, $itogs, $view_itogs) = LeadershipDepartmentController::CreateResultForPEO($new_contracts);
		//if($only_arr)
		//	return ['contracts'=>$result, 'itogs'=>$itogs, 'view_itogs'=>$view_itogs];

		//dump($contracts);
		//dd($itogs);
		//dd($view_itogs);
		//dd($contracts);
		return view('department.leadership.peo_backpack',['contracts'=>$new_contracts]);
	}
	
	public function peo_act_complete(Request $request)
	{		
		$number_contract = str_replace('-','‐',$request['number_contract']);
		
		$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','id_goz_contract',
										'number_counterpartie_contract_reestr', 'date_contract_on_first_reestr', 'vat_reestr', 'fixed_amount_reestr', 'approximate_amount_reestr', 'amount_reestr'])
						->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
						->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
						->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', '=', 'view_contracts.id')
						->leftJoin('goz_works', 'contracts.id_goz_contract','goz_works.id')
						->where('is_sip_contract', 1)
						->where('contracts.id_counterpartie_contract','>','-1')
						->where('contracts.number_contract', '=', $number_contract)
						->where('archive_contract', 0)
						->get();
		$counterparties = Counterpartie::select(['*'])->where('is_sip_counterpartie', 1)->orderBy('name', 'asc')->get();
		foreach($contracts as $contract) {
			foreach($counterparties as $counter)
				if($contract->id_counterpartie_contract == $counter->id){
					$contract->full_name_counterpartie_contract = $counter->name_full;
					$contract->name_counterpartie_contract = $counter->name;
					$contract->inn_counterpartie_contract = $counter->inn;
					break;
				}
				
			// Сумма в текст
			$amount_reestr_text = LeadershipDepartmentController::num2str($contract->amount_reestr);
			$contract->amount_reestr_text = $amount_reestr_text;
			
			// Находим НДС
			if ($contract->vat_reestr && $contract->amount_reestr) {
				$vat = $contract->amount_reestr / 1.2 * 0.2;
				$contract->vat = $vat;
				$contract->vat_text = LeadershipDepartmentController::num2str($vat);
			}
			
			//Аванс
			$prepayments = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
												->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
												->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
												->where('id_contract', $contract->id)
												->where('name', 'Оплата')
												->where('is_prepayment_invoice', 1)
												->orderBy('invoices.number_invoice', 'asc')
												->get();
			$amount_prepayment = 0;
			foreach ($prepayments as $prepayment)
			{
				$amount_prepayment += $prepayment->amount_p_invoice;
			}
			$contract->prepayment = $amount_prepayment;
			
			// Осталось перечислить
			$contract->remainder = $contract->amount_reestr - $contract->prepayment;
			$contract->remainder_text = LeadershipDepartmentController::num2str($contract->remainder);
			
			if ($contract->vat_reestr && $contract->amount_reestr) {
				$vat_remainder = $contract->remainder / 1.2 * 0.2;
				$contract->vat_remainder = $vat_remainder;
				$contract->vat_remainder_text = LeadershipDepartmentController::num2str($vat_remainder);
			}
		}
		// Акты
		$results = [];
		foreach($contracts as $contract)
		{
			$secondDepartmentTours = SecondDepartmentTour::select(['*', 'second_department_tours.id', DB::RAW('CAST(second_department_tours.number_duty AS UNSIGNED) AS cast_number_duty')])
																	->where('second_department_tours.id_contract', $contract->id)
																	->orderBy('cast_number_duty', 'asc')
																	->orderBy('second_department_tours.number_duty', 'asc')
																	->get();
			$secondDepartmentSbTours = SecondDepartmentSbTour::select(['*', 'second_department_sb_tours.id'])
																	->where('second_department_sb_tours.id_contract', $contract->id)
																	->orderBy(DB::raw('(second_department_sb_tours.number_duty+0)'), 'asc')
																	->get();
			$secondDepartmentUsTours = SecondDepartmentUsTour::select(['*'])
																	->where('second_department_us_tours.id_contract', $contract->id)
																	->orderBy(DB::raw("(second_department_us_tours.number_duty+0)"), 'asc')
																	->get();
			foreach($secondDepartmentTours as $tour){
				$acts = SecondDepartmentAct::select()->where('id_second_tour', $tour->id)->get();
				$acts->number_duty = $tour->number_duty;
				$acts->number_telegram = $tour->number_telegram;
				$acts->date_telegram = $tour->date_telegram;
				array_push($results, $acts);
			}
			foreach($secondDepartmentSbTours as $tour){
				$acts_sb = SecondDepartmentAct::select()->where('id_second_sb_tour', $tour->id)->get();
				$acts_sb->number_duty = $tour->number_duty;
				$acts_sb->number_telegram = $tour->number_telegram;
				$acts_sb->date_telegram = $tour->date_telegram;
				array_push($results, $acts_sb);
			}
			foreach($secondDepartmentUsTours as $tour){
				$acts_us = SecondDepartmentAct::select()->where('id_second_us_tour', $tour->id)->get();
				$acts_us->number_duty = $tour->number_duty;
				$acts_us->number_telegram = $tour->number_telegram;
				$acts_us->date_telegram = $tour->date_telegram;
				array_push($results, $acts_us);
			}
			//Акты для Услуг ГН и Услуг ВН
			$acts = SecondDepartmentAct::select()->where('id_contract', $contract->id)->get();
			array_push($results, $acts);
			$contract->results = $results;
		}

		return view('department.leadership.peo_act_complete', ['contracts'=>$contracts]);
	}
	
	public function peo_isp_period_complete(Request $request)
	{
		$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','item_contract','id_goz_contract','goz_works.name_works_goz','id_view_contract', 'view_contracts.name_view_contract',
										'big_deal_reestr','amoun_implementation_contract','comment_implementation_contract',
										'amount_contract_reestr','amount_reestr', 'vat_reestr', 'amount_year_reestr','amount_contract_year_reestr',
										'date_contact','year_contract','prepayment_reestr','percent_prepayment_reestr','date_maturity_reestr'])
						->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
						->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
						->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
						->join('goz_works', 'contracts.id_goz_contract', '=', 'goz_works.id')
						->where('is_sip_contract', 1)
						->where('archive_contract', 0)
						->where('renouncement_contract', '!=', 1)
						->where('contracts.id_counterpartie_contract','>','-1')
						->where('contracts.number_contract', 'not like', '%‐23‐%')
						->where('name_view_contract', 'Испытания контрольные')
						->orderBy('reestr_contracts.id_view_contract', 'asc')
						->orderBy('contracts.id_counterpartie_contract', 'asc')
						->orderBy('contracts.id', 'desc')
						->get();

		$period1 = isset($request['date_begin']) ? DATE('Y-m-d', strtotime($request['date_begin'])) : date('Y', time()) . '-01-01';
		$period2 = isset($request['date_end']) ? DATE('Y-m-d', strtotime($request['date_end'])) : date('Y-m-d', time());

		$new_contracts = [];
		$counterparties = Counterpartie::select(['*'])->where('is_sip_counterpartie', 1)->orderBy('name', 'asc')->get();
		foreach($contracts as $contract){
			foreach($counterparties as $counter)
				if($contract->id_counterpartie_contract == $counter->id){
					$contract->full_name_counterpartie_contract = $counter->name_full;
					$contract->name_counterpartie_contract = $counter->name;
					$contract->inn_counterpartie_contract = $counter->inn;
					break;
				}

			$query = "SELECT * 
						FROM states
						WHERE id_contract='" . $contract->id . "'
						AND is_work_state=1
						AND (STR_TO_DATE(date_state,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')";
			$states = DB::SELECT($query);
			//$states = State::select(['*'])->where('id_contract', $contract->id)->where('is_work_state', 1)->get();
			if(count($states) > 0){
				if($states[count($states) - 1]->name_state == 'Выполнен')
					array_push($new_contracts, $contract);
			}
		}
		
		$all_amount_reestr = 0;
		$itogs = [];
		foreach($new_contracts as $contract){
			$secondDepartmentTours = SecondDepartmentTour::select(['*', 'second_department_tours.id', DB::RAW('CAST(second_department_tours.number_duty AS UNSIGNED) AS cast_number_duty')])
																	->leftJoin('second_department_units', 'id_unit', 'second_department_units.id')
																	->where('second_department_tours.id_contract', $contract->id)
																	->orderBy('cast_number_duty', 'asc')
																	->orderBy('second_department_tours.number_duty', 'asc')
																	->get();
			$secondDepartmentSbTours = SecondDepartmentSbTour::select(['*', 'second_department_sb_tours.id'])
																	->leftJoin('second_department_units', 'id_unit', 'second_department_units.id')
																	->where('second_department_sb_tours.id_contract', $contract->id)
																	->orderBy(DB::raw('(second_department_sb_tours.number_duty+0)'), 'asc')
																	->get();
			$secondDepartmentUsTours = SecondDepartmentUsTour::select(['*'])
																	->where('second_department_us_tours.id_contract', $contract->id)
																	->orderBy(DB::raw("(second_department_us_tours.number_duty+0)"), 'asc')
																	->get();
			$number_duty = '';
			$count_duty = [];
			foreach($secondDepartmentTours as $tour){
				$number_duty .= $tour->number_duty . ', ';
				if (!in_array($tour->name_unit, array_keys($count_duty)))
					$count_duty += [$tour->name_unit => $tour->count_elements];
				else
					$count_duty[$tour->name_unit] += $tour->count_elements;
			}
			foreach($secondDepartmentSbTours as $tour){
				$number_duty .= $tour->number_duty . ', ';
				if (!in_array($tour->name_unit, array_keys($count_duty)))
					$count_duty += [$tour->name_unit => $tour->count_elements];
				else
					$count_duty[$tour->name_unit] += $tour->count_elements;
			}
			foreach($secondDepartmentUsTours as $tour){
				$number_duty .= $tour->number_duty . ', ';
				if (!in_array($tour->name_unit, array_keys($count_duty)))
					$count_duty += [$tour->name_unit => $tour->count_elements];
				else
					$count_duty[$tour->name_unit] += $tour->count_elements;
			}
			$number_duty = trim($number_duty);
			if(strlen($number_duty) > 0)
				if($number_duty[strlen($number_duty) - 1] == ',')
					$number_duty = substr($number_duty, 0, strlen($number_duty) - 1);
			$contract->number_duty = $number_duty;
			
			$count_duty_text = '';
			foreach($count_duty as $key=>$value)
				$count_duty_text .= $value . ' ' . $key . ', ';
			$count_duty_text = trim($count_duty_text);
			if (strlen($count_duty_text) > 0)
				if ($count_duty_text[strlen($count_duty_text) - 1] == ',')
					$count_duty_text = substr($count_duty_text, 0, strlen($count_duty_text) - 1);
			$contract->count_duty = $count_duty_text;
			
			// Суммы
			if ($contract->vat_reestr)
				$contract->amount_reestr = $contract->amount_reestr / 1.2;
			$all_amount_reestr += $contract->amount_reestr;
			
			if(!in_array($contract->name_works_goz, array_keys($itogs)))
				$itogs += [$contract->name_works_goz => $contract->amount_reestr];
			else
				$itogs[$contract->name_works_goz] += $contract->amount_reestr;
		}
		
		return view('department.leadership.peo_isp_period_complete',['period1'=>$period1, 'period2'=>$period2, 'contracts'=>$new_contracts, 'all_amount_reestr'=>$all_amount_reestr, 'itogs'=>$itogs]);
	}
	
	public function invoice()
	{
		$year_str = "contracts.id_counterpartie_contract";
		$year_equal = ">";
		$view_contract_str = "contracts.id_counterpartie_contract";
		$view_contract_equal = ">";
		$counterpartie_str = "contracts.id_counterpartie_contract";
		$counterpartie_equal = ">";
		$link = '';
		$year = '';
		if(isset($_GET['year'])) {
			if(strlen($_GET['year']) > 0) {
				$year = ($_GET['year']);
				$year_str = "contracts.year_contract";
				$year_equal = "=";
				$link .= "&year=" . $_GET['year'];
			}
		}
		$view_contract = '';
		if(isset($_GET['view'])) {
			if(strlen($_GET['view']) > 0) {
				$view_contract = ($_GET['view']);
				$view_contract_str = "view_contracts.name_view_contract";
				$view_contract_equal = "=";
				$link .= "&view=" . $_GET['view'];
			}
		}
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		$counterpartie = '';
		$counerpartie_name = '';
		if(isset($_GET['counterpartie'])) {
			if(strlen($_GET['counterpartie']) > 0) {
				$counerpartie_name = $_GET['counterpartie'];
				foreach($counterparties as $counter){
					if($counter->name == $counerpartie_name){
						$counterpartie = $counter->id;
						break;
					}
				}
				$counterpartie_str = "id_counterpartie_contract";
				$counterpartie_equal = "=";
				$link .= "&counterpartie=" . $_GET['counterpartie'];
			}
		}
		$invoices = Invoice::select(['contracts.number_contract','reestr_contracts.id_view_contract','contracts.id_counterpartie_contract','view_contracts.name_view_contract','number_invoice',
							'date_invoice','amount_p_invoice','name_invoices.name'])
							->join('contracts', 'invoices.id_contract', '=', 'contracts.id')
							->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
							->Leftjoin('view_invoices', 'invoices.id_view_invoice', '=', 'view_invoices.id')
							->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
							->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
							->where('contracts.id_counterpartie_contract','>','-1')
							->where($year_str, $year_equal, $year)
							->where($view_contract_str, $view_contract_equal, $view_contract)
							->where($counterpartie_str, $counterpartie_equal, $counterpartie)
							->where(function($query) {
								$query->where('name_invoices.name', 'Оплата')
								->orWhere('name_invoices.name', 'Счет-фактура');
							})
							->orderBy('reestr_contracts.id_view_contract', 'asc')
							->orderBy('contracts.id_counterpartie_contract', 'asc')
							->orderBy('contracts.id', 'desc')
							->get();
		foreach($invoices as $invoice)
			foreach($counterparties as $counter)
				if($invoice->id_counterpartie_contract == $counter->id)
					$invoice->name_counterpartie_contract = $counter->name;
		$result = [];
		foreach($invoices as $invoice)
		{
			if(!in_array($invoice->id_view_work_contract, $result))
				$result += [$invoice->name_view_work=>[]];
			if(!in_array($invoice->name_counterpartie_contract, $result[$invoice->name_view_work]))
				$result[$invoice->name_view_work] += [$invoice->name_counterpartie_contract=>[]];
			array_push($result[$invoice->name_view_work][$invoice->name_counterpartie_contract], $invoice);
		}
		//dump($result);
		return view('department.leadership.invoice',['invoices'=>$result]);
	}
	
	//Задолженность по авансам и выполненным работам перед ФКП "НТИИМ"
	public function duty()
	{
		$year_str = "contracts.id_counterpartie_contract";
		$year_equal = ">";
		$view_contract_str = "contracts.id_counterpartie_contract";
		$view_contract_equal = ">";
		$counterpartie_str = "contracts.id_counterpartie_contract";
		$counterpartie_equal = ">";
		$link = '';
		if(isset($_GET['year'])) {
			if(strlen($_GET['year']) > 0) {
				$year = ($_GET['year']);
				$year_str = "contracts.year_contract";
				$year_equal = "=";
				$link .= "&year=" . $_GET['year'];
			} else
				$year = '';
		} else
			$year = '';
		if(isset($_GET['view'])) {
			if(strlen($_GET['view']) > 0) {
				$view_contract = ($_GET['view']);
				$view_contract_str = "view_contracts.name_view_contract";
				$view_contract_equal = "=";
				$link .= "&view=" . $_GET['view'];
			} else
				$view_contract = '';
		} else
			$view_contract = '';
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		$counterpartie = '-1';
		$counerpartie_name = '';
		if(isset($_GET['counterpartie'])) {
			if(strlen($_GET['counterpartie']) > 0) {
				$counerpartie_name = $_GET['counterpartie'];
				foreach($counterparties as $counter){
					if($counter->name == $counerpartie_name){
						$counterpartie = $counter->id;
						break;
					}
				}
				$counterpartie_str = "id_counterpartie_contract";
				$counterpartie_equal = "=";
				$link .= "&counterpartie=" . $_GET['counterpartie'];
			}
		} else
			$counterpartie = '';
		if(isset($_GET['date_begin']) && isset($_GET['date_end'])){
			$period1 = DATE('Y-m-d', strtotime($_GET['date_begin']));
			$period2 = DATE('Y-m-d', strtotime($_GET['date_end']));
			/*$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','id_view_contract','amount_reestr','number_counterpartie_contract_reestr','name_view_contract',
											'prepayment_order_reestr','date_contract_on_first_reestr','year_contract','contracts.comment_implementation_contract','prepayment_reestr',
											DB::raw("STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') as filter")])
							->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
							->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
							->where('is_sip_contract', 1)
							->where('contracts.id_counterpartie_contract','>','-1')
							->where($counterpartie_str, $counterpartie_equal, $counterpartie)
							//->whereBetween('filter', array(DATE('Y-m-d', strtotime($period1)),DATE('Y-m-d', strtotime($period2))))
							->orderBy('reestr_contracts.id_view_contract', 'asc')
							->orderBy('contracts.id_counterpartie_contract', 'asc')
							->orderBy('contracts.id', 'desc')
							->get();*/
			$contracts = DB::select("SELECT contracts.id,id_counterpartie_contract,number_contract,id_view_contract,amount_reestr,
									number_counterpartie_contract_reestr,name_view_contract,prepayment_order_reestr,
									date_contract_on_first_reestr,year_contract,contracts.comment_implementation_contract,prepayment_reestr 
									FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id 
									LEFT JOIN view_contracts ON reestr_contracts.id_view_contract=view_contracts.id 
									WHERE is_sip_contract=1 AND ".$counterpartie_str.$counterpartie_equal.$counterpartie." AND (STR_TO_DATE(date_contract_on_first_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "' OR STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')");
			$contracts = collect($contracts);
		}else{
			$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','id_view_contract','amount_reestr','number_counterpartie_contract_reestr','name_view_contract',
											'prepayment_order_reestr','date_contract_on_first_reestr','year_contract','contracts.comment_implementation_contract','prepayment_reestr'])
							->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
							->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
							->where('is_sip_contract', 1)
							->where('contracts.id_counterpartie_contract','>','-1')->where($year_str, $year_equal, $year)
							->where($view_contract_str, $view_contract_equal, $view_contract)->where($counterpartie_str, $counterpartie_equal, $counterpartie)
							->orderBy('reestr_contracts.id_view_contract', 'asc')
							->orderBy('contracts.id_counterpartie_contract', 'asc')
							->orderBy('contracts.id', 'desc')
							->get();
		}
		//dd($contracts);
		foreach($contracts as $contract)
			foreach($counterparties as $counter)
				if($contract->id_counterpartie_contract == $counter->id)
					$contract->name_counterpartie_contract = $counter->name;
		$contracts = $contracts->sortBy('name_counterpartie_contract');
		$result = [];
		foreach($contracts as $contract)
		{
			//Проверка на то, что контракт заключен, но не выполнен!
			/*if($contract->comment_implementation_contract == 'Выполнен')
				continue;
			
			$is_zakl = false;
			$states = State::select(['*'])->where('id_contract', $contract->id)->get();
			foreach($states as $state)
				if($state->name_state == 'Заключен' OR $state->name_state == 'Заключён')
					$is_zakl = true;
			if(!$is_zakl)
				continue;*/

			//Аванс
			$prepayments = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
												->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
												->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
												->where('id_contract', $contract->id)
												->where('name', 'Аванс')
												->orderBy('invoices.number_invoice', 'asc')
												->get();
			//Счет-фактура
			$invoices = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
												->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
												->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
												->where('id_contract', $contract->id)
												->where('name', 'Счет-фактура')
												->orderBy('invoices.number_invoice', 'asc')
												->get();
			//Оплата
			$payments = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
												->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
												->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
												->where('id_contract', $contract->id)
												->where('name', 'Оплата')
												->orderBy('invoices.number_invoice', 'asc')
												->get();
			if(count($prepayments) > 0 || count($invoices) || count($payments)){
				$full_prepayments = 0;
				foreach($prepayments as $prepayment)
					$full_prepayments += $prepayment->amount_p_invoice;
				$contract->full_prepayments = $full_prepayments;
				$contract->prepayments = $prepayments;
				
				$full_invoices = 0;
				foreach($invoices as $invoice)
					$full_invoices += $invoice->amount_p_invoice;
				$contract->full_invoices = $full_invoices;
				$contract->invoices = $invoices;
				
				$full_payments = 0;
				$full_prepayment_payments = 0;
				foreach($payments as $payment)
					if($payment->is_prepayment_invoice == 0)
						$full_payments += $payment->amount_p_invoice;
					else
						$full_prepayment_payments += $payment->amount_p_invoice;
				$contract->payments = $payments;
				
				//Задолженность по авансам
				if($full_prepayments - $full_prepayment_payments > 0)
					$contract->duty_prepayments = $full_prepayments - $full_prepayment_payments;
				else
					$contract->duty_prepayments = 0;
				
				//Задолженость за вып. рабоыт
				$contract->duty_payments = $full_invoices - $full_prepayment_payments - $full_payments;
				if(!isset($_GET['full_report']))
					if($full_invoices - $full_prepayment_payments - $full_payments > 0)
						$contract->duty_payments = $full_invoices - $full_prepayment_payments - $full_payments;
					else
						$contract->duty_payments = 0;
				
				if(!in_array($contract->name_counterpartie_contract, $result))
					$result += [$contract->name_counterpartie_contract=>[]];
				if(!in_array($contract->name_view_contract, $result[$contract->name_counterpartie_contract]))
					$result[$contract->name_counterpartie_contract] += [$contract->name_view_contract=>[]];
				array_push($result[$contract->name_counterpartie_contract][$contract->name_view_contract], $contract);
			}
		}
		//dump($contracts);
		return view('department.leadership.duty',['contracts'=>$result]);
	}
	
	public function create_report()
	{
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		if(isset($_GET['counterpartie']))
			$counterpartie = $_GET['counterpartie'];
		else
			$counterpartie = '';
		
		$view_contracts = ViewContract::all();
		if(isset($_GET['view']))
			$view_contract = ($_GET['view']);
		else
			$view_contract = '';
		
		$years = DB::SELECT('SELECT year_contract FROM contracts GROUP BY year_contract ORDER BY year_contract DESC');
		if(isset($_GET['year']))
			$year = ($_GET['year']);
		else
			$year = '';
		return view('department.leadership.create',['year'=>$year,
													'years'=>$years,
													'viewContracts'=>$view_contracts,
													'viewContract'=>$view_contract,
													'counterpartie'=>$counterpartie,
													'counterparties'=>$counterparties
		]);
	}
	
	public function print_report(Request $request)
	{
		//$select = DB::SELECT('SELECT year_contract FROM contracts GROUP BY year_contract ORDER BY year_contract DESC');
		//dump($request->all());
		$str_select = '';
		$arr_select = [];
		$is_counterpartie = false;
		if(isset($request['selectColumn'])){
			foreach($request['selectColumn'] as $select)
				if($select != null){
					if(strlen($str_select) > 0)
						$str_select .= ', ' . $select;
					else
						$str_select .= $select;
					switch($select){
						case 'contracts.number_contract':
							array_push($arr_select, ['Номер договора'=>'number_contract']);
							break;
						case 'contracts.id_counterpartie_contract':
							array_push($arr_select, ['Контрагент'=>'name_counterpartie_contract']);
							$is_counterpartie = true;
							break;
						case 'view_contracts.name_view_contract':
							array_push($arr_select, ['Вид договора'=>'name_view_contract']);
							break;
						case 'contracts.name_work_contract':
							array_push($arr_select, ['Наименование работ'=>'name_work_contract']);
							break;
						case 'type_documents.name_type_document':
							array_push($arr_select, ['Тип документа'=>'name_type_document']);
							break;
						case 'contracts.comment_implementation_contract':
							array_push($arr_select, ['Стадия выполнения'=>'comment_implementation_contract']);
							break;
						case 'contracts.year_contract':
							array_push($arr_select, ['Год'=>'year_contract']);
							break;
						default:
							break;
					}
				}
			$str_where = '';
			if(isset($request['year']))
				if($request['year'] != null)
					$str_where .= ' AND contracts.year_contract = ' . $request['year'];
			if(isset($request['view']))
				if($request['view'] != null)
					$str_where .= ' AND view_contracts.name_view_contract = ' . "'" . $request['view'] . "'";
			$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
			$counerpartie_name = '';
			$counterpartie = '';
			if(isset($request['counterpartie'])){
				if($request['counterpartie'] != null){
					$counerpartie_name = $request['counterpartie'];
					foreach($counterparties as $counter){
						if($counter->name == $counerpartie_name){
							$counterpartie = $counter->id;
							$str_where .= ' AND contracts.id_counterpartie_contract = ' . $counterpartie;
							break;
						}
					}
				}
			}
			//dd($str_where);
			$select = DB::SELECT('SELECT ' . $str_select . ' FROM contracts LEFT JOIN reestr_contracts ON contracts.id = reestr_contracts.id_contract_reestr LEFT JOIN view_contracts ON view_contracts.id = reestr_contracts.id_view_contract LEFT JOIN type_documents ON reestr_contracts.type_document_reestr = type_documents.id WHERE id_counterpartie_contract > 0 AND contracts.deleted_at IS NULL' . $str_where . ' ORDER BY contracts.id DESC');
			if($is_counterpartie)
				foreach($select as $contract)
					foreach($counterparties as $counter)
						if($contract->id_counterpartie_contract == $counter->id)
							$contract->name_counterpartie_contract = $counter->name;
		}else{
			$select = '';
			$arr_select = '';
		}
		return view('department.leadership.print_report',['result'=>$select, 'headers'=>$arr_select]);
	}
	
	public function num2str($num)
	{
		$nul = 'ноль';
		$ten = array(
			array('', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'),
			array('', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять')
		);
		$a20 = array('десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать');
		$tens = array(2=>'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто');
		$hundred = array('', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот');
		$unit = array(
			array('копейка', 'копейки', 'копеек', 1),
			array('рубль', 'рубля', 'рублей', 0),
			array('тысяча', 'тысячи', 'тысяч', 1),
			array('миллион', 'миллиона', 'миллионов', 0),
			array('миллиард', 'миллиарда', 'миллиардов', 0)
		);
		
		$parts = explode('.', sprintf("%015.2f", floatval($num)));
		$rub = $parts[0];
		$kop = $parts[1];
		
		$out = array();
		if (intval($rub) > 0) {
			foreach (str_split($rub, 3) as $uk=>$v) {
				if (!intval($v))
					continue;
				$uk = sizeof($unit)-$uk-1;
				$gender = $unit[$uk][3];
				list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));
				
				$out[] = $hundred[$i1];
				if ($i2 > 1)
					$out[] = $tens[$i2] . ' ' . $ten[$gender][$i3];
				else
					$out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3];
				
				if ($uk > 1)
					$out[] = LeadershipDepartmentController::morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
			}
		}
		else
			$out[] = $nul;
			
		$out[] = LeadershipDepartmentController::morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]);
		$out[] = $kop . ' ' . LeadershipDepartmentController::morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]);
		return trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));
	}
	
	private function morph($n, $f1, $f2, $f5)
	{
		$n = abs(intval($n)) % 100;
		if ($n > 10 && $n < 20)
			return $f5;
		$n = $n % 10;
		if ($n > 1 && $n < 5)
			return $f2;
		if ($n == 1)
			return $f1;
		return $f5;
	}
	
	public function doublenumber2string($number)
	{
		// Ошибка!! (Например, 80182.75);
		dd('asd');
		$parts = explode('.', $number);
		$first = LeadershipDepartmentController::number2string($parts[0]);
		$second = LeadershipDepartmentController::number2string($parts[1], false) . ' коп.';
		return $first . ' ' . $second;
	}
	
	public function number2string($number, $isName = true)
	{
		static $dic = array(
			array(
				-2 => 'две',
				-1 => 'одна',
				1 => 'один',
				2 => 'два',
				3 => 'три',
				4 => 'четыре',
				5 => 'пять',
				6 => 'шесть',
				7 => 'семь',
				8 => 'восемь',
				9 => 'девять',
				10 => 'десять',
				11 => 'одиннадцать',
				12 => 'двенадцать',
				13 => 'тринадцать',
				14 => 'четырнадцать',
				15 => 'пятнадцать',
				16 => 'шестнадцать',
				17 => 'семнадцать',
				18 => 'восемнадцать',
				19 => 'девятнадцать',
				20 => 'двадцать',
				30 => 'тридцать',
				40 => 'сорок',
				50 => 'пятьдесят',
				60 => 'шестьдесят',
				70 => 'семьдесят',
				80 => 'восемьдесят',
				90 => 'девяносто',
				100 => 'сто',
				200 => 'двести',
				300 => 'триста',
				400 => 'четыреста',
				500 => 'пятьсот',
				600 => 'шестьсот',
				700 => 'семьсот',
				800 => 'восемьсот',
				900 => 'девятьсот'
			),
			
			array(
				array('рубль', 'рубля', 'рублей'),
				array('тысяча', 'тысячи', 'тысяч'),
				array('миллион', 'миллиона', 'миллионов'),
				array('миллиард', 'миллиарда', 'миллиардов'),
				array('триллион', 'триллиона', 'триллионов'),
				array('квадриллион', 'квадриллиона', 'квадриллионов')
			),
			
			array(
				2, 0, 1, 1, 1, 2
			)
		);
		
		$string = array();
		
		$number = str_pad($number, ceil(strlen($number)/3)*3, 0, STR_PAD_LEFT);
		
		$parts = array_reverse(str_split($number, 3));
		
		foreach ($parts as $i=>$part) {
			if ($part > 0) {
				$digits = array();
				
				if ($part > 99) {
					$digits[] = floor($part / 100) * 100;
				}
				
				if ($mod1=$part%100) {
					$mod2 = $part % 10;
					$flag = $i==1 && $mod1 != 11 && $mod1 != 12 && $mod2 < 3 ? -1 : 1;
					
					if ($mod1 < 20 || !$mod2) {
						$digits[] = $flag * $mod1;
					}
					else {
						$digits[] = floor($mod1 / 10) * 10;
						$digits[] = $flag * $mod2;
					}
				}
				
				$last = abs(end($digits));
				
				foreach ($digits as $j=>$digit) {
					$digits[$j] = $dic[0][$digit];
				}
				
				if ($isName)
					$digits[] = $dic[1][$i][(($last%=100)>4 && $last<20) ? 2 : $dic[2][min($last%10,5)]];
				
				array_unshift($string, join(' ', $digits));
			}
		}
		
		return join(' ', $string);
	}
}
