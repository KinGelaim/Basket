<?php

namespace App\Http\Controllers;

use App\User;
use App\Order;
use App\Postponement;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function index()
	{
		$link = '';
		$search_name = '';
		$search_value  = '';
        //Пагинация
		$paginate_count = 30;
		if (isset($_GET["page"])) {
			$page  = $_GET["page"];
		} else {
			$page=1;
		};
		$start = ($page-1) * $paginate_count;
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
								->where('archive', 1)
								->offset($start)
								->limit($paginate_count)
								->get();
		$order_count = Order::select(['orders.id'])
								->leftjoin('counterparties','orders.id_counterpartie','counterparties.id')
								->leftjoin('type_documents', 'orders.id_type_document', 'type_documents.id')
								->leftjoin('kontrol_periods', 'orders.id_period_kontrol', 'kontrol_periods.id')
								->leftjoin('users', 'orders.id_executor', 'users.id')
								->where('archive', 1)
								->count();
		$prev_page = $page - 1 > 0 ? (int)($page-1) : '';
		$next_page = $page + 1 <= (int)ceil($order_count/$paginate_count) ? (int)($page+1) : '';
		$executors = User::select(['id','users.surname','users.name','users.patronymic','users.position_department','users.telephone'])->orderBy('surname','asc')->get();
		foreach($orders as $order)
		{
			$postponements = Postponement::select()->where('id_order', $order->id)->orderBy('id', 'desc')->get();
			$last_postponement = count($postponements) > 0 ? $postponements[0] : null;
			$order->last_postponement = $last_postponement;
		}
		return view('archive.main', ['orders' => $orders,
									'executors' => $executors,
									'search_name' => $search_name,
									'search_value' => $search_value,
									'count_paginate' => (int)ceil($order_count/$paginate_count),
									'prev_page' => $prev_page,
									'next_page' => $next_page,
									'page' => $page,
									'link' => $link
								]);
	}
}
