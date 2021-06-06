<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Application;
use App\Resolution;
use App\Counterpartie;
use App\ReconciliationUser;
use App\Comment;
use App\Checkpoint;
use App\Contract;
use App\Curator;
use App\ReconciliationProtocol;
use App\Protocol;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		//Сохранение страницы текущей
		$links = session()->has('links') ? session('links') : [];
		$current_link = request()->path();
		array_unshift($links, $current_link);
		session(['links' => $links]);
		//Сортировка (щелкаем по треугольничкам)
		$sort = "id";
		$sort_span = "";
		$re_sort = "desc";
		if (isset($_GET["sorting"])) {
			$sort  = $_GET["sorting"];
			$sort_span = "▼";
		}
		$sort_p = "desc";
		if (isset($_GET["sort_p"])) {
			$sort_p  = $_GET["sort_p"];
			if($_GET["sort_p"] == "asc"){
				$sort_span = "▲";
				$re_sort = "desc";
			}else{
				$re_sort = "asc";
			}
		}
		$counterparties = Counterpartie::select(['*','contr.id'])->leftjoin('curators','contr.curator','curators.id')->orderBy('name', 'asc')->get();
		//Письма
		$new_my_applications = ReconciliationUser::select(['reconciliation_users.id',
															'applications.number_application', 
															'applications.number_outgoing', 
															'applications.date_outgoing', 
															'applications.number_incoming', 
															'applications.date_incoming', 
															'applications.id_counterpartie_application', 
															'applications.theme_application'
														])->Join('applications', 'applications.id', 'id_application')
														->where('id_user', Auth::User()->id)
														->where('check_reconciliation', 0)
														->where('applications.is_protocol', 0)
														->where('reconciliation_users.is_document', 0)
														->where('applications.deleted_at', null)
														->orderBy("applications." . $sort, $sort_p)
														->get();
		$my_applications = ReconciliationUser::select(['reconciliation_users.id',
															'applications.number_application', 
															'applications.number_outgoing', 
															'applications.date_outgoing', 
															'applications.number_incoming', 
															'applications.date_incoming', 
															'applications.id_counterpartie_application', 
															'applications.theme_application'
														])->Join('applications', 'applications.id', 'id_application')
														->where('id_user', Auth::User()->id)
														->where('check_reconciliation', 1)
														->where('check_agree_reconciliation', 0)
														->where('applications.is_protocol', 0)
														->where('reconciliation_users.is_document', 0)
														->where('applications.deleted_at', null)
														->orderBy($sort, $sort_p)
														->get();
		foreach($new_my_applications as $application)
			foreach($counterparties as $counterpartie)
				if($application->id_counterpartie_application == $counterpartie->id)
					$application->counterpartie_name = $counterpartie->name;
		foreach($my_applications as $application)
			foreach($counterparties as $counterpartie)
				if($application->id_counterpartie_application == $counterpartie->id)
					$application->counterpartie_name = $counterpartie->name;
		//dd($new_my_applications);
		//Договора
		$new_my_contracts = ReconciliationUser::select(['reconciliation_users.id',
															'contracts.number_contract',
															'contracts.id_counterpartie_contract',
															'view_works.name_view_work',
															'contracts.name_work_contract',
															'reestr_contracts.executor_reestr',
															'reestr_contracts.amount_contract_reestr',
															'reestr_contracts.date_maturity_date_reestr'
														])
														->Join('contracts', 'contracts.id', 'id_contract')
														->leftjoin('view_works', 'view_works.id', 'id_view_work_contract')
														->leftjoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
														->where('id_user', Auth::User()->id)
														->where('check_reconciliation', 0)
														->where('reconciliation_users.is_document', 0)
														->where('contracts.deleted_at', null)
														->orderBy("contracts.id")
														->get();
		$my_contracts = ReconciliationUser::select(['reconciliation_users.id',
															'contracts.number_contract',
															'contracts.id_counterpartie_contract',
															'view_works.name_view_work',
															'contracts.name_work_contract',
															'reestr_contracts.executor_reestr',
															'reestr_contracts.amount_contract_reestr',
															'reestr_contracts.date_maturity_date_reestr'
														])
														->Join('contracts', 'contracts.id', 'id_contract')
														->leftjoin('view_works', 'view_works.id', 'id_view_work_contract')
														->leftjoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
														->where('id_user', Auth::User()->id)
														->where('check_reconciliation', 1)
														->where('check_agree_reconciliation', 0)
														->where('reconciliation_users.is_document', 0)
														->where('contracts.deleted_at', null)
														->orderBy("contracts.id")
														->get();
		foreach($new_my_contracts as $contract)
			foreach($counterparties as $counterpartie)
				if($contract->id_counterpartie_contract == $counterpartie->id)
					$contract->counterpartie_name = $counterpartie->name;
		foreach($my_contracts as $contract)
			foreach($counterparties as $counterpartie)
				if($contract->id_counterpartie_contract == $counterpartie->id)
					$contract->counterpartie_name = $counterpartie->name;
		//Протоколы
		$new_my_protocols = ReconciliationUser::select(['reconciliation_users.id',
															'applications.number_application', 
															'applications.number_outgoing', 
															'applications.date_outgoing', 
															'applications.number_incoming', 
															'applications.date_incoming', 
															'applications.id_counterpartie_application', 
															'applications.theme_application'
														])->Join('applications', 'applications.id', 'id_application')
														->where('id_user', Auth::User()->id)
														->where('check_reconciliation', 0)
														->where('applications.is_protocol', 1)
														->where('reconciliation_users.is_document', 0)
														->where('applications.deleted_at', null)
														->orderBy("applications." . $sort, $sort_p)
														->get();
		$my_protocols = ReconciliationUser::select(['reconciliation_users.id',
															'applications.number_application', 
															'applications.number_outgoing', 
															'applications.date_outgoing', 
															'applications.number_incoming', 
															'applications.date_incoming', 
															'applications.id_counterpartie_application', 
															'applications.theme_application'
														])->Join('applications', 'applications.id', 'id_application')
														->where('id_user', Auth::User()->id)
														->where('check_reconciliation', 1)
														->where('check_agree_reconciliation', 0)
														->where('applications.is_protocol', 1)
														->where('reconciliation_users.is_document', 0)
														->where('applications.deleted_at', null)
														->orderBy($sort, $sort_p)
														->get();
		foreach($new_my_protocols as $protocol)
			foreach($counterparties as $counterpartie)
				if($protocol->id_counterpartie_application == $counterpartie->id)
					$protocol->counterpartie_name = $counterpartie->name;
		foreach($my_protocols as $protocol)
			foreach($counterparties as $counterpartie)
				if($protocol->id_counterpartie_application == $counterpartie->id)
					$protocol->counterpartie_name = $counterpartie->name;
		//Контрольные точки
		$red_checkpoints = [];
		if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Планово-экономический отдел'){
			$curators = Curator::all();
			$checkpoints = Checkpoint::select(['date_checkpoint', 'message_checkpoint', 'contracts.id as contractID', 'contracts.number_contract', 'reestr_contracts.executor_reestr'])
												->join('contracts', 'contracts.id', 'id_contract')
												->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'id_contract')
												->where('check_checkpoint', 0)
												->get();
			foreach($checkpoints as $checkpoint)
				if(time() - strtotime($checkpoint->date_checkpoint) > 0){
					foreach($curators as $curator)
						if($checkpoint->executor_reestr != null){
							if($curator->id == $checkpoint->executor_reestr){
								$checkpoint->curator_name = $curator->FIO;
								if($curator->id_user == Auth::User()->id || Auth::User()->id == 1)
									array_push($red_checkpoints, $checkpoint);
							}
						}else{
							array_push($red_checkpoints, $checkpoint);
							break;
						}
				}
		}
		//Комплектующие
		$my_components = [];
		$new_my_components = [];
		if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Десятый отдел'){
			$new_my_components = ReconciliationUser::select(['reconciliation_users.id',
																'applications.id as id_application',
																'documents.id as id_document',
																'applications.number_application', 
																'applications.id_counterpartie_application', 
																'applications.theme_application'
															])->Join('applications', 'applications.id', 'id_application')
															->leftJoin('documents', 'applications.id', 'id_application_document')
															->where('id_user', Auth::User()->id)
															->where('check_reconciliation', 0)
															->where('applications.is_protocol', 0)
															->where('reconciliation_users.is_document', 1)
															->where('applications.deleted_at', null)
															->orderBy("applications." . $sort, $sort_p)
															->get();
			foreach($new_my_components as $component)
				foreach($counterparties as $counterpartie)
					if($component->id_counterpartie_application == $counterpartie->id)
						$component->counterpartie_name = $counterpartie->name;
			foreach($my_components as $component)
				foreach($counterparties as $counterpartie)
					if($component->id_counterpartie_application == $counterpartie->id)
						$component->counterpartie_name = $counterpartie->name;
		}
		//Новая форма протоколов и доп. соглашений
		$new_my_additional_documents = ReconciliationProtocol::select(['reconciliation_protocols.id as recID',
														'reconciliation_protocols.check_reconciliation',
														'reconciliation_protocols.check_agree_reconciliation',
														'reconciliation_protocols.date_check_reconciliation',
														'reconciliation_protocols.date_check_agree_reconciliation',
														'protocols.id as protocolID',
														'protocols.application_protocol',
														'protocols.name_protocol',
														'protocols.name_work_protocol',
														'protocols.date_protocol',
														'users.id as userID',
														'users.surname', 
														'users.name', 
														'users.patronymic'
												])->join('users', 'users.id', 'id_user')
												->join('protocols', 'protocols.id', 'id_protocol')
												->where('id_user',Auth::User()->id)
												->where('check_reconciliation', null)
												->where('check_agree_reconciliation', null)
												->get();
		$my_additional_documents = ReconciliationProtocol::select(['reconciliation_protocols.id as recID',
														'reconciliation_protocols.check_reconciliation',
														'reconciliation_protocols.check_agree_reconciliation',
														'reconciliation_protocols.date_check_reconciliation',
														'reconciliation_protocols.date_check_agree_reconciliation',
														'protocols.id as protocolID',
														'protocols.application_protocol',
														'protocols.name_protocol',
														'protocols.name_work_protocol',
														'protocols.date_protocol',
														'users.id as userID',
														'users.surname', 
														'users.name', 
														'users.patronymic'
												])
												->join('users', 'users.id', 'id_user')
												->join('protocols', 'protocols.id', 'id_protocol')
												->where('id_user',Auth::User()->id)
												->where('check_reconciliation', 1)
												->where('check_agree_reconciliation', null)
												->get();
		//Отображаем протоколы за последнии 5 (4) дней для Марии из ОУД и для Админа (345600)
		$time_see = 4 * 24 * 60 * 60;	// Дни * часы * минуты * секунды
		$new_additional_documents = [];
		if(Auth::User())
			if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->surname == 'Бастрыкова')
				$new_additional_documents = Protocol::select(['number_contract',
													'id_contract',
													'name_protocol',
													'date_on_first_protocol',
													'date_registration_protocol',
													'date_signing_protocol',
													'date_signing_counterpartie_protocol'])
													->join('contracts','id_contract','contracts.id')
													->where('protocols.created_at', '>', date('Y-m-d 00:00:00', time()-$time_see))
													->orderBy('protocols.id','desc')
													->get();
		//Отображение сканов за последнии 10 дней для Марии из ОУД и для Админа (864000)
		$time_see = 10 * 24 * 60 * 60;
		$new_scans_documents = [];
		if(Auth::User())
			if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->surname == 'Бастрыкова')
				$new_scans_documents = Resolution::select(['number_contract',
													'contracts.id as id_contract',
													'protocols.id_contract as id_contract_in_protocol',
													'protocols.is_protocol',
													'protocols.is_additional_agreement',
													'resolutions.created_at',
													'resolutions.deleted_at',
													'real_name_resolution',
													'users.surname',
													'users.name',
													'users.patronymic'])
													->leftjoin('contracts','id_contract_resolution','contracts.id')
													->leftjoin('protocols', 'id_protocol_resolution', 'protocols.id')
													->leftjoin('users', 'id_user', 'users.id')
													->where('resolutions.created_at', '>', date('Y-m-d 00:00:00', time()-$time_see))
													->orderBy('resolutions.id','desc')
													->get();
		//dd();
        return view('welcome', ['new_my_applications'=>$new_my_applications, 
								'my_applications'=>$my_applications, 
								'new_my_contracts'=>$new_my_contracts,
								'my_contracts'=>$my_contracts,
								'sort'=>$sort, 
								'sort_span'=>$sort_span, 
								're_sort'=>$re_sort,
								'red_checkpoints'=>$red_checkpoints,
								'new_my_protocols'=>$new_my_protocols,
								'my_protocols'=>$my_protocols,
								'my_components'=>$my_components,
								'new_my_components'=>$new_my_components,
								'new_my_additional_documents'=>$new_my_additional_documents,
								'my_additional_documents'=>$my_additional_documents,
								'new_additional_documents'=>$new_additional_documents,
								'new_scans_documents'=>$new_scans_documents
								]);
    }
	
	public function show_application($id)
	{
		$counterparties = Counterpartie::select(['*','contr.id'])->join('curators','contr.curator','curators.id')->orderBy('name', 'asc')->get();
		$application = Application::select(['applications.id as appID',
											'applications.number_application', 
											'applications.number_outgoing', 
											'applications.date_outgoing', 
											'applications.number_incoming', 
											'applications.date_incoming', 
											'applications.id_counterpartie_application', 
											'applications.theme_application',
											'applications.is_protocol',
											'applications.name_protocol',
											'applications.is_additional_agreement',
											'applications.comment_application'
									])->where('applications.id',$id)->first();
		$resolutions = Resolution::select(['*'])->where('id_application_resolution', $application->appID)->get();
		$all_users = User::getAllFIO();
		foreach($counterparties as $counterpartie)
			if($application->id_counterpartie_application == $counterpartie->id)
				$application->counterpartie_name = $counterpartie->name;
		$directed_list = ReconciliationUser::select(['reconciliation_users.id as recID',
														'reconciliation_users.check_reconciliation',
														'reconciliation_users.check_agree_reconciliation',
														'users.id as userID',
														'users.surname', 
														'users.name', 
														'users.patronymic'
												])->join('users', 'users.id', 'id_user')
												->where('id_application',$application->appID)
												->where('is_protocol', $application->is_protocol)
												->where('is_document', 0)
												->get();
		$comments = Comment::select(['comments.id as commentID', 'comments.message', 'users.surname', 'users.name', 'users.patronymic', 'comments.created_at'])
									->join('users', 'users.id', 'author')
									->where('id_application', $application->appID)
									->where('is_protocol', $application->is_protocol)
									->orderBY('comments.id', 'DESC')
									->get();
		return view('reconciliation.reconciliation_application', ['application'=>$application, 
													'resolutions'=>$resolutions, 
													'all_users'=>$all_users, 
													'directed_list'=>$directed_list, 
													'comments'=>$comments,
													'is_new_app'=>false]);
	}
	
	public function reconciliation_application($id)
	{
		$counterparties = Counterpartie::select(['*','contr.id'])->join('curators','contr.curator','curators.id')->orderBy('name', 'asc')->get();
		$application = ReconciliationUser::select(['reconciliation_users.id as recID',
														'applications.id as appID',
														'applications.number_application', 
														'applications.number_outgoing', 
														'applications.date_outgoing', 
														'applications.number_incoming', 
														'applications.date_incoming', 
														'applications.id_counterpartie_application', 
														'applications.theme_application',
														'applications.is_protocol',
														'applications.name_protocol',
														'applications.is_additional_agreement',
														'applications.comment_application'
												])->join('applications', 'applications.id', 'id_application')->where('reconciliation_users.id',$id)->first();
		$resolutions = Resolution::select(['*'])->where('id_application_resolution', $application->appID)->get();
		$reconciliation = ReconciliationUser::find($id);
		if($reconciliation->id_user == Auth::User()->id){
			if($reconciliation->check_reconciliation == 0){
				$reconciliation->check_reconciliation = 1;
				$reconciliation->save();
				$comment = new Comment([
					'author' => Auth::User()->id,
					'id_application' => $application->appID,
					'is_protocol' => $application->is_protocol,
					'message' => 'Ознакомился(лась) с письмом'
				]);
				$comment->save();
			}
		}
		$all_users = User::getAllFIO();
		foreach($counterparties as $counterpartie)
			if($application->id_counterpartie_application == $counterpartie->id)
				$application->counterpartie_name = $counterpartie->name;
		$directed_list = ReconciliationUser::select(['reconciliation_users.id as recID',
														'reconciliation_users.check_reconciliation',
														'users.id as userID',
														'users.surname', 
														'users.name', 
														'users.patronymic'
												])->join('users', 'users.id', 'id_user')
												->where('id_application',$application->appID)
												->where('is_protocol', $application->is_protocol)
												->where('is_document', 0)
												->get();
		$comments = Comment::select(['comments.id as commentID', 'comments.message', 'users.surname', 'users.name', 'users.patronymic', 'comments.created_at'])
									->join('users', 'users.id', 'author')
									->where('id_application', $application->appID)
									->where('is_protocol', $application->is_protocol)
									->orderBY('comments.id', 'DESC')
									->get();
		//dd($comments);
		return view('reconciliation.reconciliation_application', ['application'=>$application, 
													'resolutions'=>$resolutions, 
													'all_users'=>$all_users, 
													'directed_list'=>$directed_list, 
													'comments'=>$comments,
													'is_new_app'=>true]);
	}
	
	public function store_application(Request $request, $id)
	{
		if($request['name_new_direction']){
			$application = Application::find($id);
			foreach($request['name_new_direction'] as $id_user){
				$count_reconciliations = ReconciliationUser::select(['id'])->where('id_user', $id_user)->where('id_application', $id)->where('reconciliation_users.is_protocol', $application->is_protocol)->count();
				if($count_reconciliations == 0){
					$new_reconciliation = new ReconciliationUser([
							'id_application' => $id,
							'id_user' => $id_user,
							'is_protocol' => $application->is_protocol
					]);
					$new_reconciliation->save();
				}
			}
		}
		if($request['check_comment'])
			if($request['new_comment'])
				if(strlen($request['new_comment'])){
					$application = Application::find($id);
					$comment = new Comment([
						'author' => Auth::User()->id,
						'id_application' => $id,
						'message' => $request['new_comment'],
						'is_protocol' => $application->is_protocol
					]);
					$comment->save();
				}
		if($request['btn_reconciliation']){
			$reconciliation = ReconciliationUser::find($request['id_reconciliation']);
			if($reconciliation->id_user == Auth::User()->id){
				if($reconciliation->check_agree_reconciliation == 0){
					$application = Application::find($id);
					$reconciliation->check_agree_reconciliation = 1;
					$reconciliation->save();
					$comment = new Comment([
						'author' => Auth::User()->id,
						'id_application' => $id,
						'message' => 'Согласовал(а) письмо',
						'is_protocol' => $application->is_protocol
					]);
					$comment->save();
				}
			}
		}
		if($request['name_protocol']){
			$application = Application::find($id);
			$application->name_protocol = $request['name_protocol'];
			$application->comment_application = $request['comment_application'];
			$application->save();
			if(session()->has('links')){
				$return_path = session('links')[0];
				session(['links' => []]);
				return redirect($return_path);
			}
		}
		return redirect()->route('home');
	}
	
	public function delete_direction_application(Request $request)
	{
		if($request['id_user'] && $request['id_application'])
		{
			$application = Application::find($request['id_application']);
			$count_reconciliations = ReconciliationUser::select(['id'])->where('id_application', $request['id_application'])->where('reconciliation_users.is_protocol', $application->is_protocol)->count();
			if($count_reconciliations > 1){
				$reconciliation_user = ReconciliationUser::select(['id'])->where('id_user', $request['id_user'])->where('id_application', $request['id_application'])->where('reconciliation_users.is_protocol', $application->is_protocol)->delete();
				return $reconciliation_user;
			}
		}
		return false;
	}
	
	public function store_checkpoint(Request $request, $id)
	{
		if($request['date_checkpoint'] && $request['message_checkpoint'])
		{
			$checkpoint = new Checkpoint();
			$checkpoint->fill([
				'id_contract' => $id,
				'date_checkpoint' => $request['date_checkpoint'],
				'message_checkpoint' => $request['message_checkpoint']
			]);
			$checkpoint->save();
		}
		return redirect()->back();
	}
	
	public function update_checkpoint(Request $request, $id)
	{
		$checkpoint = Checkpoint::findOrFail($id);
		$checkpoint->fill([
			'check_checkpoint' => $_GET['check'] == 'on' ? 1 : 0
		]);
		$checkpoint->save();
		return redirect()->back();
	}
	
	public function show_contract($id)
	{
		$counterparties = Counterpartie::select(['*','contr.id'])->join('curators','contr.curator','curators.id')->orderBy('name', 'asc')->get();
		$contract = Contract::select(['contracts.id as contractID',
											'contracts.number_contract',
											'contracts.id_counterpartie_contract',
											'contracts.name_work_contract',
											'contracts.item_contract'
									])->where('contracts.id',$id)->first();
		$resolutions = Resolution::select(['*'])->where('id_contract_resolution', $contract->contractID)->where('deleted_at', null)->orderBy('id','desc')->get();
		$all_users = User::getAllFIO();
		$check_all_users_list = $this->fill_chunck($all_users, 3);
		foreach($counterparties as $counterpartie)
			if($contract->id_counterpartie_contract == $counterpartie->id)
				$contract->counterpartie_name = $counterpartie->name;
		$directed_list = ReconciliationUser::select(['reconciliation_users.id as recID',
														'reconciliation_users.check_reconciliation',
														'users.id as userID',
														'users.surname', 
														'users.name', 
														'users.patronymic'
												])->join('users', 'users.id', 'id_user')->where('id_contract',$contract->contractID)->get();
		$comments = Comment::select(['comments.id as commentID', 'comments.message', 'users.surname', 'users.name', 'users.patronymic', 'comments.created_at'])->join('users', 'users.id', 'author')->where('id_contract', $contract->contractID)->orderBY('comments.id', 'DESC')->get();
		return view('reconciliation.reconciliation_contract', ['contract'=>$contract, 
													'resolutions'=>$resolutions, 
													'all_users'=>$all_users, 
													'check_all_users_list'=>$check_all_users_list,
													'directed_list'=>$directed_list, 
													'comments'=>$comments,
													'is_new_app'=>false]);
	}
	
	public static function fill_chunck($array, $parts)
	{
		$t = 0;
		$result = array_fill(0, $parts - 1, array());
		$max = ceil(count($array) / $parts);
		foreach($array as $v)
		{
			count($result[$t]) >= $max and $t ++;
			$result[$t][] = $v;
		}
		return $result;
	}
	
	public function reconciliation_contract($id)
	{
		$counterparties = Counterpartie::select(['*','contr.id'])->join('curators','contr.curator','curators.id')->orderBy('name', 'asc')->get();
		$contract = ReconciliationUser::select(['reconciliation_users.id as recID',
												'contracts.id as contractID',
												'contracts.number_contract',
												'contracts.id_counterpartie_contract',
												'contracts.name_work_contract',
												'contracts.item_contract'
												])->join('contracts', 'contracts.id', 'id_contract')->where('reconciliation_users.id',$id)->first();
		$resolutions = Resolution::select(['*'])->where('id_contract_resolution', $contract->contractID)->orderBy('id','desc')->get();
		$reconciliation = ReconciliationUser::find($id);
		if($reconciliation->id_user == Auth::User()->id){
			if($reconciliation->check_reconciliation == 0){
				$reconciliation->check_reconciliation = 1;
				$reconciliation->date_check_reconciliation = date('d.m.Y', time());
				$reconciliation->save();
				$comment = new Comment([
					'author' => Auth::User()->id,
					'id_contract' => $contract->contractID,
					'message' => 'Ознакомился(лась) с договором'
				]);
				$comment->save();
			}
		}
		$all_users = User::getAllFIO();
		$check_all_users_list = $this->fill_chunck($all_users, 3);
		foreach($counterparties as $counterpartie)
			if($contract->id_counterpartie_contract == $counterpartie->id)
				$contract->counterpartie_name = $counterpartie->name;
		$directed_list = ReconciliationUser::select(['reconciliation_users.id as recID',
														'reconciliation_users.check_reconciliation',
														'users.id as userID',
														'users.surname', 
														'users.name', 
														'users.patronymic'
												])->join('users', 'users.id', 'id_user')->where('id_contract', $contract->contractID)->get();
		$comments = Comment::select(['comments.id as commentID', 'comments.message', 'users.surname', 'users.name', 'users.patronymic', 'comments.created_at'])->join('users', 'users.id', 'author')->where('id_contract', $contract->contractID)->orderBY('comments.id', 'DESC')->get();
		return view('reconciliation.reconciliation_contract', ['contract'=>$contract, 
													'resolutions'=>$resolutions, 
													'all_users'=>$all_users, 
													'check_all_users_list'=>$check_all_users_list,
													'directed_list'=>$directed_list, 
													'comments'=>$comments,
													'is_new_app'=>true]);
	}
	
	public function store_contract(Request $request, $id)
	{
		if($request['name_new_direction'])
			foreach($request['name_new_direction'] as $id_user){
				$old_reconciliations = ReconciliationUser::select(['id'])
										->where('id_user', $id_user)
										->where('id_contract', $id)
										->delete();
				//if($count_reconciliations == 0){
					$new_reconciliation = new ReconciliationUser([
							'id_contract' => $id,
							'id_user' => $id_user
					]);
					$new_reconciliation->save();
				//}
			}
		if($request['check_comment'])
			if($request['new_comment'])
				if(strlen($request['new_comment'])){
					$comment = new Comment([
						'author' => Auth::User()->id,
						'id_contract' => $id,
						'message' => $request['new_comment']
					]);
					$comment->save();
				}
		if($request['btn_reconciliation']){
			if($request['btn_reconciliation'] == 2)
				return redirect()->route('home');
			else{
				$reconciliation = ReconciliationUser::find($request['id_reconciliation']);
				if($reconciliation->id_user == Auth::User()->id){
					if($reconciliation->check_agree_reconciliation == 0){
						$reconciliation->check_agree_reconciliation = 1;
						$reconciliation->date_check_agree_reconciliation = date('d.m.Y H:i:s', time());
						$reconciliation->save();
						$comment = new Comment([
							'author' => Auth::User()->id,
							'id_contract' => $id,
							'message' => 'Согласовал(а) договор'
						]);
						$comment->save();
					}
				}
				return redirect()->route('home');
			}
		}
		return redirect()->back()->with('success','Направлен на согласование!'); //->route('department.reconciliation.show', $id);
	}
	
	public function delete_direction_contract(Request $request)
	{
		if($request['id_user'] && $request['id_contract'])
		{
			$count_reconciliations = ReconciliationUser::select(['id'])->where('id_contract', $request['id_contract'])->count();
			if($count_reconciliations > 1){
				$reconciliation_user = ReconciliationUser::select(['id'])->where('id_user', $request['id_user'])->where('id_contract', $request['id_contract'])->delete();
				return $reconciliation_user;
			}
		}
		return false;
	}
	
	public function store_document(Request $request, $id)
	{
		if($request['name_new_direction'])
			foreach($request['name_new_direction'] as $id_user){
				$old_reconciliations = ReconciliationUser::select(['id'])
										->where('id_user', $id_user)
										->where('id_application', $id)
										->delete();
				$new_reconciliation = new ReconciliationUser([
						'id_application' => $id,
						'id_user' => $id_user,
						'is_document' => 1
				]);
				$new_reconciliation->save();
			}
		if($request['check_comment'])
			if($request['new_comment'])
				if(strlen($request['new_comment'])){
					$comment = new Comment([
						'author' => Auth::User()->id,
						'id_application' => $id,
						'message' => $request['new_comment'],
						'is_document' => 1
					]);
					$comment->save();
				}
		return redirect()->back();
	}
	
	public function delete_direction_document(Request $request)
	{
		if($request['id_user'] && $request['id_application'])
		{
			$reconciliation_user = ReconciliationUser::select(['id'])->where('id_user', $request['id_user'])->where('id_application', $request['id_application'])->where('is_document', 1)->delete();
			return $reconciliation_user;
		}
		return false;
	}
}
