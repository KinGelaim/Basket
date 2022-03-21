<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Contract;
use App\ViewWork;
use App\Counterpartie;
use App\State;
use App\ReestrContract;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use App\Resolution;
use App\Document;
use App\Application;
use App\Element;
use App\ViewWorkElement;
use App\Curator;
use App\SecondDepartment;
use App\Checkpoint;
use App\Department;
use App\Protocol;
use App\OudCurator;
use App\ViewContract;
use App\ReestrInvoice;
use App\Invoice;
use App\ReestrDateContract;
use App\ReestrDateMaturity;
use App\ReestrAmount;
use App\ObligationInvoice;
//use App\ReconciliationUser;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$link = '';
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
		$year_str = "contracts.id_counterpartie_contract";
		$year_equal = ">";
		$view_department_str = "contracts.id_counterpartie_contract";
		$view_department_equal = ">";
		$counterpartie_str = "contracts.id_counterpartie_contract";
		$counterpartie_equal = ">";
		$year = '';
		if(isset($_GET['year'])) {
			if(strlen($_GET['year']) > 0) {
				$year = ($_GET['year']);
				$year_str = "contracts.year_contract";
				$year_equal = "=";
				$link .= "&year=" . $_GET['year'];
			}
		}
		/*if(isset($_GET['view'])) {
			$view_work = ($_GET['view']);
			$view_work_str = "view_works.name_view_work";
			$view_work_equal = "=";
			$link .= "&view=" . $_GET['view'];
		} else
			$view_work = '';*/
		//Подразделения
		$view_departments = Department::select()->get();
		$sql_like = '%';
		$view_department = '';
		if(isset($_GET['view'])) {
			if(strlen($_GET['view']) > 0) {
				$view_department = ($_GET['view']);
				$link .= "&view=" . $_GET['view'];
				$sql_like = '%‐' . $view_department . '‐%';
			}
		}
		// ГОЗ, экспорт и тд
		$goz_work_str = "contracts.id_counterpartie_contract";
		$goz_work_equal = ">";
		$goz_work = '';
		if(isset($_GET['goz_work'])) {
			if(strlen($_GET['goz_work']) > 0) {
				$goz_work = $_GET['goz_work'];
				$goz_work_str = "name_works_goz";
				$goz_work_equal = "=";
				$link .= "&goz_work=" . $_GET['goz_work'];
			}
		}
		//Поиск
		if(isset($_GET['search_name'])) {
			$search_name = $_GET['search_name'];
			$link .= "&search_name=" . $_GET['search_name'];
		} else
			$search_name = '';
		if(isset($_GET['search_value'])) {
			$search_value = $_GET['search_value'];
			$link .= "&search_value=" . $_GET['search_value'];
			if($search_name == 'number_contract')
				$search_value = str_replace('-','‐',$search_value);
		} else
			$search_value = '';
		//Контрагенты
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		$sip_counterparties = Counterpartie::select(['*'])->where('is_sip_counterpartie', 1)->orderBy('name', 'asc')->get();
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
		//Контракты
		$paginate_count = 10;
		if (isset($_GET["page"])) {
			$page  = $_GET["page"];
		} else {
			$page=1;
		};
		$start = ($page-1) * $paginate_count;
		//$contracts = Contract::paginate($paginate_count);
		$is_sip_contract = 0;
		if(Route::current()->uri() == 'ekonomic_sip') {
			$is_sip_contract = 1;
		}
		if(isset($_GET['search_value']) && isset($_GET['search_name']) && $search_name != '' && $search_value != ''){
			$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','id_goz_contract','name_works_goz','id_view_work_contract', 'view_works.name_view_work',
											'all_count_contract','concluded_count_contract','amount_concluded_contract','formalization_count_contract',
											'amount_formalization_contract','big_deal_contract','amoun_implementation_contract','comment_implementation_contract',
											'prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract','date_contact','year_contract',
											'name_view_contract','amount_reestr','item_contract',
											'app_outgoing_number_reestr', 'date_entry_into_force_reestr', 'renouncement_contract', DB::raw('CAST(number_pp as UNSIGNED) as cast_number_pp')])
							->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
							->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
							->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', '=', 'view_contracts.id')
							->leftJoin('goz_works', 'contracts.id_goz_contract','goz_works.id')
							/*->where('contracts.number_contract','!=', null)*/
							->where($search_name, 'like', '%' . $search_value . '%')
							->where('is_sip_contract', $is_sip_contract)
							->where('contracts.id_counterpartie_contract','>','-1')
							->where($year_str, $year_equal, $year)
							->where($goz_work_str, $goz_work_equal, $goz_work)
							//->where($view_department_str, $view_department_equal, $view_department)
							->where($counterpartie_str, $counterpartie_equal, $counterpartie)
							->where('contracts.number_contract', 'like', $sql_like)
							->where('archive_contract', 0)
							->orderBy($sort_year, $sort_p)
							->orderBy($sort, $sort_p)
							->offset($start)
							->limit($paginate_count)
							->get();
			$contract_count = Contract::select()->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', '=', 'view_contracts.id')->leftJoin('goz_works', 'contracts.id_goz_contract','goz_works.id')->where('contracts.id_counterpartie_contract','>',-1)->where($year_str, $year_equal, $year)->where($goz_work_str, $goz_work_equal, $goz_work)
												//->where($view_department_str, $view_department_equal, $view_department)
												->where($search_name, 'like', '%' . $search_value . '%')
												->where($counterpartie_str, $counterpartie_equal, $counterpartie)
												->where('is_sip_contract', $is_sip_contract)
												->where('contracts.number_contract', 'like', $sql_like)
												->where('archive_contract', 0)
												->count();
		}else{
			$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','id_goz_contract','name_works_goz','id_view_work_contract', 'view_works.name_view_work',
											'all_count_contract','concluded_count_contract','amount_concluded_contract','formalization_count_contract',
											'amount_formalization_contract','big_deal_contract','amoun_implementation_contract','comment_implementation_contract',
											'prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract','date_contact','year_contract',
											'name_view_contract','amount_reestr','item_contract', 
											'app_outgoing_number_reestr', 'date_entry_into_force_reestr', 'renouncement_contract', DB::raw('CAST(number_pp as UNSIGNED) as cast_number_pp')])
							->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
							->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
							->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', '=', 'view_contracts.id')
							->leftJoin('goz_works', 'contracts.id_goz_contract','goz_works.id')
							/*->where('contracts.number_contract','!=', null)*/
							->where('is_sip_contract', $is_sip_contract)
							->where('contracts.id_counterpartie_contract','>','-1')
							->where($year_str, $year_equal, $year)
							->where($goz_work_str, $goz_work_equal, $goz_work)
							//->where($view_department_str, $view_department_equal, $view_department)
							->where($counterpartie_str, $counterpartie_equal, $counterpartie)
							->where('contracts.number_contract', 'like', $sql_like)
							->where('archive_contract', 0)
							->orderBy($sort_year, $sort_p)
							->orderBy($sort, $sort_p)
							->offset($start)
							->limit($paginate_count)
							->get();
			$contract_count = Contract::select()->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')->leftJoin('goz_works', 'contracts.id_goz_contract','goz_works.id')->where('contracts.id_counterpartie_contract','>',-1)->where($year_str, $year_equal, $year)->where($goz_work_str, $goz_work_equal, $goz_work)
												//->where($view_department_str, $view_department_equal, $view_department)
												->where($counterpartie_str, $counterpartie_equal, $counterpartie)
												->where('is_sip_contract', $is_sip_contract)
												->where('contracts.number_contract', 'like', $sql_like)
												->where('archive_contract', 0)
												->count();
		}
		$prev_page = $page - 1 > 0 ? (int)($page-1) : '';
		$next_page = $page + 1 <= (int)ceil($contract_count/$paginate_count) ? (int)($page+1) : '';
		$years = DB::SELECT('SELECT year_contract FROM contracts GROUP BY year_contract ORDER BY year_contract DESC');
		$view_works = ViewWork::all();
		$view_contracts = ViewContract::select('*')->where('is_sip_view_contract', 1)->get();
		$all_view_contracts = ViewContract::select('*')->get();
		foreach($contracts as $contract)
			foreach($counterparties as $counter)
				if($contract->id_counterpartie_contract == $counter->id)
					$contract->name_counterpartie_contract = $counter->name;
		$departments = Department::select()->orderBy('index_department', 'asc')->get();
		//dump($contracts);
		//Правильные форматы для сумм
		foreach($contracts as $contract)
			if(is_numeric($contract->amount_reestr))
				$contract->amount_reestr = number_format($contract->amount_reestr, 2, '.', ' ');
        return view('department.planekonom.main',['contracts' => $contracts,
													'years' => $years,
													'year' => $year,
													'goz_work' => $goz_work,
													'viewWorks' => $view_works,
													'viewWork' => "",//$view_work,
													'viewContracts' => $view_contracts,
													'all_view_contracts' => $all_view_contracts,
													'viewDepartments' => $view_departments,
													'viewDepartment' => $view_department,
													'search_name' => $search_name,
													'search_value' => $search_value,
													'sip_counterparties' => $sip_counterparties,
													'counterparties' => $counterparties,
													'counterpartie' => $counerpartie_name,
													'is_sip_contract' => $is_sip_contract,
													'departments' => $departments,
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
	
	//Основная страница ПЭО 2
	public function peo()
	{
		$link = '';
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
		$year_str = "contracts.id_counterpartie_contract";
		$year_equal = ">";
		$view_department_str = "contracts.id_counterpartie_contract";
		$view_department_equal = ">";
		$counterpartie_str = "contracts.id_counterpartie_contract";
		$counterpartie_equal = ">";
		//Годы
		$year = '';
		if(isset($_GET['year'])) {
			if(strlen($_GET['year']) > 0) {
				$year = ($_GET['year']);
				$year_str = "contracts.year_contract";
				$year_equal = "=";
				$link .= "&year=" . $_GET['year'];
			}
		}
		//Подразделения
		$view_departments = Department::select()->get();
		$sql_like = '%';
		$view_department = '';
		if(isset($_GET['view'])) {
			if(strlen($_GET['view']) > 0) {
				$view_department = ($_GET['view']);
				$link .= "&view=" . $_GET['view'];
				$sql_like = '%‐' . $view_department . '‐%';
			}
		}
		//Виды работ (договоров)
		$view_work = '';
		$view_work_str = "contracts.id_counterpartie_contract";
		$view_work_equal = ">";
		if(isset($_GET['view_work'])) {
			if(strlen($_GET['view_work']) > 0) {
				$view_work = ($_GET['view_work']);
				$view_work_str = "reestr_contracts.id_view_contract";
				$view_work_equal = "=";
				$link .= "&view_work=" . $_GET['view_work'];
			}
		}
		//Исполнители
		$curator = '';
		$curators = Curator::all();
		$curator_str = "contracts.id_counterpartie_contract";
		$curator_equal = ">";
		if(isset($_GET['curator'])) {
			if(strlen($_GET['curator']) > 0) {
				$curator = ($_GET['curator']);
				$curator_str = "reestr_contracts.executor_contract_reestr";
				$curator_equal = "=";
				$link .= "&curator=" . $_GET['curator'];
			}
		}
		//Поиск
		if(isset($_GET['search_name'])) {
			$search_name = $_GET['search_name'];
			$link .= "&search_name=" . $_GET['search_name'];
		} else
			$search_name = '';
		if(isset($_GET['search_value'])) {
			$search_value = $_GET['search_value'];
			$link .= "&search_value=" . $_GET['search_value'];
			if($search_name == 'number_contract')
				$search_value = str_replace('-','‐',$search_value);
		} else
			$search_value = '';
		//Контрагенты
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		$sip_counterparties = Counterpartie::select(['*'])->where('is_sip_counterpartie', 1)->orderBy('name', 'asc')->get();
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
		//Контракты
		$paginate_count = 10;
		if (isset($_GET["page"])) {
			$page  = $_GET["page"];
		} else {
			$page=1;
		};
		$start = ($page-1) * $paginate_count;
		//$contracts = Contract::paginate($paginate_count);
		if(isset($_GET['search_value']) && isset($_GET['search_name']) && $search_name != '' && $search_value != ''){
			$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','id_goz_contract','id_view_work_contract', 'view_works.name_view_work',
											'all_count_contract','concluded_count_contract','amount_concluded_contract','formalization_count_contract',
											'amount_formalization_contract','big_deal_contract','amoun_implementation_contract','comment_implementation_contract',
											'prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract','date_contact','year_contract',
											'name_view_contract','amount_reestr','item_contract', 
											'app_outgoing_number_reestr', 'date_entry_into_force_reestr', 'renouncement_contract', 'executor_contract_reestr', 
											DB::raw('CAST(number_pp as UNSIGNED) as cast_number_pp')])
							->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
							->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
							->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', '=', 'view_contracts.id')
							/*->where('contracts.number_contract','!=', null)*/
							->where($search_name, 'like', '%' . $search_value . '%')
							->where('is_sip_contract', 1)
							->where('contracts.id_counterpartie_contract','>','-1')
							->where($year_str, $year_equal, $year)
							//->where($view_department_str, $view_department_equal, $view_department)
							->where($counterpartie_str, $counterpartie_equal, $counterpartie)
							->where($curator_str, $curator_equal, $curator)
							->where($view_work_str, $view_work_equal, $view_work)
							->where('contracts.number_contract', 'like', $sql_like)
							->where('archive_contract', 0)
							->orderBy($sort_year, $sort_p)
							->orderBy($sort, $sort_p)
							->offset($start)
							->limit($paginate_count)
							->get();
			$contract_count = Contract::select()->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
												->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
												->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', '=', 'view_contracts.id')
												->where('contracts.id_counterpartie_contract','>',-1)
												->where($year_str, $year_equal, $year)
												//->where($view_department_str, $view_department_equal, $view_department)
												->where($search_name, 'like', '%' . $search_value . '%')
												->where($counterpartie_str, $counterpartie_equal, $counterpartie)
												->where($curator_str, $curator_equal, $curator)
												->where($view_work_str, $view_work_equal, $view_work)
												->where('is_sip_contract', 1)
												->where('contracts.number_contract', 'like', $sql_like)
												->where('archive_contract', 0)
												->count();
			
		}else{
			if($view_department != '')
			{
				$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','id_goz_contract','id_view_work_contract', 'view_works.name_view_work',
												'all_count_contract','concluded_count_contract','amount_concluded_contract','formalization_count_contract',
												'amount_formalization_contract','big_deal_contract','amoun_implementation_contract','comment_implementation_contract',
												'prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract','date_contact','year_contract',
												'name_view_contract','amount_reestr','item_contract',
												'app_outgoing_number_reestr', 'date_entry_into_force_reestr', 'renouncement_contract', 'executor_contract_reestr',
												DB::raw('CAST(number_pp as UNSIGNED) as cast_number_pp')])
								->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
								->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
								->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', '=', 'view_contracts.id')
								/*->where('contracts.number_contract','!=', null)*/
								->where('is_sip_contract', 1)
								->where('contracts.id_counterpartie_contract','>','-1')
								->where($year_str, $year_equal, $year)
								//->where($view_department_str, $view_department_equal, $view_department)
								->where($counterpartie_str, $counterpartie_equal, $counterpartie)
								->where($curator_str, $curator_equal, $curator)
								->where($view_work_str, $view_work_equal, $view_work)
								->where('contracts.number_contract', 'like', $sql_like)
								->where('archive_contract', 0)
								->orderBy($sort_year, $sort_p)
								->orderBy($sort, $sort_p)
								->offset($start)
								->limit($paginate_count)
								->get();
				$contract_count = Contract::select()->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
													->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
													->where('contracts.id_counterpartie_contract','>',-1)->where($year_str, $year_equal, $year)
													//->where($view_department_str, $view_department_equal, $view_department)
													->where($counterpartie_str, $counterpartie_equal, $counterpartie)
													->where($curator_str, $curator_equal, $curator)
													->where($view_work_str, $view_work_equal, $view_work)
													->where('is_sip_contract', 1)
													->where('contracts.number_contract', 'like', $sql_like)
													->where('archive_contract', 0)
													->count();
			}else{
				$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','id_goz_contract','id_view_work_contract', 'view_works.name_view_work',
												'all_count_contract','concluded_count_contract','amount_concluded_contract','formalization_count_contract',
												'amount_formalization_contract','big_deal_contract','amoun_implementation_contract','comment_implementation_contract',
												'prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract','date_contact','year_contract',
												'name_view_contract','amount_reestr','item_contract',
												'app_outgoing_number_reestr', 'date_entry_into_force_reestr', 'renouncement_contract', 'executor_contract_reestr',
												DB::raw('CAST(number_pp as UNSIGNED) as cast_number_pp')])
								->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
								->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
								->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', '=', 'view_contracts.id')
								/*->where('contracts.number_contract','!=', null)*/
								->where('is_sip_contract', 1)
								->where('contracts.id_counterpartie_contract','>','-1')
								->where($year_str, $year_equal, $year)
								//->where($view_department_str, $view_department_equal, $view_department)
								->where($counterpartie_str, $counterpartie_equal, $counterpartie)
								->where($curator_str, $curator_equal, $curator)
								->where($view_work_str, $view_work_equal, $view_work)
								//->where('contracts.number_contract', 'like', $sql_like)
								->where('archive_contract', 0)
								->orderBy($sort_year, $sort_p)
								->orderBy($sort, $sort_p)
								->offset($start)
								->limit($paginate_count)
								->get();
				$contract_count = Contract::select()->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
													->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
													->where('contracts.id_counterpartie_contract','>',-1)->where($year_str, $year_equal, $year)
													//->where($view_department_str, $view_department_equal, $view_department)
													->where($counterpartie_str, $counterpartie_equal, $counterpartie)
													->where($curator_str, $curator_equal, $curator)
													->where($view_work_str, $view_work_equal, $view_work)
													->where('is_sip_contract', 1)
													//->where('contracts.number_contract', 'like', $sql_like)
													->where('archive_contract', 0)
													->count();

			}
		}
		$prev_page = $page - 1 > 0 ? (int)($page-1) : '';
		$next_page = $page + 1 <= (int)ceil($contract_count/$paginate_count) ? (int)($page+1) : '';
		$years = DB::SELECT('SELECT year_contract FROM contracts GROUP BY year_contract ORDER BY year_contract DESC');
		$view_works = ViewWork::all();
		$view_contracts = ViewContract::select('*')->where('is_sip_view_contract', 1)->get();
		$all_view_contracts = ViewContract::select('*')->get();
		//Кураторы
		$curators_sip = Curator::all();
		foreach($contracts as $contract){
			foreach($counterparties as $counter)
				if($contract->id_counterpartie_contract == $counter->id)
					$contract->name_counterpartie_contract = $counter->name;
			foreach($curators_sip as $in_curators)
				if($contract->executor_contract_reestr == $in_curators->id)
					$contract->executor_contract_reestr = $in_curators->FIO;
		}
		$departments = Department::select()->orderBy('index_department', 'asc')->get();
		//dump($contracts);
		//Правильные форматы для сумм
		foreach($contracts as $contract)
			if(is_numeric($contract->amount_reestr))
				$contract->amount_reestr = number_format($contract->amount_reestr, 2, '.', ' ');
		//dd($view_work);
        return view('department.peo.main',['contracts' => $contracts,
													'years' => $years,
													'year' => $year,
													'viewWorks' => $view_works,
													'view_work' => $view_work,
													'viewContracts' => $view_contracts,
													'all_view_contracts' => $all_view_contracts,
													'viewDepartments' => $view_departments,
													'viewDepartment' => $view_department,
													'curators' => $curators,
													'curator' => $curator,
													'search_name' => $search_name,
													'search_value' => $search_value,
													'sip_counterparties' => $sip_counterparties,
													'counterparties' => $counterparties,
													'counterpartie' => $counerpartie_name,
													'departments' => $departments,
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
	
	public function new_peo_contract()
	{
		$contract = null;
		$view_contracts = ViewContract::select()->where('is_sip_view_contract', 1)->orderBy('name_view_contract','asc')->get();
		$counterparties = Counterpartie::select(['*'])->where('is_sip_counterpartie', 1)->orderBy('name', 'asc')->get();
		$resolutions = [];
		$reestr = new ReestrContract();
		$curators_sip = Curator::all();
		$units = DB::SELECT('SELECT * FROM units');
        return view('department.peo.new_contract', ['reestr'=>$reestr,
															'contract'=>$contract, 
															'viewContracts'=>$view_contracts, 
															'counterparties'=>$counterparties, 
															'resolutions'=>$resolutions, 
															'curators_sip'=>$curators_sip,
															'units'=>$units
															]);
	}
	
	public function store_peo_contract(Request $request)
	{
		$val = Validator::make($request->all(),[
			'id_view_contract' => 'required',
			'id_counterpartie_contract' => 'required',
			'item_contract' => 'required'
		])->validate();
		$last_number_application = Application::select()->withTrashed()->max('number_application');
		$new_application = new Application();
		$new_application->fill([
						'id_counterpartie_application' => $request['id_counterpartie_contract'],
						'number_application' => $last_number_application+1,
						'date_application' => date('Y-m-d', time())
		]);
		$new_application->save();
		$new_document = new Document();
		$new_document->fill([
						'id_application_document' => $new_application->id,
						'date_document' => date('Y-m-d', time())
		]);
		$new_document->save();
		$var_is_goz = 4;
		if($request['goz_contract'])
			$var_is_goz = 1;
		else if($request['export_contract'])
			$var_is_goz = 2;
		else if($request['interfactory_contract'])
			$var_is_goz = 3;
		$contract = new Contract();
		$contract->fill([
						'year_contract' => $request['year_contract'],
						'id_goz_contract' => $var_is_goz,
						'id_counterpartie_contract' => $request['id_counterpartie_contract'],
						'name_work_contract' => $request['item_contract'],
						'item_contract' => $request['item_contract'],
						'renouncement_contract' => $request['renouncement_contract'] ? 1 : 0,
						'archive_contract' => $request['archive_contract'] ? 1 : 0,
						'document_success_renouncement_reestr' => $request['document_success_renouncement_reestr'],
						'number_aftair_renouncement_reestr' => $request['number_aftair_renouncement_reestr'],
						'is_sip_contract' => 1,
						'id_document_contract' => $new_document->id
		]);
		$all_dirty = JournalController::getMyChanges($contract);
		$contract->save();
		$reestr = new ReestrContract();
		$reestr->fill($request->all());
		if($request['amount_contract_reestr'])
			$reestr->amount_contract_reestr = str_replace(' ','',$reestr->amount_contract_reestr);
		if($request['amount_bank_reestr'])
			$reestr->amount_bank_reestr = str_replace(' ','',$reestr->amount_bank_reestr);
		if($request['amount_reestr'])
			$reestr->amount_reestr = str_replace(' ','',$reestr->amount_reestr);
		if($request['nmcd_reestr'])
			$reestr->nmcd_reestr = str_replace(' ','',$reestr->nmcd_reestr);
		if($request['economy_reestr'])
			$reestr->economy_reestr = str_replace(' ','',$reestr->economy_reestr);
		if($request['prepayment_reestr'])
			$reestr->prepayment_reestr = str_replace(' ','',$reestr->prepayment_reestr);
		if($request['amount_year_reestr'])
			$reestr->amount_year_reestr = str_replace(' ','',$reestr->amount_year_reestr);
		if($request['amount_contract_year_reestr'])
			$reestr->amount_contract_year_reestr = str_replace(' ','',$reestr->amount_contract_year_reestr);
		$reestr->fill([
						'id_contract_reestr' => $contract->id,
						'marketing_goz_reestr' => $request['goz_contract'] ? 1 : 0,
						'export_reestr' => $request['export_contract'] ? 1 : 0,
						'interfactory_reestr' => $request['interfactory_contract'] ? 1 : 0,
						'vat_reestr' => $request['vat_reestr'],
						'big_deal_reestr' => $request['big_deal_reestr'] ? 1 : 0
		]);
		$all_dirty = JournalController::getMyChanges($reestr, $all_dirty);
		$reestr->save();
		// Создаём срок действия Д/К
		if ($request['date_contract_reestr'] || $request['date_e_contract_reestr'])
		{
			$date_contract = new ReestrDateContract();
			$date_contract->fill([
				'id_contract' => $contract->id,
				'name_date_contract' => 'Д/К',
				'term_date_contract' => $request['date_contract_reestr'] ? $request['date_contract_reestr'] : '',
				'end_date_contract' => $request['date_e_contract_reestr']
			]);
			$date_contract->save();
		}
		// Создаём срок исполнения обязательств
		if ($request['date_maturity_reestr'] || $request['date_e_maturity_reestr'])
		{
			$date_maturity = new ReestrDateMaturity();
			$date_maturity->fill([
				'id_contract' => $contract->id,
				'name_date_maturity' => 'Д/К',
				'term_date_maturity' => $request['date_maturity_reestr'],
				'end_date_maturity' => $request['date_e_maturity_reestr']
			]);
			$date_maturity->save();
		}
		// Создаём сумму
		if ($request['amount_reestr'])
		{
			$amount = new ReestrAmount();
			$amount->fill([
				'id_contract' => $contract->id,
				'name_amount' => 'Д/К',
				'value_amount' => $request['amount_reestr'],
				'unit_amount' => $request['unit_reestr'],
				'vat_amount' => $request['vat_reestr'] ? 1 : 0,
				'approximate_amount' => $request['approximate_amount_reestr'] ? 1 : 0,
				'fixed_amount' => $request['fixed_amount_reestr'] ? 1 : 0
			]);
			$amount->save();
		}
		JournalController::store(Auth::User()->id,'Создание ПЭО контракта с id = ' . $contract->id . '~' . json_encode($all_dirty));
		if($request->file('new_file_resolution'))
			ResolutionController::store_resol_new_app($request, $contract->id);
		return redirect()->back()->with('success', 'Контракт сохранен!');
	}
	
	public function show_peo_contract($id_contract)
	{
		$contract = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','item_contract','is_sip_contract','id_goz_contract','goz_works.name_works_goz','id_view_work_contract', 'view_works.name_view_work',
										'all_count_contract','concluded_count_contract','amount_concluded_contract','formalization_count_contract',
										'amount_formalization_contract','big_deal_contract','amoun_implementation_contract','comment_implementation_contract',
										'prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract','renouncement_contract','document_success_renouncement_reestr','number_aftair_renouncement_reestr',
										'archive_contract','date_contact','year_contract', 'number_pp'])
							->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
							->join('goz_works', 'contracts.id_goz_contract', '=', 'goz_works.id')
							->where('contracts.id',$id_contract)->get()[0];
		$view_contracts = ViewContract::select()->where('is_sip_view_contract', 1)->orderBy('name_view_contract','asc')->get();
		$counterparties = Counterpartie::select(['*'])->where('is_sip_counterpartie', 1)->orderBy('name', 'asc')->get();
		foreach($counterparties as $counter)
			if($contract->id_counterpartie_contract == $counter->id){
				$contract->full_name_counterpartie_contract = $counter->name_full;
				$contract->name_counterpartie_contract = $counter->name;
				$contract->inn_counterpartie_contract = $counter->inn;
				break;
			}
		$resolutions = Resolution::select(['*'])->where('id_contract_resolution', $contract->id)->orderBy('id','desc')->get();
		$reestr = ReestrContract::firstOrNew(['id_contract_reestr'=>$id_contract]);
		if($reestr->id_view_contract != null)
			$reestr->id_view_contract = ViewContract::select('id')->where('id', $reestr->id_view_contract)->first()->id;
		if(is_numeric($reestr->amount_contract_reestr))
			$reestr->amount_contract_reestr = number_format($reestr->amount_contract_reestr, 2, '.', ' ');
		if(is_numeric($reestr->amount_bank_reestr))
			$reestr->amount_bank_reestr = number_format($reestr->amount_bank_reestr, 2, '.', ' ');
		if(is_numeric($reestr->amount_reestr))
			$reestr->amount_reestr = number_format($reestr->amount_reestr, 2, '.', ' ');
		if(is_numeric($reestr->nmcd_reestr))
			$reestr->nmcd_reestr = number_format($reestr->nmcd_reestr, 2, '.', ' ');
		if(is_numeric($reestr->economy_reestr))
			$reestr->economy_reestr = number_format($reestr->economy_reestr, 2, '.', ' ');
		if(is_numeric($reestr->amount_year_reestr))
			$reestr->amount_year_reestr = number_format($reestr->amount_year_reestr, 2, '.', ' ');
		if(is_numeric($reestr->amount_contract_year_reestr))
			$reestr->amount_contract_year_reestr = number_format($reestr->amount_contract_year_reestr, 2, '.', ' ');
		if(is_numeric($reestr->prepayment_reestr))
			$reestr->prepayment_reestr = number_format($reestr->prepayment_reestr, 2, '.', ' ');
		$curators_sip = Curator::all();
		$curators = OudCurator::all();
		//$departments = Department::all();
		$departments = Department::select()->orderBy('index_department', 'asc')->get();
		//Счета
		$scores = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
											->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
											->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
											->where('id_contract', $id_contract)
											->where('name', 'Счет на оплату')
											->orderBy('invoices.number_invoice', 'asc')
											->get();
		//Счета на аванс
		$prepayments = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
											->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
											->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
											->where('id_contract', $id_contract)
											->where('name', 'Аванс')
											->orderBy('invoices.number_invoice', 'asc')
											->get();
		$invoices = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
											->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
											->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
											->where('id_contract', $id_contract)
											->where('name', 'Счет-фактура')
											->orderBy('invoices.number_invoice', 'asc')
											->get();
		$payments = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
											->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
											->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
											->where('id_contract', $id_contract)
											->where('name', 'Оплата')
											->orderBy('invoices.number_invoice', 'asc')
											->get();
		$returns = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
											->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
											->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
											->where('id_contract', $id_contract)
											->where('name', 'Возврат')
											->orderBy('invoices.number_invoice', 'asc')
											->get();
		$amount_invoice_contract_reestr = 0;
		$year_amount_invoice_contract_reestr = 0;
		foreach($invoices as $in_invoice){
			$amount_invoice_contract_reestr += $in_invoice->amount_p_invoice;
			if(date('Y', strtotime($in_invoice->date_invoice)) == date('Y', time()))
				$year_amount_invoice_contract_reestr += $in_invoice->amount_p_invoice;
		}
		if(is_numeric($amount_invoice_contract_reestr))
			$amount_invoice_contract_reestr = number_format($amount_invoice_contract_reestr, 2, '.', ' ');
		if(is_numeric($year_amount_invoice_contract_reestr))
			$year_amount_invoice_contract_reestr = number_format($year_amount_invoice_contract_reestr, 2, '.', ' ');
		$reestr->amount_invoice_contract_reestr = $amount_invoice_contract_reestr;
		$reestr->year_amount_invoice_contract_reestr = $year_amount_invoice_contract_reestr;
		//Дебет и кредит
		$amount_scores = 0;
		$amount_prepayments = 0;
		$amount_invoices = 0;
		$amount_payments = 0;
		$amount_returns = 0;
		$invoices_all = Invoice::select(['invoices.amount_p_invoice', 'name_invoices.name'])
									->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
									->where('id_contract', $id_contract)
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
		$debet = ($amount_invoices - $amount_payments + $amount_returns) > 0 ? $amount_invoices - $amount_payments + $amount_returns : 0;
		$kredit = ($amount_payments - $amount_invoices - $amount_returns) > 0 ? $amount_payments - $amount_invoices - $amount_returns : 0;
		//История выполнения работ
		$work_states = State::select(['states.id','name_state','comment_state','date_state','users.surname','users.name','users.patronymic'])->join('users','users.id','states.id_user')->where('id_contract', $id_contract)->where('is_work_state', 1)->get();
        //Типы резолюций
		$type_resolutions = DB::SELECT('SELECT * FROM type_resolutions');
		// Срок исполнения обязательств Д/К
		$all_reest_date_maturity = ReestrDateMaturity::select()->where('id_contract', $id_contract)->get();
		// Суммы по Д/К
		$units = DB::SELECT('SELECT * FROM units');
		$all_reestr_amount = ReestrAmount::select(['*','reestr_amounts.id'])->leftJoin('units', 'unit_amount', 'units.id')->where('id_contract', $id_contract)->get();
		return view('department.peo.show_contract', ['reestr'=>$reestr,
															'contract'=>$contract, 
															'viewContracts'=>$view_contracts, 
															'counterparties'=>$counterparties, 
															'resolutions'=>$resolutions,
															'type_resolutions'=>$type_resolutions,
															'curators_sip'=>$curators_sip,
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
															'debet'=>$debet,
															'kredit'=>$kredit,
															'work_states'=>$work_states,
															'all_reest_date_maturity'=>$all_reest_date_maturity,
															'units'=>$units,
															'all_reestr_amount'=>$all_reestr_amount
															]);
	}
	
	public function update_peo_contract(Request $request, $id)
	{
		$contract = Contract::findOrFail($id);
		$val = Validator::make($request->all(),[
			'id_view_contract' => 'required',
			'id_counterpartie_contract' => 'required',
			'item_contract' => 'required'
		])->validate();
		$var_is_goz = 4;
		if($request['goz_contract'])
			$var_is_goz = 1;
		else if($request['export_contract'])
			$var_is_goz = 2;
		else if($request['interfactory_contract'])
			$var_is_goz = 3;
		$contract->fill([
						'year_contract' => $request['year_contract'],
						'id_goz_contract' => $var_is_goz,
						'id_counterpartie_contract' => $request['id_counterpartie_contract'],
						'item_contract' => $request['item_contract'],
						'renouncement_contract' => $request['renouncement_contract'] ? 1 : 0,
						'archive_contract' => $request['archive_contract'] ? 1 : 0
		]);
		$all_dirty = JournalController::getMyChanges($contract);
		$contract->save();
		$reestr = ReestrContract::firstOrNew(['id_contract_reestr'=>$id]);
		$reestr->fill($request->all());
		if($request['amount_contract_reestr'])
			$reestr->amount_contract_reestr = str_replace(' ','',$reestr->amount_contract_reestr);
		if($request['amount_bank_reestr'])
			$reestr->amount_bank_reestr = str_replace(' ','',$reestr->amount_bank_reestr);
		if($request['amount_reestr'])
			$reestr->amount_reestr = str_replace(' ','',$reestr->amount_reestr);
		if($request['nmcd_reestr'])
			$reestr->nmcd_reestr = str_replace(' ','',$reestr->nmcd_reestr);
		if($request['economy_reestr'])
			$reestr->economy_reestr = str_replace(' ','',$reestr->economy_reestr);
		if($request['prepayment_reestr'])
			$reestr->prepayment_reestr = str_replace(' ','',$reestr->prepayment_reestr);
		if($request['amount_year_reestr'])
			$reestr->amount_year_reestr = str_replace(' ','',$reestr->amount_year_reestr);
		if($request['amount_contract_year_reestr'])
			$reestr->amount_contract_year_reestr = str_replace(' ','',$reestr->amount_contract_year_reestr);
		$reestr->fill([
						'marketing_goz_reestr' => $request['goz_contract'] ? 1 : 0,
						'export_reestr' => $request['export_contract'] ? 1 : 0,
						'interfactory_reestr' => $request['interfactory_contract'] ? 1 : 0,
						'vat_reestr' => $request['vat_reestr'],
						'big_deal_reestr' => $request['big_deal_reestr'] ? 1 : 0						
		]);
		$all_dirty = JournalController::getMyChanges($reestr, $all_dirty);
		$reestr->save();
		JournalController::store(Auth::User()->id,'Обновление ПЭО контракта с id = ' . $contract->id . '~' . json_encode($all_dirty));
		if($request->file('new_file_resolution'))
			ResolutionController::store_resol_new_app($request, $contract->id);
		return redirect()->back()->with('success', 'Контракт сохранен!');
	}
	
	public function show_additional_documents($id_contract)
	{	
		$contract = Contract::select(['contracts.id','number_contract','item_contract','app_outgoing_number_reestr','date_maturity_reestr','amount_reestr','amount_year_reestr'])
							->leftJoin('reestr_contracts', 'contracts.id','reestr_contracts.id_contract_reestr')
							->where('contracts.id',$id_contract)->get()[0];
		$resolutions = Resolution::select(['*'])->where('id_contract_resolution', $contract->id)->orderBy('resolutions.id','desc')->get();
		if(is_numeric($contract->amount_reestr))
			$contract->amount_reestr = number_format($contract->amount_reestr, 2, '.', '&nbsp;');
		if(is_numeric($contract->amount_year_reestr))
			$contract->amount_year_reestr = number_format($contract->amount_year_reestr, 2, '.', '&nbsp;');
		$additional_documents = Protocol::select()->where('id_contract', $id_contract)->orderBy('position_additional_document')->orderBy('id')->get();
		foreach($additional_documents as $additional_document)
		{
			$resolutions_add = Resolution::select(['*'])->where('id_protocol_resolution', $additional_document->id)->where('deleted_at', null)->orderBy('resolutions.id','desc')->get();
			foreach($resolutions_add as $resol)
				$resol->href_delete_ajax = route('resolution_additional_document_delete_ajax', $resol->id);
			$additional_document->resolutions = $resolutions_add;
			$states = State::select(['states.id','name_state','comment_state','date_state','users.name','users.surname','users.patronymic'])->leftJoin('users','users.id','states.id_user')->where('id_protocol', $additional_document->id)->get();
			$additional_document->states = $states;
		}
		$states = [];
		$states = State::select(['states.id','name_state','comment_state','date_state','users.name','users.surname','users.patronymic'])->leftJoin('users','users.id','states.id_user')->where('id_contract', $id_contract)->where('is_work_state', null)->get();
		$type_resolutions = DB::SELECT('SELECT * FROM type_resolutions');		
		return view('department.peo.additional_documents', ['id_contract'=>$id_contract, 'resolutions'=>$resolutions, 'additional_documents'=>$additional_documents, 'type_resolutions'=>$type_resolutions, 'contract'=>$contract, 'states'=>$states]);
	}
	
	public function update_position_additional_documents(Request $request, $id_contract)
	{
		foreach($request['additional_document_position'] as $key=>$value)
		{
			$additional_document = Protocol::select()->where('id_contract', $id_contract)->where('id', $key)->first();
			$additional_document->position_additional_document = $value;
			$additional_document->save();
		}
		JournalController::store(Auth::User()->id, 'Изменён порядок дог. мат. для контракта с id=' . $id_contract);
		return redirect()->back()->with(['success'=>'Порядок изменён!']);
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($number_document)
    {
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		$view_works = ViewWork::all();
		$view_contracts = ViewContract::select('*')->where('is_sip_view_contract', 1)->get();
		$elements = Element::all();
		$view_work_elements = ViewWorkElement::all();
		$curators = Curator::all();
		$application = Application::first()->where('number_application',$number_document)->get()[0];
		$curator = Counterpartie::select(['curators.id', 'curators.FIO'])->join('curators', 'contr.curator', 'curators.id')->where('contr.id', $application->id_counterpartie_application)->get();
		return view('department.planekonom.contract_new', ['number_document'=>$number_document,
														'id_counterpartie'=>$application->id_counterpartie_application,
														'contract' => [], 
														'viewWorks'=>$view_works,
														'viewContracts'=>$view_contracts,
														'counterparties'=>$counterparties,
														'elements'=>$elements,
														'view_work_elements'=>$view_work_elements,
														'curators'=>$curators,
														'curator'=>$curator]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $number_document)
    {
		//dump($request->all());
        $val = Validator::make($request->all(),[
			'id_counterpartie_contract' => 'required',
			/*'number_contract' => 'required|unique:contracts',*/
			'name_work_contract' => 'required',
			'id_view_contract' => 'required',
			'year_contract' => 'required'
		])->validate();
		$application = Application::first()->where('number_application',$number_document)->get()[0];
		$document = Document::select(['id'])->where('id_application_document',$application->id)->first();
		//dump($document);
		$val_goz_contract = $request['goz_contract'] ? 1 : ($request['export_contract'] ? 2 : 3);
		$contract = new Contract();
		$contract->fill(['id_document_contract'=>$document->id,
					'id_counterpartie_contract' => $request['id_counterpartie_contract'],
					/*'number_contract' => $request['number_contract'],*/
					'name_work_contract' => $request['name_work_contract'],
					'id_goz_contract' => $val_goz_contract,
					//'id_view_work_contract' => $request['id_view_work_contract'],
					'year_contract' => $request['year_contract'],
					'is_sip_contract' => 1
		]);
		$all_dirty = JournalController::getMyChanges($contract);
		$contract->save();
		$reestr = new ReestrContract();
		$reestr->fill([
					'id_contract_reestr' => $contract->id,
					'id_view_contract' => $request['id_view_contract'],
					'amount_reestr' => $request['amount'],
					'fix_amount_contract_reestr' => $request['fix_amount'] ? 1 : 0,
					'executor_reestr' => $request['select_curator'],
					'date_maturity_date_reestr' => $request['date_test_date'],
					'date_maturity_reestr' => $request['date_test'] ? $request['date_textarea'] : null,
					'marketing_goz_reestr' => $request['goz_contract'] ? 1 : 0,
					'export_reestr' => $request['export_contract'] ? 1 : 0,
					'interfactory_reestr' => $request['other_contract'] ? 1 : 0
		]);
		if($request['amount_reestr'])
			$reestr->amount_reestr = str_replace(' ','',$reestr->amount_reestr);
		$all_dirty = JournalController::getMyChanges($reestr, $all_dirty);
		$reestr->save();
		if($request['name_elements'])
			foreach($request['name_elements'] as $key=>$value)
			{
				if($value != null)
				{
					$isp = new SecondDepartment();
					$isp->fill([
								'id_contract' => $contract->id,
								'id_element' => $value,
								'id_view_work_elements' => $request['name_view_work'][$key],
								'count_isp' => $request['count_elements'][$key],
								'year' => $request['year_contract']
					]);
					$isp->save();
				}
			}
		if($request['checkpoint_date'])
			foreach($request['checkpoint_date'] as $key=>$value)
				if($value != null && $request['checkpoint_comment'][$key] != null)
				{
					$checkpoint = new Checkpoint();
					$checkpoint->fill([
						'id_contract' => $contract->id,
						'date_checkpoint' => $value,
						'message_checkpoint' => $request['checkpoint_comment'][$key]
					]);
					$checkpoint->save();
					//dump($checkpoint);
				}
		//dd($contract);
		JournalController::store(Auth::User()->id,'Добавил контракт с id = ' . $contract->id . '~' . json_encode($all_dirty));
        return redirect()->route('department.reconciliation.show', $contract->id);
    }
	
	public function new_state(Request $request, $id)
	{
		//dump($id);
		//dd($request->all());
		$val = Validator::make($request->all(),[
			'new_name_state' => 'required',
			'date_state' => 'required|date',
		])->validate();
		$state = new State();
		$state->fill(['id_contract' => $id,
					'is_work_state' => $request['is_work_state'],
					'id_user' => Auth::User()->id,
					'name_state' => $request['new_name_state'],
					'comment_state' => $request['comment_state'],
					'date_state' => $request['date_state'],
		]);
		$state->save();
		JournalController::store(Auth::User()->id,'Добавил новое состояние для контракста с id = ' . $id);
		return redirect()->back();
	}
	
	public function new_additional_document_state(Request $request, $id_protocol)
	{
		$val = Validator::make($request->all(),[
			'new_name_state' => 'required',
			'date_state' => 'required|date',
		])->validate();
		$state = new State();
		$state->fill(['id_protocol' => $id_protocol,
					'id_user' => Auth::User()->id,
					'name_state' => $request['new_name_state'],
					'comment_state' => $request['comment_state'],
					'date_state' => $request['date_state'],
		]);
		$state->save();
		JournalController::store(Auth::User()->id,'Добавил новое состояние для протокола с id = ' . $id_protocol);
		return redirect()->back();
	}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_peo($id)
    {
		$contract = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','id_goz_contract','goz_works.name_works_goz','id_view_work_contract', 'view_works.name_view_work',
										'all_count_contract','concluded_count_contract','amount_concluded_contract','formalization_count_contract',
										'amount_formalization_contract','big_deal_contract','amoun_implementation_contract','comment_implementation_contract',
										'prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract','date_contact','year_contract'])
							->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
							->join('goz_works', 'contracts.id_goz_contract', '=', 'goz_works.id')
							->where('contracts.id',$id)->get()[0];
		$view_works = ViewWork::all();
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		foreach($counterparties as $counter)
			if($contract->id_counterpartie_contract == $counter->id)
				$contract->name_counterpartie_contract = $counter->name;
		$states = State::select(['*'])->where('id_contract', $id)->where('is_work_state', null)->get();
		if(count($states) > 0)
			$state = $states[count($states)-1];
		else
			$state = [];
		$resolutions = Resolution::select(['*'])->where('id_contract_resolution', $contract->id)->get();
		//dump($contract);
		//dump($states);
		//dump($resolutions);
        return view('department.planekonom.contract_peo', ['contract'=>$contract, 
															'viewWorks'=>$view_works, 
															'counterparties'=>$counterparties, 
															'states'=>$states,
															'resolutions'=>$resolutions
														]);
    }

	public function show_reestr($id)
    {
		$contract = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','is_sip_contract','id_goz_contract','goz_works.name_works_goz','id_view_work_contract', 'view_works.name_view_work',
										'all_count_contract','concluded_count_contract','amount_concluded_contract','formalization_count_contract',
										'amount_formalization_contract','big_deal_contract','amoun_implementation_contract','comment_implementation_contract',
										'prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract','renouncement_contract','date_renouncement_contract',
										'archive_contract','date_contact','year_contract'])
							->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
							->join('goz_works', 'contracts.id_goz_contract', '=', 'goz_works.id')
							->where('contracts.id',$id)->get()[0];
		$view_works = ViewWork::all();
		$counterparties = Counterpartie::select(['*'])->orderBy('name_full', 'asc')->get();
		foreach($counterparties as $counter)
			if($contract->id_counterpartie_contract == $counter->id)
				$contract->name_counterpartie_contract = $counter->name;
		$states = State::select(['*'])->where('id_contract', $id)->get();
		if(count($states) > 0)
			$state = $states[count($states)-1];
		else
			$state = [];
		$resolutions = Resolution::select(['*'])->where('id_contract_resolution', $contract->id)->get();
		$units = DB::SELECT('SELECT * FROM units');
		$type_documents = DB::SELECT('SELECT * FROM type_documents');
		$selection_suppliers = DB::SELECT('SELECT * FROM selection_suppliers');
		$bases = DB::SELECT('SELECT * FROM bases');
		$reestr = ReestrContract::firstOrNew(['id_contract_reestr'=>$id]);
		$curators = Curator::all();
        return view('department.planekonom.contract_reestr', ['reestr'=>$reestr,
															'contract'=>$contract, 
															'viewWorks'=>$view_works, 
															'counterparties'=>$counterparties, 
															'states'=>$states, 
															'resolutions'=>$resolutions, 
															'units'=>$units,
															'type_documents'=>$type_documents,
															'selection_suppliers'=>$selection_suppliers,
															'bases'=>$bases,
															'curators'=>$curators
															]);
    }
	
	public function show_new_reestr($id)
	{
		$isFixTime = false;
		if(isset($_GET['time']))
			$isFixTime = true;
		if($isFixTime)
			$time_start_1 = microtime(1);
		$contract = Contract::select(['contracts.id','id_new_application_contract','id_counterpartie_contract','number_contract','name_work_contract','item_contract','is_sip_contract','id_goz_contract','goz_works.name_works_goz','id_view_work_contract', 'view_works.name_view_work',
										'all_count_contract','concluded_count_contract','amount_concluded_contract','formalization_count_contract',
										'amount_formalization_contract','big_deal_contract','amoun_implementation_contract','comment_implementation_contract',
										'prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract','renouncement_contract','date_renouncement_contract','document_success_renouncement_reestr','number_aftair_renouncement_reestr',
										'archive_contract','date_contact','year_contract', 'number_pp'])
							->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
							->join('goz_works', 'contracts.id_goz_contract', '=', 'goz_works.id')
							->where('contracts.id',$id)->get()[0];
		if($isFixTime){
			$time_end_1 = microtime(1);
			$time_start_2 = microtime(1);
		}
		$view_contracts = ViewContract::select()->orderBy('name_view_contract','asc')->get();
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		if($isFixTime){
			$time_end_2 = microtime(1);
			$time_start_3 = microtime(1);
		}
		foreach($counterparties as $counter)
			if($contract->id_counterpartie_contract == $counter->id){
				$contract->full_name_counterpartie_contract = $counter->name_full;
				$contract->name_counterpartie_contract = $counter->name;
				$contract->inn_counterpartie_contract = $counter->inn;
				break;
			}
		if($isFixTime)
			$time_end_3 = microtime(1);
		$states = State::select(['states.id','name_state','comment_state','date_state','users.name','users.surname','users.patronymic'])->leftJoin('users','users.id','states.id_user')->where('id_contract', $id)->where('is_work_state', null)->get();
		if(count($states) > 0)
			$state = $states[count($states)-1];
		else
			$state = [];
		$work_states = State::select(['states.id','name_state','comment_state','date_state','users.name','users.surname','users.patronymic'])->leftJoin('users','users.id','states.id_user')->where('id_contract', $id)->where('is_work_state', 1)->get();
		$resolutions = Resolution::select(['*'])->where('id_contract_resolution', $contract->id)->orderBy('id','desc')->get();
		$type_resolutions = DB::SELECT('SELECT * FROM type_resolutions');	
		$units = DB::SELECT('SELECT * FROM units');
		$type_documents = DB::SELECT('SELECT * FROM type_documents');
		$selection_suppliers = DB::SELECT('SELECT * FROM selection_suppliers');
		$bases = DB::SELECT('SELECT * FROM bases');
		$reestr = ReestrContract::firstOrNew(['id_contract_reestr'=>$id]);
		if($reestr->id_view_contract != null)
			$reestr->id_view_contract = ViewContract::select('id')->where('id', $reestr->id_view_contract)->first()->id;
		if(is_numeric($reestr->amount_contract_reestr))
			$reestr->amount_contract_reestr = number_format($reestr->amount_contract_reestr, 2, '.', ' ');
		if(is_numeric($reestr->amount_bank_reestr))
			$reestr->amount_bank_reestr = number_format($reestr->amount_bank_reestr, 2, '.', ' ');
		if(is_numeric($reestr->amount_begin_reestr))
			$reestr->amount_begin_reestr = number_format($reestr->amount_begin_reestr, 2, '.', ' ');
		if(is_numeric($reestr->amount_reestr))
			$reestr->amount_reestr = number_format($reestr->amount_reestr, 2, '.', ' ');
		if(is_numeric($reestr->nmcd_reestr))
			$reestr->nmcd_reestr = number_format($reestr->nmcd_reestr, 2, '.', ' ');
		if(is_numeric($reestr->economy_reestr))
			$reestr->economy_reestr = number_format($reestr->economy_reestr, 2, '.', ' ');
		$curators_sip = Curator::all();
		$curators = OudCurator::all();
		//$departments = Department::all();
		$departments = Department::select()->orderBy('index_department', 'asc')->get();
		
		$big_date_protocol = null;
		$protocols_date = Protocol::select('date_signing_protocol','date_signing_counterpartie_protocol','date_registration_protocol')->where('id_contract', $id)->where('is_protocol', 1)->get();
		if($isFixTime)
			$time_start_4 = microtime(1);
		foreach($protocols_date as $date)
		{
			if($big_date_protocol == null)
			{
				if(strtotime($date->date_signing_protocol) > strtotime($date->date_signing_counterpartie_protocol))
					$big_date_protocol = $date->date_signing_protocol;
				else
					$big_date_protocol = $date->date_signing_counterpartie_protocol;
				//if(strtotime($date->date_registration_protocol) > strtotime($big_date_protocol))
					//$big_date_protocol = $date->date_registration_protocol;
			}
			else
			{
				if(strtotime($date->date_signing_protocol) > strtotime($big_date_protocol))
					$big_date_protocol = $date->date_signing_protocol;
				if(strtotime($date->date_signing_counterpartie_protocol) > strtotime($big_date_protocol))
					$big_date_protocol = $date->date_signing_counterpartie_protocol;
				//if(strtotime($date->date_registration_protocol) > strtotime($big_date_protocol))
					//$big_date_protocol = $date->date_registration_protocol;
			}
		}
		if($isFixTime)
			$time_end_4 = microtime(1);
		$big_date_add_agreement = null;
		$add_agreements_date = Protocol::select('date_signing_protocol','date_signing_counterpartie_protocol','date_entry_ento_force_additional_agreement')->where('id_contract', $id)->where('is_additional_agreement', 1)->get();
		if($isFixTime)
			$time_start_5 = microtime(1);
		foreach($add_agreements_date as $date)
		{
			if($date->date_entry_ento_force_additional_agreement != null && $date->date_entry_ento_force_additional_agreement != '')
				$big_date_add_agreement = $date->date_entry_ento_force_additional_agreement;
			else
			{
				if($big_date_add_agreement == null)
				{
					if(strtotime($date->date_signing_protocol) > strtotime($date->date_signing_counterpartie_protocol))
						$big_date_add_agreement = $date->date_signing_protocol;
					else
						$big_date_add_agreement = $date->date_signing_counterpartie_protocol;
					//if(strtotime($date->date_registration_protocol) > strtotime($big_date_add_agreement))
						//$big_date_add_agreement = $date->date_registration_protocol;
				}
				else
				{
					if(strtotime($date->date_signing_protocol) > strtotime($big_date_add_agreement))
						$big_date_add_agreement = $date->date_signing_protocol;
					if(strtotime($date->date_signing_counterpartie_protocol) > strtotime($big_date_add_agreement))
						$big_date_add_agreement = $date->date_signing_counterpartie_protocol;
					//if(strtotime($date->date_registration_protocol) > strtotime($big_date_add_agreement))
						//$big_date_add_agreement = $date->date_registration_protocol;
				}
			}
		}
		
		// Сроки действия договора
		$all_reest_date_contract = ReestrDateContract::select()->where('id_contract', $id)->get();
		
		// Срок исполнения обязательств Д/К
		$all_reest_date_maturity = ReestrDateMaturity::select()->where('id_contract', $id)->get();
		
		// Суммы по Д/К
		$all_reestr_amount = ReestrAmount::select(['*','reestr_amounts.id'])->leftJoin('units', 'unit_amount', 'units.id')->where('id_contract', $id)->get();
		
		if($isFixTime)
			$time_end_5 = microtime(1);
		//Сумма по счетам
		if($isFixTime)
			$time_start_6 = microtime(1);
		$amount_invoice = 0;
		$invoices = ReestrInvoice::select('*')->where('id_contract', $contract->id)->get();
		foreach($invoices as $invoice)
			/*if($invoice->vat == 1){
				if($invoice->amount_vat)
					$amount_invoice += str_replace(' ','',$invoice->amount_vat);
			}else{
				if($invoice->amount)*/
					$amount_invoice += str_replace(' ','',$invoice->amount);
			//}
		$reestr->amount_invoice_reestr = number_format($amount_invoice, 2, '.', ' ');
		if($isFixTime)
			$time_end_6 = microtime(1);
		//Предыдущий и следующий контракт
		if($isFixTime)
			$time_start_7 = microtime(1);
		$prev_contract = null;
		$next_contract = null;
		if($contract->number_pp != null){
			//$prev_contract = Contract::select(['id'])->where('number_pp','=',Contract::select(['number_pp'])->where('number_pp','<',$contract->number_pp)->max('number_pp'))->max('id');	//Работает, но сравнение происходит, как строк, а не числа
			//$next_contract = Contract::select(['id'])->where('number_pp','=',Contract::select(['number_pp'])->where('number_pp','>',$contract->number_pp)->min('number_pp'))->min('id');
			
			//НЕ КОСТЫЛЬ! А КОСТЫЛИЩЕ!!!
			//$prev_contract = DB::SELECT('SELECT id FROM contracts WHERE number_pp = (SELECT max(CAST(number_pp as UNSIGNED)) FROM contracts WHERE CAST(number_pp as UNSIGNED) < ' . $contract->number_pp . ')');
			$prev_contract = DB::SELECT('SELECT id FROM contracts WHERE number_pp = (SELECT max(CAST(number_pp as UNSIGNED)) FROM contracts WHERE CAST(number_pp as UNSIGNED) < ' . $contract->number_pp . ' AND year_contract=' . $contract->year_contract . ') AND year_contract=' . $contract->year_contract);
			if($prev_contract == null)
				$prev_contract = DB::SELECT('SELECT id FROM contracts WHERE number_pp = (SELECT max(CAST(number_pp as UNSIGNED)) FROM contracts WHERE year_contract=' . ($contract->year_contract-1) . ') AND year_contract=' . ($contract->year_contract-1));
			if($prev_contract != null)
				$prev_contract = $prev_contract[0]->id;
			else
				$prev_contract = null;
			
			$next_contract = DB::SELECT('SELECT id FROM contracts WHERE number_pp = (SELECT min(CAST(number_pp as UNSIGNED)) FROM contracts WHERE CAST(number_pp as UNSIGNED) > ' . $contract->number_pp . ' AND year_contract=' . $contract->year_contract . ') AND year_contract=' . $contract->year_contract);
			if($next_contract == null)
				$next_contract = DB::SELECT('SELECT id FROM contracts WHERE number_pp = (SELECT min(CAST(number_pp as UNSIGNED)) FROM contracts WHERE year_contract=' . ($contract->year_contract+1) . ') AND year_contract=' . ($contract->year_contract+1));
			if($next_contract != null)
				$next_contract = $next_contract[0]->id;
			else
				$next_contract = null;
		}
		//Адресс страницы
		$path_to_page = 'department.planekonom.contract_new_reestr';
		if($isFixTime)
			$time_end_7 = microtime(1);
		if($contract->is_sip_contract)
		{
			if($isFixTime)
				$time_start_8 = microtime(1);
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
			if($isFixTime){
				$time_end_8 = microtime(1);
				echo 'Время на получение инфы о контракте: ' . ($time_end_1-$time_start_1) . '<br/>';
				echo 'Виды и контрагенты: ' . ($time_end_2-$time_start_2) . '<br/>';
				echo 'Подбор контрагента: ' . ($time_end_3-$time_start_3) . '<br/>';
				echo 'Протоколы: ' . ($time_end_4-$time_start_4) . '<br/>';
				echo 'ДС: ' . ($time_end_5-$time_start_5) . '<br/>';
				echo 'Сумма по счетам: ' . ($time_end_6-$time_start_6) . '<br/>';
				echo 'Селудющий и предыдущий контракт: ' . ($time_end_7-$time_start_7) . '<br/>';
				echo 'Счета по договору: ' . ($time_end_8-$time_start_8) . '<br/>';
			}
			return view($path_to_page, ['reestr'=>$reestr,
																'contract'=>$contract, 
																'viewContracts'=>$view_contracts, 
																'counterparties'=>$counterparties, 
																'states'=>$states, 
																'work_states'=>$work_states,
																'resolutions'=>$resolutions,
																'type_resolutions'=>$type_resolutions,
																'units'=>$units,
																'type_documents'=>$type_documents,
																'selection_suppliers'=>$selection_suppliers,
																'bases'=>$bases,
																'curators_sip'=>$curators_sip,
																'curators'=>$curators,
																'departments'=>$departments,
																'big_date_protocol'=>$big_date_protocol,
																'big_date_add_agreement'=>$big_date_add_agreement,
																'all_reest_date_contract'=>$all_reest_date_contract,
																'all_reest_date_maturity'=>$all_reest_date_maturity,
																'all_reestr_amount'=>$all_reestr_amount,
																'prev_contract'=>$prev_contract,
																'next_contract'=>$next_contract,
																'amount_scores'=>$amount_scores,
																'amount_prepayments'=>$amount_prepayments,
																'amount_invoices'=>$amount_invoices,
																'amount_payments'=>$amount_payments,
																'amount_returns'=>$amount_returns,
																'scores'=>$scores,
																'prepayments'=>$prepayments,
																'invoices'=>$invoices,
																'payments'=>$payments,
																'returns'=>$returns
																]);
		}
		if($isFixTime){
			echo 'Время на получение инфы о контракте: ' . ($time_end_1-$time_start_1) . '<br/>';
			echo 'Виды и контрагенты: ' . ($time_end_2-$time_start_2) . '<br/>';
			echo 'Подбор контрагента: ' . ($time_end_3-$time_start_3) . '<br/>';
			echo 'Протоколы: ' . ($time_end_4-$time_start_4) . '<br/>';
			echo 'ДС: ' . ($time_end_5-$time_start_5) . '<br/>';
			echo 'Сумма по счетам: ' . ($time_end_6-$time_start_6) . '<br/>';
			echo 'Селудющий и предыдущий контракт: ' . ($time_end_7-$time_start_7) . '<br/>';
		}
        return view($path_to_page, ['reestr'=>$reestr,
															'contract'=>$contract, 
															'viewContracts'=>$view_contracts, 
															'counterparties'=>$counterparties, 
															'states'=>$states, 
															'resolutions'=>$resolutions,
															'type_resolutions'=>$type_resolutions,
															'units'=>$units,
															'type_documents'=>$type_documents,
															'selection_suppliers'=>$selection_suppliers,
															'bases'=>$bases,
															'curators_sip'=>$curators_sip,
															'curators'=>$curators,
															'departments'=>$departments,
															'big_date_protocol'=>$big_date_protocol,
															'big_date_add_agreement'=>$big_date_add_agreement,
															'all_reest_date_contract'=>$all_reest_date_contract,
															'all_reest_date_maturity'=>$all_reest_date_maturity,
															'all_reestr_amount'=>$all_reestr_amount,
															'prev_contract'=>$prev_contract,
															'next_contract'=>$next_contract
															]);
	}
	
	public function new_reestr()
    {
		$contract = null;
		$view_contracts = ViewContract::select()->orderBy('name_view_contract','asc')->get();
		$counterparties = Counterpartie::select(['*'])->orderBy('name_full', 'asc')->get();
		$states = [];
		$state = [];
		$resolutions = [];
		$units = DB::SELECT('SELECT * FROM units');
		$type_documents = DB::SELECT('SELECT * FROM type_documents');
		$selection_suppliers = DB::SELECT('SELECT * FROM selection_suppliers');
		$bases = DB::SELECT('SELECT * FROM bases');
		$reestr = new ReestrContract();
		//$curators = Curator::all();
		$curators = OudCurator::all();
		$departments = Department::all();
        return view('department.planekonom.contract_new_reestr', ['reestr'=>$reestr,
															'contract'=>$contract, 
															'viewContracts'=>$view_contracts, 
															'counterparties'=>$counterparties, 
															'states'=>$states, 
															'resolutions'=>$resolutions, 
															'units'=>$units,
															'type_documents'=>$type_documents,
															'selection_suppliers'=>$selection_suppliers,
															'bases'=>$bases,
															'curators'=>$curators,
															'departments'=>$departments
															]);
    }
	
	public function new_sip_reestr()
    {
		$contract = null;
		$view_contracts = ViewContract::select()->where('is_sip_view_contract', 1)->orderBy('name_view_contract','asc')->get();
		$counterparties = Counterpartie::select(['*'])->where('is_sip_counterpartie', 1)->orderBy('name_full', 'asc')->get();
		$states = [];
		$state = [];
		$resolutions = [];
		$units = DB::SELECT('SELECT * FROM units');
		$type_documents = DB::SELECT('SELECT * FROM type_documents');
		$selection_suppliers = DB::SELECT('SELECT * FROM selection_suppliers');
		$bases = DB::SELECT('SELECT * FROM bases');
		$reestr = new ReestrContract();
		$curators_sip = Curator::all();
		$curators = OudCurator::all();
		$departments = Department::all();
        return view('department.planekonom.contract_new_sip_reestr', ['reestr'=>$reestr,
															'contract'=>$contract, 
															'viewContracts'=>$view_contracts, 
															'counterparties'=>$counterparties, 
															'states'=>$states, 
															'resolutions'=>$resolutions, 
															'units'=>$units,
															'type_documents'=>$type_documents,
															'selection_suppliers'=>$selection_suppliers,
															'bases'=>$bases,
															'curators_sip'=>$curators_sip,
															'curators'=>$curators,
															'departments'=>$departments
															]);
    }
	
	public function new_sip_reestr_2()
    {
		$contract = null;
		$view_contracts = ViewContract::select()->where('is_sip_view_contract', 1)->orderBy('name_view_contract','asc')->get();
		$counterparties = Counterpartie::select(['*'])->where('is_sip_counterpartie', 1)->orderBy('name_full', 'asc')->get();
		$states = [];
		$state = [];
		$resolutions = [];
		$units = DB::SELECT('SELECT * FROM units');
		$type_documents = DB::SELECT('SELECT * FROM type_documents');
		$selection_suppliers = DB::SELECT('SELECT * FROM selection_suppliers');
		$bases = DB::SELECT('SELECT * FROM bases');
		$reestr = new ReestrContract();
		$curators_sip = Curator::all();
		$curators = OudCurator::all();
		$departments = Department::all();
        return view('department.planekonom.contract_new_sip_reestr_2', ['reestr'=>$reestr,
															'contract'=>$contract, 
															'viewContracts'=>$view_contracts, 
															'counterparties'=>$counterparties, 
															'states'=>$states, 
															'resolutions'=>$resolutions, 
															'units'=>$units,
															'type_documents'=>$type_documents,
															'selection_suppliers'=>$selection_suppliers,
															'bases'=>$bases,
															'curators_sip'=>$curators_sip,
															'curators'=>$curators,
															'departments'=>$departments
															]);
    }
	
	public function new_sip_reestr_3()
    {
		$contract = null;
		$view_contracts = ViewContract::select()->where('is_sip_view_contract', 1)->orderBy('name_view_contract','asc')->get();
		$counterparties = Counterpartie::select(['*'])->where('is_sip_counterpartie', 1)->orderBy('name_full', 'asc')->get();
		$states = [];
		$state = [];
		$resolutions = [];
		$units = DB::SELECT('SELECT * FROM units');
		$type_documents = DB::SELECT('SELECT * FROM type_documents');
		$selection_suppliers = DB::SELECT('SELECT * FROM selection_suppliers');
		$bases = DB::SELECT('SELECT * FROM bases');
		$reestr = new ReestrContract();
		$curators_sip = Curator::all();
		$curators = OudCurator::all();
		$departments = Department::all();
        return view('department.planekonom.contract_new_sip_reestr_3', ['reestr'=>$reestr,
															'contract'=>$contract, 
															'viewContracts'=>$view_contracts, 
															'counterparties'=>$counterparties, 
															'states'=>$states, 
															'resolutions'=>$resolutions, 
															'units'=>$units,
															'type_documents'=>$type_documents,
															'selection_suppliers'=>$selection_suppliers,
															'bases'=>$bases,
															'curators_sip'=>$curators_sip,
															'curators'=>$curators,
															'departments'=>$departments
															]);
    }
	
	public function new_sip_reestr_4()
    {
		$contract = null;
		$view_contracts = ViewContract::select()->where('is_sip_view_contract', 1)->orderBy('name_view_contract','asc')->get();
		$counterparties = Counterpartie::select(['*'])->where('is_sip_counterpartie', 1)->orderBy('name_full', 'asc')->get();
		$states = [];
		$state = [];
		$resolutions = [];
		$units = DB::SELECT('SELECT * FROM units');
		$type_documents = DB::SELECT('SELECT * FROM type_documents');
		$selection_suppliers = DB::SELECT('SELECT * FROM selection_suppliers');
		$bases = DB::SELECT('SELECT * FROM bases');
		$reestr = new ReestrContract();
		$curators_sip = Curator::all();
		$curators = OudCurator::all();
		$departments = Department::all();
        return view('department.planekonom.contract_new_sip_reestr_4', ['reestr'=>$reestr,
															'contract'=>$contract, 
															'viewContracts'=>$view_contracts, 
															'counterparties'=>$counterparties, 
															'states'=>$states, 
															'resolutions'=>$resolutions, 
															'units'=>$units,
															'type_documents'=>$type_documents,
															'selection_suppliers'=>$selection_suppliers,
															'bases'=>$bases,
															'curators_sip'=>$curators_sip,
															'curators'=>$curators,
															'departments'=>$departments
															]);
    }
	
	public function new_sip_reestr_5()
    {
		$contract = null;
		$view_contracts = ViewContract::select()->where('is_sip_view_contract', 1)->orderBy('name_view_contract','asc')->get();
		$counterparties = Counterpartie::select(['*'])->where('is_sip_counterpartie', 1)->orderBy('name_full', 'asc')->get();
		$states = [];
		$state = [];
		$resolutions = [];
		$units = DB::SELECT('SELECT * FROM units');
		$type_documents = DB::SELECT('SELECT * FROM type_documents');
		$selection_suppliers = DB::SELECT('SELECT * FROM selection_suppliers');
		$bases = DB::SELECT('SELECT * FROM bases');
		$reestr = new ReestrContract();
		$curators_sip = Curator::all();
		$curators = OudCurator::all();
		$departments = Department::all();
        return view('department.planekonom.contract_new_sip_reestr_5', ['reestr'=>$reestr,
															'contract'=>$contract, 
															'viewContracts'=>$view_contracts, 
															'counterparties'=>$counterparties, 
															'states'=>$states, 
															'resolutions'=>$resolutions, 
															'units'=>$units,
															'type_documents'=>$type_documents,
															'selection_suppliers'=>$selection_suppliers,
															'bases'=>$bases,
															'curators_sip'=>$curators_sip,
															'curators'=>$curators,
															'departments'=>$departments
															]);
    }
	
	public function create_reestr(Request $request)
	{
		$val = Validator::make($request->all(),[
			'id_view_contract' => 'required',
			'id_counterpartie_contract' => 'required',
			'name_work_contract' => 'required',
			'number_pp' => Rule::unique('contracts')->where('year_contract', $request['year_contract'])
		])->validate();
		$var_is_goz = 4;
		if($request['marketing_goz_reestr'] || $request['procurement_goz_reestr'])
			$var_is_goz = 1;
		else if($request['export_reestr'])
			$var_is_goz = 2;
		else if($request['interfactory_reestr'])
			$var_is_goz = 3;
		$contract = new Contract();
		$contract->fill([
						'number_pp' => $request['number_pp'],
						'number_contract' => $request['number_contract'],
						'year_contract' => $request['year_contract'],
						'id_goz_contract' => $var_is_goz,
						'id_counterpartie_contract' => $request['id_counterpartie_contract'],
						'name_work_contract' => $request['name_work_contract'],
						'item_contract' => $request['item_contract'],
						'renouncement_contract' => $request['renouncement_contract'] ? 1 : 0,
						'date_renouncement_contract' => $request['date_renouncement_contract'],
						'archive_contract' => $request['archive_contract'] ? 1 : 0,
						'document_success_renouncement_reestr' => $request['document_success_renouncement_reestr'],
						'number_aftair_renouncement_reestr' => $request['number_aftair_renouncement_reestr']
		]);
		$all_dirty = JournalController::getMyChanges($contract);
		$contract->save();
		$reestr = new ReestrContract();
		$reestr->fill($request->all());
		if($request['amount_contract_reestr'])
			$reestr->amount_contract_reestr = str_replace(' ','',$reestr->amount_contract_reestr);
		if($request['amount_bank_reestr'])
			$reestr->amount_bank_reestr = str_replace(' ','',$reestr->amount_bank_reestr);
		if($request['amount_begin_reestr'])
			$reestr->amount_begin_reestr = str_replace(' ','',$reestr->amount_begin_reestr);
		if($request['amount_reestr']){
			$reestr->amount_reestr = str_replace(' ','',$reestr->amount_reestr);
			if(!$request['amount_contract_reestr'])
				$reestr->amount_contract_reestr = $reestr->amount_reestr;
		}
		if($request['nmcd_reestr'])
			$reestr->nmcd_reestr = str_replace(' ','',$reestr->nmcd_reestr);
		if($request['economy_reestr'])
			$reestr->economy_reestr = str_replace(' ','',$reestr->economy_reestr);
		$reestr->fill([
						'id_contract_reestr' => $contract->id,
						//'application_reestr' => $request['application_reestr'] ? 1 : 0,
						'marketing_reestr' => $request['marketing_reestr'] ? 1 : 0,
						'marketing_goz_reestr' => $request['marketing_goz_reestr'] ? 1 : 0,
						'participation_reestr' => $request['participation_reestr'] ? 1 : 0,
						'marketing_fz_223_reestr' => $request['marketing_fz_223_reestr'] ? 1 : 0,
						'marketing_fz_44_reestr' => $request['marketing_fz_44_reestr'] ? 1 : 0,
						'export_reestr' => $request['export_reestr'] ? 1 : 0,
						'interfactory_reestr' => $request['interfactory_reestr'] ? 1 : 0,
						'procurement_reestr' => $request['procurement_reestr'] ? 1 : 0,
						'single_provider_reestr' => $request['single_provider_reestr'] ? 1 : 0,
						'own_funds_reestr' => $request['own_funds_reestr'] ? 1 : 0,
						'investments_reestr' => $request['investments_reestr'] ? 1 : 0,
						'purchase_reestr' => $request['purchase_reestr'] ? 1 : 0,
						'procurement_fz_223_reestr' => $request['procurement_fz_223_reestr'] ? 1 : 0,
						'procurement_fz_44_reestr' => $request['procurement_fz_44_reestr'] ? 1 : 0,
						'procurement_goz_reestr' => $request['procurement_goz_reestr'] ? 1 : 0,
						'other_reestr' => $request['other_reestr'] ? 1 : 0,
						'mob_reestr' => $request['mob_reestr'] ? 1 : 0,
						'vat_begin_reestr' => $request['vat_begin_reestr'] ? 1 : 0,
						'approximate_amount_begin_reestr' => $request['approximate_amount_begin_reestr'] ? 1 : 0,
						'fixed_amount_begin_reestr' => $request['fixed_amount_begin_reestr'] ? 1 : 0,
						'vat_reestr' => $request['vat_reestr'] ? 1 : 0,
						'approximate_amount_reestr' => $request['approximate_amount_reestr'] ? 1 : 0,
						'fixed_amount_reestr' => $request['fixed_amount_reestr'] ? 1 : 0,
						'prolongation_reestr' => $request['prolongation_reestr'] ? 1 : 0
		]);
		$all_dirty = JournalController::getMyChanges($reestr, $all_dirty);
		$reestr->save();
		// Создаём срок действия Д/К
		if ($request['date_contract_reestr'] || $request['date_e_contract_reestr'])
		{
			$date_contract = new ReestrDateContract();
			$date_contract->fill([
				'id_contract' => $contract->id,
				'name_date_contract' => 'Д/К',
				'term_date_contract' => $request['date_contract_reestr'] ? $request['date_contract_reestr'] : '',
				'end_date_contract' => $request['date_e_contract_reestr']
			]);
			$date_contract->save();
		}
		// Создаём срок исполнения обязательств
		if ($request['date_maturity_reestr'] || $request['date_e_maturity_reestr'])
		{
			$date_maturity = new ReestrDateMaturity();
			$date_maturity->fill([
				'id_contract' => $contract->id,
				'name_date_maturity' => 'Д/К',
				'term_date_maturity' => $request['date_maturity_reestr'],
				'end_date_maturity' => $request['date_e_maturity_reestr']
			]);
			$date_maturity->save();
		}
		// Создаём сумму
		if ($request['amount_reestr'])
		{
			$amount = new ReestrAmount();
			$amount->fill([
				'id_contract' => $contract->id,
				'name_amount' => 'Д/К',
				'value_amount' => $request['amount_reestr'],
				'unit_amount' => $request['unit_reestr'],
				'vat_amount' => $request['vat_reestr'] ? 1 : 0,
				'approximate_amount' => $request['approximate_amount_reestr'] ? 1 : 0,
				'fixed_amount' => $request['fixed_amount_reestr'] ? 1 : 0
			]);
			$amount->save();
		}
		JournalController::store(Auth::User()->id,'Создание контракта из реестра с id = ' . $contract->id . '~' . json_encode($all_dirty));
		if($request->file('new_file_resolution'))
			ResolutionController::store_resol_new_app($request, $contract->id);
		return redirect()->route('department.ekonomic.contract_new_reestr', $contract->id)->with(['success'=>'Контракт сохранен!','del_history'=>'1']);
	}
	
	public function create_sip_reestr(Request $request)
	{
		$val = Validator::make($request->all(),[
			'id_view_contract' => 'required',
			'id_counterpartie_contract' => 'required',
			'name_work_contract' => 'required',
			'number_pp' => Rule::unique('contracts')->where('year_contract', $request['year_contract'])
		])->validate();
		$last_number_application = Application::select()->withTrashed()->max('number_application');
		$new_application = new Application();
		$new_application->fill([
						'id_counterpartie_application' => $request['id_counterpartie_contract'],
						'number_application' => $last_number_application+1,
						'date_application' => date('Y-m-d', time())
		]);
		$new_application->save();
		$new_document = new Document();
		$new_document->fill([
						'id_application_document' => $new_application->id,
						'date_document' => date('Y-m-d', time())
		]);
		$new_document->save();
		$var_is_goz = 4;
		if($request['marketing_goz_reestr'] || $request['procurement_goz_reestr'])
			$var_is_goz = 1;
		else if($request['export_reestr'])
			$var_is_goz = 2;
		else if($request['interfactory_reestr'])
			$var_is_goz = 3;
		$contract = new Contract();
		$contract->fill([
						'number_pp' => $request['number_pp'],
						'number_contract' => $request['number_contract'],
						'year_contract' => $request['year_contract'],
						'id_goz_contract' => $var_is_goz,
						'id_counterpartie_contract' => $request['id_counterpartie_contract'],
						'name_work_contract' => $request['name_work_contract'],
						'item_contract' => $request['item_contract'],
						'renouncement_contract' => $request['renouncement_contract'] ? 1 : 0,
						'date_renouncement_contract' => $request['date_renouncement_contract'],
						'archive_contract' => $request['archive_contract'] ? 1 : 0,
						'document_success_renouncement_reestr' => $request['document_success_renouncement_reestr'],
						'number_aftair_renouncement_reestr' => $request['number_aftair_renouncement_reestr'],
						'is_sip_contract' => 1,
						'id_document_contract' => $new_document->id
		]);
		$all_dirty = JournalController::getMyChanges($contract);
		$contract->save();
		$reestr = new ReestrContract();
		$reestr->fill($request->all());
		if($request['amount_contract_reestr'])
			$reestr->amount_contract_reestr = str_replace(' ','',$reestr->amount_contract_reestr);
		if($request['amount_bank_reestr'])
			$reestr->amount_bank_reestr = str_replace(' ','',$reestr->amount_bank_reestr);
		if($request['amount_begin_reestr'])
			$reestr->amount_begin_reestr = str_replace(' ','',$reestr->amount_begin_reestr);
		if($request['amount_reestr'])
			$reestr->amount_reestr = str_replace(' ','',$reestr->amount_reestr);
		if($request['nmcd_reestr'])
			$reestr->nmcd_reestr = str_replace(' ','',$reestr->nmcd_reestr);
		if($request['economy_reestr'])
			$reestr->economy_reestr = str_replace(' ','',$reestr->economy_reestr);
		$reestr->fill([
						'id_contract_reestr' => $contract->id,
						//'application_reestr' => $request['application_reestr'] ? 1 : 0,
						'marketing_reestr' => $request['marketing_reestr'] ? 1 : 0,
						'marketing_goz_reestr' => $request['marketing_goz_reestr'] ? 1 : 0,
						'participation_reestr' => $request['participation_reestr'] ? 1 : 0,
						'marketing_fz_223_reestr' => $request['marketing_fz_223_reestr'] ? 1 : 0,
						'marketing_fz_44_reestr' => $request['marketing_fz_44_reestr'] ? 1 : 0,
						'export_reestr' => $request['export_reestr'] ? 1 : 0,
						'interfactory_reestr' => $request['interfactory_reestr'] ? 1 : 0,
						'procurement_reestr' => $request['procurement_reestr'] ? 1 : 0,
						'single_provider_reestr' => $request['single_provider_reestr'] ? 1 : 0,
						'own_funds_reestr' => $request['own_funds_reestr'] ? 1 : 0,
						'investments_reestr' => $request['investments_reestr'] ? 1 : 0,
						'purchase_reestr' => $request['purchase_reestr'] ? 1 : 0,
						'procurement_fz_223_reestr' => $request['procurement_fz_223_reestr'] ? 1 : 0,
						'procurement_fz_44_reestr' => $request['procurement_fz_44_reestr'] ? 1 : 0,
						'procurement_goz_reestr' => $request['procurement_goz_reestr'] ? 1 : 0,
						'other_reestr' => $request['other_reestr'] ? 1 : 0,
						'mob_reestr' => $request['mob_reestr'] ? 1 : 0,
						'vat_reestr' => $request['vat_reestr'] ? 1 : 0,
						'approximate_amount_reestr' => $request['approximate_amount_reestr'] ? 1 : 0,
						'prolongation_reestr' => $request['prolongation_reestr'] ? 1 : 0
		]);
		$all_dirty = JournalController::getMyChanges($reestr, $all_dirty);
		$reestr->save();
		// Создаём срок действия Д/К
		if ($request['date_contract_reestr'] || $request['date_e_contract_reestr'])
		{
			$date_contract = new ReestrDateContract();
			$date_contract->fill([
				'id_contract' => $contract->id,
				'name_date_contract' => 'Д/К',
				'term_date_contract' => $request['date_contract_reestr'] ? $request['date_contract_reestr'] : '',
				'end_date_contract' => $request['date_e_contract_reestr']
			]);
			$date_contract->save();
		}
		// Создаём срок исполнения обязательств
		if ($request['date_maturity_reestr'] || $request['date_e_maturity_reestr'])
		{
			$date_maturity = new ReestrDateMaturity();
			$date_maturity->fill([
				'id_contract' => $contract->id,
				'name_date_maturity' => 'Д/К',
				'term_date_maturity' => $request['date_maturity_reestr'],
				'end_date_maturity' => $request['date_e_maturity_reestr']
			]);
			$date_maturity->save();
		}
		// Создаём сумму
		if ($request['amount_reestr'])
		{
			$amount = new ReestrAmount();
			$amount->fill([
				'id_contract' => $contract->id,
				'name_amount' => 'Д/К',
				'value_amount' => $request['amount_reestr'],
				'unit_amount' => $request['unit_reestr'],
				'vat_amount' => $request['vat_reestr'] ? 1 : 0,
				'approximate_amount' => $request['approximate_amount_reestr'] ? 1 : 0,
				'fixed_amount' => $request['fixed_amount_reestr'] ? 1 : 0
			]);
			$amount->save();
		}
		JournalController::store(Auth::User()->id,'Создание СИП контракта из реестра с id = ' . $contract->id . '~' . json_encode($all_dirty));
		if($request->file('new_file_resolution'))
			ResolutionController::store_resol_new_app($request, $contract->id);
		return redirect()->route('department.ekonomic.contract_new_reestr', $contract->id)->with(['success'=>'Контракт сохранен!','del_history'=>'1']);
		
		/*
		//Переброс договоров СИП из реестра в ПЭО 
		$contracts = Contract::select(['*'])
						->where('contracts.id_counterpartie_contract','>','-1')
						->where('contracts.number_contract', 'like', '%‐23‐%')
						->where('is_sip_contract', 0)
						->get();
		foreach($contracts as $contract)
		{
			$last_number_application = Application::select()->withTrashed()->max('number_application');
			$new_application = new Application();
			$new_application->fill([
							'id_counterpartie_application' => $contract->id_counterpartie_contract,
							'number_application' => $last_number_application+1,
							'date_application' => date('Y-m-d', time()),
							'date_incoming' => date('Y-m-d', time())
			]);
			$new_application->save();
			$new_document = new Document();
			$new_document->fill([
							'id_application_document' => $new_application->id,
							'date_document' => date('Y-m-d', time())
			]);
			$new_document->save();
			$contract->is_sip_contract = 1;
			$contract->id_document_contract = $new_document->id;
			$contract->save();
			
			/*$new_reconciliation = new ReconciliationUser([
						'id_application' => $new_application->id,
						'id_user' => 28
			]);
			$new_reconciliation->save();*/
		/*}
		dd($contracts);*/
	}
	
	public function show_all_reestr()
	{
		$link = '';
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
		
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		$sip_counterparties = Counterpartie::select(['*'])->where('is_sip_counterpartie', 1)->orderBy('name', 'asc')->get();
		$counterpartie = '';
		$counerpartie_name = '';
		if(isset($_GET['counterpartie'])) {
			//$counerpartie_name = $_GET['counterpartie'];
			$counterpartie = $_GET['counterpartie'];
			/*foreach($counterparties as $counter){
				if($counter->name == $counerpartie_name){
					$counterpartie = $counter->id;
					break;
				}
			}*/
			$counterpartie_str = "id_counterpartie_contract";
			$counterpartie_equal = "=";
			$link .= "&counterpartie=" . $_GET['counterpartie'];
		} else
			$counterpartie = '';
		$department = 'all';
		$link .= "&department=all";
		if(isset($_GET['department'])) {
			if($_GET['department'] != 'all') {
				$department = $_GET['department'];
				$link .= "&department=" . $_GET['department'];
				$sql_like = '%‐' . $department . '‐%';
			}
			else
				$sql_like = '%';
		}
		else {
			$sql_like = '%';
			//$sql_like = '';
			if(Auth::User())
				switch(Auth::User()->hasRole()->role){
					case 'Планово-экономический отдел':
						$sql_like = '%‐02‐%';
						$department = '02';
						$link .= "&department=02";
						break;
					case 'Финансовый отдел':
						$sql_like = '%‐05‐%';
						$department = '05';
						$link .= "&department=05";
						break;
					case 'Второй отдел':
						$sql_like = '%‐22‐%';
						$department = '22';
						$link .= "&department=22";
						break;
					case 'Второй отдел (просмотр)':
						$sql_like = '%‐22‐%';
						$department = '22';
						$link .= "&department=22";
						break;
					case 'Канцелярия':
						$sql_like = '%‐17‐%';
						$department = '17';
						$link .= "&department=17";
						break;
					case 'Десятый отдел':
						$sql_like = '%‐23‐%';
						$department = '23';
						$link .= "&department=23";
						break;
					case 'Отдел управления договорами':
						$sql_like = '%‐40‐%';
						$department = '40';
						$link .= "&department=40";
						break;
					case 'Отдел 15':
						$sql_like = '%‐24‐%';
						$department = '24';
						$link .= "&department=24";
						break;
					case 'Отдел 93':
						$sql_like = '%‐25‐%';
						$department = '25';
						$link .= "&department=25";
						break;
					case 'Цех 1':
						$sql_like = '%‐19‐%';
						$department = '19';
						$link .= "&department=19";
						break;
					case 'Цех 2':
						$sql_like = '%‐20‐%';
						$department = '20';
						$link .= "&department=20";
						break;
					case 'Цех 3':
						$sql_like = '%‐21‐%';
						$department = '21';
						$link .= "&department=21";
						break;
					case 'ОТК':
						$sql_like = '%‐10‐%';
						$department = '10';
						$link .= "&department=10";
						break;
					case 'ОГТ':
						$sql_like = '%‐12‐%';
						$department = '12';
						$link .= "&department=12";
						break;
					case 'Юридический отдел':
						$sql_like = '%‐06‐%';
						$department = '06';
						$link .= "&department=06";
						break;
					case 'ООТиЗ':
						$sql_like = '%‐03‐%';
						$department = '03';
						$link .= "&department=03";
						break;
					case 'Отдел 22':
						$sql_like = '%‐09‐%';
						$department = '09';
						$link .= "&department=09";
						break;
					case 'СФКЗЦ':
						$sql_like = '%‐37‐%';
						$department = '37';
						$link .= "&department=37";
						break;
					case 'ОИТ':
						$sql_like = '%‐18‐%';
						$department = '18';
						$link .= "&department=18";
						break;
					default:
						$sql_like = '%';
						break;
				}
		}
		//Контракты
		$paginate_count = 10;
		if (isset($_GET["page"])) {
			$page  = $_GET["page"];
		} else {
			$page=1;
		};
		$start = ($page-1) * $paginate_count;
		//if(strlen($sql_like) > 0){
		if($search_name != '' && $search_value != ''){
			$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','id_goz_contract','id_view_contract', 'view_contracts.name_view_contract',
											'all_count_contract','concluded_count_contract','amount_concluded_contract','formalization_count_contract',
											'amount_formalization_contract','big_deal_contract','amoun_implementation_contract','comment_implementation_contract',
											'prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract','date_contact','year_contract',
											'item_contract', 'amount_reestr', DB::raw('CAST(number_pp as UNSIGNED) as cast_number_pp')])
							->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
							->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
							->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
							/*->where('contracts.number_contract','!=', null)*/
							->where('contracts.id_counterpartie_contract','>','-1')
							->where($year_str, $year_equal, $year)
							->where($view_contract_str, $view_contract_equal, $view_contract)
							->where($counterpartie_str, $counterpartie_equal, $counterpartie)
							//->where('contracts.number_contract', 'like', $sql_like)
							->where($search_name, 'like', '%' . $search_value . '%')
							->orderBy($sort_year, $sort_p)
							->orderBy($sort, $sort_p)
							->offset($start)
							->limit($paginate_count)
							->get();
			$contract_count = Contract::select()->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', '=', 'view_contracts.id')->where('contracts.id_counterpartie_contract','>',-1)->where($year_str, $year_equal, $year)
											->where($view_contract_str, $view_contract_equal, $view_contract)
											->where($counterpartie_str, $counterpartie_equal, $counterpartie)
											//->where('contracts.number_contract', 'like', $sql_like)
											->where($search_name, 'like', '%' . $search_value . '%')
											->count();
		}else{
			$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','id_goz_contract','id_view_contract', 'view_contracts.name_view_contract',
											'all_count_contract','concluded_count_contract','amount_concluded_contract','formalization_count_contract',
											'amount_formalization_contract','big_deal_contract','amoun_implementation_contract','comment_implementation_contract',
											'prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract','date_contact','year_contract',
											'item_contract', 'amount_reestr', DB::raw('CAST(number_pp as UNSIGNED) as cast_number_pp')])
							->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
							->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
							->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
							/*->where('contracts.number_contract','!=', null)*/
							->where('contracts.id_counterpartie_contract','>','-1')
							->where($year_str, $year_equal, $year)
							->where($view_contract_str, $view_contract_equal, $view_contract)
							->where($counterpartie_str, $counterpartie_equal, $counterpartie)
							->where('contracts.number_contract', 'like', $sql_like)
							->orderBy($sort_year, $sort_p)
							->orderBy($sort, $sort_p)
							->offset($start)
							->limit($paginate_count)
							->get();
			$contract_count = Contract::select()->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', '=', 'view_contracts.id')->where('contracts.id_counterpartie_contract','>',-1)->where($year_str, $year_equal, $year)
											->where($view_contract_str, $view_contract_equal, $view_contract)
											->where($counterpartie_str, $counterpartie_equal, $counterpartie)
											->where('contracts.number_contract', 'like', $sql_like)
											->count();
		}
		/*}
		else{
			$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','id_goz_contract','id_view_work_contract', 'view_works.name_view_work',
											'all_count_contract','concluded_count_contract','amount_concluded_contract','formalization_count_contract',
											'amount_formalization_contract','big_deal_contract','amoun_implementation_contract','comment_implementation_contract',
											'prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract','date_contact','year_contract'])
							->join('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
							->where('contracts.id_counterpartie_contract','>','-1')
							->where($year_str, $year_equal, $year)
							->where($view_work_str, $view_work_equal, $view_work)
							->where($counterpartie_str, $counterpartie_equal, $counterpartie)
							->orderBy('contracts.id', 'desc')
							->offset($start)
							->limit($paginate_count)
							->get();
		$contract_count = Contract::select()->join('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')->where('contracts.id_counterpartie_contract','>',-1)->where($year_str, $year_equal, $year)
											->where($view_work_str, $view_work_equal, $view_work)
											->where($counterpartie_str, $counterpartie_equal, $counterpartie)
											->count();
		}*/
		
		$prev_page = $page - 1 > 0 ? (int)($page-1) : '';
		$next_page = $page + 1 <= (int)ceil($contract_count/$paginate_count) ? (int)($page+1) : '';
		$years = DB::SELECT('SELECT year_contract FROM contracts GROUP BY year_contract ORDER BY year_contract DESC');
		$view_works = ViewWork::all();
		$all_view_contracts = ViewContract::select('*')->get();
		$view_contracts = ViewContract::select()->orderBy('name_view_contract','asc')->get();
		foreach($contracts as $contract)
			foreach($counterparties as $counter)
				if($contract->id_counterpartie_contract == $counter->id)
					$contract->name_counterpartie_contract = $counter->name;

		$departments = Department::select()->get();
		
		//Правильные форматы для сумм
		foreach($contracts as $contract)
			if(is_numeric($contract->amount_reestr))
				$contract->amount_reestr = number_format($contract->amount_reestr, 2, '.', ' ');
        return view('reestr.main',['contracts' => $contracts,
													'years' => $years,
													'year' => $year,
													'viewWorks' => $view_works,
													'viewWork' => '',//$view_work,
													'all_view_contracts' => $all_view_contracts,
													'viewContracts' => $view_contracts,
													'viewContract' => $view_contract,
													'counterparties' => $counterparties,
													'counterpartie' => $counterpartie,
													'sip_counterparties' => $sip_counterparties,
													'department' => $department,
													'departments' => $departments,
													'search_name' => $search_name,
													'search_value' => $search_value,
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

	
	public function update_peo(Request $request, $id)
    {
        //dump($request->all());
		$val = Validator::make($request->all(),[
			'number_contract' => 'required',
			'id_view_work_contract' => 'required',
			'id_counterpartie_contract' => 'required',
			'name_work_contract' => 'required',
			'date_contact' => 'nullable|date',
		])->validate();
		$contract = Contract::findOrFail($id);
		$contract->fill(['id_counterpartie_contract' => $request['id_counterpartie_contract'],
						'number_contract' => $request['number_contract'],
						'name_work_contract' => $request['name_work_contract'],
						'id_goz_contract' => $request['goz_contract'] ? 1 : ($request['export_contract'] ? 2 : 3),
						'id_view_work_contract' => $request['id_view_work_contract'],
						'all_count_contract' => $request['all_count_contract'],
						'concluded_count_contract' => $request['concluded_count_contract'],
						'amount_concluded_contract' => $request['amount_concluded_contract'],
						'formalization_count_contract' => $request['formalization_count_contract'],
						'amount_formalization_contract' => $request['amount_formalization_contract'],
						'big_deal_contract' => $request['big_deal_contract'],
						'amoun_implementation_contract' => $request['amoun_implementation_contract'],
						'comment_implementation_contract' => ($request['check_implementation_contract'] ? null : true) == null ? null : ($request['comment_implementation_contract'] == null ? '' : $request['comment_implementation_contract']),
						'prepayment_score_contract' => $request['prepayment_score_contract'],
						'invoice_score_contract' => $request['invoice_score_contract'],
						'prepayment_payment_contract' => $request['prepayment_payment_contract'],
						'amount_payment_contract' => $request['amount_payment_contract'],
						'date_contact' => $request['ot_date_contact'] ? $request['date_contact'] : null,
						'year_contract' => $request['year_contract'],
		]);
		//dump($contract);
		$all_dirty = JournalController::getMyChanges($contract);
		$contract->save();
		JournalController::store(Auth::User()->id,'Обновление ПЭО контракта с id = ' . $contract->id . '~' . json_encode($all_dirty));
		return redirect()->back()->with('success', 'Контракт обновлен!');
    }
	
	public function update_reestr(Request $request, $id)
    {
		$contract = Contract::findOrFail($id);
		$val = Validator::make($request->all(),[
			'number_contract' => 'required',
			'id_view_contract' => 'required',
			'id_counterpartie_contract' => 'required',
			'name_work_contract' => 'required',
			'number_pp' => Rule::unique('contracts')->where('year_contract', $request['year_contract'])->ignore($id)
		])->validate();
		//$contract = Contract::findOrFail($id);
		$var_is_goz = 4;
		if($request['marketing_goz_reestr'] || $request['procurement_goz_reestr'])
			$var_is_goz = 1;
		else if($request['export_reestr'])
			$var_is_goz = 2;
		else if($request['interfactory_reestr'])
			$var_is_goz = 3;
		$contract->fill([
						'number_pp' => $request['number_pp'],
						'number_contract' => $request['number_contract'],
						'year_contract' => $request['year_contract'],
						'id_goz_contract' => $var_is_goz,
						'id_counterpartie_contract' => $request['id_counterpartie_contract'],
						'name_work_contract' => $request['name_work_contract'],
						'item_contract' => $request['item_contract'] ? $request['item_contract'] : $contract->item_contract,
						'renouncement_contract' => $request['renouncement_contract'] ? 1 : 0,
						'date_renouncement_contract' => $request['date_renouncement_contract'],
						'archive_contract' => $request['archive_contract'] ? 1 : 0,
						// TODO: нужно подправить! Для сохранения пустого значения!
						'document_success_renouncement_reestr' => $request['document_success_renouncement_reestr'] ? $request['document_success_renouncement_reestr'] : $contract->document_success_renouncement_reestr,
						'number_aftair_renouncement_reestr' => $request['number_aftair_renouncement_reestr'] ? $request['number_aftair_renouncement_reestr'] : $contract->number_aftair_renouncement_reestr
		]);
		//dd($contract->getChanges());
		//dd($contract->getMyChanges());
		//$all_dirty = $contract->getMyChanges();
		$all_dirty = JournalController::getMyChanges($contract);
		//dd($all_dirty);
		$contract->save();
		//dd($contract->getChanges());
		$reestr = ReestrContract::firstOrNew(['id_contract_reestr'=>$id]);
		$reestr->fill($request->all());
		if($request['amount_contract_reestr'])
			$reestr->amount_contract_reestr = str_replace(' ','',$reestr->amount_contract_reestr);
		if($request['amount_bank_reestr'])
			$reestr->amount_bank_reestr = str_replace(' ','',$reestr->amount_bank_reestr);
		if($request['amount_begin_reestr'])
			$reestr->amount_begin_reestr = str_replace(' ','',$reestr->amount_begin_reestr);
		if($request['amount_reestr'])
			$reestr->amount_reestr = str_replace(' ','',$reestr->amount_reestr);
		if($request['amount_begin_reestr'])
			$reestr->amount_begin_reestr = str_replace(' ','',$reestr->amount_begin_reestr);
		if($request['nmcd_reestr'])
			$reestr->nmcd_reestr = str_replace(' ','',$reestr->nmcd_reestr);
		if($request['economy_reestr'])
			$reestr->economy_reestr = str_replace(' ','',$reestr->economy_reestr);
		if($request['is_new_reestr'])
			$reestr->fill([
							'application_reestr' => $request['application_reestr'] ? 1 : 0,
							'marketing_reestr' => $request['marketing_reestr'] ? 1 : 0,
							'marketing_goz_reestr' => $request['marketing_goz_reestr'] ? 1 : 0,
							'participation_reestr' => $request['participation_reestr'] ? 1 : 0,
							'marketing_fz_223_reestr' => $request['marketing_fz_223_reestr'] ? 1 : 0,
							'marketing_fz_44_reestr' => $request['marketing_fz_44_reestr'] ? 1 : 0,
							'export_reestr' => $request['export_reestr'] ? 1 : 0,
							'interfactory_reestr' => $request['interfactory_reestr'] ? 1 : 0,
							'procurement_reestr' => $request['procurement_reestr'] ? 1 : 0,
							'single_provider_reestr' => $request['single_provider_reestr'] ? 1 : 0,
							'own_funds_reestr' => $request['own_funds_reestr'] ? 1 : 0,
							'investments_reestr' => $request['investments_reestr'] ? 1 : 0,
							'purchase_reestr' => $request['purchase_reestr'] ? 1 : 0,
							'procurement_fz_223_reestr' => $request['procurement_fz_223_reestr'] ? 1 : 0,
							'procurement_fz_44_reestr' => $request['procurement_fz_44_reestr'] ? 1 : 0,
							'procurement_goz_reestr' => $request['procurement_goz_reestr'] ? 1 : 0,
							'other_reestr' => $request['other_reestr'] ? 1 : 0,
							'mob_reestr' => $request['mob_reestr'] ? 1 : 0,
							//'vat_reestr' => $request['vat_reestr'] ? 1 : 0,
							//'approximate_amount_reestr' => $request['approximate_amount_reestr'] ? 1 : 0,
							//'fixed_amount_reestr' => $request['fixed_amount_reestr'] ? 1 : 0,
							'vat_begin_reestr' => $request['vat_begin_reestr'] ? 1 : 0,
							'approximate_amount_begin_reestr' => $request['approximate_amount_begin_reestr'] ? 1 : 0,
							'fixed_amount_begin_reestr' => $request['fixed_amount_begin_reestr'] ? 1 : 0,
							'prolongation_reestr' => $request['prolongation_reestr'] ? 1 : 0
			]);
		else
			$reestr->fill([
							'oud_original_contract_reestr' => $request['oud_original_contract_reestr'] ? 1 : 0,
							'otd_original_contract_reestr' => $request['otd_original_contract_reestr'] ? 1 : 0,
							'application_reestr' => $request['application_reestr'] ? 1 : 0,
							'marketing_reestr' => $request['marketing_reestr'] ? 1 : 0,
							'procurement_reestr' => $request['procurement_reestr'] ? 1 : 0,
							'investments_reestr' => $request['investments_reestr'] ? 1 : 0,
							'other_reestr' => $request['other_reestr'] ? 1 : 0,
							'cash_order_reestr' => $request['cash_order_reestr'] ? 1 : 0,
							'cash_contract_reestr' => $request['cash_contract_reestr'] ? 1 : 0,
							'non_cash_order_reestr' => $request['non_cash_order_reestr'] ? 1 : 0,
							'non_cash_contract_reestr' => $request['non_cash_contract_reestr'] ? 1 : 0,
							//'vat_reestr' => $request['vat_reestr'] ? 1 : 0,
							//'approximate_amount_reestr' => $request['approximate_amount_reestr'] ? 1 : 0,
							//'fixed_amount_reestr' => $request['fixed_amount_reestr'] ? 1 : 0,
							'vat_begin_reestr' => $request['vat_begin_reestr'] ? 1 : 0,
							'approximate_amount_begin_reestr' => $request['approximate_amount_begin_reestr'] ? 1 : 0,
							'fixed_amount_begin_reestr' => $request['fixed_amount_begin_reestr'] ? 1 : 0,
							'prolongation_reestr' => $request['prolongation_reestr'] ? 1 : 0,
							're_registration_reestr' => $request['re_registration_reestr'] ? 1 : 0,
			]);
		$all_dirty = JournalController::getMyChanges($reestr, $all_dirty);
		$reestr->save();
//dd(json_encode($all_dirty));
		JournalController::store(Auth::User()->id,'Обновление реестра с id контракта = ' . $contract->id . '~' . json_encode($all_dirty));
		return redirect()->back()->with('success', 'Контракт обновлен!');
    }
	
	public function update_state(Request $request, $id)
	{
		$val = Validator::make($request->all(),[
			'id_state' => 'required',
			'new_name_state' => 'required',
			'date_state' => 'required|date',
		])->validate();
		$state = State::findOrFail($request['id_state']);
		$state->fill(['name_state' => $request['new_name_state'],
					'comment_state' => $request['comment_state'],
					'date_state' => $request['date_state'],
		]);
		$state->save();
		return redirect()->back();
	}
	
	//Печать реестра
	public function print_reestr(Request $request)
	{
		if(isset($_GET['real_name_table']))
		{
			$forming_arr_for_print = ContractController::forming_print($request);

			switch($_GET['real_name_table'])
			{
				case 'проекты на закуп за период':
					return view('reestr.print_a', $forming_arr_for_print);
				case 'проекты на сбыт за период':
					return view('reestr.print_a', $forming_arr_for_print);
				case 'заключенные на закуп за период':
					return view('reestr.print_a', $forming_arr_for_print);
				case 'заключенные на сбыт за период':
					return view('reestr.print_a', $forming_arr_for_print);
				case 'не заключенные на закуп за период':
					return view('reestr.print_a', $forming_arr_for_print);
				case 'не заключенные на сбыт за период':
					return view('reestr.print_a', $forming_arr_for_print);
				case 'справка о контрагенте за период':
					return view('reestr.print_a', $forming_arr_for_print);
				case 'справка о контрагентах за период':
					return view('reestr.print_a', $forming_arr_for_print);
				case 'отчет по подразделению за период':
					return view('reestr.print_a', $forming_arr_for_print);
				case 'отчет по подразделению по исполнителю':
					return view('reestr.print_a', $forming_arr_for_print);
				case 'просроченое по подразделению':
					return view('reestr.print_b', $forming_arr_for_print);
				case 'отказы за период':
					return view('reestr.print_c', $forming_arr_for_print);
				case 'пролонгированные за период':
					return view('reestr.print_c', $forming_arr_for_print);
				case 'отчет по иным за период':
					return view('reestr.print_c', $forming_arr_for_print);
				case 'сводная таблица поставщиков':
					return view('reestr.print_d', $forming_arr_for_print);
				case 'сводная таблица заказчиков':
					return view('reestr.print_e', $forming_arr_for_print);
				case 'список заявок на сатадии согласования':
					return view('reestr.print_project_reconciliation', $forming_arr_for_print);
				case 'список заявок без проекта':
					return view('reestr.print_none_project', $forming_arr_for_print);
				case 'список договоров вступивших в силу':
					return view('reestr.print_entry_into_force', $forming_arr_for_print);
				case 'список исполненных договоров':
					return view('reestr.print_complete_contracts', $forming_arr_for_print);
				case 'список договоров за период':
					return view('reestr.print_f', $forming_arr_for_print);
				case 'пролонгированные договора':
					return view('reestr.print_g', $forming_arr_for_print);
				case 'банковские гарантии':
					return view('reestr.print_h', $forming_arr_for_print);
				case 'итоги по реестру':
					return view('reestr.print_i', $forming_arr_for_print);
				case 'итоги по введенным договорам':
					return view('reestr.print_k', $forming_arr_for_print);
				case 'итоги по виду договора':
					return view('reestr.print_l', $forming_arr_for_print);
				case 'справка по виду договоров':
					return view('reestr.print_sp_view_dep', $forming_arr_for_print);
				case 'статистика по количеству':
					return view('reestr.print_statistic_a', $forming_arr_for_print);
				case 'статистика по сумме':
					return view('reestr.print_statistic_b', $forming_arr_for_print);
				case 'действующие договора за год':
					return view('reestr.print_statistic_c', $forming_arr_for_print);
				case 'итоги по действующим':
					return view('reestr.print_statistic_d', $forming_arr_for_print);
				case 'отказы по договорам за год':
					return view('reestr.print_statistic_e', $forming_arr_for_print);
				case 'договора по подразделению':
					return view('reestr.print_all_contract_department', $forming_arr_for_print);
				case 'информация о запросах котировки в электронной форме':
					return view('reestr.print_t', $forming_arr_for_print);
				case 'информация о запросах котировки':
					return view('reestr.print_t', $forming_arr_for_print);
				case 'информация о аукционах в электронной форме':
					return view('reestr.print_t', $forming_arr_for_print);
				case 'список контрактов по инвестициям':
					return view('reestr.print_fz_investments', $forming_arr_for_print);
				case 'сведения о заключенным в рамках':
					return view('reestr.print_sp_ramki', $forming_arr_for_print);
				case 'список заключенных в рамках для контроля исполнения':
					return view('reestr.print_rasch_isp', $forming_arr_for_print);
				case 'справка о крупных сделках':
					return view('reestr.print_y', $forming_arr_for_print);
				case 'отчет о заключенных крупных сделках':
					return view('reestr.print_y', $forming_arr_for_print);
				case 'договоры сданные 223':
					return view('reestr.print_spravka_fz', $forming_arr_for_print);
				case 'договоры сданные 44':
					return view('reestr.print_spravka_fz', $forming_arr_for_print);
				case 'срок исполнения пэо':
					return view('reestr.print_srok', $forming_arr_for_print);
				case 'сведения о количестве и об общей стоимости договоров':
					return view('reestr.print_sv_count_and_amount', $forming_arr_for_print);
				
				// ---- FORMS ----
				case 'форма проекты на сбыт за период':
					return view('reestr.forms.print_1_1', $forming_arr_for_print);
				case 'форма заключенные на сбыт за период':
					return view('reestr.forms.print_1_2', $forming_arr_for_print);
				case 'форма отказы на сбыт за период':
					return view('reestr.forms.print_1_3', $forming_arr_for_print);
				case 'форма банковские гарантии на сбыт за период':
					return view('reestr.forms.print_1_4', $forming_arr_for_print);
				case 'форма отчет по подразделению по исполнителю на сбыт':
					return view('reestr.forms.print_1_5', $forming_arr_for_print);
				case 'форма список исполненных договоров на сбыт':
					return view('reestr.forms.print_1_6', $forming_arr_for_print);
				case 'форма список договоров вступивших в силу на сбыт':
					return view('reestr.forms.print_1_7', $forming_arr_for_print);
				case 'форма проекты на закуп за период':
					return view('reestr.forms.print_2_1', $forming_arr_for_print);
				case 'форма заключенные на закуп за период':
					return view('reestr.forms.print_2_2', $forming_arr_for_print);
				case 'форма отказы на закуп за период':
					return view('reestr.forms.print_2_3', $forming_arr_for_print);	
				case 'форма банковские гарантии на закуп за период':
					return view('reestr.forms.print_2_4', $forming_arr_for_print);
				case 'форма отчет об исполнении с единственным поставщиком':
					return view('reestr.forms.print_2_5', $forming_arr_for_print);
				case 'форма информация о запросах котировки в электронной форме':
					return view('reestr.forms.print_2_6', $forming_arr_for_print);
				case 'форма отчет об исполнении на закуп по итогам электронного аукциона':
					return view('reestr.forms.print_2_7', $forming_arr_for_print);
				case 'форма отчет по подразделению по исполнителю на закуп':
					return view('reestr.forms.print_2_8', $forming_arr_for_print);
				case 'форма справка о контрагентах на закуп за период':
					return view('reestr.forms.print_3_1', $forming_arr_for_print);
				case 'форма справка о контрагентах на сбыт за период':
					return view('reestr.forms.print_3_2', $forming_arr_for_print);
				// TODO: form 3.3.
				// TODO: form 3.4.
				case 'форма договоры сданные 223':
					return view('reestr.forms.print_4_1', $forming_arr_for_print);
				case 'форма договоры сданные 44':
					return view('reestr.forms.print_4_2', $forming_arr_for_print);
				// TODO: form 4.3.
				// TODO: form 4.4.
				case 'форма пролонгированные за период':
					return view('reestr.forms.print_5_1', $forming_arr_for_print);
				case 'форма отчет по иным за период':
					return view('reestr.forms.print_5_2', $forming_arr_for_print);
				case 'форма справка о крупных сделках':
					return view('reestr.forms.print_5_3', $forming_arr_for_print);
				// TODO: form 5.4.
				// TODO: form 5.4.1.
				case 'форма договора по подразделению':
					return view('reestr.forms.print_5_5', $forming_arr_for_print);
				case 'форма статистика по количеству':
					return view('reestr.forms.print_5_6', $forming_arr_for_print);
				case 'форма итоги по действующим':
					return view('reestr.forms.print_5_7', $forming_arr_for_print);
				case 'форма статистика по сумме':
					return view('reestr.forms.print_5_8', $forming_arr_for_print);
				case 'форма просроченое по подразделению':
					return view('reestr.forms.print_5_9', $forming_arr_for_print);
				case 'форма итоги по реестру':
					return view('reestr.forms.print_5_10', $forming_arr_for_print);
				case 'форма итоги по введенным договорам':
					return view('reestr.forms.print_5_11', $forming_arr_for_print);
				case 'форма справка по договорам ПЭО':
					return view('reestr.forms.print_5_12', $forming_arr_for_print);
				// TODO: form 5.13.
				case 'форма участник в закупках 223':
					return view('reestr.forms.print_6_1', $forming_arr_for_print);
				case 'форма участник в закупках 44':
					return view('reestr.forms.print_6_2', $forming_arr_for_print);
				case 'справка по срокам оплаты':
					return view('reestr.forms.print_7_1', $forming_arr_for_print);
					
					
					
				default:
					dd($_GET['real_name_table']);
					return redirect()->back()->with('error',  $forming_arr_for_print);
			}
		}
		return redirect()->back()->with('error', 'Какая-то ошибка!');
	}
	
	//Формирование реестра
	public function forming_print(Request $request)
	{
		if(isset($request['real_name_table']))
		{
			$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
			$lider = 'Гуринова Н.М.';
			$period1 = isset($request['date_begin']) ? DATE('Y-m-d', strtotime($request['date_begin'])) : date('Y', time()) . '-01-01';
			$period2 = isset($request['date_end']) ? DATE('Y-m-d', strtotime($request['date_end'])) : date('Y-m-d', time());
			$departments = Department::select()->orderBy('index_department','asc')->get();
			switch($request['real_name_table'])
			{
				case 'проекты на закуп за период':
					$text1 = 'Справка: проекты Договоров/Контрактов на закуп';
					//dd(DATE('Y-m-d', strtotime($period1)));
					/*$contracts = Contract::select('contracts.id','contracts.number_contract','number_counterpartie_contract_reestr','id_counterpartie_contract','item_contract',
													'date_maturity_reestr','amount_contract_reestr','payment_order_reestr','date_contract_reestr')
										->join('reestr_contracts','contracts.id','reestr_contracts.id_contract_reestr')
										->whereBetween('date_registration_project_reestr', array(DATE('Y-m-d', strtotime($period1)),DATE('Y-m-d', strtotime($period2))))
										->get();*/
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr,amount_reestr,prepayment_order_reestr,score_order_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
												WHERE number_contract LIKE '%‐" . $fil_dep . "‐%' AND procurement_reestr=1 
												AND date_entry_into_force_reestr IS NULL 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'contracts'=>$contracts,'lider'=>$lider];
				case 'проекты на сбыт за период':
					$text1 = 'Справка: проекты Договоров/Контрактов на сбыт';
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr,amount_reestr,prepayment_order_reestr,score_order_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr WHERE number_contract LIKE '%‐" . $fil_dep . "‐%' 
												AND date_entry_into_force_reestr IS NULL 
												AND marketing_reestr=1 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'contracts'=>$contracts,'lider'=>$lider];
				case 'заключенные на закуп за период':
					$text1 = 'Справка по подразделению на закуп: заключенные Договора/Контракты';
					$result = [];
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
					// AND date_signing_contract_reestr IS NOT NULL AND date_signing_contract_counterpartie_reestr IS NOT NULL 
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,amount_reestr,prepayment_order_reestr,score_order_reestr,payment_order_reestr,date_contract_reestr,
												is_sip_contract,executor_contract_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr WHERE number_contract LIKE '%‐" . $fil_dep . "‐%' 
												AND procurement_reestr=1 
												AND (STR_TO_DATE(date_entry_into_force_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
						
						// Исполнитель
						if($contract->is_sip_contract == 1){
							$pr_executor = Curator::select()->where('id', $contract->executor_contract_reestr)->first();
							if($pr_executor != null)
								$contract->executor_contract_reestr = $pr_executor->FIO;
						}
						
						//Распределяем по подразделениям
						$split_str = explode('‐',$contract->number_contract);
						if(count($split_str) > 1)
							foreach($departments as $department)
							{
								if($split_str[1] == $department->index_department)
								{
									if(!in_array($department->name_department,array_keys($result)))
										$result += [$department->name_department => [$contract]];
									else
										array_push($result[$department->name_department],$contract);
									break;
								}
							}
						else
							$result += ['' => [$contract]];
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'result'=>$result,'count_contracts'=>count($contracts),'lider'=>$lider];
				case 'заключенные на сбыт за период':
					$text1 = 'Справка по подразделению на сбыт: заключенные Договора/Контракты';
					$result = [];
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
					// AND date_signing_contract_reestr IS NOT NULL AND date_signing_contract_counterpartie_reestr IS NOT NULL 
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr,amount_reestr,prepayment_order_reestr,score_order_reestr,
												is_sip_contract, executor_contract_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr WHERE number_contract LIKE '%‐" . $fil_dep . "‐%' 
												AND marketing_reestr=1 
												AND (STR_TO_DATE(date_entry_into_force_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
						
						// Исполнитель
						if($contract->is_sip_contract == 1){
							$pr_executor = Curator::select()->where('id', $contract->executor_contract_reestr)->first();
							if($pr_executor != null)
								$contract->executor_contract_reestr = $pr_executor->FIO;
						}
						
						//Распределяем по подразделениям
						$split_str = explode('‐',$contract->number_contract);
						if(count($split_str) > 1)
							foreach($departments as $department)
							{
								if($split_str[1] == $department->index_department)
								{
									if(!in_array($department->name_department,array_keys($result)))
										$result += [$department->name_department => [$contract]];
									else
										array_push($result[$department->name_department],$contract);
									break;
								}
							}
						else
							$result += ['' => [$contract]];
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'result'=>$result,'count_contracts'=>count($contracts),'lider'=>$lider];
				case 'не заключенные на закуп за период':
					$text1 = 'Справка по подразделению на закуп: не заключенные Договора/Контракты';
					$result = [];
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr,amount_reestr,prepayment_order_reestr,score_order_reestr,
												executor_contract_reestr, is_sip_contract 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr WHERE number_contract LIKE '%‐" . $fil_dep . "‐%' 
												AND procurement_reestr=1 AND (date_signing_contract_reestr IS NULL OR date_signing_contract_counterpartie_reestr IS NULL) 
												AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
						
						// Исполнитель
						if($contract->is_sip_contract == 1){
							$pr_executor = Curator::select()->where('id', $contract->executor_contract_reestr)->first();
							if($pr_executor != null)
								$contract->executor_contract_reestr = $pr_executor->FIO;
						}
						
						//Распределяем по подразделениям
						$split_str = explode('‐',$contract->number_contract);
						if(count($split_str) > 1)
							foreach($departments as $department)
							{
								if($split_str[1] == $department->index_department)
								{
									if(!in_array($department->name_department,array_keys($result)))
										$result += [$department->name_department => [$contract]];
									else
										array_push($result[$department->name_department],$contract);
									break;
								}
							}
						else
							$result += ['' => [$contract]];
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'result'=>$result,'count_contracts'=>count($contracts),'lider'=>$lider];
				case 'не заключенные на сбыт за период':
					$text1 = 'Справка по подразделению на сбыт: не заключенные Договора/Контракты';
					$result = [];
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,amount_reestr,payment_order_reestr,date_contract_reestr,amount_reestr,prepayment_order_reestr,
												score_order_reestr, is_sip_contract, executor_contract_reestr FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
												WHERE number_contract LIKE '%‐" . $fil_dep . "‐%' AND marketing_reestr=1 
												AND (date_signing_contract_reestr IS NULL OR date_signing_contract_counterpartie_reestr IS NULL) 
												AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
						
						// Исполнитель
						if($contract->is_sip_contract == 1){
							$pr_executor = Curator::select()->where('id', $contract->executor_contract_reestr)->first();
							if($pr_executor != null)
								$contract->executor_contract_reestr = $pr_executor->FIO;
						}
						
						//Распределяем по подразделениям
						$split_str = explode('‐',$contract->number_contract);
						if(count($split_str) > 1)
							foreach($departments as $department)
							{
								if($split_str[1] == $department->index_department)
								{
									if(!in_array($department->name_department,array_keys($result)))
										$result += [$department->name_department => [$contract]];
									else
										array_push($result[$department->name_department],$contract);
									break;
								}
							}
						else
							$result += ['' => [$contract]];
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'result'=>$result,'count_contracts'=>count($contracts),'lider'=>$lider];
				case 'справка о контрагенте за период':
					$text1 = 'Справка о Договорах/Контрактов по контрагенту';
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr,amount_reestr,prepayment_order_reestr,score_order_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr WHERE id_counterpartie_contract=" . $request['counterpartie'] . " 
												AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					$counterpartie = Counterpartie::select('name','name_full')->where('id',$request['counterpartie'])->first();
					foreach($contracts as $contract)
					{
						$contract->counterpartie_name = $counterpartie->name;
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'contracts'=>$contracts,'counterpartie_full_name'=>$counterpartie->name_full,'lider'=>$lider];
				case 'справка о контрагентах за период':
					$text1 = 'Справка о Договорах/Контрактах по контрагентам';
					$result = [];
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr,amount_reestr,prepayment_order_reestr,score_order_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr WHERE number_contract LIKE '%‐" . $fil_dep . "‐%' 
												AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								$contract->counterpartie_name_full = $counter->name_full;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
						
						//Распределяем по контрагентам
						if(!in_array($contract->counterpartie_name_full,array_keys($result)))
							$result += [$contract->counterpartie_name_full => [$contract]];
						else
							array_push($result[$contract->counterpartie_name_full],$contract);
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'result'=>$result,'count_contracts'=>count($contracts),'lider'=>$lider];
				case 'отчет по подразделению за период':
					$text1 = 'Отчет о Договорах/Контрактов по подразделению';
					$department = Department::select('index_department','name_department')->where('id',$request['department'])->first();
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr,amount_reestr,prepayment_order_reestr,score_order_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
												WHERE number_contract LIKE '%‐" . $department->index_department . "‐%' 
												AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								$contract->counterpartie_name_full = $counter->name_full;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'contracts'=>$contracts,'department_name'=>$department->name_department,'lider'=>$lider];
				case 'отчет по подразделению по исполнителю':
					$text1 = 'Отчет о Договорах/Контрактов по подразделению по Исполнителю';
					$result = [];
					$department = Department::select('index_department','name_department')->where('id',$request['department'])->first();
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr,amount_reestr,prepayment_order_reestr,score_order_reestr,
												oud_curators.FIO as executor,executor_contract_reestr,executor_reestr,is_sip_contract 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr LEFT JOIN oud_curators ON executor_reestr=oud_curators.id 
												WHERE number_contract LIKE '%‐" . $department->index_department . "‐%' 
												AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
						
						if($contract->is_sip_contract == 1){
							$pr_executor = Curator::select()->where('id', $contract->executor_contract_reestr)->first();
							if($pr_executor != null)
								$contract->executor_contract_reestr = $pr_executor->FIO;
						}
						
						//Распределяем по исполнителям
						$key_executor = str_replace('.', '', str_replace(' ', '', $contract->executor_contract_reestr));
						if(!in_array($key_executor,array_keys($result)))
							$result += [$key_executor => [$contract]];
						else
							array_push($result[$key_executor],$contract);
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'result'=>$result,'count_contracts'=>count($contracts),'lider'=>$lider];
				case 'просроченое по подразделению':
					$department = Department::select('index_department','name_department')->where('id',$request['department'])->first();
					if($department != null)
						$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
													date_maturity_reestr,amount_contract_reestr,amount_reestr,payment_order_reestr,date_contract_reestr,oud_curators.FIO as executor,executor_contract_reestr,executor_reestr,is_sip_contract FROM contracts JOIN reestr_contracts ON 
													contracts.id=reestr_contracts.id_contract_reestr LEFT JOIN oud_curators ON executor_reestr=oud_curators.id WHERE number_contract LIKE '%‐" . $department->index_department . "‐%' 
													AND ((renouncement_contract IS NULL OR renouncement_contract=0) AND date_save_contract_reestr IS NULL AND date_registration_project_reestr IS NOT NULL AND ".time()."-STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') > 2592000)
													AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
													AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
													ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					else
						$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
													date_maturity_reestr,amount_contract_reestr,amount_reestr,payment_order_reestr,date_contract_reestr,oud_curators.FIO as executor,executor_contract_reestr,executor_reestr,is_sip_contract FROM contracts JOIN reestr_contracts ON 
													contracts.id=reestr_contracts.id_contract_reestr LEFT JOIN oud_curators ON executor_reestr=oud_curators.id WHERE 
													((renouncement_contract IS NULL OR renouncement_contract=0) AND date_save_contract_reestr IS NULL AND date_registration_project_reestr IS NOT NULL AND " . time() . "-STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') > 2592000)
													AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
													AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
													ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					$result = [];
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name_full;
								break;
							}
						}
						
						if($contract->is_sip_contract == 1){
							$pr_executor = Curator::select()->where('id', $contract->executor_contract_reestr)->first();
							if($pr_executor != null)
								$contract->executor_contract_reestr = $pr_executor->FIO;
						}
						
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
						
						//Распределяем по подразделениям
						$split_str = explode('‐',$contract->number_contract);
						if(count($split_str) > 1)
							foreach($departments as $department)
							{
								if($split_str[1] == $department->index_department)
								{
									//Записываем в контракт информацию о том кто начальник
									$contract->lider_department = User::select('surname','name','patronymic','position_department')->where('id',$department->lider_department)->first();
									if(!in_array($department->name_department,array_keys($result)))
										$result += [$department->name_department => [$contract]];
									else
										array_push($result[$department->name_department],$contract);
									break;
								}
							}
						else
							$result += ['' => [$contract]];
					}
					return ['result'=>$result,'count_contracts'=>count($contracts),'lider'=>$lider];
				case 'отказы за период':
					$text1 = 'Отказы по Договорам/Контрактам, зарегистрированным отделом управления договорами';
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr WHERE renouncement_contract=1 
												AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								$contract->counterpartie_name_full = $counter->name_full;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'contracts'=>$contracts,'lider'=>$lider];
				case 'пролонгированные за период':
					$text1 = 'Отчеты по пролонгированным Договорам/Контрактам, зарегистированным отделом управления договорами';
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
												WHERE prolongation_reestr=1 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								$contract->counterpartie_name_full = $counter->name_full;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'contracts'=>$contracts,'lider'=>$lider];
				case 'отчет по иным за период':
					$text1 = 'Отчет по ИНЫМ Договорам/Контрактам';
					$result = [];
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,name_view_contract,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
												JOIN view_contracts ON reestr_contracts.id_view_contract=view_contracts.id 
												WHERE other_reestr=1 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								$contract->counterpartie_name_full = $counter->name_full;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
						
						//Распределяем по видам договоров
						if(!in_array($contract->name_view_contract,array_keys($result)))
							$result += [$contract->name_view_contract => [$contract]];
						else
							array_push($result[$contract->name_view_contract],$contract);
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'result'=>$result,'count_contracts'=>count($contracts),'lider'=>$lider];
				case 'сводная таблица поставщиков':
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,oud_curators.FIO as executor,bank_reestr,amount_bank_reestr,economy_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr LEFT JOIN oud_curators ON executor_reestr=oud_curators.id 
												WHERE investments_reestr=1 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								$contract->counterpartie_name_full = $counter->name_full;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
					}
					return ['period1'=>$request['date_begin'],'period2'=>$request['date_end'],'contracts'=>$contracts];
				case 'сводная таблица заказчиков':
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,oud_curators.FIO as executor,bank_reestr,amount_bank_reestr,economy_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr,nmcd_reestr,prepayment_order_reestr,score_order_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr LEFT JOIN oud_curators ON executor_reestr=oud_curators.id 
												WHERE marketing_reestr=1 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								$contract->counterpartie_name_full = $counter->name_full;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
					}
					return ['period1'=>$request['date_begin'],'period2'=>$request['date_end'],'contracts'=>$contracts];
				case 'список заявок на сатадии согласования':
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,is_sip_contract,date_registration_project_reestr,id_counterpartie_contract,item_contract,
											app_outgoing_number_reestr,app_incoming_number_reestr,amount_contract_reestr,executor_contract_reestr,executor_reestr 
											FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
											WHERE number_contract LIKE '%‐" . $fil_dep . "‐%' AND date_registration_application_reestr IS NULL 
											AND (app_outgoing_number_reestr IS NOT NULL OR app_incoming_number_reestr IS NOT NULL) 
											AND (date_signing_contract_reestr IS NULL OR date_signing_contract_counterpartie_reestr IS NULL) 
											AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
											AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
											ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract){
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						if($contract->is_sip_contract == 1){
							$pr_executor = Curator::select()->where('id', $contract->executor_contract_reestr)->first();
							if($pr_executor != null)
								$contract->executor_contract_reestr = $pr_executor->FIO;
						}
					}
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'contracts'=>$contracts,
								'lider'=>$lider];
				case 'список заявок без проекта':
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,is_sip_contract,date_registration_project_reestr,id_counterpartie_contract,
											item_contract,app_outgoing_number_reestr,app_incoming_number_reestr,amount_contract_reestr,executor_contract_reestr,executor_reestr,date_contract_on_first_reestr,
											date_maturity_reestr,amount_reestr FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
											WHERE number_contract LIKE '%‐" . $fil_dep . "‐%' AND (app_outgoing_number_reestr IS NOT NULL OR app_incoming_number_reestr IS NOT NULL) 
											AND date_registration_application_reestr IS NOT NULL AND (STR_TO_DATE(date_registration_application_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
											AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) AND is_sip_contract=1 
											ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					$result = [];
					foreach($contracts as $contract){
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						if($contract->is_sip_contract == 1){
							$pr_executor = Curator::select()->where('id', $contract->executor_contract_reestr)->first();
							if($pr_executor != null)
								$contract->executor_contract_reestr = $pr_executor->FIO;
						}
						if(!in_array($contract->counterpartie_name,array_keys($result)))
							$result += [$contract->counterpartie_name => [$contract]];
						else
							array_push($result[$contract->counterpartie_name],$contract);
					}
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'result'=>$result,
								'lider'=>$lider];
				case 'список договоров вступивших в силу':
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,id_counterpartie_contract,is_sip_contract,executor_contract_reestr,
											date_contract_on_first_reestr,item_contract,amount_contract_reestr,amount_reestr,date_maturity_reestr,date_signing_contract_reestr,
											date_signing_contract_counterpartie_reestr,date_entry_into_force_reestr,date_save_contract_reestr 
											FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
											WHERE number_contract LIKE '%‐" . $fil_dep . "‐%' AND (date_entry_into_force_reestr IS NOT NULL) 
											AND (STR_TO_DATE(date_entry_into_force_reestr, '%d.%m.%Y') <= '" . $period2 . "') 
											AND ((STR_TO_DATE(date_signing_contract_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "' OR STR_TO_DATE(date_signing_contract_counterpartie_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')) 
											AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
											AND is_sip_contract=1 
											ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					$result = [];
					foreach($contracts as $contract){
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						if($contract->is_sip_contract == 1){
							$pr_executor = Curator::select()->where('id', $contract->executor_contract_reestr)->first();
							if($pr_executor != null)
								$contract->executor_contract_reestr = $pr_executor->FIO;
						}
						if(!in_array($contract->counterpartie_name,array_keys($result)))
							$result += [$contract->counterpartie_name => [$contract]];
						else
							array_push($result[$contract->counterpartie_name],$contract);
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
					}
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'result'=>$result,
								'lider'=>$lider];
				case 'список исполненных договоров':
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,id_counterpartie_contract,is_sip_contract,executor_contract_reestr,
											date_contract_on_first_reestr,item_contract,amount_contract_reestr,amount_reestr,date_maturity_reestr,date_signing_contract_reestr,
											date_signing_contract_counterpartie_reestr,date_entry_into_force_reestr,date_save_contract_reestr 
											FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
											WHERE number_contract LIKE '%‐" . $fil_dep . "‐%' AND (date_entry_into_force_reestr IS NOT NULL) 
											AND (STR_TO_DATE(date_entry_into_force_reestr, '%d.%m.%Y') <= '" . $period2 . "') 
											AND ((STR_TO_DATE(date_signing_contract_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "' OR STR_TO_DATE(date_signing_contract_counterpartie_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')) 
											AND is_sip_contract=1 
											ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					$result = [];
					foreach($contracts as $contract){
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
						
						// Проверка на исполнение по сумме оплат
						if($amount_payments + $amount_prepayments - $amount_invoices - $amount_returns != 0)
							continue;

						// Проверка на исполнение по статусу работы
						$states = State::select(['*'])->where('id_contract', $contract->id)->where('is_work_state', 1)->get();
						if(count($states) > 0){
							if($states[count($states) - 1]->name_state != 'Выполнен')
								continue;
						}else
							continue;
						
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						if($contract->is_sip_contract == 1){
							$pr_executor = Curator::select()->where('id', $contract->executor_contract_reestr)->first();
							if($pr_executor != null)
								$contract->executor_contract_reestr = $pr_executor->FIO;
						}
						if(!in_array($contract->counterpartie_name,array_keys($result)))
							$result += [$contract->counterpartie_name => [$contract]];
						else
							array_push($result[$contract->counterpartie_name],$contract);
					}
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'result'=>$result,
								'lider'=>$lider];
				case 'список договоров за период':
					$text1 = 'Список Договоров/Контрактов по Подразделениям';
					$result = [];
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_registration_project_reestr,date_entry_into_force_reestr,date_save_contract_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
												WHERE number_contract LIKE '%‐" . $fil_dep . "‐%' 
												AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");

					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}

						//Распределяем по подразделениям
						$split_str = explode('‐',$contract->number_contract);
						if(count($split_str) > 1)
							foreach($departments as $department)
							{
								if($split_str[1] == $department->index_department)
								{
									if(!in_array($department->name_department,array_keys($result)))
										$result += [$department->name_department => [$contract]];
									else
										array_push($result[$department->name_department],$contract);
									break;
								}
							}
						else
							$result += ['' => [$contract]];
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'result'=>$result,'count_contracts'=>count($contracts),'lider'=>$lider];
				case 'пролонгированные договора':
					$text1 = 'Пролонгированные Договоры/Контракты';
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_registration_project_reestr,date_contract_reestr,amount_contract_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr 
												FROM contracts LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
												WHERE prolongation_reestr=1 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'contracts'=>$contracts,'count_contracts'=>count($contracts),'lider'=>$lider];
				case 'банковские гарантии':
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,amount_bank_reestr,date_bank_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr 
												FROM contracts LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
												WHERE (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND amount_bank_reestr IS NOT NULL AND amount_bank_reestr != '' 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
					}
					return ['period1'=>$request['date_begin'],'period2'=>$request['date_end'],'contracts'=>$contracts,'count_contracts'=>count($contracts),'lider'=>$lider];
				case 'итоги по реестру':
					$proekt_t = 0;
					$registr_t = 0;
					$break_t = 0;
					$proekt_p = 0;
					$registr_p = 0;
					$break_p = 0;
					foreach($departments as $department)
					{
						$t_proekt_count_contracts = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE contracts.year_contract='" . date('Y',time()) . "' AND contracts.number_contract like '%‐" . $department->index_department . "‐%' AND (date_signing_contract_reestr IS NULL OR date_signing_contract_counterpartie_reestr IS NULL) AND renouncement_contract=0 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$t_registr_count_contracts = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE contracts.year_contract='" . date('Y',time()) . "' AND contracts.number_contract like '%‐" . $department->index_department . "‐%' AND (date_signing_contract_reestr IS NOT NULL AND date_signing_contract_counterpartie_reestr IS NOT NULL) AND renouncement_contract=0 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						//$t_break_count_contracts = Contract::select('id')->where('year_contract', date('Y',time()))->where('renouncement_contract', 1)->count();
						$t_break_count_contracts = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE contracts.year_contract='" . date('Y',time()) . "' AND contracts.number_contract like '%‐" . $department->index_department . "‐%' AND renouncement_contract=1 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$p_proekt_count_contracts = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE contracts.year_contract!='" . date('Y',time()) . "' AND contracts.number_contract like '%‐" . $department->index_department . "‐%' AND (date_signing_contract_reestr IS NULL OR date_signing_contract_counterpartie_reestr IS NULL) AND renouncement_contract=0 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$p_registr_count_contracts = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE contracts.year_contract!='" . date('Y',time()) . "' AND contracts.number_contract like '%‐" . $department->index_department . "‐%' AND (date_signing_contract_reestr IS NOT NULL AND date_signing_contract_counterpartie_reestr IS NOT NULL) AND renouncement_contract=0 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$p_break_count_contracts = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE contracts.year_contract!='" . date('Y',time()) . "' AND contracts.number_contract like '%‐" . $department->index_department . "‐%' AND renouncement_contract=1 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						
						$proekt_t += $t_proekt_count_contracts;
						$registr_t += $t_registr_count_contracts;
						$break_t += $t_break_count_contracts;
						$proekt_p += $p_proekt_count_contracts;
						$registr_p += $p_registr_count_contracts;
						$break_p += $p_break_count_contracts;
						
						$department->t_all_count_contracts = $t_proekt_count_contracts + $t_registr_count_contracts + $t_break_count_contracts;
						$department->t_proekt_count_contracts = $t_proekt_count_contracts;
						$department->t_registr_count_contracts = $t_registr_count_contracts;
						$department->t_break_count_contracts = $t_break_count_contracts;
						
						$department->p_all_count_contracts = $p_proekt_count_contracts + $p_registr_count_contracts + $p_break_count_contracts;
						$department->p_proekt_count_contracts = $p_proekt_count_contracts;
						$department->p_registr_count_contracts = $p_registr_count_contracts;
						$department->p_break_count_contracts = $p_break_count_contracts;
						//break;
					}
					//dd($departments);
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'departments'=>$departments,
								'proekt_t'=>$proekt_t,
								'registr_t'=>$registr_t,
								'break_t'=>$break_t,
								'proekt_p'=>$proekt_p,
								'registr_p'=>$registr_p,
								'break_p'=>$break_p,
								'lider'=>$lider];
				case 'итоги по введенным договорам':
					$proekt_count_contracts = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE (STR_TO_DATE(date_entry_into_force_reestr,'%d.%m.%Y') > '" . $period2 . "' OR date_entry_into_force_reestr IS NULL) AND renouncement_contract=0 AND archive_contract=0 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
					$registr_count_contracts = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE (STR_TO_DATE(date_entry_into_force_reestr,'%d.%m.%Y') <= '" . $period2 . "') AND renouncement_contract=0 AND archive_contract=0 AND (STR_TO_DATE(date_entry_into_force_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
					$break_count_contracts = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE renouncement_contract=1 AND (STR_TO_DATE(date_renouncement_contract,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
					$arhive_count_contracts = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE archive_contract=1 AND renouncement_contract=0 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'proekt_count_contracts'=>$proekt_count_contracts,
								'registr_count_contracts'=>$registr_count_contracts,
								'break_count_contracts'=>$break_count_contracts,
								'arhive_count_contracts'=>$arhive_count_contracts,
								'lider'=>$lider];
				case 'итоги по виду договора':
					$result = [];
					$years = DB::SELECT('SELECT year_contract FROM contracts GROUP BY year_contract ORDER BY year_contract DESC');
					$view_contract = ViewContract::select('*')->where('id', $request['view'])->first();
					foreach($years as $year)
					{
						$count_contracts = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE id_view_contract='" . $_GET['view'] . "' AND year_contract='" . $year->year_contract . "' AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL)");
						if(count($count_contracts) > 0)
							$result += [$year->year_contract=>$count_contracts[0]->count];
					}
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'result'=>$result,
								'view_contract'=>$view_contract,
								'lider'=>$lider];
				case 'справка по виду договоров':
					$view_contract_str = "contracts.id_counterpartie_contract";
					$view_contract_equal = ">";
					$view_contract = '';
					if(isset($request['view']))
						if($request['view'] != 'Все виды работ')
						{
							$view_contract = $request['view'];
							$view_contract_str = "view_contracts.id";
							$view_contract_equal = "=";
						}
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
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
													'reestr_contracts.amount_reestr',
													'reestr_contracts.amount_contract_reestr',
													'reestr_contracts.date_maturity_date_reestr',
													'reestr_contracts.date_maturity_reestr',
													'reestr_contracts.number_counterpartie_contract_reestr',
													DB::raw('CAST(number_pp as UNSIGNED) as cast_number_pp'),
													'reestr_contracts.date_contract_reestr',
													'reestr_contracts.date_entry_into_force_reestr',
													'executor_contract_reestr',
													'is_sip_contract'])
									->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
									->join('view_contracts', 'reestr_contracts.id_view_contract', '=', 'view_contracts.id')
									->join('goz_works', 'contracts.id_goz_contract', '=', 'goz_works.id')
									->where('number_contract', 'LIKE', '%‐' . $fil_dep . '‐%')
									->where('contracts.id_counterpartie_contract','>','-1')
									->where($view_contract_str, $view_contract_equal, $view_contract)
									->where('archive_contract', 0)
									->orderBy('year_contract', 'asc')
									->orderBy('cast_number_pp', 'asc')
									->get();
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter)
							if($contract->id_counterpartie_contract == $counter->id)
								$contract->name_counterpartie_contract = $counter->name;

						if($contract->is_sip_contract == 1){
							$pr_executor = Curator::select()->where('id', $contract->executor_contract_reestr)->first();
							if($pr_executor != null)
								$contract->executor_contract_reestr = $pr_executor->FIO;
						}

						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
					}
					return ['contracts'=>$contracts];
				case 'статистика по количеству':
					foreach($departments as $department)
					{
						$procurement_count = DB::SELECT("SELECT count(*) as count FROM statics_count_contract WHERE number_contract like '%‐" . $department->index_department . "‐%' AND procurement_reestr=1 AND renouncement_contract=0 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$department->procurement_count = $procurement_count;
						$marketing_count = DB::SELECT("SELECT count(*) as count FROM statics_count_contract WHERE number_contract like '%‐" . $department->index_department . "‐%' AND marketing_reestr=1 AND renouncement_contract=0 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$department->marketing_count = $marketing_count;
						$procurement_marketing_count = DB::SELECT("SELECT count(*) as count FROM statics_count_contract WHERE number_contract like '%‐" . $department->index_department . "‐%' AND procurement_reestr=1 AND marketing_reestr=1 AND renouncement_contract=0 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$department->procurement_marketing_count = $procurement_marketing_count;
						$other_count = DB::SELECT("SELECT count(*) as count FROM statics_count_contract WHERE number_contract like '%‐" . $department->index_department . "‐%' AND other_reestr=1 AND renouncement_contract=0 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$department->other_count = $other_count;
						$break_count = DB::SELECT("SELECT count(*) as count FROM statics_count_contract WHERE number_contract like '%‐" . $department->index_department . "‐%' AND renouncement_contract=1 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$department->break_count = $break_count;
						$department->all_count = $procurement_count + $marketing_count + $procurement_marketing_count + $other_count + $break_count;
					}
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'departments'=>$departments,
								'lider'=>$lider];
				case 'статистика по сумме':
					foreach($departments as $department)
					{
						$count_100 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE contracts.number_contract like '%‐" . $department->index_department . "‐%' AND procurement_reestr=1 AND amount_reestr<100000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$department->count_100 = $count_100;
						$count_300 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE contracts.number_contract like '%‐" . $department->index_department . "‐%' AND procurement_reestr=1 AND amount_reestr<500000 AND amount_reestr>100000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$department->count_300 = $count_300;
						$count_700 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE contracts.number_contract like '%‐" . $department->index_department . "‐%' AND procurement_reestr=1 AND amount_reestr<1000000 AND amount_reestr>500000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$department->count_700 = $count_700;
						$count_lyam = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE contracts.number_contract like '%‐" . $department->index_department . "‐%' AND procurement_reestr=1 AND amount_reestr>1000000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$department->count_lyam = $count_lyam;
						$count_null = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE contracts.number_contract like '%‐" . $department->index_department . "‐%' AND procurement_reestr=1 AND amount_reestr IS NULL AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$department->count_null = $count_null;
						$department->all_count = $count_100 + $count_300 + $count_700 + $count_lyam + $count_null;
					}
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'departments'=>$departments,
								'lider'=>$lider];
				case 'действующие договора за год':
						//Договора до 100 к руб
						$m_p_count_100 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) AND marketing_reestr=1 AND procurement_reestr=1 AND other_reestr=0 AND amount_reestr<100000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$m_count_100 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) AND marketing_reestr=1 AND other_reestr=0 AND amount_reestr<100000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$p_count_100 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) AND procurement_reestr=1 AND other_reestr=0 AND amount_reestr<100000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$o_count_100 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) AND other_reestr=1 AND amount_reestr<100000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						//Договора от 100 до 500 к руб
						$m_p_count_300 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) AND marketing_reestr=1 AND procurement_reestr=1 AND other_reestr=0 AND amount_reestr<500000 AND amount_reestr>100000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$m_count_300 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) AND marketing_reestr=1 AND other_reestr=0 AND amount_reestr<500000 AND amount_reestr>100000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$p_count_300 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) AND procurement_reestr=1 AND other_reestr=0 AND amount_reestr<500000 AND amount_reestr>100000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$o_count_300 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) AND other_reestr=1 AND amount_reestr<500000 AND amount_reestr>100000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						//Договора от 500к - 1м руб
						$m_p_count_700 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) AND marketing_reestr=1 AND procurement_reestr=1 AND other_reestr=0 AND amount_reestr<1000000 AND amount_reestr>500000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$m_count_700 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) AND marketing_reestr=1 AND other_reestr=0 AND amount_reestr<1000000 AND amount_reestr>500000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$p_count_700 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) AND procurement_reestr=1 AND other_reestr=0 AND amount_reestr<1000000 AND amount_reestr>500000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$o_count_700 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) AND other_reestr=1 AND amount_reestr<1000000 AND amount_reestr>500000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						//Договора свыше 1 м руб
						$m_p_count_lyam = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) AND marketing_reestr=1 AND procurement_reestr=1 AND other_reestr=0 AND amount_reestr>1000000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$m_count_lyam = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) AND marketing_reestr=1 AND other_reestr=0 AND amount_reestr>1000000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$p_count_lyam = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) AND procurement_reestr=1 AND other_reestr=0 AND amount_reestr>1000000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$o_count_lyam = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) AND other_reestr=1 AND amount_reestr>1000000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						//Договора без конкретной суммы
						$m_p_count_null = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) AND marketing_reestr=1 AND procurement_reestr=1 AND other_reestr=0 AND amount_reestr IS NULL AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$m_count_null = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) AND marketing_reestr=1 AND other_reestr=0 AND amount_reestr IS NULL AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$p_count_null = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) AND procurement_reestr=1 AND other_reestr=0 AND amount_reestr IS NULL AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$o_count_null = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) AND other_reestr=1 AND amount_reestr IS NULL AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						//Всего
						$m_p_count_count = $m_p_count_100 + $m_p_count_300 + $m_p_count_700 + $m_p_count_lyam + $m_p_count_null;
						$m_count_count = $m_count_100 + $m_count_300 + $m_count_700 + $m_count_null + $m_count_null;
						$p_count_count = $p_count_100 + $p_count_300 + $p_count_700 + $p_count_lyam + $p_count_null;
						$o_count_count = $o_count_100 + $o_count_300 + $o_count_700 + $o_count_lyam + $o_count_null;
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'm_p_count_100'=>$m_p_count_100,
								'm_count_100'=>$m_count_100,
								'p_count_100'=>$p_count_100,
								'o_count_100'=>$o_count_100,
								'm_p_count_300'=>$m_p_count_300,
								'm_count_300'=>$m_count_300,
								'p_count_300'=>$p_count_300,
								'o_count_300'=>$o_count_300,
								'm_p_count_700'=>$m_p_count_700,
								'm_count_700'=>$m_count_700,
								'p_count_700'=>$p_count_700,
								'o_count_700'=>$o_count_700,
								'm_p_count_lyam'=>$m_p_count_lyam,
								'm_count_lyam'=>$m_count_lyam,
								'p_count_lyam'=>$p_count_lyam,
								'o_count_lyam'=>$o_count_lyam,
								'm_p_count_null'=>$m_p_count_null,
								'm_count_null'=>$m_count_null,
								'p_count_null'=>$p_count_null,
								'o_count_null'=>$o_count_null,
								'm_p_count_count'=>$m_p_count_count,
								'm_count_count'=>$m_count_count,
								'p_count_count'=>$p_count_count,
								'o_count_count'=>$o_count_count,
								'lider'=>$lider];
				case 'отказы по договорам за год':
						//Договора до 100 к руб
						$m_p_count_100 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE renouncement_contract=1 AND marketing_reestr=1 AND procurement_reestr=1 AND other_reestr=0 AND amount_contract_reestr<100000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$m_count_100 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE renouncement_contract=1 AND marketing_reestr=1 AND other_reestr=0 AND amount_contract_reestr<100000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$p_count_100 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE renouncement_contract=1 AND procurement_reestr=1 AND other_reestr=0 AND amount_contract_reestr<100000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$o_count_100 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE renouncement_contract=1 AND other_reestr=1 AND amount_contract_reestr<100000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						//Договора от 100 до 500 к руб
						$m_p_count_300 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE renouncement_contract=1 AND marketing_reestr=1 AND procurement_reestr=1 AND other_reestr=0 AND amount_contract_reestr<500000 AND amount_contract_reestr>100000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$m_count_300 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE renouncement_contract=1 AND marketing_reestr=1 AND other_reestr=0 AND amount_contract_reestr<500000 AND amount_contract_reestr>100000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$p_count_300 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE renouncement_contract=1 AND procurement_reestr=1 AND other_reestr=0 AND amount_contract_reestr<500000 AND amount_contract_reestr>100000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$o_count_300 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE renouncement_contract=1 AND other_reestr=1 AND amount_contract_reestr<500000 AND amount_contract_reestr>100000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						//Договора от 500к - 1м руб
						$m_p_count_700 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE renouncement_contract=1 AND marketing_reestr=1 AND procurement_reestr=1 AND other_reestr=0 AND amount_contract_reestr<1000000 AND amount_contract_reestr>500000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$m_count_700 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE renouncement_contract=1 AND marketing_reestr=1 AND other_reestr=0 AND amount_contract_reestr<1000000 AND amount_contract_reestr>500000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$p_count_700 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE renouncement_contract=1 AND procurement_reestr=1 AND other_reestr=0 AND amount_contract_reestr<1000000 AND amount_contract_reestr>500000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$o_count_700 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE renouncement_contract=1 AND other_reestr=1 AND amount_contract_reestr<1000000 AND amount_contract_reestr>500000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						//Договора свыше 1 м руб
						$m_p_count_lyam = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE renouncement_contract=1 AND marketing_reestr=1 AND procurement_reestr=1 AND other_reestr=0 AND amount_contract_reestr>1000000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$m_count_lyam = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE renouncement_contract=1 AND marketing_reestr=1 AND other_reestr=0 AND amount_contract_reestr>1000000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$p_count_lyam = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE renouncement_contract=1 AND procurement_reestr=1 AND other_reestr=0 AND amount_contract_reestr>1000000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$o_count_lyam = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE renouncement_contract=1 AND other_reestr=1 AND amount_contract_reestr>1000000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						//Договора без конкретной суммы
						$m_p_count_null = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE renouncement_contract=1 AND marketing_reestr=1 AND procurement_reestr=1 AND other_reestr=0 AND amount_contract_reestr IS NULL AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$m_count_null = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE renouncement_contract=1 AND marketing_reestr=1 AND other_reestr=0 AND amount_contract_reestr IS NULL AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$p_count_null = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE renouncement_contract=1 AND procurement_reestr=1 AND other_reestr=0 AND amount_contract_reestr IS NULL AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$o_count_null = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE renouncement_contract=1 AND other_reestr=1 AND amount_contract_reestr IS NULL AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						//Всего
						$m_p_count_count = $m_p_count_100 + $m_p_count_300 + $m_p_count_700 + $m_p_count_lyam + $m_p_count_null;
						$m_count_count = $m_count_100 + $m_count_300 + $m_count_700 + $m_count_null + $m_count_null;
						$p_count_count = $p_count_100 + $p_count_300 + $p_count_700 + $p_count_lyam + $p_count_null;
						$o_count_count = $o_count_100 + $o_count_300 + $o_count_700 + $o_count_lyam + $o_count_null;
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'm_p_count_100'=>$m_p_count_100,
								'm_count_100'=>$m_count_100,
								'p_count_100'=>$p_count_100,
								'o_count_100'=>$o_count_100,
								'm_p_count_300'=>$m_p_count_300,
								'm_count_300'=>$m_count_300,
								'p_count_300'=>$p_count_300,
								'o_count_300'=>$o_count_300,
								'm_p_count_700'=>$m_p_count_700,
								'm_count_700'=>$m_count_700,
								'p_count_700'=>$p_count_700,
								'o_count_700'=>$o_count_700,
								'm_p_count_lyam'=>$m_p_count_lyam,
								'm_count_lyam'=>$m_count_lyam,
								'p_count_lyam'=>$p_count_lyam,
								'o_count_lyam'=>$o_count_lyam,
								'm_p_count_null'=>$m_p_count_null,
								'm_count_null'=>$m_count_null,
								'p_count_null'=>$p_count_null,
								'o_count_null'=>$o_count_null,
								'm_p_count_count'=>$m_p_count_count,
								'm_count_count'=>$m_count_count,
								'p_count_count'=>$p_count_count,
								'o_count_count'=>$o_count_count,
								'lider'=>$lider];
				case 'итоги по действующим':
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,amount_contract_reestr,amount_reestr,procurement_reestr,marketing_reestr,investments_reestr,other_reestr 
											FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
											WHERE date_entry_into_force_reestr IS NOT NULL 
											AND (STR_TO_DATE(date_entry_into_force_reestr, '%d.%m.%Y') <= '" . $period2 . "') 
											AND ((STR_TO_DATE(date_signing_contract_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "' OR STR_TO_DATE(date_signing_contract_counterpartie_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')) 
											ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					$result = [];
					foreach($contracts as $contract){
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
						
						// Проверка на исполнение по статусу работы
						$status = true;
						$states = State::select(['*'])->where('id_contract', $contract->id)->where('is_work_state', 1)->get();
						if(count($states) > 0){
							if($states[count($states) - 1]->name_state != 'Выполнен')
								$status = false;
							else
								continue;
						}else
							$status = false;
						
						// Проверка на исполнение по сумме оплат
						if($amount_payments + $amount_prepayments - $amount_invoices - $amount_returns != 0 && $status == true)
							continue;
						
						//Распределяем по подразделениям
						$split_str = explode('‐',$contract->number_contract);
						if(count($split_str) > 1)
							foreach($departments as $department)
							{
								if($split_str[1] == $department->index_department)
								{
									if(!in_array($department->name_department,array_keys($result)))
										$result += [$department->name_department => [$contract]];
									else
										array_push($result[$department->name_department],$contract);
									break;
								}
							}
						else
							$result += ['' => [$contract]];
					}
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'result'=>$result,
								'lider'=>$lider];
				case 'договора по подразделению':
					$department = Department::select('index_department','name_department')->where('id',$request['department'])->first();
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,id_counterpartie_contract,amount_contract_reestr,renouncement_contract,
											date_entry_into_force_reestr,procurement_reestr,marketing_reestr,investments_reestr,other_reestr,item_contract,archive_contract 
											FROM contracts LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
											WHERE number_contract LIKE '%‐" . $department->index_department . "‐%' 
											AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
											AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL)
											ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract){
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
					}
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'contracts'=>$contracts,
								'lider'=>$lider];
				case 'информация о запросах котировки в электронной форме':
					$text = 'Информация о проведенных запросах котировки в электронной форме';
					if($request['fz'] == '223-ФЗ')
						$filter_fz = '(marketing_fz_223_reestr=1 OR procurement_fz_223_reestr=1)';
					else if($request['fz'] == '44-ФЗ')
						$filter_fz = '(marketing_fz_44_reestr=1 OR procurement_fz_44_reestr=1)';
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,id_counterpartie_contract,executor_reestr,oud_curators.FIO,item_contract,date_registration_project_reestr,
											amount_contract_reestr,nmcd_reestr,economy_reestr 
											FROM contracts LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
											JOIN selection_suppliers ON reestr_contracts.selection_supplier_reestr=selection_suppliers.id 
											LEFT JOIN oud_curators ON reestr_contracts.executor_reestr=oud_curators.id 
											WHERE " . $filter_fz . " AND (name_selection_supplier='Запрос котировки в эл. форме для СМСП' OR name_selection_supplier='Запрос котировки в эл. форме') 
											AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
											AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
											ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract){
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
					}
					return ['fz'=>$request['fz'],
								'period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'contracts'=>$contracts,
								'text'=>$text,
								'lider'=>$lider];
				case 'информация о запросах котировки':
					$text = 'Информация о проведенных запросах котировки';
					if($request['fz'] == '223-ФЗ')
						$filter_fz = '(marketing_fz_223_reestr=1 OR procurement_fz_223_reestr=1)';
					else if($request['fz'] == '44-ФЗ')
						$filter_fz = '(marketing_fz_44_reestr=1 OR procurement_fz_44_reestr=1)';
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,id_counterpartie_contract,executor_reestr,oud_curators.FIO,item_contract,date_registration_project_reestr,
											amount_contract_reestr,nmcd_reestr,economy_reestr 
											FROM contracts LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
											JOIN selection_suppliers ON reestr_contracts.selection_supplier_reestr=selection_suppliers.id 
											LEFT JOIN oud_curators ON reestr_contracts.executor_reestr=oud_curators.id 
											WHERE " . $filter_fz . " AND name_selection_supplier='Запрос котировки в бумажной форме' 
											AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
											AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
											ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract){
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
					}
					return ['fz'=>$request['fz'],
								'period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'contracts'=>$contracts,
								'text'=>$text,
								'lider'=>$lider];
				case 'информация о аукционах в электронной форме':
					$text = 'Информация о проведенных аукционах в электронной форме';
					if($request['fz'] == '223-ФЗ')
						$filter_fz = '(marketing_fz_223_reestr=1 OR procurement_fz_223_reestr=1)';
					else if($request['fz'] == '44-ФЗ')
						$filter_fz = '(marketing_fz_44_reestr=1 OR procurement_fz_44_reestr=1)';
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,id_counterpartie_contract,executor_reestr,oud_curators.FIO,item_contract,date_registration_project_reestr,
											amount_contract_reestr,nmcd_reestr,economy_reestr 
											FROM contracts LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
											JOIN selection_suppliers ON reestr_contracts.selection_supplier_reestr=selection_suppliers.id 
											LEFT JOIN oud_curators ON reestr_contracts.executor_reestr=oud_curators.id WHERE " . $filter_fz . " 
											AND (name_selection_supplier='Эл. аукцион для СМСП' OR name_selection_supplier='Эл. аукцион') 
											AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
											AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
											ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract){
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
					}
					return ['fz'=>$request['fz'],
								'period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'contracts'=>$contracts,
								'text'=>$text,
								'lider'=>$lider];
				case 'список контрактов по инвестициям':
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,id_counterpartie_contract,item_contract,number_counterpartie_contract_reestr,nmcd_reestr,
											amount_contract_reestr,amount_reestr,economy_reestr,date_control_signing_contract_reestr,date_contract_on_first_reestr 
											FROM contracts LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr WHERE investments_reestr=1 
											AND (procurement_fz_44_reestr=1 OR marketing_fz_44_reestr=1) 
											AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
											AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
											ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract){
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
					}
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'contracts'=>$contracts,
								'lider'=>$lider];
				case 'сведения о заключенным в рамках':
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,id_counterpartie_contract,item_contract,number_counterpartie_contract_reestr,
											amount_contract_reestr,amount_reestr,date_registration_project_reestr 
											FROM contracts LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr WHERE 
											(procurement_fz_44_reestr=1 OR marketing_fz_44_reestr=1 OR marketing_fz_223_reestr=1 OR procurement_fz_223_reestr=1) 
											AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
											AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
											AND date_signing_contract_reestr IS NOT NULL AND date_signing_contract_reestr != '' 
											AND date_signing_contract_counterpartie_reestr IS NOT NULL AND date_signing_contract_counterpartie_reestr != '' 
											ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract){
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						
						//Задолженность
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
						
						$contract->dolg = $amount_payments + $amount_prepayments - $amount_invoices - $amount_returns;
					}
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'contracts'=>$contracts,
								'lider'=>$lider];
				case 'список заключенных в рамках для контроля исполнения':
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,id_counterpartie_contract,item_contract,number_counterpartie_contract_reestr,
											amount_contract_reestr,amount_reestr,date_maturity_reestr,prepayment_order_reestr,score_order_reestr,payment_order_reestr,date_e_contract_reestr,
											date_registration_project_reestr
											FROM contracts LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr WHERE 
											(procurement_fz_44_reestr=1 OR marketing_fz_44_reestr=1 OR marketing_fz_223_reestr=1 OR procurement_fz_223_reestr=1) 
											AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
											AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
											AND date_signing_contract_counterpartie_reestr IS NOT NULL AND date_signing_contract_counterpartie_reestr != '' 
											ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract){
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
					}
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'contracts'=>$contracts,
								'lider'=>$lider];
				case 'справка о крупных сделках':
					$query = "SELECT contracts.id,contracts.number_contract,date_registration_project_reestr,item_contract,number_inquiry_reestr,date_inquiry_reestr,number_answer_reestr,
							date_answer_reestr,amount_contract_reestr,amount_reestr,date_contract_reestr,date_maturity_reestr 
							FROM contracts LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
							WHERE (number_inquiry_reestr IS NOT NULL OR number_answer_reestr IS NOT NULL) 
							AND (STR_TO_DATE(date_inquiry_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
							AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
							ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC";
					$contracts = DB::SELECT($query);
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'contracts'=>$contracts,
								'lider'=>$lider,
								'mini_title'=>'Справка о согласованиях крупных сделок',
								'title'=>'Справка о согласованиях, полученных в текущем и предшествующем году, с указанием условий совершения соответствующих сделок',
								'query'=>$query];
				case 'отчет о заключенных крупных сделках':
					$query = "SELECT contracts.id,contracts.number_contract,date_registration_project_reestr,item_contract,number_inquiry_reestr,date_inquiry_reestr,number_answer_reestr,
							date_answer_reestr,amount_contract_reestr,amount_reestr,date_contract_reestr,date_maturity_reestr 
							FROM contracts LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
							WHERE (number_inquiry_reestr IS NOT NULL OR number_answer_reestr IS NOT NULL) 
							AND date_inquiry_reestr IS NOT NULL AND date_inquiry_reestr != '' AND date_answer_reestr IS NOT NULL AND date_answer_reestr != '' 
							AND date_signing_contract_reestr IS NOT NULL AND date_signing_contract_reestr != '' 
							AND date_signing_contract_counterpartie_reestr IS NOT NULL AND date_signing_contract_counterpartie_reestr != '' 
							AND ((STR_TO_DATE(date_signing_contract_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') OR (STR_TO_DATE(date_signing_contract_counterpartie_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')) 
							AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
							ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC";
					$contracts = DB::SELECT($query);
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'contracts'=>$contracts,
								'lider'=>$lider,
								'mini_title'=>'Отчёт о заключенных крупных сделках',
								'title'=>'Отчет о заключенных крупных сделках, с указанием условий совершения соответствующих сделок',
								'query'=>$query];
				case 'договоры сданные 223':
					$query = "SELECT contracts.id,contracts.number_contract,date_save_contract_reestr,number_counterpartie_contract_reestr,amount_contract_reestr,amount_reestr 
								FROM contracts LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
								WHERE marketing_fz_223_reestr=1 AND procurement_reestr=1 AND (STR_TO_DATE(date_save_contract_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
								AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
								ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC";
					$contracts = DB::SELECT($query);
					return ['number_fz'=>223,
								'period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'contracts'=>$contracts,
								'lider'=>$lider
								];
				case 'договоры сданные 44':
					$query = "SELECT contracts.id,contracts.number_contract,date_save_contract_reestr,number_counterpartie_contract_reestr,amount_contract_reestr,amount_reestr 
								FROM contracts LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
								WHERE marketing_fz_44_reestr=1 AND procurement_reestr=1 AND (STR_TO_DATE(date_save_contract_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
								AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
								ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC";
					$contracts = DB::SELECT($query);
					return ['number_fz'=>44,
								'period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'contracts'=>$contracts,
								'lider'=>$lider
								];
					break;
				case 'срок исполнения пэо':
					$text1 = 'Справка о исполнении по Договорам/Контрактам по контрагентам';
					$result = [];
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
					$contracts = DB::SELECT("SELECT contracts.id_counterpartie_contract, number_contract, number_counterpartie_contract_reestr, item_contract, date_maturity_reestr, 
											date_contract_reestr, date_e_contract_reestr, amount_reestr, amount_comment_reestr 
											FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
											WHERE contracts.deleted_at IS NULL AND number_contract LIKE '%‐02‐%' AND document_success_renouncement_reestr IS NULL AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) ORDER BY contracts.id DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								$contract->counterpartie_name_full = $counter->name_full;
								break;
							}
						}
						
						//Распределяем по контрагентам
						if(!in_array($contract->counterpartie_name_full,array_keys($result)))
							$result += [$contract->counterpartie_name_full => [$contract]];
						else
							array_push($result[$contract->counterpartie_name_full],$contract);
					}
					return ['text'=>$text1,'result'=>$result,'lider'=>$lider];
				case 'сведения о количестве и об общей стоимости договоров':
					$text1 = 'Сведения о количестве и об общей стоимости договоров, заключенных заказчиком по результатам закупки товаров, работ, услуг ';
					if($request['fz'] == '223-ФЗ'){
						$filter_fz = '(marketing_fz_223_reestr=1 OR procurement_fz_223_reestr=1)';
						$text1 .= 'в рамках "223-ФЗ"';
					}
					else if($request['fz'] == '44-ФЗ'){
						$filter_fz = '(marketing_fz_44_reestr=1 OR procurement_fz_44_reestr=1)';
						$text1 .= 'в рамках "44-ФЗ"';
					}
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,id_counterpartie_contract,item_contract,date_registration_project_reestr, 
											amount_contract_reestr, amount_reestr, okpd_2_reestr, reestr_number_reestr, purchase_reestr, selection_suppliers.name_selection_supplier, 
											date_entry_into_force_reestr 
											FROM contracts 
											LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
											LEFT JOIN selection_suppliers ON reestr_contracts.selection_supplier_reestr=selection_suppliers.id 
											WHERE " . $filter_fz . "  
											AND (STR_TO_DATE(date_entry_into_force_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
											AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
											AND reestr_number_reestr IS NOT NULL 
											AND reestr_number_reestr != '' 
											ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract){
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						// Исполнение
						$amount_av = 0;
						$amount_nakl = 0;
						$amount_pp = 0;
						$obligations = ObligationInvoice::select('*')->where('id_contract', $contract->id)->get();
						foreach($obligations as $obligation)
							if($obligation->type_invoice == 5)			// Счёт-фактура
								$amount_av += $obligation->amount;
							else if ($obligation->type_invoice == 6)	// Товарные накладные
								$amount_nakl += $obligation->amount;
							else if ($obligation->type_invoice == 8)	// Акты (для услуг)
								$amount_nakl += $obligation->amount;
							else if ($obligation->type_invoice == 7)	// Платежные поручения
								$amount_pp += $obligation->amount;
						$contract->amount_st = $amount_av + $amount_nakl;
						$contract->amount_pp = $amount_pp;
					}
					return ['text'=>$text1,'contracts'=>$contracts,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'lider'=>$lider];
				
				// ---- FORMS ----
				case 'форма проекты на сбыт за период':
					$text1 = 'Справка по подразделению на сбыт: проекты Договоров (Контрактов) за период: ';
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr,amount_reestr,prepayment_order_reestr,score_order_reestr, 
												is_sip_contract,executor_contract_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr WHERE number_contract LIKE '%‐" . $fil_dep . "‐%' 
												AND date_entry_into_force_reestr IS NULL 
												AND marketing_reestr=1 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					
					$result = [];
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
						
						// Исполнитель
						if($contract->is_sip_contract == 1){
							$pr_executor = Curator::select()->where('id', $contract->executor_contract_reestr)->first();
							if($pr_executor != null)
								$contract->executor_contract_reestr = $pr_executor->FIO;
						}
						
						//Распределяем по подразделениям
						$split_str = explode('‐',$contract->number_contract);
						if(count($split_str) > 1)
							foreach($departments as $department)
							{
								if($split_str[1] == $department->index_department)
								{
									if(!in_array($department->name_department,array_keys($result)))
										$result += [$department->name_department => [$contract]];
									else
										array_push($result[$department->name_department],$contract);
									break;
								}
							}
						else
							$result += ['' => [$contract]];
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'result'=>$result,'lider'=>$lider];
				case 'форма заключенные на сбыт за период':
					$text1 = 'Справка на сбыт: заключенные Договора (Контракты) (предприятия, подразделения) за период';
					$result = [];
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr,amount_reestr,prepayment_order_reestr,score_order_reestr,
												is_sip_contract,executor_contract_reestr,date_entry_into_force_reestr  
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr WHERE number_contract LIKE '%‐" . $fil_dep . "‐%' 
												AND marketing_reestr=1 AND date_signing_contract_reestr IS NOT NULL AND date_signing_contract_counterpartie_reestr IS NOT NULL 
												AND (STR_TO_DATE(date_entry_into_force_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
						
						// Исполнитель
						if($contract->is_sip_contract == 1){
							$pr_executor = Curator::select()->where('id', $contract->executor_contract_reestr)->first();
							if($pr_executor != null)
								$contract->executor_contract_reestr = $pr_executor->FIO;
						}
						
						//Распределяем по подразделениям
						$split_str = explode('‐',$contract->number_contract);
						if(count($split_str) > 1)
							foreach($departments as $department)
							{
								if($split_str[1] == $department->index_department)
								{
									if(!in_array($department->name_department,array_keys($result)))
										$result += [$department->name_department => [$contract]];
									else
										array_push($result[$department->name_department],$contract);
									break;
								}
							}
						else
							$result += ['' => [$contract]];
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'result'=>$result,'count_contracts'=>count($contracts),'lider'=>$lider];
				case 'форма отказы на сбыт за период':
					$text1 = 'Справка по подразделению на сбыт: ОТКАЗЫ по Договорам (Контрактам) за период';
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr WHERE marketing_reestr=1 AND renouncement_contract=1 
												AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								$contract->counterpartie_name_full = $counter->name_full;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'contracts'=>$contracts,'lider'=>$lider];
				case 'форма банковские гарантии на сбыт за период':
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,amount_bank_reestr,date_bank_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr 
												FROM contracts LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
												WHERE marketing_reestr=1 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND amount_bank_reestr IS NOT NULL AND amount_bank_reestr != '' 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
					}
					return ['period1'=>$request['date_begin'],'period2'=>$request['date_end'],'contracts'=>$contracts,'count_contracts'=>count($contracts),'lider'=>$lider];
				case 'форма отчет по подразделению по исполнителю на сбыт':
					$text1 = 'Отчет о Договорах/Контрактов по подразделению по Исполнителю';
					$result = [];
					$department = Department::select('index_department','name_department')->where('id',$request['department'])->first();
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr,amount_reestr,prepayment_order_reestr,score_order_reestr,
												oud_curators.FIO as executor,executor_contract_reestr,executor_reestr,is_sip_contract 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr LEFT JOIN oud_curators ON executor_reestr=oud_curators.id 
												WHERE marketing_reestr=1 AND number_contract LIKE '%‐" . $department->index_department . "‐%' 
												AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
						
						if($contract->is_sip_contract == 1){
							$pr_executor = Curator::select()->where('id', $contract->executor_contract_reestr)->first();
							if($pr_executor != null)
								$contract->executor_contract_reestr = $pr_executor->FIO;
						}
						
						//Распределяем по исполнителям
						$key_executor = str_replace('.', '', str_replace(' ', '', $contract->executor_contract_reestr));
						if(!in_array($key_executor,array_keys($result)))
							$result += [$key_executor => [$contract]];
						else
							array_push($result[$key_executor],$contract);
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'result'=>$result,'count_contracts'=>count($contracts),'lider'=>$lider];
				case 'форма список исполненных договоров на сбыт':
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,id_counterpartie_contract,is_sip_contract,executor_contract_reestr,
											date_contract_on_first_reestr,item_contract,amount_contract_reestr,amount_reestr,date_maturity_reestr,date_signing_contract_reestr,
											date_signing_contract_counterpartie_reestr,date_entry_into_force_reestr,date_save_contract_reestr,id_goz_contract,goz_works.name_works_goz 
											FROM contracts 
											JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
											LEFT JOIN goz_works ON contracts.id_goz_contract=goz_works.id 
											WHERE marketing_reestr=1 AND number_contract LIKE '%‐" . $fil_dep . "‐%' AND (date_entry_into_force_reestr IS NOT NULL) 
											AND (STR_TO_DATE(date_entry_into_force_reestr, '%d.%m.%Y') <= '" . $period2 . "') 
											AND ((STR_TO_DATE(date_signing_contract_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "' OR STR_TO_DATE(date_signing_contract_counterpartie_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')) 
											AND is_sip_contract=1 
											ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					$result = [];
					foreach($contracts as $contract){
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
						
						// Проверка на исполнение по сумме оплат
						if($amount_payments + $amount_prepayments - $amount_invoices - $amount_returns != 0)
							continue;

						// Проверка на исполнение по статусу работы
						$states = State::select(['*'])->where('id_contract', $contract->id)->where('is_work_state', 1)->get();
						if(count($states) > 0){
							if($states[count($states) - 1]->name_state != 'Выполнен')
								continue;
						}else
							continue;
						
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						if($contract->is_sip_contract == 1){
							$pr_executor = Curator::select()->where('id', $contract->executor_contract_reestr)->first();
							if($pr_executor != null)
								$contract->executor_contract_reestr = $pr_executor->FIO;
						}
						if(!in_array($contract->counterpartie_name,array_keys($result)))
							$result += [$contract->counterpartie_name => [$contract]];
						else
							array_push($result[$contract->counterpartie_name],$contract);
					}
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'result'=>$result,
								'lider'=>$lider];
				case 'форма список договоров вступивших в силу на сбыт':
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,id_counterpartie_contract,is_sip_contract,executor_contract_reestr,
											date_contract_on_first_reestr,item_contract,amount_contract_reestr,amount_reestr,date_maturity_reestr,date_signing_contract_reestr,
											date_signing_contract_counterpartie_reestr,date_entry_into_force_reestr,date_save_contract_reestr 
											FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
											WHERE marketing_reestr=1 AND number_contract LIKE '%‐" . $fil_dep . "‐%' AND (date_entry_into_force_reestr IS NOT NULL) 
											AND (STR_TO_DATE(date_entry_into_force_reestr, '%d.%m.%Y') <= '" . $period2 . "') 
											AND ((STR_TO_DATE(date_signing_contract_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "' OR STR_TO_DATE(date_signing_contract_counterpartie_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')) 
											AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
											AND is_sip_contract=1 
											ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					$result = [];
					foreach($contracts as $contract){
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						if($contract->is_sip_contract == 1){
							$pr_executor = Curator::select()->where('id', $contract->executor_contract_reestr)->first();
							if($pr_executor != null)
								$contract->executor_contract_reestr = $pr_executor->FIO;
						}
						if(!in_array($contract->counterpartie_name,array_keys($result)))
							$result += [$contract->counterpartie_name => [$contract]];
						else
							array_push($result[$contract->counterpartie_name],$contract);
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
					}
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'result'=>$result,
								'lider'=>$lider];
				case 'форма проекты на закуп за период':
					$text1 = 'Справка: проекты Договоров (Контрактов) на закуп';
					//dd(DATE('Y-m-d', strtotime($period1)));
					/*$contracts = Contract::select('contracts.id','contracts.number_contract','number_counterpartie_contract_reestr','id_counterpartie_contract','item_contract',
													'date_maturity_reestr','amount_contract_reestr','payment_order_reestr','date_contract_reestr')
										->join('reestr_contracts','contracts.id','reestr_contracts.id_contract_reestr')
										->whereBetween('date_registration_project_reestr', array(DATE('Y-m-d', strtotime($period1)),DATE('Y-m-d', strtotime($period2))))
										->get();*/
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr,amount_reestr,prepayment_order_reestr,score_order_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
												WHERE number_contract LIKE '%‐" . $fil_dep . "‐%' AND procurement_reestr=1 
												AND date_entry_into_force_reestr IS NULL 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'contracts'=>$contracts,'lider'=>$lider];
				case 'форма заключенные на закуп за период':
					$text1 = 'Справка на закуп: заключенные Договора (Контракты) (предприятия, подразделения) за период';
					$result = [];
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr,amount_reestr,prepayment_order_reestr,score_order_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr WHERE number_contract LIKE '%‐" . $fil_dep . "‐%' 
												AND procurement_reestr=1 AND date_signing_contract_reestr IS NOT NULL AND date_signing_contract_counterpartie_reestr IS NOT NULL 
												AND (STR_TO_DATE(date_entry_into_force_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
						
						//Распределяем по подразделениям
						$split_str = explode('‐',$contract->number_contract);
						if(count($split_str) > 1)
							foreach($departments as $department)
							{
								if($split_str[1] == $department->index_department)
								{
									if(!in_array($department->name_department,array_keys($result)))
										$result += [$department->name_department => [$contract]];
									else
										array_push($result[$department->name_department],$contract);
									break;
								}
							}
						else
							$result += ['' => [$contract]];
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'result'=>$result,'count_contracts'=>count($contracts),'lider'=>$lider];
				case 'форма отказы на закуп за период':
					$text1 = 'Справка по подразделению на закуп: ОТКАЗЫ по Договорам (Контрактам) за период';
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr WHERE procurement_reestr=1 AND renouncement_contract=1 
												AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								$contract->counterpartie_name_full = $counter->name_full;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'contracts'=>$contracts,'lider'=>$lider];
				case 'форма банковские гарантии на закуп за период':
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,amount_bank_reestr,date_bank_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr 
												FROM contracts LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
												WHERE procurement_reestr=1 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND amount_bank_reestr IS NOT NULL AND amount_bank_reestr != '' 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
					}
					return ['period1'=>$request['date_begin'],'period2'=>$request['date_end'],'contracts'=>$contracts,'count_contracts'=>count($contracts),'lider'=>$lider];
				case 'форма отчет об исполнении с единственным поставщиком':
					$text = 'Отчет об исполнении Договоров (Контрактов) с Единственным поставщиком в рамках ' . $request['fz'];
					if($request['fz'] == '223-ФЗ')
						$filter_fz = '(marketing_fz_223_reestr=1 OR procurement_fz_223_reestr=1)';
					else if($request['fz'] == '44-ФЗ')
						$filter_fz = '(marketing_fz_44_reestr=1 OR procurement_fz_44_reestr=1)';
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,id_counterpartie_contract,executor_reestr,oud_curators.FIO,item_contract,date_registration_project_reestr,
											amount_contract_reestr,nmcd_reestr,economy_reestr 
											FROM contracts LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
											JOIN selection_suppliers ON reestr_contracts.selection_supplier_reestr=selection_suppliers.id 
											LEFT JOIN oud_curators ON reestr_contracts.executor_reestr=oud_curators.id 
											WHERE " . $filter_fz . " AND name_selection_supplier='Единственный поставщик' 
											AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
											AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
											ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract){
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
					}
					return ['fz'=>$request['fz'],
								'period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'contracts'=>$contracts,
								'text'=>$text,
								'lider'=>$lider];
				case 'форма информация о запросах котировки в электронной форме':
					$text = 'Отчет о проведенных запросах котировок в электронной форме в рамках ' . $request['fz'];
					if($request['fz'] == '223-ФЗ')
						$filter_fz = '(marketing_fz_223_reestr=1 OR procurement_fz_223_reestr=1)';
					else if($request['fz'] == '44-ФЗ')
						$filter_fz = '(marketing_fz_44_reestr=1 OR procurement_fz_44_reestr=1)';
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,id_counterpartie_contract,executor_reestr,oud_curators.FIO,item_contract,date_registration_project_reestr,
											amount_contract_reestr,nmcd_reestr,economy_reestr 
											FROM contracts LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
											JOIN selection_suppliers ON reestr_contracts.selection_supplier_reestr=selection_suppliers.id 
											LEFT JOIN oud_curators ON reestr_contracts.executor_reestr=oud_curators.id 
											WHERE " . $filter_fz . " AND (name_selection_supplier='Запрос котировки в эл. форме для СМСП' OR name_selection_supplier='Запрос котировки в эл. форме') 
											AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
											AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
											ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract){
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
					}
					return ['fz'=>$request['fz'],
								'period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'contracts'=>$contracts,
								'text'=>$text,
								'lider'=>$lider];
				case 'форма отчет об исполнении на закуп по итогам электронного аукциона':
					$text = 'Отчет об исполнении Договора на закуп товаров, работ, услуг заключенного по итогам электронного аукциона в рамках ' . $request['fz'];
					if($request['fz'] == '223-ФЗ')
						$filter_fz = '(marketing_fz_223_reestr=1 OR procurement_fz_223_reestr=1)';
					else if($request['fz'] == '44-ФЗ')
						$filter_fz = '(marketing_fz_44_reestr=1 OR procurement_fz_44_reestr=1)';
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,id_counterpartie_contract,executor_reestr,oud_curators.FIO,item_contract,date_registration_project_reestr,
											amount_contract_reestr,nmcd_reestr,economy_reestr 
											FROM contracts LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
											JOIN selection_suppliers ON reestr_contracts.selection_supplier_reestr=selection_suppliers.id 
											LEFT JOIN oud_curators ON reestr_contracts.executor_reestr=oud_curators.id 
											WHERE " . $filter_fz . " AND (name_selection_supplier='Эл. аукцион' OR name_selection_supplier='Эл. аукцион для СМСП') 
											AND procurement_reestr=1 
											AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
											AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
											ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract){
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
					}
					return ['fz'=>$request['fz'],
								'period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'contracts'=>$contracts,
								'text'=>$text,
								'lider'=>$lider];
				case 'форма отчет по подразделению по исполнителю на закуп':
					$text1 = 'Отчет о Договорах/Контрактов по подразделению по Исполнителю';
					$result = [];
					$department = Department::select('index_department','name_department')->where('id',$request['department'])->first();
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr,amount_reestr,prepayment_order_reestr,score_order_reestr,
												oud_curators.FIO as executor,executor_contract_reestr,executor_reestr,is_sip_contract 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr LEFT JOIN oud_curators ON executor_reestr=oud_curators.id 
												WHERE procurement_reestr=1 AND number_contract LIKE '%‐" . $department->index_department . "‐%' 
												AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
						
						if($contract->is_sip_contract == 1){
							$pr_executor = Curator::select()->where('id', $contract->executor_contract_reestr)->first();
							if($pr_executor != null)
								$contract->executor_contract_reestr = $pr_executor->FIO;
						}
						
						//Распределяем по исполнителям
						$key_executor = str_replace('.', '', str_replace(' ', '', $contract->executor_contract_reestr));
						if(!in_array($key_executor,array_keys($result)))
							$result += [$key_executor => [$contract]];
						else
							array_push($result[$key_executor],$contract);
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'result'=>$result,'count_contracts'=>count($contracts),'lider'=>$lider];
				case 'форма справка о контрагентах на закуп за период':
					$text1 = 'Справка о Договорах/Контрактах по контрагентам';
					$result = [];
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr,amount_reestr,prepayment_order_reestr,score_order_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr WHERE number_contract LIKE '%‐" . $fil_dep . "‐%' 
												AND procurement_reestr=1 
												AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								$contract->counterpartie_name_full = $counter->name_full;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
						
						//Распределяем по контрагентам
						if(!in_array($contract->counterpartie_name_full,array_keys($result)))
							$result += [$contract->counterpartie_name_full => [$contract]];
						else
							array_push($result[$contract->counterpartie_name_full],$contract);
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'result'=>$result,'count_contracts'=>count($contracts),'lider'=>$lider];
				case 'форма справка о контрагентах на сбыт за период':
					$text1 = 'Справка о Договорах/Контрактах по контрагентам';
					$result = [];
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr,amount_reestr,prepayment_order_reestr,score_order_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr WHERE number_contract LIKE '%‐" . $fil_dep . "‐%' 
												AND marketing_reestr=1 
												AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								$contract->counterpartie_name_full = $counter->name_full;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
						
						//Распределяем по контрагентам
						if(!in_array($contract->counterpartie_name_full,array_keys($result)))
							$result += [$contract->counterpartie_name_full => [$contract]];
						else
							array_push($result[$contract->counterpartie_name_full],$contract);
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'result'=>$result,'count_contracts'=>count($contracts),'lider'=>$lider];
				case 'форма договоры сданные 223':
					$query = "SELECT contracts.id,contracts.number_contract,date_save_contract_reestr,number_counterpartie_contract_reestr,amount_contract_reestr,amount_reestr 
								FROM contracts LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
								WHERE marketing_fz_223_reestr=1 AND procurement_reestr=1 AND (STR_TO_DATE(date_save_contract_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
								AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
								ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC";
					$contracts = DB::SELECT($query);
					return ['number_fz'=>223,
								'period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'contracts'=>$contracts,
								'lider'=>$lider
								];
				case 'форма договоры сданные 44':
					$query = "SELECT contracts.id,contracts.number_contract,date_save_contract_reestr,number_counterpartie_contract_reestr,amount_contract_reestr,amount_reestr 
								FROM contracts LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
								WHERE marketing_fz_44_reestr=1 AND procurement_reestr=1 AND (STR_TO_DATE(date_save_contract_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
								AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
								ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC";
					$contracts = DB::SELECT($query);
					return ['number_fz'=>44,
								'period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'contracts'=>$contracts,
								'lider'=>$lider
								];
				case 'форма пролонгированные за период':
					$text1 = 'Пролонгированные Договора (Контракты)';
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
												WHERE prolongation_reestr=1 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								$contract->counterpartie_name_full = $counter->name_full;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'contracts'=>$contracts,'lider'=>$lider];
				case 'форма отчет по иным за период':
					$text1 = 'Список - иные Договора (Контракты)';
					$result = [];
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,name_view_contract,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
												JOIN view_contracts ON reestr_contracts.id_view_contract=view_contracts.id 
												WHERE other_reestr=1 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								$contract->counterpartie_name_full = $counter->name_full;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
						
						//Распределяем по видам договоров
						if(!in_array($contract->name_view_contract,array_keys($result)))
							$result += [$contract->name_view_contract => [$contract]];
						else
							array_push($result[$contract->name_view_contract],$contract);
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'result'=>$result,'count_contracts'=>count($contracts),'lider'=>$lider];
				case 'форма справка о крупных сделках':
					$query = "SELECT contracts.id,contracts.number_contract,date_registration_project_reestr,item_contract,number_inquiry_reestr,date_inquiry_reestr,number_answer_reestr,
							date_answer_reestr,amount_contract_reestr,amount_reestr,date_contract_reestr,date_maturity_reestr 
							FROM contracts LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
							WHERE (number_inquiry_reestr IS NOT NULL OR number_answer_reestr IS NOT NULL) 
							AND (STR_TO_DATE(date_inquiry_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
							AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
							ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC";
					$contracts = DB::SELECT($query);
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'contracts'=>$contracts,
								'lider'=>$lider,
								'mini_title'=>'Справка о согласованиях крупных сделок',
								'title'=>'Справка о согласованиях, полученных в текущем и предшествующем году, с указанием условий совершения соответствующих сделок',
								'query'=>$query];
				case 'форма договора по подразделению':
					$department = Department::select('index_department','name_department')->where('id',$request['department'])->first();
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,id_counterpartie_contract,amount_contract_reestr,renouncement_contract,
											date_entry_into_force_reestr,procurement_reestr,marketing_reestr,investments_reestr,other_reestr,item_contract,archive_contract 
											FROM contracts LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
											WHERE number_contract LIKE '%‐" . $department->index_department . "‐%' 
											AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
											AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL)
											ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract){
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
					}
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'contracts'=>$contracts,
								'lider'=>$lider];
				case 'форма статистика по количеству':
					foreach($departments as $department)
					{
						$procurement_count = DB::SELECT("SELECT count(*) as count FROM statics_count_contract WHERE number_contract like '%‐" . $department->index_department . "‐%' AND procurement_reestr=1 AND renouncement_contract=0 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$department->procurement_count = $procurement_count;
						$marketing_count = DB::SELECT("SELECT count(*) as count FROM statics_count_contract WHERE number_contract like '%‐" . $department->index_department . "‐%' AND marketing_reestr=1 AND renouncement_contract=0 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$department->marketing_count = $marketing_count;
						$procurement_marketing_count = DB::SELECT("SELECT count(*) as count FROM statics_count_contract WHERE number_contract like '%‐" . $department->index_department . "‐%' AND procurement_reestr=1 AND marketing_reestr=1 AND renouncement_contract=0 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$department->procurement_marketing_count = $procurement_marketing_count;
						$other_count = DB::SELECT("SELECT count(*) as count FROM statics_count_contract WHERE number_contract like '%‐" . $department->index_department . "‐%' AND other_reestr=1 AND renouncement_contract=0 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$department->other_count = $other_count;
						$break_count = DB::SELECT("SELECT count(*) as count FROM statics_count_contract WHERE number_contract like '%‐" . $department->index_department . "‐%' AND renouncement_contract=1 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$department->break_count = $break_count;
						$department->all_count = $procurement_count + $marketing_count + $procurement_marketing_count + $other_count + $break_count;
					}
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'departments'=>$departments,
								'lider'=>$lider];
				case 'форма итоги по действующим':
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,amount_contract_reestr,amount_reestr,procurement_reestr,marketing_reestr,investments_reestr,other_reestr 
											FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
											WHERE date_entry_into_force_reestr IS NOT NULL 
											AND (STR_TO_DATE(date_entry_into_force_reestr, '%d.%m.%Y') <= '" . $period2 . "') 
											AND ((STR_TO_DATE(date_signing_contract_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "' OR STR_TO_DATE(date_signing_contract_counterpartie_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')) 
											ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					$result = [];
					foreach($contracts as $contract){
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
						
						// Проверка на исполнение по статусу работы
						$status = true;
						$states = State::select(['*'])->where('id_contract', $contract->id)->where('is_work_state', 1)->get();
						if(count($states) > 0){
							if($states[count($states) - 1]->name_state != 'Выполнен')
								$status = false;
							else
								continue;
						}else
							$status = false;
						
						// Проверка на исполнение по сумме оплат
						if($amount_payments + $amount_prepayments - $amount_invoices - $amount_returns != 0 && $status == true)
							continue;
						
						//Распределяем по подразделениям
						$split_str = explode('‐',$contract->number_contract);
						if(count($split_str) > 1)
							foreach($departments as $department)
							{
								if($split_str[1] == $department->index_department)
								{
									if(!in_array($department->name_department,array_keys($result)))
										$result += [$department->name_department => [$contract]];
									else
										array_push($result[$department->name_department],$contract);
									break;
								}
							}
						else
							$result += ['' => [$contract]];
					}
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'result'=>$result,
								'lider'=>$lider];
				case 'форма статистика по сумме':
					foreach($departments as $department)
					{
						$count_100 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE contracts.number_contract like '%‐" . $department->index_department . "‐%' AND procurement_reestr=1 AND amount_reestr<100000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$department->count_100 = $count_100;
						$count_300 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE contracts.number_contract like '%‐" . $department->index_department . "‐%' AND procurement_reestr=1 AND amount_reestr<500000 AND amount_reestr>100000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$department->count_300 = $count_300;
						$count_700 = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE contracts.number_contract like '%‐" . $department->index_department . "‐%' AND procurement_reestr=1 AND amount_reestr<1000000 AND amount_reestr>500000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$department->count_700 = $count_700;
						$count_lyam = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE contracts.number_contract like '%‐" . $department->index_department . "‐%' AND procurement_reestr=1 AND amount_reestr>1000000 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$department->count_lyam = $count_lyam;
						$count_null = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE contracts.number_contract like '%‐" . $department->index_department . "‐%' AND procurement_reestr=1 AND amount_reestr IS NULL AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$department->count_null = $count_null;
						$department->all_count = $count_100 + $count_300 + $count_700 + $count_lyam + $count_null;
					}
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'departments'=>$departments,
								'lider'=>$lider];
				case 'форма просроченое по подразделению':
					$department = Department::select('index_department','name_department')->where('id',$request['department'])->first();
					if($department != null)
						$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
													date_maturity_reestr,amount_contract_reestr,amount_reestr,payment_order_reestr,date_contract_reestr,oud_curators.FIO as executor,executor_contract_reestr,executor_reestr,is_sip_contract FROM contracts JOIN reestr_contracts ON 
													contracts.id=reestr_contracts.id_contract_reestr LEFT JOIN oud_curators ON executor_reestr=oud_curators.id WHERE number_contract LIKE '%‐" . $department->index_department . "‐%' 
													AND ((renouncement_contract IS NULL OR renouncement_contract=0) AND date_save_contract_reestr IS NULL AND date_registration_project_reestr IS NOT NULL AND ".time()."-STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') > 2592000)
													AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
													AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
													ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					else
						$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
													date_maturity_reestr,amount_contract_reestr,amount_reestr,payment_order_reestr,date_contract_reestr,oud_curators.FIO as executor,executor_contract_reestr,executor_reestr,is_sip_contract FROM contracts JOIN reestr_contracts ON 
													contracts.id=reestr_contracts.id_contract_reestr LEFT JOIN oud_curators ON executor_reestr=oud_curators.id WHERE 
													((renouncement_contract IS NULL OR renouncement_contract=0) AND date_save_contract_reestr IS NULL AND date_registration_project_reestr IS NOT NULL AND " . time() . "-STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') > 2592000)
													AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
													AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
													ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					$result = [];
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name_full;
								break;
							}
						}
						
						if($contract->is_sip_contract == 1){
							$pr_executor = Curator::select()->where('id', $contract->executor_contract_reestr)->first();
							if($pr_executor != null)
								$contract->executor_contract_reestr = $pr_executor->FIO;
						}
						
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
						
						//Распределяем по подразделениям
						$split_str = explode('‐',$contract->number_contract);
						if(count($split_str) > 1)
							foreach($departments as $department)
							{
								if($split_str[1] == $department->index_department)
								{
									//Записываем в контракт информацию о том кто начальник
									$contract->lider_department = User::select('surname','name','patronymic','position_department')->where('id',$department->lider_department)->first();
									if(!in_array($department->name_department,array_keys($result)))
										$result += [$department->name_department => [$contract]];
									else
										array_push($result[$department->name_department],$contract);
									break;
								}
							}
						else
							$result += ['' => [$contract]];
					}
					return ['result'=>$result,'count_contracts'=>count($contracts),'lider'=>$lider];
				case 'форма итоги по реестру':
					$proekt_t = 0;
					$registr_t = 0;
					$break_t = 0;
					$proekt_p = 0;
					$registr_p = 0;
					$break_p = 0;
					foreach($departments as $department)
					{
						$t_proekt_count_contracts = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE contracts.year_contract='" . date('Y',time()) . "' AND contracts.number_contract like '%‐" . $department->index_department . "‐%' AND (date_signing_contract_reestr IS NULL OR date_signing_contract_counterpartie_reestr IS NULL) AND renouncement_contract=0 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$t_registr_count_contracts = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE contracts.year_contract='" . date('Y',time()) . "' AND contracts.number_contract like '%‐" . $department->index_department . "‐%' AND (date_signing_contract_reestr IS NOT NULL AND date_signing_contract_counterpartie_reestr IS NOT NULL) AND renouncement_contract=0 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						//$t_break_count_contracts = Contract::select('id')->where('year_contract', date('Y',time()))->where('renouncement_contract', 1)->count();
						$t_break_count_contracts = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE contracts.year_contract='" . date('Y',time()) . "' AND contracts.number_contract like '%‐" . $department->index_department . "‐%' AND renouncement_contract=1 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$p_proekt_count_contracts = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE contracts.year_contract!='" . date('Y',time()) . "' AND contracts.number_contract like '%‐" . $department->index_department . "‐%' AND (date_signing_contract_reestr IS NULL OR date_signing_contract_counterpartie_reestr IS NULL) AND renouncement_contract=0 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$p_registr_count_contracts = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE contracts.year_contract!='" . date('Y',time()) . "' AND contracts.number_contract like '%‐" . $department->index_department . "‐%' AND (date_signing_contract_reestr IS NOT NULL AND date_signing_contract_counterpartie_reestr IS NOT NULL) AND renouncement_contract=0 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						$p_break_count_contracts = DB::SELECT("SELECT count(contracts.id) as count FROM contracts LEFT JOIN reestr_contracts ON reestr_contracts.id_contract_reestr=contracts.id WHERE contracts.year_contract!='" . date('Y',time()) . "' AND contracts.number_contract like '%‐" . $department->index_department . "‐%' AND renouncement_contract=1 AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "')")[0]->count;
						
						$proekt_t += $t_proekt_count_contracts;
						$registr_t += $t_registr_count_contracts;
						$break_t += $t_break_count_contracts;
						$proekt_p += $p_proekt_count_contracts;
						$registr_p += $p_registr_count_contracts;
						$break_p += $p_break_count_contracts;
						
						$department->t_all_count_contracts = $t_proekt_count_contracts + $t_registr_count_contracts + $t_break_count_contracts;
						$department->t_proekt_count_contracts = $t_proekt_count_contracts;
						$department->t_registr_count_contracts = $t_registr_count_contracts;
						$department->t_break_count_contracts = $t_break_count_contracts;
						
						$department->p_all_count_contracts = $p_proekt_count_contracts + $p_registr_count_contracts + $p_break_count_contracts;
						$department->p_proekt_count_contracts = $p_proekt_count_contracts;
						$department->p_registr_count_contracts = $p_registr_count_contracts;
						$department->p_break_count_contracts = $p_break_count_contracts;
						//break;
					}
					//dd($departments);
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'departments'=>$departments,
								'proekt_t'=>$proekt_t,
								'registr_t'=>$registr_t,
								'break_t'=>$break_t,
								'proekt_p'=>$proekt_p,
								'registr_p'=>$registr_p,
								'break_p'=>$break_p,
								'lider'=>$lider];
				case 'форма справка по договорам ПЭО':
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,id_counterpartie_contract,amount_contract_reestr,renouncement_contract,
											date_entry_into_force_reestr,procurement_reestr,marketing_reestr,investments_reestr,other_reestr,item_contract,archive_contract, 
											is_sip_contract,executor_contract_reestr,date_contract_on_first_reestr,id_goz_contract,goz_works.name_works_goz 
											FROM contracts 
											LEFT JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
											LEFT JOIN goz_works ON contracts.id_goz_contract=goz_works.id 
											WHERE (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
											AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL)
											ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract){
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						
						// Исполнитель
						if($contract->is_sip_contract == 1){
							$pr_executor = Curator::select()->where('id', $contract->executor_contract_reestr)->first();
							if($pr_executor != null)
								$contract->executor_contract_reestr = $pr_executor->FIO;
						}
					}
					return ['period1'=>$request['date_begin'],
								'period2'=>$request['date_end'],
								'contracts'=>$contracts,
								'lider'=>$lider];
				case 'форма участник в закупках 223':
					$text1 = 'Филиал "НТИИМ" - участник в закупках по 223-ФЗ';
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr,amount_reestr,prepayment_order_reestr,score_order_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
												WHERE number_contract LIKE '%‐" . $fil_dep . "‐%' AND procurement_reestr=1 AND marketing_fz_223_reestr=1 
												AND date_entry_into_force_reestr IS NULL 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'contracts'=>$contracts,'lider'=>$lider];
				case 'форма участник в закупках 44':
					$text1 = 'Филиал "НТИИМ" - участник в закупках по 44-ФЗ';
					$fil_dep = '%';
					if(isset($request['department'])){
						if(strlen($request['department']) > 0){
							$sel_dep = Department::select('index_department')->where('id',$request['department'])->first();
							if($sel_dep != null)
								$fil_dep = $sel_dep->index_department;
						}
					}
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr,amount_reestr,prepayment_order_reestr,score_order_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
												WHERE number_contract LIKE '%‐" . $fil_dep . "‐%' AND procurement_reestr=1 AND marketing_fz_44_reestr=1 
												AND date_entry_into_force_reestr IS NULL 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
						$protocols = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_protocol', 1)->get();
						$contract->protocols = $protocols;
						$add_agreements = Protocol::select('name_protocol','date_on_first_protocol')->where('id_contract', $contract->id)->where('is_additional_agreement', 1)->get();
						$contract->add_agreements = $add_agreements;
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'contracts'=>$contracts,'lider'=>$lider];
				case 'справка по срокам оплаты':
					$text1 = 'Справка по срокам оплаты Договоров (Контрактов) для финансового отдела';
					$contracts = DB::SELECT("SELECT contracts.id,contracts.number_contract,number_counterpartie_contract_reestr,id_counterpartie_contract,item_contract,date_contract_on_first_reestr,
												date_maturity_reestr,amount_contract_reestr,payment_order_reestr,date_contract_reestr,amount_reestr,prepayment_order_reestr,score_order_reestr, 
                                                end_term_repayment_reestr,date_entry_into_force_reestr 
												FROM contracts JOIN reestr_contracts ON contracts.id=reestr_contracts.id_contract_reestr 
												WHERE procurement_reestr=1 
												AND (renouncement_contract=0 OR renouncement_contract IS NULL) AND (archive_contract=0 OR archive_contract IS NULL) 
												AND (STR_TO_DATE(date_registration_project_reestr,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') 
												ORDER BY contracts.year_contract DESC, contracts.number_pp+0 DESC");
					foreach($contracts as $contract)
					{
						foreach($counterparties as $counter){
							if($counter->id == $contract->id_counterpartie_contract){
								$contract->counterpartie_name = $counter->name;
								break;
							}
						}
					}
					return ['text'=>$text1,'period1'=>$request['date_begin'],'period2'=>$request['date_end'],'contracts'=>$contracts,'lider'=>$lider];
					
					
					
					
					

				default:
					return 'Неизвестный тип таблицы! (' . $request['real_name_table'] . ')';
			}
		}
		else
			return 'Какая-то ошибка!';
	}
	
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contract = Contract::findOrFail($id);
		$contract->number_pp = null;
		$contract->save();
		$contract->delete();
		JournalController::store(Auth::User()->id,'Удаление контракта с id = ' . $id);
		return redirect()->back()->with('success', 'Контракт удален!');
    }
	
	public function destroy_state($id)
	{
		$state = State::findOrFail($id);
		JournalController::store(Auth::User()->id,'Удалено состояние для контракта с id = ' . $state->id_contract);
		$state->delete();
		return redirect()->back()->with('success', 'Состояние удалено!');
	}
}
