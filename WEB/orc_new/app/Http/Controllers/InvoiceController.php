<?php

namespace App\Http\Controllers;

use Auth;
use App\Contract;
use App\ViewWork;
use App\Counterpartie;
use App\Invoice;
use App\Curator;
use App\Resolution;
use App\ViewContract;
use App\Department;
use App\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$link = '';
		//Поиск
		$search_name = '';
		if(isset($_GET['search_name'])) {
			if(strlen($_GET['search_name']) > 0) {
				$search_name = $_GET['search_name'];
				$link .= "&search_name=" . $_GET['search_name'];
			}
		}
		$search_value = '';
		if(isset($_GET['search_value'])) {
			if(strlen($_GET['search_value']) > 0) {						
				$search_value = $_GET['search_value'];
				if(isset($_GET['search_name']))
					if($_GET['search_name'] == 'number_contract')
						$search_value = str_replace('-','‐',$search_value);
				$link .= "&search_value=" . $search_value;
			}
		}
		//Сортировка (щелкаем по треугольничкам)
		$sort = "id";
		$sort_span = "";
		$re_sort = "desc";
		if (isset($_GET["sorting"])) {
			$sort  = $_GET["sorting"];
			$sort_span = "▼";
			$link .= '&sorting=' . $_GET["sorting"];
		}
		$sort_p = "desc";
		if (isset($_GET["sort_p"])) {
			$sort_p  = $_GET["sort_p"];
			$link .= '&sort_p=' . $_GET["sort_p"];
			if($_GET["sort_p"] == "asc"){
				$sort_span = "▲";
				$re_sort = "desc";
			}else{
				$re_sort = "asc";
			}
		}
		//Фильтры
		$year_str = "contracts.id_counterpartie_contract";
		$year_equal = ">";
		$view_contract_str = "contracts.id_counterpartie_contract";
		$view_contract_equal = ">";
		$counterpartie_str = "contracts.id_counterpartie_contract";
		$counterpartie_equal = ">";
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
		$counterparties = Counterpartie::select(['*'])->where('is_sip_counterpartie', 1)->orderBy('name', 'asc')->get();
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
		if($search_name != '' && $search_value != ''){
			$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','id_goz_contract','reestr_contracts.id_view_contract', 'view_contracts.name_view_contract',
											'all_count_contract','concluded_count_contract','amount_concluded_contract','formalization_count_contract',
											'amount_formalization_contract','big_deal_contract','amoun_implementation_contract','comment_implementation_contract',
											'prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract','date_contact',
											'year_contract', 'item_contract', DB::raw('CAST(number_pp as UNSIGNED) as cast_number_pp')])
							->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
							->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
							->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
							->where('is_sip_contract', 1)
							->where($search_name, 'like', '%' . $search_value . '%')
							->where('contracts.id_counterpartie_contract','>','-1')->where($year_str, $year_equal, $year)
							->where($view_contract_str, $view_contract_equal, $view_contract)->where($counterpartie_str, $counterpartie_equal, $counterpartie)
							->orderBy($sort, $sort_p)
							->offset($start)
							->limit($paginate_count)
							->get();
			$contract_count = Contract::select()->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', '=', 'view_contracts.id')->where('is_sip_contract', 1)->where($search_name, 'like', '%' . $search_value . '%')->where('contracts.id_counterpartie_contract','>',-1)->where($year_str, $year_equal, $year)
												->where($view_contract_str, $view_contract_equal, $view_contract)->where($counterpartie_str, $counterpartie_equal, $counterpartie)->count();
		}else{
			$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','id_goz_contract','reestr_contracts.id_view_contract', 'view_contracts.name_view_contract',
											'all_count_contract','concluded_count_contract','amount_concluded_contract','formalization_count_contract',
											'amount_formalization_contract','big_deal_contract','amoun_implementation_contract','comment_implementation_contract',
											'prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract','date_contact',
											'year_contract', 'item_contract', DB::raw('CAST(number_pp as UNSIGNED) as cast_number_pp')])
							->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
							->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
							->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
							->where('is_sip_contract', 1)
							->where('contracts.id_counterpartie_contract','>','-1')->where($year_str, $year_equal, $year)
							->where($view_contract_str, $view_contract_equal, $view_contract)->where($counterpartie_str, $counterpartie_equal, $counterpartie)
							->orderBy($sort, $sort_p)
							->offset($start)
							->limit($paginate_count)
							->get();
			$contract_count = Contract::select()->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', '=', 'view_contracts.id')->where('is_sip_contract', 1)->where('contracts.id_counterpartie_contract','>',-1)->where($year_str, $year_equal, $year)
												->where($view_contract_str, $view_contract_equal, $view_contract)->where($counterpartie_str, $counterpartie_equal, $counterpartie)->count();

		}
		$prev_page = $page - 1 > 0 ? (int)($page-1) : '';
		$next_page = $page + 1 <= (int)ceil($contract_count/$paginate_count) ? (int)($page+1) : '';
		$years = DB::SELECT('SELECT year_contract FROM contracts GROUP BY year_contract ORDER BY year_contract DESC');
		$view_works = ViewWork::all();
		$view_contracts = ViewContract::all();
		foreach($contracts as $contract)
			foreach($counterparties as $counter)
				if($contract->id_counterpartie_contract == $counter->id)
					$contract->name_counterpartie_contract = $counter->name;
		$departments = Department::select()->orderBy('index_department', 'asc')->get();
		$all_view_contracts = ViewContract::select('*')->get();
        return view('department.invoice.main',['contracts' => $contracts,
													'years' => $years,
													'year' => $year,
													'viewWorks' => $view_works,
													'viewWork' => '',//$view_work,
													'viewContracts' => $view_contracts,
													'viewContract' => $view_contract,
													'counterparties' => $counterparties,
													'counterpartie' => $counerpartie_name,
													'departments' => $departments,
													'all_view_contracts' => $all_view_contracts,
													'search_name'=>$search_name,
													'search_value'=>$search_value,
													'sort'=>$sort, 
													'sort_span'=>$sort_span, 
													're_sort'=>$re_sort,
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
	public function create_score($id)
    {
		$contract = Contract::select(['id','id_counterpartie_contract','number_contract'])
							->where('id',$id)->get()[0];
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		foreach($counterparties as $counter)
			if($contract->id_counterpartie_contract == $counter->id)
				$contract->name_counterpartie_contract = $counter->name;
		$view_invoices = DB::SELECT('SELECT * FROM view_invoices');
		$name_invoices = DB::SELECT('SELECT * FROM name_invoices');
		$n_invoice = "Счет на оплату";
        return view('department.invoice.invoice', ['invoice'=>[],
													'contract'=>$contract,
													'view_invoices'=>$view_invoices,
													'name_invoices'=>$name_invoices,
													'n_invoice'=>$n_invoice]);
    }
	public function create_prepayment($id)
    {
		$contract = Contract::select(['id','id_counterpartie_contract','number_contract'])
							->where('id',$id)->get()[0];
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		foreach($counterparties as $counter)
			if($contract->id_counterpartie_contract == $counter->id)
				$contract->name_counterpartie_contract = $counter->name;
		$view_invoices = DB::SELECT('SELECT * FROM view_invoices');
		$name_invoices = DB::SELECT('SELECT * FROM name_invoices');
		$n_invoice = "Аванс";
        return view('department.invoice.invoice', ['invoice'=>[],
													'contract'=>$contract,
													'view_invoices'=>$view_invoices,
													'name_invoices'=>$name_invoices,
													'n_invoice'=>$n_invoice]);
    }
	public function create_invoice($id)
    {
		$contract = Contract::select(['id','id_counterpartie_contract','number_contract'])
							->where('id',$id)->get()[0];
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		foreach($counterparties as $counter)
			if($contract->id_counterpartie_contract == $counter->id)
				$contract->name_counterpartie_contract = $counter->name;
		$view_invoices = DB::SELECT('SELECT * FROM view_invoices');
		$name_invoices = DB::SELECT('SELECT * FROM name_invoices');
		$n_invoice = "Счет-фактура";
        return view('department.invoice.invoice', ['invoice'=>[],
													'contract'=>$contract,
													'view_invoices'=>$view_invoices,
													'name_invoices'=>$name_invoices,
													'n_invoice'=>$n_invoice]);
    }
    public function create_payment($id)
    {
		$contract = Contract::select(['id','id_counterpartie_contract','number_contract'])
							->where('id',$id)->get()[0];
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		foreach($counterparties as $counter)
			if($contract->id_counterpartie_contract == $counter->id)
				$contract->name_counterpartie_contract = $counter->name;
		$view_invoices = DB::SELECT('SELECT * FROM view_invoices');
		$name_invoices = DB::SELECT('SELECT * FROM name_invoices');
		$n_invoice = "Оплата";
        return view('department.invoice.invoice', ['invoice'=>[],
													'contract'=>$contract,
													'view_invoices'=>$view_invoices,
													'name_invoices'=>$name_invoices,
													'n_invoice'=>$n_invoice]);
    }
	public function create_return($id)
    {
		$contract = Contract::select(['id','id_counterpartie_contract','number_contract'])
							->where('id',$id)->get()[0];
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		foreach($counterparties as $counter)
			if($contract->id_counterpartie_contract == $counter->id)
				$contract->name_counterpartie_contract = $counter->name;
		$view_invoices = DB::SELECT('SELECT * FROM view_invoices');
		$name_invoices = DB::SELECT('SELECT * FROM name_invoices');
		$n_invoice = "Возврат";
        return view('department.invoice.invoice', ['invoice'=>[],
													'contract'=>$contract,
													'view_invoices'=>$view_invoices,
													'name_invoices'=>$name_invoices,
													'n_invoice'=>$n_invoice]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_invoice(Request $request)
    {
        $val = $request->validate([
			'id_contract' => 'required',
			'id_counterpartie' => 'required',
			'number_invoice' => 'required',
			'date_invoice' => 'required|date',
			'name_date_invoice' => 'nullable|date',
			'id_name_invoice' => 'required'
		]);
		$view = DB::SELECT('SELECT * FROM view_invoices WHERE name_view_invoice = "' . $request['name_view_invoice'] . '"');
		$inv = new Invoice;
		$inv->fill(['id_contract' => $request['id_contract'],
					'id_name_invoice' => $request['id_name_invoice'],
					'is_prepayment_invoice' => $request['is_prepayment_invoice'] ? 1 : 0,
					'number_deed_invoice' => $request['number_deed_invoice'],
					'number_invoice' => $request['number_invoice'],
					'date_invoice' => $request['date_invoice'] ? date('Y-m-d', strtotime($request['date_invoice'])) : null,
					'amount_p_invoice' => str_replace(',', '.', $request['amount_p_invoice']),
					'id_view_invoice' => $view ? $view[0]->id : null,
					'name_invoice' => $request['name_invoice'],
					'name_date_invoice' => $request['name_date_invoice'],
					'amount_invoice' => $request['amount_invoice'],
					'date_payment_invoice' => $request['date_payment_invoice'],
					'amount_payment_invoice' => $request['amount_payment_invoice']
		]);
		if($request['amount_p_invoice'])
			$inv->amount_p_invoice = str_replace(' ','',$inv->amount_p_invoice);
		$inv->save();
		JournalController::store(Auth::User()->id,'Добавил новый счет для контракста с id = ' . $inv->id_contract);
        return redirect()->back()->with(['pls_back'=>'Счет сохранен!','success'=>'Счет сохранен!']); //->route('department.contract_invoice.show', $request['id_contract']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_contract($id)
    {
		$curators = Curator::all();
		$contract = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','id_goz_contract','goz_works.name_works_goz','id_view_work_contract', 'view_works.name_view_work',
										'all_count_contract','concluded_count_contract','amount_concluded_contract','formalization_count_contract',
										'amount_formalization_contract','big_deal_contract','amoun_implementation_contract','comment_implementation_contract',
										'prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract','date_contact','year_contract',
										'reestr_contracts.number_counterpartie_contract_reestr','reestr_contracts.executor_reestr',
										'reestr_contracts.amount_reestr','reestr_contracts.amount_contract_reestr',
										'reestr_contracts.amount_begin_reestr', 'reestr_contracts.vat_begin_reestr', 'reestr_contracts.approximate_amount_begin_reestr', 'reestr_contracts.fixed_amount_begin_reestr',
										'reestr_contracts.fix_amount_contract_reestr', 'reestr_contracts.vat_reestr', 'reestr_contracts.approximate_amount_reestr', 'reestr_contracts.fixed_amount_reestr',
										'reestr_contracts.date_b_contract_reestr', 'reestr_contracts.date_e_contract_reestr', 'reestr_contracts.date_contract_reestr', 
										'reestr_contracts.date_maturity_date_reestr', 'reestr_contracts.date_e_maturity_reestr', 'reestr_contracts.date_maturity_reestr', 'name_view_contract',
										'item_contract','reestr_contracts.date_contract_on_first_reestr', 'executor_contract_reestr'])
							->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
							->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
							->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
							->join('goz_works', 'contracts.id_goz_contract', '=', 'goz_works.id')
							->where('contracts.id',$id)->get()[0];
		foreach($curators as $curator)
			if($curator->id == $contract->executor_contract_reestr)
				$contract->name_executor = $curator->FIO;
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		foreach($counterparties as $counter)
			if($contract->id_counterpartie_contract == $counter->id)
				$contract->name_counterpartie_contract = $counter->name;
		//Счета
		$scores = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
											->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
											->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
											->where('id_contract', $id)
											->where('name', 'Счет на оплату')
											->orderBy('invoices.date_invoice', 'asc')
											->get();
		foreach($scores as $score)
			if(is_numeric($score->amount_p_invoice))
				$score->amount_p_invoice = number_format($score->amount_p_invoice, 2, '.', ' ');
		//Аванс
		$prepayments = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
											->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
											->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
											->where('id_contract', $id)
											->where('name', 'Аванс')
											->orderBy('invoices.date_invoice', 'asc')
											->get();
		foreach($prepayments as $prepayment)
			if(is_numeric($prepayment->amount_p_invoice))
				$prepayment->amount_p_invoice = number_format($prepayment->amount_p_invoice, 2, '.', ' ');
		//Счета-фактуры
		$invoices = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
											->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
											->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
											->where('id_contract', $id)
											->where('name', 'Счет-фактура')
											->orderBy('invoices.date_invoice', 'asc')
											->get();
		foreach($invoices as $invoice)
			if(is_numeric($invoice->amount_p_invoice))
				$invoice->amount_p_invoice = number_format($invoice->amount_p_invoice, 2, '.', ' ');
		//Оплата
		$payments = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
											->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
											->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
											->where('id_contract', $id)
											->where('name', 'Оплата')
											->orderBy('invoices.date_invoice', 'asc')
											->get();
		foreach($payments as $payment)
			if(is_numeric($payment->amount_p_invoice))
				$payment->amount_p_invoice = number_format($payment->amount_p_invoice, 2, '.', ' ');
		//Возврат
		$returns = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
											->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
											->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
											->where('id_contract', $id)
											->where('name', 'Возврат')
											->orderBy('invoices.date_invoice', 'asc')
											->get();
		foreach($returns as $return)
			if(is_numeric($return->amount_p_invoice))
				$return->amount_p_invoice = number_format($return->amount_p_invoice, 2, '.', ' ');
		//Счета по договору
		$amount_scores = 0;
		$amount_prepayments = 0;
		$amount_invoices = 0;
		$amount_payments = 0;
		$amount_returns = 0;
		$invoices_all = Invoice::select(['invoices.amount_p_invoice', 'name_invoices.name'])
									->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
									->where('id_contract', $id)
									->get();
		foreach($invoices_all as $score)
			if($score->name == 'Счет на оплату')
				$amount_scores += $score->amount_p_invoice;
			else if($score->name == 'Аванс')
				$amount_prepayments += $score->amount_p_invoice;
			else if($score->name == 'Счет-фактура')
				$amount_invoices += $score->amount_p_invoice;
			else if($score->name == 'Оплата')
				$amount_payments += $score->amount_p_invoice;
			else if($score->name == 'Возврат')
				$amount_returns += $score->amount_p_invoice;
		//dd($amount_payments);
		$resolutions = Resolution::select(['*'])->where('id_contract_resolution', $contract->id)->where('deleted_at', null)->orderBy('resolutions.id', 'desc')->get();
		//dump($invoices);
		//История договора
		$states = State::select(['states.id','name_state','comment_state','date_state','users.surname','users.name','users.patronymic'])->join('users','users.id','states.id_user')->where('id_contract', $id)->where('is_work_state', null)->get();
		//Стадии выполнения
		$work_states = State::select(['states.id','name_state','comment_state','date_state','users.surname','users.name','users.patronymic'])->join('users','users.id','states.id_user')->where('id_contract', $id)->where('is_work_state', 1)->get();
        return view('department.invoice.contract', ['contract'=>$contract,
													'scores'=>$scores,
													'prepayments'=>$prepayments,
													'invoices'=>$invoices,
													'payments'=>$payments,
													'returns'=>$returns,
													'amount_scores'=>$amount_scores,
													'amount_prepayments'=>$amount_prepayments,
													'amount_invoices'=>$amount_invoices,
													'amount_payments'=>$amount_payments,
													'amount_returns'=>$amount_returns,
													'resolutions'=>$resolutions,
													'states'=>$states,
													'work_states'=>$work_states
													]);
    }
	
    public function show_invoice($id)
    {
		$invoice = Invoice::select(['*','invoices.id', 'contracts.id_counterpartie_contract','contracts.number_contract','view_invoices.name_view_invoice'])
											->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
											->join('contracts', 'invoices.id_contract', 'contracts.id')
											->join('name_invoices', 'name_invoices.id', 'invoices.id_name_invoice')
											->where('invoices.id', $id)->get()[0];
		if(is_numeric($invoice->amount_p_invoice))
			$invoice->amount_p_invoice = number_format($invoice->amount_p_invoice, 2, '.', ' ');
		//dd($invoice);
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		//dd($invoice);
		foreach($counterparties as $counter)
			if($invoice->id_counterpartie_contract == $counter->id)
				$invoice->name_counterpartie_contract = $counter->name;
		$view_invoices = DB::SELECT('SELECT * FROM view_invoices');
		$name_invoices = DB::SELECT('SELECT * FROM name_invoices');
        return view('department.invoice.invoice', ['invoice'=>$invoice,
													'view_invoices'=>$view_invoices,
													'name_invoices'=>$name_invoices]);
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
        $val = $request->validate([
			'id_contract' => 'required',
			'id_counterpartie' => 'required',
			'number_invoice' => 'required',
			'date_invoice' => 'required|date',
			'name_date_invoice' => 'nullable|date',
			'id_name_invoice' => 'required'
		]);
		$view = DB::SELECT('SELECT * FROM view_invoices WHERE name_view_invoice = "' . $request['name_view_invoice'] . '"');
		$inv = Invoice::findOrFail($id);
		$inv->fill(['number_invoice' => $request['number_invoice'],
					'is_prepayment_invoice' => $request['is_prepayment_invoice'] ? 1 : 0,
					'number_deed_invoice' => $request['number_deed_invoice'],
					'date_invoice' => $request['date_invoice'] ? date('Y-m-d', strtotime($request['date_invoice'])) : null,
					'amount_p_invoice' => str_replace(',', '.', $request['amount_p_invoice']),
					'id_name_invoice' => $request['id_name_invoice'],
					'id_view_invoice' => $view ? $view[0]->id : null,
					'name_invoice' => $request['name_invoice'],
					'name_date_invoice' => $request['name_date_invoice'],
					'amount_invoice' => $request['amount_invoice'],
					'date_payment_invoice' => $request['date_payment_invoice'],
					'amount_payment_invoice' => $request['amount_payment_invoice']
		]);
		if($request['amount_p_invoice'])
			$inv->amount_p_invoice = str_replace(' ','',$inv->amount_p_invoice);
		//dump($inv);
		$inv->save();
		JournalController::store(Auth::User()->id,'Обновил счет id = ' . $inv->id . ' для контракста с id = ' . $inv->id_contract);
        return redirect()->back()->with(['pls_back'=>'Счет обновлен!','success'=>'Счет обновлен!']);	//->route('department.contract_invoice.show', $request['id_contract']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
		$invoice->delete();
		JournalController::store(Auth::User()->id,'Удален счет id = ' . $invoice->id . ' для контракста с id = ' . $invoice->id_contract);
		return redirect()->back()->with('success', 'Счет Удален!');
    }
}
