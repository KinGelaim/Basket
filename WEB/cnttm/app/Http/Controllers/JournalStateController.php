<?php

namespace App\Http\Controllers;

use App\JournalState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JournalStateController extends Controller
{
    public function index()
	{
		$states = JournalState::select(['*'])->get();
		return view('administration.journal_state.main', ['states'=>$states]);
	}
	
	public function create()
	{
		return view('administration.journal_state.register');
	}
	
	public function save(Request $request)
	{
		$val = Validator::make($request->all(),[
			'name' => 'required'
		])->validate();
        $journal_state = JournalState::create([
			'name' => $request['name']
        ]);
		return redirect()->route('journal_state.main')->with('success','Успешно добавлена!');
	}
	
	public function delete($id_journal_state)
	{
		$journal_state = JournalState::findOrFail($id_journal_state);
		$journal_state->delete();
		return redirect()->back()->with('success','Успешно удален!');
	}
}
