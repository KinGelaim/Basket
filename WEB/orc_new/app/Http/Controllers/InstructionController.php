<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InstructionController extends Controller
{
    function index()
	{
		
		return view('instruction.main');
	}
}
