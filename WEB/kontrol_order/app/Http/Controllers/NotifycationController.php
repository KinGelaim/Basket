<?php

namespace App\Http\Controllers;

use App\Order;
use App\Notifycation;
use App\Postponement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotifycationController extends Controller
{
    public function print_notify(Request $request)
	{
		if(isset($_GET['name_table']))
		{
			switch($_GET['name_table'])
			{
				case 'result':
					$orders = NotifycationController::CreateNotifycationResult();
					$query = '';
					foreach($request->all() as $key=>$value)
					{
						$query .= $key . '=' . $value . '&';
					}
					$query = substr($query,0,-1);
					return view('notify.print', ['orders'=>$orders, 'query'=>$query]);
				case 'executor':
					if(isset($_GET['id_executor']))
					{
						$orders = NotifycationController::CreateNotifycationExecutor();
						$query = '';
						foreach($request->all() as $key=>$value)
						{
							$query .= $key . '=' . $value . '&';
						}
						$query = substr($query,0,-1);
						return view('notify.print', ['orders'=>$orders, 'query'=>$query]);
					}
					break;
				case 'number_document':
					if(isset($_GET['number_document']))
					{
						$orders = NotifycationController::CreateNotifycationNumberDocument();
						$query = '';
						foreach($request->all() as $key=>$value)
						{
							$query .= $key . '=' . $value . '&';
						}
						$query = substr($query,0,-1);
						return view('notify.print', ['orders'=>$orders, 'query'=>$query]);
					}
					break;
				case 'month':
					if(isset($_GET['month']))
					{
						$orders = NotifycationController::CreateNotifycationMonth();
						$query = '';
						foreach($request->all() as $key=>$value)
						{
							$query .= $key . '=' . $value . '&';
						}
						$query = substr($query,0,-1);
						return view('notify.print', ['orders'=>$orders, 'query'=>$query]);
					}
					break;
				case 'today':
					$orders = NotifycationController::CreateNotifycationToday();
					$query = '';
					foreach($request->all() as $key=>$value)
					{
						$query .= $key . '=' . $value . '&';
					}
					$query = substr($query,0,-1);
					return view('notify.print', ['orders'=>$orders, 'query'=>$query]);
				case 'period':
					if(isset($_GET['beginPeriod']) && isset($_GET['endPeriod']))
					{
						$orders = NotifycationController::CreateNotifycationPeriod();
						$query = '';
						foreach($request->all() as $key=>$value)
						{
							$query .= $key . '=' . $value . '&';
						}
						$query = substr($query,0,-1);
						return view('notify.print', ['orders'=>$orders, 'query'=>$query]);
					}
					break;
			}
		}
		return redirect()->back()->with('error', 'Ошибка! Обратитесь к системному администратору!');
	}
	
	private static function GetNotifyAndPosponement($orders)
	{
		foreach($orders as $order)
		{
			//Переносы
			$postponements = Postponement::select()->where('id_order', $order->id)->orderBy('id', 'desc')->get();
			$last_postponement = count($postponements) > 0 ? $postponements[0] : null;
			$order->last_postponement = $last_postponement;
			//Уведомления
			$notifycation_list = '';
			$notifycations = Notifycation::select(['*'])->where('id_order', $order->id)->get();
			$proverka = true;
			foreach($notifycations as $notifycation)
			{
				if(date('d.m.Y', strtotime($notifycation->created_at)) == date('d.m.Y', time()))
				{
					$proverka = false;
				}
				else
					$notifycation_list .= date('d.m.Y', strtotime($notifycation->created_at)) . ' ';
			}
			if($proverka)
			{
				$notify = Notifycation::create([
					'id_order' => $order->id
				]);
				JournalController::store(Auth::User()->id,'Создание уведомления для приказа с id = ' . $order->id);
			}
			$order->notifycation_list = $notifycation_list;
			
			//Преобразования форматов
			$order->date_maturity_format = date('d.m.Y', strtotime($order->date_maturity));
			$order->last_postponement_format = $order->last_postponement ? date('d.m.Y', strtotime($order->last_postponement['date_postponement'])) : '';
			$order->out_days_format = $order->last_postponement ? ceil((strtotime($order->last_postponement['date_postponement'])-time())/60/60/24) : ceil((strtotime($order->date_maturity)-time())/60/60/24);
		}
		return $orders;
	}
	
	public static function CreateNotifycationResult()
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
					->get();
		$orders = NotifycationController::GetNotifyAndPosponement($orders);
		return $orders;
	}
	
	public static function CreateNotifycationExecutor()
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
					->where('id_executor', $_GET['id_executor'])
					->get();
		$orders = NotifycationController::GetNotifyAndPosponement($orders);
		return $orders;
	}
	
	public static function CreateNotifycationNumberDocument()
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
					->where('number_document', $_GET['number_document'])
					->get();
		$orders = NotifycationController::GetNotifyAndPosponement($orders);
		return $orders;
	}
	
	public static function CreateNotifycationMonth()
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
					//->where('date_maturity_timeformat', '>=', DATE('Y-m-d', strtotime('01.'.$_GET['month'].'.'.date('Y', time()))))
					->whereBetween('date_maturity', array(date('Y', time()).'-'.$_GET['month'].'-'.'01',date('Y', time()).'-'.$_GET['month'].'-'.'31'))
					->orWhereBetween('date_postponement', array(date('Y', time()).'-'.$_GET['month'].'-'.'01',date('Y', time()).'-'.$_GET['month'].'-'.'31'))
					->get()
					->unique();
		$orders = NotifycationController::GetNotifyAndPosponement($orders);
		return $orders;
	}
	
	public static function CreateNotifycationToday()
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
					->where('date_maturity', date('Y-m-d', time()+259200))
					->orWhere('date_postponement', date('Y-m-d', time()+259200))
					->get()
					->unique();
		$orders = NotifycationController::GetNotifyAndPosponement($orders);
		return $orders;
	}
	
	public static function CreateNotifycationPeriod()
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
					->whereBetween('date_maturity', array(date('Y-m-d',strtotime($_GET['beginPeriod'])), date('Y-m-d',strtotime($_GET['endPeriod']))))
					->orWhereBetween('date_postponement', array(date('Y-m-d',strtotime($_GET['beginPeriod'])), date('Y-m-d',strtotime($_GET['endPeriod']))))
					->get()
					->unique();
		$orders = NotifycationController::GetNotifyAndPosponement($orders);
		return $orders;
	}
}
