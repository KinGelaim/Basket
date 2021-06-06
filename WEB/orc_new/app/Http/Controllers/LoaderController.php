<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoaderController extends Controller
{
    public function index()
    {
        return view('loaders');
    }
}
