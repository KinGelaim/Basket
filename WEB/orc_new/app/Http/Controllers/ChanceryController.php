<?php

namespace App\Http\Controllers;

use Auth;
use App\Counterpartie;
use App\Chancery;
use App\Application;
use App\Executor;
use App\Resolution;
use App\Document;
use App\User;
use App\ReconciliationUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChanceryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$counterparties = Counterpartie::select(['*','contr.id'])->leftJoin('curators','contr.curator','curators.id')->where('is_sip_counterpartie', 1)->orderBy('name', 'asc')->get();
		$paginate_count = 10;
		if (isset($_GET["page"])) {
			$page  = $_GET["page"];
		} else {
			$page=1;
		};
		$start = ($page-1) * $paginate_count;
		//$date_begin_str = '01.01.1970';
		//$date_end_str = '31.12.2037';
		$date_begin_str = '01.01.' . date('Y', time());
		$date_end_str = date('d.m.Y', time());
		$counterpartie_str = "applications.id_counterpartie_application";
		$counterpartie_equal = ">";
		$link = '';
		if(isset($_GET['begindate'])) {
			$date_begin = $_GET['begindate'];
			$date_begin_str = $_GET['begindate'];
			$year_equal = "=";
			$link .= "&begindate=" . $_GET['begindate'];
		} else
			$date_begin = '';
		if(isset($_GET['enddate'])) {
			$date_end = $_GET['enddate'];
			$date_end_str = $_GET['enddate'];
			$link .= "&enddate=" . $_GET['enddate'];
		} else
			$date_end = '';
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
			$counterpartie_str = "id_counterpartie_application";
			$counterpartie_equal = "=";
			$link .= "&counterpartie=" . $_GET['counterpartie'];
		} else
			$counterpartie = '';
		$applications = Application::select(['*','applications.id'])->Leftjoin('executors', 'applications.id','executors.id_application')
									->where($counterpartie_str, $counterpartie_equal, $counterpartie)
									->whereBetween('date_incoming', array(DATE('Y-m-d', strtotime($date_begin_str)),DATE('Y-m-d', strtotime($date_end_str))))
									->offset($start)->limit($paginate_count)
									->orderBy('applications.date_outgoing', 'desc')
									->orderBy('applications.id', 'desc')
									->get();
		//dd($applications);
		$applications_count = Application::select(['*','applications.id'])->Leftjoin('executors', 'applications.id','executors.id_application')
									->where($counterpartie_str, $counterpartie_equal, $counterpartie)
									->whereBetween('date_incoming', array(DATE('Y-m-d', strtotime($date_begin_str)),DATE('Y-m-d', strtotime($date_end_str))))
									->count();
		$prev_page = $page - 1 > 0 ? (int)($page-1) : '';
		$next_page = $page + 1 <= (int)ceil($applications_count/$paginate_count) ? (int)($page+1) : '';
		$resolutions = [];
		$directed_list = [];
		foreach($applications as $application)
		{
			$k = Resolution::select(['*'])->where('id_application_resolution', $application->id)->get();
			if(count($k) > 0)
				$resolutions += [$application->id=>$k];
			$directed = ReconciliationUser::select(['reconciliation_users.id as recID',
														'users.id as userID',
														'users.surname',
														'users.name',
														'users.patronymic'
												])->join('users', 'users.id', 'id_user')->where('id_application',$application->id)->get();
			//dump($directed);
			if(count($directed) > 0)
				$directed_list += [$application->id=>$directed];
			$count_documents = Document::select('id')->where('id_application_document',$application->id)->get()->count();
			$application->count_documents=$count_documents;
		}
		$last_number_application = Application::select()->withTrashed()->max('number_application');
		//dd($last_number_application);
		$all_users = User::getAllFIO();
		//phpinfo();
		//$ds = "Иван";
		//dd(mb_substr($ds, 0, 1));
		//print(mb_detect_encoding($all_users[0]->name, mb_detect_order(), true));
		//dd(mb_substr($all_users[0]->name,0,1));
		//dd($directed_list);
		//dd(old());
        return view('department.chancery.main', [
													'last_number_application' => $last_number_application,
													'counterparties' => $counterparties,
													'applications' => $applications,
													'resolutions' => $resolutions,
													'all_users' => $all_users,
													'directed_list' => $directed_list,
													'count_paginate' => (int)ceil($applications_count/$paginate_count),
													'prev_page' => $prev_page,
													'next_page' => $next_page,
													'page' => $page,
													'date_begin' => $date_begin,
													'date_end' => $date_end,
													'counterpartie' => $counerpartie_name,
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
    public function store(Request $request)
    {
        //dd($request->all());
        $val = Validator::make($request->all(),[
			'id_counterpartie_application' => 'required',
			'number_application' => 'required|unique:applications',
			'date_outgoing' => 'nullable|date',
			'date_incoming' => 'nullable|date',
			'date_directed' => 'nullable|date',
		])->validate();
		$directed_user = null;
		if($request['directed_application'])
			$directed_user = User::find($request['directed_application']);
		$application = new Application;
		$application->fill(['id_counterpartie_application' => $request['id_counterpartie_application'],
					'number_application' => $request['number_application'],
					'date_application' => date('Y-m-d', time()),
					'number_outgoing' => $request['number_outgoing'],
					'date_outgoing' => $request['date_outgoing'] ? date('Y-m-d', strtotime($request['date_outgoing'])) : date('Y-m-d', time()),
					'number_incoming' => $request['number_incoming'],
					'date_incoming' => $request['date_incoming'] ? date('Y-m-d', strtotime($request['date_incoming'])) : date('Y-m-d', time()),
					'directed_application' => $directed_user != null ? $directed_user->surname . ' ' . $directed_user->name . ' ' . $directed_user->patronymic : null,
					'date_directed' => $request['date_directed'],
					'theme_application' => $request['theme_application']
					/*'resolution_application' => null,
					'date_resolution' => null,
					'executor' => Auth::User()->id,*/
		]);
		//dd($application);
		$application->save();
		if($directed_user != null){
			$new_reconciliation = new ReconciliationUser([
						'id_application' => $application->id,
						'id_user' => $directed_user->id
			]);
			$new_reconciliation->save();
		}
		ResolutionController::store_resol_new_app($request, $application->id);
		$id_contr = $request['id_counterpartie_application'];
		$request->flush();
		JournalController::store(Auth::User()->id,'Добавил новую заявку с id = ' . $application->id);
        return redirect()->back()->with('id_counterpartie_application', $id_contr);
    }
	
    public function store_for_new_application(Request $request, $id_new_application)
    {
        $val = Validator::make($request->all(),[
			'id_counterpartie_application' => 'required',
			'number_application' => 'required|unique:applications',
			'date_outgoing' => 'nullable|date',
			'date_incoming' => 'nullable|date',
			'date_directed' => 'nullable|date',
		])->validate();
		$application = new Application;
		$application->fill(['id_counterpartie_application' => $request['id_counterpartie_application'],
					'id_new_application' => $id_new_application,
					'number_application' => $request['number_application'],
					'date_application' => date('Y-m-d', time()),
					'number_outgoing' => $request['number_outgoing'],
					'date_outgoing' => $request['date_outgoing'] ? date('Y-m-d', strtotime($request['date_outgoing'])) : date('Y-m-d', time()),
					'number_incoming' => $request['number_incoming'],
					'date_incoming' => $request['date_incoming'] ? date('Y-m-d', strtotime($request['date_incoming'])) : date('Y-m-d', time()),
					'date_directed' => $request['date_directed'],
					'theme_application' => $request['theme_application']
		]);
		$application->save();
		ResolutionController::store_resol_new_app($request, $application->id);
		JournalController::store(Auth::User()->id,'Добавил новое письмо для заявки с id = ' . $application->id);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Chancery  $chancery
     * @return \Illuminate\Http\Response
     */
    public function show(Chancery $chancery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Chancery  $chancery
     * @return \Illuminate\Http\Response
     */
    public function edit(Chancery $chancery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Chancery  $chancery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());
        $val = Validator::make($request->all(),[
			'number_application_update' => 'required',
			'date_outgoing_update' => 'nullable|date',
			'date_incoming_update' => 'nullable|date',
			'date_directed_update' => 'nullable|date',
		])->validate();
		$directed_user = null;
		if($request['directed_application_update'])
			$directed_user = User::find($request['directed_application_update']);
		$application = Application::findOrFail($id);
		$is_new_directed = true;
		if($directed_user != null)
			if($application->directed_application == $directed_user->surname . ' ' . $directed_user->name . ' ' . $directed_user->patronymic)
				$is_new_directed = false;
		$application->fill(['number_application' => $request['number_application_update'],
					'number_outgoing' => $request['number_outgoing_update'],
					'date_outgoing' => $request['date_outgoing_update'] ? date('Y-m-d', strtotime($request['date_outgoing_update'])) : date('Y-m-d', time()),
					'number_incoming' => $request['number_incoming_update'],
					'date_incoming' => $request['date_incoming_update'] ? date('Y-m-d', strtotime($request['date_incoming_update'])) : date('Y-m-d', time()),
					'directed_application' => $directed_user != null ? $directed_user->surname . ' ' . $directed_user->name . ' ' . $directed_user->patronymic : $application->directed_application,
					'date_directed' => $request['date_directed_update'],
					'theme_application' => $request['theme_application_update'],
					/*'resolution_application' => null,
					'date_resolution' => $request['date_resolution_update'],*/
		]);
		//dd($application);
		$executor = Executor::firstOrNew(['id_application' => $id]);//->firstOrFail();
		$executor->fill(['id_application' => $id,
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
		]);
		//dump($executor);
		$application->save();
		$executor->save();
		if($directed_user != null AND $is_new_directed){
			$count_reconciliations = ReconciliationUser::select(['id'])->where('id_user', $directed_user->id)->where('id_application', $application->id)->count();
			if($count_reconciliations == 0){
				$new_reconciliation = new ReconciliationUser([
							'id_application' => $application->id,
							'id_user' => $directed_user->id
				]);
				$new_reconciliation->save();
			}
		}
		if($request['name_new_direction'])
			foreach($request['name_new_direction'] as $id_user){
				$count_reconciliations = ReconciliationUser::select(['id'])->where('id_user', $id_user)->where('id_application', $id)->count();
				if($count_reconciliations == 0){
					$new_reconciliation = new ReconciliationUser([
							'id_application' => $id,
							'id_user' => $id_user
					]);
					$new_reconciliation->save();
				}
			}
		JournalController::store(Auth::User()->id,'Обновил заявку с id = ' . $application->id);
        return redirect()->back()->with('success','Успешно изменен!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Chancery  $chancery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chancery $chancery, $id)
    {
        $application = Application::findOrFail($id);
		$application->delete();
		JournalController::store(Auth::User()->id,'Удалил заявку с id = ' . $application->id);
		return redirect()->back()->with('success','Успешно удален!');
    }
}
