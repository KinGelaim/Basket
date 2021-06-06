<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$users = User::select(['users.id','users.surname','users.name','users.patronymic', 'users.position_department', 'users.telephone', 'roles.role','users.deleted_at'])->join('roles','users.id_role','roles.id')->withTrashed()->get();
        return view('administration.user.main', ['users'=>$users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$roles = DB::SELECT('SELECT id, role FROM roles');
        return view('administration.user.register', ['roles'=>$roles]);
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
			'surname' => 'required',
			'name' => 'required',
			'patronymic' => 'required',
			'role' => 'required',
			'login' => 'required|unique:users',
			'password' => 'required'
		])->validate();
        $user = User::create([
            'surname' => $request['surname'],
			'name' => $request['name'],
			'patronymic' => $request['patronymic'],
			'id_role' => $request['role'],
			'position_department' => $request['position_department'],
			'telephone' => $request['telephone'],
            'login' => $request['login'],
            'password' => bcrypt($request['password']),
        ]);
		return redirect()->route('user.main')->with('success','Успешно добавлен!');
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
		$user = User::findOrFail($id);
		$roles = DB::SELECT('SELECT id, role FROM roles');
        return view('administration.user.edit', ['user'=>$user, 'roles'=>$roles]);
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
		$user = User::findOrFail($id);
		if($request['change_password']){
			$val = Validator::make($request->all(),[
				'surname' => 'required',
				'name' => 'required',
				'patronymic' => 'required',
				'role' => 'required',
				'login' => 'required|unique:users,login,' . $id,
				'password' => 'required'
			])->validate();
			$user->fill([
				'surname' => $request['surname'],
				'name' => $request['name'],
				'patronymic' => $request['patronymic'],
				'id_role' => $request['role'],
				'position_department' => $request['position_department'],
				'telephone' => $request['telephone'],
				'login' => $request['login'],
				'password' => bcrypt($request['password'])
			]);
		}else{
			$val = Validator::make($request->all(),[
				'surname' => 'required',
				'name' => 'required',
				'patronymic' => 'required',
				'role' => 'required',
				'login' => 'required|unique:users,login,' . $id
			])->validate();
			$user->fill([
				'surname' => $request['surname'],
				'name' => $request['name'],
				'patronymic' => $request['patronymic'],
				'id_role' => $request['role'],
				'position_department' => $request['position_department'],
				'telephone' => $request['telephone'],
				'login' => $request['login']
			]);
		}
		$user->save();
		return redirect()->route('user.main')->with('success','Успешно изменен!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		if($id != 1)
		{
			$user = User::findOrFail($id);
			$user->delete();
			return redirect()->back()->with('success','Успешно удален!');
		}
		else
			return redirect()->back()->with('error','Нельзя удалить администратора!');
    }
}
