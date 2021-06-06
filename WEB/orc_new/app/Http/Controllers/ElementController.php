<?php

namespace App\Http\Controllers;

use Auth;
use App\Element;
use Illuminate\Http\Request;

class ElementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $elements = Element::select(['*'])->orderBy('elements.name_element')->get();
        return view('administrator.element.main', ['elements'=>$elements]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administrator.element.element', ['element'=>'']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $element = new Element();
		$element->name_element = $request['name_element'];
		$element->save();
		JournalController::store(Auth::User()->id,'Добавлено новое изделие для испытаний с id = ' . $element->id);
		return redirect()->route('element.main')->with('success','Успешно добавлен!');
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
		$element = Element::findOrFail($id);
        return view('administrator.element.element', ['element'=>$element]);
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
        $element = Element::findOrFail($id);
		$element->name_element = $request['name_element'];
		$element->save();
		JournalController::store(Auth::User()->id,'Обновлено изделие для испытаний с id = ' . $element->id);
		return redirect()->route('element.main')->with('success','Успешно изменен!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $element = Element::findOrFail($id);
		$element->delete();
		return redirect()->back()->with('success','Успешно удален!');
    }
}
