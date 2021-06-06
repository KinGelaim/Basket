@extends('layouts.header')

@section('title')
	Печать
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Администрация' OR Auth::User()->surname == 'Бастрыкова' OR Auth::User()->surname == 'Гуринова')
				<div class="content">
					@if($contracts)
						<div class='row'>
							<div class="col-md-12">
								<button class='btn btn-primary' id='createExcel' real_name_table='ПЭО'>Сформировать Excel</button>
							</div>
						</div>
						<div class='row' style='text-align: center;'>
							<div class="col-md-12">
								Оперативный отчет о заключении договоров по оборонной продукции (услугам)
							</div>
						</div>
						<div class='row' style='text-align: right;'>
							<div class="col-md-12">
								по состоянию на {{date('d.m.Y', time())}} г.
							</div>
						</div>
						<table id='resultTable' class="table table-bordered tablePrint" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
							<thead style='text-align: center;'>
								<tr>
									<th rowspan='4'>Номер договора</th>
									<th rowspan='4'>Наименование работ</th>
									<th rowspan='4'>ГОЗ, экспорт</th>
									<th rowspan='4'>Вид работ</th>
									<th rowspan='4'>Всего договоров</th>
									<th colspan='7'>из них</th>
									<th rowspan='4'>Состояние заключения договоров (где и когда)</th>
									<th colspan='3'>Выполнение</th>
									<th colspan='3'>Выставление счетов (служебная ПЭО)</th>
									<th colspan='3'>Оплата с НДС, тыс.руб.</th>
								</tr>
								<tr>
									<th colspan='3'>Заключено</th>
									<th colspan='3'>На оформлении</th>
									<th rowspan='3'>Крупная сделка</th>
									<th colspan='2'>Сумма с НДС, тыс.руб.</th>
									<th rowspan='3'>Выполнен в полном объеме / в стадии выполнения</th>
									<th colspan='3'>Сумма с НДС, тыс. руб.</th>
									<th rowspan='3'>Всего</th>
									<th colspan='2'>в том числе</th>
								</tr>
								<tr>
									<th rowspan='2'>кол-во догов.</th>
									<th colspan='2'>сумма с НДС, тыс.руб.</th>
									<th rowspan='2'>кол-во догов.</th>
									<th colspan='2'>сумма с НДС, тыс.руб.</th>
									<th rowspan='2'>всего по договору</th>
									<th rowspan='2'>в т.ч. на {{date('Y',time())}}г.</th>
									<th colspan='2'>аванс</th>
									<th>счет- фактура</th>
									<th>аванс</th>
									<th>оплата</th>
								</tr>
								<tr>
									<th>всего по дог., тыс.руб.</th>
									<th>в т.ч. на {{date('Y',time())}}г.</th>
									<th>всего по дог., тыс.руб.</th>
									<th>в т.ч. на {{date('Y',time())}}г.</th>
									<th>% аванс по договору</th>
									<th>сумма</th>
									<th>сумма</th>
									<th>аванс</th>
									<th>окончат. расчет</th>
								</tr>
							</thead>
							<tbody>
								<?php
									//Для подведение полноценных итогов по всем заводам
									$result_all_contract = 0; 
									$result_all_concluded_contract = 0;
									$result_amount_concluded_contract = 0;
									$result_year_amount_concluded_contract = 0;
									$result_all_formalization_contract = 0;
									$result_amount_formalization_contract = 0;
									$result_year_amount_formalization_contract = 0;
									$result_big_count_contract = 0;
									$result_amount_implementation_contract = 0;
									$result_year_amount_implementation_contract = 0;
									$result_all_prepayment_contract = 0;
									$result_all_payment_contract = 0;
									$result_all_prepayment_reestr = 0;
									$result_all_invoices = 0;
								?>
								@foreach($contracts as $key=>$value)
									<tr>
										<td colspan='2'><b>{{$key}}</b></td>
										<td colspan='20'></td>
										@foreach($value as $key2=>$value2)
											<tr>
												<td colspan='2'><b>{{$key2}}</b></td>
												<td colspan='20'></td>
											</tr>
											<?php
												//Для подведения итогов по заводу
												$all_contract = 0; 
												$all_concluded_contract = 0;
												$amount_concluded_contract = 0;
												$year_amount_concluded_contract = 0;
												$all_formalization_contract = 0;
												$amount_formalization_contract = 0;
												$year_amount_formalization_contract = 0;
												$big_count_contract = 0;
												$amount_implementation_contract = 0;
												$year_amount_implementation_contract = 0;
												$all_prepayment_contract = 0;
												$all_payment_contract = 0;
												$all_prepayment_reestr = 0;
												$all_invoices = 0;
											?>
											@foreach($value2 as $contract)
												<tr>
													<td>{{$contract->number_contract}}</td>
													<td>{{$contract->item_contract}}</td>
													<td style='text-align: center;'>{{$contract->name_works_goz}}</td>
													<td style='text-align: center;'>{{$contract->name_view_contract}}</td>
													<td style='text-align: center;'>1</td>
													<!-- Заключено -->
													<td style='text-align: center;'>
														<?php 
															if(count($contract['states'])>0)
																if($contract['states'][count($contract['states'])-1]->name_state == 'Заключен' OR $contract['states'][count($contract['states'])-1]->name_state == 'Заключён') {
																	$all_concluded_contract++;
																	echo '1';
																}
														?>
													</td>
													<td style='text-align: right;'>
														<?php
															$is_complete = false;
															if(count($contract['states'])>0)
																if($contract['states'][count($contract['states'])-1]->name_state == 'Заключен' OR $contract['states'][count($contract['states'])-1]->name_state == 'Заключён') {
																	$is_complete = true;
																	if(is_numeric($contract->amount_reestr))
																		$amount_concluded_contract += $contract->amount_reestr;
																	if(is_numeric($contract->amount_reestr))
																		echo number_format($contract->amount_reestr/1000, 2, ',', '&nbsp;');
																	else
																		echo $contract->amount_reestr;
																}
														?>
													</td>
													<td>
														<?php
															if($is_complete)
															{
																if(is_numeric($contract->amount_year_reestr)){
																	$year_amount_concluded_contract += $contract->amount_year_reestr;
																	echo number_format($contract->amount_year_reestr/1000, 2, ',', '&nbsp;');
																}else{
																	echo $contract->amount_year_reestr;
																}
															}
														?>
													</td>
													<!-- На оформлении -->
													<td style='text-align: center;'>
														<?php 
															if(count($contract['states'])>0) {
																if($contract['states'][count($contract['states'])-1]->name_state != 'Заключен' AND $contract['states'][count($contract['states'])-1]->name_state != 'Заключён') {
																	$all_formalization_contract++;
																	echo '1';
																}
															}
															else {
																$all_formalization_contract++;
																echo '1';
															}
														?>
													</td>
													<td style='text-align: right;'>
														<?php
															$is_no_complete = false;
															if(count($contract['states'])>0) {
																if($contract['states'][count($contract['states'])-1]->name_state != 'Заключен' AND $contract['states'][count($contract['states'])-1]->name_state != 'Заключён') {
																	$is_no_complete = true;
																	if(is_numeric($contract->amount_reestr))
																		$amount_formalization_contract += $contract->amount_reestr;
																	if(is_numeric($contract->amount_reestr))
																		echo number_format($contract->amount_reestr/1000, 2, ',', '&nbsp;');
																	else
																		echo $contract->amount_reestr;
																}
															}
															else {
																$is_no_complete = true;
																if(is_numeric($contract->amount_reestr))
																	$amount_formalization_contract += $contract->amount_reestr;
																if(is_numeric($contract->amount_reestr))
																	echo number_format($contract->amount_reestr/1000, 2, ',', '&nbsp;');
																else
																	echo $contract->amount_reestr;
															}
														?>
													</td>
													<td>
														<?php
															if($is_no_complete)
															{
																if(is_numeric($contract->amount_year_reestr)){
																	$year_amount_formalization_contract += $contract->amount_year_reestr;
																	echo number_format($contract->amount_year_reestr/1000, 2, ',', '&nbsp;');
																}else{
																	echo $contract->amount_year_reestr;
																}
															}
														?>
													</td>
													<!-- Крупная сделка -->
													<td style='text-align: center;'>
														<?php
															if($contract->big_deal_reestr != null) {
																echo '1';
																$big_count_contract++;
															}
														?>
													</td>
													<!-- Состояние заключения -->
													<td style='text-align: center;'>
														@if($contract->states)
															@if(count($contract->states) > 0)
																{{$contract['states'][count($contract['states'])-1]->name_state}} <br/> {{$contract['states'][count($contract['states'])-1]->comment_state}}
															@endif
														@endif
													</td>
													<!-- Выполнение -->
													<td style='text-align: right;'>
														{{ is_numeric($contract->amount_acts) ? number_format($contract->amount_acts/1000, 2, ',', '&nbsp;') : $contract->amount_acts}}
														<?php
															$amount_implementation_contract += $contract->amount_acts;
														?>
													</td>
													<td style='text-align: right;'>
														{{ is_numeric($contract->year_amount_acts) ? number_format($contract->year_amount_acts/1000, 2, ',', '&nbsp;') : $contract->year_amount_acts}}
														<?php
															$year_amount_implementation_contract += $contract->year_amount_acts;
														?>
													</td>
													<td style='text-align: center;' style='width: 200px;'>
														<?php 
															/*if($contract->comment_implementation_contract === null)
																echo 'Выполнен';
															else
																echo $contract->comment_implementation_contract;*/
														?>
														@if($contract->work_states)
															@if(count($contract->work_states) > 0)
																{{$contract['work_states'][count($contract['work_states'])-1]->name_state}} <br/> {{$contract['work_states'][count($contract['work_states'])-1]->comment_state}}
															@endif
														@endif
													</td>
													<!-- Выставление счетов -->
													<td style='text-align: center;'>{{$contract->percent_prepayment_reestr}}</td>
													<td style='text-align: right;'>{{is_numeric($contract->prepayment_reestr) ? number_format($contract->prepayment_reestr / 1000, 2, ',', '&nbsp;') : $contract->prepayment_reestr}}</td>
													<td style='text-align: right;'>{{is_numeric($contract->invoices) ? number_format($contract->invoices / 1000, 2, ',', '&nbsp;') : $contract->invoices}}</td>
													<?php
														if(is_numeric($contract->prepayment_reestr))
															$all_prepayment_reestr += $contract->prepayment_reestr;
														if(is_numeric($contract->invoices))
															$all_invoices += $contract->invoices;
													?>
													<!-- Оплата -->
													<td style='text-align: right;'>{{$contract->prepayments + $contract->payments != 0 ? number_format(($contract->prepayments + $contract->payments)/1000, 2, ',', ' ') : ''}}</td>
													<td style='text-align: right;'>{{$contract->prepayments != 0 ? number_format($contract->prepayments/1000, 2, ',', '&nbsp;') : ''}}</td>
													<?php 
														$all_prepayment_contract += $contract->prepayments;
													?>
													<td style='text-align: right;'>{{$contract->payments != 0 ? number_format($contract->payments/1000, 2, ',', '&nbsp;') : ''}}</td>
													<?php
														$all_payment_contract += $contract->payments;
													?>
												</tr>
												@foreach($contract->additional_agreements as $additional_agreement)
													<tr>
														<td>{{$additional_agreement->name_protocol}}</td>
														<td>{{$additional_agreement->name_work_protocol}}</td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td style='text-align: center;' style='width: 200px;'>
															@if($additional_agreement->states)
																@if(count($additional_agreement->states) > 0)
																	{{$additional_agreement['states'][count($additional_agreement['states'])-1]->name_state}} <br/> {{$additional_agreement['states'][count($additional_agreement['states'])-1]->comment_state}}
																@endif
															@endif
														</td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
													</tr>
												@endforeach
												<?php
													//Считаем количество контрактов всего
													$all_contract++;
												?>
											@endforeach
											<!-- Итоги по всем контрактам -->
											<tr>
												<td colspan='2' style='text-align: right;'><b>Итого</b></td>
												<td></td>
												<td></td>
												<td style='text-align: center;'><b>{{$all_contract}}</b></td>
												<!-- Заключено -->
												<td style='text-align: center;'><b>{{$all_concluded_contract}}</b></td>
												<td style='text-align: right;'><b>{{is_numeric($amount_concluded_contract) ? number_format($amount_concluded_contract/1000, 2, ',', '&nbsp;') : $amount_concluded_contract}}</b></td>
												<td style='text-align: right;'><b>{{is_numeric($year_amount_concluded_contract) ? number_format($year_amount_concluded_contract/1000, 2, ',', '&nbsp;') : $year_amount_concluded_contract}}</b></td>
												<!-- На оформлении -->
												<td style='text-align: center;'><b>{{$all_formalization_contract}}</b></td>
												<td style='text-align: right;'><b>{{is_numeric($amount_formalization_contract) ? number_format($amount_formalization_contract/1000, 2, ',', '&nbsp;') : $amount_formalization_contract}}</b></td>
												<td style='text-align: right;'><b>{{is_numeric($year_amount_formalization_contract) ? number_format($year_amount_formalization_contract/1000, 2, ',', '&nbsp;') : $year_amount_formalization_contract}}</b></td>
												<!-- Крупная сделка -->
												<td style='text-align: center;'><b>{{$big_count_contract}}</b></td>
												<td style='text-align: center;'></td>
												<!-- Выполнение -->
												<td style='text-align: right;'><b>{{is_numeric($amount_implementation_contract) ? number_format($amount_implementation_contract/1000, 2, ',', '&nbsp;') : $amount_implementation_contract}}</b></td>
												<td style='text-align: right;'><b>{{is_numeric($year_amount_implementation_contract) ? number_format($year_amount_implementation_contract/1000, 2, ',', '&nbsp;') : $year_amount_implementation_contract}}</b></td>
												<td></td>
												<!-- Выставлено счетов -->
												<td></td>
												<td style='text-align: right;'><b>{{is_numeric($all_prepayment_reestr) ? number_format($all_prepayment_reestr / 1000, 2, ',', '&nbsp;') : $all_prepayment_reestr}}</b></td>
												<td style='text-align: right;'><b>{{is_numeric($all_invoices) ? number_format($all_invoices / 1000, 2, ',', '&nbsp;') : $all_invoices}}</b></td>
												<!-- Оплата -->
												<td style='text-align: right;'><b>{{number_format(($all_prepayment_contract/1000+$all_payment_contract/1000), 2, ',', '&nbsp;')}}</b></td>
												<td style='text-align: right;'><b>{{number_format($all_prepayment_contract/1000, 2, ',', '&nbsp;')}}</b></td>
												<td style='text-align: right;'><b>{{number_format($all_payment_contract/1000, 2, ',', '&nbsp;')}}</b></td>
											</tr>
											<?php
												//Для подведение полноценных итогов по всем заводам
												$result_all_contract += $all_contract; 
												$result_all_concluded_contract += $all_concluded_contract;
												$result_amount_concluded_contract += $amount_concluded_contract;
												$result_year_amount_concluded_contract += $year_amount_concluded_contract;
												$result_all_formalization_contract += $all_formalization_contract;
												$result_amount_formalization_contract += $amount_formalization_contract;
												$result_year_amount_formalization_contract += $year_amount_formalization_contract;
												$result_big_count_contract += $big_count_contract;
												$result_amount_implementation_contract += $amount_implementation_contract;
												$result_year_amount_implementation_contract += $year_amount_implementation_contract;
												$result_all_prepayment_contract += $all_prepayment_contract;
												$result_all_payment_contract += $all_payment_contract;
												$result_all_prepayment_reestr += $all_prepayment_reestr;
												$result_all_invoices += $all_invoices;
											?>
										@endforeach
									</tr>
								@endforeach
								<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
								<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
								<!-- Итоги по ГОЗ, Экспорт, Межзаводские -->
								<tr>
									<td><b>Всего</b></td>
									<td></td>
									<td></td>
									<td></td>
									<td style='text-align: center;'><b>{{$result_all_contract}}</b></td>
									<!-- Заключено -->
									<td style='text-align: center;'><b>{{$result_all_concluded_contract}}</b></td>
									<td style='text-align: right;'><b>{{is_numeric($result_amount_concluded_contract) ? number_format($result_amount_concluded_contract/1000, 2, ',', '&nbsp;') : $result_amount_concluded_contract}}</b></td>
									<td style='text-align: right;'><b>{{is_numeric($result_year_amount_concluded_contract) ? number_format($result_year_amount_concluded_contract/1000, 2, ',', '&nbsp;') : $result_year_amount_concluded_contract}}</b></td>
									<!-- На оформлении -->
									<td style='text-align: center;'><b>{{$result_all_formalization_contract}}</b></td>
									<td style='text-align: right;'><b>{{is_numeric($result_amount_formalization_contract) ? number_format($result_amount_formalization_contract/1000, 2, ',', '&nbsp;') : $result_amount_formalization_contract}}</b></td>
									<td style='text-align: right;'><b>{{is_numeric($result_year_amount_formalization_contract) ? number_format($result_year_amount_formalization_contract/1000, 2, ',', '&nbsp;') : $result_year_amount_formalization_contract}}</b></td>
									<!-- Крупная сделка -->
									<td style='text-align: center;'><b>{{$result_big_count_contract}}</b></td>
									<td style='text-align: center;'></td>
									<!-- Выполнение -->
									<td style='text-align: right;'><b>{{is_numeric($result_amount_implementation_contract) ? number_format($result_amount_implementation_contract/1000, 2, ',', '&nbsp;') : $result_amount_implementation_contract}}</b></td>
									<td style='text-align: right;'><b>{{is_numeric($result_year_amount_implementation_contract) ? number_format($result_year_amount_implementation_contract/1000, 2, ',', '&nbsp;') : $result_year_amount_implementation_contract}}</b></td>
									<td></td>
									<!-- Выставлено счетов -->
									<td></td>
									<td style='text-align: right;'><b>{{is_numeric($result_all_prepayment_reestr) ? number_format($result_all_prepayment_reestr / 1000, 2, ',', '&nbsp;') : $result_all_prepayment_reestr}}</b></td>
									<td style='text-align: right;'><b>{{is_numeric($result_all_invoices) ? number_format($result_all_invoices / 1000, 2, ',', '&nbsp;') : $result_all_invoices}}</b></td>
									<!-- Оплата -->
									<td style='text-align: right;'><b>{{number_format(($result_all_prepayment_contract/1000+$result_all_payment_contract/1000), 2, ',', '&nbsp;')}}</b></td>
									<td style='text-align: right;'><b>{{number_format($result_all_prepayment_contract/1000, 2, ',', '&nbsp;')}}</b></td>
									<td style='text-align: right;'><b>{{number_format($result_all_payment_contract/1000, 2, ',', '&nbsp;')}}</b></td>
								</tr>
								<?php $count_name_work = 1; ?>
								@foreach($itogs as $key=>$value)
									<tr>
										<td style='text-align: right;'>{{$count_name_work++}}</td>
										<td style='text-align: center;'>{{$key}}</td>
										<td></td>
										<td></td>
										<td style='text-align: center;'>{{$value['result_all_contract']}}</td>
										<!-- Заключено -->
										<td style='text-align: center;'>{{$value['result_all_concluded_contract']}}</td>
										<td style='text-align: right;'>{{is_numeric($value['result_amount_concluded_contract']) ? number_format($value['result_amount_concluded_contract']/1000, 2, ',', '&nbsp;') : $value['result_amount_concluded_contract']}}</td>
										<td style='text-align: right;'>{{is_numeric($value['result_year_amount_concluded_contract']) ? number_format($value['result_year_amount_concluded_contract']/1000, 2, ',', '&nbsp;') : $value['result_year_amount_concluded_contract']}}</td>
										<!-- На оформлении -->
										<td style='text-align: center;'>{{$value['result_all_formalization_contract']}}</td>
										<td style='text-align: right;'>{{is_numeric($value['result_amount_formalization_contract']) ? number_format($value['result_amount_formalization_contract']/1000, 2, ',', '&nbsp;') : $value['result_amount_formalization_contract']}}</td>
										<td style='text-align: right;'>{{is_numeric($value['result_year_amount_formalization_contract']) ? number_format($value['result_year_amount_formalization_contract']/1000, 2, ',', '&nbsp;') : $value['result_year_amount_formalization_contract']}}</td>
										<!-- Крупная сделка -->
										<td style='text-align: center;'>{{$value['result_big_count_contract']}}</td>
										<td style='text-align: center;'></td>
										<!-- Выполнение -->
										<td style='text-align: right;'>{{is_numeric($value['result_amount_implementation_contract']) ? number_format($value['result_amount_implementation_contract']/1000, 2, ',', '&nbsp;') : $value['result_amount_implementation_contract']}}</td>
										<td style='text-align: right;'>{{is_numeric($value['result_year_amount_implementation_contract']) ? number_format($value['result_year_amount_implementation_contract']/1000, 2, ',', '&nbsp;') : $value['result_year_amount_implementation_contract']}}</td>
										<td></td>
										<!-- Выставлено счетов -->
										<td></td>
										<td style='text-align: right;'>{{is_numeric($value['result_all_prepayment_reestr']) ? number_format($value['result_all_prepayment_reestr'] / 1000, 2, ',', '&nbsp;') : $value['result_all_prepayment_reestr']}}</td>
										<td style='text-align: right;'>{{is_numeric($value['result_all_invoices']) ? number_format($value['result_all_invoices'] / 1000, 2, ',', '&nbsp;') : $value['result_all_invoices']}}</td>
										<!-- Оплата -->
										<td style='text-align: right;'>{{number_format(($value['result_all_prepayment_contract']/1000+$value['result_all_payment_contract']/1000), 2, ',', '&nbsp;')}}</td>
										<td style='text-align: right;'>{{number_format($value['result_all_prepayment_contract']/1000, 2, ',', '&nbsp;')}}</td>
										<td style='text-align: right;'>{{number_format($value['result_all_payment_contract']/1000, 2, ',', '&nbsp;')}}</td>
									</tr>
								@endforeach
								<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
								<tr><td>В том числе:</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
								@foreach($view_itogs as $key=>$value)
									<tr>
										<td>{{$key}}</td>
										<td style='text-align: center;'></td>
										<td></td>
										<td></td>
										<td style='text-align: center;'>{{$value['result_all_contract']}}</td>
										<!-- Заключено -->
										<td style='text-align: center;'>{{$value['result_all_concluded_contract']}}</td>
										<td style='text-align: right;'>{{is_numeric($value['result_amount_concluded_contract']) ? number_format($value['result_amount_concluded_contract']/1000, 2, ',', '&nbsp;') : $value['result_amount_concluded_contract']}}</td>
										<td style='text-align: right;'>{{is_numeric($value['result_year_amount_concluded_contract']) ? number_format($value['result_year_amount_concluded_contract']/1000, 2, ',', '&nbsp;') : $value['result_year_amount_concluded_contract']}}</td>
										<!-- На оформлении -->
										<td style='text-align: center;'>{{$value['result_all_formalization_contract']}}</td>
										<td style='text-align: right;'>{{is_numeric($value['result_amount_formalization_contract']) ? number_format($value['result_amount_formalization_contract']/1000, 2, ',', '&nbsp;') : $value['result_amount_formalization_contract']}}</td>
										<td style='text-align: right;'>{{is_numeric($value['result_year_amount_formalization_contract']) ? number_format($value['result_year_amount_formalization_contract']/1000, 2, ',', '&nbsp;') : $value['result_year_amount_formalization_contract']}}</td>
										<!-- Крупная сделка -->
										<td style='text-align: center;'>{{$value['result_big_count_contract']}}</td>
										<td style='text-align: center;'></td>
										<!-- Выполнение -->
										<td style='text-align: right;'>{{is_numeric($value['result_amount_implementation_contract']) ? number_format($value['result_amount_implementation_contract']/1000, 2, ',', '&nbsp;') : $value['result_amount_implementation_contract']}}</td>
										<td style='text-align: right;'>{{is_numeric($value['result_year_amount_implementation_contract']) ? number_format($value['result_year_amount_implementation_contract']/1000, 2, ',', '&nbsp;') : $value['result_year_amount_implementation_contract']}}</td>
										<td></td>
										<!-- Выставлено счетов -->
										<td></td>
										<td style='text-align: right;'>{{is_numeric($value['result_all_prepayment_reestr']) ? number_format($value['result_all_prepayment_reestr'] / 1000, 2, ',', '&nbsp;') : $value['result_all_prepayment_reestr']}}</td>
										<td style='text-align: right;'>{{is_numeric($value['result_all_invoices']) ? number_format($value['result_all_invoices'] / 1000, 2, ',', '&nbsp;') : $value['result_all_invoices']}}</td>
										<!-- Оплата -->
										<td style='text-align: right;'>{{number_format(($value['result_all_prepayment_contract']/1000+$value['result_all_payment_contract']/1000), 2, ',', '&nbsp;')}}</td>
										<td style='text-align: right;'>{{number_format($value['result_all_prepayment_contract']/1000, 2, ',', '&nbsp;')}}</td>
										<td style='text-align: right;'>{{number_format($value['result_all_payment_contract']/1000, 2, ',', '&nbsp;')}}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					@endif
				</div>
			@else
				<div class="alert alert-danger">
					Недостаточно прав для просмотра данной страницы!
				</div>
			@endif
		@else
			<div class="alert alert-danger">
				Необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection
