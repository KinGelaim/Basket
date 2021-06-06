<?php

namespace App\Http\Controllers;

use Auth;
use App\Order;
use App\User;
use App\Postponement;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
	{
		$orders = [];
		$executors = [];
		if(Auth::User())
		{
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
									->where('id_executor', Auth::User()->id)
									->get();
			$executors = User::select(['id','users.surname','users.name','users.patronymic','users.position_department','users.telephone'])->orderBy('surname','asc')->get();
			foreach($orders as $order)
			{
				$postponements = Postponement::select()->where('id_order', $order->id)->orderBy('id', 'desc')->get();
				$last_postponement = count($postponements) > 0 ? $postponements[0] : null;
				$order->last_postponement = $last_postponement;
			}
		}
		return view('home', [
								'orders' => $orders,
								'executors' => $executors
							]);
	}
}
