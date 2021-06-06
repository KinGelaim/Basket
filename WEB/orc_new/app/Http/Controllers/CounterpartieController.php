<?php

namespace App\Http\Controllers;

use Auth;
use App\Counterpartie;
use App\Curator;
use App\TitleDocument;
//use App\Contract;
use App\ReestrContract;
use App\BankDetail;
use App\CounterpartieResolution;
use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CounterpartieController extends Controller
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
				$link .= "&search_value=" . $_GET['search_value'];
			}
		}
		$letter_value = '';
		if(isset($_GET['letter'])) {
			if(strlen($_GET['letter']) > 0) {
				$letter_value = $_GET['letter'];
				$link .= "&letter=" . $_GET['letter'];
			}
		}
		//Пагинация
		$paginate_count = 30;
		if (isset($_GET["page"])) {
			$page  = $_GET["page"];
		} else {
			$page=1;
		};
		$start = ($page-1) * $paginate_count;
		if($search_name != '' && $search_value != '' && $letter_value != ''){
			$counterparties = Counterpartie::select('*','contr.id','contr.deleted_at')
								->leftjoin('curators','curator','curators.id')
								->where($search_name, 'like', '%' . $search_value . '%')
								->where('name', 'like', $letter_value . '%')
								->orderBy('name', 'asc')
								->withTrashed()
								->offset($start)
								->limit($paginate_count)
								->get();
			$counterparties_count = Counterpartie::select('contr.id')
									->where($search_name, 'like', '%' . $search_value . '%')
									->where('name', 'like', $letter_value . '%')
									->withTrashed()
									->count();		
		}
		else{
			if($letter_value != ''){
				$counterparties = Counterpartie::select('*','contr.id','contr.deleted_at')
									->leftjoin('curators','curator','curators.id')
									->where('name', 'like', $letter_value . '%')
									->orderBy('name', 'asc')
									->withTrashed()
									->offset($start)
									->limit($paginate_count)
									->get();
				$counterparties_count = Counterpartie::select('contr.id')
										->where('name', 'like', $letter_value . '%')
										->withTrashed()
										->count();
			}
			else{
				if($search_name != '' && $search_value != ''){
					$counterparties = Counterpartie::select('*','contr.id','contr.deleted_at')
										->leftjoin('curators','curator','curators.id')
										->where($search_name, 'like', '%' . $search_value . '%')
										->withTrashed()
										->offset($start)
										->limit($paginate_count)
										->get();
					$counterparties_count = Counterpartie::select('contr.id')
											->where($search_name, 'like', '%' . $search_value . '%')
											->withTrashed()
											->count();		
				}
				else{
					$counterparties = Counterpartie::select('*','contr.id','contr.deleted_at')
										->leftjoin('curators','curator','curators.id')
										->withTrashed()
										->offset($start)
										->limit($paginate_count)
										->get();
					$counterparties_count = Counterpartie::select('contr.id')
											->withTrashed()
											->count();
				}
			}
		}
		$prev_page = $page - 1 > 0 ? (int)($page-1) : '';
		$next_page = $page + 1 <= (int)ceil($counterparties_count/$paginate_count) ? (int)($page+1) : '';
		return view('administrator.counterpartie.main', ['counterparties'=>$counterparties,
															'search_name'=>$search_name,
															'search_value'=>$search_value,
															'letter_value'=>$letter_value,
															'count_paginate' => (int)ceil($counterparties_count/$paginate_count),
															'prev_page' => $prev_page,
															'next_page' => $next_page,
															'page' => $page,
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
		$curators = Curator::all();
        return view('administrator.counterpartie.register', ['curators'=>$curators]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$val = Validator::make($request->all(),[
			'name' => 'required',
			'name_full' => 'required'
		])->validate();
        $counterpartie = Counterpartie::create([
			'name' => str_replace('"','',$request['name']),
			'name_full' => $request['name_full'],
			'curator' => $request['curator'],
			'is_sip_counterpartie' => $request['is_sip_counterpartie'] ? 1 : 0,
			'dishonesty' => $request['dishonesty'] ? 1 : 0,
			'lider' => $request['lider'],
			'legal_address' => $request['legal_address'],
			'mailing_address' => $request['mailing_address'],
			'actual_address' => $request['actual_address'],
			'telephone' => $request['telephone'],
			'email' => $request['email'],
			'inn' => $request['inn'],
			'ogrn' => $request['ogrn'],
			'kpp' => $request['kpp'],
			'okpo' => $request['okpo'],
			'okved' => $request['okved'],
			'number_file' => $request['number_file'],
			'statement_egryl' => $request['statement_egryl'] ? 1 : 0,
			'order_counterpartie' => $request['order_counterpartie'] ? 1 : 0,
			'protocol_meeting' => $request['protocol_meeting'] ? 1 : 0,
			'statement_egrip' => $request['statement_egrip'] ? 1 : 0,
			'statement_charter' => $request['statement_charter'] ? 1 : 0,
			'map' => $request['map'] ? 1 : 0,
			'copy_passport' => $request['copy_passport'] ? 1 : 0,
			'statement_rosstata' => $request['statement_rosstata'] ? 1 : 0,
			'proxys' => $request['proxys'] ? 1 : 0,
			'licences' => $request['licences'] ? 1 : 0,
			'certificates' => $request['certificates'] ? 1 : 0,
			'other_documents' => $request['other_documents'] ? 1 : 0
        ]);
		JournalController::store(Auth::User()->id,'Добавил контрагента с id = ' . $counterpartie->id);
		return redirect()->route('counterpartie.edit', $counterpartie->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		$counterpartie = Counterpartie::findOrFail($id);
		$employees = Employee::select()->where('id_counterpartie', $id)->get();
		$curators = Curator::all();
		
		$maps = TitleDocument::select()->where('type_title_document', 'map')->where('id_counterpartie', $id)->get();
		foreach($maps as $map)
			$map->resolutions = CounterpartieResolution::select()->where('id_title_document', $map->id)->get();
		$orders = TitleDocument::select()->where('type_title_document', 'order')->where('id_counterpartie', $id)->get();
		foreach($orders as $order)
			$order->resolutions = CounterpartieResolution::select()->where('id_title_document', $order->id)->get();
		$protocol_meetings = TitleDocument::select()->where('type_title_document', 'protocol_meeting')->where('id_counterpartie', $id)->get();
		foreach($protocol_meetings as $protocol_meeting)
			$protocol_meeting->resolutions = CounterpartieResolution::select()->where('id_title_document', $protocol_meeting->id)->get();
		$statement_egrips = TitleDocument::select()->where('type_title_document', 'statement_egrip')->where('id_counterpartie', $id)->get();
		foreach($statement_egrips as $statement_egrip)
			$statement_egrip->resolutions = CounterpartieResolution::select()->where('id_title_document', $statement_egrip->id)->get();
		$statement_charters = TitleDocument::select()->where('type_title_document', 'statement_charter')->where('id_counterpartie', $id)->get();
		foreach($statement_charters as $statement_charter)
			$statement_charter->resolutions = CounterpartieResolution::select()->where('id_title_document', $statement_charter->id)->get();
		$copy_passports = TitleDocument::select()->where('type_title_document', 'copy_passport')->where('id_counterpartie', $id)->get();
		foreach($copy_passports as $copy_passport)
			$copy_passport->resolutions = CounterpartieResolution::select()->where('id_title_document', $copy_passport->id)->get();
		$statements_rosstata = TitleDocument::select()->where('type_title_document', 'statement_rosstata')->where('id_counterpartie', $id)->get();
		foreach($statements_rosstata as $statement_rosstata)
			$statement_rosstata->resolutions = CounterpartieResolution::select()->where('id_title_document', $statement_rosstata->id)->get();
		$certificates = TitleDocument::select()->where('type_title_document', 'certificate')->where('id_counterpartie', $id)->get();
		foreach($certificates as $certificate)
			$certificate->resolutions = CounterpartieResolution::select()->where('id_title_document', $certificate->id)->get();
		$licences = TitleDocument::select()->where('type_title_document', 'licence')->where('id_counterpartie', $id)->get();
		foreach($licences as $licence)
			$licence->resolutions = CounterpartieResolution::select()->where('id_title_document', $licence->id)->get();
		$proxys = TitleDocument::select()->where('type_title_document', 'proxy')->where('id_counterpartie', $id)->get();
		foreach($proxys as $proxy)
			$proxy->resolutions = CounterpartieResolution::select()->where('id_title_document', $proxy->id)->get();
		$bank_details = BankDetail::select()->where('id_counterpartie', $id)->get();
		foreach($bank_details as $bank_detail)
			$bank_detail->resolutions = CounterpartieResolution::select()->where('id_title_document', $bank_detail->id)->get();
		$other_title_documents = TitleDocument::select()->where('type_title_document', 'other_title_document')->where('id_counterpartie', $id)->get();
		foreach($other_title_documents as $other_title_document)
			$other_title_document->resolutions = CounterpartieResolution::select()->where('id_title_document', $other_title_document->id)->get();
		
		$old_resolutrions = CounterpartieResolution::select()->join('title_documents','counterpartie_resolutions.id_title_document','title_documents.id')->where('type_title_document', 'old_resolution')->where('id_counterpartie', $id)->get();
			
        return view('administrator.counterpartie.new_edit', ['counterpartie'=>$counterpartie,
															'employees'=>$employees,
															'curators'=>$curators, 
															'maps'=>$maps,
															'orders'=>$orders,
															'protocol_meetings'=>$protocol_meetings,
															'statement_egrips'=>$statement_egrips,
															'statement_charters'=>$statement_charters,
															'copy_passports'=>$copy_passports,
															'statements_rosstata'=>$statements_rosstata,
															'certificates'=>$certificates, 
															'licences'=>$licences, 
															'proxys'=>$proxys, 
															'bank_details'=>$bank_details, 
															'other_title_documents'=>$other_title_documents,
															'old_resolutions'=>$old_resolutrions]);
    }
	
	public function new_edit($id)
	{
		$counterpartie = Counterpartie::findOrFail($id);
		$curators = Curator::all();
		$maps = TitleDocument::select()->where('type_title_document', 'map')->where('id_counterpartie', $id)->get();
		foreach($maps as $map)
			$map->resolutions = CounterpartieResolution::select()->where('id_title_document', $map->id)->get();
		$orders = TitleDocument::select()->where('type_title_document', 'order')->where('id_counterpartie', $id)->get();
		foreach($orders as $order)
			$order->resolutions = CounterpartieResolution::select()->where('id_title_document', $order->id)->get();
		$protocol_meetings = TitleDocument::select()->where('type_title_document', 'protocol_meeting')->where('id_counterpartie', $id)->get();
		foreach($protocol_meetings as $protocol_meeting)
			$protocol_meeting->resolutions = CounterpartieResolution::select()->where('id_title_document', $protocol_meeting->id)->get();
		$statement_egrips = TitleDocument::select()->where('type_title_document', 'statement_egrip')->where('id_counterpartie', $id)->get();
		foreach($statement_egrips as $statement_egrip)
			$statement_egrip->resolutions = CounterpartieResolution::select()->where('id_title_document', $statement_egrip->id)->get();
		$statement_charters = TitleDocument::select()->where('type_title_document', 'statement_charter')->where('id_counterpartie', $id)->get();
		foreach($statement_charters as $statement_charter)
			$statement_charter->resolutions = CounterpartieResolution::select()->where('id_title_document', $statement_charter->id)->get();
		$copy_passports = TitleDocument::select()->where('type_title_document', 'copy_passport')->where('id_counterpartie', $id)->get();
		foreach($copy_passports as $copy_passport)
			$copy_passport->resolutions = CounterpartieResolution::select()->where('id_title_document', $copy_passport->id)->get();
		$statements_rosstata = TitleDocument::select()->where('type_title_document', 'statement_rosstata')->where('id_counterpartie', $id)->get();
		foreach($statements_rosstata as $statement_rosstata)
			$statement_rosstata->resolutions = CounterpartieResolution::select()->where('id_title_document', $statement_rosstata->id)->get();
		$certificates = TitleDocument::select()->where('type_title_document', 'certificate')->where('id_counterpartie', $id)->get();
		foreach($certificates as $certificate)
			$certificate->resolutions = CounterpartieResolution::select()->where('id_title_document', $certificate->id)->get();
		$licences = TitleDocument::select()->where('type_title_document', 'licence')->where('id_counterpartie', $id)->get();
		foreach($licences as $licence)
			$licence->resolutions = CounterpartieResolution::select()->where('id_title_document', $licence->id)->get();
		$proxys = TitleDocument::select()->where('type_title_document', 'proxy')->where('id_counterpartie', $id)->get();
		foreach($proxys as $proxy)
			$proxy->resolutions = CounterpartieResolution::select()->where('id_title_document', $proxy->id)->get();
		$bank_details = BankDetail::select()->where('id_counterpartie', $id)->get();
		foreach($bank_details as $bank_detail)
			$bank_detail->resolutions = CounterpartieResolution::select()->where('id_title_document', $bank_detail->id)->get();
		$other_title_documents = TitleDocument::select()->where('type_title_document', 'other_title_document')->where('id_counterpartie', $id)->get();
		foreach($other_title_documents as $other_title_document)
			$other_title_document->resolutions = CounterpartieResolution::select()->where('id_title_document', $other_title_document->id)->get();
        return view('administrator.counterpartie.new_edit', ['counterpartie'=>$counterpartie, 
															'curators'=>$curators, 
															'maps'=>$maps,
															'orders'=>$orders,
															'protocol_meetings'=>$protocol_meetings,
															'statement_egrips'=>$statement_egrips,
															'statement_charters'=>$statement_charters,
															'copy_passports'=>$copy_passports,
															'statements_rosstata'=>$statements_rosstata,
															'certificates'=>$certificates, 
															'licences'=>$licences, 
															'proxys'=>$proxys, 
															'bank_details'=>$bank_details, 
															'other_title_documents'=>$other_title_documents]);
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
        $val = Validator::make($request->all(),[
			'name' => 'required',
			'name_full' => 'required'
		])->validate();
		$counterpartie = Counterpartie::findOrFail($id);
		$counterpartie->fill([
			'name' => $request['name'],
			'name_full' => $request['name_full'],
			'curator' => $request['is_no_sip'] ? $counterpartie->curator : $request['curator'],
			'is_sip_counterpartie' => $request['is_no_sip'] ? $counterpartie->is_sip_counterpartie : ($request['is_sip_counterpartie'] ? 1 : 0),
			'dishonesty' => $request['dishonesty'] ? 1 : 0,
			'lider' => $request['lider'],
			'legal_address' => $request['legal_address'],
			'mailing_address' => $request['mailing_address'],
			'actual_address' => $request['actual_address'],
			'telephone' => $request['telephone'],
			'email' => $request['email'],
			'inn' => $request['inn'],
			'ogrn' => $request['ogrn'],
			'kpp' => $request['kpp'],
			'okpo' => $request['okpo'],
			'okved' => $request['okved'],
			'number_file' => $request['number_file'],
			'statement_egryl' => $request['statement_egryl'] ? 1 : 0,
			'order_counterpartie' => $request['order_counterpartie'] ? 1 : 0,
			'protocol_meeting' => $request['protocol_meeting'] ? 1 : 0,
			'statement_egrip' => $request['statement_egrip'] ? 1 : 0,
			'statement_charter' => $request['statement_charter'] ? 1 : 0,
			'map' => $request['map'] ? 1 : 0,
			'copy_passport' => $request['copy_passport'] ? 1 : 0,
			'statement_rosstata' => $request['statement_rosstata'] ? 1 : 0,
			'proxys' => $request['proxys'] ? 1 : 0,
			'licences' => $request['licences'] ? 1 : 0,
			'certificates' => $request['certificates'] ? 1 : 0,
			'other_documents' => $request['other_documents'] ? 1 : 0
		]);
		$all_dirty = JournalController::getMyChanges($counterpartie);
		$counterpartie->save();
		JournalController::store(Auth::User()->id,'Обновил контрагента с id = ' . $counterpartie->id . '~' . json_encode($all_dirty));
		return redirect()->back()->with('success','Успешно изменен!');
    }

	public function store_title_document(Request $request, $id_counterpartie)
	{
		$val = Validator::make($request->all(),[
			'date_title_document' => 'required',
			'text_title_document' => 'required',
			'type_title_document' => 'required'
		])->validate();
		$titledocument = TitleDocument::create([
			'id_counterpartie' => $id_counterpartie,
			'date_title_document' => $request['date_title_document'],
			'text_title_document' => $request['text_title_document'],
			'type_title_document' => $request['type_title_document'],
			'relevance_document' => $request['relevance_document'] ? 1 : 0
		]);
		if($request->file('new_file_resolution'))
			ResolutionController::save_in_bd_resol_counterpartie($request, $titledocument->id);
		JournalController::store(Auth::User()->id,'Добавил новый документ к контрагенту с id = ' . $id_counterpartie);
		return redirect()->back()->withInput()->with('check_type_title_document', $request['type_title_document']);
	}
	
	public function update_title_document(Request $request, $id_title_document)
	{
		$val = Validator::make($request->all(),[
			'date_title_document' => 'required',
			'text_title_document' => 'required',
			'type_title_document' => 'required'
		])->validate();
		$titledocument = TitleDocument::findOrFail($id_title_document);
		$titledocument->fill([
			'date_title_document' => $request['date_title_document'],
			'text_title_document' => $request['text_title_document'],
			'type_title_document' => $request['type_title_document'],
			'relevance_document' => $request['relevance_document'] ? 1 : 0
		]);
		$titledocument->save();
		JournalController::store(Auth::User()->id,'Обновил документ с id = ' . $id_title_document);
		return redirect()->back()->withInput();
	}
	
	public function store_bank_detail(Request $request, $id_counterpartie)
	{
		$val = Validator::make($request->all(),[
			'checking_account' => 'required'
		])->validate();
		$bank_detail = BankDetail::create([
			'id_counterpartie' => $id_counterpartie,
			'checking_account' => $request['checking_account'],
			'bank_account' => $request['bank_account'],
			'correspondent_account' => $request['correspondent_account'],
			'personal_account' => $request['personal_account'],
			'bik' => $request['bik'],
			'bank_ca_pa' => $request['bank_ca_pa']
		]);
		JournalController::store(Auth::User()->id,'Добавил новые банковские реквизиты к контрагенту с id = ' . $id_counterpartie);
		return redirect()->back()->withInput()->with('check_type_title_document', $request['bank_detail']);
	}
	
	public function update_bank_detail(Request $request, $id_bank_detail)
	{
		$val = Validator::make($request->all(),[
			'checking_account' => 'required'
		])->validate();
		$bank_detail = BankDetail::findOrFail($id_bank_detail);
		$bank_detail->fill([
			'checking_account' => $request['checking_account'],
			'bank_account' => $request['bank_account'],
			'correspondent_account' => $request['correspondent_account'],
			'personal_account' => $request['personal_account'],
			'bik' => $request['bik'],
			'bank_ca_pa' => $request['bank_ca_pa']
		]);
		$bank_detail->save();
		JournalController::store(Auth::User()->id,'Обновил банковские реквизиты с id = ' . $id_bank_detail);
		return redirect()->back()->withInput();
	}
	
	public function show_reestr($id_counterpartie)
	{
		$counterpartie = Counterpartie::findOrFail($id_counterpartie);
		$reestr = ReestrContract::select(['contracts.id','id_counterpartie_contract','number_contract','date_registration_project_reestr',
											'date_contract_reestr','amount_reestr','name_work_contract','number_counterpartie_contract_reestr', 'item_contract'])
										->rightjoin('contracts', 'contracts.id', '=', 'reestr_contracts.id_contract_reestr')
										->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
										->where('contracts.id_counterpartie_contract', $id_counterpartie)
										->where('contracts.deleted_at', null)
										->orderBy('contracts.id', 'desc')
										->get();
		return view('reestr.counterpartie', ['counterpartie'=>$counterpartie,'contracts'=>$reestr]);
	}
	
	public function store_employee(Request $request, $id_counterpartie)
	{
		$val = Validator::make($request->all(),[
			'FIO' => 'required'
		])->validate();
		$employee = new Employee();
		$employee->fill($request->all());
		$employee->id_counterpartie = $id_counterpartie;
		$employee->save();
		JournalController::store(Auth::User()->id,'Добавил нового сотрудника к контрагенту с id = ' . $id_counterpartie);
		return redirect()->back()->with('success','Успешно добавлен сотрудник!');
	}
	
	public function update_employee(Request $request, $id_employee)
	{
		$val = Validator::make($request->all(),[
			'FIO' => 'required'
		])->validate();
		$employee = Employee::findOrFail($id_employee);
		$employee->fill($request->all());
		$employee->save();
		JournalController::store(Auth::User()->id,'Обновил сотрудника с id = ' . $id_employee);
		return redirect()->back()->with('success','Успешно изменен сотрудник!');
	}
	
	public function delete_employee($id_employee)
	{
		$employee = Employee::findOrFail($id_employee);
		$employee->delete();
		JournalController::store(Auth::User()->id,'Удалил сотрудника с id = ' . $id_employee);
		return redirect()->back()->with('success','Успешно удален сотрудник!');
	}
	
	public function search_counterpartie(Request $request)
	{
		$search_name = '';
		if(strlen($request['search_name']) > 0) {
			$search_name = $request['search_name'];
		}
		$search_value = '';
		if(strlen($request['search_value']) > 0) {
			$search_value = $request['search_value'];
		}
		$search_counterparties = Counterpartie::select('*','contr.id','contr.inn','contr.name','contr.name_full')
							->where($search_name, 'like', '%' . $search_value . '%')
							->orderBy('name', 'asc')
							->get();
		return redirect()->back()->with(['search_counterparties'=>$search_counterparties]);
	}
	
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$counterpartie = Counterpartie::findOrFail($id);
		$counterpartie->delete();
		JournalController::store(Auth::User()->id,'Удалил контрагента с id = ' . $id);
		return redirect()->back()->with('success','Успешно удален!');
    }
}
