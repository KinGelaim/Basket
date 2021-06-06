<?php

namespace App\Http\Controllers;

use Auth;
use App\Order;
use App\Counterpartie;
use App\TypeDocument;
use App\User;
use App\KontrolPeriod;
use App\Resolution;
use App\Notifycation;
use App\Postponement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$search_name = '';
		$search_value  = '';
        $orders = Order::select([	
									'*',
									'orders.id',
									'counterparties.name as counterpartie',
									'type_documents.name as type_document',
									'kontrol_periods.name as period_kontrol',
									'orders.created_at'
								])
								->leftjoin('counterparties','orders.id_counterpartie','counterparties.id')
								->leftjoin('type_documents', 'orders.id_type_document', 'type_documents.id')
								->leftjoin('kontrol_periods', 'orders.id_period_kontrol', 'kontrol_periods.id')
								->leftjoin('users', 'orders.id_executor', 'users.id')
								->where('archive', 0)
								->get();
		$executors = User::select(['id','users.surname','users.name','users.patronymic','users.position_department','users.telephone'])->orderBy('surname','asc')->get();
		foreach($orders as $order)
		{
			$postponements = Postponement::select()->where('id_order', $order->id)->orderBy('id', 'desc')->get();
			$last_postponement = count($postponements) > 0 ? $postponements[0] : null;
			$order->last_postponement = $last_postponement;
		}
		return view('order.main', ['orders' => $orders,
									'executors' => $executors,
									'search_name' => $search_name,
									'search_value' => $search_value
								]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counterparties = Counterpartie::all();
		$type_documents = TypeDocument::all();
		$executors = User::select(['id','users.surname','users.name','users.patronymic','users.position_department','users.telephone'])->orderBy('surname','asc')->get();
		$kontrol_periods = KontrolPeriod::all();
		$last_number_document = Order::select()->max('number_document');
		return view('order.new_order', ['counterparties' => $counterparties,
										'type_documents' => $type_documents,
										'executors' => $executors,
										'kontrol_periods' => $kontrol_periods,
										'last_number_document' => $last_number_document
										]);
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
			'id_counterpartie' => 'required',
			'type_document' => 'required',
			'number_order' => 'required',
			'date_order' => 'required',
			'short_maintenance' => 'required',
			'maintenance_order' => 'required',
			'date_maturity' => 'required',
			'id_executor' => 'required'
		])->validate();
		$last_number_document = Order::select()->max('number_document');
		$order = new Order();
		$order->fill([
						'id_user' => Auth::User()->id,
						'number_document' => $last_number_document+1,
						'card_print_executor' => $request['card_print_executor'] ? 1 : 0,
						'id_counterpartie' => $request['id_counterpartie'],
						'id_type_document' => $request['type_document'],
						'punkt_order' => $request['punkt_order'],
						'number_order' => $request['number_order'],
						'date_order' => $request['date_order'],
						'id_period_kontrol' => $request['id_period_kontrol'],
						'short_maintenance' => $request['short_maintenance'],
						'maintenance_order' => $request['maintenance_order'],
						'date_maturity' => $request['date_maturity'] ? date('Y-m-d', strtotime($request['date_maturity'])) : null,
						'id_executor' => $request['id_executor'],
						'second_executor' => $request['second_executor'],
						'events' => $request['events'],
						'date_complete_executor' => $request['date_complete_executor'] ? date('Y-m-d', strtotime($request['date_complete_executor'])) : null,
						'archive' => $request['archive'] ? 1 : 0
		]);
		$all_dirty = JournalController::getMyChanges($order);
		$order->save();
		JournalController::store(Auth::User()->id,'Создание приказа с id = ' . $order->id . '~' . json_encode($all_dirty));
		if($request->file('new_file_resolution'))
			ResolutionController::store_resol_new_order($request, $order->id);
		return redirect()->route('orders.show_order', $order->id)->with(['success'=>'Приказ сохранен!','del_history'=>'1']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$order = Order::findOrFail($id);
        $counterparties = Counterpartie::all();
		$type_documents = TypeDocument::all();
		$executors = User::select(['id','users.surname','users.name','users.patronymic','users.position_department','users.telephone'])->orderBy('surname','asc')->get();
		foreach($executors as $executor)
		{
			if($executor->id == $order->id_executor)
			{
				$order->telephone_executor = $executor->telephone;
				$order->position_executor = $executor->position_department;
			}
		}
		$kontrol_periods = KontrolPeriod::all();
		$resolutions = Resolution::select()->where('id_order', $id)->orderBy('id', 'desc')->get();
		$notifycations = Notifycation::select()->where('id_order', $id)->orderBy('id','desc')->get();
		$last_notifycation = count($notifycations) > 0 ? $notifycations[0] : null;
		$postponements = Postponement::select()->where('id_order', $id)->orderBy('id', 'desc')->get();
		$last_postponement = count($postponements) > 0 ? $postponements[0] : null;
		return view('order.show_order', [
										'order' => $order,
										'counterparties' => $counterparties,
										'type_documents' => $type_documents,
										'executors' => $executors,
										'kontrol_periods' => $kontrol_periods,
										'resolutions' => $resolutions,
										'notifycations' => $notifycations,
										'last_notifycation' => $last_notifycation,
										'postponements' => $postponements,
										'last_postponement' => $last_postponement
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
		$order = Order::findOrFail($id);
		$val = Validator::make($request->all(),[
			'id_counterpartie' => 'required',
			'type_document' => 'required',
			'number_order' => 'required',
			'date_order' => 'required',
			'short_maintenance' => 'required',
			'maintenance_order' => 'required',
			'date_maturity' => 'required',
			'id_executor' => 'required'
		])->validate();
		//dd($request['date_complete_executor']);
		//dd(date('Y-m-d', strtotime($request['date_complete_executor'])));
		$order->fill([
						'card_print_executor' => $request['card_print_executor'] ? 1 : 0,
						'id_counterpartie' => $request['id_counterpartie'],
						'id_type_document' => $request['type_document'],
						'punkt_order' => $request['punkt_order'],
						'number_order' => $request['number_order'],
						'date_order' => $request['date_order'],
						'id_period_kontrol' => $request['id_period_kontrol'],
						'short_maintenance' => $request['short_maintenance'],
						'maintenance_order' => $request['maintenance_order'],
						'date_maturity' => $request['date_maturity'] ? date('Y-m-d', strtotime($request['date_maturity'])) : null,
						'id_executor' => $request['id_executor'],
						'second_executor' => $request['second_executor'],
						'events' => $request['events'],
						'date_complete_executor' => $request['date_complete_executor'] ? date('Y-m-d', strtotime($request['date_complete_executor'])) : null,
						'archive' => $request['archive'] ? 1 : 0
		]);
		$all_dirty = JournalController::getMyChanges($order);
		$order->save();
		JournalController::store(Auth::User()->id,'Изменение приказа с id = ' . $order->id . '~' . json_encode($all_dirty));
		return redirect()->route('orders.show_order', $order->id)->with(['success'=>'Приказ изменен!','del_history'=>'1']);
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
