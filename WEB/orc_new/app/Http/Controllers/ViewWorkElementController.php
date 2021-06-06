<?php

namespace App\Http\Controllers;

use Auth;
use App\ViewWorkElement;
use Illuminate\Http\Request;

class ViewWorkElementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $views = ViewWorkElement::select()->orderBy('name_view_work_elements')->get();
        return view('administrator.view_work_element.main', ['views'=>$views]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administrator.view_work_element.view', ['view'=>'']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $view = new ViewWorkElement();
		$view->name_view_work_elements = $request['name_view_work_elements'];
		$view->save();
		JournalController::store(Auth::User()->id,'Добавлен новый вид испытания с id = ' . $view->id);
		return redirect()->route('view_work.element.main');
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
		$view = ViewWorkElement::findOrFail($id);
        return view('administrator.view_work_element.view', ['view'=>$view]);
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
        $view = ViewWorkElement::findOrFail($id);
		$view->name_view_work_elements = $request['name_view_work_elements'];
		$view->update();
		JournalController::store(Auth::User()->id,'Обновлен вид испытания с id = ' . $view->id);
		return redirect()->route('view_work.element.main');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $view = ViewWorkElement::findOrFail($id);
		$view->delete();
		return redirect()->back();
    }
}
