<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
	public function download()
	{
		$file = 'reports/KontrolOrder_Reports.exe';
		$headers = [
					  'Content-Type' => 'application/exe',
				   ];

		return response()->download($file, 'KontrolOrder_Reports.exe', $headers);
	}
	
	//Уведомления
	public function report_notify()
	{
		if(isset($_GET['name_table']))
		{
			switch($_GET['name_table'])
			{
				case 'result':
					$orders = NotifycationController::CreateNotifycationResult();
					return json_encode($orders);
				case 'executor':
					if(isset($_GET['id_executor']))
					{
						$orders = NotifycationController::CreateNotifycationExecutor();
						return json_encode($orders);
					}
					break;
				case 'number_document':
					if(isset($_GET['number_document']))
					{
						$orders = NotifycationController::CreateNotifycationNumberDocument();
						return json_encode($orders);
					}
					break;
				case 'month':
					if(isset($_GET['month']))
					{
						$orders = NotifycationController::CreateNotifycationMonth();
						return json_encode($orders);
					}
					break;
				case 'today':
					$orders = NotifycationController::CreateNotifycationToday();
					return $orders;
				case 'period':
					if(isset($_GET['beginPeriod']) && isset($_GET['endPeriod']))
					{
						$orders = NotifycationController::CreateNotifycationPeriod();
						return json_encode($orders);
					}
					break;
			}
		}
	}
	
	//Отчёты
    public function report_print(Request $request)
	{
		if(isset($_GET['name_report']))
		{
			if(strlen($_GET['name_report']) > 0)
			{
				$equal_executor = '>';
				$value_executor = '0';
				if(isset($_GET['id_executor']))
				{
					if(strlen($_GET['id_executor']) > 0)
					{
						$equal_executor = '=';
						$value_executor = $_GET['id_executor'];
					}
				}
				switch($_GET['name_report'])
				{
					case 'no_complete':
						$result = PrintController::GetOrdersNoComplete($equal_executor, $value_executor);
						return json_encode($result);
					case 'month':
						$result = PrintController::GetOrdersMonth($equal_executor, $value_executor);
						return json_encode($result);
					case 'complete':
						$result = PrintController::GetOrdersComplete($equal_executor, $value_executor);
						return json_encode($result);
				}
			}
		}
	}
}
