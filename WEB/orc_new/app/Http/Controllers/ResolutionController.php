<?php

namespace App\Http\Controllers;

use Auth;
use App\Resolution;
use App\ReconciliationUser;
use App\User;
use App\CounterpartieResolution;
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
		if($request['name_new_direction'])
		{
			$list_new_direction = [];
			foreach($request['name_new_direction'] as $directed)
			{
				$user = User::find($directed);
				if($user != null)
					$list_new_direction += [$user->id => $user->surname . ' ' . $user->name . ' ' . $user->patronymic];
			}
			$request->session()->flash('list_new_direction', $list_new_direction);
		}
        //dd($request->all());
		
		$val = Validator::make($request->all(),[
			//'new_file_resolution' => 'required',
			'real_name_document' => 'required',
			'date_resolution' => 'required|date',
		])->validate();
		
		$file = $request->file('new_file_resolution');
		//md5
		$md5 = md5($file);
		
		//Путь к файлу
		$patchPrFirst = 'resolution/'.$md5[0].$md5[1];
		$patchPrTwo = 'resolution/'.$md5[0].$md5[1].'/'.$md5[2].$md5[3];
		$patchPrThree = 'resolution/'.$md5[0].$md5[1].'/'.$md5[2].$md5[3].'/'.substr($md5 , 4);
		
		if(!file_exists($patchPrFirst))
			mkdir($patchPrFirst, 0777, true);
		if(!file_exists($patchPrTwo))
		{
			chmod($patchPrFirst, 0777);
			mkdir($patchPrTwo, 0777, true);
			chmod($patchPrTwo, 0777);
		}
		if(!file_exists($patchPrThree))
		{
			chmod($patchPrFirst, 0777);
			chmod($patchPrTwo, 0777);
			mkdir($patchPrThree, 0777, true);
			chmod($patchPrThree, 0777);
		}
		$nameFile = str_replace('.'.$file->getClientOriginalExtension(),'',$file->getClientOriginalName());
		
		//Сохранение скана
		$fullName = $patchPrThree . '/' . $nameFile . '.' . $file->getClientOriginalExtension();
		$file->move('Y:/' . $patchPrThree,$nameFile . '.' . $file->getClientOriginalExtension());
		
		$resolution = new Resolution();
		$resolution->fill([
			'id_user' => Auth::User()->id,
			$request['real_name_document'] => $id,
			'real_name_resolution' => isset($_POST['real_name_resolution']) ? (strlen($_POST['real_name_resolution'])>0 ? $_POST['real_name_resolution'] : $file->getClientOriginalName()) : $file->getClientOriginalName(),
			'path_resolution' => $_SERVER['SERVER_ADDR'] . '/resolution/' . $fullName,
			'date_resolution' => $request['date_resolution'],
			'type_resolution' => $request['type_resolution']
		]);
		
		$resolution->save();
		
		//dump($resolution);
		
		$resolutions = Resolution::select(['*'])->where($request['real_name_document'], $id)->get();
		JournalController::store(Auth::User()->id,'Добавил новый скан с '.$request['real_name_document'].' = ' . $id);
		//dump($resolutions);
		
		$request->session()->flash('all_scan', $resolutions);
		$request->session()->flash('new_scan', $resolution);
		if($request['real_name_document'] == 'id_application_resolution')
		{
			$directed = ReconciliationUser::select(['reconciliation_users.id as recID',
														'users.id as userID',
														'users.surname', 
														'users.name', 
														'users.patronymic'
												])->join('users', 'users.id', 'id_user')
												->where('id_application',$id)
												->get();
			$request->session()->flash('all_directed_list', $directed);
		}
		return redirect()->back()->withInput();
    }

    public static function store_resol_new_app(Request $request, $id)
    {
		if($request->file('new_file_resolution'))
		{
			$val = Validator::make($request->all(),[
				//'new_file_resolution' => 'required',
				'real_name_document' => 'required',
				'date_resolution' => 'required',
			])->validate();
			
			$file = $request->file('new_file_resolution');
			//md5
			$md5 = md5($file);
			
			//Путь к файлу
			$patchPrFirst = 'resolution/'.$md5[0].$md5[1];
			$patchPrTwo = 'resolution/'.$md5[0].$md5[1].'/'.$md5[2].$md5[3];
			
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
				$request['real_name_document'] => $id,
				'real_name_resolution' => isset($_POST['real_name_resolution']) ? (strlen($_POST['real_name_resolution'])>0 ? $_POST['real_name_resolution'] : $file->getClientOriginalName()) : $file->getClientOriginalName(),
				'path_resolution' => $_SERVER['SERVER_ADDR'] . '/resolution/' . $fullName,
				'date_resolution' => $request['date_resolution'],
			]);
			
			$resolution->save();
		}
		return redirect()->back()->withInput();
    }
	
	public static function save_in_bd_resol_counterpartie(Request $request, $id)
	{
		if($request->file('new_file_resolution'))
		{
			$val = Validator::make($request->all(),[
				'real_name_document' => 'required',
				'date_resolution' => 'required',
			])->validate();
			
			$file = $request->file('new_file_resolution');
			$md5 = md5($file);
			
			//Путь к файлу
			$patchPrFirst = 'resolution_counterpartie/'.$md5[0].$md5[1];
			$patchPrTwo = 'resolution_counterpartie/'.$md5[0].$md5[1].'/'.$md5[2].$md5[3];
			
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
			
			$resolution = new CounterpartieResolution();
			$resolution->fill([
				$request['real_name_document'] => $id,
				'real_name_resolution' => isset($_POST['real_name_resolution']) ? (strlen($_POST['real_name_resolution'])>0 ? $_POST['real_name_resolution'] : $file->getClientOriginalName()) : $file->getClientOriginalName(),
				'path_resolution' => $_SERVER['SERVER_ADDR'] . '/resolution/' . $fullName,
				'date_resolution' => $request['date_resolution'],
			]);
			
			$resolution->save();
		}
	}
	
	public static function store_resol_counterpartie(Request $request, $id)
    {
		ResolutionController::save_in_bd_resol_counterpartie($request, $id);
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
		//$img = "D:\resolution_counterpartie\85\14\ebe78f5dca6c517790743ec3e383.bmp";
        return view('resolution.show');
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
		if(isset($request['select_old_delete']))
		{
			foreach($request['select_old_delete'] as $id_resolution)
			{
				$resolution = Resolution::findOrFail($id_resolution);
				$resolution->deleted_at = null;
				$resolution->save();
				JournalController::store(Auth::User()->id,'Восстановлен скан с id = ' . $resolution->id . ' для контракта с id = ' . $resolution->id_contract_resolution);
			}
		}
        if(isset($request['select_message']))
		{
			foreach($request['select_message'] as $id_resolution)
			{
				$resolution = Resolution::findOrFail($id_resolution);
				$resolution->deleted_at = date('Y-m-d H:i:s',time());
				$resolution->save();
				JournalController::store(Auth::User()->id,'Помечен скан на удаление с id = ' . $resolution->id . ' для контракта с id = ' . $resolution->id_contract_resolution);
			}
		}
		return redirect()->back()->with('success', 'Сканы удалены и восстановлены!');
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
