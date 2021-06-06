@extends('layouts.header')

@section('title')
	Печать {{$real_name_table}}
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-12">
						<button class='btn btn-primary' id='createExcel' real_name_table='{{$real_name_table}}'>Сформировать Excel</button>
					</div>
				</div>
				<div class='row' style='text-align: center;'>
					<div class="col-md-12">
						@if($real_name_table == 'Сводный отчет по договорам')
							Сводный отчет по договорам
						@elseif($real_name_table == 'Предприятия за год')
							Предприятия за {{$year}} год
						@elseif($real_name_table == 'Предприятия за год к')
							Предприятия за {{$year}} год
						@elseif($real_name_table == 'Поступление за период')
							Поступление за период с {{date('d.m.Y', strtotime($date_begin))}} по {{date('d.m.Y', strtotime($date_end))}}
						@elseif($real_name_table == 'Выполнение за период')
							Выполнение за период с {{date('d.m.Y', strtotime($date_begin))}} по {{date('d.m.Y', strtotime($date_end))}}
						@elseif($real_name_table == 'Выполнение за период (испытания)')
							Выполнение за период (испытания) с {{date('d.m.Y', strtotime($date_begin))}} по {{date('d.m.Y', strtotime($date_end))}}
						@elseif($real_name_table == 'Выполнение за период (сборка)')
							Выполнение за период (сборка) с {{date('d.m.Y', strtotime($date_begin))}} по {{date('d.m.Y', strtotime($date_end))}}
						@elseif($real_name_table == 'Выполнение за период (услуги)')
							Выполнение за период (услуги) с {{date('d.m.Y', strtotime($date_begin))}} по {{date('d.m.Y', strtotime($date_end))}}
						@elseif($real_name_table == 'Оплата за период')
							Оплата за период с {{date('d.m.Y', strtotime($date_begin))}} по {{date('d.m.Y', strtotime($date_end))}}
						@elseif($real_name_table == 'Сводный отчет по оплате')
							Сводный отчет по оплате
						@elseif($real_name_table == 'Выполнение работ по договорам')
							Выполнение работ по договорам
						@elseif($real_name_table == 'Тестовый режим')
							К плану мероприятий по организации тестового режима с {{$period1}} по {{$period2}}
						@elseif($real_name_table == 'Испытано за период')
							Испытано за период с {{$date_begin}} по {{$date_end}}
						@elseif($real_name_table == 'Номенклатура за период')
							Номенклатура за период с {{$date_begin}} по {{$date_end}}
						@endif
					</div>
				</div>
				<div class='row' style='text-align: right;'>
					<div class="col-md-12">
						по состоянию на {{date('d.m.Y', time())}} г.
					</div>
				</div>
				<table id='resultTable' class="table table-bordered tablePrint" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
					@if($real_name_table == 'Сводный отчет по договорам')
						<thead style='text-align: center;'>
							<tr>
								<th>№ дог.</th>
								<th>Контрагент</th>
								<th>Вид договора</th>
								<th>Наименование работ</th>
								<th>ГОЗ, экспорт</th>
								<th>Срок исполнения</th>
								<th>Состояние</th>
								<th>Сумма (оконч.)</th>
							</tr>
						</thead>
						<tbody>
							@foreach($contracts as $contract)
								<tr>
									<td>{{$contract->number_contract}}</td>
									<td>{{$contract->name_counterpartie_contract}}</td>
									<td>{{$contract->name_view_contract}}</td>
									<td>{{$contract->item_contract}}</td>
									<td>
										<?php 
											if($contract->name_works_goz == 'ГОЗ')
												echo $contract->name_works_goz . '<br/>' . $contract->number_counterpartie_contract_reestr;
											else
												echo $contract->name_works_goz;
										?>
									</td>
									<td>{{$contract->date_maturity_reestr ? $contract->date_maturity_reestr : $contract->date_maturity_date_reestr}}</td>
									<td>{{$contract->state != null ? $contract->state->name_state : ''}}<br/>{{$contract->state != null ? $contract->state->comment_state : ''}}</td>
									<td style='text-align: right;'>{{is_numeric($contract->amount_contract_reestr) ? number_format($contract->amount_contract_reestr, 2, ',', '&nbsp;') : $contract->amount_contract_reestr}}</td>
								</tr>
							@endforeach
						</tbody>
					@elseif($real_name_table == 'Поступление за период')
						<thead style='text-align: center;'>
							<tr>
								<th>Контрагент</th>
								<th>№ дог.</th>
								<th>№ наряда</th>
								<th>Изд-е</th>
								<th>Вид испытания</th>
								<th>Дата поступления</th>
								<th>Кол-во</th>
								<th>Дата отработки</th>
								<th>Счетн.</th>
								<th>Пристр.</th>
								<th>Прогрев.</th>
								<th>Н/СЧ</th>
								<th>Отказ</th>
								<th>Результат</th>
								<th>№ телегр.</th>
								<th>Дата телегр.</th>
								<th>№ отчета</th>
								<th>Дата отчета</th>
								<th>№ акта</th>
								<th>Дата акта</th>
								<th>Сумма акта</th>
							</tr>
						</thead>
						<tbody>
							<?php $k=0; ?>
							@foreach($second_department_tours as $isp)
								<tr>
									<td>{{$isp->name_counterpartie_contract}}</td>
									<td style='text-align: center;'>{{$isp->number_contract}}</td>
									<td style='text-align: center;'>{{$isp->number_duty}}</td>
									<td style='text-align: center;'>{{$isp->name_element}}</td>
									<td style='text-align: center;'>{{$isp->name_view_work_elements}}</td>
									<td style='text-align: center;'>{{$isp->date_incoming}}</td>
									<td style='text-align: center;'>{{$isp->count_elements}} {{$isp->name_unit}}</td>
									<td style='text-align: center;'>{{$isp->date_worked ? date('d.m.Y', strtotime($isp->date_worked)) : ''}}</td>
									<td style='text-align: center;'>{{$isp->countable}}</td>
									<td style='text-align: center;'>{{$isp->targeting}}</td>
									<td style='text-align: center;'>{{$isp->warm}}</td>
									<td style='text-align: center;'>{{$isp->uncountable}}</td>
									<td style='text-align: center;'>{{$isp->renouncement}}</td>
									<td style='text-align: center;'>{{$isp->name_result}}</td>
									<td style='text-align: center;'>{{$isp->number_telegram}}</td>
									<td style='text-align: center;'>{{$isp->date_telegram}}</td>
									<td style='text-align: center;'>{{$isp->number_report}}</td>
									<td style='text-align: center;'>{{$isp->date_report}}</td>
									<td style='text-align: center;'>{{$isp->number_act}}</td>
									<td style='text-align: center;'>{{$isp->date_act}}</td>
									<td style='text-align: center;'>{{is_numeric($isp->amount_acts) ? number_format($isp->amount_acts, 2, ',', '&nbsp;') : $isp->amount_acts}}</td>
								</tr>
							@endforeach
						</tbody>
					@elseif($real_name_table == 'Выполнение за период')
						<thead style='text-align: center;'>
							<tr>
								<th>Контрагент</th>
								<th>№ дог.</th>
								<th>№ наряда</th>
								<th>Изд-е</th>
								<th>Вид испытания/работы</th>
								<th>Кол-во</th>
								<th>Дата отработки/сдачи</th>
								<th>№ акта</th>
								<th>Дата акта</th>
								<th>Сумма акта</th>
							</tr>
						</thead>
						<tbody>
							@foreach($second_department_tours as $isp)
								<tr>
									<td>{{$isp->name_counterpartie_contract}}</td>
									<td style='text-align: center;'>{{$isp->number_contract}}</td>
									<td style='text-align: center;'>{{$isp->number_duty}}</td>
									<td style='text-align: center;'>{{$isp->name_element}}</td>
									<td style='text-align: center;'>{{$isp->name_view_work_elements}}</td>
									<td style='text-align: center;'>{{$isp->count_elements}} {{$isp->name_unit}}</td>
									<td style='text-align: center;'>{{$isp->date_worked ? date('d.m.Y', strtotime($isp->date_worked)) : ''}}</td>
									<td style='text-align: center;'>{{$isp->number_act}}</td>
									<td style='text-align: center;'>{{$isp->date_act}}</td>
									<td style='text-align: center;'>{{is_numeric($isp->amount_acts) ? number_format($isp->amount_acts, 2, ',', '&nbsp;') : $isp->amount_acts}}</td>
								</tr>
							@endforeach
							@foreach($second_department_sb_tours as $isp)
								<tr>
									<td>{{$isp->name_counterpartie_contract}}</td>
									<td style='text-align: center;'>{{$isp->number_contract}}</td>
									<td style='text-align: center;'>{{$isp->number_duty}}</td>
									<td style='text-align: center;'>{{$isp->name_element}}</td>
									<td style='text-align: center;'>{{$isp->name_view_work_elements}}</td>
									<td style='text-align: center;'>{{$isp->count_elements}} {{$isp->name_unit}}</td>
									<td style='text-align: center;'>{{$isp->date_worked ? date('d.m.Y', strtotime($isp->date_worked)) : ''}}</td>
									<td style='text-align: center;'>{{$isp->number_act}}</td>
									<td style='text-align: center;'>{{$isp->date_act}}</td>
									<td style='text-align: center;'>{{is_numeric($isp->amount_acts) ? number_format($isp->amount_acts, 2, ',', '&nbsp;') : $isp->amount_acts}}</td>
								</tr>
							@endforeach
							@foreach($second_department_us_tours as $isp)
								<tr>
									<td>{{$isp->name_counterpartie_contract}}</td>
									<td style='text-align: center;'>{{$isp->number_contract}}</td>
									<td style='text-align: center;'>{{$isp->number_duty}}</td>
									<td></td>
									<td style='text-align: center;'>{{$isp->item_contract}}</td>
									<td></td>
									<td style='text-align: center;'>{{$isp->date_worked ? date('d.m.Y', strtotime($isp->date_worked)) : ''}}</td>
									<td style='text-align: center;'>{{$isp->number_act}}</td>
									<td style='text-align: center;'>{{$isp->date_act}}</td>
									<td style='text-align: center;'>{{is_numeric($isp->amount_acts) ? number_format($isp->amount_acts, 2, ',', '&nbsp;') : $isp->amount_acts}}</td>
								</tr>
							@endforeach
						</tbody>
					@elseif($real_name_table == 'Выполнение за период (испытания)')
						<thead style='text-align: center;'>
							<tr>
								<th>Контрагент</th>
								<th>№ дог.</th>
								<th>№ наряда</th>
								<th>Изд-е</th>
								<th>Вид испытания</th>
								<th>Дата поступления</th>
								<th>Кол-во</th>
								<th>Дата отработки</th>
								<th>Счетн.</th>
								<th>Пристр.</th>
								<th>Прогрев.</th>
								<th>Н/СЧ</th>
								<th>Отказ</th>
								<th>Результат</th>
								<th>№ телегр.</th>
								<th>Дата телегр.</th>
								<th>№ отчета</th>
								<th>Дата отчета</th>
								<th>№ акта</th>
								<th>Дата акта</th>
								<th>Сумма акта</th>
							</tr>
						</thead>
						<tbody>
							<?php $k=0; $count_countable=0; $count_targeting=0; $count_warm=0; $count_uncountable=0; $count_renouncement=0; $full_amount_acts=0; ?>
							@foreach($second_department_tours as $isp)
								<tr>
									<td>{{$isp->name_counterpartie_contract}}</td>
									<td style='text-align: center;'>{{$isp->number_contract}}</td>
									<td style='text-align: center;'>{{$isp->number_duty}}</td>
									<td style='text-align: center;'>{{$isp->name_element}}</td>
									<td style='text-align: center;'>{{$isp->name_view_work_elements}}</td>
									<td style='text-align: center;'>{{$isp->date_incoming}}</td>
									<td style='text-align: center;'>{{$isp->count_elements}} {{$isp->name_unit}}</td>
									<td style='text-align: center;'>{{$isp->date_worked ? date('d.m.Y', strtotime($isp->date_worked)) : ''}}</td>
									<td style='text-align: center;'>{{$isp->countable}}</td>
									<td style='text-align: center;'>{{$isp->targeting}}</td>
									<td style='text-align: center;'>{{$isp->warm}}</td>
									<td style='text-align: center;'>{{$isp->uncountable}}</td>
									<td style='text-align: center;'>{{$isp->renouncement}}</td>
									<td style='text-align: center;'>{{$isp->name_result}}</td>
									<td style='text-align: center;'>{{$isp->number_telegram}}</td>
									<td style='text-align: center;'>{{$isp->date_telegram}}</td>
									<td style='text-align: center;'>{{$isp->number_report}}</td>
									<td style='text-align: center;'>{{$isp->date_report}}</td>
									<td style='text-align: center;'>{{$isp->number_act}}</td>
									<td style='text-align: center;'>{{$isp->date_act}}</td>
									<td style='text-align: center;'>{{is_numeric($isp->amount_acts) ? number_format($isp->amount_acts, 2, ',', '&nbsp;') : $isp->amount_acts}}</td>
								</tr>
								<?php
									$k++;
									$count_countable += $isp->countable;
									$count_targeting += $isp->targeting;
									$count_warm += $isp->warm;
									$count_uncountable += $isp->uncountable;
									$count_renouncement += $isp->renouncement;
									$full_amount_acts += $isp->amount_acts;
								?>
							@endforeach
							<tr>
								<td colspan='2' style='text-align: right;'>Всего нарядов:</td>
								<td style='text-align: center;'>{{$k}}</td>
								<td colspan='5'></td>
								<td style='text-align: center;'>{{$count_countable}}</td>
								<td style='text-align: center;'>{{$count_targeting}}</td>
								<td style='text-align: center;'>{{$count_warm}}</td>
								<td style='text-align: center;'>{{$count_uncountable}}</td>
								<td style='text-align: center;'>{{$count_renouncement}}</td>
								<td colspan='7'></td>
								<td style='text-align: center;'>{{number_format($full_amount_acts, 2, ',', '&nbsp;')}}</td>
							</tr>
						</tbody>
					@elseif($real_name_table == 'Выполнение за период (сборка)')
						<thead style='text-align: center;'>
							<tr>
								<th>Контрагент</th>
								<th>№ дог.</th>
								<th>№ наряда</th>
								<th>Изд-е</th>
								<th>Калибр</th>
								<th>Номер партии</th>
								<th>Вид работ</th>
								<th>Кол-во</th>
								<th>Дата сдачи</th>
								<th>№ формуляра</th>
								<th>Дата формуляра</th>
								<th>№ уведомления</th>
								<th>Дата уведомления</th>
								<th>№ акта</th>
								<th>Дата акта</th>
								<th>Сумма акта</th>
							</tr>
						</thead>
						<tbody>
							<?php $k=0; ?>
							@foreach($second_department_sb_tours as $isp)
								<tr>
									<td>{{$isp->name_counterpartie_contract}}</td>
									<td style='text-align: center;'>{{$isp->number_contract}}</td>
									<td style='text-align: center;'>{{$isp->number_duty}}</td>
									<td style='text-align: center;'>{{$isp->name_element}}</td>
									<td style='text-align: center;'>{{$isp->name_caliber}}</td>
									<td style='text-align: center;'>{{$isp->number_party}}</td>
									<td style='text-align: center;'>{{$isp->name_view_work_elements}}</td>
									<td style='text-align: center;'>{{$isp->count_elements}} {{$isp->name_unit}}</td>
									<td style='text-align: center;'>{{$isp->date_worked ? date('d.m.Y', strtotime($isp->date_worked)) : ''}}</td>
									<td style='text-align: center;'>{{$isp->number_logbook}}</td>
									<td style='text-align: center;'>{{$isp->date_logbook}}</td>
									<td style='text-align: center;'>{{$isp->number_notification}}</td>
									<td style='text-align: center;'>{{$isp->date_notification}}</td>
									<td style='text-align: center;'>{{$isp->number_act}}</td>
									<td style='text-align: center;'>{{$isp->date_act}}</td>
									<td style='text-align: center;'>{{is_numeric($isp->amount_acts) ? number_format($isp->amount_acts, 2, ',', '&nbsp;') : $isp->amount_acts}}</td>
								</tr>
							@endforeach
						</tbody>
					@elseif($real_name_table == 'Выполнение за период (услуги)')
						<thead style='text-align: center;'>
							<tr>
								<th>Контрагент</th>
								<th>№ дог.</th>
								<th>№ наряда</th>
								<th>Вид работ</th>
								<th>Дата отработки</th>
								<th>№ отчёта-справки</th>
								<th>Дата отчёта-справки</th>
								<th>№ акта</th>
								<th>Дата акта</th>
								<th>Сумма акта</th>
							</tr>
						</thead>
						<tbody>
							<?php $k=0; ?>
							@foreach($second_department_us_tours as $isp)
								<tr>
									<td>{{$isp->name_counterpartie_contract}}</td>
									<td style='text-align: center;'>{{$isp->number_contract}}</td>
									<td style='text-align: center;'>{{$isp->number_duty}}</td>
									<td style='text-align: center;'>{{$isp->item_contract}}</td>
									<td style='text-align: center;'>{{$isp->date_worked ? date('d.m.Y', strtotime($isp->date_worked)) : ''}}</td>
									<td style='text-align: center;'>{{$isp->number_help_report}}</td>
									<td style='text-align: center;'>{{$isp->date_help_report}}</td>
									<td style='text-align: center;'>{{$isp->number_act}}</td>
									<td style='text-align: center;'>{{$isp->date_act}}</td>
									<td style='text-align: center;'>{{is_numeric($isp->amount_acts) ? number_format($isp->amount_acts, 2, ',', '&nbsp;') : $isp->amount_acts}}</td>
								</tr>
							@endforeach
						</tbody>
					@elseif($real_name_table == 'Оплата за период')
						<thead style='text-align: center;'>
							<tr>
								<th>Контрагент</th>
								<th>№ дог.</th>
								<th>№ платежн. поруч.</th>
								<th>Дата п/п</th>
								<th>Оплата</th>
								<th>Вид договора</th>
								<th>Наименование работ</th>
							</tr>
						</thead>
						<tbody>
							<?php $k=0; ?>
							@foreach($payments as $score)
								<tr>
									<td>{{$score->name_counterpartie_contract}}</td>
									<td style='text-align: center;'>{{$score->number_contract}}</td>
									<td style='text-align: center;'>{{$score->number_invoice}}</td>
									<td style='text-align: center;'>{{$score->date_invoice ? date('d.m.Y', strtotime($score->date_invoice)) : ''}}</td>
									<td style='text-align: center;'>{{is_numeric($score->amount_p_invoice) ? number_format($score->amount_p_invoice, 2, ',', '&nbsp;') : $score->amount_p_invoice}}</td>
									<td style='text-align: center;'>{{$score->name_view_contract}}</td>
									<td>{{$score->item_contract}}</td>
								</tr>
							@endforeach
						</tbody>
					@elseif($real_name_table == 'Предприятия за год')
						<thead style='text-align: center;'>
							<tr>
								<th>№ п/п</th>
								<th>Контрагент</th>
								<th>Вид договора</th>
								<th>Наименование работ</th>
							</tr>
						</thead>
						<tbody>
							<?php $k=0; ?>
							@foreach($result as $key=>$value)
								<tr>
									<td>{{++$k}}</td>
									<td>{{$key}}</td>
									<td>
										@foreach($value['view'] as $key2=>$value2)
											{{$value2}}<br/>
										@endforeach
									</td>
									<td>
										@foreach($value['name_work'] as $key2=>$value2)
											{{$value2}}<br/>
										@endforeach
									</td>
								</tr>
							@endforeach
						</tbody>
					@elseif($real_name_table == 'Предприятия за год к')
						<thead style='text-align: center;'>
							<tr>
								<th>№ п/п</th>
								<th>Контрагент</th>
							</tr>
						</thead>
						<tbody>
							<?php $k=0; ?>
							@foreach($contracts as $key=>$value)
								<tr><td colspan='2'>{{$key}}</td><tr>
								@foreach($value as $counterpartie)
									<tr>
										<td>{{++$k}}</td>
										<td>{{$counterpartie}}</td>
									</tr>
								@endforeach
								<?php $k=0; ?>
							@endforeach
						</tbody>
					@elseif($real_name_table == 'Сводный отчет по оплате')
						<thead style='text-align: center;'>
							<tr>
								<th>№ п/п</th>
								<th>Контрагент</th>
								<th>№ дог.</th>
								<th>Наименование работ</th>
								<th>Сумма дог.</th>
								<th>Счета на оплату</th>
								<th>Аванс</th>
								<th>Сумма сч-ф</th>
								<th>Оплачено по сч-ф</th>
								<th>Оплачено всего</th>
								<!--<th>Возврат</th>-->
								<th>Дебет</th>
								<th>Кредит</th>
							</tr>
						</thead>
						<tbody>
							<?php $k=0; ?>
							@foreach($payments as $payment)
								<tr>
									<td style='text-align: center;'>{{++$k}}</td>
									<td>{{$payment->name_counterpartie_contract}}</td>
									<td style='text-align: center;'>{{$payment->number_contract}}</td>
									<td style='text-align: center;'>{{$payment->item_contract}}</td>
									<td style='text-align: right;'>{{is_numeric($payment->amount_contract_reestr) ? number_format($payment->amount_contract_reestr, 2, ',', '&nbsp;') : ($payment->amount_contract_reestr ? $payment->amount_contract_reestr : (is_numeric($payment->amount_reestr) ? number_format($payment->amount_reestr, 2, ',', '&nbsp;') : $payment->amount_reestr))}}</td>
									<td style='text-align: right;'>{{is_numeric($payment->amount_scores) ? number_format($payment->amount_scores, 2, ',', '&nbsp;') : $payment->amount_scores}}</td>
									<td style='text-align: right;'>{{is_numeric($payment->amount_prepayments) ? number_format($payment->amount_prepayments, 2, ',', '&nbsp;') : $payment->amount_prepayments}}</td>
									<td style='text-align: right;'>{{is_numeric($payment->amount_invoices) ? number_format($payment->amount_invoices, 2, ',', '&nbsp;') : $payment->amount_invoices}}</td>
									<td style='text-align: right;'>{{is_numeric($payment->amount_payments) ? number_format($payment->amount_payments, 2, ',', '&nbsp;') : $payment->amount_payments}}</td>
									<td style='text-align: right;'>{{is_numeric($payment->amount_prepayments + $payment->amount_payments) ? number_format($payment->amount_prepayments + $payment->amount_payments, 2, ',', '&nbsp;') : $payment->amount_prepayments + $payment->amount_payments}} р.</td>
									<!--<td>{{$payment->amount_returns}}</td>-->
									<td style='text-align: right;'>{{is_numeric($payment->debet) ? number_format($payment->debet, 2, ',', '&nbsp;') : $payment->debet}} р.</td>
									<td style='text-align: right;'>{{is_numeric($payment->kredit) ? number_format($payment->kredit, 2, ',', '&nbsp;') : $payment->kredit}} р.</td>
								</tr>
							@endforeach
						</tbody>
					@elseif($real_name_table == 'Выполнение работ по договорам')
						<thead style='text-align: center;'>
							<tr>
								<th>Контрагент</th>
								<th>№ договора</th>
								<th>Вид договора</th>
								<th>Наименование работ</th>
								<th>Выполнение работ</th>
								<th>Наряды</th>
								<th>Примечание</th>
								<th>Срок исполнения</th>
							</tr>
						</thead>
						<tbody>
							@foreach($contracts as $contract)
								<tr>
									<td>{{$contract->name_counterpartie_contract}}</td>
									<td>{{$contract->number_contract}}</td>
									<td>{{$contract->name_view_contract}}</td>
									<td>{{$contract->item_contract}}</td>
									<td>{{count($contract->work_states) > 0 ? $contract->work_states[count($contract->work_states) - 1]['name_state'] . ($contract->work_states[count($contract->work_states) - 1]['comment_state'] ? ' (' . $contract->work_states[count($contract->work_states) - 1]['comment_state'] . ')' : '') : ''}}</td>
									<td>
										<?php
											$prStrDutys = ''; 
											foreach($contract->dutys as $duty)
												if(isset($duty->year))
													$prStrDutys .= '<b>н.' . $duty->number_duty . '</b>, ';
												else
													$prStrDutys .= 'н.' . $duty->number_duty . ', ';
											foreach($contract->dutys_sb as $duty)
												$prStrDutys .= 'н.' . $duty->number_duty . ', ';
											foreach($contract->dutys_us as $duty)
												$prStrDutys .= 'н.' . $duty->number_duty . ', ';
											if(strlen($prStrDutys) > 0)
												$prStrDutys = mb_substr($prStrDutys, 0, -2);
											echo $prStrDutys;
										?>
									</td>
									<td>{{$contract->name_works_goz}}</td>
									<td>{{$contract->date_maturity_reestr}}</td>
								</tr>
							@endforeach
						</tbody>
					@elseif($real_name_table == 'Тестовый режим')
						<thead style='text-align: center;'>
							<tr>
								<th>Предприятие</th>
								<th>Заявка (исх.№, дата)</th>
								<th>Изделие</th>
								<th>Вид испытаний</th>
								<th>№ договора</th>
								<th style='width: 300px;'>Обеспечение</th>
								<th>Телеграмма/отчёт</th>
								<!--<th style='width: 200px;'>№ наряда</th>-->
							</tr>
						</thead>
						<tbody>
							@foreach($contracts as $key=>$value)
								<tr>
									<td colspan='7'><b>{{$key}}</b></td>
								</tr>
								@foreach($value as $contract)
									<tr>
										<td>{{$contract['name_counterpartie_contract']}}</td>
										<td>{{$contract['app_outgoing_number_reestr']}}</td>
										<td>
											@foreach($contract['name_elements'] as $name_element)
												{{$name_element}}<br/>
											@endforeach
										</td>
										<td>
											@foreach($contract['name_view_work_elements'] as $name_view_work_elements)
												{{$name_view_work_elements}}<br/>
											@endforeach
										</td>
										<td>{{$contract['number_contract']}}</td>
										<td>Материальнотехническое, метрологическое, методикотехнологическое и документарное обеспечение имеем</td>
										<td>
											@foreach($contract['number_telegrams'] as $key2=>$value2)
												{{$key2}} {{$value2['date_telegram']}}<br/>
												@if(count($value2['reports']) > 1)
													<?php
														$prStrReports = ''; 
														foreach($value2['reports'] as $number_report)
															$prStrReports .= '№' . $number_report . ', ';
														if(strlen($prStrReports) > 0)
															$prStrReports = mb_substr($prStrReports, 0, -2);
														echo 'Отчёты ' . $prStrReports . '<br/>';
													?>
												@else
													Отчёт
													@foreach($value2['reports'] as $number_report)
														№{{$number_report}}<br/>
													@endforeach
												@endif
											@endforeach
										</td>
										<!--<td>
											<?php
												/*$prStrDutys = ''; 
												foreach($contract['number_dutys'] as $number_duty)
													$prStrDutys .= 'н.' . $number_duty . ', ';
												if(strlen($prStrDutys) > 0)
													$prStrDutys = mb_substr($prStrDutys, 0, -2);
												echo $prStrDutys;*/
											?>
										</td>-->
									</tr>
								@endforeach
							@endforeach
						</tbody>
					@elseif($real_name_table == 'Испытано за период')
						<thead style='text-align: center;'>
							<tr>
								<th>№ п/п</th>
								<th>Контрольные</th>
								<th>Количество выстрелов</th>
							</tr>
						</thead>
						<tbody>
							<?php $count = 1; $all_control_count = 0; ?>
							@foreach($contracts as $key=>$value)
								<tr>
									<td><b>{{$count++}}.</b></td>
									<td><b>{{$key}}</b></td>
									<td><b>{{$contracts[$key]['count']}}</b></td>
								</tr>
								<?php $all_control_count += $contracts[$key]['count']; ?>
								@foreach($value as $key2=>$value2)
									@if($key2 != 'count')
										<tr>
											<td></td>
											<td>{{$key2}}</td>
											<td>{{$value2}}</td>
										</tr>
									@endif
								@endforeach
							@endforeach
							<tr>
								<td></td>
								<td><b>ИТОГО:</b></td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td><b>Контрольных</b></td>
								<td><b>{{$all_control_count}}</b></td>
							</tr>
							<tr>
								<td></td>
								<td>в т.ч. подрывов:</td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td>в т.ч. пусков:</td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td>в т.ч. сбросов:</td>
								<td></td>
							</tr>
							@if(isset($count_warm))
								<tr>
									<td></td>
									<td>Прогревных</td>
									<td>{{$count_warm}}</td>
								</tr>
							@endif
							@if(isset($count_tour))
								<tr>
									<td></td>
									<td>Количество нарядов</td>
									<td>{{$count_tour}}</td>
								</tr>
							@endif
							<tr>
								<td><b>{{$count++}}.</b></td>
								<td><b>ОПЫТНЫЕ:</b></td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td><b>Количество нарядов</b></td>
								<td></td>
							</tr>
							<tr><td></td><td></td><td></td></tr>
							<tr><td></td><td></td><td></td></tr>
							<tr><td></td><td></td><td></td></tr>
							<tr>
								<td><b>{{$count++}}.</b></td>
								<td><b>ПК, ПП, Н, СБ:</b></td>
								<td></td>
							</tr>
							<tr><td></td><td></td><td></td></tr>
						</tbody>
					@elseif($real_name_table == 'Номенклатура за период')
						<thead style='text-align: center;'>
							<tr>
								<th>№ п/п</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php $count = 1; ?>
							@foreach($contracts as $key=>$value)
								<tr>
									<td><b>{{$count++}}.</b></td>
									<td><b>{{$key}}</b></td>
									<td>@if(isset($contracts[$key]['elements'])){{implode(',',$contracts[$key]['elements'])}}@endif</td>
								</tr>
								@foreach($value as $key2=>$value2)
									@if($key2 != 'elements')
										<tr>
											<td></td>
											<td>{{$key2}}</td>
											<td>{{implode(',',$value2)}}</td>
										</tr>
									@endif
								@endforeach
							@endforeach
						</tbody>
					@endif
				</table>
			</div>
		@else
			<div class="alert alert-danger">
				Для просмотра данной страницы необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection
