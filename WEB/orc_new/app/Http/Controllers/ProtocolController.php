<?php

namespace App\Http\Controllers;

use Auth;
use App\Comment;
use App\Application;
use App\ReconciliationUser;
use App\Protocol;
use App\Resolution;
use App\ReestrContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProtocolController extends Controller
{
	public function show_protocols($id_contract)
	{
		$protocols = Protocol::select()->where('id_contract', $id_contract)->where('is_protocol', 1)->get();
		foreach($protocols as $protocol)
		{
			$resolutions = Resolution::select(['*'])->where('id_protocol_resolution', $protocol->id)->where('deleted_at', null)->orderBy('id','desc')->get();
			$protocol->resolutions = $resolutions;
		}
		$type_resolutions = DB::SELECT('SELECT * FROM type_resolutions');
		return view('reestr.protocols', ['id_contract'=>$id_contract, 'protocols'=>$protocols, 'type_resolutions'=>$type_resolutions]);
	}
	
	public function store_protocol(Request $request, $id_contract)
	{
		$protocol = new Protocol();
		$protocol->fill($request->all());
		$protocol->fill([
						'id_contract' => $id_contract,
						'is_protocol' => 1,
						'is_oud' => $request['is_oud'] ? 1 : 0,
						'is_oud_el' => $request['is_oud_el'] ? 1 : 0,
						'is_dep' => $request['is_dep'] ? 1 : 0,
						'is_dep_el' => $request['is_dep_el'] ? 1 : 0
		]);
		$count_protocol = Protocol::select('id')->where('id_contract', $id_contract)->count();
		$protocol->position_additional_document = $count_protocol + 1;
		$all_dirty = JournalController::getMyChanges($protocol);
		$protocol->save();
		JournalController::store(Auth::User()->id,'Добавлен протокол для контракта с id = ' . $id_contract . '~' . json_encode($all_dirty));
		return redirect()->back();
	}
	
	public function show_additional_agreements($id_contract)
	{
		$additional_agreements = Protocol::select()->where('id_contract', $id_contract)->where('is_additional_agreement', 1)->get();
		foreach($additional_agreements as $additional_agreement)
		{
			$resolutions = Resolution::select(['*'])->where('id_protocol_resolution', $additional_agreement->id)->where('deleted_at', null)->orderBy('id','desc')->get();
			$additional_agreement->resolutions = $resolutions;
		}
		$type_resolutions = DB::SELECT('SELECT * FROM type_resolutions');	
		return view('reestr.additional_agreements', ['id_contract'=>$id_contract, 'additional_agreements'=>$additional_agreements, 'type_resolutions'=>$type_resolutions]);
	}
	
	public function store_additional_agreement(Request $request, $id_contract)
	{
		$protocol = new Protocol();
		$protocol->fill($request->all());
		$protocol->fill([
						'id_contract' => $id_contract,
						'is_additional_agreement' => 1,
						'is_oud' => $request['is_oud'] ? 1 : 0,
						'is_oud_el' => $request['is_oud_el'] ? 1 : 0,
						'is_dep' => $request['is_dep'] ? 1 : 0,
						'is_dep_el' => $request['is_dep_el'] ? 1 : 0
		]);
		$count_additional_document = Protocol::select('id')->where('id_contract', $id_contract)->count();
		$protocol->position_additional_document = $count_additional_document + 1;
		$all_dirty = JournalController::getMyChanges($protocol);
		$protocol->save();
		if($protocol->amount_protocol)
		{
			$contract = ReestrContract::select()->where('id_contract_reestr',$id_contract)->first();
			$contract->amount_reestr = $contract->amount_reestr + $protocol->amount_protocol;
			$contract->save();
			JournalController::store(Auth::User()->id,'Добавлен ДС для контракта с id = ' . $id_contract . ' и начальная сумма контракта изменена' . '~' . json_encode($all_dirty));
		}
		else
			JournalController::store(Auth::User()->id,'Добавлен ДС для контракта с id = ' . $id_contract . '~' . json_encode($all_dirty));
		return redirect()->back();
	}
	
	public function update_protocol(Request $request, $id_protocol)
	{
		$protocol = Protocol::findOrFail($id_protocol);
		$protocol->fill($request->all());
		$protocol->fill([
						'is_oud' => $request['is_oud'] ? 1 : 0,
						'is_oud_el' => $request['is_oud_el'] ? 1 : 0,
						'is_dep' => $request['is_dep'] ? 1 : 0,
						'is_dep_el' => $request['is_dep_el'] ? 1 : 0
		]);
		$all_dirty = JournalController::getMyChanges($protocol);
		$protocol->save();
		$text = 'Протокол обновлен!';
		if($protocol->is_additional_agreement == 1)
			$text = 'Дополнительное соглашение обновлено!';
		JournalController::store(Auth::User()->id,'Обновлен ПР/ДС id = ' . $id_protocol . ' для контракта с id = ' . $protocol->id_contract . '~' . json_encode($all_dirty));
		return redirect()->back()->with('success', $text);
	}
	
    public function store(Request $request, $id)
    {
		$application = Application::findOrFail($id);
		$application->is_protocol = 1;
		$application->name_protocol = $request['name_protocol'];
		if($request['additional_agreement'])
			$application->is_additional_agreement = 1;
		$application->save();
		$old_reconciliation = ReconciliationUser::select(['id_user'])->where('id_application', $id)->first();
		$new_reconciliation = new ReconciliationUser([
				'id_application' => $id,
				'id_user' => $old_reconciliation->id_user,
				'is_protocol' => $application->is_protocol
		]);
		$new_reconciliation->save();
		$comment = new Comment([
						'author' => Auth::User()->id,
						'id_application' => $id,
						'is_protocol' => $application->is_protocol,
						'message' => 'Протокол создан!'
					]);
		$comment->save();
		return redirect()->route('reconciliation.application', $new_reconciliation->id);
	}
	
	public function destroy($id_protocol)
	{
		//TODO: удалять сканы
		$protocol = Protocol::findOrFail($id_protocol);
		JournalController::store(Auth::User()->id,'Удален ПР/ДС id = ' . $id_protocol . ' для контракта с id = ' . $protocol->id_contract);
		$protocol->delete();
		return redirect()->back()->with('success', 'Протокол успешно удален!');
	}
}
