<?php

namespace App\Http\Controllers;

use App\Document;
use App\Application;
use App\Contract;
use App\Resolution;
use App\Component;
use App\ComponentPack;
use App\Protocol;
use App\SecondDepartmentTour;
use App\Invoice;
use App\State;
use App\Curator;
use App\Counterpartie;
use Illuminate\Http\Request;

class TreeController extends Controller
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
		//Отображения графа заявки
		$documents = Document::select(['documents.id',
										'documents.date_document',
										'documents.id_old_document',
										'applications.id as appID',
										'applications.number_application',
										'applications.number_outgoing'
										])
										->join('applications','id_application_document','applications.id')
										->where('documents.id', $id)
										->orderBy('documents.id', 'desc')
										->get();
		$result = [];
		$number_document = 0;
		$date_document = '';
		foreach($documents as $document)
		{
			$number_document = $document->number_application;
			$date_document = $document->date_document;
			$contracts = Contract::select(['contracts.id','contracts.number_contract','reestr_contracts.number_counterpartie_contract_reestr','view_works.name_view_work',
										'contracts.name_work_contract','reestr_contracts.amount_reestr','reestr_contracts.amount_contract_reestr','reestr_contracts.date_maturity_date_reestr','reestr_contracts.date_maturity_reestr'])
										->leftjoin('reestr_contracts','contracts.id','reestr_contracts.id_contract_reestr')
										->leftjoin('view_works','id_view_work_contract','view_works.id')
										->where('id_document_contract', $document->id)
										->orderBy('contracts.id', 'desc')
										->get();
			foreach($contracts as $contract)
			{
				$my_applications_in_contract = Application::select(['*'])->where('id_contract_application', $contract->id)->where('is_protocol',0)->get();
				$contract->applications_in_contract = $my_applications_in_contract;
				$my_protocols_in_contract = Application::select(['*'])->where('id_contract_application', $contract->id)->where('is_protocol',1)->get();
				$contract->protocols_in_contract = $my_protocols_in_contract;
			}
			array_push($result, $contracts);
		}
		$my_applications = Application::select(['*'])->where('id_document_application', $documents[0]->id)->get();
		$applications = Application::select(['*','applications.id','applications.number_outgoing'])
										->leftJoin('documents','applications.id','documents.id_application_document')
										->where('applications.id_contract_application', null)
										->where('applications.id_document_application', null)
										->where('documents.id_application_document', null)
										->where('applications.id_counterpartie_application', $documents[0]->id_counterpartie_application)
										->orderBy('applications.id', 'desc')
										->get();
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
        return view('tree.show', ['number_document'=>$number_document,
												'date_document'=>$date_document,
												'documents'=>$documents,
												'result'=>$result,
												'applications'=>$applications,
												'my_applications'=>$my_applications,
												'resolutions'=>$resolutions,
												'my_parents'=>$my_parents,
												'my_childs'=>$my_childs
		]);
    }
	
	private function recurse_search_child(&$my_childs)
	{
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
	
	public function show_component($id_element_component)
	{
		$components = Component::select('*')->where('id_element_component',$id_element_component)->get();
		$documents = Component::select('id_document')->groupBy('id_document')->get();
		dd($documents);
		$packs = ComponentPack::select('*')->where('id', $component->id_pack)->get();
		$component->documents = $documents;
		$component->packs = $packs;
		dd($components);
		return view('tree.show_component', ['components'=>$components]);
	}
	
	public function show_contract($id_contract)
	{
		$contract = Contract::select(['contracts.id', 'number_contract', 'number_counterpartie_contract_reestr',
										'id_counterpartie_contract','number_contract','name_work_contract','id_goz_contract','goz_works.name_works_goz',
										'id_view_work_contract', 'view_works.name_view_work',
										'reestr_contracts.number_counterpartie_contract_reestr','reestr_contracts.executor_reestr',
										'reestr_contracts.amount_reestr','reestr_contracts.amount_contract_reestr',
										'reestr_contracts.amount_begin_reestr', 'reestr_contracts.vat_begin_reestr', 'reestr_contracts.approximate_amount_begin_reestr', 'reestr_contracts.fixed_amount_begin_reestr',
										'reestr_contracts.fix_amount_contract_reestr', 'reestr_contracts.vat_reestr', 'reestr_contracts.approximate_amount_reestr', 'reestr_contracts.fixed_amount_reestr',
										'reestr_contracts.date_b_contract_reestr', 'reestr_contracts.date_e_contract_reestr', 'reestr_contracts.date_contract_reestr', 
										'reestr_contracts.date_maturity_date_reestr', 'reestr_contracts.date_e_maturity_reestr', 'reestr_contracts.date_maturity_reestr', 'name_view_contract',
										'item_contract','reestr_contracts.date_contract_on_first_reestr', 'executor_contract_reestr'])
								->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
								->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
								->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
								->join('goz_works', 'contracts.id_goz_contract', '=', 'goz_works.id')
								->where('contracts.id', $id_contract)
								->first();
		$curators = Curator::all();
		foreach($curators as $curator)
			if($curator->id == $contract->executor_contract_reestr)
				$contract->name_executor = $curator->FIO;
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		foreach($counterparties as $counter)
			if($contract->id_counterpartie_contract == $counter->id)
				$contract->name_counterpartie_contract = $counter->name;
		$protocols = Protocol::select()->where('id_contract', $id_contract)->where('is_protocol', 1)->get();
		$additional_agreements = Protocol::select()->where('id_contract', $id_contract)->where('is_additional_agreement', 1)->get();
		$tours = SecondDepartmentTour::select(['second_department_tours.id', 'second_department_tours.number_duty'])
																->where('second_department_tours.id_contract', $id_contract)
																->orderBy('second_department_tours.number_duty', 'asc')
																->get();
		//Счета по договору
		$amount_scores = 0;
		$amount_prepayments = 0;
		$amount_invoices = 0;
		$amount_payments = 0;
		$amount_returns = 0;
		$invoices = Invoice::select(['invoices.amount_p_invoice', 'name_invoices.name', 'is_prepayment_invoice'])
									->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
									->where('id_contract', $id_contract)
									->get();
		foreach($invoices as $score)
			if($score->name == 'Счет на оплату' || $score->name == 'Аванс')
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
		//Все счета
		$scores = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
											->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
											->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
											->where('id_contract', $id_contract)
											->where('name', 'Счет на оплату')
											->orderBy('invoices.number_invoice', 'asc')
											->get();
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
		//Вся резолюция, но отмеченная, как оригиналы
		$result = [];
		$resolutions = Resolution::select(['*'])->where('id_contract_resolution', $contract->id)->where('type_resolution', 1)->orderBy('id','desc')->get();
		array_push($result, $resolutions);
		foreach($protocols as $protocol)
		{
			$protocol_resolutions = Resolution::select(['*'])->where('id_protocol_resolution', $protocol->id)->where('type_resolution', 1)->orderBy('id','desc')->get();			
			array_push($result, $protocol_resolutions);
			
			$states = State::select(['states.id','name_state','comment_state','date_state','users.surname','users.name','users.patronymic'])
					->join('users','users.id','states.id_user')
					->where('id_protocol', $protocol->id)
					->get();
			
			$protocol->complete = 0;
			if(count($states) > 0)
				if($states[count($states) - 1]->name_state == "Заключен" OR $states[count($states) - 1]->name_state == "Заключён")
					$protocol->complete = 1;
		}
		foreach($additional_agreements as $additional_agreement)
		{
			$additional_agreement_resolutions = Resolution::select(['*'])->where('id_protocol_resolution', $additional_agreement->id)->where('type_resolution', 1)->orderBy('id','desc')->get();
			array_push($result, $additional_agreement_resolutions);
			
			$states = State::select(['states.id','name_state','comment_state','date_state','users.surname','users.name','users.patronymic'])
					->join('users','users.id','states.id_user')
					->where('id_protocol', $additional_agreement->id)
					->get();
			
			$additional_agreement->complete = 0;
			if(count($states) > 0)
				if($states[count($states) - 1]->name_state == "Заключен" OR $states[count($states) - 1]->name_state == "Заключён")
					$additional_agreement->complete = 1;
		}
		//История договора
		$states = State::select(['states.id','name_state','comment_state','date_state','users.surname','users.name','users.patronymic'])->join('users','users.id','states.id_user')->where('id_contract', $id_contract)->where('is_work_state', null)->get();
		//Стадии выполнения
		$work_states = State::select(['states.id','name_state','comment_state','date_state','users.surname','users.name','users.patronymic'])->join('users','users.id','states.id_user')->where('id_contract', $id_contract)->where('is_work_state', 1)->get();
		return view('tree.show_contract', ['contract'=>$contract, 
											'protocols'=>$protocols, 
											'additional_agreements'=>$additional_agreements, 
											'tours'=>$tours,
											'resolutions'=>$result,
											'states'=>$states,
											'work_states'=>$work_states,
											'scores'=>$scores,
											'prepayments'=>$prepayments,
											'invoices'=>$invoices,
											'payments'=>$payments,
											'returns'=>$returns
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
