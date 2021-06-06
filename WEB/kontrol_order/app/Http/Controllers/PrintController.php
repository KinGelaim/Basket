<?php

namespace App\Http\Controllers;

use App\Order;
use App\Notifycation;
use App\Postponement;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public static function print_report(Request $request)
	{
		if(isset($_GET['name_report']))
		{
			if(strlen($_GET['name_report']) > 0)
			{
				$equal_executor = '>';
				$value_executor = '0';
				if(isset($_GET['id_executor']))
				{
					if(strlen($_GET['id_executor']) > 0)
					{
						$equal_executor = '=';
						$value_executor = $_GET['id_executor'];
					}
				}
				switch($_GET['name_report'])
				{
					case 'no_complete':
						$result = PrintController::GetOrdersNoComplete($equal_executor, $value_executor);
						$query = '';
						foreach($request->all() as $key=>$value)
						{
							$query .= $key . '=' . $value . '&';
						}
						$query = substr($query,0,-1);
						return view('report.print', ['result'=>$result, 'title'=>'Поручения, НЕ ИСПОЛНЕННЫЕ по состоянию на:', 'query'=>$query]);
					case 'month':
						$result = PrintController::GetOrdersMonth($equal_executor, $value_executor);
						$query = '';
						foreach($request->all() as $key=>$value)
						{
							$query .= $key . '=' . $value . '&';
						}
						$query = substr($query,0,-1);
						return view('report.print', ['result'=>$result, 'title'=>'Поручения К ИСПОЛНЕНИЮ по состоянию на:', 'query'=>$query]);
					case 'complete':
						$result = PrintController::GetOrdersComplete($equal_executor, $value_executor);
						$query = '';
						foreach($request->all() as $key=>$value)
						{
							$query .= $key . '=' . $value . '&';
						}
						$query = substr($query,0,-1);
						return view('report.print', ['result'=>$result, 'title'=>'Поручения, ИСПОЛНЕННЫЕ в течение месяца, по состоянию на:', 'use'=>1, 'query'=>$query]);
				}
			}
		}
		return redirect()->back()->with('error', 'Ошибка! Обратитесь к системному администратору!');
	}
	
	public static function GetOrdersNoComplete($equal_executor, $value_executor)
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
					->leftjoin('postponements', 'postponements.id_order', 'orders.id')
					->where('archive', 0)
					->where('id_executor', $equal_executor, $value_executor)
					->where(function($date){
						$date->where('date_maturity', '<', date('Y-m-d', time()))
						->orWhere('date_postponement', '<', date('Y-m-d', time()));
					})
					->get()
					->unique();
		$result = [];
		foreach($orders as $order)
		{
			//Переносы
			$postponements = Postponement::select()->where('id_order', $order->id)->orderBy('id', 'desc')->get();
			$last_postponement = count($postponements) > 0 ? $postponements[0] : null;
			$order->last_postponement = $last_postponement;
			if($order->last_postponement ? ceil((strtotime($order->last_postponement['date_postponement'])-time())/60/60/24) > 0 : ceil((strtotime($order->date_maturity)-time())/60/60/24) > 0)
				continue;
			//Уведомления
			$notify = Notifycation::select()->where('id_order', $order->id)->get();
			foreach($notify as $not)
				$not->created_at_formating = date('d.m.Y', strtotime($not->created_at));
			$order->notifycations = $notify;
			//Форматирование
			$order->date_maturity_formating = date('d.m.Y', strtotime($order->date_maturity));
			$order->last_postponement_formating = $order->last_postponement ? date('d.m.Y', strtotime($order->last_postponement['date_postponement'])) : '';
			$order->proskor_formating = $order->last_postponement ? ceil((strtotime($order->last_postponement['date_postponement'])-time())/60/60/24) : ceil((strtotime($order->date_maturity)-time())/60/60/24);
			if(in_array($order->surname . ' ' . substr($order->name,0,2) . '.' . substr($order->patronymic,0,2) . '.', array_keys($result)))
			{
				array_push($result[$order->surname . ' ' . substr($order->name,0,2) . '.' . substr($order->patronymic,0,2) . '.'], $order);
			}
			else
			{
				$result += [$order->surname . ' ' . substr($order->name,0,2) . '.' . substr($order->patronymic,0,2) . '.' => [$order]];
			}
		}
		return $result;
	}
	
	public static function GetOrdersMonth($equal_executor, $value_executor)
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
					->leftjoin('postponements', 'postponements.id_order', 'orders.id')
					->where('archive', 0)
					->where('id_executor', $equal_executor, $value_executor)
					//->whereBetween('date_maturity', array(date('Y-m-d',time()), date('Y-m', time()).'-'.'31'))
					->where(function($date){
						$date->whereBetween('date_maturity', array(date('Y-m-d',time()), date('Y-m', time()).'-'.'31'))
						->orWhereBetween('date_postponement', array(date('Y-m-d',time()), date('Y-m', time()).'-'.'31'));
					})
					->get()
					->unique();
		$result = [];
		foreach($orders as $order)
		{
			//Переносы
			$postponements = Postponement::select()->where('id_order', $order->id)->orderBy('id', 'desc')->get();
			$last_postponement = count($postponements) > 0 ? $postponements[0] : null;
			$order->last_postponement = $last_postponement;
			//Уведомления
			$notify = Notifycation::select()->where('id_order', $order->id)->get();
			foreach($notify as $not)
				$not->created_at_formating = date('d.m.Y', strtotime($not->created_at));
			$order->notifycations = $notify;
			//Форматирование
			$order->date_maturity_formating = date('d.m.Y', strtotime($order->date_maturity));
			$order->last_postponement_formating = $order->last_postponement ? date('d.m.Y', strtotime($order->last_postponement['date_postponement'])) : '';
			$order->proskor_formating = $order->last_postponement ? ceil((strtotime($order->last_postponement['date_postponement'])-time())/60/60/24) : ceil((strtotime($order->date_maturity)-time())/60/60/24);
			if(in_array($order->surname . ' ' . substr($order->name,0,2) . '.' . substr($order->patronymic,0,2) . '.', array_keys($result)))
			{
				array_push($result[$order->surname . ' ' . substr($order->name,0,2) . '.' . substr($order->patronymic,0,2) . '.'], $order);
			}
			else
			{
				$result += [$order->surname . ' ' . substr($order->name,0,2) . '.' . substr($order->patronymic,0,2) . '.' => [$order]];
			}
		}
		return $result;
	}
	
	public static function GetOrdersComplete($equal_executor, $value_executor)
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
					->where('id_executor', $equal_executor, $value_executor)
					->whereBetween('date_complete_executor', array(date('Y-m-d',time()), date('Y-m', time()).'-'.'31'))
					->get();
		$result = [];
		foreach($orders as $order)
		{
			//Переносы
			$postponements = Postponement::select()->where('id_order', $order->id)->orderBy('id', 'desc')->get();
			$last_postponement = count($postponements) > 0 ? $postponements[0] : null;
			$order->last_postponement = $last_postponement;
			//Уведомления
			$notify = Notifycation::select()->where('id_order', $order->id)->get();
			foreach($notify as $not)
				$not->created_at_formating = date('d.m.Y', strtotime($not->created_at));
			$order->notifycations = $notify;
			//Форматирование
			$order->date_maturity_formating = date('d.m.Y', strtotime($order->date_maturity));
			$order->last_postponement_formating = $order->last_postponement ? date('d.m.Y', strtotime($order->last_postponement['date_postponement'])) : '';
			$order->proskor_formating = $order->last_postponement ? ceil((strtotime($order->last_postponement['date_postponement'])-time())/60/60/24) : ceil((strtotime($order->date_maturity)-time())/60/60/24);
			$order->date_complete_executor_formating = date('d.m.Y', strtotime($order->date_complete_executor));
			$order->character_formating = $order->date_complete_executor ? ($order->last_postponement ? ceil((strtotime($order->date_complete_executor)-strtotime($order->last_postponement['date_postponement']))/60/60/24) : ceil((strtotime($order->date_complete_executor)-strtotime($order->date_maturity))/60/60/24)) : '';
			if(in_array($order->surname . ' ' . substr($order->name,0,2) . '.' . substr($order->patronymic,0,2) . '.', array_keys($result)))
			{
				array_push($result[$order->surname . ' ' . substr($order->name,0,2) . '.' . substr($order->patronymic,0,2) . '.'], $order);
			}
			else
			{
				$result += [$order->surname . ' ' . substr($order->name,0,2) . '.' . substr($order->patronymic,0,2) . '.' => [$order]];
			}
		}
		return $result;
	}
}
