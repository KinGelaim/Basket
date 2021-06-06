<?php

namespace App\Http\Controllers;

use App\Test;
use App\Question;
use App\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
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
    public function create($id_test)
    {
		$views = View::all();
        return view('administration.question.register', ['id_test'=>$id_test, 'views'=>$views]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id_test)
    {
		$val = Validator::make($request->all(),[
			'part' => 'required',
			'position' => 'required|numeric',
			'question' => 'required',
			'id_view' => 'required'
		])->validate();
        $quest = Question::create([
            'id_test' => $id_test,
			'part' => $request['part'],
			'position' => $request['position'],
			'question' => $request['question'],
            'description' => $request['description'],
            'id_view' => $request['id_view'],
            'first_answer' => $request['first_answer'],
            'second_answer' => $request['second_answer'],
            'third_answer' => $request['third_answer'],
            'fourth_answer' => $request['fourth_answer'],
			'finally_answer' => $request['finally_answer']
        ]);
		return redirect()->route('test.show', $id_test)->with('success','Успешно добавлен!');
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
		$question = Question::findOrFail($id);
		$views = View::all();
        return view('administration.question.edit', ['question'=>$question, 'views'=>$views]);
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
		$question = Question::findOrFail($id);
		$val = Validator::make($request->all(),[
			'part' => 'required',
			'position' => 'required|numeric',
			'question' => 'required',
			'id_view' => 'required'
		])->validate();
		$question->fill([
			'part' => $request['part'],
			'position' => $request['position'],
			'question' => $request['question'],
            'description' => $request['description'],
            'id_view' => $request['id_view'],
            'first_answer' => $request['first_answer'],
            'second_answer' => $request['second_answer'],
            'third_answer' => $request['third_answer'],
            'fourth_answer' => $request['fourth_answer'],
			'finally_answer' => $request['finally_answer']
		]);
		$question->save();
		return redirect()->route('test.show', $question->id_test)->with('success','Успешно изменен!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$question = Question::findOrFail($id);
		$question->delete();
		return redirect()->back()->with('success','Успешно удален!');
    }
}
