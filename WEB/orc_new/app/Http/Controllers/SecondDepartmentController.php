<?php

namespace App\Http\Controllers;

use Auth;
use App\Contract;
use App\ViewWork;
use App\Element;
use App\ViewWorkElement;
use App\Counterpartie;
use App\SecondDepartment;
use App\SecondDepartmentComment;
use App\SecondDepartmentMonth;
use App\Curator;
use App\Resolution;
use App\SecondDepartmentTour;
use App\Invoice;
use App\ViewContract;
use App\SecondDepartmentUnit;
use App\Department;
use App\SecondDepartmentAct;
use App\State;
use App\SecondDepartmentSbTour;
use App\SecondDepartmentCaliber;
use App\SecondDepartmentNameElement;
use App\SecondDepartmentUsTour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SecondDepartmentController extends Controller
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
		$sort_year = "id";
		$sort_span = "";
		$re_sort = "desc";
		if (isset($_GET["sorting"])) {
			$sort  = $_GET["sorting"];
			$sort_span = "▼";
			$link .= '&sorting=' . $_GET["sorting"];
			$sort_year = 'contracts.year_contract';
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
		//Фильтр
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
			$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','id_goz_contract','id_view_contract', 'view_contracts.name_view_contract',
											'all_count_contract','concluded_count_contract','amount_concluded_contract','formalization_count_contract',
											'amount_formalization_contract','big_deal_contract','amoun_implementation_contract','comment_implementation_contract',
											'prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract','date_contact','year_contract',
											'item_contract', DB::raw('CAST(number_pp as UNSIGNED) as cast_number_pp')])
							->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
							->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
							->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
							->where('contracts.is_sip_contract',1)
							->where($search_name, 'like', $search_value . '%')
							->where('contracts.id_counterpartie_contract','>','-1')->where($year_str, $year_equal, $year)
							->where($view_contract_str, $view_contract_equal, $view_contract)->where($counterpartie_str, $counterpartie_equal, $counterpartie)
							->where('archive_contract', 0)
							->orderBy($sort_year, $sort_p)
							->orderBy($sort, $sort_p)
							->offset($start)
							->limit($paginate_count)
							->get();
			$contract_count = Contract::select()
							->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
							->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', '=', 'view_contracts.id')
							->where('contracts.is_sip_contract',1)
							->where($search_name, 'like', $search_value . '%')
							->where('contracts.id_counterpartie_contract','>',-1)
							->where($year_str, $year_equal, $year)
							->where($view_contract_str, $view_contract_equal, $view_contract)
							->where($counterpartie_str, $counterpartie_equal, $counterpartie)
							->where('archive_contract', 0)
							->count();
		}else{
			$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','id_goz_contract','id_view_contract', 'view_contracts.name_view_contract',
											'all_count_contract','concluded_count_contract','amount_concluded_contract','formalization_count_contract',
											'amount_formalization_contract','big_deal_contract','amoun_implementation_contract','comment_implementation_contract',
											'prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract','date_contact','year_contract',
											'item_contract', DB::raw('CAST(number_pp as UNSIGNED) as cast_number_pp')])
							->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
							->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
							->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
							->where('contracts.is_sip_contract',1)
							->where('contracts.id_counterpartie_contract','>','-1')->where($year_str, $year_equal, $year)
							->where($view_contract_str, $view_contract_equal, $view_contract)->where($counterpartie_str, $counterpartie_equal, $counterpartie)
							->where('archive_contract', 0)
							->orderBy($sort_year, $sort_p)
							->orderBy($sort, $sort_p)
							->offset($start)
							->limit($paginate_count)
							->get();
			$contract_count = Contract::select()->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', '=', 'view_contracts.id')->where('contracts.is_sip_contract',1)->where('contracts.id_counterpartie_contract','>',-1)->where($year_str, $year_equal, $year)
												->where($view_contract_str, $view_contract_equal, $view_contract)->where($counterpartie_str, $counterpartie_equal, $counterpartie)->where('archive_contract', 0)->count();
		}
		$prev_page = $page - 1 > 0 ? (int)($page-1) : '';
		$next_page = $page + 1 <= (int)ceil($contract_count/$paginate_count) ? (int)($page+1) : '';
		$years = DB::SELECT('SELECT year_contract FROM contracts GROUP BY year_contract ORDER BY year_contract DESC');
		$view_works = ViewWork::all();
		$view_contracts = ViewContract::select()->where('is_sip_view_contract', 1)->get();
		foreach($contracts as $contract)
			foreach($counterparties as $counter)
				if($contract->id_counterpartie_contract == $counter->id){
					$contract->name_counterpartie_contract = $counter->name;
					break;
				}
		$departments = Department::select()->orderBy('index_department', 'asc')->get();
		$all_view_contracts = ViewContract::select('*')->get();
		$view_work_elements = ViewWorkElement::select('*')->orderBy('name_view_work_elements', 'asc')->get();
        return view('department.second.main',['contracts' => $contracts,
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
													'viewWorkElements' => $view_work_elements,
													'search_name'=>$search_name,
													'search_value'=>$search_value,
													'sort'=>$sort, 
													'sort_span'=>$sort_span, 
													're_sort'=>$re_sort,
													'count_paginate' => (int)ceil($contract_count/$paginate_count),
													'prev_page' => $prev_page,
													'next_page' => $next_page,
													'page' => $page,
													'link' => $link
												]);
    }
	
	public function create_new_isp(Request $request, $id_contract)
	{
        $val = Validator::make($request->all(),[
			'id_element' => 'required',
			'year_isp' => 'required|numeric'
		])->validate();
		$isp = new SecondDepartment();
		$isp -> fill([
			'id_contract' => $id_contract,
			'id_element' => $request['id_element'],
			'id_view_work_elements' => $request['name_view_work'] ? $request['name_view_work'] : null,
			'count_isp' => $request['count_elements'],
			'year' => $request['year_isp']
		]);
		$isp->save();
		return redirect()->back()->with('success','Успешно добавлен!');
	}
	
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_isp(Request $request, $id)
    {
        $val = Validator::make($request->all(),[
			'id_element' => 'required',
			'id_view_work_elements' => 'required',
			'year' => 'required|numeric',
			'january' => 'nullable|numeric|min:0',
			'february' => 'nullable|numeric|min:0',
			'march' => 'nullable|numeric|min:0',
			'april' => 'nullable|numeric|min:0',
			'may' => 'nullable|numeric|min:0',
			'june' => 'nullable|numeric|min:0',
			'july' => 'nullable|numeric|min:0',
			'august' => 'nullable|numeric|min:0',
			'september' => 'nullable|numeric|min:0',
			'october' => 'nullable|numeric|min:0',
			'november' => 'nullable|numeric|min:0',
			'december' => 'nullable|numeric|min:0'
		])->validate();
		$isp = new SecondDepartment();
		//$isp -> fill($request->all());
		$isp -> fill([
			'id_contract' => $id,
			'id_element' => $request['id_element'],
			'id_view_work_elements' => $request['id_view_work_elements'],
			'january' => $request['january']!=0 ? $request['january'] : null,
			'january_check' => $request['january_check'] ? 1 : 0,
			'february' => $request['february']!=0 ? $request['february'] : null,
			'february_check' => $request['february_check'] ? 1 : 0,
			'march' => $request['march']!=0 ? $request['march'] : null,
			'march_check' => $request['march_check'] ? 1 : 0,
			'april' => $request['april']!=0 ? $request['april'] : null,
			'april_check' => $request['april_check'] ? 1 : 0,
			'may' => $request['may']!=0 ? $request['may'] : null,
			'may_check' => $request['may_check'] ? 1 : 0,
			'june' => $request['june']!=0 ? $request['june'] : null,
			'june_check' => $request['june_check'] ? 1 : 0,
			'july' => $request['july']!=0 ? $request['july'] : null,
			'july_check' => $request['july_check'] ? 1 : 0,
			'august' => $request['august']!=0 ? $request['august'] : null,
			'august_check' => $request['august_check'] ? 1 : 0,
			'september' => $request['september']!=0 ? $request['september'] : null,
			'september_check' => $request['september_check'] ? 1 : 0,
			'october' => $request['october']!=0 ? $request['october'] : null,
			'october_check' => $request['october_check'] ? 1 : 0,
			'november' => $request['november']!=0 ? $request['november'] : null,
			'november_check' => $request['november_check'] ? 1 : 0,
			'december' => $request['december']!=0 ? $request['december'] : null,
			'december_check' => $request['december_check'] ? 1 : 0,
			'year' => $request['year']
		]);
		//dump($isp);
		//dump($id);
        //dump($request->all());
		$isp->save();
		return redirect()->route('department.contract_second.show', $id)->with('success','Успешно добавлен!');
    }

	public function create_sb(Request $request, $id)
    {
        $val = Validator::make($request->all(),[
			'id_element' => 'required',
			'year' => 'required|numeric',
			'january' => 'nullable|numeric|min:0',
			'february' => 'nullable|numeric|min:0',
			'march' => 'nullable|numeric|min:0',
			'april' => 'nullable|numeric|min:0',
			'may' => 'nullable|numeric|min:0',
			'june' => 'nullable|numeric|min:0',
			'july' => 'nullable|numeric|min:0',
			'august' => 'nullable|numeric|min:0',
			'september' => 'nullable|numeric|min:0',
			'october' => 'nullable|numeric|min:0',
			'november' => 'nullable|numeric|min:0',
			'december' => 'nullable|numeric|min:0'
		])->validate();
		$isp = new SecondDepartment();
		$isp -> fill([
			'id_contract' => $id,
			'id_element' => $request['id_element'],
			'id_view_work_elements' => null,
			'january' => $request['january']!=0 ? $request['january'] : null,
			'january_check' => $request['january_check'] ? 1 : 0,
			'february' => $request['february']!=0 ? $request['february'] : null,
			'february_check' => $request['february_check'] ? 1 : 0,
			'march' => $request['march']!=0 ? $request['march'] : null,
			'march_check' => $request['march_check'] ? 1 : 0,
			'april' => $request['april']!=0 ? $request['april'] : null,
			'april_check' => $request['april_check'] ? 1 : 0,
			'may' => $request['may']!=0 ? $request['may'] : null,
			'may_check' => $request['may_check'] ? 1 : 0,
			'june' => $request['june']!=0 ? $request['june'] : null,
			'june_check' => $request['june_check'] ? 1 : 0,
			'july' => $request['july']!=0 ? $request['july'] : null,
			'july_check' => $request['july_check'] ? 1 : 0,
			'august' => $request['august']!=0 ? $request['august'] : null,
			'august_check' => $request['august_check'] ? 1 : 0,
			'september' => $request['september']!=0 ? $request['september'] : null,
			'september_check' => $request['september_check'] ? 1 : 0,
			'october' => $request['october']!=0 ? $request['october'] : null,
			'october_check' => $request['october_check'] ? 1 : 0,
			'november' => $request['november']!=0 ? $request['november'] : null,
			'november_check' => $request['november_check'] ? 1 : 0,
			'december' => $request['december']!=0 ? $request['december'] : null,
			'december_check' => $request['december_check'] ? 1 : 0,
			'year' => $request['year']
		]);
		$isp->save();
		return redirect()->route('department.contract_second.show', $id)->with('success','Успешно добавлен!');
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
    public function show_contract($id)
    {
		$curators = Curator::all();
		//dd($curators);
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
										'reestr_contracts.igk_reestr',
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
		//Испытания
		$paginate_count = 10;
		if (isset($_GET["page"])) {
			$page  = $_GET["page"];
		} else {
			$page=1;
		};
		$start = ($page-1) * $paginate_count;
		$secondDepartments = SecondDepartment::select(['*','second_department.id'])->join('elements', 'id_element', 'elements.id')->LeftJoin('view_work_elements', 'id_view_work_elements', 'view_work_elements.id')
											->where('id_contract', $id)->offset($start)
											->limit($paginate_count)
											->orderBy('second_department.id', 'desc')
											->get();
		//dump($secondDepartments);
		$k = 0;
		foreach($secondDepartments as $second)
		{
			$comments = SecondDepartmentComment::select(['second_department_comments.id','name_month','message_comment','check_comment'])
											->join('second_department_months','id_month_comment','second_department_months.id')
											->where('id_second_dep_comment', $second->id)->get();
			//dump($comments);
			if(count($comments) > 0)
				foreach($comments as $comment){
					if($second['month'])
						$second['month'] += [$k => ['month'=>$comment->name_month, 'message'=>$comment->message_comment]];
					else
						$second['month'] = [$k => ['month'=>$comment->name_month, 'message'=>$comment->message_comment]];
					$k++;
				}
		}
		//dd($secondDepartments);
		$isp_count = SecondDepartment::select(['*'])->where('id_contract', $id)->count();
		$prev_page = $page - 1 > 0 ? (int)($page-1) : '';
		$next_page = $page + 1 <= (int)ceil($isp_count/$paginate_count) ? (int)($page+1) : '';
		$elements = Element::select(['*'])->orderBy('elements.name_element')->get();
		$view_work_elements = ViewWorkElement::select()->orderBy('name_view_work_elements')->get();
		//dump($secondDepartments);
		$resolutions = Resolution::select(['*'])->where('id_contract_resolution', $contract->id)->where('deleted_at', null)->orderBy('resolutions.id', 'desc')->get();
		$secondDepartmentTours = SecondDepartmentTour::select(['*', 'second_department_tours.id', DB::RAW('CAST(second_department_tours.number_duty AS UNSIGNED) AS cast_number_duty')])
																->leftJoin('elements', 'elements.id', 'second_department_tours.id_element')
																->leftJoin('view_work_elements', 'view_work_elements.id', 'second_department_tours.id_view_work_elements')
																->leftJoin('results', 'results.id', 'second_department_tours.id_result')
																->leftJoin('second_department_units', 'second_department_units.id', 'second_department_tours.id_unit')
																->where('second_department_tours.id_contract', $contract->id)
																->orderBy('cast_number_duty', 'asc')
																->orderBy('second_department_tours.number_duty', 'asc')
																->get();
		$secondDepartmentSbTours = SecondDepartmentSbTour::select(['*', 'second_department_sb_tours.id'])
																->leftJoin('elements', 'elements.id', 'second_department_sb_tours.id_element')
																->leftJoin('view_work_elements', 'view_work_elements.id', 'second_department_sb_tours.id_view_work_elements')
																->leftJoin('second_department_units', 'second_department_units.id', 'second_department_sb_tours.id_unit')
																->leftJoin('second_department_calibers', 'second_department_calibers.id', 'second_department_sb_tours.id_caliber')
																->where('second_department_sb_tours.id_contract', $contract->id)
																->orderBy(DB::raw('(second_department_sb_tours.number_duty+0)'), 'asc')
																->get();
		$secondDepartmentUsTours = SecondDepartmentUsTour::select(['*'])
																->where('second_department_us_tours.id_contract', $contract->id)
																->orderBy(DB::raw("(second_department_us_tours.number_duty+0)"), 'asc')
																->get();
        //dd($secondDepartmentSbTours);
		//Счета по предприятию
		$amount_scores_all = 0;
		$amount_prepayments_all = 0;
		$amount_invoices_all = 0;
		$amount_payments_all = 0;
		$amount_returns_all = 0;
		$invoices = Invoice::select(['invoices.amount_p_invoice', 'name_invoices.name'])
									->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
									->join('contracts', 'contracts.id', 'invoices.id_contract')
									->where('contracts.id_counterpartie_contract', $contract->id_counterpartie_contract)
									->get();
		foreach($invoices as $score)
			if($score->name == 'Счет на оплату')
				$amount_scores_all += $score->amount_p_invoice;
			else if($score->name == 'Аванс')
				$amount_prepayments_all += $score->amount_p_invoice;
			else if($score->name == 'Счет-фактура')
				$amount_invoices_all += $score->amount_p_invoice;
			else if($score->name == 'Оплата')
				$amount_payments_all += $score->amount_p_invoice;
			else if($score->name == 'Возврат')
				$amount_returns_all += $score->amount_p_invoice;
		//Счета по договору
		$amount_scores = 0;
		$amount_prepayments = 0;
		$amount_invoices = 0;
		$amount_payments = 0;
		$amount_returns = 0;
		$invoices = Invoice::select(['invoices.amount_p_invoice', 'name_invoices.name', 'is_prepayment_invoice'])
									->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
									->where('id_contract', $id)
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
		//Все счета
		$scores = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
											->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
											->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
											->where('id_contract', $id)
											->where('name', 'Счет на оплату')
											->orderBy('invoices.number_invoice', 'asc')
											->get();
		$prepayments = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
											->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
											->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
											->where('id_contract', $id)
											->where('name', 'Аванс')
											->orderBy('invoices.number_invoice', 'asc')
											->get();
		$invoices = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
											->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
											->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
											->where('id_contract', $id)
											->where('name', 'Счет-фактура')
											->orderBy('invoices.number_invoice', 'asc')
											->get();
		$payments = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
											->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
											->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
											->where('id_contract', $id)
											->where('name', 'Оплата')
											->orderBy('invoices.number_invoice', 'asc')
											->get();
		$returns = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
											->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
											->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
											->where('id_contract', $id)
											->where('name', 'Возврат')
											->orderBy('invoices.number_invoice', 'asc')
											->get();
		//Акты (последнии акты для отображения в таблице)
		foreach($secondDepartmentTours as $tour){
			$acts = SecondDepartmentAct::select()->where('id_second_tour', $tour->id)->get();
			$tour->acts = $acts;
			$tour->act = null;
			if(count($acts) > 0){
				$pr_amount_acts = 0;
				foreach($acts as $act){
					$pr_amount_acts += $act->amount_act;
					$act->edit_href = route('department.second.update_act', $act->id);
				}
				$tour->act = $acts[count($acts) - 1];
				$tour->amount_acts = is_numeric($pr_amount_acts) ? number_format($pr_amount_acts, 2, ',', ' ') : $pr_amount_acts;
			}
		}
		foreach($secondDepartmentSbTours as $tour){
			$acts_sb = SecondDepartmentAct::select()->where('id_second_sb_tour', $tour->id)->get();
			$tour->acts_sb = $acts_sb;
			$tour->act_sb = null;
			if(count($acts_sb) > 0){
				$pr_amount_acts = 0;
				foreach($acts_sb as $act_sb){
					$pr_amount_acts += $act_sb->amount_act;
					$act_sb->edit_href = route('department.second.update_act', $act_sb->id);
				}
				$tour->act_sb = $acts_sb[count($acts_sb) - 1];
				$tour->amount_acts_sb = is_numeric($pr_amount_acts) ? number_format($pr_amount_acts, 2, ',', ' ') : $pr_amount_acts;
			}
		}
		foreach($secondDepartmentUsTours as $tour){
			$acts_us = SecondDepartmentAct::select()->where('id_second_us_tour', $tour->id)->get();
			$tour->acts_us = $acts_us;
			$tour->act_us = null;
			if(count($acts_us) > 0){
				$pr_amount_acts = 0;
				foreach($acts_us as $act_us){
					$pr_amount_acts += $act_us->amount_act;
					$act_us->edit_href = route('department.second.update_act', $act_us->id);
				}
				$tour->act_us = $acts_us[count($acts_us) - 1];
				$tour->amount_acts_us = is_numeric($pr_amount_acts) ? number_format($pr_amount_acts, 2, ',', ' ') : $pr_amount_acts;
			}
		}
		//Акты для Услуг ГН и Услуг ВН
		$contract->acts = SecondDepartmentAct::select()->where('id_contract', $contract->id)->get();
		//История договора
		$states = State::select(['states.id','name_state','comment_state','date_state','users.surname','users.name','users.patronymic'])->join('users','users.id','states.id_user')->where('id_contract', $id)->where('is_work_state', null)->get();
		//Стадии выполнения
		$work_states = State::select(['states.id','name_state','comment_state','date_state','users.surname','users.name','users.patronymic'])->join('users','users.id','states.id_user')->where('id_contract', $id)->where('is_work_state', 1)->get();
		return view('department.second.contract', ['contract'=>$contract,
													'secondDepartments'=>$secondDepartments,
													'secondDepartmentTours'=>$secondDepartmentTours,
													'secondDepartmentSbTours'=>$secondDepartmentSbTours,
													'secondDepartmentUsTours'=>$secondDepartmentUsTours,
													'elements' => $elements,
													'view_work_elements' => $view_work_elements,
													'resolutions' => $resolutions,
													'amount_scores_all' => $amount_scores_all,
													'amount_prepayments_all' => $amount_prepayments_all,
													'amount_invoices_all' => $amount_invoices_all,
													'amount_payments_all' => $amount_payments_all,
													'amount_returns_all' => $amount_returns_all,
													'amount_scores'=>$amount_scores,
													'amount_prepayments'=>$amount_prepayments,
													'amount_invoices'=>$amount_invoices,
													'amount_payments'=>$amount_payments,
													'amount_returns'=>$amount_returns,
													'scores'=>$scores,
													'prepayments'=>$prepayments,
													'invoices'=>$invoices,
													'payments'=>$payments,
													'returns'=>$returns,
													'states'=>$states,
													'work_states'=>$work_states,
													'count_paginate' => (int)ceil($isp_count/$paginate_count),
													'prev_page' => $prev_page,
													'next_page' => $next_page,
													'page' => $page
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

	public function update_new_isp(Request $request, $id_isp)
	{
        $val = Validator::make($request->all(),[
			'id_element' => 'required',
			'year_isp' => 'required|numeric'
		])->validate();
		$isp = SecondDepartment::findOrFail($id_isp);
		$isp -> fill([
			'id_element' => $request['id_element'],
			'id_view_work_elements' => $request['name_view_work'] ? $request['name_view_work'] : null,
			'count_isp' => $request['count_elements'],
			'year' => $request['year_isp']
		]);
		$isp->save();
		return redirect()->back()->with('success','Успешно изменен!');
	}
	
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_isp(Request $request, $id)
    {
        $val = Validator::make($request->all(),[
			'id_element_update' => 'required',
			'id_view_work_elements_update' => 'required',
			'year_update' => 'required|numeric',
			'january_update' => 'nullable|numeric|min:0',
			'february_update' => 'nullable|numeric|min:0',
			'march_update' => 'nullable|numeric|min:0',
			'april_update' => 'nullable|numeric|min:0',
			'may_update' => 'nullable|numeric|min:0',
			'june_update' => 'nullable|numeric|min:0',
			'july_update' => 'nullable|numeric|min:0',
			'august_update' => 'nullable|numeric|min:0',
			'september_update' => 'nullable|numeric|min:0',
			'october_update' => 'nullable|numeric|min:0',
			'november_update' => 'nullable|numeric|min:0',
			'december_update' => 'nullable|numeric|min:0'
		])->validate();
        $isp = SecondDepartment::findOrFail($id);
		$isp -> fill([
			'id_element' => $request['id_element_update'],
			'id_view_work_elements' => $request['id_view_work_elements_update'],
			'january' => $request['january_update']!=0 ? $request['january_update'] : null,
			'january_check' => $request['january_check_update'] ? 1 : 0,
			'february' => $request['february_update']!=0 ? $request['february_update'] : null,
			'february_check' => $request['february_check_update'] ? 1 : 0,
			'march' => $request['march_update']!=0 ? $request['march_update'] : null,
			'march_check' => $request['march_check_update'] ? 1 : 0,
			'april' => $request['april_update']!=0 ? $request['april_update'] : null,
			'april_check' => $request['april_check_update'] ? 1 : 0,
			'may' => $request['may_update']!=0 ? $request['may_update'] : null,
			'may_check' => $request['may_check_update'] ? 1 : 0,
			'june' => $request['june_update']!=0 ? $request['june_update'] : null,
			'june_check' => $request['june_check_update'] ? 1 : 0,
			'july' => $request['july_update']!=0 ? $request['july_update'] : null,
			'july_check' => $request['july_check_update'] ? 1 : 0,
			'august' => $request['august_update']!=0 ? $request['august_update'] : null,
			'august_check' => $request['august_check_update'] ? 1 : 0,
			'september' => $request['september_update']!=0 ? $request['september_update'] : null,
			'september_check' => $request['september_check_update'] ? 1 : 0,
			'october' => $request['october_update']!=0 ? $request['october_update'] : null,
			'october_check' => $request['october_check_update'] ? 1 : 0,
			'november' => $request['november_update']!=0 ? $request['november_update'] : null,
			'november_check' => $request['november_check_update'] ? 1 : 0,
			'december' => $request['december_update']!=0 ? $request['december_update'] : null,
			'december_check' => $request['december_check_update'] ? 1 : 0,
			'year' => $request['year_update']
		]);
		$isp->save();
		return redirect()->route('department.contract_second.show', $isp->id_contract)->with('success','Успешно изменен!');
    }
	
    public function update_sb(Request $request, $id)
    {
        $val = Validator::make($request->all(),[
			'id_element_update' => 'required',
			'year_update' => 'required|numeric',
			'january_update' => 'nullable|numeric|min:0',
			'february_update' => 'nullable|numeric|min:0',
			'march_update' => 'nullable|numeric|min:0',
			'april_update' => 'nullable|numeric|min:0',
			'may_update' => 'nullable|numeric|min:0',
			'june_update' => 'nullable|numeric|min:0',
			'july_update' => 'nullable|numeric|min:0',
			'august_update' => 'nullable|numeric|min:0',
			'september_update' => 'nullable|numeric|min:0',
			'october_update' => 'nullable|numeric|min:0',
			'november_update' => 'nullable|numeric|min:0',
			'december_update' => 'nullable|numeric|min:0'
		])->validate();
        $isp = SecondDepartment::findOrFail($id);
		$isp -> fill([
			'id_element' => $request['id_element_update'],
			'id_view_work_elements' => null,
			'january' => $request['january_update']!=0 ? $request['january_update'] : null,
			'january_check' => $request['january_check_update'] ? 1 : 0,
			'february' => $request['february_update']!=0 ? $request['february_update'] : null,
			'february_check' => $request['february_check_update'] ? 1 : 0,
			'march' => $request['march_update']!=0 ? $request['march_update'] : null,
			'march_check' => $request['march_check_update'] ? 1 : 0,
			'april' => $request['april_update']!=0 ? $request['april_update'] : null,
			'april_check' => $request['april_check_update'] ? 1 : 0,
			'may' => $request['may_update']!=0 ? $request['may_update'] : null,
			'may_check' => $request['may_check_update'] ? 1 : 0,
			'june' => $request['june_update']!=0 ? $request['june_update'] : null,
			'june_check' => $request['june_check_update'] ? 1 : 0,
			'july' => $request['july_update']!=0 ? $request['july_update'] : null,
			'july_check' => $request['july_check_update'] ? 1 : 0,
			'august' => $request['august_update']!=0 ? $request['august_update'] : null,
			'august_check' => $request['august_check_update'] ? 1 : 0,
			'september' => $request['september_update']!=0 ? $request['september_update'] : null,
			'september_check' => $request['september_check_update'] ? 1 : 0,
			'october' => $request['october_update']!=0 ? $request['october_update'] : null,
			'october_check' => $request['october_check_update'] ? 1 : 0,
			'november' => $request['november_update']!=0 ? $request['november_update'] : null,
			'november_check' => $request['november_check_update'] ? 1 : 0,
			'december' => $request['december_update']!=0 ? $request['december_update'] : null,
			'december_check' => $request['december_check_update'] ? 1 : 0,
			'year' => $request['year_update']
		]);
		$isp->save();
		return redirect()->route('department.contract_second.show', $isp->id_contract)->with('success','Успешно изменен!');
    }
	
	public function new_comment(Request $request, $id)
	{
		//dump($request->all());
		$val = Validator::make($request->all(),[
			'month_new_comment' => 'required',
			'new_comment' => 'required'
		])->validate();
		$id_month_comment = SecondDepartmentMonth::firstOrCreate(['name_month'=>$request['month_new_comment']]);
		$comment = new SecondDepartmentComment();
		$comment->fill([
			'id_second_dep_comment' => $id,
			'id_month_comment' => $id_month_comment->id,
			'message_comment' => $request['new_comment']
		]);
		$comment->save();
		//dump($comment);
		return redirect()->back();
	}
	
	public function new_tour_of_duty($id_contract)
	{
		$second_department_tours = [];
		$elements = Element::select(['*'])->orderBy('elements.name_element')->get();
		$view_work_elements = ViewWorkElement::select()->orderBy('name_view_work_elements')->get();
		$second_department_units = SecondDepartmentUnit::select()->orderBy('name_unit')->get();
		$calibers = SecondDepartmentCaliber::select()->orderBy('name_caliber')->get();
		$name_elements = SecondDepartmentNameElement::select()->orderBy('name_element')->get();
		return view('department.second.tour_of_duty', ['contractID'=>$id_contract,
														'second_department_tours'=>$second_department_tours, 
														'elements'=>$elements, 
														'view_work_elements'=>$view_work_elements,
														'second_department_units'=>$second_department_units,
														'calibers'=>$calibers,
														'name_elements'=>$name_elements]);
	}
	
	public function new_tour_of_duty_exp($id_contract)
	{
		$second_department_tours = [];
		$second_department_units = SecondDepartmentUnit::select()->orderBy('name_unit')->get();
		$elements = Element::select(['*'])->orderBy('elements.name_element')->get();
		return view('department.second.tour_of_duty_exp', ['contractID'=>$id_contract,
														'second_department_tours'=>$second_department_tours, 
														'second_department_units'=>$second_department_units,
														'elements'=>$elements]);
	}
	
	public function new_tour_of_duty_sb($id_contract)
	{
		$elements = Element::select(['*'])->orderBy('elements.name_element')->get();
		$view_work_elements = ViewWorkElement::select()->orderBy('name_view_work_elements')->get();
		$second_department_units = SecondDepartmentUnit::select()->orderBy('name_unit')->get();
		$calibers = SecondDepartmentCaliber::select()->orderBy('name_caliber')->get();
		return view('department.second.tour_of_duty_sb', ['contractID'=>$id_contract,
														'second_department_sb_tours'=>[], 
														'elements'=>$elements, 
														'view_work_elements'=>$view_work_elements,
														'second_department_units'=>$second_department_units,
														'calibers'=>$calibers]);
	}
	
	public function new_tour_of_duty_us($id_contract)
	{
		return view('department.second.tour_of_duty_us', ['contractID'=>$id_contract,
														'second_department_us_tours'=>[]]);
	}
	
	public function store_tour_of_duty(Request $request, $id_contract)
	{
		//dump($request->all());
		$val = Validator::make($request->all(),[
			'full_name_element' => 'required',
			'id_view_work_elements' => 'required',
			'number_duty' => 'required',
			'date_duty' => 'required|date',
			'count_elements' => 'required|numeric|min:0',
			'date_incoming'	=> 'nullable|date'
		])->validate();
		$second_department_tour = new SecondDepartmentTour();
		$element = Element::select()->where('name_element', $request['full_name_element'])->first();
		if($element == null)
			return redirect()->back()->withInput()->with('message', 'Не найдено такое изделие!');
		$second_department_tour->fill([
			'id_contract' => $id_contract,
			'id_element' => $element->id,
			'id_view_work_elements' => $request['id_view_work_elements'],
			'id_caliber' => $request['id_caliber'],
			'id_name_element' => $request['id_name_element'],
			'number_duty' => $request['number_duty'],
			'date_duty' => $request['date_duty'],
			'count_elements' => $request['count_elements'],
			'id_unit' => $request['id_unit'],
			'date_incoming' => $request['date_incoming'],
			'number_report' => $request['number_duty'],
			'add_information' => $request['add_information']
		]);
		$all_dirty = JournalController::getMyChanges($second_department_tour);
		$second_department_tour->save();
		JournalController::store(Auth::User()->id,'Добавлено новое испытание из второго отдела для контракта с id = ' . $id_contract . '~' . json_encode($all_dirty));
		return redirect()->back()->with(['pls_back'=>'Испытание добавлено!','success'=>'Испытание добавлено!']);
	}
	
	public function store_tour_of_duty_exp(Request $request, $id_contract)
	{
		//dump($request->all());
		$val = Validator::make($request->all(),[
			'number_duty' => 'required',
			'theme_exp' => 'required',
			'date_duty' => 'required|date'
		])->validate();
		$second_department_tour = new SecondDepartmentTour();
		$second_department_tour->fill([
			'id_contract' => $id_contract,
			'theme_exp' => $request['theme_exp'],
			'number_duty' => $request['number_duty'],
			'date_duty' => $request['date_duty'],
			'count_elements' => $request['count_elements'],
			'id_unit' => $request['id_unit'],
			'date_incoming' => $request['date_incoming'],
			'result_document_exp' => $request['result_document_exp']
		]);
		$all_dirty = JournalController::getMyChanges($second_department_tour);
		$second_department_tour->save();
		JournalController::store(Auth::User()->id,'Добавлено нового опытного испытания из второго отдела для контракта с id = ' . $id_contract . '~' . json_encode($all_dirty));
		return redirect()->back()->with(['pls_back'=>'Испытание добавлено!','success'=>'Опытное испытание добавлено!']);
	}
	
	public function store_tour_of_duty_sb(Request $request, $id_contract)
	{
		//dd($request->all());
		$val = Validator::make($request->all(),[
			'full_name_element' => 'required',
			'id_view_work_elements' => 'required',
			'number_duty' => 'required',
			'date_duty' => 'required|date',
			'count_elements' => 'required|numeric|min:0',
			'id_caliber' => 'required',
			'id_unit' => 'required'
		])->validate();
		$second_department_tour_sb = new SecondDepartmentSbTour();
		$element = Element::select()->where('name_element', $request['full_name_element'])->first();
		if($element == null)
			return redirect()->back()->withInput()->with('message', 'Не найдено такое изделие!');
		$second_department_tour_sb->fill([
			'id_contract' => $id_contract,
			'id_element' => $element->id,
			'id_view_work_elements' => $request['id_view_work_elements'],
			'number_duty' => $request['number_duty'],
			'date_duty' => $request['date_duty'],
			'count_elements' => $request['count_elements'],
			'addition_count_elements' => $request['addition_count_elements'],
			'id_unit' => $request['id_unit'],
			'id_caliber' => $request['id_caliber'],
			'number_party' => $request['number_party']
		]);
		$all_dirty = JournalController::getMyChanges($second_department_tour_sb);
		$second_department_tour_sb->save();
		JournalController::store(Auth::User()->id,'Добавлена новая сборка из второго отдела для контракта с id = ' . $id_contract . '~' . json_encode($all_dirty));
		return redirect()->back()->with(['pls_back'=>'Сборка добавлена!','success'=>'Сборка добавлена!']);
	}
	
	public function store_tour_of_duty_us(Request $request, $id_contract)
	{
		//dd($request->all());
		$val = Validator::make($request->all(),[
			'number_duty' => 'required',
			'date_duty' => 'required|date',
			'date_worked'	=> 'nullable|date',
			'date_help_report'	=> 'nullable|date'
		])->validate();
		$second_department_tour_us = new SecondDepartmentUsTour();
		$second_department_tour_us->fill([
			'id_contract' => $id_contract,
			'number_duty' => $request['number_duty'],
			'date_duty' => $request['date_duty'],
			'date_worked' => $request['date_worked'] ? date('Y-m-d', strtotime($request['date_worked'])) : null,
			'number_help_report' => $request['number_help_report'],
			'date_help_report' => $request['date_help_report']
		]);
		$all_dirty = JournalController::getMyChanges($second_department_tour_us);
		$second_department_tour_us->save();
		JournalController::store(Auth::User()->id,'Добавлена новая услуга из второго отдела для контракта с id = ' . $id_contract . '~' . json_encode($all_dirty));
		return redirect()->back()->with(['pls_back'=>'Услуга добавлена!','success'=>'Услуга добавлена!']);
	}
	
	public function edit_tour_of_duty($id_second_dep_duty)
	{
		$second_department_tours = SecondDepartmentTour::findOrFail($id_second_dep_duty);
		$elements = Element::select(['*'])->orderBy('elements.name_element')->get();
		$view_work_elements = ViewWorkElement::select()->orderBy('name_view_work_elements')->get();
		$results = DB::SELECT('SELECT * FROM results');
		$second_department_units = SecondDepartmentUnit::select()->orderBy('name_unit')->get();
		//dd($second_department_tours);
		$calibers = SecondDepartmentCaliber::select()->orderBy('name_caliber')->get();
		$name_elements = SecondDepartmentNameElement::select()->orderBy('name_element')->get();
		return view('department.second.tour_of_duty', ['second_department_tours'=>$second_department_tours, 
														'elements'=>$elements, 
														'view_work_elements'=>$view_work_elements,
														'results'=>$results,
														'second_department_units'=>$second_department_units,
														'calibers'=>$calibers,
														'name_elements'=>$name_elements]);
	}
	
	public function edit_tour_of_duty_exp($id_second_dep_duty)
	{
		$second_department_tours = SecondDepartmentTour::findOrFail($id_second_dep_duty);
		$second_department_units = SecondDepartmentUnit::select()->orderBy('name_unit')->get();
		return view('department.second.tour_of_duty_exp', ['second_department_tours'=>$second_department_tours, 
														'second_department_units'=>$second_department_units]);
	}
	
	public function edit_tour_of_duty_sb($id_second_dep_duty)
	{
		$second_department_sb_tours = SecondDepartmentSbTour::findOrFail($id_second_dep_duty);
		$elements = Element::select(['*'])->orderBy('elements.name_element')->get();
		$view_work_elements = ViewWorkElement::select()->orderBy('name_view_work_elements')->get();
		$second_department_units = SecondDepartmentUnit::select()->orderBy('name_unit')->get();
		$calibers = SecondDepartmentCaliber::select()->orderBy('name_caliber')->get();
		return view('department.second.tour_of_duty_sb', ['second_department_sb_tours'=>$second_department_sb_tours, 
														'elements'=>$elements, 
														'view_work_elements'=>$view_work_elements,
														'second_department_units'=>$second_department_units,
														'calibers'=>$calibers]);
	}
	
	public function edit_tour_of_duty_us($id_second_dep_duty)
	{
		$second_department_us_tours = SecondDepartmentUsTour::findOrFail($id_second_dep_duty);
		return view('department.second.tour_of_duty_us', ['second_department_us_tours'=>$second_department_us_tours]);
	}
	
	public function update_tour_of_duty(Request $request, $id_second_dep_duty)
	{
		$val = Validator::make($request->all(),[
			'id_element' => 'required',
			'id_view_work_elements' => 'required',
			'number_duty' => 'required',
			'date_duty' => 'required|date',
			'count_elements' => 'required|numeric|min:0',
			'date_incoming'	=> 'nullable|date',
			'countable'	=> 'nullable|numeric|min:0',
			'targeting'	=> 'nullable|numeric|min:0',
			'warm'	=> 'nullable|numeric|min:0',
			'uncountable'	=> 'nullable|numeric|min:0',
			'renouncement'	=> 'nullable|numeric|min:0',
			'date_worked'	=> 'nullable|date',
			'date_telegram'	=> 'nullable|date',
			'date_report'	=> 'nullable|date',
			'date_act'	=> 'nullable|date'
		])->validate();
		$second_department_tour = SecondDepartmentTour::findOrFail($id_second_dep_duty);
		$second_department_tour->fill([
			'id_element' => $request['id_element'],
			'id_view_work_elements' => $request['id_view_work_elements'],
			'id_caliber' => $request['id_caliber'],
			'id_name_element' => $request['id_name_element'],
			'number_duty' => $request['number_duty'],
			'date_duty' => $request['date_duty'],
			'count_elements' => $request['count_elements'],
			'id_unit' => $request['id_unit'],
			'date_incoming' => $request['date_incoming'],
			'countable' => $request['countable'],
			'targeting' => $request['targeting'],
			'warm' => $request['warm'],
			'uncountable' => $request['uncountable'],
			'renouncement' => $request['renouncement'],
			'date_worked' => $request['date_worked'] ? date('Y-m-d', strtotime($request['date_worked'])) : null,
			'id_result' => $request['id_result'],
			'add_information' => $request['add_information'],
			'number_telegram' => $request['number_telegram'],
			'date_telegram' => $request['date_telegram'],
			'number_report' => $request['number_report'],
			'date_report' => $request['date_report'],
			'number_act' => $request['number_act'],
			'date_act' => $request['date_act'],
		]);
		$all_dirty = JournalController::getMyChanges($second_department_tour);
		$second_department_tour->save();
		JournalController::store(Auth::User()->id,'Обновлено испытание из второго отдела с id = ' .$id_second_dep_duty . ' для контракта с id = ' . $second_department_tour->id_contract . '~' . json_encode($all_dirty));
		return redirect()->back()->with(['pls_back'=>'Испытание обновлено!','success'=>'Испытание обновлено!']);
	}
	
	public function update_tour_of_duty_exp(Request $request, $id_second_dep_duty)
	{
		$val = Validator::make($request->all(),[
			'theme_exp' => 'required',
			'number_duty' => 'required',
			'date_duty' => 'required|date',
			'count_elements' => 'required|numeric|min:0',
			'date_incoming'	=> 'nullable|date',
			'countable'	=> 'nullable|numeric|min:0',
			'targeting'	=> 'nullable|numeric|min:0',
			'warm'	=> 'nullable|numeric|min:0',
			'uncountable'	=> 'nullable|numeric|min:0',
			'renouncement'	=> 'nullable|numeric|min:0',
			'date_worked'	=> 'nullable|date',
			'date_telegram'	=> 'nullable|date',
			'date_report'	=> 'nullable|date',
			'date_act'	=> 'nullable|date'
		])->validate();
		$second_department_tour = SecondDepartmentTour::findOrFail($id_second_dep_duty);
		$second_department_tour->fill([
			'theme_exp' => $request['theme_exp'],
			'number_duty' => $request['number_duty'],
			'date_duty' => $request['date_duty'],
			'count_elements' => $request['count_elements'],
			'id_unit' => $request['id_unit'],
			'date_incoming' => $request['date_incoming'],
			'countable' => $request['countable'],
			'targeting' => $request['targeting'],
			'warm' => $request['warm'],
			'uncountable' => $request['uncountable'],
			'renouncement' => $request['renouncement'],
			'date_worked' => $request['date_worked'] ? date('Y-m-d', strtotime($request['date_worked'])) : null,
			'result_document_exp' => $request['result_document_exp']
		]);
		$all_dirty = JournalController::getMyChanges($second_department_tour);
		$second_department_tour->save();
		JournalController::store(Auth::User()->id,'Обновлено опытное испытание из второго отдела с id = ' .$id_second_dep_duty . ' для контракта с id = ' . $second_department_tour->id_contract . '~' . json_encode($all_dirty));
		return redirect()->back()->with(['pls_back'=>'Испытание обновлено!','success'=>'Испытание обновлено!']);
	}
	
	public function update_tour_of_duty_sb(Request $request, $id_second_dep_duty)
	{
		$val = Validator::make($request->all(),[
			'id_element' => 'required',
			'id_view_work_elements' => 'required',
			'number_duty' => 'required',
			'date_duty' => 'required|date',
			'count_elements' => 'required|numeric|min:0',
			'id_unit' => 'required',
			'id_caliber' => 'required',
			'date_worked'	=> 'nullable|date',
			'date_logbook'	=> 'nullable|date',
			'date_notification'	=> 'nullable|date'		
		])->validate();
		$second_department_sb_tour = SecondDepartmentSbTour::findOrFail($id_second_dep_duty);
		$second_department_sb_tour->fill([
			'id_element' => $request['id_element'],
			'id_view_work_elements' => $request['id_view_work_elements'],
			'number_duty' => $request['number_duty'],
			'date_duty' => $request['date_duty'],
			'count_elements' => $request['count_elements'],
			'addition_count_elements' => $request['addition_count_elements'],
			'id_unit' => $request['id_unit'],
			'id_caliber' => $request['id_caliber'],
			'number_party' => $request['number_party'],
			'date_worked' => $request['date_worked'] ? date('Y-m-d', strtotime($request['date_worked'])) : null,
			'number_logbook' => $request['number_logbook'],
			'date_logbook' => $request['date_logbook'],
			'number_notification' => $request['number_notification'],
			'date_notification' => $request['date_notification']
		]);
		$all_dirty = JournalController::getMyChanges($second_department_sb_tour);
		$second_department_sb_tour->save();
		JournalController::store(Auth::User()->id,'Обновлена сборка из второго отдела с id = ' . $id_second_dep_duty . ' для контракта с id = ' . $second_department_sb_tour->id_contract . '~' . json_encode($all_dirty));
		return redirect()->back()->with(['pls_back'=>'Сборка обновлена!','success'=>'Сборка обновлена!']);
	}
	
	public function update_tour_of_duty_us(Request $request, $id_second_dep_duty)
	{
		$val = Validator::make($request->all(),[
			'number_duty' => 'required',
			'date_duty' => 'required|date',
			'date_worked'	=> 'nullable|date',
			'date_help_report'	=> 'nullable|date'	
		])->validate();
		$second_department_us_tour = SecondDepartmentUsTour::findOrFail($id_second_dep_duty);
		$second_department_us_tour->fill([
			'number_duty' => $request['number_duty'],
			'date_duty' => $request['date_duty'],
			'date_worked' => $request['date_worked'] ? date('Y-m-d', strtotime($request['date_worked'])) : null,
			'number_help_report' => $request['number_help_report'],
			'date_help_report' => $request['date_help_report']
		]);
		$all_dirty = JournalController::getMyChanges($second_department_us_tour);
		$second_department_us_tour->save();
		JournalController::store(Auth::User()->id,'Обновлена услуга из второго отдела с id = ' . $id_second_dep_duty . ' для контракта с id = ' . $second_department_us_tour->id_contract . '~' . json_encode($all_dirty));
		return redirect()->back()->with(['pls_back'=>'Услуга обновлена!','success'=>'Услуга обновлена!']);
	}
	
	public function print_report(Request $request)
	{
		if($request['real_name_table'])
		{
			switch($request['real_name_table'])
			{
				case 'Сводный отчет по договорам':
					$view_contract_str = "contracts.id_counterpartie_contract";
					$view_contract_equal = ">";
					$view_contract = '';
					if(isset($request['view_work']))
						if($request['view_work'] != 'Все виды работ')
						{
							$view_contract = $request['view_work'];
							$view_contract_str = "view_contracts.name_view_contract";
							$view_contract_equal = "=";
						}
					$year_contract_str = "contracts.id_counterpartie_contract";
					$year_contract_equal = ">";
					$year_contract = '';
					if(isset($request['year']))
						if($request['year'] != '')
						{
							$year_contract = $request['year'];
							$year_contract_str = "contracts.year_contract";
							$year_contract_equal = "=";
						}
					$contracts = $this->print_report_1($view_contract_str, $view_contract_equal, $view_contract, $year_contract_str, $year_contract_equal, $year_contract);
					foreach($contracts as $contract){
						$states = State::select(['*'])->where('id_contract', $contract->id)->where('is_work_state', null)->get();
						if($states != null && count($states) > 0)
							$contract->state = $states[count($states) - 1];
					}
					return view('department.second.print_report', [
																	'real_name_table'=>$request['real_name_table'],
																	'view_work'=>$request['view_work'],
																	'contracts'=>$contracts
																]);
					break;
				case 'Поступление за период':
					/*$date_begin = date('Y', time()) . '-' . '01' . '-' . '01';
					$date_end = date('Y-m-d', time());
					if($request['date_begin'])
						if($request['date_begin'] != '')
							$date_begin = $request['date_begin'];
					if($request['date_end'])
						if($request['date_end'] != '')
							$date_end = $request['date_end'];*/
					$period1 = isset($request['date_begin']) && $request['date_begin'] != '' ? DATE('Y-m-d', strtotime($request['date_begin'])) : date('Y', time()) . '-01-01';
					$period2 = isset($request['date_end']) && $request['date_end'] != '' ? DATE('Y-m-d', strtotime($request['date_end'])) : date('Y-m-d', time());
					$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
					/*$second_department_tours = SecondDepartmentTour::Select('*')
															->join('contracts','second_department_tours.id_contract','contracts.id')
															->join('elements','second_department_tours.id_element', 'elements.id')
															->join('view_work_elements', 'second_department_tours.id_view_work_elements', 'view_work_elements.id')
															->whereBetween('second_department_tours.date_incoming', array(DATE('Y-m-d', strtotime($date_begin)),DATE('Y-m-d', strtotime($date_end))))
															->get()->sortByDesc('number_duty');*/
					$second_department_tours = DB::SELECT("SELECT * , second_department_tours.id as tourID FROM second_department_tours JOIN contracts ON contracts.id=second_department_tours.id_contract LEFT JOIN elements ON second_department_tours.id_element=elements.id LEFT JOIN view_work_elements ON second_department_tours.id_view_work_elements=view_work_elements.id LEFT JOIN second_department_units ON second_department_units.id=second_department_tours.id_unit LEFT JOIN results ON results.id=second_department_tours.id_result WHERE second_department_tours.deleted_at IS NULL AND (STR_TO_DATE(date_incoming,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') ORDER BY STR_TO_DATE(date_incoming,'%d.%m.%Y') ASC");
					//dd($second_department_tours);
					foreach($second_department_tours as $contract){
						foreach($counterparties as $counter)
							if($contract->id_counterpartie_contract == $counter->id)
								$contract->name_counterpartie_contract = $counter->name;
								
						//Получаем акты
						$pr_amount_acts = 0;
						$acts = SecondDepartmentAct::select(['number_act','amount_act','date_act'])
													->where('id_second_tour', $contract->tourID)
													->get();
						foreach($acts as $act){
							$pr_amount_acts += $act->amount_act;
							$contract->date_act = $act->date_act;
							$contract->number_act = $act->number_act;
						}
						$contract->amount_acts = $pr_amount_acts;
					}
					return view('department.second.print_report', [
																	'real_name_table'=>$request['real_name_table'],
																	'date_begin'=>$period1,
																	'date_end'=>$period2,
																	'second_department_tours'=>$second_department_tours
																]);
					break;
				case 'Выполнение за период':
					//TODO: 01.06.2020 года появился метод для создания разных видов отчётов по выполнению за период (остальные нужно будет убрать)
					if(isset($request['view_complete_report'])){
						$date_begin = date('Y', time()) . '-' . '01' . '-' . '01';
						$date_end = date('Y-m-d', time());
						if($request['date_begin'])
							if($request['date_begin'] != '')
								$date_begin = $request['date_begin'];
						if($request['date_end'])
							if($request['date_end'] != '')
								$date_end = $request['date_end'];
						$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
						$counterpartie_str = "contracts.id_counterpartie_contract";
						$counterpartie_equal = ">";
						$counterpartie = '';
						if(isset($request['counterpartie'])) {
							if(strlen($request['counterpartie']) > 0) {
								$counterpartie = $request['counterpartie'];
								$counterpartie_str = "id_counterpartie_contract";
								$counterpartie_equal = "=";
							}
						}
						switch($request['view_complete_report']){
							case 'Общий':
								$second_department_tours = SecondDepartmentTour::Select(['*', 'second_department_tours.id as tourID'])
																		->join('contracts','second_department_tours.id_contract','contracts.id')
																		->leftjoin('elements','second_department_tours.id_element', 'elements.id')
																		->leftjoin('view_work_elements', 'second_department_tours.id_view_work_elements', 'view_work_elements.id')
																		->leftJoin('second_department_units', 'second_department_units.id', 'second_department_tours.id_unit')
																		->where($counterpartie_str, $counterpartie_equal, $counterpartie)
																		->whereBetween('second_department_tours.date_worked', array(DATE('Y-m-d', strtotime($date_begin)),DATE('Y-m-d', strtotime($date_end))))
																		->get()->sortBy('number_duty');
								$second_department_sb_tours = SecondDepartmentSbTour::Select(['*', 'second_department_sb_tours.id as tourID'])
																		->join('contracts','second_department_sb_tours.id_contract','contracts.id')
																		->leftjoin('elements','second_department_sb_tours.id_element', 'elements.id')
																		->leftjoin('view_work_elements', 'second_department_sb_tours.id_view_work_elements', 'view_work_elements.id')
																		->leftJoin('second_department_units', 'second_department_units.id', 'second_department_sb_tours.id_unit')
																		->where($counterpartie_str, $counterpartie_equal, $counterpartie)
																		->whereBetween('second_department_sb_tours.date_worked', array(DATE('Y-m-d', strtotime($date_begin)),DATE('Y-m-d', strtotime($date_end))))
																		->get()->sortBy('number_duty');
								$second_department_us_tours = SecondDepartmentUsTour::Select(['*', 'second_department_us_tours.id as tourID'])
																		->join('contracts','second_department_us_tours.id_contract','contracts.id')
																		->where($counterpartie_str, $counterpartie_equal, $counterpartie)
																		->whereBetween('second_department_us_tours.date_worked', array(DATE('Y-m-d', strtotime($date_begin)),DATE('Y-m-d', strtotime($date_end))))
																		->orderBy(DB::raw("(number_duty+0)"),'asc')
																		->get();
								foreach($second_department_tours as $contract){
									foreach($counterparties as $counter)
										if($contract->id_counterpartie_contract == $counter->id)
											$contract->name_counterpartie_contract = $counter->name;
									$pr_amount_acts = 0;
									$acts = SecondDepartmentAct::select(['number_act','amount_act','date_act'])
																->where('id_second_tour', $contract->tourID)
																->get();
									foreach($acts as $act){
										$pr_amount_acts += $act->amount_act;
										$contract->date_act = $act->date_act;
										$contract->number_act = $act->number_act;
									}
									$contract->amount_acts = $pr_amount_acts;
								}
								foreach($second_department_sb_tours as $contract){
									foreach($counterparties as $counter)
										if($contract->id_counterpartie_contract == $counter->id)
											$contract->name_counterpartie_contract = $counter->name;
									$pr_amount_acts = 0;
									$acts = SecondDepartmentAct::select(['number_act','amount_act','date_act'])
																->where('id_second_sb_tour', $contract->tourID)
																->get();
									foreach($acts as $act){
										$pr_amount_acts += $act->amount_act;
										$contract->date_act = $act->date_act;
										$contract->number_act = $act->number_act;
									}
									$contract->amount_acts = $pr_amount_acts;
								}
								foreach($second_department_us_tours as $contract){
									foreach($counterparties as $counter)
										if($contract->id_counterpartie_contract == $counter->id)
											$contract->name_counterpartie_contract = $counter->name;
									$pr_amount_acts = 0;
									$acts = SecondDepartmentAct::select(['number_act','amount_act','date_act'])
																->where('id_second_us_tour', $contract->tourID)
																->get();
									foreach($acts as $act){
										$pr_amount_acts += $act->amount_act;
										$contract->date_act = $act->date_act;
										$contract->number_act = $act->number_act;
									}
									$contract->amount_acts = $pr_amount_acts;
								}
								return view('department.second.print_report', [
																				'real_name_table'=>'Выполнение за период',
																				'date_begin'=>$date_begin,
																				'date_end'=>$date_end,
																				'second_department_tours'=>$second_department_tours,
																				'second_department_sb_tours'=>$second_department_sb_tours,
																				'second_department_us_tours'=>$second_department_us_tours
																			]);
								break;
							case 'Испытания':
								$second_department_tours = SecondDepartmentTour::Select(['*', 'second_department_tours.id as tourID'])
																		->join('contracts','second_department_tours.id_contract','contracts.id')
																		->leftjoin('elements','second_department_tours.id_element', 'elements.id')
																		->leftjoin('view_work_elements', 'second_department_tours.id_view_work_elements', 'view_work_elements.id')
																		->leftJoin('second_department_units', 'second_department_units.id', 'second_department_tours.id_unit')
																		->leftJoin('results', 'results.id', 'second_department_tours.id_result')
																		->where($counterpartie_str, $counterpartie_equal, $counterpartie)
																		->whereBetween('second_department_tours.date_worked', array(DATE('Y-m-d', strtotime($date_begin)),DATE('Y-m-d', strtotime($date_end))))
																		->get()->sortBy('number_duty');
								//$second_department_tours = $second_department_tours->sortBy('number_duty', 0);
								foreach($second_department_tours as $contract){
									foreach($counterparties as $counter)
										if($contract->id_counterpartie_contract == $counter->id)
											$contract->name_counterpartie_contract = $counter->name;
									
									//Получаем акты
									$pr_amount_acts = 0;
									$acts = SecondDepartmentAct::select(['number_act','amount_act','date_act'])
																->where('id_second_tour', $contract->tourID)
																->get();
									foreach($acts as $act){
										$pr_amount_acts += $act->amount_act;
										$contract->date_act = $act->date_act;
										$contract->number_act = $act->number_act;
									}
									$contract->amount_acts = $pr_amount_acts;
								}
								return view('department.second.print_report', [
																				'real_name_table'=>'Выполнение за период (испытания)',
																				'date_begin'=>$date_begin,
																				'date_end'=>$date_end,
																				'second_department_tours'=>$second_department_tours
																			]);
								break;
							case 'Сборка':
								$second_department_sb_tours = SecondDepartmentSbTour::Select(['*', 'second_department_sb_tours.id as tourID'])
																		->join('contracts','second_department_sb_tours.id_contract','contracts.id')
																		->leftjoin('elements','second_department_sb_tours.id_element', 'elements.id')
																		->leftjoin('view_work_elements', 'second_department_sb_tours.id_view_work_elements', 'view_work_elements.id')
																		->leftJoin('second_department_units', 'second_department_units.id', 'second_department_sb_tours.id_unit')
																		->leftJoin('second_department_calibers', 'second_department_calibers.id', 'second_department_sb_tours.id_caliber')
																		->where($counterpartie_str, $counterpartie_equal, $counterpartie)
																		->whereBetween('second_department_sb_tours.date_worked', array(DATE('Y-m-d', strtotime($date_begin)),DATE('Y-m-d', strtotime($date_end))))
																		->get()->sortBy('number_duty');
								foreach($second_department_sb_tours as $contract){
									foreach($counterparties as $counter)
										if($contract->id_counterpartie_contract == $counter->id)
											$contract->name_counterpartie_contract = $counter->name;
									
									//Получаем акты
									$pr_amount_acts = 0;
									$acts = SecondDepartmentAct::select(['number_act','amount_act','date_act'])
																->where('id_second_sb_tour', $contract->tourID)
																->get();
									foreach($acts as $act){
										$pr_amount_acts += $act->amount_act;
										$contract->date_act = $act->date_act;
										$contract->number_act = $act->number_act;
									}
									$contract->amount_acts = $pr_amount_acts;
								}
								return view('department.second.print_report', [
																				'real_name_table'=>'Выполнение за период (сборка)',
																				'date_begin'=>$date_begin,
																				'date_end'=>$date_end,
																				'second_department_sb_tours'=>$second_department_sb_tours
																			]);
								break;
							case 'Услуги':
								$second_department_us_tours = SecondDepartmentUsTour::Select(['*', 'second_department_us_tours.id as tourID'])
																		->join('contracts','second_department_us_tours.id_contract','contracts.id')
																		->where($counterpartie_str, $counterpartie_equal, $counterpartie)
																		->whereBetween('second_department_us_tours.date_worked', array(DATE('Y-m-d', strtotime($date_begin)),DATE('Y-m-d', strtotime($date_end))))
																		->orderBy(DB::raw("(number_duty+0)"),'asc')
																		->get();
																		
								foreach($second_department_us_tours as $contract){
									foreach($counterparties as $counter)
										if($contract->id_counterpartie_contract == $counter->id)
											$contract->name_counterpartie_contract = $counter->name;
									
									//Получаем акты
									$pr_amount_acts = 0;
									$acts = SecondDepartmentAct::select(['number_act','amount_act','date_act'])
																->where('id_second_us_tour', $contract->tourID)
																->get();
									foreach($acts as $act){
										$pr_amount_acts += $act->amount_act;
										$contract->date_act = $act->date_act;
										$contract->number_act = $act->number_act;
									}
									$contract->amount_acts = $pr_amount_acts;
								}
								return view('department.second.print_report', [
																				'real_name_table'=>'Выполнение за период (услуги)',
																				'date_begin'=>$date_begin,
																				'date_end'=>$date_end,
																				'second_department_us_tours'=>$second_department_us_tours
																			]);
								break;
						}
					}
					break;
				case 'Выполнение за период по выходным и праздничным дням':
					$date_begin = date('Y', time()) . '-' . '01' . '-' . '01';
					$date_end = date('Y-m-d', time());
					if($request['date_begin'])
						if($request['date_begin'] != '')
							$date_begin = $request['date_begin'];
					if($request['date_end'])
						if($request['date_end'] != '')
							$date_end = $request['date_end'];
					
					if(date('Y', strtotime($date_begin)) != date('Y', strtotime($date_end)))
						return redirect()->back()->with(['error'=>'Указывайте период в одном году!']);
					
					$year = date('Y', strtotime($date_end));
					
					if(!file_exists('calendar/' . $year . '.xml'))
						return redirect()->back()->with(['error'=>'Не удалось найти файл календаря за ' . $year . ' год!']);
					
					$calendar = simplexml_load_file('calendar/' . $year . '.xml');
					$calendar = $calendar->days->day;
					
					$holidays = [];
					$workdays = [];
					foreach($calendar as $day){
						$d = (array)$day->attributes()->d;
						$d = $d[0];
						$d = $year . '-' . substr($d, 0, 2) . '-' . substr($d, 3, 2);
						
						if($day->attributes()->t == 1)
							array_push($holidays, $d);
						else
							array_push($workdays, $d);
					}

					$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
					$second_department_tours = SecondDepartmentTour::Select(['*', 'second_department_tours.id as tourID'])
															->join('contracts','second_department_tours.id_contract','contracts.id')
															->leftjoin('elements','second_department_tours.id_element', 'elements.id')
															->leftjoin('view_work_elements', 'second_department_tours.id_view_work_elements', 'view_work_elements.id')
															->leftJoin('second_department_units', 'second_department_units.id', 'second_department_tours.id_unit')
															->whereBetween('second_department_tours.date_worked', array(DATE('Y-m-d', strtotime($date_begin)),DATE('Y-m-d', strtotime($date_end))))
															->orderBy('second_department_tours.date_worked')
															->get();
					$second_department_sb_tours = SecondDepartmentSbTour::Select(['*', 'second_department_sb_tours.id as tourID'])
															->join('contracts','second_department_sb_tours.id_contract','contracts.id')
															->leftjoin('elements','second_department_sb_tours.id_element', 'elements.id')
															->leftjoin('view_work_elements', 'second_department_sb_tours.id_view_work_elements', 'view_work_elements.id')
															->leftJoin('second_department_units', 'second_department_units.id', 'second_department_sb_tours.id_unit')
															->whereBetween('second_department_sb_tours.date_worked', array(DATE('Y-m-d', strtotime($date_begin)),DATE('Y-m-d', strtotime($date_end))))
															->orderBy('second_department_sb_tours.date_worked')
															->get();
					$second_department_us_tours = SecondDepartmentUsTour::Select(['*', 'second_department_us_tours.id as tourID'])
															->join('contracts','second_department_us_tours.id_contract','contracts.id')
															->whereBetween('second_department_us_tours.date_worked', array(DATE('Y-m-d', strtotime($date_begin)),DATE('Y-m-d', strtotime($date_end))))
															->orderBy(DB::raw("(number_duty+0)"),'asc')
															->orderBy('second_department_us_tours.date_worked')
															->get();
					
					$results = [];
					foreach($second_department_tours as $contract){
						// Находим день до пятницы
						if(date('N', strtotime($contract->date_worked)) < 6){
							// Проверяем, выходной ли это
							if(array_search($contract->date_worked, $holidays) === false)
								continue;
						}else{
							// Проверяем рабочий ли это день в субботу или воскресенье
							if(array_search($contract->date_worked, $workdays) !== false)
								continue;
						}
					
						foreach($counterparties as $counter)
							if($contract->id_counterpartie_contract == $counter->id)
								$contract->name_counterpartie_contract = $counter->name;
						$pr_amount_acts = 0;
						$contract->amount_acts = $pr_amount_acts;
						
						array_push($results, $contract);
					}
					foreach($second_department_sb_tours as $contract){
						if(date('N', strtotime($contract->date_worked)) < 6){
							if(array_search($contract->date_worked, $holidays) === false)
								continue;
						}else{
							if(array_search($contract->date_worked, $workdays) !== false)
								continue;
						}
						
						foreach($counterparties as $counter)
							if($contract->id_counterpartie_contract == $counter->id)
								$contract->name_counterpartie_contract = $counter->name;
						$pr_amount_acts = 0;
						$contract->amount_acts = $pr_amount_acts;
						
						array_push($results, $contract);
					}
					foreach($second_department_us_tours as $contract){
						if(date('N', strtotime($contract->date_worked)) < 6){
							if(array_search($contract->date_worked, $holidays) === false)
								continue;
						}else{
							if(array_search($contract->date_worked, $workdays) !== false)
								continue;
						}
						
						foreach($counterparties as $counter)
							if($contract->id_counterpartie_contract == $counter->id)
								$contract->name_counterpartie_contract = $counter->name;
						$pr_amount_acts = 0;
						$contract->amount_acts = $pr_amount_acts;
						
						array_push($results, $contract);
					}
					//dd($results);
					return view('department.second.print_report', [
																	'real_name_table'=>'Выполнение за период по выходным и праздничным дням',
																	'date_begin'=>$date_begin,
																	'date_end'=>$date_end,
																	'results'=>$results
																]);
					break;
				case 'Выполнение за период (испытания)':
					$date_begin = date('Y', time()) . '-' . '01' . '-' . '01';
					$date_end = date('Y-m-d', time());
					if($request['date_begin'])
						if($request['date_begin'] != '')
							$date_begin = $request['date_begin'];
					if($request['date_end'])
						if($request['date_end'] != '')
							$date_end = $request['date_end'];
					$view_isp = '0';
					$view_isp_equal = '>';
					if($request['view_isp'])
						if($request['view_isp'] != '0')
						{
							$view_isp = $request['view_isp'];
							$view_isp_equal = '=';
						}
					$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
					$second_department_tours = SecondDepartmentTour::Select(['*', 'second_department_tours.id as tourID'])
															->join('contracts','second_department_tours.id_contract','contracts.id')
															->leftjoin('elements','second_department_tours.id_element', 'elements.id')
															->leftjoin('view_work_elements', 'second_department_tours.id_view_work_elements', 'view_work_elements.id')
															->leftJoin('second_department_units', 'second_department_units.id', 'second_department_tours.id_unit')
															->leftJoin('results', 'results.id', 'second_department_tours.id_result')
															->where('view_work_elements.id', $view_isp_equal, $view_isp)
															->whereBetween('second_department_tours.date_worked', array(DATE('Y-m-d', strtotime($date_begin)),DATE('Y-m-d', strtotime($date_end))))
															->get()->sortBy('number_duty');
					//$second_department_tours = $second_department_tours->sortBy('number_duty', 0);
					foreach($second_department_tours as $contract){
						foreach($counterparties as $counter)
							if($contract->id_counterpartie_contract == $counter->id)
								$contract->name_counterpartie_contract = $counter->name;
						
						//Получаем акты
						$pr_amount_acts = 0;
						$acts = SecondDepartmentAct::select(['number_act','amount_act','date_act'])
													->where('id_second_tour', $contract->tourID)
													->get();
						foreach($acts as $act){
							$pr_amount_acts += $act->amount_act;
							$contract->date_act = $act->date_act;
							$contract->number_act = $act->number_act;
						}
						$contract->amount_acts = $pr_amount_acts;
					}
					return view('department.second.print_report', [
																	'real_name_table'=>$request['real_name_table'],
																	'date_begin'=>$date_begin,
																	'date_end'=>$date_end,
																	'second_department_tours'=>$second_department_tours
																]);
					break;
				case 'Выполнение за период (сборка)':
					$date_begin = date('Y', time()) . '-' . '01' . '-' . '01';
					$date_end = date('Y-m-d', time());
					if($request['date_begin'])
						if($request['date_begin'] != '')
							$date_begin = $request['date_begin'];
					if($request['date_end'])
						if($request['date_end'] != '')
							$date_end = $request['date_end'];
					$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
					$second_department_sb_tours = SecondDepartmentSbTour::Select(['*', 'second_department_sb_tours.id as tourID'])
															->join('contracts','second_department_sb_tours.id_contract','contracts.id')
															->leftjoin('elements','second_department_sb_tours.id_element', 'elements.id')
															->leftjoin('view_work_elements', 'second_department_sb_tours.id_view_work_elements', 'view_work_elements.id')
															->leftJoin('second_department_units', 'second_department_units.id', 'second_department_sb_tours.id_unit')
															->leftJoin('second_department_calibers', 'second_department_calibers.id', 'second_department_sb_tours.id_caliber')
															->whereBetween('second_department_sb_tours.date_worked', array(DATE('Y-m-d', strtotime($date_begin)),DATE('Y-m-d', strtotime($date_end))))
															->get()->sortBy('number_duty');
					foreach($second_department_sb_tours as $contract){
						foreach($counterparties as $counter)
							if($contract->id_counterpartie_contract == $counter->id)
								$contract->name_counterpartie_contract = $counter->name;
						
						//Получаем акты
						$pr_amount_acts = 0;
						$acts = SecondDepartmentAct::select(['number_act','amount_act','date_act'])
													->where('id_second_sb_tour', $contract->tourID)
													->get();
						foreach($acts as $act){
							$pr_amount_acts += $act->amount_act;
							$contract->date_act = $act->date_act;
							$contract->number_act = $act->number_act;
						}
						$contract->amount_acts = $pr_amount_acts;
					}
					return view('department.second.print_report', [
																	'real_name_table'=>$request['real_name_table'],
																	'date_begin'=>$date_begin,
																	'date_end'=>$date_end,
																	'second_department_sb_tours'=>$second_department_sb_tours
																]);
					break;
				case 'Выполнение за период (услуги)':
					$date_begin = date('Y', time()) . '-' . '01' . '-' . '01';
					$date_end = date('Y-m-d', time());
					if($request['date_begin'])
						if($request['date_begin'] != '')
							$date_begin = $request['date_begin'];
					if($request['date_end'])
						if($request['date_end'] != '')
							$date_end = $request['date_end'];
					$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
					$second_department_us_tours = SecondDepartmentUsTour::Select(['*', 'second_department_us_tours.id as tourID'])
															->join('contracts','second_department_us_tours.id_contract','contracts.id')
															->whereBetween('second_department_us_tours.date_worked', array(DATE('Y-m-d', strtotime($date_begin)),DATE('Y-m-d', strtotime($date_end))))
															->orderBy(DB::raw("(number_duty+0)"),'asc')
															->get();
															
					foreach($second_department_us_tours as $contract){
						foreach($counterparties as $counter)
							if($contract->id_counterpartie_contract == $counter->id)
								$contract->name_counterpartie_contract = $counter->name;
						
						//Получаем акты
						$pr_amount_acts = 0;
						$acts = SecondDepartmentAct::select(['number_act','amount_act','date_act'])
													->where('id_second_us_tour', $contract->tourID)
													->get();
						foreach($acts as $act){
							$pr_amount_acts += $act->amount_act;
							$contract->date_act = $act->date_act;
							$contract->number_act = $act->number_act;
						}
						$contract->amount_acts = $pr_amount_acts;
					}
					return view('department.second.print_report', [
																	'real_name_table'=>$request['real_name_table'],
																	'date_begin'=>$date_begin,
																	'date_end'=>$date_end,
																	'second_department_us_tours'=>$second_department_us_tours
																]);
					break;
				case 'Оплата за период':
					$date_begin = date('Y', time()) . '-' . '01' . '-' . '01';
					$date_end = date('Y-m-d', time());
					if($request['date_begin'])
						if($request['date_begin'] != '')
							$date_begin = $request['date_begin'];
					if($request['date_end'])
						if($request['date_end'] != '')
							$date_end = $request['date_end'];
					$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
					$payments = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice', DB::raw("STR_TO_DATE(date_invoice,'%Y-%m-%d') as STR_DATE")])
											->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
											->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
											->join('contracts', 'invoices.id_contract', 'contracts.id')
											->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
											->leftjoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
											->where('is_sip_contract', 1)
											->where('name', 'Оплата')
											->whereBetween('date_invoice', array(DATE('Y-m-d', strtotime($date_begin)),DATE('Y-m-d', strtotime($date_end))))
											->orderBy('date_invoice', 'asc')
											->get();
					foreach($payments as $contract)
						foreach($counterparties as $counter)
							if($contract->id_counterpartie_contract == $counter->id)
								$contract->name_counterpartie_contract = $counter->name;
					return view('department.second.print_report', [
																	'real_name_table'=>$request['real_name_table'],
																	'date_begin'=>$date_begin,
																	'date_end'=>$date_end,
																	'payments'=>$payments
																]);
					break;
				case 'Предприятия за год к':
					$contracts = Contract::select('id_counterpartie_contract', 'name_view_contract')
											->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
											->leftjoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
											->leftJoin('second_department_tours', 'contracts.id', 'second_department_tours.id_contract')
											->where('is_sip_contract', 1)
											//->where('year_contract', $request['year'])
											->where('date_incoming', 'like', '%'.$request['year'])
											->where('id_counterpartie_contract' , '>' , 0)
											->where('contracts.deleted_at', null)
											->get();
					$counterparties = Counterpartie::select(['*'])->get();
					$result = [];
					foreach($contracts as $contract)
						if($contract->name_view_contract == 'Испытания контрольные' || $contract->name_view_contract == 'Испытания опытные' || $contract->name_view_contract == 'Сборка ')
							foreach($counterparties as $counter)
								if($contract->id_counterpartie_contract == $counter->id)
								{
									$contract->name_counterpartie_contract = $counter->name_full;
									if(in_array($contract->name_view_contract, array_keys($result)))
									{
										if(!in_array($contract->name_counterpartie_contract, $result[$contract->name_view_contract]))
											array_push($result[$contract->name_view_contract], $contract->name_counterpartie_contract);
									}
									else
									{
										$result += [$contract->name_view_contract => [$contract->name_counterpartie_contract]];
									}
									break;
								}
					return view('department.second.print_report', [
																	'real_name_table'=>$request['real_name_table'],
																	'year'=>$request['year'],
																	'contracts'=>$result
																]);
					break;
				case 'Предприятия за год':
					$contracts = Contract::select('id_counterpartie_contract', 'name_view_contract', 'item_contract')
											->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
											->leftjoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
											->leftJoin('second_department_tours', 'contracts.id', 'second_department_tours.id_contract')
											->where('is_sip_contract', 1)
											//->where('year_contract', $request['year'])
											->where('date_incoming', 'like', '%'.$request['year'])
											->where('id_counterpartie_contract' , '>' , 0)
											->where('contracts.deleted_at', null)
											->get();
					$counterparties = Counterpartie::select(['*'])->get();
					$result = [];
					foreach($contracts as $contract)
						if($contract->name_view_contract == 'Испытания контрольные' || $contract->name_view_contract == 'Испытания опытные' || $contract->name_view_contract == 'Сборка ')
							foreach($counterparties as $counter)
								if($contract->id_counterpartie_contract == $counter->id)
								{
									$contract->name_counterpartie_contract = $counter->name_full;
									if(in_array($counter->name_full, array_keys($result)))
									{
										if(!in_array($contract->name_view_contract, $result[$contract->name_counterpartie_contract]['view']))
											array_push($result[$contract->name_counterpartie_contract]['view'], $contract->name_view_contract);
										if(!in_array($contract->item_contract, $result[$contract->name_counterpartie_contract]['name_work']))
											array_push($result[$contract->name_counterpartie_contract]['name_work'], $contract->item_contract);
									}
									else
									{
										$result += [$contract->name_counterpartie_contract => ['view' => [$contract->name_view_contract], 'name_work' => [$contract->item_contract]]];
									}
									break;
								}
					//dd($result);
					return view('department.second.print_report', [
																	'real_name_table'=>$request['real_name_table'],
																	'year'=>$request['year'],
																	'result'=>$result
																]);
					break;
				case 'Сводный отчет по оплате':
					$view_contract_str = "contracts.id_counterpartie_contract";
					$view_contract_equal = ">";
					$view_contract = '';
					if(isset($request['view_work']))
						if($request['view_work'] != 'Все виды работ')
						{
							$view_contract = $request['view_work'];
							$view_contract_str = "view_contracts.name_view_contract";
							$view_contract_equal = "=";
						}
					$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
					if(!isset($_POST['full_report']))
						$payments = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
												->leftJoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
												->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
												->join('contracts', 'invoices.id_contract', 'contracts.id')
												->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
												->leftjoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
												->where('is_sip_contract', 1)
												->where($view_contract_str, $view_contract_equal, $view_contract)
												->where('contracts.deleted_at', null)
												->orderBy('contracts.id_counterpartie_contract', 'asc')
												->orderBy('reestr_contracts.id_view_contract', 'asc')
												->orderBy('contracts.number_contract', 'asc')
												->get();
					else
						$payments = Contract::select(['*','invoices.id','view_invoices.name_view_invoice'])
												->leftjoin('invoices','invoices.id_contract','contracts.id')
												->leftJoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
												->leftjoin('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
												->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
												->leftjoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
												->where('is_sip_contract', 1)
												->where($view_contract_str, $view_contract_equal, $view_contract)
												->where('contracts.deleted_at', null)
												->orderBy('contracts.id_counterpartie_contract', 'asc')
												->orderBy('reestr_contracts.id_view_contract', 'asc')
												->orderBy('contracts.number_contract', 'asc')
												->get();
					foreach($payments as $payment)
						foreach($counterparties as $counter)
							if($payment->id_counterpartie_contract == $counter->id)
								$payment->name_counterpartie_contract = $counter->name;
					$result = [];
					$proverka_in_result = [];
					foreach($payments as $score)
					{
						if(in_array($score->number_contract, $proverka_in_result))
						{
							foreach($result as $res)
								if($res->number_contract == $score->number_contract)
								{
									if($score->name == 'Счет на оплату')
										$res->amount_scores += $score->amount_p_invoice;
									else if($score->name == 'Аванс')
										$res->amount_scores += $score->amount_p_invoice;
									else if($score->name == 'Счет-фактура')
										$res->amount_invoices += $score->amount_p_invoice;
									else if($score->name == 'Оплата')
										if($score->is_prepayment_invoice != 1)
											$res->amount_payments += $score->amount_p_invoice;
										else
											$res->amount_prepayments += $score->amount_p_invoice;
									else if($score->name == 'Возврат')
										$res->amount_returns += $score->amount_p_invoice;
								}
						}
						else
						{
							array_push($proverka_in_result, $score->number_contract);
							if($score->name == 'Счет на оплату')
								$score->amount_scores += $score->amount_p_invoice;
							else if($score->name == 'Аванс')
								$score->amount_scores += $score->amount_p_invoice;
							else if($score->name == 'Счет-фактура')
								$score->amount_invoices += $score->amount_p_invoice;
							else if($score->name == 'Оплата')
								if($score->is_prepayment_invoice != 1)
									$score->amount_payments += $score->amount_p_invoice;
								else
									$score->amount_prepayments += $score->amount_p_invoice;
							else if($score->name == 'Возврат')
								$score->amount_returns += $score->amount_p_invoice;
							array_push($result, $score);
						}
					}
					foreach($result as $score)
					{
						$score->debet += $score->name_view_work != 'Комплектация' ? (($score->amount_invoices - ($score->amount_payments+$score->amount_prepayments) + $score->amount_returns) > 0 ? $score->amount_invoices - ($score->amount_payments+$score->amount_prepayments) + $score->amount_returns : 0) : (($score->amount_payments+$score->amount_prepayments - $score->amount_invoices + $score->amount_returns) > 0 ? ($score->amount_payments+$score->amount_prepayments) - $score->amount_invoices + $score->amount_returns : 0);
						$score->kredit += $score->name_view_work != 'Комплектация' ? (($score->amount_payments+$score->amount_prepayments - $score->amount_invoices - $score->amount_returns) > 0 ? ($score->amount_payments+$score->amount_prepayments) - $score->amount_invoices - $score->amount_returns : 0) : (($score->amount_invoices - ($score->amount_payments+$score->amount_prepayments) - $score->amount_returns) > 0 ? $score->amount_invoices - ($score->amount_payments+$score->amount_prepayments) - $score->amount_returns : 0);
					}
					return view('department.second.print_report', [
																	'real_name_table'=>$request['real_name_table'],
																	'view_work'=>$request['view_work'],
																	'payments'=>$result
																]);
					break;
				case 'Выполнение работ по договорам':
					$view_contract_str = "contracts.id_counterpartie_contract";
					$view_contract_equal = ">";
					$view_contract = '';
					if(isset($request['view_work']))
						if($request['view_work'] != 'Все виды работ')
						{
							$view_contract = $request['view_work'];
							$view_contract_str = "view_contracts.name_view_contract";
							$view_contract_equal = "=";
						}
					$year_contract_str = "contracts.id_counterpartie_contract";
					$year_contract_equal = ">";
					$year_contract = '';
					if(isset($request['year']))
						if($request['year'] != '')
						{
							$year_contract = $request['year'];
							$year_contract_str = "contracts.year_contract";
							$year_contract_equal = "=";
						}
					$contracts = $this->print_report_1($view_contract_str, $view_contract_equal, $view_contract, $year_contract_str, $year_contract_equal, $year_contract);
					foreach($contracts as $contract)
					{
						//Выполнение работ
						$work_states = State::select(['states.id','name_state','comment_state','date_state'])->where('id_contract', $contract->id)->where('is_work_state', 1)->get();
						$contract->work_states = $work_states;
						
						//Наряды
						$dutys = SecondDepartmentTour::select(['number_duty','date_incoming'])->where('id_contract', $contract->id)->get();
						foreach($dutys as $duty)
							if(date('Y',strtotime($duty->date_incoming)) != date('Y',time()))
								$duty->year = 'on';
						$contract->dutys = $dutys;
						
						//Наряды на сборку
						$dutys_sb = SecondDepartmentSbTour::select(['number_duty'])->where('id_contract', $contract->id)->get();
						$contract->dutys_sb = $dutys_sb;
						
						//Наряды на услуги
						$dutys_us = SecondDepartmentUsTour::select(['number_duty'])->where('id_contract', $contract->id)->get();
						$contract->dutys_us = $dutys_us;
					}
					//dd($contracts);
					return view('department.second.print_report', [
																	'real_name_table'=>$request['real_name_table'],
																	'view_work'=>$request['view_work'],
																	'contracts'=>$contracts
																]);
					break;
				case 'Тестовый режим':
					$period1 = isset($request['date_begin']) && $request['date_begin'] != '' ? DATE('Y-m-d', strtotime($request['date_begin'])) : date('Y', time()) . '-01-01';
					$period2 = isset($request['date_end']) && $request['date_end'] != '' ? DATE('Y-m-d', strtotime($request['date_end'])) : date('Y-m-d', time());
					$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
					$second_department_tours = DB::SELECT("SELECT id_counterpartie_contract,app_outgoing_number_reestr,name_element,name_view_work_elements,number_contract,number_telegram,date_telegram,number_report,number_duty,date_incoming FROM second_department_tours JOIN contracts ON contracts.id=second_department_tours.id_contract LEFT JOIN elements ON second_department_tours.id_element=elements.id LEFT JOIN view_work_elements ON second_department_tours.id_view_work_elements=view_work_elements.id LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr WHERE second_department_tours.deleted_at IS NULL AND (STR_TO_DATE(date_incoming,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') ORDER BY id_counterpartie_contract ASC");
					foreach($second_department_tours as $contract){
						foreach($counterparties as $counter)
							if($contract->id_counterpartie_contract == $counter->id)
								$contract->name_counterpartie_contract = $counter->name;
					}
					//Распределение испытаний по контрактам по месяцам
					$result = [];
					/*foreach($second_department_tours as $contract){
						if(in_array($contract->number_contract, array_keys($result))){
							array_push($result[$contract->number_contract], $contract);
						}
						else{
							$result += [$month=>[$contract->number_contract=>$contract]];
						}
					}*/
					foreach($second_department_tours as $contract){
						//Определяем месяц испытания
						$month = DATE('m', strtotime($contract->date_incoming));
						if(in_array($month, array_keys($result))){
							//Проверка на наличие контракта
							if(in_array($contract->number_contract, array_keys($result[$month]))){
								//Проверяем на уникальность изделий
								if(!in_array($contract->name_element, $result[$month][$contract->number_contract]['name_elements'])){
									array_push($result[$month][$contract->number_contract]['name_elements'], $contract->name_element);
								}
								//Проверяем на уникальность видов испытаний
								if(!in_array($contract->name_view_work_elements, $result[$month][$contract->number_contract]['name_view_work_elements'])){
									array_push($result[$month][$contract->number_contract]['name_view_work_elements'], $contract->name_view_work_elements);
								}
								//Проверяем на телеграммы / отчёты
								if(in_array($contract->number_telegram, array_keys($result[$month][$contract->number_contract]['number_telegrams']))){
									array_push($result[$month][$contract->number_contract]['number_telegrams'][$contract->number_telegram]['reports'], $contract->number_report);
								}else{
									$result[$month][$contract->number_contract]['number_telegrams'] += [$contract->number_telegram=>['date_telegram'=>$contract->date_telegram, 'reports'=>[$contract->number_report]]];
								}
								//Добавляем наряды
								array_push($result[$month][$contract->number_contract]['number_dutys'], $contract->number_duty);
							}else{
								$prContract = ['name_counterpartie_contract'=>$contract->name_counterpartie_contract,
											'app_outgoing_number_reestr'=>$contract->app_outgoing_number_reestr,
											'name_elements'=>[$contract->name_element],
											'name_view_work_elements'=>[$contract->name_view_work_elements],
											'number_contract'=>$contract->number_contract,
											'number_telegrams'=>[$contract->number_telegram=>['date_telegram'=>$contract->date_telegram, 'reports'=>[$contract->number_report]]],
											'number_dutys'=>[$contract->number_duty]];
								$result[$month] += [$contract->number_contract=>$prContract];
							}
						}else{
							$prContract = ['name_counterpartie_contract'=>$contract->name_counterpartie_contract,
											'app_outgoing_number_reestr'=>$contract->app_outgoing_number_reestr,
											'name_elements'=>[$contract->name_element],
											'name_view_work_elements'=>[$contract->name_view_work_elements],
											'number_contract'=>$contract->number_contract,
											'number_telegrams'=>[$contract->number_telegram=>['date_telegram'=>$contract->date_telegram, 'reports'=>[$contract->number_report]]],
											'number_dutys'=>[$contract->number_duty]];
							$result += [$month=>[$contract->number_contract=>$prContract]];
						}
					}
					//Сортировка по месяцам
					ksort($result);
					$new_result = [];
					foreach($result as $key=>$value){
						switch($key){
							case 1:
								$new_key = 'Январь';
								break;
							case 2:
								$new_key = 'Февраль';
								break;
							case 3:
								$new_key = 'Март';
								break;
							case 4:
								$new_key = 'Апрель';
								break;
							case 5:
								$new_key = 'Май';
								break;
							case 6:
								$new_key = 'Июнь';
								break;
							case 7:
								$new_key = 'Июль';
								break;
							case 8:
								$new_key = 'Август';
								break;
							case 9:
								$new_key = 'Сентябрь';
								break;
							case 10:
								$new_key = 'Октябрь';
								break;
							case 11:
								$new_key = 'Ноябрь';
								break;
							case 12:
								$new_key = 'Декабрь';
								break;
							default:
								$new_key = '';
								break;
						}
						$prArr = [$new_key => $result[$key]];
						$new_result += $prArr;
					}
					$period1 = DATE('d.m.Y', strtotime($period1));
					$period2 = DATE('d.m.Y', strtotime($period2));
					//dd($result);
					return view('department.second.print_report', [
																	'real_name_table'=>$request['real_name_table'],
																	'contracts'=>$new_result,
																	'period1'=>$period1,
																	'period2'=>$period2
																]);
					break;
				case 'Испытано за период':
					$date_begin = date('Y', time()) . '-' . '01' . '-' . '01';
					$date_end = date('Y-m-d', time());
					if($request['date_begin'])
						if($request['date_begin'] != '')
							$date_begin = $request['date_begin'];
					if($request['date_end'])
						if($request['date_end'] != '')
							$date_end = $request['date_end'];
					$second_department_tours = SecondDepartmentTour::Select(['*', 'second_department_tours.id as tourID'])
															->leftJoin('second_department_calibers','id_caliber','second_department_calibers.id')
															->leftJoin('second_department_name_elements','id_name_element','second_department_name_elements.id')
															->whereBetween('second_department_tours.date_worked', array(DATE('Y-m-d', strtotime($date_begin)),DATE('Y-m-d', strtotime($date_end))))
															->orderBy('name_caliber', 'asc')
															->orderBy('number_duty', 'asc')
															->get();
															//->sortBy('number_duty');
					// Костыль для сортировки
					$new_result = ['снаряды, выстрелы, мины'=>['count'=>0],
								'гильзы'=>['count'=>0],
								'пороха, заряды'=>['count'=>0],
								'СВ, взрыватели, трассера'=>['count'=>0],
								'СББ'=>['count'=>0],
								'авиционные средства поражения'=>['count'=>0],
								'БЧ'=>['count'=>0],
								'б/плиты'=>['count'=>0],
								'военная техника'=>['count'=>0],
								'инженерные боеприпасы'=>['count'=>0]];
					$count_warm = 0;
					$count_tour = 0;
					foreach($second_department_tours as $tour){
						if ($tour->name_element == '')	// Наряды по сборке и опытные
							continue;
						$count_shot = 0 + $tour->countable + $tour->targeting + $tour->uncountable + $tour->renouncement;
						$count_warm += $tour->warm;
						$count_tour++;
						if(!in_array($tour->name_element, array_keys($new_result))){
							$new_result += [$tour->name_element=>['count'=>$count_shot]];
							if($tour->name_caliber != null){
								$new_result[$tour->name_element] += [$tour->name_caliber=>$count_shot];
							}
						}else{
							$new_result[$tour->name_element]['count'] += $count_shot;
							if($tour->name_caliber != null){
								if(in_array($tour->name_caliber, array_keys($new_result[$tour->name_element]))){
									$new_result[$tour->name_element][$tour->name_caliber] += $count_shot;
								}else{
									$new_result[$tour->name_element] += [$tour->name_caliber=>$count_shot];
								}
							}
						}
					}
					return view('department.second.print_report', [
																	'real_name_table'=>$request['real_name_table'],
																	'date_begin'=>$date_begin,
																	'date_end'=>$date_end,
																	'contracts'=>$new_result,
																	'count_warm'=>$count_warm,
																	'count_tour'=>$count_tour
																]);
					break;
				case 'Номенклатура за период':
					$date_begin = date('Y', time()) . '-' . '01' . '-' . '01';
					$date_end = date('Y-m-d', time());
					if($request['date_begin'])
						if($request['date_begin'] != '')
							$date_begin = $request['date_begin'];
					if($request['date_end'])
						if($request['date_end'] != '')
							$date_end = $request['date_end'];
					$second_department_tours = SecondDepartmentTour::Select(['*', 'second_department_tours.id as tourID', 'elements.name_element as nameElement', 'second_department_name_elements.name_element as name_element'])
															->leftJoin('elements', 'id_element', 'elements.id')
															->leftJoin('second_department_calibers','id_caliber','second_department_calibers.id')
															->leftJoin('second_department_name_elements','id_name_element','second_department_name_elements.id')
															->whereBetween('second_department_tours.date_worked', array(DATE('Y-m-d', strtotime($date_begin)),DATE('Y-m-d', strtotime($date_end))))
															//->orderBy('id_element', 'asc')
															->orderBy('name_caliber', 'asc')
															->orderBy('number_duty', 'asc')
															->get();
															//->sortBy('number_duty');
					// Костыль для сортировки
					$new_result = ['снаряды, выстрелы, мины'=>['elements'=>[]],
								'гильзы'=>['elements'=>[]],
								'пороха, заряды'=>['elements'=>[]],
								'СВ, взрыватели, трассера'=>['elements'=>[]],
								'СББ'=>['elements'=>[]],
								'авиционные средства поражения'=>['elements'=>[]],
								'БЧ'=>['elements'=>[]],
								'б/плиты'=>['elements'=>[]],
								'военная техника'=>['elements'=>[]],
								'инженерные боеприпасы'=>['elements'=>[]]];	
					foreach($second_department_tours as $tour){
						if ($tour->name_element == '')	// Наряды по сборке и опытные
							continue;
						if(!in_array($tour->name_element, array_keys($new_result))){
							if($tour->name_caliber != null){
								$new_result += [$tour->name_element=>[$tour->name_caliber=>[$tour->nameElement]]];
							}else{
								$new_result += [$tour->name_element=>['elements'=>[$tour->nameElement]]];
							}
						}else{
							if($tour->name_caliber != null){
								if(in_array($tour->name_caliber, array_keys($new_result[$tour->name_element]))){
									if(!in_array($tour->nameElement, $new_result[$tour->name_element][$tour->name_caliber]))
										array_push($new_result[$tour->name_element][$tour->name_caliber], $tour->nameElement);
								}else{
									$new_result[$tour->name_element] += [$tour->name_caliber=>[$tour->nameElement]];
								}
							}else{
								if(!in_array($tour->nameElement, $new_result[$tour->name_element]['elements']))
									array_push($new_result[$tour->name_element]['elements'], $tour->nameElement);
							}
						}
					}
					return view('department.second.print_report', [
																	'real_name_table'=>$request['real_name_table'],
																	'date_begin'=>$date_begin,
																	'date_end'=>$date_end,
																	'contracts'=>$new_result
																]);
					break;
				case 'Наряды за год по виду':
					$view_isp = '0';
					$view_isp_equal = '>';
					if($request['view_isp'])
						if($request['view_isp'] != '0')
						{
							$view_isp = $request['view_isp'];
							$view_isp_equal = '=';
						}
					//dd($view_contract);
					$year_contract = date('Y', time());
					if(isset($request['year']))
						if($request['year'] != '')
							$year_contract = $request['year'];
					$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
					$second_department_tours = SecondDepartmentTour::Select(['*', 'second_department_tours.id as tourID'])
															->join('contracts','second_department_tours.id_contract','contracts.id')
															->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
															->leftjoin('elements','second_department_tours.id_element', 'elements.id')
															->leftjoin('view_work_elements', 'second_department_tours.id_view_work_elements', 'view_work_elements.id')
															->leftJoin('second_department_units', 'second_department_units.id', 'second_department_tours.id_unit')
															->where('second_department_tours.created_at', 'like', $year_contract . '%')
															->where('view_work_elements.id', $view_isp_equal, $view_isp)
															->get()
															->sortBy('number_duty');
					$second_department_sb_tours = SecondDepartmentSbTour::Select(['*', 'second_department_sb_tours.id as tourID'])
															->join('contracts','second_department_sb_tours.id_contract','contracts.id')
															->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
															->leftjoin('elements','second_department_sb_tours.id_element', 'elements.id')
															->leftjoin('view_work_elements', 'second_department_sb_tours.id_view_work_elements', 'view_work_elements.id')
															->leftJoin('second_department_units', 'second_department_units.id', 'second_department_sb_tours.id_unit')
															->where('second_department_sb_tours.created_at', 'like', $year_contract . '%')
															->where('view_work_elements.id', $view_isp_equal, $view_isp)
															->get()
															->sortBy('number_duty');
					if($view_isp == '0')
						$second_department_us_tours = SecondDepartmentUsTour::Select(['*', 'second_department_us_tours.id as tourID'])
																->join('contracts','second_department_us_tours.id_contract','contracts.id')
																->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
																->where('second_department_us_tours.created_at', 'like', $year_contract . '%')
																->orderBy(DB::raw("(number_duty+0)"),'asc')
																->get();
					else
						$second_department_us_tours = [];
					foreach($second_department_tours as $contract){
						foreach($counterparties as $counter)
							if($contract->id_counterpartie_contract == $counter->id)
								$contract->name_counterpartie_contract = $counter->name;
						$pr_amount_acts = 0;
						$acts = SecondDepartmentAct::select(['number_act','amount_act','date_act'])
													->where('id_second_tour', $contract->tourID)
													->get();
						foreach($acts as $act){
							$pr_amount_acts += $act->amount_act;
							$contract->date_act = $act->date_act;
							$contract->number_act = $act->number_act;
						}
						$contract->amount_acts = $pr_amount_acts;
					}
					foreach($second_department_sb_tours as $contract){
						foreach($counterparties as $counter)
							if($contract->id_counterpartie_contract == $counter->id)
								$contract->name_counterpartie_contract = $counter->name;
						$pr_amount_acts = 0;
						$acts = SecondDepartmentAct::select(['number_act','amount_act','date_act'])
													->where('id_second_sb_tour', $contract->tourID)
													->get();
						foreach($acts as $act){
							$pr_amount_acts += $act->amount_act;
							$contract->date_act = $act->date_act;
							$contract->number_act = $act->number_act;
						}
						$contract->amount_acts = $pr_amount_acts;
					}
					foreach($second_department_us_tours as $contract){
						foreach($counterparties as $counter)
							if($contract->id_counterpartie_contract == $counter->id)
								$contract->name_counterpartie_contract = $counter->name;
						$pr_amount_acts = 0;
						$acts = SecondDepartmentAct::select(['number_act','amount_act','date_act'])
													->where('id_second_us_tour', $contract->tourID)
													->get();
						foreach($acts as $act){
							$pr_amount_acts += $act->amount_act;
							$contract->date_act = $act->date_act;
							$contract->number_act = $act->number_act;
						}
						$contract->amount_acts = $pr_amount_acts;
					}
					return view('department.second.print_report', [
																	'real_name_table'=>'Наряды за год по виду',
																	'year'=>$year_contract,
																	'second_department_tours'=>$second_department_tours,
																	'second_department_sb_tours'=>$second_department_sb_tours,
																	'second_department_us_tours'=>$second_department_us_tours
																]);
					break;
				default:
					break;
			}
		}
	}
	
	public static function print_report_1($view_contract_str, $view_contract_equal, $view_contract, $year_contract_str, $year_contract_equal, $year_contract)
	{
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		$contracts = Contract::select(['contracts.id',
										'id_counterpartie_contract',
										'number_contract',
										'name_work_contract',
										'item_contract',
										'id_goz_contract',
										'goz_works.name_works_goz',
										'id_view_contract', 
										'view_contracts.name_view_contract',
										'reestr_contracts.amount_contract_reestr',
										'reestr_contracts.date_maturity_date_reestr',
										'reestr_contracts.date_maturity_reestr',
										'reestr_contracts.number_counterpartie_contract_reestr',
										DB::raw('CAST(number_pp as UNSIGNED) as cast_number_pp'),
										'reestr_contracts.date_maturity_reestr'])
						->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
						->join('view_contracts', 'reestr_contracts.id_view_contract', '=', 'view_contracts.id')
						->join('goz_works', 'contracts.id_goz_contract', '=', 'goz_works.id')
						->where('is_sip_contract', 1)
						->where('contracts.id_counterpartie_contract','>','-1')
						->where($view_contract_str, $view_contract_equal, $view_contract)
						->where($year_contract_str, $year_contract_equal, $year_contract)
						->where('archive_contract', 0)
						->orderBy('cast_number_pp', 'asc')
						->get();
		foreach($contracts as $contract)
			foreach($counterparties as $counter)
				if($contract->id_counterpartie_contract == $counter->id)
					$contract->name_counterpartie_contract = $counter->name;
		return $contracts;
	}
	
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $second = SecondDepartmentTour::findOrFail($id);
		$id_contract = $second->id_contract;
		$second->delete();
		JournalController::store(Auth::User()->id,'Удалено испытание из второго отдела с id = ' . $id . ' для контракта с id = ' . $id_contract);
		return redirect()->route('department.contract_second.show', $id_contract)->with('success','Успешно удален!');
    }
	
	public function destroy_sb($id)
    {
        $second = SecondDepartmentSbTour::findOrFail($id);
		$id_contract = $second->id_contract;
		$second->delete();
		JournalController::store(Auth::User()->id,'Удалена сборка из второго отдела с id = ' . $id . ' для контракта с id = ' . $id_contract);
		return redirect()->route('department.contract_second.show', $id_contract)->with('success','Успешно удалена!');
    }
	
	public function destroy_us($id)
    {
        $second = SecondDepartmentUsTour::findOrFail($id);
		$id_contract = $second->id_contract;
		$second->delete();
		JournalController::store(Auth::User()->id,'Удалена услуга из второго отдела с id = ' . $id . ' для контракта с id = ' . $id_contract);
		return redirect()->route('department.contract_second.show', $id_contract)->with('success','Успешно удалена!');
    }
}
