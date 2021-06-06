<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CSharpController extends Controller
{
	public $storage_path = "D:/tmp_storage_reports/";
	
    public function index()
	{
		$files = scandir($this->storage_path);
		return view('storage_reports.main',['files'=>$files, 'path'=>$this->storage_path]);
	}
	
	//Распределение по видам отчетов и вызов модуля
	public function create(Request $request)
	{
		if(isset($request['real_name_report']) && isset($request['type_report']) && isset($request['real_name_table']))
		{
			$exe = __DIR__ . '\..\..\..\public\create_report\Create_Report_Reestr.exe';
			//$exe = 'C:\xampp\htdocs\orc_new\public\create_report\Create_Report_Reestr.exe';
			$desc = [["pipe", "r"], ["pipe", "w"]];
			$query = '';
			if(isset($request['query']))
				$query = $request['query'];
			if($request['real_name_table'] == 'справка о крупных сделках')
				$new_real_name_table = 1;
			//dd("{'real_name_report':'".$request['real_name_report']."','type_report':'".$request['type_report']."','real_name_table':'".$new_real_name_table."','query':".'"'.$query.'"'.",'storage_path':'".$this->storage_path."'}");
			$proc = proc_open($exe, $desc, $pipes);
			fwrite($pipes[0], "{'real_name_report':'".$request['real_name_report']."','type_report':'".$request['type_report']."','real_name_table':'".$new_real_name_table."','query':".'"'.$query.'"'.",'storage_path':'".$this->storage_path."'}");	//Передаем входные параметры в модуль в формате json
			fclose($pipes[0]);
			$result = stream_get_contents($pipes[1]);
			fclose($pipes[1]);
			//dd($result);
			if($result == 'true')
				return redirect()->route('sharp.storage');
			else
				return redirect()->back()->with('error', 'Какая-то ошибка!\n' . $result);
		}
	}
}
