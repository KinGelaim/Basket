<?php

namespace App\Http\Controllers;

use App\User;
use App\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class JournalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$link = '';
		//-------ФИЛЬТРЫ-------
		//Пользователи
		$users = User::select('id','name','surname','patronymic')->orderBy('users.surname','asc')->get();
		$filter_user_str = "users.id";
		$filter_user_equal = ">";
		$filter_user = '0';
		if(isset($_GET['filter_user'])) {
			if(strlen($_GET['filter_user']) > 0){
				$filter_user = ($_GET['filter_user']);
				$filter_user_str = "users.id";
				$filter_user_equal = "=";
				$link .= "&filter_user=" . $_GET['filter_user'];
			}
		}
		//Роли
		$roles = DB::SELECT('SELECT id, role FROM roles');
		$filter_role_str = "roles.id";
		$filter_role_equal = ">";
		$filter_role = '0';
		if(isset($_GET['filter_role'])) {
			if(strlen($_GET['filter_role']) > 0){
				$filter_role = ($_GET['filter_role']);
				$filter_role_str = "roles.id";
				$filter_role_equal = "=";
				$link .= "&filter_role=" . $_GET['filter_role'];
			}
		}
		//Дата
		$filter_date_str = "journal.created_at";
		$filter_date_equal = ">";
		$filter_b_date = '0';
		$filter_e_date = date('Y-m-d 23:59:59',time());
		if(isset($_GET['filter_date'])) {
			if(strlen($_GET['filter_date']) > 0){
				$filter_b_date = date('Y-m-d 00:00:00',strtotime($_GET['filter_date']));
				$filter_e_date = date('Y-m-d 23:59:59',strtotime($_GET['filter_date']));
				$filter_date_str = "journal.created_at";
				$filter_date_equal = ">";
				$link .= "&filter_date=" . $_GET['filter_date'];
			}
		}
		//Поиск
		$search_name = '';
		if(isset($_GET['search_name'])) {
			if(strlen($_GET['search_name']) > 0) {
				$search_name = $_GET['search_name'];
				$link .= "&search_name=" . $_GET['search_name'];
			}
		}
		$search_value = '';
		if(isset($_GET['search_value'])) {
			if(strlen($_GET['search_value']) > 0) {						
				$search_value = $_GET['search_value'];
				if(isset($_GET['search_name']))
					if($_GET['search_name'] == 'number_contract')
						$search_value = str_replace('-','‐',$search_value);
				$link .= "&search_value=" . $search_value;
			}
		}
		//Пагинация
		$paginate_count = 17;
		if (isset($_GET["page"])) {
			$page  = $_GET["page"];
		} else {
			$page=1;
		};
		$start = ($page-1) * $paginate_count;
		if($search_name != '' && $search_value != ''){
			$journals = Journal::select('message','users.name','users.surname','users.patronymic','roles.role','journal.created_at')
								->join('users','journal.id_user','users.id')
								->leftJoin('roles','roles.id','users.id_role')
								->where($filter_user_str,$filter_user_equal,$filter_user)
								->where($filter_role_str,$filter_role_equal,$filter_role)
								->where($filter_date_str,$filter_date_equal,$filter_b_date)
								->where('journal.created_at','<=',$filter_e_date)
								->where($search_name, 'like', '%' . $search_value . '%')
								->offset($start)
								->limit($paginate_count)
								->orderBy('journal.id','desc')
								->get();
			$journal_count = Journal::select('journal.id')
								->join('users','journal.id_user','users.id')
								->leftJoin('roles','roles.id','users.id_role')
								->where($filter_user_str,$filter_user_equal,$filter_user)
								->where($filter_role_str,$filter_role_equal,$filter_role)
								->where($filter_date_str,$filter_date_equal,$filter_b_date)
								->where('journal.created_at','<=',$filter_e_date)
								->where($search_name, 'like', '%' . $search_value . '%')
								->count();
		}else{
			$journals = Journal::select('message','users.name','users.surname','users.patronymic','roles.role','journal.created_at')
								->join('users','journal.id_user','users.id')
								->leftJoin('roles','roles.id','users.id_role')
								->where($filter_user_str,$filter_user_equal,$filter_user)
								->where($filter_role_str,$filter_role_equal,$filter_role)
								->where($filter_date_str,$filter_date_equal,$filter_b_date)
								->where('journal.created_at','<=',$filter_e_date)
								->offset($start)
								->limit($paginate_count)
								->orderBy('journal.id','desc')
								->get();
			$journal_count = Journal::select('journal.id')->join('users','journal.id_user','users.id')->leftJoin('roles','roles.id','users.id_role')->where($filter_user_str,$filter_user_equal,$filter_user)->where($filter_role_str,$filter_role_equal,$filter_role)->where($filter_date_str,$filter_date_equal,$filter_b_date)->where('journal.created_at','<=',$filter_e_date)->count();
		}
		foreach($journals as $journal)
		{
			$pr = explode('~',$journal->message);
			if(count($pr) > 1){
				$journal->comment = json_decode($pr[1]);
				$journal->message = $pr[0];
			}
		}
		$prev_page = $page - 1 > 0 ? (int)($page-1) : '';
		$next_page = $page + 1 <= (int)ceil($journal_count/$paginate_count) ? (int)($page+1) : '';
        return view('journal.main', [
										'users' => $users,
										'roles' => $roles,
										'search_name'=>$search_name,
										'search_value'=>$search_value,
										'journals' => $journals,
										'count_paginate' => (int)ceil($journal_count/$paginate_count),
										'prev_page' => $prev_page,
										'next_page' => $next_page,
										'page' => $page,
										'link' => $link]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store($id_user, $message)
    {
        $journal = new Journal();
		$journal->id_user = $id_user;
		$journal->message = $message;
		$journal->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Journal  $journal
     * @return \Illuminate\Http\Response
     */
    public function show(Journal $journal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Journal  $journal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Journal $journal)
    {
        //
    }
	
	public static function getMyChanges(Model $model, $all_dirty = [])
	{
		$dirty = $model->getDirty();
		foreach($dirty as $key=>$value){
			$original_attr = $model->getOriginal($key);
			if($original_attr == null){
				if($value != null)
					$all_dirty += [$key=>'null->' . $value];
			}
			else if($value == null){
				if($original_attr != null)
					$all_dirty += [$key=>$original_attr . '->null'];
			}
			else if($value != null || $original_attr != null)
				$all_dirty += [$key=>$original_attr . '->' . $value];
		}
		return $all_dirty;
	}
}
