<?php

namespace App\Http\Controllers;

use App\User;
use App\Application;
use App\ReestrContract;
use App\ReconciliationUser;
use Illuminate\Http\Request;

class HelperController extends Controller
{
    public function download()
	{
		$file = 'helper/ORC_Helper.exe';
		$headers = [
					  'Content-Type' => 'application/exe',
				   ];

		return response()->download($file, 'ORC_Helper.exe', $headers);
	}
	
	public function all_users()
	{
		$users = User::select(['id','surname','name','patronymic'])->orderBy('surname')->get();
		return $users;
	}
	
	public function all_select_commands()
	{
		if(isset($_GET['id_user']))
		{
			$text = "";
			if(isset($_GET['applications']))
			{
				if($_GET['applications'] == '1')
				{
					$count_new_application = ReconciliationUser::select('reconciliation_users.id')
											->join('applications','id_application', 'applications.id')
											->where('id_user',$_GET['id_user'])
											->where('check_reconciliation', 0)
											->where('applications.is_protocol', 0)
											->where('applications.deleted_at', null)
											->get()
											->count();
					if($count_new_application > 0)
						$text = "У Вас новые письма на согласование! (" . $count_new_application . ")\n";
				}
			}
			if(isset($_GET['contracts']))
			{
				if($_GET['contracts'] == '1')
				{
					$count_new_contract = ReconciliationUser::select('reconciliation_users.id')
									->join('contracts','id_contract', 'contracts.id')
									->where('id_user',$_GET['id_user'])
									->where('check_reconciliation', 0)
									->where('contracts.deleted_at', null)
									->get()
									->count();
					if($count_new_contract > 0)
						$text .= "У Вас новые контракты на согласование! (" . $count_new_contract . ")\n";
				}
			}
			if(isset($_GET['protocols']))
			{
				if($_GET['protocols'] == '1')
				{
					$count_new_protocol = ReconciliationUser::select('reconciliation_users.id')
									->join('applications','id_application', 'applications.id')
									->where('id_user',$_GET['id_user'])
									->where('check_reconciliation', 0)
									->where('applications.is_protocol', 1)
									->where('applications.deleted_at', null)
									->get()
									->count();
					if($count_new_protocol > 0)
						$text .= "У Вас новые протоколы на согласование! (" . $count_new_protocol . ")\n";
				}
			}
			return $text;
		}
	}
	
	public function all_unsigning_contract()
	{
		$text = "";
		$count = 0;
		$unsigning_contract = ReestrContract::select('id_contract_reestr','date_registration_project_reestr','date_signing_contract_reestr')
									->where('date_registration_project_reestr', '!=', null)
									->where('date_signing_contract_reestr', null)
									->get();
		foreach($unsigning_contract as $contract)
			if(time() - strtotime($contract->date_registration_project_reestr) > 2592000)
				if(isset($_GET['info']))
					$text .= $contract->id_contract_reestr . "<br/>";
				else
					$count++;
		if($count != 0)
			$text .= "Неподписанных договоров > 30 дней! (" . $count . ")\n";
		return $text;
	}
	
	public function all_department_unsigning_contract()
	{
		$text = "";
		$count = 0;
		$result = [];
		$unsigning_contract = ReestrContract::select('id_contract_reestr','number_contract','date_registration_project_reestr','date_signing_contract_reestr')
									->join('contracts', 'id_contract_reestr', 'contracts.id')
									->where('date_registration_project_reestr', '!=', null)
									->where('date_signing_contract_reestr', null)
									->get();
		return $unsigning_contract;
		//Распределение по департаментам и выделение количество неподписанных дней
		foreach($unsigning_contract as $contract)
			if(time() - strtotime($contract->date_registration_project_reestr) > 2592000)
				if(isset($_GET['info']))
					$text .= $contract->id_contract_reestr . "<br/>";
				else
					$count++;
		if($count != 0)
			$text .= "Неподписанных договоров > 30 дней! (" . $count . ")\n";
		return $text;
	}
}