<?php

namespace App\Http\Controllers;

use Auth;
use App\Resolution;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ResolutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request, $id)
    {
		ResolutionController::store_resol_new_order($request, $id);
		return redirect()->back();
    }

    public static function store_resol_new_order(Request $request, $id)
    {
		if($request->file('new_file_resolution'))
		{
			$val = Validator::make($request->all(),[
				//'new_file_resolution' => 'required',
				'date_resolution' => 'required',
			])->validate();
			
			$file = $request->file('new_file_resolution');
			//md5
			$md5 = md5($file);
			
			//Путь к файлу
			$patchPrFirst = 'kontrol_order_resolution/'.$md5[0].$md5[1];
			$patchPrTwo = 'kontrol_order_resolution/'.$md5[0].$md5[1].'/'.$md5[2].$md5[3];
			
			if(!file_exists($patchPrFirst))
				mkdir($patchPrFirst, 0777, true);
			if(!file_exists($patchPrTwo))
			{
				chmod($patchPrFirst, 0777);
				mkdir($patchPrTwo, 0777, true);
				chmod($patchPrTwo, 0777);
			}
			$nameFile = substr($md5 , 4);
			
			//Сохранение скана
			$fullName = $patchPrTwo . '/' . $nameFile . '.' . $file->getClientOriginalExtension();
			$file->move('Y:/' . $patchPrTwo,$nameFile . '.' . $file->getClientOriginalExtension());
			
			$resolution = new Resolution();
			$resolution->fill([
				'id_user' => Auth::User()->id,
				'id_order' => $id,
				'real_name_resolution' => isset($_POST['real_name_resolution']) ? (strlen($_POST['real_name_resolution'])>0 ? $_POST['real_name_resolution'] : $file->getClientOriginalName()) : $file->getClientOriginalName(),
				'path_resolution' => $_SERVER['SERVER_ADDR'] . '/resolution/' . $fullName,
				'date_resolution' => $request['date_resolution'],
			]);
			
			$resolution->save();
		}
		return redirect()->back()->withInput();
    }
	
    /**
     * Display the specified resource.
     *
     * @param  \App\Resolution  $resolution
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
		//
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Resolution  $resolution
     * @return \Illuminate\Http\Response
     */
    public function edit(Resolution $resolution)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Resolution  $resolution
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
		//
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Resolution  $resolution
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		//Удаляем из БД
		$resolution = Resolution::findOrFail($id);
		//dd($resolution);
		$resolution->delete();
		//Удаляем физически
		$pr_path = preg_replace("/^(.*?)(resolution)\//", "", $resolution->path_resolution);	//Выделяем путь к файлу
		$full_pr_path = 'Y:/' . $pr_path;		//Формируем полный путь к файлу
		//Проверяем на наличие этого файла и удаляем
		if(file_exists($full_pr_path))
		{
			unlink($full_pr_path);
		}
		JournalController::store(Auth::User()->id,'Удален скан id = ' . $resolution->id . ' для контракста с id = ' . $resolution->id_contract_resolution);
		return redirect()->back()->with('success', 'Скан удален!');
    }
	
	public function destroy_contract_ajax($id)
    {
		$resolution = Resolution::findOrFail($id);
		$resolution->deleted_at = date('Y-m-d H:i:s', time());
		$resolution->save();
		JournalController::store(Auth::User()->id,'Помечен на удаление скан id = ' . $resolution->id . ' для контракста с id = ' . $resolution->id_contract_resolution);
		return 'true';
    }
	
	public function destroy_additional_document_ajax($id)
	{
		$resolution = Resolution::findOrFail($id);
		$resolution->deleted_at = date('Y-m-d H:i:s', time());
		$resolution->save();
		JournalController::store(Auth::User()->id,'Помечен на удаление скан id = ' . $resolution->id . ' для ПР/ДС с id = ' . $resolution->id_protocol_resolution);
		return 'true';
	}
	
	public function download($id)
	{
		$resolution = Resolution::findOrFail($id);
		//dump($resolution);
		return Response()->file($resolution->path_resolution);
	}
}
