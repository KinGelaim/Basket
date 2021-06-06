<?php

namespace App\Http\Controllers;

use Auth;
use App\ReestrInvoice;
use Illuminate\Http\Request;

class ReestrInvoiceController extends Controller
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
    public function store($id_contract, Request $request)
    {
        $invoice = new ReestrInvoice();
		$invoice->fill($request->all());
		if($request['amount'])
			$invoice->amount = str_replace(' ','',$request['amount']);
		if($request['amount_vat'])
			$invoice->amount_vat = str_replace(' ','',$request['amount_vat']);
		$invoice->id_contract = $id_contract;
		$invoice->vat = $request['vat'] ? 1 : 0;
		$invoice->save();
		JournalController::store(Auth::User()->id,'Добавлен счет для контракта с id = ' . $id_contract);
		return redirect()->back()->with('success', 'Счет добавлен!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ReestrInvoice  $reestrInvoice
     * @return \Illuminate\Http\Response
     */
    public function show($id_contract)
    {
        $invoices = ReestrInvoice::select('*')->where('id_contract', $id_contract)->get();
		foreach($invoices as $invoice){
			if(is_numeric($invoice->amount))
				$invoice->amount = number_format($invoice->amount, 2, '.', ' ');
			if(is_numeric($invoice->amount_vat))
				$invoice->amount_vat = number_format($invoice->amount_vat, 2, '.', ' ');
		}
		return view('reestr.amount_invoices', ['id_contract'=>$id_contract, 'invoices'=>$invoices]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ReestrInvoice  $reestrInvoice
     * @return \Illuminate\Http\Response
     */
    public function edit(ReestrInvoice $reestrInvoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ReestrInvoice  $reestrInvoice
     * @return \Illuminate\Http\Response
     */
    public function update($id_invoice, Request $request)
    {
        $invoice = ReestrInvoice::findOrFail($id_invoice);
		$invoice->fill($request->all());
		if($request['amount'])
			$invoice->amount = str_replace(' ','',$request['amount']);
		if($request['amount_vat'])
			$invoice->amount_vat = str_replace(' ','',$request['amount_vat']);
		$invoice->vat = $request['vat'] ? 1 : 0;
		$invoice->save();
		JournalController::store(Auth::User()->id,'Обновлен счет для контракта с id = ' . $invoice->id_contract);
		return redirect()->back()->with('success', 'Счет обновлен!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ReestrInvoice  $reestrInvoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReestrInvoice $reestrInvoice)
    {
        //
    }
}
