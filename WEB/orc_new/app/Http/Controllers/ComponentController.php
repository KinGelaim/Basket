<?php

namespace App\Http\Controllers;

use App\Component;
use App\DocumentComponent;
use App\Document;
use App\Application;
use App\ReconciliationUser;
use Auth;
use App\User;
use App\Comment;
use App\Counterpartie;
//use App\ViewWork;
use App\ViewContract;
use App\Contract;
use App\ReestrContract;
use App\Checkpoint;
use App\ComponentContract;	//Для прикрепления паков к контрактам
use App\ComponentElement;
use App\ComponentPack;
use App\ComponentsContract;	//Для прикрепления внутренних компонентов к контрактам
use App\ComponentElementParty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComponentController extends Controller
{
	//Конструктор
	public function __construct()
    {
		//Проверка на авторизацию
        $this->middleware('auth');
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$results = [];
		$results_count = [];
		$results_id_element = [];
        $components = Component::select('*', 'components.id')->join('component_elements', 'component_elements.id', 'components.id_element_component')->orderBy('id_element_component','asc')->get();
		$name_component = 0;
		foreach($components as $component)
		{
			$number_app = Document::select('number_application')->join('applications','applications.id','documents.id_application_document')->where('documents.id', $component->id_document)->first();
			$component->number_application = $number_app->number_application;
			$contracts_id = ComponentsContract::select('id_contract','number_contract')->join('contracts','contracts.id','id_contract')->where('id_component', $component->id)->get();
			$component->contracts_id = $contracts_id;
			if($name_component == 0 || $component->name_component != $name_component)
			{
				$results += [$component->name_component => [0=>$component]];
				$results_count += [$component->name_component => $component->need_count];
				$name_component = $component->name_component;
			}
			else
			{
				array_push($results[$component->name_component],$component);
				$results_count[$component->name_component] += $component->need_count;
			}
		}
		//dd($results);
		//dd($results_count);
		$contracts = Contract::select(['contracts.id', 'contracts.id_counterpartie_contract', 'contracts.name_work_contract', 'contracts.number_contract','view_works.name_view_work', 
											'number_counterpartie_contract_reestr', 'reestr_contracts.amount_reestr','reestr_contracts.amount_contract_reestr',
											'reestr_contracts.date_maturity_date_reestr','reestr_contracts.date_maturity_reestr'])
										->join('component_contracts','component_contracts.id_contract','contracts.id')
										->join('view_works','view_works.id','contracts.id_view_work_contract')
										->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
										->orderBy('contracts.id', 'desc')
										->get();
		$counterparties = Counterpartie::select(['*','contr.id'])->join('curators','contr.curator','curators.id')->orderBy('name', 'asc')->get();
		foreach($contracts as $contract)
			foreach($counterparties as $counter)
				if($contract->id_counterpartie_contract == $counter->id)
				{
					$contract->name_counterpartie_contract = $counter->name;
					$contract->curator_contract = $counter->FIO;
				}
		//Внешняя комплектация
		$packs = ComponentPack::select('*','component_packs.id')->join('component_elements', 'component_elements.id','component_packs.id_element')->get();
		foreach($packs as $pack)
		{
			$components_pack = Component::select('need_count')->where('id_pack', $pack->id)->get();
			$need_count = 0;
			foreach($components_pack as $component)
				$need_count += $component->need_count;
			$pack->need_count = $need_count;
		}
		//Фильтр
		$id_counterpartie = 0;
		$counterpartie_equal = '>';
		if(isset($_GET['counterpartie']))
		{
			if($_GET['counterpartie'] > 0)
			{
				$id_counterpartie = $_GET['counterpartie'];
				$counterpartie_equal = '=';
			}
		}
		//Письма
		$new_my_applications = ReconciliationUser::select(['applications.id',
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
														->where('applications.id_counterpartie_application',$counterpartie_equal,$id_counterpartie)
														->get();
		$my_applications = ReconciliationUser::select(['applications.id',
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
														->where('applications.is_protocol', 0)
														->where('reconciliation_users.is_document', 0)
														->where('applications.deleted_at', null)
														->where('applications.id_counterpartie_application',$counterpartie_equal,$id_counterpartie)
														->get();
		foreach($new_my_applications as $application)
			foreach($counterparties as $counterpartie)
				if($application->id_counterpartie_application == $counterpartie->id)
					$application->counterpartie_name = $counterpartie->name;
		foreach($my_applications as $application)
			foreach($counterparties as $counterpartie)
				if($application->id_counterpartie_application == $counterpartie->id)
					$application->counterpartie_name = $counterpartie->name;
		//Заявки на комплектацию
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
															->where('applications.id_counterpartie_application',$counterpartie_equal,$id_counterpartie)
															->get();
			$my_components = ReconciliationUser::select(['reconciliation_users.id',
																'applications.id as id_application',
																'documents.id as id_document',
																'applications.number_application', 
																'applications.id_counterpartie_application', 
																'applications.theme_application'
															])->Join('applications', 'applications.id', 'id_application')
															->leftJoin('documents', 'applications.id', 'id_application_document')
															->where('id_user', Auth::User()->id)
															->where('check_reconciliation', 1)
															->where('check_agree_reconciliation', 0)
															->where('applications.is_protocol', 0)
															->where('reconciliation_users.is_document', 1)
															->where('applications.deleted_at', null)
															->where('applications.id_counterpartie_application',$counterpartie_equal,$id_counterpartie)
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
		return view('department.ten.main', ['results'=>$results, 
											'results_count'=>$results_count, 
											'components'=>$components, 
											'contracts'=>$contracts, 
											'packs'=>$packs, 
											'my_components'=>$my_components, 
											'new_my_components'=>$new_my_components,
											'new_my_applications'=>$new_my_applications,
											'my_applications'=>$my_applications,
											'id_counterpartie'=>$id_counterpartie,
											'counterparties'=>$counterparties
											]);
    }
	
    public function index_2()
    {
		$results = [];
		$results_count = [];
		$results_id_element = [];
        $components = Component::select('*', 'components.id')
													->join('component_elements', 'component_elements.id', 'components.id_element_component')
													->orderBy('id_element_component','asc')
													->get();
		$name_component = 0;
		foreach($components as $component)
		{
			$contracts_id = Component::select('id_contract','number_contract')->join('contracts','contracts.id','id_contract')->where('components.id', $component->id)->get();
			$component->contracts_id = $contracts_id;
			if($name_component == 0 || $component->name_component != $name_component)
			{
				$results += [$component->name_component => [0=>$component]];
				$results_count += [$component->name_component => $component->need_count];
				$name_component = $component->name_component;
			}
			else
			{
				array_push($results[$component->name_component],$component);
				$results_count[$component->name_component] += $component->need_count;
			}
		}
		//dd($results);
		//dd($results_count);
		//Контракты 10-го отдела
		$contracts = Contract::select(['contracts.id', 'contracts.id_counterpartie_contract', 'contracts.name_work_contract', 'contracts.number_contract','view_works.name_view_work', 
											'number_counterpartie_contract_reestr', 'reestr_contracts.amount_reestr','reestr_contracts.amount_contract_reestr',
											'reestr_contracts.date_maturity_date_reestr','reestr_contracts.date_maturity_reestr'])
										->leftJoin('view_works','view_works.id','contracts.id_view_work_contract')
										->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
										->where('contracts.number_contract', 'like', '%‐23‐%')
										->orderBy('contracts.id', 'desc')
										->get();
		//Контракты ПЭО (не сип)
		$contracts_sip = Contract::select(['contracts.id', 'contracts.id_counterpartie_contract', 'contracts.name_work_contract', 'contracts.number_contract','view_works.name_view_work', 
											'number_counterpartie_contract_reestr', 'reestr_contracts.amount_reestr','reestr_contracts.amount_contract_reestr',
											'reestr_contracts.date_maturity_date_reestr','reestr_contracts.date_maturity_reestr'])
										->leftJoin('view_works','view_works.id','contracts.id_view_work_contract')
										->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
										->where('number_contract', 'like', '%‐02‐%')
										->orderBy('contracts.id', 'desc')
										->get();
		//Контракты с комплектацией
		$contracts_components = Contract::select(['contracts.id', 'contracts.id_counterpartie_contract', 'contracts.name_work_contract', 'contracts.number_contract','view_works.name_view_work', 
											'number_counterpartie_contract_reestr', 'reestr_contracts.amount_reestr','reestr_contracts.amount_contract_reestr',
											'reestr_contracts.date_maturity_date_reestr','reestr_contracts.date_maturity_reestr'])
										->join('components','components.id_contract','contracts.id')
										->leftJoin('view_works','view_works.id','contracts.id_view_work_contract')
										->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
										->orderBy('contracts.id', 'desc')
										->get()
										->unique();
		$counterparties = Counterpartie::select(['*','contr.id'])->join('curators','contr.curator','curators.id')->orderBy('name', 'asc')->get();
		foreach($contracts_components as $contract)
			foreach($counterparties as $counter)
				if($contract->id_counterpartie_contract == $counter->id)
				{
					$contract->name_counterpartie_contract = $counter->name;
					$contract->curator_contract = $counter->FIO;
				}
		//Внешняя комплектация
		$packs = ComponentPack::select('*','component_packs.id')->join('component_elements', 'component_elements.id','component_packs.id_element')->get();
		foreach($packs as $pack)
		{
			$components_pack = Component::select('need_count')->where('id_pack', $pack->id)->get();
			$need_count = 0;
			foreach($components_pack as $component)
				$need_count += $component->need_count;
			$pack->need_count = $need_count;
		}
		//Фильтр
		$id_counterpartie = 0;
		$counterpartie_equal = '>';
		if(isset($_GET['counterpartie']))
		{
			if($_GET['counterpartie'] > 0)
			{
				$id_counterpartie = $_GET['counterpartie'];
				$counterpartie_equal = '=';
			}
		}
		return view('department.ten.new_main', ['results'=>$results, 
											'results_count'=>$results_count, 
											'components'=>$components,
											'contracts'=>$contracts,
											'contracts_sip'=>$contracts_sip,
											'contracts_components'=>$contracts_components,
											'packs'=>$packs,
											'id_counterpartie'=>$id_counterpartie,
											'counterparties'=>$counterparties
											]);
    }

    public function create_contract($id_pack)
    {
        //Отображение представления страницы создания нового контракта
		$pack = ComponentPack::select('*','component_packs.id')->join('component_elements', 'component_packs.id_element', 'component_elements.id')->where('component_packs.id',$id_pack)->get()[0];
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		$view_contracts = ViewContract::select()->where('is_sip_view_contract', 1)->orderBy('name_view_contract','asc')->get();
		return view('department.ten.contract_new', ['pack'=>$pack, 'counterparties'=>$counterparties, 'viewContracts'=>$view_contracts]);
    }
	
	public function store_contract(Request $request, $id_pack)
	{
		$pack = ComponentPack::findOrFail($id_pack);
		$val = Validator::make($request->all(),[
			'id_counterpartie_contract' => 'required',
			'name_work_contract' => 'required',
			'id_view_contract' => 'required',
			'year_contract' => 'required'
		])->validate();
		$contract = new Contract();
		$contract->fill(['id_counterpartie_contract' => $request['id_counterpartie_contract'],
					'name_work_contract' => $request['name_work_contract'],
					'id_goz_contract' => $request['goz_contract'] ? 1 : 2,
					'year_contract' => $request['year_contract'],
					'is_sip_contract' => 1
		]);
		$contract->save();
		$component_contract = new ComponentContract();
		$component_contract->fill([
						'id_pack' => $id_pack,
						'id_contract' => $contract->id
						]);
		$component_contract->save();
		$reestr = new ReestrContract();
		$reestr->fill([
					'id_contract_reestr' => $contract->id,
					'id_view_contract' => $request['id_view_contract'],
					'amount_reestr' => $request['amount'],
					'fix_amount_contract_reestr' => $request['fix_amount'] ? 1 : 0,
					'date_maturity_date_reestr' => $request['date_test_date'],
					'date_maturity_reestr' => $request['date_test'] ? $request['date_textarea'] : null
		]);
		$reestr->save();
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
				}
		$pack->check_complete = 1;
		$pack->save();
		JournalController::store(Auth::User()->id,'Создание нового контракта из 10 отдела с id = ' . $contract->id);
        return redirect()->route('ten.edit_component_pack', $id_pack);
	}
	
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function create()
	{
		$elements = ComponentElement::select('*')->orderBy('name_component', 'asc')->get();
		return view('department.ten.create_component', ['elements'=>$elements]);
	}
	
	public function store_component(Request $request)
	{
        //Создание новой комплектации
		$component = new Component([
							'id_element_component' => $request['element'],
							'need_count' => $request['need_count']
						]);
		$component->save();
		JournalController::store(Auth::User()->id,'Создание новой комплектации из 10 отдела с id = ' . $component->id);
		return redirect()->route('department.ten');
	}
	
	
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id_contract)
    {
        //Создание новой комплектации с привязкой к контракту
		$component = new Component([
							'id_element_component' => $request['element'],
							'id_contract' => $id_contract,
							'need_count' => $request['need_count']
						]);
		$packs = ComponentPack::select('*')->where('id_element', $component->id_element_component)->where('check_complete', '0')->get();
		if(count($packs) == 0)
		{
			$new_pack = new ComponentPack([
							'id_element' => $component->id_element_component,
							'check_complete' => 0
						]);
			$new_pack->save();
			$component->id_pack = $new_pack->id;
		}
		else
			$component->id_pack = $packs[0]->id;
		$component->save();
		/*$document_component = new DocumentComponent([
						'id_document' => $id_document,
						'id_component' => $component->id
						]);
		$document_component->save();*/
		JournalController::store(Auth::User()->id,'Создание новой комплектации из 10 отдела с id = ' . $component->id);
		return redirect()->back()->with('success','Комплектации успешно привязаны!');
    }
	
	public function store_old_component(Request $request, $id_contract)
    {
		$element = ComponentElementParty::findOrFail($request['element']);
        //Создание новой комплектации со СКЛАДА с привязкой к контракту
		$component = new Component([
							'id_element_component' => $element->id_element,
							'id_party' => $request['element'],
							'id_contract' => $id_contract,
							'need_count' => $request['need_count']
						]);
		$component->save();
		JournalController::store(Auth::User()->id,'Создание новой комплектации со склада из 10 отдела с id = ' . $component->id);
		return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Отображение карточки комплектации документа
		$document = Document::select('*')->where('id',$id)->get();
		$application = Application::select('*')->where('id',$document[0]->id_application_document)->get();
		$counterparties = Counterpartie::select(['*','contr.id'])->join('curators','contr.curator','curators.id')->orderBy('name', 'asc')->get();
		foreach($counterparties as $counter)
			if($application[0]->id_counterpartie_application == $counter->id)
			{
				$application[0]->name_counterpartie_contract = $counter->name;
				$application[0]->curator_contract = $counter->FIO;
				break;
			}
		$reconciliations = ReconciliationUser::select('*')->where('id_application', $application[0]->id)->where('is_document', '1')->get();
		if(Auth::User())
			if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Десятый отдел')
				foreach($reconciliations as $rec)
					if($rec->id_user == Auth::User()->id)
					{
						$rec->check_reconciliation = 1;
						$rec->save();
					}
		$all_users = User::getAllFIO();
		$directed_list = ReconciliationUser::select(['reconciliation_users.id as recID',
														'reconciliation_users.check_reconciliation',
														'users.id as userID',
														'users.surname', 
														'users.name', 
														'users.patronymic'
												])->join('users', 'users.id', 'id_user')
												->where('id_application',$application[0]->id)
												->where('is_document', 1)
												->get();
		$comments = Comment::select('*','comments.id','comments.created_at','comments.updated_at')->join('users', 'comments.author', 'users.id')->where('id_application',$application[0]->id)->where('is_document', 1)->get();
		$components = Component::select('*','components.id')
						->leftJoin('component_elements', 'component_elements.id', 'components.id_element_component')
						->leftJoin('component_element_parties','component_element_parties.id','components.id_party')
						->where('components.id_contract', $id)
						->get();
		$elements = ComponentElement::select('*')->orderBy('name_component', 'asc')->get();
		$old_elements =  ComponentElementParty::select('*','component_element_parties.id')
							->join('component_elements','component_elements.id','component_element_parties.id_element')
							->get();
		foreach($old_elements as $in_old_elements)
		{
			$party = Component::select('need_count')->where('id_party', $in_old_elements->id)->get();
			foreach($party as $component)
			{
				$in_old_elements->count_party -= $component->need_count;
			}
		}
		return view('department.ten.document_component', [
															'document'=>$document[0],
															'application'=>$application[0],
															'reconciliations'=>$reconciliations,
															'all_users'=>$all_users,
															'directed_list'=>$directed_list,
															'comments'=>$comments,
															'components'=>$components,
															'elements'=>$elements,
															'old_elements'=>$old_elements
														]);
    }
	
	public function show_contract($id_contract)
    {
        //Отображение карточки комплектации контракта
		//Компоненты документа
		/*$contract = Contract::findOrFail($id_contract);
		$all_components = Component::select('*','components.id')
						->join('component_elements', 'component_elements.id', 'components.id_element_component')
						->where('components.id_contract', $contract->id)
						->get();
		foreach($all_components as $component)
		{
			$pr = ComponentsContract::select('*')->where('id_component', $component->id)->get();
			foreach($pr as $p)
				$component->need_count = $component->need_count - $p->count_components;
		}
		//Компоненты контракта
		$components = Component::select('*','components.id as id','components_contracts.id as componentsID')
						->join('component_elements', 'component_elements.id', 'components.id_element_component')
						->join('components_contracts', 'components_contracts.id_component', 'components.id')
						->where('components_contracts.id_contract', $id_contract)
						->get();*/
        //Отображение карточки комплектации документа
		$contract = Contract::findOrFail($id_contract);
		$components = Component::select('*','components.id')
						->leftJoin('component_elements', 'component_elements.id', 'components.id_element_component')
						->leftJoin('component_element_parties','component_element_parties.id','components.id_party')
						->where('components.id_contract', $id_contract)
						->get();
		foreach($components as $component)
		{
			$proverka = ComponentsContract::select('components_contracts.id','contracts.number_contract')->leftJoin('contracts', 'contracts.id', 'components_contracts.id_contract')->where('id_component', $component->id)->get();
			$component->number_contract = [];
			$k = 0;
			foreach($proverka as $pr){
				$component->number_contract += [$k => $pr->number_contract];
				$k++;
			}
			if(count($proverka) > 0)
				$component->isChoseContract = true;
			else
				$component->isChoseContract = false;
		}
		$elements = ComponentElement::select('*')->orderBy('name_component', 'asc')->get();
		$old_elements =  ComponentElementParty::select('*','component_element_parties.id')
							->join('component_elements','component_elements.id','component_element_parties.id_element')
							->get();
		foreach($old_elements as $in_old_elements)
		{
			$party = Component::select('need_count')->where('id_party', $in_old_elements->id)->get();
			foreach($party as $component)
			{
				$in_old_elements->count_party -= $component->need_count;
			}
		}
		return view('department.ten.new_contract_component', [
															'contract'=>$contract,
															'components'=>$components,
															'elements'=>$elements,
															'old_elements'=>$old_elements
														]);
    }
	
	public function chose_all_component($id_document)
	{
        $components = Component::select('*', 'components.id')->join('component_elements', 'component_elements.id', 'components.id_element_component')->get();
		foreach($components as $component)
		{
			if(Component::select()->join('component_elements', 'component_elements.id', 'components.id_element_component')->where('id_component', $component->id)->where('id_document', $id_document)->count() > 0)
				$component->is_done = true;
			else
				$component->is_done = false;
		}
		return view('department.ten.chose_component', ['components'=>$components, 'id_document'=>$id_document]);
	}
	
	public function chose_component(Request $request, $id_document)
	{
		$document_component = new DocumentComponent([
						'id_document' => $id_document,
						'id_component' => $request['id_component']
						]);
		$document_component->save();
		return redirect()->back();
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //Отображение конкретной внешней комплектации 
		$pack = ComponentPack::findOrFail($id);
		$components = Component::select('*', 'components.id')->join('component_elements', 'component_elements.id', 'components.id_element_component')->where('id_pack', $pack->id)->get();
		$need_count_element = 0;
		foreach($components as $component)
			$need_count_element += $component->need_count;
		$element = ComponentElement::select('*')->where('id',$pack->id_element)->get()[0];
		$contracts = Contract::select(['contracts.id', 'contracts.id_counterpartie_contract', 'contracts.name_work_contract', 'contracts.number_contract','view_works.name_view_work', 
											'number_counterpartie_contract_reestr', 'reestr_contracts.amount_reestr','reestr_contracts.amount_contract_reestr',
											'reestr_contracts.date_maturity_date_reestr','reestr_contracts.date_maturity_reestr', 'name_view_contract'])
										->join('component_contracts','component_contracts.id_contract','contracts.id')
										->leftjoin('view_works','view_works.id','contracts.id_view_work_contract')
										->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
										->leftjoin('view_contracts','view_contracts.id','reestr_contracts.id_view_contract')
										->where('id_pack', $id)
										->orderBy('contracts.id', 'desc')
										->get();
		$counterparties = Counterpartie::select(['*','contr.id'])->join('curators','contr.curator','curators.id')->orderBy('name', 'asc')->get();
		foreach($contracts as $contract)
			foreach($counterparties as $counter)
				if($contract->id_counterpartie_contract == $counter->id)
				{
					$contract->name_counterpartie_contract = $counter->name;
					$contract->curator_contract = $counter->FIO;
				}
		$elements = ComponentElement::select('*')->orderBy('name_component', 'asc')->get();
		return view('department.ten.component_pack', ['pack'=>$pack, 'components'=>$components, 'need_count_element'=>$need_count_element, 'element'=>$element, 'contracts'=>$contracts, 'elements'=>$elements]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $component = Component::findOrFail($id);
		$component->fill([
					'id_element_component' => $request['element'],
					'need_count' => $request['need_count']
					]);
		$component->save();
		JournalController::store(Auth::User()->id,'Обновление комплектации из 10 отдела с id = ' . $component->id);
		return redirect()->back()->with('success','Компонент успешно обновлен!');
    }
	
	public function start_new_reconciliation($id)
	{
		$new_reconciliation = new ReconciliationUser([
						'id_application' => $id,
						'id_user' => 1,
						'is_document' => 1
		]);
		$new_reconciliation->save();
		return redirect()->back()->with('success','Согласование запущено!');
	}
	
	public function edit_small_component($id)
	{
		$component = Component::findOrFail($id);
		$elements = ComponentElement::select('*')->orderBy('name_component', 'asc')->get();
		return view('department.ten.edit_component', ['component'=>$component, 'elements'=>$elements]);
	}
	
	public function change_complete($id_pack)
	{
		try{
			if(isset($_GET['isProcess'])){
				$pack = ComponentPack::findOrFail($id_pack);
				if($_GET['isProcess'] == 'true')
					$pack->check_complete = 1;
				else
					$pack->check_complete = 0;
				$pack->save();
				return 'true';
			}
			else
				return 'false';
		}catch(Exception $e){
			return 'false';
		}
	}
	
	//Вывод все контрактов для прикрепления
	public function chose_all_contract($id_component)
	{
		$proverka = ComponentsContract::select('id')->where('id_component', $id_component)->get();
		if(!\Session::has('success'))
			if(count($proverka) > 0)
				return redirect()->back();
		$component = Component::select('*','components.id')->join('component_elements','component_elements.id','id_element_component')->where('components.id',$id_component)->get()[0];
		$document = Document::findOrFail($component->id_document);
        $contracts = Contract::select(['contracts.id', 'contracts.id_counterpartie_contract', 'contracts.name_work_contract', 'contracts.number_contract','view_works.name_view_work', 
											'number_counterpartie_contract_reestr', 'reestr_contracts.amount_reestr','reestr_contracts.amount_contract_reestr',
											'reestr_contracts.date_maturity_date_reestr','reestr_contracts.date_maturity_reestr'])
										->leftjoin('view_works','view_works.id','contracts.id_view_work_contract')
										->leftJoin('reestr_contracts', 'reestr_contracts.id_contract_reestr', 'contracts.id')
										->where('id_document_contract', $component->id_document)
										->orderBy('contracts.id', 'desc')
										->get();
		$counterparties = Counterpartie::select(['*','contr.id'])->join('curators','contr.curator','curators.id')->orderBy('name', 'asc')->get();
		foreach($contracts as $contract)
			foreach($counterparties as $counter)
				if($contract->id_counterpartie_contract == $counter->id)
				{
					$contract->name_counterpartie_contract = $counter->name;
					$contract->curator_contract = $counter->FIO;
				}
		return view('department.ten.chose_contract', ['contracts'=>$contracts, 'id_document'=>$component->id_document, 'component'=>$component]);
	}
	
	//Уже прикрепление компонента к контрактам
	public function chose_contract(Request $request, $id_component)
	{
		$component = Component::select('*','components.id')->join('component_elements','component_elements.id','id_element_component')->where('components.id',$id_component)->get()[0];
		foreach($request['id_contract'] as $key=>$value)
		{
			if($request['count_element'][$key] > 0){
				$component_contract = new ComponentsContract([
							'id_component' => $id_component,
							'id_contract' => $value,
							'count_components' => $request['count_element'][$key]
				]);
				$component_contract->save();
			}
		}
		JournalController::store(Auth::User()->id,'Прикрепление компонентов к контрактам из 10 отдела');
		return redirect()->back()->with('success','Успешно прикреплено!');
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $component = Component::select()->where('id', $id)->delete();
		return redirect()->back()->with('success','Компонент удален!');
    }
	
	
	public function destroy_pack($id_pack)
	{
		$pack = ComponentPack::select()->where('id', $id_pack)->delete();
		return redirect()->back()->with('success','Внешняя комплектация удалена!');
	}
	
	public function destroy_component_contract($id_component_contract)
	{
		$component = ComponentsContract::select()->where('id', $id_component_contract)->delete();
		return redirect()->back();
	}
}
