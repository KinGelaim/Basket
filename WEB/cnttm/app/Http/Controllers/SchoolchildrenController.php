<?php

namespace App\Http\Controllers;

use App\User;
use App\Group;
use App\Schoolchildren;
use App\SchoolchildrenTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SchoolchildrenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$schoolchildrens = Schoolchildren::select(['*','schoolchildrens.id','users.name as name','groups.name as nameGroup','laboratories.name as nameLaba'])->join('users','users.id','schoolchildrens.id_user')->leftjoin('groups','groups.id','schoolchildrens.id_group')->leftjoin('laboratories','groups.id_laba','laboratories.id')->get();
        return view('administration.schoolchildren.main', ['schoolchildrens'=>$schoolchildrens]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Group::all();
		$users = User::select(['users.id','surname','users.name','patronymic'])->join('roles','roles.id','users.id_role')->where('roles.role','Ученик')->get();
        return view('administration.schoolchildren.register', ['groups'=>$groups, 'users'=>$users]);
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
			'id_user' => 'required',
			'id_group' => 'required'
		])->validate();
        $schoolchildren = Schoolchildren::create([
            'id_user' => $request['id_user'],
			'id_group' => $request['id_group']
        ]);
		return redirect()->route('schoolchildren.main')->with('success','Успешно добавлен!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
		
    }
	
	public function redirect($id_schoolchildren)
	{
		if(isset($_GET['id_test']))
		{
			$id_test = $_GET['id_test'];
			$schoolchildrenTest = SchoolchildrenTest::create([
				'id_schoolchildren' => $id_schoolchildren,
				'id_test' => $id_test
			]);
			return redirect()->back()->with('success', 'Успешно направлен!');
		}
		else
			return redirect()->back()->with('error', 'Ошибка при направлении!');
	}
	
	public function redirect_group($id_group)
	{
		if(isset($_GET['id_test']))
		{
			$id_test = $_GET['id_test'];
			$schoolchildrens = Schoolchildren::select(['*'])->where('id_group', $id_group)->get();
			foreach($schoolchildrens as $schoolchildren){
				$schoolchildrenTest = SchoolchildrenTest::create([
					'id_schoolchildren' => $schoolchildren->id,
					'id_test' => $id_test
				]);
			}
			return redirect()->back()->with('success', 'Успешно направлен!');
		}
		else
			return redirect()->back()->with('error', 'Ошибка при направлении!');
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		$schoolchildren = Schoolchildren::findOrFail($id);
		$groups = Group::all();
		$users = User::select(['users.id','surname','users.name','patronymic'])->join('roles','roles.id','users.id_role')->where('roles.role','Ученик')->get();
        return view('administration.schoolchildren.edit', ['schoolchildren'=>$schoolchildren, 'groups'=>$groups, 'users'=>$users]);
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
		$schoolchildren = Schoolchildren::findOrFail($id);
		$val = Validator::make($request->all(),[
			'id_user' => 'required',
			'id_group' => 'required'
		])->validate();
		$schoolchildren->fill([
			'id_user' => $request['id_user'],
			'id_group' => $request['id_group'],
			'is_complete' => $request['is_complete'] ? 1 : 0
		]);
		$schoolchildren->save();
		return redirect()->route('schoolchildren.main')->with('success','Успешно изменен!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$schoolchildren = Schoolchildren::findOrFail($id);
		$schoolchildren->delete();
		return redirect()->back()->with('success','Успешно удален!');
    }
}
