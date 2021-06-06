<?php

namespace App\Http\Controllers;

use App\Counterpartie;
use App\TypeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypeDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $type_documents = TypeDocument::select(['*','type_documents.id', 'type_documents.name', 'counterparties.name as counterpartie'])->leftjoin('counterparties', 'type_documents.id_counterpartie', 'counterparties.id')->get();
		return view('administration.type_document.main', ['type_documents'=>$type_documents]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$counterparties = Counterpartie::all();
        return view('administration.type_document.register', ['counterparties'=>$counterparties]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$val = Validator::make($request->all(),[
			'name' => 'required'
		])->validate();
        $type_document = TypeDocument::create([
            'name' => $request['name'],
			'id_counterpartie' => $request['id_counterpartie']
        ]);
		return redirect()->route('type_document.main')->with('success','Успешно добавлен!');
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
		$type_document = TypeDocument::findOrFail($id);
		$counterparties = Counterpartie::all();
        return view('administration.type_document.edit', ['type_document'=>$type_document, 'counterparties'=>$counterparties]);
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
		$type_document = TypeDocument::findOrFail($id);
		$val = Validator::make($request->all(),[
			'name' => 'required'
		])->validate();
		$type_document->fill([
			'name' => $request['name'],
			'id_counterpartie' => $request['id_counterpartie']
		]);
		$type_document->save();
		return redirect()->route('type_document.main')->with('success','Успешно изменен!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$type_document = TypeDocument::findOrFail($id);
		$type_document->delete();
		return redirect()->back()->with('success','Успешно удален!');
    }
}
