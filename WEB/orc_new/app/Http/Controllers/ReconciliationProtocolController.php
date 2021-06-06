<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Contract;
use App\Protocol;
use App\ProtocolComment;
use App\ReconciliationProtocol;
use App\Resolution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ReconciliationProtocolController extends Controller
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
    public function store(Request $request, $id_additional_document)
    {
		//dd($request->all());
		if($request['name_new_direction']){
			foreach($request['name_new_direction'] as $id_user){
				$count_reconciliations = ReconciliationProtocol::select(['id'])->where('id_user', $id_user)->where('id_protocol', $id_additional_document)->count();
				if($count_reconciliations == 0){
					$new_reconciliation = new ReconciliationProtocol([
							'id_protocol' => $id_additional_document,
							'id_user' => $id_user
					]);
					$new_reconciliation->save();
				}
			}
		}
		if($request['check_comment'])
			if($request['new_comment'])
				if(strlen($request['new_comment'])){
					$comment = new ProtocolComment([
						'author' => Auth::User()->id,
						'id_protocol' => $id_additional_document,
						'message' => $request['new_comment']
					]);
					$comment->save();
				}
		if($request['btn_reconciliation']){
			$reconciliation = ReconciliationProtocol::select()->where('id_protocol',$id_additional_document)->where('id_user', Auth::User()->id)->first();
			if($reconciliation != null)
				if($reconciliation->check_agree_reconciliation == 0){
					$reconciliation->check_agree_reconciliation = 1;
					$reconciliation->date_check_agree_reconciliation = date('d.m.Y H:i:s', time());
					$reconciliation->save();
					$comment = new ProtocolComment([
						'author' => Auth::User()->id,
						'id_protocol' => $id_additional_document,
						'message' => 'Согласовал(а) письмо'
					]);
					$comment->save();
				}
		}
		return redirect()->back()->with('success','Отправлен на согласование!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ReconciliationProtocol  $reconciliationProtocol
     * @return \Illuminate\Http\Response
     */
    public function show($id_additional_document)
    {
		$additional_document = Protocol::findOrFail($id_additional_document);
		$resolutions = Resolution::select(['*'])->where('id_protocol_resolution', $id_additional_document)->orderBy('id','desc')->get();
		$all_users = User::getAllFIO();
		$check_all_users_list = HomeController::fill_chunck($all_users, 3);
		$comments = ProtocolComment::select(['protocol_comments.id as commentID', 'protocol_comments.message', 'users.surname', 'users.name', 'users.patronymic', 'protocol_comments.created_at'])
						->join('users', 'users.id', 'author')
						->where('id_protocol', $id_additional_document)
						->orderBY('protocol_comments.id', 'DESC')
						->get();
		$directed_list = ReconciliationProtocol::select(['reconciliation_protocols.id as recID',
														'reconciliation_protocols.check_reconciliation',
														'users.id as userID',
														'users.surname', 
														'users.name', 
														'users.patronymic'
												])->join('users', 'users.id', 'id_user')
												->where('id_protocol',$id_additional_document)
												->get();
		//Проверка на направленность на соглашение текущему юзеру
		foreach($directed_list as $directed)
		{
			if($directed->userID == Auth::User()->id)
			{
				if($directed->check_reconciliation == 0)
				{
					$full_directed = ReconciliationProtocol::select()->where('id_protocol',$id_additional_document)->where('id_user', Auth::User()->id)->first();
					$full_directed->check_reconciliation = 1;
					$full_directed->date_check_reconciliation = date('d.m.Y', time());
					$full_directed->save();
					$comment = new ProtocolComment([
						'author' => Auth::User()->id,
						'id_protocol' => $id_additional_document,
						'message' => 'Ознакомился(лась) с договором'
					]);
					$comment->save();
				}
			}
		}
		$type_resolutions = $type_resolutions = DB::SELECT('SELECT * FROM type_resolutions');
		return view('reconciliation.reconciliation_additional_document', ['additional_document'=>$additional_document,
																			'resolutions'=>$resolutions,
																			'type_resolutions'=>$type_resolutions,
																			'directed_list'=>$directed_list,
																			'all_users'=>$all_users,
																			'check_all_users_list'=>$check_all_users_list,
																			'comments'=>$comments
																		]);
    }
	
	public function print_reconciliation($id_additional_document)
	{
		$addional_document = Protocol::findOrFail($id_additional_document);
		$number_contract = Contract::select(['number_contract'])->join('protocols','protocols.id_contract','contracts.id')->where('protocols.id',$id_additional_document)->first();
		$directed_list = ReconciliationProtocol::select(['reconciliation_protocols.id as recID',
														'reconciliation_protocols.check_reconciliation',
														'reconciliation_protocols.check_agree_reconciliation',
														'reconciliation_protocols.date_check_reconciliation',
														'reconciliation_protocols.date_check_agree_reconciliation',
														'reconciliation_protocols.created_at',
														'users.id as userID',
														'users.surname', 
														'users.name', 
														'users.patronymic'
												])->join('users', 'users.id', 'id_user')
												->where('id_protocol',$id_additional_document)
												->get();
		foreach($directed_list as $directed)
		{
			$comments = ProtocolComment::select()->where('author', $directed->userID)->where('id_protocol',$id_additional_document)->get();
			$directed->date_outgoing = date('d.m.Y H:i:s', strtotime($directed->created_at));
			$directed->comments = $comments;
		}
		return view('reconciliation.print_reconciliation_additional_document', ['directed_list'=>$directed_list, 'number_contract'=>$number_contract, 'addional_document'=>$addional_document]);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ReconciliationProtocol  $reconciliationProtocol
     * @return \Illuminate\Http\Response
     */
    public function edit(ReconciliationProtocol $reconciliationProtocol)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ReconciliationProtocol  $reconciliationProtocol
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReconciliationProtocol $reconciliationProtocol)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ReconciliationProtocol  $reconciliationProtocol
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReconciliationProtocol $reconciliationProtocol)
    {
        //
    }
}
