<?php

namespace App\Http\Controllers;

use App\State;
use App\ViewContract;
use App\Counterpartie;
use App\SecondDepartmentAct;
use App\SecondDepartmentTour;
use App\SecondDepartmentSbTour;
use App\SecondDepartmentUsTour;
use App\Invoice;
use App\Contract;
use App\Department;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

//Контроллер для вспомогательное проги ORC_Reports
class ReportsController extends Controller
{
	private $version = '1.0.0.0';
	
	public function check_version()
	{
		return $this->version;
	}
	
	public function download()
	{
		$file = 'reports/ORC_Reports.exe';
		$headers = [
					  'Content-Type' => 'application/exe',
				   ];

		return response()->download($file, 'ORC_Reports.exe', $headers);
	}
	
	public function second_department_print()
	{
		if($_GET['real_name_table'])
		{
			switch($_GET['real_name_table'])
			{
				case 'Сводный отчет по договорам':
					$view_contract_str = "contracts.id_counterpartie_contract";
					$view_contract_equal = ">";
					$view_contract = '';
					if(isset($_GET['view_work']))
						if($_GET['view_work'] != 'Все виды работ' && strlen($_GET['view_work']) > 0)
						{
							$view_contract = $_GET['view_work'];
							$view_contract_str = "view_contracts.name_view_contract";
							$view_contract_equal = "=";
						}
					$year_contract_str = "contracts.id_counterpartie_contract";
					$year_contract_equal = ">";
					$year_contract = '';
					if(isset($_GET['year']))
						if($_GET['year'] != '')
						{
							$year_contract = $_GET['year'];
							$year_contract_str = "contracts.year_contract";
							$year_contract_equal = "=";
						}
					$contracts = SecondDepartmentController::print_report_1($view_contract_str, $view_contract_equal, $view_contract, $year_contract_str, $year_contract_equal, $year_contract);
					foreach($contracts as $contract){
						$states = State::select(['*'])->where('id_contract', $contract->id)->where('is_work_state', null)->get();
						if($states != null && count($states) > 0)
							$contract->state = $states[count($states) - 1];
					}
					return $contracts;
				case 'Поступление за период':
					$period1 = isset($_GET['date_begin']) && $_GET['date_begin'] != '' ? DATE('Y-m-d', strtotime($_GET['date_begin'])) : date('Y', time()) . '-01-01';
					$period2 = isset($_GET['date_end']) && $_GET['date_end'] != '' ? DATE('Y-m-d', strtotime($_GET['date_end'])) : date('Y-m-d', time());
					$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
					$second_department_tours = DB::SELECT("SELECT * , second_department_tours.id as tourID FROM second_department_tours JOIN contracts ON contracts.id=second_department_tours.id_contract LEFT JOIN elements ON second_department_tours.id_element=elements.id LEFT JOIN view_work_elements ON second_department_tours.id_view_work_elements=view_work_elements.id LEFT JOIN second_department_units ON second_department_units.id=second_department_tours.id_unit LEFT JOIN results ON results.id=second_department_tours.id_result WHERE second_department_tours.deleted_at IS NULL AND (STR_TO_DATE(date_incoming,'%d.%m.%Y') BETWEEN '" . $period1 . "' AND '" . $period2 . "') ORDER BY STR_TO_DATE(date_incoming,'%d.%m.%Y') ASC");
					foreach($second_department_tours as $contract){
						foreach($counterparties as $counter)
							if($contract->id_counterpartie_contract == $counter->id)
								$contract->name_counterpartie_contract = $counter->name;
								
						//Получаем акты
						$pr_amount_acts = 0;
						$acts = SecondDepartmentAct::select(['number_act','amount_act','date_act'])
													->where('id_second_tour', $contract->tourID)
													->get();
						foreach($acts as $act){
							$pr_amount_acts += $act->amount_act;
							$contract->date_act = $act->date_act;
							$contract->number_act = $act->number_act;
						}
						$contract->amount_acts = $pr_amount_acts;
					}
					return $second_department_tours;
				case 'Выполнение за период':
					if(isset($_GET['view_complete_report'])){
						$date_begin = date('Y', time()) . '-' . '01' . '-' . '01';
						$date_end = date('Y-m-d', time());
						if(isset($_GET['date_begin']))
							if($_GET['date_begin'])
								if($_GET['date_begin'] != '')
									$date_begin = $_GET['date_begin'];
						if(isset($_GET['date_end']))
							if($_GET['date_end'])
								if($_GET['date_end'] != '')
									$date_end = $_GET['date_end'];
						$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
						$counterpartie_str = "contracts.id_counterpartie_contract";
						$counterpartie_equal = ">";
						$counterpartie = '';
						if(isset($_GET['counterpartie'])) {
							if(strlen($_GET['counterpartie']) > 0) {
								$counterpartie = $_GET['counterpartie'];
								$counterpartie_str = "id_counterpartie_contract";
								$counterpartie_equal = "=";
							}
						}
						switch($_GET['view_complete_report']){
							case 'Общий':
								$second_department_tours = SecondDepartmentTour::Select(['*', 'second_department_tours.id as tourID'])
																		->join('contracts','second_department_tours.id_contract','contracts.id')
																		->leftjoin('elements','second_department_tours.id_element', 'elements.id')
																		->leftjoin('view_work_elements', 'second_department_tours.id_view_work_elements', 'view_work_elements.id')
																		->leftJoin('second_department_units', 'second_department_units.id', 'second_department_tours.id_unit')
																		->where($counterpartie_str, $counterpartie_equal, $counterpartie)
																		->whereBetween('second_department_tours.date_worked', array(DATE('Y-m-d', strtotime($date_begin)),DATE('Y-m-d', strtotime($date_end))))
																		->get()->sortBy('number_duty');
								$second_department_sb_tours = SecondDepartmentSbTour::Select(['*', 'second_department_sb_tours.id as tourID'])
																		->join('contracts','second_department_sb_tours.id_contract','contracts.id')
																		->leftjoin('elements','second_department_sb_tours.id_element', 'elements.id')
																		->leftjoin('view_work_elements', 'second_department_sb_tours.id_view_work_elements', 'view_work_elements.id')
																		->leftJoin('second_department_units', 'second_department_units.id', 'second_department_sb_tours.id_unit')
																		->where($counterpartie_str, $counterpartie_equal, $counterpartie)
																		->whereBetween('second_department_sb_tours.date_worked', array(DATE('Y-m-d', strtotime($date_begin)),DATE('Y-m-d', strtotime($date_end))))
																		->get()->sortBy('number_duty');
								$second_department_us_tours = SecondDepartmentUsTour::Select(['*', 'second_department_us_tours.id as tourID'])
																		->join('contracts','second_department_us_tours.id_contract','contracts.id')
																		->where($counterpartie_str, $counterpartie_equal, $counterpartie)
																		->whereBetween('second_department_us_tours.date_worked', array(DATE('Y-m-d', strtotime($date_begin)),DATE('Y-m-d', strtotime($date_end))))
																		->orderBy(DB::raw("(number_duty+0)"),'asc')
																		->get();
								$result = [];
								foreach($second_department_tours as $contract){
									foreach($counterparties as $counter)
										if($contract->id_counterpartie_contract == $counter->id)
											$contract->name_counterpartie_contract = $counter->name;
									$pr_amount_acts = 0;
									$acts = SecondDepartmentAct::select(['number_act','amount_act','date_act'])
																->where('id_second_tour', $contract->tourID)
																->get();
									foreach($acts as $act){
										$pr_amount_acts += $act->amount_act;
										$contract->date_act = $act->date_act;
										$contract->number_act = $act->number_act;
									}
									$contract->amount_acts = $pr_amount_acts;
									$contract->type_contract = "isp";
									array_push($result, $contract);
								}
								foreach($second_department_sb_tours as $contract){
									foreach($counterparties as $counter)
										if($contract->id_counterpartie_contract == $counter->id)
											$contract->name_counterpartie_contract = $counter->name;
									$pr_amount_acts = 0;
									$acts = SecondDepartmentAct::select(['number_act','amount_act','date_act'])
																->where('id_second_sb_tour', $contract->tourID)
																->get();
									foreach($acts as $act){
										$pr_amount_acts += $act->amount_act;
										$contract->date_act = $act->date_act;
										$contract->number_act = $act->number_act;
									}
									$contract->amount_acts = $pr_amount_acts;
									$contract->type_contract = "sb";
									array_push($result, $contract);
								}
								foreach($second_department_us_tours as $contract){
									foreach($counterparties as $counter)
										if($contract->id_counterpartie_contract == $counter->id)
											$contract->name_counterpartie_contract = $counter->name;
									$pr_amount_acts = 0;
									$acts = SecondDepartmentAct::select(['number_act','amount_act','date_act'])
																->where('id_second_us_tour', $contract->tourID)
																->get();
									foreach($acts as $act){
										$pr_amount_acts += $act->amount_act;
										$contract->date_act = $act->date_act;
										$contract->number_act = $act->number_act;
									}
									$contract->amount_acts = $pr_amount_acts;
									$contract->type_contract = "us";
									array_push($result, $contract);
								}
								return $result;
							case 'Испытания':
								$second_department_tours = SecondDepartmentTour::Select(['*', 'second_department_tours.id as tourID'])
																		->join('contracts','second_department_tours.id_contract','contracts.id')
																		->leftjoin('elements','second_department_tours.id_element', 'elements.id')
																		->leftjoin('view_work_elements', 'second_department_tours.id_view_work_elements', 'view_work_elements.id')
																		->leftJoin('second_department_units', 'second_department_units.id', 'second_department_tours.id_unit')
																		->leftJoin('results', 'results.id', 'second_department_tours.id_result')
																		->where($counterpartie_str, $counterpartie_equal, $counterpartie)
																		->whereBetween('second_department_tours.date_worked', array(DATE('Y-m-d', strtotime($date_begin)),DATE('Y-m-d', strtotime($date_end))))
																		->get()->sortBy('number_duty');
								foreach($second_department_tours as $contract){
									foreach($counterparties as $counter)
										if($contract->id_counterpartie_contract == $counter->id)
											$contract->name_counterpartie_contract = $counter->name;
									
									//Получаем акты
									$pr_amount_acts = 0;
									$acts = SecondDepartmentAct::select(['number_act','amount_act','date_act'])
																->where('id_second_tour', $contract->tourID)
																->get();
									foreach($acts as $act){
										$pr_amount_acts += $act->amount_act;
										$contract->date_act = $act->date_act;
										$contract->number_act = $act->number_act;
									}
									$contract->amount_acts = $pr_amount_acts;
								}
								return $second_department_tours;
							case 'Сборка':
								$second_department_sb_tours = SecondDepartmentSbTour::Select(['*', 'second_department_sb_tours.id as tourID'])
																		->join('contracts','second_department_sb_tours.id_contract','contracts.id')
																		->leftjoin('elements','second_department_sb_tours.id_element', 'elements.id')
																		->leftjoin('view_work_elements', 'second_department_sb_tours.id_view_work_elements', 'view_work_elements.id')
																		->leftJoin('second_department_units', 'second_department_units.id', 'second_department_sb_tours.id_unit')
																		->leftJoin('second_department_calibers', 'second_department_calibers.id', 'second_department_sb_tours.id_caliber')
																		->where($counterpartie_str, $counterpartie_equal, $counterpartie)
																		->whereBetween('second_department_sb_tours.date_worked', array(DATE('Y-m-d', strtotime($date_begin)),DATE('Y-m-d', strtotime($date_end))))
																		->get()->sortBy('number_duty');
								foreach($second_department_sb_tours as $contract){
									foreach($counterparties as $counter)
										if($contract->id_counterpartie_contract == $counter->id)
											$contract->name_counterpartie_contract = $counter->name;
									
									//Получаем акты
									$pr_amount_acts = 0;
									$acts = SecondDepartmentAct::select(['number_act','amount_act','date_act'])
																->where('id_second_sb_tour', $contract->tourID)
																->get();
									foreach($acts as $act){
										$pr_amount_acts += $act->amount_act;
										$contract->date_act = $act->date_act;
										$contract->number_act = $act->number_act;
									}
									$contract->amount_acts = $pr_amount_acts;
								}
								return $second_department_sb_tours;
							case 'Услуги':
								$second_department_us_tours = SecondDepartmentUsTour::Select(['*', 'second_department_us_tours.id as tourID'])
																		->join('contracts','second_department_us_tours.id_contract','contracts.id')
																		->where($counterpartie_str, $counterpartie_equal, $counterpartie)
																		->whereBetween('second_department_us_tours.date_worked', array(DATE('Y-m-d', strtotime($date_begin)),DATE('Y-m-d', strtotime($date_end))))
																		->orderBy(DB::raw("(number_duty+0)"),'asc')
																		->get();
																		
								foreach($second_department_us_tours as $contract){
									foreach($counterparties as $counter)
										if($contract->id_counterpartie_contract == $counter->id)
											$contract->name_counterpartie_contract = $counter->name;
									
									//Получаем акты
									$pr_amount_acts = 0;
									$acts = SecondDepartmentAct::select(['number_act','amount_act','date_act'])
																->where('id_second_us_tour', $contract->tourID)
																->get();
									foreach($acts as $act){
										$pr_amount_acts += $act->amount_act;
										$contract->date_act = $act->date_act;
										$contract->number_act = $act->number_act;
									}
									$contract->amount_acts = $pr_amount_acts;
								}
								return $second_department_us_tours;
						}
					}
					break;
				case "Оплата за период":
					$date_begin = date('Y', time()) . '-' . '01' . '-' . '01';
					$date_end = date('Y-m-d', time());
					if(isset($_GET['date_begin']))
						if($_GET['date_begin'] != '')
							$date_begin = $_GET['date_begin'];
					if(isset($_GET['date_end']))
						if($_GET['date_end'] != '')
							$date_end = $_GET['date_end'];
					$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
					$payments = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice', DB::raw("STR_TO_DATE(date_invoice,'%Y-%m-%d') as STR_DATE")])
											->leftjoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
											->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
											->join('contracts', 'invoices.id_contract', 'contracts.id')
											->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
											->leftjoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
											->where('is_sip_contract', 1)
											->where('name', 'Оплата')
											->whereBetween('date_invoice', array(DATE('Y-m-d', strtotime($date_begin)),DATE('Y-m-d', strtotime($date_end))))
											->orderBy('date_invoice', 'asc')
											->get();
					foreach($payments as $contract)
						foreach($counterparties as $counter)
							if($contract->id_counterpartie_contract == $counter->id)
								$contract->name_counterpartie_contract = $counter->name;
					return $payments;
				case 'Предприятия за год к':
					$contracts = Contract::select('id_counterpartie_contract', 'name_view_contract')
											->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
											->leftjoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
											->where('is_sip_contract', 1)
											->where('year_contract', $_GET['year'])
											->where('id_counterpartie_contract' , '>' , 0)
											->where('deleted_at', null)
											->get();
					$counterparties = Counterpartie::select(['*'])->get();
					$result = [];
					foreach($contracts as $contract)
						if($contract->name_view_contract == 'Испытания контрольные' || $contract->name_view_contract == 'Испытания опытные' || $contract->name_view_contract == 'Сборка ')
							foreach($counterparties as $counter)
								if($contract->id_counterpartie_contract == $counter->id)
								{
									$contract->name_counterpartie_contract = $counter->name_full;
									if(in_array($contract->name_view_contract, array_keys($result)))
									{
										if(!in_array($contract->name_counterpartie_contract, $result[$contract->name_view_contract]))
											array_push($result[$contract->name_view_contract], $contract->name_counterpartie_contract);
									}
									else
									{
										$result += [$contract->name_view_contract => [$contract->name_counterpartie_contract]];
									}
									break;
								}
					return $result;
				case 'Предприятия за год':
					$contracts = Contract::select('id_counterpartie_contract', 'name_view_contract', 'item_contract')
											->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
											->leftjoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
											->where('is_sip_contract', 1)
											->where('year_contract', $_GET['year'])
											->where('id_counterpartie_contract' , '>' , 0)
											->where('deleted_at', null)
											->get();
					$counterparties = Counterpartie::select(['*'])->get();
					$result = [];
					foreach($contracts as $contract)
						if($contract->name_view_contract == 'Испытания контрольные' || $contract->name_view_contract == 'Испытания опытные' || $contract->name_view_contract == 'Сборка ')
							foreach($counterparties as $counter)
								if($contract->id_counterpartie_contract == $counter->id)
								{
									$contract->name_counterpartie_contract = $counter->name_full;
									if(in_array($counter->name_full, array_keys($result)))
									{
										if(!in_array($contract->name_view_contract, $result[$contract->name_counterpartie_contract]['view']))
											array_push($result[$contract->name_counterpartie_contract]['view'], $contract->name_view_contract);
										if(!in_array($contract->item_contract, $result[$contract->name_counterpartie_contract]['name_work']))
											array_push($result[$contract->name_counterpartie_contract]['name_work'], $contract->item_contract);
									}
									else
									{
										$result += [$contract->name_counterpartie_contract => ['view' => [$contract->name_view_contract], 'name_work' => [$contract->item_contract]]];
									}
									break;
								}
					return $result;
				case 'Сводный отчет по оплате':
					$view_contract_str = "contracts.id_counterpartie_contract";
					$view_contract_equal = ">";
					$view_contract = '';
					if(isset($_GET['view_work']))
						if($_GET['view_work'] != 'Все виды работ')
						{
							$view_contract = $_GET['view_work'];
							$view_contract_str = "view_contracts.name_view_contract";
							$view_contract_equal = "=";
						}
					$counterparties = Counterpartie::select(['*'])->orderBy('name', 'asc')->get();
					if(!isset($_GET['full_report']))
						$payments = Invoice::select(['*','invoices.id','view_invoices.name_view_invoice'])
												->leftJoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
												->join('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
												->join('contracts', 'invoices.id_contract', 'contracts.id')
												->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
												->leftjoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
												->where('is_sip_contract', 1)
												->where($view_contract_str, $view_contract_equal, $view_contract)
												->where('contracts.deleted_at', null)
												->orderBy('contracts.id_counterpartie_contract', 'asc')
												->orderBy('reestr_contracts.id_view_contract', 'asc')
												->orderBy('contracts.number_contract', 'asc')
												->get();
					else
						$payments = Contract::select(['*','invoices.id','view_invoices.name_view_invoice'])
												->leftjoin('invoices','invoices.id_contract','contracts.id')
												->leftJoin('view_invoices', 'invoices.id_view_invoice', 'view_invoices.id')
												->leftjoin('name_invoices', 'invoices.id_name_invoice', 'name_invoices.id')
												->leftJoin('reestr_contracts', 'contracts.id', 'reestr_contracts.id_contract_reestr')
												->leftjoin('view_contracts', 'reestr_contracts.id_view_contract', 'view_contracts.id')
												->where('is_sip_contract', 1)
												->where($view_contract_str, $view_contract_equal, $view_contract)
												->where('contracts.deleted_at', null)
												->orderBy('contracts.id_counterpartie_contract', 'asc')
												->orderBy('reestr_contracts.id_view_contract', 'asc')
												->orderBy('contracts.number_contract', 'asc')
												->get();
					foreach($payments as $payment)
						foreach($counterparties as $counter)
							if($payment->id_counterpartie_contract == $counter->id)
								$payment->name_counterpartie_contract = $counter->name;
					$result = [];
					$proverka_in_result = [];
					foreach($payments as $score)
					{
						if(in_array($score->number_contract, $proverka_in_result))
						{
							foreach($result as $res)
								if($res->number_contract == $score->number_contract)
								{
									if($score->name == 'Счет на оплату')
										$res->amount_scores += $score->amount_p_invoice;
									else if($score->name == 'Аванс')
										$res->amount_scores += $score->amount_p_invoice;
									else if($score->name == 'Счет-фактура')
										$res->amount_invoices += $score->amount_p_invoice;
									else if($score->name == 'Оплата')
										if($score->is_prepayment_invoice != 1)
											$res->amount_payments += $score->amount_p_invoice;
										else
											$res->amount_prepayments += $score->amount_p_invoice;
									else if($score->name == 'Возврат')
										$res->amount_returns += $score->amount_p_invoice;
								}
						}
						else
						{
							array_push($proverka_in_result, $score->number_contract);
							if($score->name == 'Счет на оплату')
								$score->amount_scores += $score->amount_p_invoice;
							else if($score->name == 'Аванс')
								$score->amount_scores += $score->amount_p_invoice;
							else if($score->name == 'Счет-фактура')
								$score->amount_invoices += $score->amount_p_invoice;
							else if($score->name == 'Оплата')
								if($score->is_prepayment_invoice != 1)
									$score->amount_payments += $score->amount_p_invoice;
								else
									$score->amount_prepayments += $score->amount_p_invoice;
							else if($score->name == 'Возврат')
								$score->amount_returns += $score->amount_p_invoice;
							array_push($result, $score);
						}
					}
					foreach($result as $score)
					{
						$score->debet += $score->name_view_work != 'Комплектация' ? (($score->amount_invoices - ($score->amount_payments+$score->amount_prepayments) + $score->amount_returns) > 0 ? $score->amount_invoices - ($score->amount_payments+$score->amount_prepayments) + $score->amount_returns : 0) : (($score->amount_payments+$score->amount_prepayments - $score->amount_invoices + $score->amount_returns) > 0 ? ($score->amount_payments+$score->amount_prepayments) - $score->amount_invoices + $score->amount_returns : 0);
						$score->kredit += $score->name_view_work != 'Комплектация' ? (($score->amount_payments+$score->amount_prepayments - $score->amount_invoices - $score->amount_returns) > 0 ? ($score->amount_payments+$score->amount_prepayments) - $score->amount_invoices - $score->amount_returns : 0) : (($score->amount_invoices - ($score->amount_payments+$score->amount_prepayments) - $score->amount_returns) > 0 ? $score->amount_invoices - ($score->amount_payments+$score->amount_prepayments) - $score->amount_returns : 0);
					}
					return $result;
			}
		}
	}
	
	//Получение всех видов сип договоров
	public function view_sip_contracts()
	{
		$view_contracts = ViewContract::select('*')->where('is_sip_view_contract', 1)->get();
		return $view_contracts;
	}
	
	//Получение сиповских контрагентов
	public function counterparties_sip()
	{
		$counterparties = Counterpartie::select(['*'])->where('is_sip_counterpartie', 1)->orderBy('name', 'asc')->get();
		return $counterparties;
	}
	
	//Получение всех подразделений
	public function departments()
	{
		$departments = Department::select()->orderBy('index_department', 'asc')->get();
		return $departments;
	}
	
	// Отчёты ОУД
    public function report_oud(Request $request)
	{
		$n = new ContractController();
		$asd = $n->forming_print($request);
		return $asd;
	}
	
	// Отчёт ПЭО (в стадии выполнения)
	public function report_peo_no_execute(Request $request)
	{
		$n = new LeadershipDepartmentController();
		$asd = $n->peoNoExecute($request, true);
		return $asd;
	}
}
