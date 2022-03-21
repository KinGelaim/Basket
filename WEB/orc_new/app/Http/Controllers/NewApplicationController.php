<?php

namespace App\Http\Controllers;

use Auth;
use App\Contract;
use App\ReestrContract;
use App\Application;
use App\Document;
use App\Counterpartie;
use App\NewApplication;
use App\Resolution;
use App\Curator;
use App\User;
use App\ReconciliationUser;
use App\Comment;
use App\NewApplicationContraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NewApplicationController extends Controller
{
    public function index()
    {
		$link = '';
		$paginate_count = 10;
		if (isset($_GET["page"])) {
			$page  = $_GET["page"];
		} else {
			$page=1;
		};
		
		$method = 'id_counterpartie_new_application';
		$method_equal = '>';
		$method_value = 0;
		if (isset($_GET['method']))
		{
			if ($_GET['method'] == 'dk')
			{
				$method = 'is_contract_new_application';
				$method_equal = '=';
				$method_value = 1;
				$link .= '&method=dk';
			}
			else if ($_GET['method'] == 'rkm')
			{
				$method = 'is_rkm_new_application';
				$method_equal = '=';
				$method_value = 1;
				$link .= '&method=rkm';
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
		//Поиск по контрагентам
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		$counterpartie = '';
		$counerpartie_name = '';
		$counterpartie_str = "id_counterpartie_new_application";
		$counterpartie_equal = ">";
		if(isset($_GET['counterpartie'])) {
			if(strlen($_GET['counterpartie']) > 0) {
				$counerpartie_name = $_GET['counterpartie'];
				foreach($counterparties as $counter){
					if($counter->name == $counerpartie_name){
						$counterpartie = $counter->id;
						break;
					}
				}
				$counterpartie_str = "id_counterpartie_new_application";
				$counterpartie_equal = "=";
				$link .= "&counterpartie=" . $_GET['counterpartie'];
			}
		}
		
		$start = ($page-1) * $paginate_count;
		if(isset($_GET['search_value']) && isset($_GET['search_name']) && $search_name != '' && $search_value != ''){
			$new_applications = NewApplication::select()
											->where($method, $method_equal, $method_value)
											->where($counterpartie_str, $counterpartie_equal, $counterpartie)
											->where($search_name, 'like', '%' . $search_value . '%')
											->orderBy('new_applications.id', 'desc')
											->offset($start)
											->limit($paginate_count)
											->get();										
			$applications_count = NewApplication::select()
											->where($method, $method_equal, $method_value)
											->where($counterpartie_str, $counterpartie_equal, $counterpartie)
											->where($search_name, 'like', '%' . $search_value . '%')
											->count();
		}else{
			$new_applications = NewApplication::select()
											->where($method, $method_equal, $method_value)
											->where($counterpartie_str, $counterpartie_equal, $counterpartie)
											->orderBy('new_applications.id', 'desc')
											->offset($start)
											->limit($paginate_count)
											->get();										
			$applications_count = NewApplication::select()
											->where($method, $method_equal, $method_value)
											->where($counterpartie_str, $counterpartie_equal, $counterpartie)
											->count();

		}

		$prev_page = $page - 1 > 0 ? (int)($page-1) : '';
		$next_page = $page + 1 <= (int)ceil($applications_count/$paginate_count) ? (int)($page+1) : '';
		
		//$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		foreach($new_applications as $new_application)
			foreach($counterparties as $counter)
				if($new_application->id_counterpartie_new_application == $counter->id){
					$new_application->full_name_counterpartie_contract = $counter->name_full;
					$new_application->name_counterpartie_contract = $counter->name;
					$new_application->inn_counterpartie_contract = $counter->inn;
					break;
				}

		return view('reconciliation.new_applications', [
								'new_applications'=>$new_applications,
								'counterparties'=>$counterparties,
								'counterpartie'=>$counterpartie,
								'count_paginate' => (int)ceil($applications_count/$paginate_count),
								'method' => $method,
								'search_name' => $search_name,
								'search_value' => $search_value,
								'prev_page' => $prev_page,
								'next_page' => $next_page,
								'page' => $page,
								'link' => $link
		]);
    }
	
	public function create()
	{
		if (isset($_GET['method']))
		{
			$new_application = new NewApplication();
			if ($_GET['method'] == 'is_contract_new_application')
				$new_application->is_contract_new_application = 1;
			else if ($_GET['method'] == 'is_rkm_new_application')
				$new_application->is_rkm_new_application = 1;
			else
				return redirect()->back()->with('error', 'Ошибка!');
			
			$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();

			$resolutions = [];
			$resolutions_roll = [];
			$type_resolutions = DB::SELECT('SELECT * FROM type_resolutions');
			
			return view('reconciliation.new_application_show', ['is_new_application'=>1, 'method'=>$_GET['method'], 'new_application'=>$new_application, 'resolutions'=>$resolutions, 'resolutions_roll'=>$resolutions_roll, 'type_resolutions'=>$type_resolutions, 'directed_list'=>[], 'comments'=>[], 'contractions'=>[]]);
		}
		
		return redirect()->back()->with('error', 'Ошибка!');
	}
	
	public function store(Request $request)
	{
        $val = $request->validate([
			'date_registration_new_application' => 'required',
			'id_counterpartie_new_application' => 'required',
			'item_new_application' => 'required'
		]);
		
		$new_application = new NewApplication();
		
		if ($request['method'] == 'is_contract_new_application')
			$new_application->is_contract_new_application = 1;
		else if ($request['method'] == 'is_rkm_new_application')
			$new_application->is_rkm_new_application = 1;
		else
			return redirect()->back()->with('error', 'Ошибка!');
		$new_application->count_dk_new_application = 0;

		$new_application->fill($request->all());
		$new_application->on_dk_new_application = $request['on_dk_new_application'] ? 1 : 0;
		$new_application->call_price_new_application = $request['call_price_new_application'] ? 1 : 0;
		$new_application->result_vp_new_application = $request['result_vp_new_application'] ? 1 : 0;
		$new_application->rkm_new_application = $request['rkm_new_application'] ? 1 : 0;
		$new_application->other_new_application = $request['other_new_application'] ? 1 : 0;
		$new_application->isp_new_application = $request['isp_new_application'] ? 1 : 0;
		$new_application->goz_new_application = $request['goz_new_application'] ? 1 : 0;
		$new_application->interfactory_new_application = $request['interfactory_new_application'] ? 1 : 0;
		$new_application->sb_new_application = $request['sb_new_application'] ? 1 : 0;
		$new_application->export_new_application = $request['export_new_application'] ? 1 : 0;
		$new_application->view_other_new_application = $request['view_other_new_application'] ? 1 : 0;
		$new_application->storage_new_application = $request['storage_new_application'] ? 1 : 0;
		$new_application->agree_new_application = $request['agree_new_application'] ? 1 : 0;
		$new_application->rejection_new_application = $request['rejection_new_application'] ? 1 : 0;
		$new_application->check_approximate_new_application = $request['check_approximate_new_application'] ? 1 : 0;
		$new_application->check_fixed_new_application = $request['check_fixed_new_application'] ? 1 : 0;
		
		$all_dirty = JournalController::getMyChanges($new_application);
		$new_application->save();
		JournalController::store(Auth::User()->id,'Создана заявка с id = ' . $new_application->id . '~' . json_encode($all_dirty));
		
		$new_application->number_pp_new_application = $new_application->id;
		$new_application->save();
		
		return redirect()->route('new_applications.show', $new_application->id)->with('success', 'Заявка создана!');
	}
	
	public function show($id_new_application)
	{
		$new_application = NewApplication::findOrFail($id_new_application);
		
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		foreach($counterparties as $counter)
			if($new_application->id_counterpartie_new_application == $counter->id){
				$new_application->full_name_counterpartie_contract = $counter->name_full;
				$new_application->name_counterpartie_contract = $counter->name;
				$new_application->inn_counterpartie_contract = $counter->inn;
				break;
			}

		$resolutions = Resolution::select(['*'])->where('id_new_application_resolution', $id_new_application)->orderBy('id','desc')->get();
		$resolutions_roll = Resolution::select(['*'])->where('id_new_application_roll_resolution', $id_new_application)->orderBy('id','desc')->get();
		$type_resolutions = DB::SELECT('SELECT * FROM type_resolutions');
		
		$directed_list = ReconciliationUser::select(['reconciliation_users.id as recID',
												'reconciliation_users.check_reconciliation',
												'reconciliation_users.date_check_reconciliation',
												'reconciliation_users.check_agree_reconciliation',
												'reconciliation_users.date_check_agree_reconciliation',
												'users.id as userID',
												'users.surname', 
												'users.name', 
												'users.patronymic'
										])->join('users', 'users.id', 'id_user')
										->where('id_new_application', $id_new_application)
										->get();
										
		foreach($directed_list as $dir_user){
			$comments = Comment::select(['comments.id as commentID', 'comments.message', 'users.surname', 'users.name', 'users.patronymic', 'comments.created_at'])
							->join('users', 'users.id', 'author')
							->where('id_new_application', $id_new_application)
							->where('author', $dir_user->userID)
							->orderBY('comments.id', 'DESC')
							->get();
			$dir_user->comments = $comments;
		}
		
		// Заключения
		$contractions = NewApplicationContraction::select()->where('id_new_application', $id_new_application)->get();
		
		return view('reconciliation.new_application_show', ['new_application'=>$new_application, 'resolutions'=>$resolutions, 'resolutions_roll'=>$resolutions_roll, 'type_resolutions'=>$type_resolutions, 'directed_list'=>$directed_list, 'contractions'=>$contractions]);
	}
	
	public function update(Request $request, $id_new_application)
	{
		$new_application = NewApplication::findOrFail($id_new_application);
		
		$new_application->fill($request->all());
		$new_application->on_dk_new_application = $request['on_dk_new_application'] ? 1 : 0;
		$new_application->call_price_new_application = $request['call_price_new_application'] ? 1 : 0;
		$new_application->result_vp_new_application = $request['result_vp_new_application'] ? 1 : 0;
		$new_application->rkm_new_application = $request['rkm_new_application'] ? 1 : 0;
		$new_application->other_new_application = $request['other_new_application'] ? 1 : 0;
		$new_application->isp_new_application = $request['isp_new_application'] ? 1 : 0;
		$new_application->goz_new_application = $request['goz_new_application'] ? 1 : 0;
		$new_application->interfactory_new_application = $request['interfactory_new_application'] ? 1 : 0;
		$new_application->sb_new_application = $request['sb_new_application'] ? 1 : 0;
		$new_application->export_new_application = $request['export_new_application'] ? 1 : 0;
		$new_application->view_other_new_application = $request['view_other_new_application'] ? 1 : 0;
		$new_application->storage_new_application = $request['storage_new_application'] ? 1 : 0;
		$new_application->agree_new_application = $request['agree_new_application'] ? 1 : 0;
		$new_application->rejection_new_application = $request['rejection_new_application'] ? 1 : 0;
		$new_application->check_approximate_new_application = $request['check_approximate_new_application'] ? 1 : 0;
		$new_application->check_fixed_new_application = $request['check_fixed_new_application'] ? 1 : 0;
		
		$all_dirty = JournalController::getMyChanges($new_application);
		JournalController::store(Auth::User()->id,'Изменена заявка с id = ' . $new_application->id . '~' . json_encode($all_dirty));
		$new_application->save();
		
		return redirect()->back();
	}
	
	public function create_contract(Request $request, $id_new_application)
	{
		NewApplicationController::update($request, $id_new_application);
		
		$new_application = NewApplication::findOrFail($id_new_application);
		
		$last_number_application = Application::select()->withTrashed()->max('number_application');
		$application = new Application();
		$application->fill([
						'id_counterpartie_application' => $new_application->id_counterpartie_new_application,
						'number_application' => $last_number_application+1,
						'date_application' => date('Y-m-d', time())
		]);
		$application->save();
		
		$new_document = new Document();
		$new_document->fill([
						'id_application_document' => $application->id,
						'date_document' => date('Y-m-d', time())
		]);
		$new_document->save();
		
		$var_is_goz = 4;
		if($new_application->goz_new_application)
			$var_is_goz = 1;
		else if($new_application->export_new_application)
			$var_is_goz = 2;
		else if($new_application->interfactory_new_application)
			$var_is_goz = 3;
		$contract = new Contract();
		$contract->fill([
						'year_contract' => date('Y', time()),
						'id_goz_contract' => $var_is_goz,
						'id_counterpartie_contract' => $new_application->id_counterpartie_new_application,
						'name_work_contract' => $new_application->name_work_new_application,
						'item_contract' => $new_application->item_new_application,
						'is_sip_contract' => 1,
						'id_document_contract' => $new_document->id,
						'id_new_application_contract' => $new_application->id
		]);
		$all_dirty = JournalController::getMyChanges($contract);
		$contract->save();
		
		//$new_application->id_contract_new_application = $contract->id;
		
		// Увеличиваем количество договоров по заявке
		if($new_application->count_dk_new_application == null)
			$new_application->count_dk_new_application = 1;
		else
			$new_application->count_dk_new_application = $new_application->count_dk_new_application + 1;
		$new_application->save();
		
		// Копируем сканы
		$resolutions = Resolution::select(['*'])->where('id_new_application_resolution', $new_application->id)->orderBy('id','desc')->get();
		foreach($resolutions as $resolution)
		{
			$copy_resolution = new Resolution();
			$copy_resolution->id_user = $resolution->id_user;
			$copy_resolution->id_contract_resolution = $contract->id;
			$copy_resolution->real_name_resolution = $resolution->real_name_resolution;
			$copy_resolution->path_resolution = $resolution->path_resolution;
			$copy_resolution->date_resolution = $resolution->date_resolution;
			$copy_resolution->type_resolution = $resolution->type_resolution;
			$copy_resolution->created_at = $resolution->created_at;
			$copy_resolution->save();
		}
		
		$reestr = new ReestrContract();
		$reestr->fill([
						'id_contract_reestr' => $contract->id,
						'marketing_goz_reestr' => $new_application->goz_new_application ? 1 : 0,
						'export_reestr' => $new_application->export_new_application ? 1 : 0,
						'interfactory_reestr' => $new_application->interfactory_new_application ? 1 : 0,
						'other_reestr' => $new_application->view_other_new_application ? 1 : 0,
						'app_outgoing_number_reestr' => $new_application->number_outgoing_new_application,
						'app_incoming_number_reestr' => $new_application->number_incoming_new_application,
						'date_registration_application_reestr' => $new_application->date_registration_new_application
		]);
		$all_dirty = JournalController::getMyChanges($reestr, $all_dirty);
		$reestr->save();
		JournalController::store(Auth::User()->id,'Создание СИП контракта из заявки с id = ' . $contract->id . '~' . json_encode($all_dirty));
		return redirect()->route('department.ekonomic.contract_new_reestr', $contract->id)->with(['success'=>'Контракт сохранен!','del_history'=>'1']);
	}
	
	public function copying($id_new_application)
	{
		$new_application = NewApplication::findOrFail($id_new_application);
		
		$last_number_application = Application::select()->withTrashed()->max('number_application');
		$applications = Application::select()->where('id_new_application', $id_new_application)->get();
		foreach ($applications as $application)
		{
			$resolutions_add = Resolution::select(['*'])->where('id_application_resolution', $application->id)->where('deleted_at', null)->orderBy('resolutions.id','desc')->get();
			foreach($resolutions_add as $resol)
				$resol->href_delete_ajax = route('resolution_additional_document_delete_ajax', $resol->id);
			$application->resolutions = $resolutions_add;
		}
		
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		foreach($counterparties as $counter)
			if($new_application->id_counterpartie_new_application == $counter->id){
				$new_application->full_name_counterpartie_contract = $counter->name_full;
				$new_application->name_counterpartie_contract = $counter->name;
				$new_application->inn_counterpartie_contract = $counter->inn;
				break;
			}
		
		$resolutions = Resolution::select(['*'])->where('id_new_application_resolution', $id_new_application)->orderBy('id','desc')->get();
		$type_resolutions = DB::SELECT('SELECT * FROM type_resolutions');
		
		$contracts = Contract::select(['contracts.id', 'number_contract', 'number_counterpartie_contract_reestr', 'item_contract', 'name_view_contract', 'amount_begin_reestr', 'amount_reestr', 'date_maturity_reestr', 'executor_contract_reestr'])							
							->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
							->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', '=', 'view_contracts.id')
							->where('id_new_application_contract', $id_new_application)
							->get();
		
		//Кураторы
		$curators_sip = Curator::all();
		foreach($contracts as $contract){
			foreach($curators_sip as $in_curators)
				if($contract->executor_contract_reestr == $in_curators->id)
					$contract->executor_contract_reestr = $in_curators->FIO;
		}
		
		return view('reconciliation.new_application_copying', ['new_application'=>$new_application, 
																'resolutions'=>$resolutions, 
																'type_resolutions'=>$type_resolutions, 
																'applications'=>$applications, 
																'last_number_application'=>$last_number_application, 
																'contracts'=>$contracts]);
	}
	
	public function reconciliation($id_new_application)
	{
		$new_application = NewApplication::findOrFail($id_new_application);
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		foreach($counterparties as $counter)
			if($new_application->id_counterpartie_new_application == $counter->id){
				$new_application->full_name_counterpartie_contract = $counter->name_full;
				$new_application->name_counterpartie_contract = $counter->name;
				$new_application->inn_counterpartie_contract = $counter->inn;
				break;
			}
		
		$reconciliation = ReconciliationUser::select()->where('id_new_application', $id_new_application)->where('id_user', Auth::User()->id)->first();
		if($reconciliation != null){
			if($reconciliation->id_user == Auth::User()->id){
				if($reconciliation->check_reconciliation == 0){
					$reconciliation->check_reconciliation = 1;
					$reconciliation->date_check_reconciliation = date('d.m.Y', time());
					$reconciliation->save();
					$comment = new Comment([
						'author' => Auth::User()->id,
						'id_new_application' => $id_new_application,
						'message' => 'Ознакомился(лась) с заявкой'
					]);
					$comment->save();
				}
			}
		}
		
		$resolutions = Resolution::select(['*'])->where('id_new_application_resolution', $id_new_application)->orderBy('id','desc')->get();
		$all_users = User::getAllFIO();
		
		$directed_list = ReconciliationUser::select(['reconciliation_users.id as recID',
														'reconciliation_users.check_reconciliation',
														'users.id as userID',
														'users.surname', 
														'users.name', 
														'users.patronymic'
												])->join('users', 'users.id', 'id_user')
												->where('id_new_application', $id_new_application)
												->get();
		$comments = Comment::select(['comments.id as commentID', 'comments.message', 'users.surname', 'users.name', 'users.patronymic', 'comments.created_at'])
									->join('users', 'users.id', 'author')
									->where('id_new_application', $id_new_application)
									->orderBY('comments.id', 'DESC')
									->get();
		
		return view('reconciliation.reconciliation_new_application', ['new_application'=>$new_application, 
													'resolutions'=>$resolutions, 
													'all_users'=>$all_users, 
													'directed_list'=>$directed_list, 
													'comments'=>$comments,
													//'is_new_app'=>true
													]);
	}
	
	public function reconciliation_store(Request $request, $id_new_application)
	{
		if($request['name_new_direction']){
			foreach($request['name_new_direction'] as $id_user){
				$count_reconciliations = ReconciliationUser::select(['id'])->where('id_user', $id_user)->where('id_new_application', $id_new_application)->count();
				if($count_reconciliations == 0){
					$new_reconciliation = new ReconciliationUser([
							'id_new_application' => $id_new_application,
							'id_user' => $id_user
					]);
					$new_reconciliation->save();
				}
			}
		}
		if($request['check_comment'])
			if($request['new_comment'])
				if(strlen($request['new_comment'])){
					$comment = new Comment([
						'author' => Auth::User()->id,
						'id_new_application' => $id_new_application,
						'message' => $request['new_comment']
					]);
					$comment->save();
				}
		if($request['btn_reconciliation']){
			$reconciliation = ReconciliationUser::select()->where('id_new_application', $id_new_application)->where('id_user', Auth::User()->id)->first();
			if($reconciliation != null){
				if($reconciliation->id_user == Auth::User()->id){
					if($reconciliation->check_agree_reconciliation == 0){
						$reconciliation->check_agree_reconciliation = 1;
						$reconciliation->date_check_agree_reconciliation = date('d.m.Y', time());
						$reconciliation->save();
						$comment = new Comment([
							'author' => Auth::User()->id,
							'id_new_application' => $id_new_application,
							'message' => 'Согласовал(а) заявку'
						]);
						$comment->save();
					}
				}
			}
		}
		return redirect()->back()->with(['success'=>'Успешно изменено!']);
	}
}
