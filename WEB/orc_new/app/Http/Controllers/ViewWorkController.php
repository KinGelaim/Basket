<?php

namespace App\Http\Controllers;

use App\ViewWork;
use Illuminate\Http\Request;

class ViewWorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $views = ViewWork::all();
        return view('administrator.view_work.main', ['views'=>$views]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administrator.view_work.view', ['view'=>'']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $view = new ViewWork();
		$view->name_view_work = $request['name_view_work'];
		$view->save();
		return redirect()->route('view_work.main');
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
		$view = ViewWork::findOrFail($id);
        return view('administrator.view_work.view', ['view'=>$view]);
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
        $view = ViewWork::findOrFail($id);
		$view->name_view_work = $request['name_view_work'];
		$view->save();
		return redirect()->route('view_work.main');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $view = ViewWork::findOrFail($id);
		$view->delete();
		return redirect()->back();
    }
}
