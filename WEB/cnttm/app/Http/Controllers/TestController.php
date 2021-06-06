<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Test;
use App\Group;
use App\Answer;
use App\Question;
use App\Schoolchildren;
use App\SchoolchildrenTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$tests = Test::select(['*'])->get();
        return view('administration.test.main', ['tests'=>$tests]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administration.test.register');
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
			'name' => 'required',
			'name_first_part' => 'required',
			'name_second_part' => 'required',
			'name_third_part' => 'required',
			'name_fourth_part' => 'required'
		])->validate();
        $test = Test::create([
			'name' => $request['name'],
			'name_first_part' => $request['name_first_part'],
			'name_second_part' => $request['name_second_part'],
            'name_third_part' => $request['name_third_part'],
            'name_fourth_part' => $request['name_fourth_part']
        ]);
		return redirect()->route('test.main')->with('success','Успешно добавлен!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		//Отображение вопросов теста
		$test = Test::findOrFail($id);
		$questions = Question::select(['*','questions.id'])->join('views','questions.id_view','views.id')->where('id_test', $id)->orderBy('part','asc')->orderBy('position','asc')->get();
        return view('administration.test.show', ['test'=>$test, 'questions'=>$questions]);
    }
	
	public function all_show_test($id)
	{
		$test = Test::findOrFail($id);
		$questions = Question::select(['*','questions.id'])->join('views','questions.id_view','views.id')->where('id_test', $id)->orderBy('part','asc')->orderBy('position','asc')->get();
        return view('administration.test.show_all_test', ['test'=>$test, 'questions'=>$questions]);
	}
	
	public function redirect_schoolchildren($id_test)
	{
		$schoolchildrens = Schoolchildren::select(['*','schoolchildrens.id','users.name as name','groups.name as nameGroup','laboratories.name as nameLaba'])->join('users','users.id','schoolchildrens.id_user')->leftjoin('groups','groups.id','schoolchildrens.id_group')->leftjoin('laboratories','groups.id_laba','laboratories.id')->get();
        foreach($schoolchildrens as $school)
		{
			$schoolchildrenTest = SchoolchildrenTest::select(['*'])->where('id_schoolchildren', $school->id)->where('id_test', $id_test)->first();
			if($schoolchildrenTest != null)
				$school->enabled = 'disabled';
		}
		return view('administration.test.redirect_schoolchildren', ['id_test'=>$id_test, 'schoolchildrens'=>$schoolchildrens]);
	}
	
	public function redirect_group($id_test)
	{
		$groups = Group::all();
		return view('administration.test.redirect_group', ['id_test'=>$id_test, 'groups'=>$groups]);
	}
	
	public function complete($id_schoolchildren, $id_test)
	{
		//Отображение теста для прорешивания
		$test = Test::select(['*'])->where('id', $id_test)->first();
		$questions = Question::select(['*','questions.id'])->join('views','views.id','questions.id_view')->where('id_test', $id_test)->orderBy('position','asc')->get();
		$schoolchildrenTest = SchoolchildrenTest::select(['*'])->where('id_test', $id_test)->where('id_schoolchildren', $id_schoolchildren)->first();
		return view('complete_test', ['test'=>$test, 'questions'=>$questions, 'schoolchildrenTest'=>$schoolchildrenTest]);
	}
	
    public function show_test_schoolchildren($id_schoolchildren)
    {
        //---------Список тестов для конкретного ученика для преподавателя---------
		$schoolchildren = Schoolchildren::select(['schoolchildrens.id','schoolchildrens.id_user','users.surname','users.name','users.patronymic'])->join('users','users.id','schoolchildrens.id_user')->where('schoolchildrens.id', $id_schoolchildren)->first();
		$schoolchildrenTest = SchoolchildrenTest::select(['*'])->join('tests','tests.id','schoolchildren_tests.id_test')->where('id_schoolchildren', $id_schoolchildren)->get();
		return view('administration.answer.tests', ['schoolchildren'=>$schoolchildren, 'schoolchildrenTest'=>$schoolchildrenTest]);
    }
	
	public function complete_answer($id_schoolchildren, $id_test)
	{
		//Для проверки преподавателями
		$id_user = User::select(['users.id'])->join('schoolchildrens', 'schoolchildrens.id_user', 'users.id')->where('schoolchildrens.id', $id_schoolchildren)->first()->id;
		$test = Test::select(['*'])->where('id', $id_test)->first();
		$questions = Question::select(['*','questions.id'])->join('views','views.id','questions.id_view')->where('id_test', $id_test)->orderBy('position','asc')->get();
		foreach($questions as $quest){
			$answers = Answer::select(['*','answers.id as idAnswer'])->where('id_user', $id_user)->where('id_question', $quest->id)->first();
			$quest->answers = $answers;
		}
		$schoolchildrenTest = SchoolchildrenTest::select(['*'])->where('id_test', $id_test)->where('id_schoolchildren', $id_schoolchildren)->first();
		return view('complete_answer_test', ['test'=>$test, 'questions'=>$questions, 'id_user'=>$id_user, 'id_schoolchildren'=>$id_schoolchildren, 'schoolchildrenTest'=>$schoolchildrenTest]);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		$test = Test::findOrFail($id);
        return view('administration.test.edit', ['test'=>$test]);
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
		$test = Test::findOrFail($id);
		$val = Validator::make($request->all(),[
			'name' => 'required',
			'name_first_part' => 'required',
			'name_second_part' => 'required',
			'name_third_part' => 'required',
			'name_fourth_part' => 'required'
		])->validate();
		$test->fill([
			'name' => $request['name'],
			'name_first_part' => $request['name_first_part'],
			'name_second_part' => $request['name_second_part'],
			'name_third_part' => $request['name_third_part'],
			'name_fourth_part' => $request['name_fourth_part']
		]);
		$test->save();
		return redirect()->route('test.main')->with('success','Успешно изменен!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$test = Test::findOrFail($id);
		$test->delete();
		return redirect()->back()->with('success','Успешно удален!');
    }
}
