<?php

namespace App\Http\Controllers;

use Auth;
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
		//$logins = ['Смирнов Н.П.','Ахметзанов А.Х.','Задорин Л.А.','Кочуров А.И.','Крапко И.А.','Малинина Г.А.','Марковкин В.В.','Руденко С.В.','Щадилов С.М.','Фалалеев В.И.','Вепрев П.А.','Гуринова Н.М.','Мезенцева Г.А.','Филатова В.А.','Роженок А.А.','Колобов А.С.','Медведев В.Л.','Власов Е.Ю.','Арефьев А.В.','Воронюк В.И.','Свечков А.Л.','Рассказова Т.В.','Семячкова М.А.','Зыкова Т.Н.','Копылова Е.А.','Грязнова А.И.','Бордовская О.Ф.','Богданова Т.А.','Коленченко Д.В.','Талащенко Е.А.','Матвеева Е.А.','Роженок Н.М.','Рыжкова Е.В.','Логунов Д.В.','Путилов И.С.','Свиридова Е.В.'];
		//$passwords = ['snp159','aah157','zla567','edc159','kia842','we456r','qw456e','es789z','ed852c','fvi789','yg741v','es789z','zx741c','fva456','raa273','kas426','mvl745','vey014','aav047','vvi098','sal014','ws254x','fg456h','qw159e','asd456','as789d','bn963m','bta026','kdv100','tea038','fg456h','rnm022','rev546','ldv675','pis862','sev236'];
		$users = User::select(['users.id','users.surname','users.name','users.patronymic','roles.role','users.position_department','users.deleted_at'])->join('roles','users.role','roles.id')->withTrashed()->get();
		/*foreach($users as $user){
			$user->login = $user->surname . ' ' . mb_substr($user->name,0,1) . '.' . mb_substr($user->patronymic,0,1) . '.';
			for($i=0; $i<count($logins); $i++)
				if($logins[$i] == $user->login)
					$user->password = bcrypt($passwords[$i]);
			$user->save();
		}*/
        return view('administrator.user.main', ['users'=>$users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$roles = DB::SELECT('SELECT id, role FROM roles');
        return view('administrator.user.register', ['roles'=>$roles]);
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
			'role' => $request['role'],
			'position_department' => $request['position_department'],
            'login' => $request['login'],
            'password' => bcrypt($request['password']),
        ]);
		JournalController::store(Auth::User()->id,'Создание нового пользователя с id = ' . $user->id);
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
        return view('administrator.user.edit', ['user'=>$user, 'roles'=>$roles]);
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
				'role' => $request['role'],
				'position_department' => $request['position_department'],
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
				'role' => $request['role'],
				'position_department' => $request['position_department']
			]);
		}
		$user->save();
		JournalController::store(Auth::User()->id,'Редактирование пользователя с id = ' . $user->id);
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
