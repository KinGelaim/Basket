<?php

namespace App\Http\Controllers;

use App\Journal;
use App\Visit;
use App\Schoolchildren;
use App\JournalState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JournalController extends Controller
{
    public function journals($id_group)
	{
		$journals = Journal::select(['*'])->where('id_group', $id_group)->get();
		return view('administration.journal.main', ['journals'=>$journals, 'id_group'=>$id_group]);
	}
	
	public function create($id_group)
	{
		return view('administration.journal.register', ['id_group'=>$id_group]);
	}
	
	public function save(Request $request, $id_group)
	{
		$val = Validator::make($request->all(),[
			'name' => 'required'
		])->validate();
        $journal = Journal::create([
			'id_group' => $id_group,
			'name' => $request['name']
        ]);
		return redirect()->route('group.journal', $id_group)->with('success','Успешно добавлена!');
	}
	
	public function show($id_journal)
	{
		$journal = Journal::findOrFail($id_journal);
		$visits = Visit::select(['users.surname','users.name','users.patronymic','journal_states.name as state','date','users.id'])
						->join('users', 'users.id', 'visits.id_user')
						->leftjoin('journal_states', 'journal_states.id', 'visits.id_state')
						->where('id_journal', $id_journal)
						->get();
		$schoolchildrens = Schoolchildren::select(['users.surname','users.name','users.patronymic','users.id'])
											->join('users','users.id','schoolchildrens.id_user')
											->where('schoolchildrens.id_group', $journal->id_group)
											->get();
		$states = JournalState::select(['*'])->get();
		$result = [];
		foreach($visits as $visit)
		{
			if(in_array(date('d.m.Y', strtotime($visit->date)), array_keys($result)))
			{
				//array_push($result[date('d.m.Y', strtotime($visit->date))], [$visit->surname=>$visit->state]);
				$result[date('d.m.Y', strtotime($visit->date))] += [$visit->id=>$visit];
			}
			else
			{
				$result += [date('d.m.Y', strtotime($visit->date))=>[$visit->id=>$visit]];
			}
		}
		return view('administration.journal.show', ['journal'=>$journal, 'result'=>$result, 'schoolchildrens'=>$schoolchildrens, 'states'=>$states]);
	}
	
	public function add_visit(Request $request, $id_journal)
	{
		$val = Validator::make($request->all(),[
			'date' => 'required'
		])->validate();
		$newFormatDate = date('Y-m-d', strtotime($request['date']));
		foreach($request['state'] as $key=>$value)
		{
			$visit = Visit::create([
				'id_journal' => $id_journal,
				'id_user' => $key,
				'id_state' => $value,
				'date' => $newFormatDate
			]);
		}
		return redirect()->back()->with('success','Успешно добавлен день!');
	}
	
	public function delete($id_journal)
	{
		$journal = Journal::findOrFail($id_journal);
		$journal->delete();
		return redirect()->back()->with('success','Успешно удален!');
	}
}
