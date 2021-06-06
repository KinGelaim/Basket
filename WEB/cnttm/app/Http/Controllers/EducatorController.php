<?php

namespace App\Http\Controllers;

use App\User;
use App\Educator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EducatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$educators = Educator::select(['users.surname',
										'users.name',
										'users.patronymic',
										'position',
										'photo',
										'short_information',
										'full_information',
										'educators.id',
										'educators.deleted_at'])->join('users','users.id','educators.id_user')->orderBy('position','asc')->get();
        return view('administration.educator.main', ['educators'=>$educators]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::select(['users.id','surname','users.name','patronymic'])->join('roles','roles.id','users.id_role')->where('roles.role','Преподаватель')->orWhere('roles.role', 'Администратор')->get();
        return view('administration.educator.register', ['users'=>$users]);
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
			'position' => 'required',
			'photo' => 'required',
			'short_information' => 'required',
			'full_information' => 'required'
		])->validate();
        $educator = Educator::create([
            'id_user' => $request['id_user'],
            'position' => $request['position'],
            'photo' => $request['photo'],
            'short_information' => $request['short_information'],
			'full_information' => $request['full_information']
        ]);
		return redirect()->route('educator.main')->with('success','Успешно добавлен!');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
		$educators = Educator::select(['users.surname',
										'users.name',
										'users.patronymic',
										'position',
										'photo',
										'short_information',
										'full_information',
										'educators.deleted_at'])->join('users','users.id','educators.id_user')->orderBy('position','asc')->get();
		foreach($educators as $educator)
		{
			$pr = explode(';', $educator->photo);
			$educator->photo = $pr;
		}
        return view('prepods', ['educators'=>$educators]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		$educator = Educator::findOrFail($id);
		$users = User::select(['users.id','surname','users.name','patronymic'])->join('roles','roles.id','users.id_role')->where('roles.role','Преподаватель')->orWhere('roles.role', 'Администратор')->get();
        return view('administration.educator.edit', ['educator'=>$educator, 'users'=>$users]);
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
		$educator = Educator::findOrFail($id);
		$val = Validator::make($request->all(),[
			'id_user' => 'required',
			'position' => 'required',
			'photo' => 'required',
			'short_information' => 'required',
			'full_information' => 'required'
		])->validate();
		$educator->fill([
            'id_user' => $request['id_user'],
            'position' => $request['position'],
            'photo' => $request['photo'],
            'short_information' => $request['short_information'],
			'full_information' => $request['full_information']
		]);
		$educator->save();
		return redirect()->route('educator.main')->with('success','Успешно изменен!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$educator = Educator::findOrFail($id);
		$educator->delete();
		return redirect()->back()->with('success','Успешно удален!');
    }
}
