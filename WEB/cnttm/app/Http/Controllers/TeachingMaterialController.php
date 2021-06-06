<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeachingMaterialController extends Controller
{
    public function index()
	{
		return view('teaching_materials.main');
	}
	
	public function history()
	{
		return view('teaching_materials.history');
	}
	
	public function tb()
	{
		return view('teaching_materials.tb');
	}
	
	public function radio()
	{
		return view('teaching_materials.radio');
	}
}
