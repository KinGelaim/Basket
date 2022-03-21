@extends('layouts.header')

@section('title')
	Финансовый отдел
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Финансовый отдел' OR Auth::User()->hasRole()->role == 'Администрация' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
				@if (Route::has('login'))
					<div class="top-right links">
						
					</div>
				@endif
				
				@if($contract)
					<div class="content">
						<div class='row'>
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-2">
										<div class="form-group">
											<label for='numberContract1' class='small-text'>Номер Д/К ФКП "НТИИМ"</label>
											<input id='numberContract1' class='form-control' type='text' value='{{old("number_contract") ? old("number_contract") : $contract->number_contract}}' readonly />
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for='numberContract2'>Номер Д/К Контрагента</label>
											<input id='numberContract2' class='form-control' type='text' value='{{old("number_counterpartie_contract_reestr") ? old("number_counterpartie_contract_reestr") : $contract->number_counterpartie_contract_reestr}}' readonly />
										</div>
									</div>
									<div class="col-md-2">
										<div class='row'>
											<div class="col-md-6" style='padding: 0px;'>
												<div class='form-check'>
													@if($contract->name_works_goz == "ГОЗ")
														<input class='form-check-input' type="checkbox" checked disabled />
													@else
														<input class='form-check-input' type="checkbox" disabled />
													@endif
													<label class='form-check-label' for='gozCheck'>ГОЗ</label>
												</div>
											</div>
											<div class="col-md-6" style='padding: 0px;'>
												<div class='form-check'>
													@if($contract->name_works_goz == "Экспорт")
														<input class='form-check-input' type="checkbox" checked disabled />
													@else
														<input class='form-check-input' type="checkbox" disabled />
													@endif
													<label class='form-check-label' for='exportCheck'>Экспорт</label>
												</div>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-6" style='padding: 0px;'>
												<div class='form-check'>
													@if($contract->name_works_goz == "Межзаводские")
														<input class='form-check-input' type="checkbox" checked disabled />
													@else
														<input class='form-check-input' type="checkbox" disabled />
													@endif
													<label class='form-check-label' for='interfactoryCheck'>Межзавод.</label>
												</div>
											</div>
											<div class="col-md-6" style='padding: 0px;'>
												<div class='form-check'>
													@if($contract->name_works_goz == "Иные")
														<input class='form-check-input' type="checkbox" checked disabled />
													@else
														<input class='form-check-input' type="checkbox" disabled />
													@endif
													<label class='form-check-label' for='otherCheck'>Иные</label>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="sel1">Вид договора</span></label>
											<select class="form-control" id="sel1" disabled>
												<option>{{$contract->name_view_contract}}</option>
											</select>
										</div>
									</div>
									<div class="col-md-2">
										<label class='small-text'>Ответственный исполнитель</span></label>
										<input class='form-control' type='text' value='{{$contract->name_executor ? $contract->name_executor : $contract->executor_contract_reestr}}' disabled />
									</div>
									<div class="col-md-1" style='text-align: right; margin-top: 25px;'>
										<button class='btn btn-primary' data-toggle="modal" data-target="#scan" type='button'>Сканы</button>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
										<label>Дата Дог./Контр. на 1 л.</label>
									</div>
									<div class="col-md-10">
										<label for='sel2'>Название предприятия</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
										<input name='date_contract_on_first_reestr' class='form-control' type='text' value="{{$contract->date_contract_on_first_reestr}}" readonly />
									</div>
									<div class="col-md-8">
										<div class="form-group">
											<select class="form-control" id="sel2" disabled>
												<option>{{ $contract->name_counterpartie_contract }}</option>
											</select>
										</div>
									</div>
									<div class="col-md-2">
										<div class='form-check'>
											@if(count($states) > 0)
												@if($states[count($states) - 1]->name_state == "Заключен" OR $states[count($states) - 1]->name_state == "Заключён")
													<input id='completeContract' class='form-check-input completeCheck' type="checkbox" checked disabled />
												@else
													<input id='completeContract' class='form-check-input completeCheck' type="checkbox" disabled />
												@endif
											@else
												<input id='completeContract' class='form-check-input completeCheck' type="checkbox" disabled />
											@endif
											<label class='form-check-label' for='completeContract'>Заключен</label>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-10">
										<div class="row">
											<div class="col-md-2">
												<div class="row">
													<div class="col-md-12">
														<div class='form-group'>
															<label for='nameWork1'>Предмет</label>
															<textarea id='nameWork1' class='form-control' type="text" style="width: 100%;" rows='5' disabled>{{$contract->item_contract}}</textarea>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-2">
												<div class="row">
													<div class="col-md-12">
														<div class='form-group'>
															<label for='nameWork1'>Цель</label>
															<textarea id='nameWork1' class='form-control' type="text" style="width: 100%;" rows='5' disabled>{{$contract->name_work_contract}}</textarea>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-3">
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label>Срок исполнения</label>
															<input class='form-control' type='text' value="{{$contract->date_maturity_reestr}}" readonly />
															<label for='date_test'>До</label>
															<input class='form-control' type='text' value="{{$contract->date_e_maturity_reestr}}" readonly />
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-5">
												<div class="row">
													<div class="col-md-6">
														<label class='small-text'>Цена при заключении Д/К</label>
														<input class='form-control' type='text' value="{{is_numeric($contract->amount_begin_reestr) ? number_format($contract->amount_begin_reestr, 2, '.', ' ') : $contract->amount_begin_reestr}}" readonly />
													</div>
													<div class="col-md-6">
														<div class="row">
															<div class="col-md-12">
																@if($contract->approximate_amount_begin_reestr)
																	<input id='approximate_amount_begin_reestr' class='form-check-input' type="checkbox" checked disabled />
																	<label for='approximate_amount_begin_reestr'>Ориентировочная</label>
																@elseif($contract->fixed_amount_begin_reestr)
																	<input id='fixed_amount_begin_reestr' class='form-check-input' type="checkbox" checked disabled />
																	<label for='fixed_amount_begin_reestr'>Фиксированная</label>
																@endif
															</div>
														</div>
														<div class="row">
															<div class="col-md-12">
																@if($contract->vat_begin_reestr)
																	<input id='vat_begin_reestr' class='form-check-input' type="checkbox" checked disabled />
																@else
																	<input id='vat_begin_reestr' class='form-check-input' type="checkbox" disabled />
																@endif
																<label for='vat_begin_reestr'>НДС</label>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-6">
														<label>Сумма Д/К</label>
														<input class='form-control' type='text' value="{{is_numeric($contract->amount_reestr) ? number_format($contract->amount_reestr, 2, '.', ' ') : $contract->amount_reestr}}" readonly />
													</div>
													<div class="col-md-6">
														<div class="row">
															<div class="col-md-12">
																@if($contract->approximate_amount_reestr)
																	<input id='approximate_amount_reestr' class='form-check-input' type="checkbox" checked disabled />
																	<label for='approximate_amount_reestr'>Ориентировочная</label>
																@elseif($contract->fixed_amount_reestr)
																	<input id='fixed_amount_reestr' class='form-check-input' type="checkbox" checked disabled />
																	<label for='fixed_amount_reestr'>Фиксированная</label>
																@endif
															</div>
														</div>
														<div class="row">
															<div class="col-md-12">
																@if($contract->vat_reestr)
																	<input id='vat_reestr' class='form-check-input' type="checkbox" checked disabled />
																@else
																	<input id='vat_reestr' class='form-check-input' type="checkbox" disabled />
																@endif
																<label for='vat_reestr'>НДС</label>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="row">
													<div class="col-md-3">
														<label for='date_b_contract_reestr'>Срок действия ДК с</label>
													</div>
													<div class="col-md-3">
														<label for='date_e_contract_reestr'>по</label>
													</div>
												</div>
												<div class="row">
													<div class="col-md-3">
														<input class='form-control' type='text' value="{{$contract->date_b_contract_reestr}}" readonly />
													</div>
													<div class="col-md-3">
														<input class='form-control' type='text' value="{{$contract->date_e_contract_reestr}}" readonly />
													</div>
													<div class="col-md-6">
														<input class='form-control' type='text' value="{{$contract->date_contract_reestr}}" readonly />
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="row">
											<div class="col-md-12" style='text-align: right;'>
												<button class='btn btn-primary' data-toggle="modal" data-target="#history_states" type='button' style='width: 150px; float: right;'>История Д/К</button>
											</div>
											<div class="col-md-12" style='text-align: right;'>
												<button type='button' class="btn btn-primary" style="float: right; width: 150px; margin-top: 5px;" data-toggle="modal" data-target="#work_states">Выполнение работ</button>
											</div>
											<div class="col-md-12" style='text-align: right;'>
												<button type='button' class="btn btn-primary btn-href" style="float: right; width: 150px; margin-top: 5px;" href="{{route('department.peo.show_additional_documents', $contract->id)}}">Догов. мат</button>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="row">
													<div class="col-md-12">
														<label>Дебет</label>
														<input class='form-control' type='text' value="{{$contract->name_view_work != 'Комплектация' ? (($amount_invoices - ($amount_payments) + $amount_returns) > 0 ? $amount_invoices - ($amount_payments) + $amount_returns : 0) : (($amount_payments - $amount_invoices + $amount_returns) > 0 ? ($amount_payments) - $amount_invoices + $amount_returns : 0)}} р." readonly />
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<label>Кредит</label>
														<input class='form-control' type='text' value="{{$contract->name_view_work != 'Комплектация' ? (($amount_payments - $amount_invoices - $amount_returns) > 0 ? ($amount_payments) - $amount_invoices - $amount_returns : 0) : (($amount_invoices - ($amount_payments) - $amount_returns) > 0 ? $amount_invoices - ($amount_payments) - $amount_returns : 0)}} р." readonly />
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class='row'>
									@if($scores)
										<div class="col-md-12" style='padding-right: 0px;'>
											<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
												<thead>
													<tr>
														<th colspan='4' style='text-align: center;'>СЧЕТ НА ОПЛАТУ</th>
													</tr>
													<tr>
														<th>№ сч</th>
														<th>Дата</th>
														<th>Сумма</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													<?php $pr_amount = 0; ?>
													@foreach($scores as $score)
														<tr class="rowsContract rowsInvoiceClick cursorPointer" href="{{ route('department.invoice.show',$score->id) }}">
															<td>
																{{ $score->number_invoice }}
															</td>
															<td>
																{{ $score->date_invoice ? date('d.m.Y', strtotime($score->date_invoice)) : '' }}
															</td>
															<td>
																{{ $score->amount_p_invoice }}
															</td>
															<td>
																@if(Auth::User()->hasRole()->role != 'Администрация')
																	<button class='btn btn-danger btn-href' href="{{ route('department.invoice.delete', $score->id) }}"><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -247px; background-position-y: -136px;"></span></button>
																@endif
															</td>
														</tr>
														<?php $pr_amount += str_replace(' ','', $score->amount_p_invoice); ?>
													@endforeach
													<tr>
														<td></td>
														<td><b>Итого:</b></td>
														<td><b>{{ number_format($pr_amount, 2, '.', '&nbsp;') }}</b></td>
														<td></td>
													</tr>
												</tbody>
											</table>
										</div>
									@endif
								</div>
								<div class="row">
									<div class="col-md-12">
										@if(Auth::User()->hasRole()->role != 'Администрация')
											<button class='btn btn-primary btn-href' href="{{route('department.invoice.create_score',$contract->id)}}">Добавить счет</button>
										@endif
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class='row'>
									@if($prepayments)
										<div class="col-md-12">
											<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
												<thead>
													<tr>
														<th colspan='4' style='text-align: center;'>СЧЕТ НА АВАНС</th>
													</tr>
													<tr>
														<th>№ сч</th>
														<th>Дата</th>
														<th>Сумма</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													<?php $pr_amount = 0; ?>
													@foreach($prepayments as $prepayment)
														<tr class="rowsContract rowsInvoiceClick cursorPointer" href="{{ route('department.invoice.show',$prepayment->id) }}">
															<td>
																{{ $prepayment->number_invoice }}
															</td>
															<td>
																{{ $prepayment->date_invoice ? date('d.m.Y', strtotime($prepayment->date_invoice)) : '' }}
															</td>
															<td>
																{{ $prepayment->amount_p_invoice }}
															</td>
															<td>
																@if(Auth::User()->hasRole()->role != 'Администрация')
																	<button class='btn btn-danger btn-href' href="{{ route('department.invoice.delete', $prepayment->id) }}"><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -247px; background-position-y: -136px;"></span></button>
																@endif
															</td>
														</tr>
														<?php $pr_amount += str_replace(' ','', $prepayment->amount_p_invoice); ?>
													@endforeach
													<tr>
														<td></td>
														<td><b>Итого:</b></td>
														<td><b>{{ number_format($pr_amount, 2, '.', '&nbsp;') }}</b></td>
														<td></td>
													</tr>
												</tbody>
											</table>
										</div>
									@endif
								</div>
								<div class="row">
									<div class="col-md-12">
										@if(Auth::User()->hasRole()->role != 'Администрация')
											<button class='btn btn-primary btn-href' href="{{route('department.invoice.create_prepayment',$contract->id)}}">Добавить аванс</button>
										@endif
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class='row'>
									@if($invoices)
										<div class="col-md-12">
											<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
												<thead>
													<tr>
														<th colspan='5' style='text-align: center;'>ОКАЗАНО УСЛУГ (Счет-фактура)</th>
													</tr>
													<tr>
														<th>№ акта</th>
														<th>№ сч</th>
														<th>Дата</th>
														<th>Сумма</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													<?php $pr_amount = 0; ?>
													@foreach($invoices as $invoice)
														<tr class="rowsContract rowsInvoiceClick cursorPointer" href="{{ route('department.invoice.show',$invoice->id) }}">
															<td>
																{{ $invoice->number_deed_invoice }}
															</td>
															<td>
																{{ $invoice->number_invoice }}
															</td>
															<td>
																{{ $invoice->date_invoice ? date('d.m.Y', strtotime($invoice->date_invoice)) : '' }}
															</td>
															<td>
																{{ $invoice->amount_p_invoice }}
															</td>
															<td>
																@if(Auth::User()->hasRole()->role != 'Администрация')
																	<button class='btn btn-danger btn-href' href="{{ route('department.invoice.delete', $invoice->id) }}"><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -247px; background-position-y: -136px;"></span></button>
																@endif
															</td>
														</tr>
														<?php $pr_amount += str_replace(' ','', $invoice->amount_p_invoice); ?>
													@endforeach
													<tr>
														<td></td>
														<td></td>
														<td><b>Итого:</b></td>
														<td><b>{{ number_format($pr_amount, 2, '.', '&nbsp;') }}</b></td>
														<td></td>
													</tr>
												</tbody>
											</table>
										</div>
									@endif
								</div>
								<div class="row">
									<div class="col-md-12">
										@if(Auth::User()->hasRole()->role != 'Администрация')
											<button class='btn btn-primary btn-href' href="{{route('department.invoice.create_invoice',$contract->id)}}">Добавить счет-фактуру</button>
										@endif
									</div>
								</div>
							</div>
						</div>
						<div class='row'>
							<div class="col-md-2">
							</div>
							<div class="col-md-4">
								<div class='row'>
									@if($payments)
										<div class="col-md-12">
											<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
												<thead>
													<tr>
														<th colspan='5' style='text-align: center;'>ОПЛАТА ОКАЗАННЫХ УСЛУГ</th>
													</tr>
													<tr>
														<th>№ сч</th>
														<th>Дата</th>
														<th>Сумма</th>
														<th>Аванс</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													<?php $pr_amount = 0; ?>
													@foreach($payments as $payment)
														<tr class="rowsContract rowsInvoiceClick cursorPointer" href="{{ route('department.invoice.show',$payment->id) }}">
															<td>
																{{ $payment->number_invoice }}
															</td>
															<td>
																{{ $payment->date_invoice ? date('d.m.Y', strtotime($payment->date_invoice)) : '' }}
															</td>
															<td>
																{{ $payment->amount_p_invoice }}
															</td>
															<td>
																<?php if($payment->is_prepayment_invoice == 1) echo "<input class='form-check-input' type='checkbox' checked disabled />"; else echo "<input class='form-check-input' type='checkbox' disabled />"; ?>
															</td>
															<td>
																@if(Auth::User()->hasRole()->role != 'Администрация')
																	<button class='btn btn-danger btn-href' href="{{ route('department.invoice.delete', $payment->id) }}"><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -247px; background-position-y: -136px;"></span></button>
																@endif
															</td>
														</tr>
														<?php $pr_amount += str_replace(' ','', $payment->amount_p_invoice); ?>
													@endforeach
													<tr>
														<td></td>
														<td><b>Итого:</b></td>
														<td><b>{{ number_format($pr_amount, 2, '.', '&nbsp;') }}</b></td>
														<td></td>
														<td></td>
													</tr>
												</tbody>
											</table>
										</div>
									@endif
								</div>
								<div class="row">
									<div class="col-md-12">
										@if(Auth::User()->hasRole()->role != 'Администрация')
											<button class='btn btn-primary btn-href' href="{{route('department.invoice.create_payment',$contract->id)}}">Добавить оплату</button>
										@endif
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class='row'>
									@if($returns)
										<div class="col-md-12">
											<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
												<thead>
													<tr>
														<th colspan='4' style='text-align: center;'>Возврат</th>
													</tr>
													<tr>
														<th>№ сч</th>
														<th>Дата</th>
														<th>Сумма</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													<?php $pr_amount = 0; ?>
													@foreach($returns as $return)
														<tr class="rowsContract rowsInvoiceClick cursorPointer" href="{{ route('department.invoice.show',$return->id) }}">
															<td>
																{{ $return->number_invoice }}
															</td>
															<td>
																{{ $return->date_invoice ? date('d.m.Y', strtotime($return->date_invoice)) : '' }}
															</td>
															<td>
																{{ $return->amount_p_invoice }}
															</td>
															<td>
																@if(Auth::User()->hasRole()->role != 'Администрация')
																	<button class='btn btn-danger btn-href' href="{{ route('department.invoice.delete', $return->id) }}"><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -247px; background-position-y: -136px;"></span></button>
																@endif
															</td>
														</tr>
														<?php $pr_amount += str_replace(' ','', $return->amount_p_invoice); ?>
													@endforeach
													<tr>
														<td></td>
														<td><b>Итого:</b></td>
														<td><b>{{ number_format($pr_amount, 2, '.', '&nbsp;') }}</b></td>
														<td></td>
													</tr>
												</tbody>
											</table>
										</div>
									@endif
								</div>
								<div class="row">
									<div class="col-md-12">
										@if(Auth::User()->hasRole()->role != 'Администрация')
											<button class='btn btn-primary btn-href' href="{{route('department.invoice.create_return',$contract->id)}}">Добавить возврат</button>
										@endif
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Модальное окно выполнение работ -->
					<div class="modal fade" id="work_states" tabindex="-1" role="dialog" aria-labelledby="workStatesModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form method='POST' action="{{ route('department.ekonomic.new_state',$contract->id)}}">
									{{csrf_field()}}
									<div class="modal-header">
										<h5 class="modal-title" id="workStatesModalLabel">Выполнение работ</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<div class='row'>
											<div id='table_history_states' class='col-md-12'>
												<table class="table" style='margin: 0 auto;'>
													<thead>
														<tr>
															<th>Наименование</th>
															<th>Дата</th>
															<th>Автор</th>
														</tr>
													</thead>
													<tbody>
														@if(isset($work_states))
															@foreach($work_states as $state)
																<tr class='rowsContract'>
																	<td>{{$state->name_state}}<br/>{{$state->comment_state}}</td>
																	<td>{{$state->date_state}}</td>
																	<td>{{$state->surname . ' ' . mb_substr($state->name, 0, 1) . '.' . mb_substr($state->patronymic, 0, 1) . '.'}}</td>
																</tr>
															@endforeach
														@endif
													</tbody>
												</table>
											</div>
										</div>									
									</div>
									<div class="modal-footer">
										<button id='btn_add_new_history' type="submit" class="btn btn-primary" style='display: none;'>Добавить</button>
										<button id='btn_destroy_state' type="submit" class="btn btn-danger" style='display: none;'>Удалить</button>
										<button id='btn_close_new_history_states' type="button" class="btn btn-secondary" style='display: none;'>Закрыть</button>
										<button id='btn_close_new_history' type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<!-- Модальное окно история состояний -->
					<div class="modal fade" id="history_states" tabindex="-1" role="dialog" aria-labelledby="historyStatesModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form method='POST' action="{{ route('department.ekonomic.new_state',$contract->id)}}">
									{{csrf_field()}}
									<div class="modal-header">
										<h5 class="modal-title" id="historyStatesModalLabel">История состояний договора</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<div class='row'>
											@if(count($states) > 0)
												<div id='table_history_states' class='col-md-12'>
													<table class="table" style='margin: 0 auto;'>
														<thead>
															<tr>
																<th>Наименование</th>
																<th>Дата</th>
																<th>Автор</th>
															</tr>
														</thead>
														<tbody>
															@foreach($states as $state)
																<tr class='rowsContract' id_state='{{$state->id}}' 
																													name_state='{{$state->name_state}}' 
																													date_state='{{$state->date_state}}' 
																													action_state='{{ route("department.ekonomic.update_state",$contract->id)}}'
																													destroy_state='{{ route("department.ekonomic.destroy_state",$state->id)}}'>
																	<td>{{$state->name_state}}<br/>{{$state->comment_state}}</td>
																	<td>{{$state->date_state}}</td>
																	<td>{{$state->surname . ' ' . mb_substr($state->name, 0, 1) . '.' . mb_substr($state->patronymic, 0, 1) . '.'}}</td>
																</tr>
															@endforeach
														</tbody>
													</table>
												</div>
											@endif
										</div>									
									</div>
									<div class="modal-footer">
										<button id='btn_add_new_history' type="submit" class="btn btn-primary" style='display: none;'>Добавить</button>
										<button id='btn_destroy_state' type="submit" class="btn btn-primary" style='display: none;'>Удалить</button>
										<button id='btn_close_new_history_states' type="button" class="btn btn-secondary" style='display: none;'>Закрыть</button>
										<button id='btn_close_new_history' type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<!-- Модальное окно резолюции -->
					<div class="modal fade" id="scan" tabindex="-1" role="dialog" aria-labelledby="scanModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form id='form_all_application' method='POST' action=''>
									{{csrf_field()}}
									<div class="modal-header">
										<h5 class="modal-title" id="scanModalLabel">Скан договора</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div id='all_aplication' class="modal-body">
										<div class='form-group row'>
											<div class="col-md-3">
												<label>Резолюция:</label>
											</div>
											<div class="col-md-4">
												
											</div>
											<div class="col-md-4">
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-12">
												<select id='resolution_list' name='resolution_list' class='form-control {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
													@if(count($resolutions) > 0)
														@foreach($resolutions as $resolution)
															<option value='http://{{$resolution->path_resolution}}' download_href='resolution_download/{{$resolution->id}}'>{{$resolution->real_name_resolution}}</option>
														@endforeach
													@else
														<option></option>
													@endif
												</select>
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
												<button id='open_resolution' type='button' class='btn btn-secondary' style='width: 122px;'>Открыть скан</button>
											</div>
											<div class="col-md-3">
												<!--<button id='download_resolution' type='button' class='btn btn-secondary' style='width: 122px;'>Скачать скан</button>-->
											</div>
											<div class="col-md-6">
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				@else
					
				@endif
				<script src="{{ asset('js/history.js') }}"></script>
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
