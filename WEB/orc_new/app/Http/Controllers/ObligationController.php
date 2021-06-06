<?php

namespace App\Http\Controllers;

use Auth;
use App\Obligation;
use App\Contract;
use App\OudCurator;
use App\Counterpartie;
use App\ObligationInvoice;
use Illuminate\Http\Request;

class ObligationController extends Controller
{
    public function show_obligation($id_contract)
	{
		$curators = OudCurator::all();
		$contract = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','id_view_work_contract', 'view_works.name_view_work',
										'reestr_contracts.number_counterpartie_contract_reestr','reestr_contracts.executor_reestr',
										'reestr_contracts.executor_contract_reestr','reestr_contracts.date_maturity_reestr',
										'reestr_contracts.amount_reestr', 'reestr_contracts.amount_contract_reestr', 'reestr_contracts.vat_reestr',
										'reestr_contracts.prepayment_order_reestr', 'reestr_contracts.score_order_reestr', 'reestr_contracts.payment_order_reestr',
										'reestr_contracts.date_b_contract_reestr', 'reestr_contracts.date_e_contract_reestr', 'reestr_contracts.date_contract_reestr',
										'reestr_contracts.date_control_signing_contract_reestr'])
							->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
							->join('goz_works', 'contracts.id_goz_contract', '=', 'goz_works.id')
							->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
							->where('contracts.id',$id_contract)->first();
		foreach($curators as $curator)
			if($curator->id == $contract->executor_reestr)
				$contract->name_executor = $curator->FIO;
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		foreach($counterparties as $counter)
			if($contract->id_counterpartie_contract == $counter->id)
				$contract->name_counterpartie_contract = $counter->name;
		$obligation = Obligation::select()->where('id_contract', $id_contract)->first();
		if($obligation == null)
			$obligation = new Obligation();
		$type1 = ObligationInvoice::select()->where('id_contract', $id_contract)->where('type_invoice', 1)->get();
		$type2 = ObligationInvoice::select()->where('id_contract', $id_contract)->where('type_invoice', 2)->get();
		$type3 = ObligationInvoice::select()->where('id_contract', $id_contract)->where('type_invoice', 3)->get();
		$type4 = ObligationInvoice::select()->where('id_contract', $id_contract)->where('type_invoice', 4)->get();
		$type5 = ObligationInvoice::select()->where('id_contract', $id_contract)->where('type_invoice', 5)->get();
		$type6 = ObligationInvoice::select()->where('id_contract', $id_contract)->where('type_invoice', 6)->get();
		$type7 = ObligationInvoice::select()->where('id_contract', $id_contract)->where('type_invoice', 7)->get();
		$type8 = ObligationInvoice::select()->where('id_contract', $id_contract)->where('type_invoice', 8)->get();
		return view('reestr.obligation', ['contract'=>$contract, 
											'obligation'=>$obligation, 
											'type1'=>$type1,
											'type2'=>$type2,
											'type3'=>$type3,
											'type4'=>$type4,
											'type5'=>$type5,
											'type6'=>$type6,
											'type7'=>$type7,
											'type8'=>$type8]);
	}
	
	public function update_obligation(Request $request, $id_contract)
	{
		$obligation = Obligation::select()->where('id_contract', $id_contract)->first();
		if($obligation == null)
			$obligation = new Obligation();
		$obligation->fill($request->all());
		$obligation->id_contract = $id_contract;
		$all_dirty = JournalController::getMyChanges($obligation);
		$obligation->save();
		JournalController::store(Auth::User()->id,'Изменения исполнения с id договора = ' . $id_contract . '~' . json_encode($all_dirty));
		return redirect()->back();
	}
	
	public function create_obligation_invoice(Request $request, $id_contract)
	{
		$invoice = new ObligationInvoice();
		$invoice->fill($request->all());
		$invoice->id_contract = $id_contract;
		$invoice->amount = str_replace(' ','',$invoice->amount);
		$all_dirty = JournalController::getMyChanges($invoice);
		$invoice->save();
		JournalController::store(Auth::User()->id,'Создание платы исполнения для договора с id = ' . $id_contract . '~' . json_encode($all_dirty));
		return redirect()->back();
	}
	
	public function update_obligation_invoice(Request $request, $id_obligation_invoice)
	{
		$obligation_invoice = ObligationInvoice::findOrFail($id_obligation_invoice);
		$obligation_invoice->fill($request->all());
		$obligation_invoice->amount = str_replace(' ','',$obligation_invoice->amount);
		$all_dirty = JournalController::getMyChanges($obligation_invoice);
		$obligation_invoice->save();
		JournalController::store(Auth::User()->id,'Изменение платы исполнения с id = ' . $id_obligation_invoice . '~' . json_encode($all_dirty));
		return redirect()->back();
	}
	
	public function delete_obligation_invoice($id_obligation_invoice)
	{
		$obligation_invoice = ObligationInvoice::findOrFail($id_obligation_invoice);
		$obligation_invoice->delete();
		JournalController::store(Auth::User()->id,'Удаление платы исполнения с id = ' . $id_obligation_invoice);
		return redirect()->back();
	}
}
