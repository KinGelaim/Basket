<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
	{
		$roles = Role::select(['*'])->get();
        return view('administration.role.main', ['roles'=>$roles]);
	}
	
	public function create()
	{
	
	}
	
	public function store(Request $request)
	{
	
	}
	
	public function edit($id)
	{
	
	}
	
	public function update(Request $request, $id)
	{
	
	}
	
	public function delete($id)
	{
	
	}
}
