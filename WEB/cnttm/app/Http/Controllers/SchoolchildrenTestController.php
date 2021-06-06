<?php

namespace App\Http\Controllers;

use Auth;
use App\Schoolchildren;
use App\SchoolchildrenTest;
use Illuminate\Http\Request;

class SchoolchildrenTestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$is_complete = 0;		//TODO: какой-то выбор группы, перед отображением, что учёба завершена!
		$tests = [];
		if(Auth::User())
		{
			$schoolchildrens = Schoolchildren::select(['*'])->where('id_user', Auth::User()->id)->get();
			foreach($schoolchildrens as $schoolchildren){
				array_push($tests, SchoolchildrenTest::select(['*','tests.id'])->join('tests','tests.id','schoolchildren_tests.id_test')->where('id_schoolchildren', $schoolchildren->id)->get());
				if($schoolchildren->is_complete == 1)
					$is_complete = 1;
			}
		}
        return view('control', ['tests'=>$tests, 'is_complete'=>$is_complete]);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_schoolchildren)
    {
		$tests = SchoolchildrenTest::select(['*'])->join('tests','tests.id','schoolchildren_tests.id_test')->where('id_schoolchildren', $id_schoolchildren)->get();
        return view('control', ['tests'=>$tests]);
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
    public function update(Request $request, $id)
    {
        //
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
