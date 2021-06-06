<?php

namespace App\Http\Controllers;

use Auth;
use App\Counterpartie;
use App\ComponentElement;
use Illuminate\Http\Request;

class ComponentElementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $elements = ComponentElement::select(['*'])->orderBy('name_component')->get();
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		foreach($elements as $element)
			foreach($counterparties as $contr)
				if($element->id_counterpartie == $contr->id)
					$element->name_counterpartie = $contr->name;
        return view('administrator.component_element.main', ['elements'=>$elements]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
        return view('administrator.component_element.element', ['element'=>'', 'counterparties'=>$counterparties]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $element = new ComponentElement();
		$element->id_counterpartie = $request['id_counterpartie'];
		$element->name_component = $request['name_component'];
		$element->count_element = $request['count_element'];
		$element->need_count_element = $request['need_count_element'];
		$element->save();
		JournalController::store(Auth::User()->id,'Добавил новый КЭ с id = ' . $element->id);
		return redirect()->back()->with('success','Успешно добавлен!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ComponentElement  $componentElement
     * @return \Illuminate\Http\Response
     */
    public function show(ComponentElement $componentElement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ComponentElement  $componentElement
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $element = ComponentElement::findOrFail($id);
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
        return view('administrator.component_element.element', ['element'=>$element, 'counterparties'=>$counterparties]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ComponentElement  $componentElement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		$element = ComponentElement::findOrFail($id);
		$element->id_counterpartie = $request['id_counterpartie'];
		$element->name_component = $request['name_component'];
		$element->count_element = $request['count_element'];
		$element->need_count_element = $request['need_count_element'];
		$element->save();
		JournalController::store(Auth::User()->id,'Изменил КЭ с id = ' . $element->id);
		return redirect()->back()->with('success','Успешно изменено!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ComponentElement  $componentElement
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $element = ComponentElement::findOrFail($id);
		JournalController::store(Auth::User()->id,'Удалил КЭ с id = ' . $element->id);
		$element->delete();
		return redirect()->back()->with('success','Успешно удален!');
    }
}
