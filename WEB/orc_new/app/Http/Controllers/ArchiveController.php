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
		$contracts = Contract::select(['contracts.id','id_counterpartie_contract','number_contract','name_work_contract','id_goz_contract','id_view_work_contract', 'view_works.name_view_work',
												'all_count_contract','concluded_count_contract','amount_concluded_contract','formalization_count_contract',
												'amount_formalization_contract','big_deal_contract','amoun_implementation_contract','comment_implementation_contract',
												'prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract','date_contact','year_contract',
												'name_view_contract','amount_reestr','item_contract', 
												'app_outgoing_number_reestr', 'date_entry_into_force_reestr', 'renouncement_contract', DB::raw('CAST(number_pp as UNSIGNED) as cast_number_pp')])
								->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
								->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
								->leftJoin('view_contracts', 'reestr_contracts.id_view_contract', '=', 'view_contracts.id')
								/*->where('contracts.number_contract','!=', null)*/
								//->where('is_sip_contract', 0)
								->where('contracts.id_counterpartie_contract','>','-1')
								//->where($year_str, $year_equal, $year)
								//->where($view_department_str, $view_department_equal, $view_department)
								//->where($counterpartie_str, $counterpartie_equal, $counterpartie)
								//->where('contracts.number_contract', 'like', $sql_like)
								->where('archive_contract', 1)
								//->orderBy($sort, $sort_p)
								//->offset($start)
								//->limit($paginate_count)
								->orderBy('contracts.year_contract','asc')
								->orderBy('cast_number_pp','asc')
								->get();
		$contract_count = Contract::select()->leftJoin('view_works', 'contracts.id_view_work_contract', '=', 'view_works.id')
											->where('contracts.id_counterpartie_contract','>',-1)
											//->where($year_str, $year_equal, $year)
											//->where($view_department_str, $view_department_equal, $view_department)
											//->where($counterpartie_str, $counterpartie_equal, $counterpartie)
											//->where('is_sip_contract', 0)
											//->where('contracts.number_contract', 'like', $sql_like)
											->where('archive_contract', 1)
											->count();
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		foreach($contracts as $contract)
			foreach($counterparties as $counter)
				if($contract->id_counterpartie_contract == $counter->id)
					$contract->name_counterpartie_contract = $counter->name;
        return view('archive.main', ['contracts'=>$contracts]);
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
