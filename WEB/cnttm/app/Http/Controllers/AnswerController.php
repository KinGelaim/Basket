<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Test;
use App\Answer;
use App\Question;
use App\Schoolchildren;
use App\SchoolchildrenTest;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //---------Список учеников для проверки ответов
		$schoolchildrens = Schoolchildren::select(['*','schoolchildrens.id','users.name as name','groups.name as nameGroup','laboratories.name as nameLaba'])->join('users','users.id','schoolchildrens.id_user')->leftjoin('groups','groups.id','schoolchildrens.id_group')->leftjoin('laboratories','groups.id_laba','laboratories.id')->get();
        return view('administration.answer.main_answers', ['schoolchildrens'=>$schoolchildrens]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id_schoolchildren, $id_test)
    {
		//Сохранение ответов ученика
		//dd($request->all());
		$test = Test::select(['*'])->where('id', $id_test)->first();
		$questions = Question::select(['*','questions.id'])->join('views', 'views.id', 'questions.id_view')->where('id_test', $id_test)->where('part', $request['part'])->get();
		foreach($questions as $question){
			if($question->name != 'checkbox'){
				$answer = Answer::create([
					'id_user' => Auth::User()->id,
					'id_question' => $question->id,
					'answer' => $request['question' . $question->id]
				]);
			}else{
				$checkboxAnswer = "";
				if(isset($request['question' . $question->id])){
					foreach($request['question' . $question->id] as $answ){
						$checkboxAnswer .= $answ . ',';
					}
					$answer = Answer::create([
						'id_user' => Auth::User()->id,
						'id_question' => $question->id,
						'answer' => $checkboxAnswer
					]);
				}
			}
		}
		$schoolchildrenTest = SchoolchildrenTest::select(['*'])->where('id_test', $id_test)->where('id_schoolchildren', $id_schoolchildren)->first();
		switch($request['part']){
			case 1:
				$schoolchildrenTest->first_part = 0;
				break;
			case 2:
				$schoolchildrenTest->second_part = 0;
				break;
			case 3:
				$schoolchildrenTest->third_part = 0;
				break;
			case 4:
				$schoolchildrenTest->fourth_part = 0;
				break;
		}
		$schoolchildrenTest->save();
		return redirect()->route('test.complete', ['id_schoolchildren'=>$id_schoolchildren, 'id_test'=>$id_test])->with('success', 'Ответ отправлен на проверку!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_test, $id_schoolchildren)
    {
		
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_schoolchildren, $id_test)
    {
		//dd($request->all());
		$id_user = User::select(['users.id'])->join('schoolchildrens', 'schoolchildrens.id_user', 'users.id')->where('schoolchildrens.id', $id_schoolchildren)->first()->id;
		$test = Test::select(['*'])->where('id', $id_test)->first();
		$questions = Question::select(['*','questions.id'])->join('views', 'views.id', 'questions.id_view')->where('id_test', $id_test)->where('part', $request['part'])->get();
		foreach($questions as $question){
			$answer = Answer::select(['*'])->where('id_user', $id_user)->where('id_question', $question->id)->first();
			if($answer != null){
				$answer->check_question = $request['answer' . $answer->id];
				$answer->save();
			}
		}
		$schoolchildrenTest = SchoolchildrenTest::select(['*'])->where('id_test', $id_test)->where('id_schoolchildren', $id_schoolchildren)->first();
		switch($request['part']){
			case 1:
				$schoolchildrenTest->first_part = $request['complete_ball'];
				break;
			case 2:
				$schoolchildrenTest->second_part = $request['complete_ball'];
				break;
			case 3:
				$schoolchildrenTest->third_part = $request['complete_ball'];
				break;
			case 4:
				$schoolchildrenTest->fourth_part = $request['complete_ball'];
				break;
		}
		$schoolchildrenTest->save();
		return redirect()->route('test.complete_answer', ['id_schoolchildren'=>$id_schoolchildren,'id_test'=>$id_test])->with('success', 'Часть проверена!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
