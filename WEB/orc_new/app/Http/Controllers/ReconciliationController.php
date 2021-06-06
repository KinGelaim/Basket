<?php

namespace App\Http\Controllers;

use Auth;
use App\Contract;
use App\Counterpartie;
use App\ViewWork;
use App\Resolution;
use App\Executor;
use App\ReconciliationContract;
use App\Application;
use App\Document;
use App\SecondDepartment;
use App\ViewWorkElement;
use App\Element;
use App\Checkpoint;
use App\Curator;
use App\ReestrContract;
use App\ReconciliationUser;
use App\Invoice;
use App\User;
use App\Comment;
use App\Department;
use App\ViewContract;
use App\State;
use App\Protocol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReconciliationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$link = '';
		$paginate_count = 10;
		if (isset($_GET["page"])) {
			$page  = $_GET["page"];
		} else {
			$page=1;
		};
		
		$counterparties = Counterpartie::select(['*'])->where('is_sip_counterpartie', 1)->orderBy('name', 'asc')->get();
		$counterpartie = '';
		$counerpartie_name = '';
		$counterpartie_str = "applications.id_counterpartie_application";
		$counterpartie_equal = ">";
		if(isset($_GET['counterpartie'])) {
			if($_GET['counterpartie'] != ''){
				$counerpartie_name = $_GET['counterpartie'];
				foreach($counterparties as $counter){
					if($counter->name == $counerpartie_name){
						$counterpartie = $counter->id;
						break;
					}
				}
				$counterpartie_str = "id_counterpartie_application";
				$counterpartie_equal = "=";
				$link .= "&counterpartie=" . $_GET['counterpartie'];
			}
		} else
			$counterpartie = '';
		$view_contract_str = "contracts.id_counterpartie_contract";
		$view_contract_equal = ">";
		$view_contracts = ViewContract::select('*')->where('is_sip_view_contract', 1)->get();
		if(isset($_GET['view'])) {
			if($_GET['view'] != ''){
				$view_contract = ($_GET['view']);
				$view_contract_str = "view_contracts.name_view_contract";
				$view_contract_equal = "=";
				$link .= "&view=" . $_GET['view'];
			}
			else
				$view_contract = '';
		} else
			$view_contract = '';
		$number_contract = '%';
		if(isset($_GET['search_name'])) {
			$search_name = $_GET['search_name'];
			if($search_name == 'number_contract'){
				$search_name = '';
			}				
			$link .= "&search_name=" . $_GET['search_name'];
		} else
			$search_name = '';
		if(isset($_GET['search_value'])) {
			$search_value = $_GET['search_value'];
			if($_GET['search_name'] == 'number_contract'){
				$number_contract = str_replace('-','‐',$search_value);
				$search_value = '';
			}
			$link .= "&search_value=" . $_GET['search_value'];
		} else
			$search_value = '';
		$start = ($page-1) * $paginate_count;
		if(isset($_GET['search_value']) && isset($_GET['search_name']) && $search_name != '' && $search_value != '')
			$documents = Document::select(['documents.id','documents.date_document','applications.number_application','applications.number_outgoing',
											'applications.date_outgoing','applications.number_incoming','applications.date_incoming','applications.theme_application',
											'applications.id_counterpartie_application'])
											->join('applications','id_application_document','applications.id')
											->where($counterpartie_str, $counterpartie_equal, $counterpartie)
											->where($search_name, 'like', '%' . $search_value . '%')
											->orderBy('documents.id', 'desc')
											->offset($start)
											->limit($paginate_count)
											->get();
		else
			$documents = Document::select(['documents.id','documents.date_document','applications.number_application','applications.number_outgoing',
											'applications.date_outgoing','applications.number_incoming','applications.date_incoming','applications.theme_application',
											'applications.id_counterpartie_application'])
											->join('applications','id_application_document','applications.id')
											->where($counterpartie_str, $counterpartie_equal, $counterpartie)
											->orderBy('documents.id', 'desc')
											->offset($start)
											->limit($paginate_count)
											->get();
		$result = [];
		foreach($documents as $document)
		{
			$contracts = Contract::select(['contracts.number_contract','view_contracts.name_view_contract'])
										->leftJoin('reestr_contracts','reestr_contracts.id_contract_reestr','contracts.id')
										->leftjoin('view_contracts','reestr_contracts.id_view_contract','view_contracts.id')
										->where('id_document_contract', $document->id)
										->where($view_contract_str, $view_contract_equal, $view_contract)
										->where('contracts.number_contract', 'like', '%' . $number_contract . '%')
										->get();
			if(count($contracts) > 0){
				$document->contracts = $contracts;
					if(!in_array($document->number_application,array_keys($result)))
						$result += [$document->number_application => [$document]];
					else
						array_push($result[$document->number_application],$document);
			}
			foreach($counterparties as $counter)
				if($document->id_counterpartie_application == $counter->id){
					$document->name_counterpartie_contract = $counter->name;
					break;
				}
		}

		if(isset($_GET['search_value']) && isset($_GET['search_name']) && $search_name != '' && $search_value != '')
			$document_count = Document::select(['documents.id','documents.date_document','applications.number_application','applications.number_outgoing','applications.date_outgoing','applications.number_incoming','applications.date_incoming'])
											->join('applications','id_application_document','applications.id')
											->where($counterpartie_str, $counterpartie_equal, $counterpartie)
											->where('applications.' . $search_name, 'like', '%' . $search_value . '%')
											->count();
		else
			$document_count = Document::select(['documents.id','documents.date_document','applications.number_application','applications.number_outgoing','applications.date_outgoing','applications.number_incoming','applications.date_incoming'])
											->join('applications','id_application_document','applications.id')
											->where($counterpartie_str, $counterpartie_equal, $counterpartie)
											->count();
		$prev_page = $page - 1 > 0 ? (int)($page-1) : '';
		$next_page = $page + 1 <= (int)ceil($document_count/$paginate_count) ? (int)($page+1) : '';
		return view('reconciliation.main', [
								'documents'=>$result,
								'counterpartie'=>$counterpartie,
								'counterparties'=>$counterparties,
								'viewContract'=>$view_contract,
								'viewContracts'=>$view_contracts,
								'search_name' => $search_name,
								'search_value' => $search_value,
								'count_paginate' => (int)ceil($document_count/$paginate_count),
								'prev_page' => $prev_page,
								'next_page' => $next_page,
								'page' => $page,
								'link' => $link
		]);
    }
	
	public function incoming()
	{
		$link = '';
		$paginate_count = 10;
		if (isset($_GET["page"])) {
			$page  = $_GET["page"];
		} else {
			$page=1;
		};
		
		$counterparties = Counterpartie::select(['*'])->where('is_sip_counterpartie', 1)->orderBy('name', 'asc')->get();
		$counterpartie = '';
		$counerpartie_name = '';
		$counterpartie_str = "applications.id_counterpartie_application";
		$counterpartie_equal = ">";
		if(isset($_GET['counterpartie'])) {
			if($_GET['counterpartie'] != ''){
				$counerpartie_name = $_GET['counterpartie'];
				foreach($counterparties as $counter){
					if($counter->name == $counerpartie_name){
						$counterpartie = $counter->id;
						break;
					}
				}
				$counterpartie_str = "id_counterpartie_application";
				$counterpartie_equal = "=";
				$link .= "&counterpartie=" . $_GET['counterpartie'];
			}
		} else
			$counterpartie = '';
		if(isset($_GET['search_name'])) {
			$search_name = $_GET['search_name'];
			$link .= "&search_name=" . $_GET['search_name'];
		} else
			$search_name = '';
		if(isset($_GET['search_value'])) {
			$search_value = $_GET['search_value'];
			$link .= "&search_value=" . $_GET['search_value'];
		} else
			$search_value = '';
		$start = ($page-1) * $paginate_count;
		if(isset($_GET['search_value']) && isset($_GET['search_name']) && $search_name != '' && $search_value != '')
			$applications = Application::select(['*','applications.id'])->leftJoin('documents','applications.id','documents.id_application_document')
											//->where('applications.id_contract_application', null)
											//->where('applications.id_document_application', null)
											//->where('documents.id_application_document', null)
											->where($counterpartie_str, $counterpartie_equal, $counterpartie)
											->where('applications.' . $search_name, 'like', '%' . $search_value . '%')
											->orderBy('applications.id', 'desc')
											->offset($start)
											->limit($paginate_count)
											->get();
		else
			$applications = Application::select(['*','applications.id'])->leftJoin('documents','applications.id','documents.id_application_document')
											->where($counterpartie_str, $counterpartie_equal, $counterpartie)
											->orderBy('applications.id', 'desc')
											->offset($start)
											->limit($paginate_count)
											->get();

		//dd($applications);
		if(isset($_GET['search_value']) && isset($_GET['search_name']) && $search_name != '' && $search_value != '')
			$application_count = Application::select(['*'])
									->leftJoin('documents','applications.id','documents.id_application_document')
									->where('id_contract_application', null)
									->where('id_document_application', null)
									->where('documents.id_application_document', null)
									->where($counterpartie_str, $counterpartie_equal, $counterpartie)
									->where('applications.' . $search_name, 'like', '%' . $search_value . '%')
									->count();
		else
			$application_count = Application::select(['*'])
									->leftJoin('documents','applications.id','documents.id_application_document')
									->where('id_contract_application', null)
									->where('id_document_application', null)
									->where('documents.id_application_document', null)
									->where($counterpartie_str, $counterpartie_equal, $counterpartie)
									->count();
		$prev_page = $page - 1 > 0 ? (int)($page-1) : '';
		$next_page = $page + 1 <= (int)ceil($application_count/$paginate_count) ? (int)($page+1) : '';
		
		$view_works = ViewWork::all();
		if(isset($_GET['view'])) {
			$view_work = ($_GET['view']);
			$link .= "&view=" . $_GET['view'];
		} else
			$view_work = '';
		//dump($applications);
		return view('reconciliation.incoming', [
								'applications'=>$applications,
								'counterpartie'=>$counterpartie,
								'counterparties'=>$counterparties,
								'viewWork'=>$view_work,
								'viewWorks'=>$view_works,
								'search_name' => $search_name,
								'search_value' => $search_value,
								'count_paginate' => (int)ceil($application_count/$paginate_count),
								'prev_page' => $prev_page,
								'next_page' => $next_page,
								'page' => $page,
								'link' => $link
		]);
	}
	
	public function document($number_application)
	{
		$documents = Document::select(['documents.id',
										'documents.date_document',
										'documents.id_old_document',
										'applications.id as appID',
										'applications.number_application',
										'applications.id_counterpartie_application',
										'applications.number_outgoing',
										'applications.date_outgoing',
										'applications.number_incoming',
										'applications.date_incoming',
										'applications.theme_application'])
										->join('applications','id_application_document','applications.id')
										->where('applications.number_application', $number_application)
										->orderBy('documents.id', 'desc')
										->get();
		$counterpartie = '';
		$counterparties = Counterpartie::select(['*','contr.id'])->leftjoin('curators','contr.curator','curators.id')->orderBy('name', 'asc')->get();
		foreach($documents as $document)
			foreach($counterparties as $counter)
				if($document->id_counterpartie_application == $counter->id)
				{
					$document->name_counterpartie_contract = $counter->name;
					$document->curator_contract = $counter->FIO;
					$counterpartie = $counter->name;
				}
		$result = [];
		$number_document = 0;
		$date_document = '';
		$curators = Curator::all();
		foreach($documents as $document)
		{
			$number_document = $document->number_application;
			$date_document = $document->date_document;
			$contract = Contract::select(['contracts.id','contracts.number_contract','reestr_contracts.number_counterpartie_contract_reestr','view_works.name_view_work',
										'contracts.name_work_contract','reestr_contracts.amount_reestr','reestr_contracts.amount_contract_reestr','reestr_contracts.date_maturity_date_reestr',
										'reestr_contracts.date_maturity_reestr','name_view_contract',
										'executor_contract_reestr'])
										->leftjoin('reestr_contracts','contracts.id','reestr_contracts.id_contract_reestr')
										->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
										->leftjoin('view_works','id_view_work_contract','view_works.id')
										->where('id_document_contract', $document->id)
										//->where('contracts.number_contract', '!=', null)
										->orderBy('contracts.id', 'desc')
										->get();
			foreach($contract as $in_contract)
				if($in_contract->executor_contract_reestr != null)
					foreach($curators as $curator)
						if($curator->id == $in_contract->executor_contract_reestr)
							$in_contract->curator_contract = $curator->FIO;
			array_push($result, $contract);
			/*if(!in_array($document->number_application,array_keys($result)))
			{
				$result += [$document->number_application => [$document]];
			}
			else
				array_push($result[$document->number_application],$document);*/
		}
		//dump($result);
		//dump($documents[0]->id_counterpartie_application);
		$my_applications = Application::select(['*'])->where('id_document_application', $documents[0]->id)->get();
		$applications = Application::select(['*','applications.id'])->leftJoin('documents','applications.id','documents.id_application_document')
										->where('applications.id_contract_application', null)
										->where('applications.id_document_application', null)
										->where('documents.id_application_document', null)
										->where('applications.id_counterpartie_application', $documents[0]->id_counterpartie_application)
										->orderBy('applications.id', 'desc')
										->get();
		//dump($my_applications);
		//dump($documents);
		$resolutions = Resolution::select(['*'])->where('id_application_resolution', $documents[0]->id)->get();
		$my_childs = [];
		$childs = Document::select(['documents.id',
										'documents.date_document',
										'documents.id_old_document',
										'applications.id as idApp',
										'applications.number_outgoing',
										'applications.date_outgoing',
										'applications.number_incoming',
										'applications.date_incoming',
										'applications.theme_application',
										'applications.number_application'])
										->join('applications','id_application_document','applications.id')
										->where('id_old_document', $documents[0]->id)
										->get();
		//Получение детей с помощью рекурсии
		if(count($childs) > 0){
			array_push($my_childs, $childs);
			//dump($my_childs);
			$this::recurse_search_child($my_childs);
		}
		//dd($my_childs);
		$my_parents = [];
		$parents = Document::select(['documents.id',
										'documents.date_document',
										'documents.id_old_document',
										'applications.id as idApp',
										'applications.number_outgoing',
										'applications.date_outgoing',
										'applications.number_incoming',
										'applications.date_incoming',
										'applications.theme_application',
										'applications.number_application'])
										->join('applications','id_application_document','applications.id')
										->where('documents.id', $documents[0]->id_old_document)
										->get();
		if(count($parents) > 0){
			array_push($my_parents, $parents);
			$this::recurse_search_parents($my_parents);
		}
		//Для редактирования родительской заявки
		$all_users = User::getAllFIO();
		return view('reconciliation.document', ['number_document'=>$number_document,
												'date_document'=>$date_document,
												'counterpartie'=>$counterpartie,
												'documents'=>$documents,
												'result'=>$result,
												'applications'=>$applications,
												'my_applications'=>$my_applications,
												'resolutions'=>$resolutions,
												'my_parents'=>$my_parents,
												'my_childs'=>$my_childs,
												'all_users'=>$all_users
		]);
	}
	
	private function recurse_search_child(&$my_childs)
	{
		//global $my_childs;
		//dump($my_childs);
		foreach($my_childs as $childs)
		{
			foreach($childs as $child)
			{
				$new_my_childs = [];
				$new_childs = Document::select(['documents.id',
											'documents.date_document',
											'documents.id_old_document',
											'applications.id as idApp',
											'applications.number_outgoing',
											'applications.date_outgoing',
											'applications.number_incoming',
											'applications.date_incoming',
											'applications.theme_application',
											'applications.number_application'])
											->join('applications','id_application_document','applications.id')
											->where('id_old_document', $child->id)
											->get();
				if(count($new_childs) > 0){
					array_push($new_my_childs, $new_childs);
					$this::recurse_search_child($new_my_childs);
					if(count($new_my_childs) > 0)
						foreach($new_my_childs as $new_child)
							array_push($my_childs, $new_child);
					//dump($my_childs);
				}
			}
		}
	}
	
	private function recurse_search_parents(&$my_parents)
	{
		foreach($my_parents as $parents)
		{
			foreach($parents as $parent)
			{
				$new_my_parents = [];
				$new_parents = Document::select(['documents.id',
											'documents.date_document',
											'documents.id_old_document',
											'applications.id as idApp',
											'applications.number_outgoing',
											'applications.date_outgoing',
											'applications.number_incoming',
											'applications.date_incoming',
											'applications.theme_application',
											'applications.number_application'])
											->join('applications','id_application_document','applications.id')
											->where('documents.id', $parent->id_old_document)
											->get();
				if(count($new_parents) > 0){
					array_push($new_my_parents, $new_parents);
					$this::recurse_search_parents($new_my_parents);
					if(count($new_my_parents) > 0)
						foreach($new_my_parents as $new_parent)
							array_push($my_parents, $new_parent);
				}
			}
		}
	}
	
	public function create_document($id_application)
    {
		$document = new Document();
		$document->fill(['id_application_document'=>$id_application,'date_document'=>date('d.m.Y', time())]);
		$document->save();
		$application = Application::find($id_application);
        return redirect()->route('department.reconciliation.document', $application->number_application);
    }
	
	public function create_new_document($id_application)
    {
		$application = Application::find($id_application);
		$document = new Document();
		$document->fill(['id_application_document'=>$id_application,'id_old_document'=>$application->id_document_application,'date_document'=>date('d.m.Y', time())]);
		$document->save();
		$application->id_document_application = null;
		$application->save();
        return redirect()->route('department.reconciliation');
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
		//Сохранение страницы текущей
		$links = session()->has('links') ? session('links') : [];
		$current_link = request()->path();
		array_unshift($links, $current_link);
		session(['links' => $links]);
		//
		$contract = Contract::select(['id_document_contract','id_counterpartie_contract','number_contract','name_work_contract','id_goz_contract','goz_works.name_works_goz','id_view_work_contract', 'view_works.name_view_work',
										'renouncement_contract','archive_contract','reconciliation_contract','date_contact','year_contract','executors.*','contracts.id','name_view_contract',
										'id_view_contract','amoun_implementation_contract','comment_implementation_contract'])
							->Leftjoin('executors', 'contracts.id','executors.id_contract')
							->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
							->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
							->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
							->join('goz_works', 'contracts.id_goz_contract', '=', 'goz_works.id')
							->where('contracts.id',$id)->get()[0];
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		foreach($counterparties as $counter)
			if($contract->id_counterpartie_contract == $counter->id)
				$contract->name_counterpartie_contract = $counter->name;
		$resolutions = Resolution::select(['*'])->where('id_contract_resolution', $contract->id)->get();
		$reconciliations = ReconciliationContract::select(['*'])->where('id_contract_reconciliation', $contract->id)->get();
		/*$applications = Application::select(['*','applications.id'])
						->leftJoin('documents','applications.id','documents.id_application_document')
						->where('id_contract_application', null)
						->where('id_document_application', null)
						->where('documents.id_application_document', null)
						->where('applications.id_counterpartie_application', $contract->id_counterpartie_contract)
						->get();*/
		$my_applications = Application::select(['*'])->where('id_contract_application', $id)->get();
		$document = Document::select(['applications.number_application','documents.id'])->join('applications','documents.id_application_document','applications.id')->where('documents.id', $contract->id_document_contract)->get()->first();
		$applications = null;
		if($document)
			$applications = Application::select(['*'])->where('id_document_application', $document->id)->where('id_contract_application', null)->where('is_protocol', 0)->get();
		$number_application = '';
		if($document)
			$number_application = $document->number_application;
		$secondDepartments = SecondDepartment::select(['*','second_department.id', 'elements.id as elID'])->join('elements', 'id_element', 'elements.id')
											->LeftJoin('view_work_elements', 'id_view_work_elements', 'view_work_elements.id')
											->where('id_contract', $id)
											->orderBy('second_department.id', 'desc')
											->get();
		$view_work_elements = ViewWorkElement::all();
		$elements = Element::all();
		//dump($secondDepartments);
		$checkpoints = Checkpoint::select(['*'])->where('id_contract', $contract->id)->get();
		$curators = Curator::all();
		$reestr = ReestrContract::select(['executor_contract_reestr', 
											'amount_reestr',
											'amount_contract_reestr',											
											'fix_amount_contract_reestr', 
											'date_maturity_date_reestr', 
											'date_maturity_reestr',
											'date_contract_on_first_reestr',
											'number_counterpartie_contract_reestr',
											'vat_reestr',
											'prepayment_reestr',
											'big_deal_reestr'])
											->where('id_contract_reestr', $contract->id)
											->first();
		if($reestr == null)
			$reestr = new ReestrContract();
		$directed_list = ReconciliationUser::select(['reconciliation_users.id as recID',
														'reconciliation_users.check_reconciliation',
														'reconciliation_users.check_agree_reconciliation',
														'users.id as userID',
														'users.surname', 
														'users.name', 
														'users.patronymic'
												])->join('users', 'users.id', 'id_user')->where('id_contract',$contract->id)->get();
		//Счета по предприятию
		$amount_scores_all = 0;
		$amount_prepayments_all = 0;
		$amount_invoices_all = 0;
		$amount_payments_all = 0;
		$amount_returns_all = 0;
		$invoices = Invoice::select(['invoices.amount_p_invoice', 'name_invoices.name', 'is_prepayment_invoice'])
									->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
									->join('contracts', 'contracts.id', 'invoices.id_contract')
									->where('contracts.id_counterpartie_contract', $contract->id_counterpartie_contract)
									->get();
		foreach($invoices as $score)
			if($score->name == 'Счет на оплату')
				$amount_scores_all += $score->amount_p_invoice;
			else if($score->name == 'Счет-фактура')
				$amount_invoices_all += $score->amount_p_invoice;
			else if($score->name == 'Оплата')
				if($score->is_prepayment_invoice == 0)
					$amount_payments_all += $score->amount_p_invoice;
				else
					$amount_prepayments_all += $score->amount_p_invoice;
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
		$all_protocols = ReconciliationUser::select(['reconciliation_users.id',
												'applications.number_application', 
												'applications.number_outgoing', 
												'applications.date_outgoing', 
												'applications.number_incoming', 
												'applications.date_incoming', 
												'applications.name_protocol',
												'applications.comment_application',
												'reconciliation_users.check_agree_reconciliation'
											])->join('applications', 'applications.id', 'id_application')
											->where('applications.is_protocol', 1)
											->where('reconciliation_users.is_protocol', 1)
											->where('id_contract_application', $id)
											->orderBy('applications.number_application', 'asc')
											->get();
		//dd($all_protocols);
		$protocols = [];
		$pr_number_application = -1;
		foreach($all_protocols as $protocol){
			$protocol->check_reconciliation = true;
			if($protocol->number_application != $pr_number_application){
				if($protocol->check_agree_reconciliation == 0)
					$protocol->check_reconciliation = false;
				array_push($protocols, $protocol);
				$pr_number_application = $protocol->number_application;
			}else{
				if($protocol->check_agree_reconciliation == 0)
					foreach($protocols as $pr)
						if($pr->number_application == $protocol->number_application)
							$pr->check_reconciliation = false;
			}
		}
		//dd($protocols);
		//Извлечение протоколов и дополнительных соглашений из ОУД
		$protocols_oud = Protocol::select(['*'])->where('id_contract', $contract->id)->get();
		$all_users = User::getAllFIO();
		$departments = Department::select()->orderBy('index_department', 'asc')->get();
		$view_contracts = ViewContract::select()->where('is_sip_view_contract', 1)->get();
		$states = State::select(['states.id','name_state','date_state','users.name','users.surname','users.patronymic'])->leftJoin('users','users.id','states.id_user')->where('id_contract', $id)->get();
		if(count($states) > 0)
			$state = $states[count($states)-1];
		else
			$state = [];
		//Формат значений
		if(is_numeric($reestr->amount_reestr))
			$reestr->amount_reestr = number_format($reestr->amount_reestr, 2, '.', ' ');
		if(is_numeric($reestr->amount_contract_reestr))
			$reestr->amount_contract_reestr = number_format($reestr->amount_contract_reestr, 2, '.', ' ');
		if(is_numeric($reestr->prepayment_reestr))
			$reestr->prepayment_reestr = number_format($reestr->prepayment_reestr, 2, '.', ' ');
		if(is_numeric($contract->amoun_implementation_contract))
			$contract->amoun_implementation_contract = number_format($contract->amoun_implementation_contract, 2, '.', ' ');
		return view('reconciliation.contract', ['contract'=>$contract, 
												'applications'=>$applications, 
												'my_applications'=>$my_applications, 
												'resolutions'=>$resolutions, 
												'reconciliations'=>$reconciliations, 
												'number_application'=>$number_application, 
												'isps'=>$secondDepartments,
												'view_work_elements'=>$view_work_elements,
												'elements'=>$elements,
												'checkpoints'=>$checkpoints,
												'curators'=>$curators,
												'reestr'=>$reestr,
												'directed_list'=>$directed_list,
												'amount_scores_all'=>$amount_scores_all,
												'amount_prepayments_all'=>$amount_prepayments_all,
												'amount_invoices_all'=>$amount_invoices_all,
												'amount_payments_all'=>$amount_payments_all,
												'amount_returns_all'=>$amount_returns_all,
												'amount_scores'=>$amount_scores,
												'amount_prepayments'=>$amount_prepayments,
												'amount_invoices'=>$amount_invoices,
												'amount_payments'=>$amount_payments,
												'amount_returns'=>$amount_returns,
												'protocols'=>$protocols,
												'all_users'=>$all_users,
												'departments'=>$departments,
												'viewContracts'=>$view_contracts,
												'states'=>$states,
												'protocols_oud'=>$protocols_oud
		]);
    }
	
	public function print_reconciliation($id)
	{
		$number_contract = Contract::select(['number_contract'])->where('contracts.id',$id)->get()[0];
		$directed_list = ReconciliationUser::select(['reconciliation_users.id as recID',
														'reconciliation_users.check_reconciliation',
														'reconciliation_users.check_agree_reconciliation',
														'reconciliation_users.date_check_reconciliation',
														'reconciliation_users.date_check_agree_reconciliation',
														'reconciliation_users.created_at',
														'users.id as userID',
														'users.surname', 
														'users.name', 
														'users.patronymic'
												])->join('users', 'users.id', 'id_user')->where('id_contract',$id)->get();
		foreach($directed_list as $directed)
		{
			$comments = Comment::select()->where('author', $directed->userID)->where('id_contract',$id)->get();
			$directed->date_outgoing = date('d.m.Y H:i:s', strtotime($directed->created_at));
			$directed->comments = $comments;
		}
		return view('reconciliation.print_reconciliation_contract', ['directed_list'=>$directed_list, 'number_contract'=>$number_contract]);
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
		//dd($request->all());
        /*$val = Validator::make($request->all(),[
			'id_counterpartie_application' => 'required',
			'number_application' => 'required|unique:applications',
			'date_outgoing' => 'nullable|date',
			'date_incoming' => 'nullable|date',
			'date_directed' => 'nullable|date',
		])->validate();*/
		$val_goz_contract = $request['goz_contract'] ? 1 : ($request['export_contract'] ? 2 : 3);
		$contract = Contract::findOrFail($id);
		$contract->fill([
						'name_work_contract' => $request['name_work_contract'],
						'renouncement_contract' => $request['renouncement_contract'] ? 1 : 0,
						'archive_contract' => $request['archive_contract'] ? 1 : 0,
						'reconciliation_contract' => $request['reconciliation_contract'] ? 1 : 0,
						'id_goz_contract' => $val_goz_contract,
						'amoun_implementation_contract' => $request['amoun_implementation_contract'],
						'comment_implementation_contract' => $request['check_implementation_contract'] ? 'Выполнен' : $request['comment_implementation_contract']
		]);
		$reestr = ReestrContract::firstOrNew(['id_contract_reestr' => $id]);
		$reestr->fill([
						'id_contract_reestr' => $id,
						'id_view_contract' => $request['id_view_contract'],
						'number_counterpartie_contract_reestr' => $request['number_counterpartie_contract_reestr'],
						'amount_reestr' => $request['amount_reestr'],
						'amount_contract_reestr' => $request['amount_contract_reestr'],
						'fix_amount_contract_reestr' => $request['fix_amount_contract_reestr'] ? 1 : 0,
						'date_contract_on_first_reestr' => $request['date_contract_on_first_reestr'],
						'date_maturity_date_reestr' => $request['date_maturity_date_reestr'],
						'date_maturity_reestr' => $request['date_test'] ? $request['date_textarea'] : null,
						'executor_contract_reestr' => $request['executor_contract_reestr'],
						'vat_reestr' => $request['vat_reestr'] ? 1 : 0,
						'prepayment_reestr' => $request['prepayment_reestr'],
						'big_deal_reestr' => $request['big_deal_reestr'] ? 1 : 0,
						'marketing_goz_reestr' => $request['goz_contract'] ? 1 : 0,
						'export_reestr' => $request['export_contract'] ? 1 : 0,
						'interfactory_reestr' => $request['other_contract'] ? 1 : 0
		]);
		if($request['amount_reestr'])
			$reestr->amount_reestr = str_replace(' ','',$reestr->amount_reestr);
		if($request['amount_contract_reestr'])
			$reestr->amount_contract_reestr = str_replace(' ','',$reestr->amount_contract_reestr);
		if($request['prepayment_reestr'])
			$reestr->prepayment_reestr = str_replace(' ','',$reestr->prepayment_reestr);
		if($request['amoun_implementation_contract'])
			$contract->amoun_implementation_contract = str_replace(' ','',$contract->amoun_implementation_contract);
		$reestr->save();
		//dump($contract);
		$contract->save();
		$executor = Executor::firstOrNew(['id_contract' => $id]);
		$executor->fill(['id_contract' => $id,
					'isp_dir' => $request['isp_dir'] ? 1 : 0,
					'zam_isp_dir_niokr' => $request['zam_isp_dir_niokr'] ? 1 : 0,
					'main_in' => $request['main_in'] ? 1 : 0,
					'dir_sip' => $request['dir_sip'] ? 1 : 0,
					'dir_peo' => $request['dir_peo'] ? 1 : 0,
					'isp_dir_check' => $request['isp_dir_check'] ? 1 : 0,
					'zam_isp_dir_niokr_check' => $request['zam_isp_dir_niokr_check'] ? 1 : 0,
					'main_in_check' => $request['main_in_check'] ? 1 : 0,
					'dir_sip_check' => $request['dir_sip_check'] ? 1 : 0,
					'dir_peo_check' => $request['dir_peo_check'] ? 1 : 0,
					'dep_2' => $request['dep_2'] ? 1 : 0,
					'dep_15' => $request['dep_15'] ? 1 : 0,
					'dep_93' => $request['dep_93'] ? 1 : 0,
					'dep_main_tech' => $request['dep_main_tech'] ? 1 : 0,
					'dep_10' => $request['dep_10'] ? 1 : 0,
					'shop_1' => $request['shop_1'] ? 1 : 0,
					'shop_2' => $request['shop_2'] ? 1 : 0,
					'shop_3' => $request['shop_3'] ? 1 : 0,
					'ootiz' => $request['ootiz'] ? 1 : 0,
					'dep_2_check' => $request['dep_2_check'] ? 1 : 0,
					'dep_15_check' => $request['dep_15_check'] ? 1 : 0,
					'dep_93_check' => $request['dep_93_check'] ? 1 : 0,
					'dep_main_tech_check' => $request['dep_main_tech_check'] ? 1 : 0,
					'dep_10_check' => $request['dep_10_check'] ? 1 : 0,
					'shop_1_check' => $request['shop_1_check'] ? 1 : 0,
					'shop_2_check' => $request['shop_2_check'] ? 1 : 0,
					'shop_3_check' => $request['shop_3_check'] ? 1 : 0,
					'ootiz_check' => $request['ootiz_check'] ? 1 : 0,
		]);
		//dump($executor);
		$executor->save();
		JournalController::store(Auth::User()->id,'Обновление карточки контракта с id = ' . $contract->id);
		return redirect()->back()->with('success','Успешно изменен!');
    }
	
	public function create_process(Request $request, $id)
	{
		//dump($request->all());
        $val = Validator::make($request->all(),[
			'process_reconciliation' => 'required',
			'b_date_reconciliation' => 'required|date',
		])->validate();
		$reconciliation = new ReconciliationContract();
		$reconciliation->fill(['id_contract_reconciliation' => $id,
					'process_reconciliation' => $request['process_reconciliation'],
					'b_date_reconciliation' => $request['b_date_reconciliation'],
		]);
		$reconciliation->save();
		return redirect()->back();
	}
	
	public function end_date($id)
	{
		$reconciliation = ReconciliationContract::findOrFail($id);
		$reconciliation->fill(['e_date_reconciliation' => date('d.m.Y',time())]);
		$reconciliation->save();
		return redirect()->back();
	}
	
	public function reconciliation_document_message_show($id_doc)
	{
		$document = Document::find($id_doc);
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		$application = Application::select(['*'])->where('id', $document->id_application_document)->first();
		$last_number_application = Application::select()->withTrashed()->max('number_application');
		$all_users = User::getAllFIO();
		$counterpartie = '';
		$id_counterpartie = '';
		
		foreach($counterparties as $counter){
			if($counter->id == $application->id_counterpartie_application){
				$counterpartie = $counter->name;
				$id_counterpartie = $counter->id;
				break;
			}
		}
			
		$applications = Application::select(['*','applications.id'])->leftJoin('documents','applications.id','documents.id_application_document')
										->where('applications.id_contract_application', null)
										->where('applications.id_document_application', null)
										->where('documents.id_application_document', null)
										->where('id_counterpartie_application', $application->id_counterpartie_application)
										->orderBy('applications.id', 'desc')
										->get();

		return view('reconciliation.message', [
								'application'=>$application,
								'applications'=>$applications,
								'id_counterpartie'=>$id_counterpartie,
								'counterpartie'=>$counterpartie,
								'document'=>$document,
								'last_number_application'=>$last_number_application,
								'all_users'=>$all_users
		]);
	}
	
	public function reconciliation_document_message_store(Request $request, $id)
	{
		$document = Document::select(['applications.number_application'])->join('applications', 'id_application_document', 'applications.id')->where('documents.id', $id)->first();
		if($request['select_message'])
			foreach($request['select_message'] as $app)
			{
				if($app != null)
				{
					$application = Application::findOrFail($app);
					$application->fill(['id_document_application'=>$id]);
					$application->save();
				}
			}
		return redirect()->route('department.reconciliation.document', $document->number_application);
	}
	
	public function reconciliation_document_message_destroy(Request $request, $id)
	{
		$application = Application::findOrFail($id);
		$application->id_document_application = null;
		$application->save();
		return redirect()->back();
	}
	
	public function reconciliation_contract_message(Request $request, $id)
	{
		//dd($request->all());
		if($request['select_message'])
			foreach($request['select_message'] as $app)
			{
				if($app != null)
				{
					$application = Application::find($app);
					$application->fill(['id_contract_application'=>$id]);
					$application->save();
				}
			}
		return redirect()->back()->with('create_message',true);
	}
	
	public function reconciliation_contract_message_destroy($id)
	{
		$application = Application::findOrFail($id);
		$application->id_contract_application = null;
		$application->is_protocol = 0;
		$application->is_additional_agreement = null;
		$application->name_protocol = '';
		$application->comment_application = '';
		$application->save();
		$reconciliation_users_protocols = ReconciliationUser::select()->join('applications', 'applications.id', 'id_application')
											->where('reconciliation_users.is_protocol', 1)
											->where('id_application', $application->id)
											->delete();
		$comments_protocols = Comment::select()
									->where('id_application', $application->id)
									->where('is_protocol', 1)
									->where('is_document', 0)
									->delete();
		return redirect()->back()->with('delete_message',true);
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($number_application)
    {
		$documents = Document::select(['documents.id','documents.date_document','documents.id_old_document','applications.number_application','applications.id_counterpartie_application'])
										->join('applications','id_application_document','applications.id')
										->where('applications.number_application', $number_application)
										->orderBy('documents.id', 'desc')
										->get();
		$proverka = true;
		foreach($documents as $document)
		{
			$contracts = Contract::select(['contracts.id','contracts.number_contract','reestr_contracts.number_counterpartie_contract_reestr','view_works.name_view_work',
										'contracts.name_work_contract','reestr_contracts.amount_contract_reestr','reestr_contracts.date_maturity_reestr'])
										->leftjoin('reestr_contracts','contracts.id','reestr_contracts.id_contract_reestr')
										->leftjoin('view_works','id_view_work_contract','view_works.id')
										->where('id_document_contract', $document->id)
										->where('contracts.number_contract', '!=', null)
										->orderBy('contracts.id', 'desc')
										->get();
			if(count($contracts) > 0){
				$proverka = false;
				break;
			}
		}
		if($proverka){
			//TODO: удаление ДОКУМЕНТА! Так как у него нет контрактов!
			foreach($documents as $document){
				$document->delete();
			}
		}
		return redirect()->back();
    }
}
