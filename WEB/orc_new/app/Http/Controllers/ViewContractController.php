<?php

namespace App\Http\Controllers;

use Auth;
use App\ViewContract;
use Illuminate\Http\Request;

class ViewContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $views = ViewContract::all();
        return view('administrator.view_contract.main', ['views'=>$views]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administrator.view_contract.view', ['view'=>'']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $view = new ViewContract();
		$view->name_view_contract = $request['name_view_contract'];
		$view->save();
		JournalController::store(Auth::User()->id,'Создание нового вида договора с id = ' . $view->id);
		return redirect()->route('view_contract.main');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ViewContract  $viewContract
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ViewContract  $viewContract
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		$view = ViewContract::findOrFail($id);
        return view('administrator.view_contract.view', ['view'=>$view]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ViewContract  $viewContract
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $view = ViewContract::findOrFail($id);
		$view->name_view_contract = $request['name_view_contract'];
		$view->save();
		JournalController::store(Auth::User()->id,'Редактирование вида договора с id = ' . $view->id);
		return redirect()->route('view_contract.main');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ViewContract  $viewContract
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $view = ViewContract::findOrFail($id);
		$view->delete();
		return redirect()->back();
    }
}
