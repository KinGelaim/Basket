<?php

namespace App\Http\Controllers;

use App\Contract;
use App\Counterpartie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArchiveController extends Controller
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
		if (isset($_GET['search_name'])) {
			if ($_GET['search_name'] != 'counterpartie') {
				$search_name = $_GET['search_name'];
				$link .= "&search_name=" . $_GET['search_name'];
			}
		}
			
		if (isset($_GET['search_value'])) {
			$search_value = $_GET['search_value'];
			$link .= "&search_value=" . $_GET['search_value'];
			if($search_name == 'number_contract')
				$search_value = str_replace('-','‐',$search_value);
		} else
			$search_value = '';
		
		//Контрагенты
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		//$sip_counterparties = Counterpartie::select(['*'])->where('is_sip_counterpartie', 1)->orderBy('name', 'asc')->get();
		$counterpartie_str = "id_counterpartie_contract";
		$counterpartie_equal = ">";
		$counterpartie = '-1';
		$counerpartie_name = '';
		if (isset($_GET['search_name'])) {
			if (strlen($_GET['search_name']) > 0) {
				if ($_GET['search_name'] == 'counterpartie') {
					$counerpartie_name = $_GET['search_value'];
					foreach($counterparties as $counter){
						if(stripos($counter->name, $counerpartie_name) !== false) {
							$counterpartie = $counter->id;
							break;
						}
					}
					$counterpartie_str = "id_counterpartie_contract";
					$counterpartie_equal = "=";
					$link .= "&counterpartie=" . $_GET['search_value'];
				}
			}
		}
		//Контракты
		$paginate_count = 10;
		if (isset($_GET["page"])) {
			$page  = $_GET["page"];
		} else {
			$page=1;
		};
		$start = ($page-1) * $paginate_count;
	
		if(isset($_GET['search_value']) && isset($_GET['search_name']) && $search_name != '' && $search_value != '') {
			$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','id_goz_contract','id_view_work_contract', 'view_works.name_view_work',
													'all_count_contract','concluded_count_contract','amount_concluded_contract','formalization_count_contract',
													'amount_formalization_contract','big_deal_contract','amoun_implementation_contract','comment_implementation_contract',
													'prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract','date_contact','year_contract',
													'name_view_contract','amount_reestr','item_contract', 
													'app_outgoing_number_reestr', 'date_entry_into_force_reestr', 'renouncement_contract', 'executor_contract_reestr',
													DB::raw('CAST(number_pp as UNSIGNED) as cast_number_pp')])
									->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
									->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
									->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', '=', 'view_contracts.id')
									->where('contracts.id_counterpartie_contract','>','-1')
									->where('archive_contract', 1)
									->where($search_name, 'like', '%' . $search_value . '%')
									->offset($start)
									->limit($paginate_count)
									->orderBy('contracts.year_contract','asc')
									->orderBy('cast_number_pp','asc')
									->get();
			$contract_count = Contract::select()->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
												->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
												->where('contracts.id_counterpartie_contract','>',-1)
												->where('archive_contract', 1)
												->where($search_name, 'like', '%' . $search_value . '%')
												->count();
			//dd($contracts);
		}
		else if ($counterpartie_equal == '=') {
			$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','id_goz_contract','id_view_work_contract', 'view_works.name_view_work',
													'all_count_contract','concluded_count_contract','amount_concluded_contract','formalization_count_contract',
													'amount_formalization_contract','big_deal_contract','amoun_implementation_contract','comment_implementation_contract',
													'prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract','date_contact','year_contract',
													'name_view_contract','amount_reestr','item_contract', 
													'app_outgoing_number_reestr', 'date_entry_into_force_reestr', 'renouncement_contract', DB::raw('CAST(number_pp as UNSIGNED) as cast_number_pp')])
									->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
									->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
									->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', '=', 'view_contracts.id')
									->where('contracts.id_counterpartie_contract','>','-1')
									->where('archive_contract', 1)
									->where($counterpartie_str, $counterpartie_equal, $counterpartie)
									->offset($start)
									->limit($paginate_count)
									->orderBy('contracts.year_contract','asc')
									->orderBy('cast_number_pp','asc')
									->get();
			$contract_count = Contract::select()->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
												->where('contracts.id_counterpartie_contract','>',-1)
												->where('archive_contract', 1)
												->where($counterpartie_str, $counterpartie_equal, $counterpartie)
												->count();
			$search_name = 'counterpartie';
		}
		else {
			$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','id_goz_contract','id_view_work_contract', 'view_works.name_view_work',
													'all_count_contract','concluded_count_contract','amount_concluded_contract','formalization_count_contract',
													'amount_formalization_contract','big_deal_contract','amoun_implementation_contract','comment_implementation_contract',
													'prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract','date_contact','year_contract',
													'name_view_contract','amount_reestr','item_contract', 
													'app_outgoing_number_reestr', 'date_entry_into_force_reestr', 'renouncement_contract', DB::raw('CAST(number_pp as UNSIGNED) as cast_number_pp')])
									->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
									->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
									->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', '=', 'view_contracts.id')
									->where('contracts.id_counterpartie_contract','>','-1')
									->where('archive_contract', 1)
									->offset($start)
									->limit($paginate_count)
									->orderBy('contracts.year_contract','asc')
									->orderBy('cast_number_pp','asc')
									->get();
			$contract_count = Contract::select()->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
												->where('contracts.id_counterpartie_contract','>',-1)
												->where('archive_contract', 1)
												->count();
		}
		// Находим контрагента
		foreach($contracts as $contract)
			foreach($counterparties as $counter)
				if($contract->id_counterpartie_contract == $counter->id)
					$contract->name_counterpartie_contract = $counter->name;
		
		// Пагинация
		$prev_page = $page - 1 > 0 ? (int)($page-1) : '';
		$next_page = $page + 1 <= (int)ceil($contract_count/$paginate_count) ? (int)($page+1) : '';		
		
        return view('archive.main', [
										'contracts'=>$contracts,
										'search_name' => $search_name,
										'search_value' => $search_value,
										'count_paginate' => (int)ceil($contract_count/$paginate_count),
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
