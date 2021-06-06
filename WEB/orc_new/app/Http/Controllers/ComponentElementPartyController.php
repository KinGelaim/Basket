<?php

namespace App\Http\Controllers;

use Auth;
use App\ComponentElementParty;
use App\Counterpartie;
use App\ComponentElement;
use App\Component;
use Illuminate\Http\Request;

class ComponentElementPartyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $components = ComponentElementParty::select('*','component_element_parties.id')
						->join('component_elements','component_elements.id','component_element_parties.id_element')
						->get();
		foreach($components as $component)
		{
			$component->new_count_party = $component->count_party;
			$party = Component::select('need_count')->where('id_party', $component->id)->get();
			foreach($party as $part)
			{
				$component->new_count_party -= $part->need_count;
			}
		}
        return view('administrator.component_element_party.main', ['components'=>$components]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $elements = ComponentElement::select(['*'])->orderBy('name_component')->get();
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		foreach($elements as $element)
			foreach($counterparties as $contr)
				if($element->id_counterpartie == $contr->id)
					$element->name_counterpartie = $contr->name;
		return view('administrator.component_element_party.element', ['element'=>'', 'elements'=>$elements, 'counterparties'=>$counterparties]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $element = new ComponentElementParty();
		$element->id_element = $request['name_component'];
		$element->name_party = $request['name_party'];
		$element->date_party = $request['date_party'];
		$element->count_party = $request['count_party'];
		$element->save();
		JournalController::store(Auth::User()->id,'Добавил новый КЭ на склад с id = ' . $element->id);
		return redirect()->back()->with('success','Успешно добавлен!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ComponentElementParty  $componentElementParty
     * @return \Illuminate\Http\Response
     */
    public function show(ComponentElementParty $componentElementParty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ComponentElementParty  $componentElementParty
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		$element = ComponentElementParty::findOrFail($id);
        $elements = ComponentElement::select(['*'])->orderBy('name_component')->get();
		$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
		foreach($elements as $in_element)
			foreach($counterparties as $contr)
				if($in_element->id_counterpartie == $contr->id)
					$in_element->name_counterpartie = $contr->name;
		return view('administrator.component_element_party.element', ['element'=>$element, 'elements'=>$elements, 'counterparties'=>$counterparties]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ComponentElementParty  $componentElementParty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $element = ComponentElementParty::findOrFail($id);
		$element->id_element = $request['name_component'];
		$element->name_party = $request['name_party'];
		$element->date_party = $request['date_party'];
		$element->count_party = $request['count_party'];
		$element->save();
		JournalController::store(Auth::User()->id,'Изменил КЭ на складе с id = ' . $element->id);
		return redirect()->back()->with('success','Успешно изменен!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ComponentElementParty  $componentElementParty
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $element = ComponentElementParty::findOrFail($id);
		JournalController::store(Auth::User()->id,'Удаление КЭ со склада с id = ' . $element->id);
		$element->delete();
		return redirect()->back()->with('success','Успешно удален!');
    }
}
