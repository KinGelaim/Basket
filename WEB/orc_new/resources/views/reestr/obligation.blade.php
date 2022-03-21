@extends('layouts.header')

@section('title')
	Обязательства по контракту
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Отдел управления договорами' OR Auth::User()->hasRole()->role == 'Администрация')
				@if (Route::has('login'))
					<div class="top-right links">
						
					</div>
				@endif
				<style>
					.row{
						margin-top: 0px;
					}
				</style>
				<div class="content">
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label>Номер договора (контракта)</label>
								<input class='form-control' type='text' value='{{ $contract->number_contract }}' readonly />
							</div>
						</div>
						<div class="col-md-4">
							<label for='sel2'>Сокращенное наименование предприятия</label>
							<div class="form-group">
								<select class="form-control" id="sel2" disabled>
									<option>{{ $contract->name_counterpartie_contract }}</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Срок исполнения обязательств</label>
								<input class='form-control' type='text' value='{{ $contract->date_maturity_reestr}}' readonly />
							</div>
						</div>
						<!--
						<div class="col-md-2">
							<div class="form-group">
								<label>Номер контракта контрагента</label>
								<input class='form-control' type='text' value='{{ $contract->number_counterpartie_contract_reestr}}' readonly />
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="sel1">Вид Контракта</span></label>
								<select class="form-control" id="sel1" disabled>
									<option>{{$contract->name_view_work}}</option>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Исполнитель отдела/цеха</label>
								<input class='form-control' type='text' value='{{ $contract->executor_contract_reestr}}' readonly />
							</div>
						</div>
						<div class="col-md-2">
							<label for="sel4">Исполнитель ОУД</span></label>
							<select class="form-control" id="sel4" disabled>
								<option>{{$contract->name_executor}}</option>
							</select>
						</div>
						-->
					</div>
					<!--
					<div class="row">
						<div class="col-md-4">
							<div class='form-group'>
								<label for='nameWork1'>Цель заключения Дог./Контр.</label>
								<textarea id='nameWork1' class='form-control' type="text" style="width: 100%;" rows='1' disabled>{{$contract->name_work_contract}}</textarea>
							</div>
						</div>
					</div>
					-->
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label>Сумма начальная</label>
								<input class='form-control' type='text' value='{{ $contract->amount_reestr}}' readonly />
							</div>
						</div>
						<div class="col-md-1">
							@if($contract->vat_reestr)
								<input class='form-check-input' name='vat_reestr' style='margin-top: 30px;' type="checkbox" checked disabled />
							@else
								<input class='form-check-input' name='vat_reestr' style='margin-top: 30px;' type="checkbox" disabled />
							@endif
							<label>НДС</label>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label>Сумма окончательная</label>
								<input class='form-control' type='text' value='{{ $contract->amount_contract_reestr}}' readonly />
							</div>
						</div>
						<div class="col-md-7">
							<div class="form-group">
								<label>Срок действия контракта</label>
								<input class='form-control' value='{{ $contract->date_contract_reestr }}' readonly />
							</div>
						</div>
						<!--
						<div class="col-md-7">
							<div class='row'>
								<div class="col-md-6">
									<label>Аванс</label>
									<textarea class='form-control' type="text" style="width: 100%;" rows='2' readonly >{{$contract->prepayment_order_reestr}}</textarea>
								</div>
								<div class="col-md-6">
									<label>Окончат. расчет</label>
									<textarea class='form-control' type="text" style="width: 100%;" rows='2' readonly >{{$contract->score_order_reestr}}</textarea>
								</div>
							</div>
							<div class='row'>
								<div class="col-md-12">
									<label>Иное</label>
									<textarea class='form-control' type="text" style="width: 100%;" rows='2' readonly >{{$contract->payment_order_reestr}}</textarea>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<label>Контрольный срок исполнения по контракту</label>
							<textarea class='form-control' type="text" style="width: 100%;" rows='6' readonly >{{$contract->date_control_signing_contract_reestr}}</textarea>
						</div>
						-->
					</div>
					<!--
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label>Срок действия контракта</label>
							</div>
						</div>
						<div class="col-md-4">
							<div class='row'>
								<div class="col-md-1">
									<label>с</label>
								</div>
								<div class="col-md-5">
									<input class='form-control' value='{{ $contract->date_b_contract_reestr }}' readonly />
								</div>
								<div class="col-md-1">
									<label>по</label>
								</div>
								<div class="col-md-5">
									<input class='form-control' value='{{ $contract->date_e_contract_reestr }}' readonly />
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class='row'>
								<div class="col-md-12">
									<input class='form-control' value='{{ $contract->date_contract_reestr }}' readonly />
								</div>
							</div>
						</div>
					</div>
					-->
					<!-- РАЗДЕЛИТЕЛЬ -->
					<div class='row'>
						<div class="col-md-12">
							<hr style='border-color: black; margin-top: 5px;'/>
						</div>
					</div>
					<!-- ОБЯЗАТЕЛЬСТВА -->
					<div class='row'>
						<div class="col-md-10">
							<h5 style='text-align: center;'><b>Исполнение Контракта</b></h5>
						</div>
						<div class="col-md-2">
							@if(Auth::User()->hasRole()->role != 'Администрация')
								<button id='editMainContent' class='btn btn-primary' style='float: right;' onclick="$('#mainContent').css('display', 'none');$(this).css('display','none');$('#cancelEdit').css('display', 'block');$('#editContent').css('display', 'block');">Изменить исполнение контракта</button>
								<button id='cancelEdit' class='btn btn-primary' style='float: right; display: none;' onclick="$('#editContent').css('display', 'none');$(this).css('display','none');$('#editMainContent').css('display', 'block');$('#mainContent').css('display', 'block');">Отменить изменения исполнение контракта</button>
							@endif
						</div>
					</div>
					<!-- ОБЕСПЕЧЕНИЕ ЗАЯВКИ И ОБЕСПЕЧЕНИЕ ИСПОЛНЕНИЯ -->
					<div id='mainContent'>
						<div class='row'>
							<div class="col-md-12">
								<div class='row'>
									<div class="col-md-12">
										<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
											<thead>
												<tr>
													<th style='text-align: center;'>Обеспечение заявки</th>
													<th style='text-align: center;'>Обеспечение исполнения Контракта</th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-3">
										<div class='row'>
											<div class="col-md-12">
												<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
													<thead>
														<tr>
															<th colspan='4' style='text-align: center;'>Поступление обеспечения заявки до {{$obligation->date_incoming_application}}</th>
														</tr>
														<tr>
															<th style='text-align: center;'>Наименование организации</th>
															<th style='text-align: center;'>№ п/п</th>
															<th style='text-align: center;'>Дата п/п</th>
															<th style='text-align: center;'>Сумма</th>
														</tr>
													</thead>
													<tbody>
														@foreach($type1 as $type)
															<tr class='rowsContract cursorPointer' type='button' data-toggle="modal" data-target="#invoice" onclick="$('#name_organization_block').css('display','block');$('#type_incoming_obligation_block').css('display','none');$('#type_incoming_obligation').prop('disabled', true);$('#formObligationInvoice').attr('action','{{route('department.reestr.update_obligation_invoice', $type->id)}}');$('#invoiceModalLabel').text('Редактирование поступление обеспечения заявки');$('#type_invoice').val('1');$('#number_pp_label').text('№ п/п:');$('#date_pp_label').text('Дата п/п:');$('#amount_label').text('Сумма п/п:');$('#name_organization').val('{{$type->name_organization}}');$('#number_pp').val('{{$type->number_pp}}');$('#date_pp').val('{{$type->date_pp}}');$('#amount').val('{{$type->amount}}');$('#btnDeleteInvoice').css('display', 'inline-block');$('#btnDeleteInvoice').attr('href','{{route('department.reestr.delete_obligation_invoice', $type->id)}}');">
																<td>{{$type->name_organization}}</td>
																<td>{{$type->number_pp}}</td>
																<td>{{$type->date_pp}}</td>
																<td>{{$type->amount}}</td>
															</tr>
														@endforeach
													</tbody>
												</table>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												@if(Auth::User()->hasRole()->role != 'Администрация')
													<button type='button' data-toggle="modal" data-target="#invoice"  class='btn btn-primary' onclick="$('#name_organization_block').css('display','block');$('#type_incoming_obligation_block').css('display','none');$('#type_incoming_obligation').prop('disabled', true);$('#formObligationInvoice').attr('action','{{route('department.reestr.create_obligation_invoice', $contract->id)}}');$('#invoiceModalLabel').text('Добавление поступление обеспечения заявки');$('#type_invoice').val('1');$('#number_pp_label').text('№ п/п:');$('#date_pp_label').text('Дата п/п:');$('#amount_label').text('Сумма п/п:');$('#name_organization').val('');$('#number_pp').val('');$('#date_pp').val('');$('#amount').val('');$('#btnDeleteInvoice').css('display','none');$('#btnDeleteInvoice').attr('href','');">Добавить поступление обеспечения заявки</button>
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class='row'>
											<div class="col-md-12">
												<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
													<thead>
														<tr>
															<th colspan='3' style='text-align: center;'>Возврат обеспечения заявки до {{$obligation->date_outgoing_application}}</th>
														</tr>
														<tr>
															<th style='text-align: center;'>№ п/п</th>
															<th style='text-align: center;'>Дата п/п</th>
															<th style='text-align: center;'>Сумма</th>
														</tr>
													</thead>
													<tbody>
														@foreach($type2 as $type)
															<tr class='rowsContract cursorPointer' type='button' data-toggle="modal" data-target="#invoice" onclick="$('#name_organization_block').css('display','none');$('#type_incoming_obligation_block').css('display','none');$('#type_incoming_obligation').prop('disabled', true);$('#formObligationInvoice').attr('action','{{route('department.reestr.update_obligation_invoice', $type->id)}}');$('#invoiceModalLabel').text('Редактирование возврата обеспечения заявки');$('#type_invoice').val('2');$('#number_pp_label').text('№ п/п:');$('#date_pp_label').text('Дата п/п:');$('#amount_label').text('Сумма п/п:');$('#name_organization').val('');$('#number_pp').val('{{$type->number_pp}}');$('#date_pp').val('{{$type->date_pp}}');$('#amount').val('{{$type->amount}}');$('#btnDeleteInvoice').css('display', 'inline-block');$('#btnDeleteInvoice').attr('href','{{route('department.reestr.delete_obligation_invoice', $type->id)}}');">
																<td>{{$type->number_pp}}</td>
																<td>{{$type->date_pp}}</td>
																<td>{{$type->amount}}</td>
															</tr>
														@endforeach
													</tbody>
												</table>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												@if(Auth::User()->hasRole()->role != 'Администрация')
													<button type='button' data-toggle="modal" data-target="#invoice"  class='btn btn-primary' onclick="$('#name_organization_block').css('display','none');$('#type_incoming_obligation_block').css('display','none');$('#type_incoming_obligation').prop('disabled', true);$('#formObligationInvoice').attr('action','{{route('department.reestr.create_obligation_invoice', $contract->id)}}');$('#invoiceModalLabel').text('Добавление возврата обеспечения заявки');$('#type_invoice').val('2');$('#number_pp_label').text('№ п/п:');$('#date_pp_label').text('Дата п/п:');$('#amount_label').text('Сумма п/п:');$('#name_organization').val('');$('#number_pp').val('');$('#date_pp').val('');$('#amount').val('');$('#btnDeleteInvoice').css('display','none');$('#btnDeleteInvoice').attr('href','');">Добавить возврат обеспечения заявки</button>
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class='row'>
											<div class="col-md-12">
												<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
													<thead>
														<tr>
															<th colspan='3' style='text-align: center;'>Поступление обеспечения исполнения до {{$obligation->date_incoming_complete}}</th>
														</tr>
														<tr>
															<th style='text-align: center;'>№</th>
															<th style='text-align: center;'>Дата</th>
															<th style='text-align: center;'>Сумма</th>
														</tr>
													</thead>
													<tbody>
														@foreach($type3 as $type)
															<tr class='rowsContract cursorPointer' type='button' data-toggle="modal" data-target="#invoice" onclick="$('#name_organization_block').css('display','none');$('#type_incoming_obligation_block').css('display','block');$('#type_incoming_obligation').prop('disabled', false);$('#formObligationInvoice').attr('action','{{route('department.reestr.update_obligation_invoice', $type->id)}}');$('#invoiceModalLabel').text('Редактирование поступления обеспечения исполнения');$('#type_invoice').val('3');$('#number_pp_label').text('№:');$('#date_pp_label').text('Дата:');$('#amount_label').text('Сумма:');$('#name_organization').val('');$('#type_incoming_obligation option[value=\'{{$type->type_incoming_obligation}}\']').prop('selected',true);$('#number_pp').val('{{$type->number_pp}}');$('#date_pp').val('{{$type->date_pp}}');$('#amount').val('{{$type->amount}}');$('#btnDeleteInvoice').css('display', 'inline-block');$('#btnDeleteInvoice').attr('href','{{route('department.reestr.delete_obligation_invoice', $type->id)}}');">
																<td>{{$type->number_pp}}</td>
																<td>{{$type->date_pp}}</td>
																<td>{{$type->amount}}</td>
															</tr>
														@endforeach
													</tbody>
												</table>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												@if(Auth::User()->hasRole()->role != 'Администрация')
													<button type='button' data-toggle="modal" data-target="#invoice"  class='btn btn-primary' onclick="$('#name_organization_block').css('display','none');$('#type_incoming_obligation_block').css('display','block');$('#type_incoming_obligation').prop('disabled', false);$('#formObligationInvoice').attr('action','{{route('department.reestr.create_obligation_invoice', $contract->id)}}');$('#invoiceModalLabel').text('Добавление поступления обеспечения исполнения');$('#type_invoice').val('3');$('#number_pp_label').text('№:');$('#date_pp_label').text('Дата:');$('#amount_label').text('Сумма:');$('#name_organization').val('');$('#type_incoming_obligation option[value=\'0\']').prop('selected',true);$('#number_pp').val('');$('#date_pp').val('');$('#amount').val('');$('#btnDeleteInvoice').css('display','none');$('#btnDeleteInvoice').attr('href','');">Добавить поступление обеспечения исполнения</button>
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class='row'>
											<div class="col-md-12">
												<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
													<thead>
														<tr>
															<th colspan='3' style='text-align: center;'>Возврат обеспечения исполнения до {{$obligation->date_outgoing_complete}}</th>
														</tr>
														<tr>
															<th style='text-align: center;'>№ п/п</th>
															<th style='text-align: center;'>Дата п/п</th>
															<th style='text-align: center;'>Сумма</th>
														</tr>
													</thead>
													<tbody>
														@foreach($type4 as $type)
															<tr class='rowsContract cursorPointer' type='button' data-toggle="modal" data-target="#invoice" onclick="$('#name_organization_block').css('display','none');$('#type_incoming_obligation_block').css('display','none');$('#type_incoming_obligation').prop('disabled', true);$('#formObligationInvoice').attr('action','{{route('department.reestr.update_obligation_invoice', $type->id)}}');$('#invoiceModalLabel').text('Редактирование возврата обеспечения исполнения');$('#type_invoice').val('4');$('#number_pp_label').text('№ п/п:');$('#date_pp_label').text('Дата п/п:');$('#amount_label').text('Сумма п/п:');$('#name_organization').val('');$('#number_pp').val('{{$type->number_pp}}');$('#date_pp').val('{{$type->date_pp}}');$('#amount').val('{{$type->amount}}');$('#btnDeleteInvoice').css('display', 'inline-block');$('#btnDeleteInvoice').attr('href','{{route('department.reestr.delete_obligation_invoice', $type->id)}}');">
																<td>{{$type->number_pp}}</td>
																<td>{{$type->date_pp}}</td>
																<td>{{$type->amount}}</td>
															</tr>
														@endforeach
													</tbody>
												</table>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												@if(Auth::User()->hasRole()->role != 'Администрация')
													<button type='button' data-toggle="modal" data-target="#invoice"  class='btn btn-primary' onclick="$('#name_organization_block').css('display','none');$('#type_incoming_obligation_block').css('display','none');$('#type_incoming_obligation').prop('disabled', true);$('#formObligationInvoice').attr('action','{{route('department.reestr.create_obligation_invoice', $contract->id)}}');$('#invoiceModalLabel').text('Добавление возврата обеспечения исполнения');$('#type_invoice').val('4');$('#number_pp_label').text('№ п/п:');$('#date_pp_label').text('Дата п/п:');$('#amount_label').text('Сумма п/п:');$('#name_organization').val('');$('#number_pp').val('');$('#date_pp').val('');$('#amount').val('');$('#btnDeleteInvoice').css('display','none');$('#btnDeleteInvoice').attr('href','');">Добавить возврат обеспечения исполнения</button>
												@endif
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- ФАКТИЧЕСКОЕ ИСПОЛНЕНИЕ -->
						<div class='row'>
							<div class="col-md-12">
								<div class='row'>
									<div class="col-md-12">
										<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
											<thead>
												<tr>
													<th style='text-align: center;'>Фактическое исполнение</th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-3">
										<div class='row'>
											<div class="col-md-12">
												<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
													<thead>
														<tr>
															<th style='text-align: center;'>№ счет-фактуры</th>
															<th style='text-align: center;'>Дата счет-фактуры</th>
															<th style='text-align: center;'>Сумма</th>
														</tr>
													</thead>
													<tbody>
														<?php $amount_pr = 0; ?>
														@foreach($type5 as $type)
															<tr class='rowsContract cursorPointer' type='button' data-toggle="modal" data-target="#invoice" onclick="$('#name_organization_block').css('display','none');$('#type_incoming_obligation_block').css('display','none');$('#type_incoming_obligation').prop('disabled', true);$('#formObligationInvoice').attr('action','{{route('department.reestr.update_obligation_invoice', $type->id)}}');$('#invoiceModalLabel').text('Редактирование счет-фактуры');$('#type_invoice').val('5');$('#number_pp_label').text('№ счет-фактуры:');$('#date_pp_label').text('Дата счет-фактуры:');$('#amount_label').text('Сумма:');$('#name_organization').val('');$('#number_pp').val('{{$type->number_pp}}');$('#date_pp').val('{{$type->date_pp}}');$('#amount').val('{{$type->amount}}');$('#btnDeleteInvoice').css('display', 'inline-block');$('#btnDeleteInvoice').attr('href','{{route('department.reestr.delete_obligation_invoice', $type->id)}}');">
																<td>{{$type->number_pp}}</td>
																<td>{{$type->date_pp}}</td>
																<td>{{is_numeric($type->amount) ? number_format($type->amount,2,',',' ') : $type->amount}}</td>
															</tr>
															<?php if(is_numeric($type->amount)) $amount_pr += $type->amount; ?>
														@endforeach
														<tr>
															<td></td>
															<td style='text-align: right;'>Итого:</td>
															<td>{{number_format($amount_pr,2,',',' ')}}</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												@if(Auth::User()->hasRole()->role != 'Администрация')
													<button type='button' data-toggle="modal" data-target="#invoice"  class='btn btn-primary' onclick="$('#name_organization_block').css('display','none');$('#type_incoming_obligation_block').css('display','none');$('#type_incoming_obligation').prop('disabled', true);$('#formObligationInvoice').attr('action','{{route('department.reestr.create_obligation_invoice', $contract->id)}}');$('#invoiceModalLabel').text('Добавление счет-фактуры');$('#type_invoice').val('5');$('#number_pp_label').text('№ счет-фактуры:');$('#date_pp_label').text('Дата счет-фактуры:');$('#amount_label').text('Сумма:');$('#name_organization').val('');$('#number_pp').val('');$('#date_pp').val('');$('#amount').val('');$('#btnDeleteInvoice').css('display','none');$('#btnDeleteInvoice').attr('href','');">Добавить счет-фактуру</button>
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class='row'>
											<div class="col-md-12">
												<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
													<thead>
														<tr>
															<th style='text-align: center;'>№ тов. накладной</th>
															<th style='text-align: center;'>Дата тов. накладной</th>
															<th style='text-align: center;'>Сумма</th>
														</tr>
													</thead>
													<tbody>
														<?php $amount_pr = 0; ?>
														@foreach($type6 as $type)
															<tr class='rowsContract cursorPointer' type='button' data-toggle="modal" data-target="#invoice" onclick="$('#name_organization_block').css('display','none');$('#type_incoming_obligation_block').css('display','none');$('#type_incoming_obligation').prop('disabled', true);$('#formObligationInvoice').attr('action','{{route('department.reestr.update_obligation_invoice', $type->id)}}');$('#invoiceModalLabel').text('Редактирование товарной накладной');$('#type_invoice').val('6');$('#number_pp_label').text('№ товарной накладной:');$('#date_pp_label').text('Дата товарной накладной:');$('#amount_label').text('Сумма:');$('#name_organization').val('');$('#number_pp').val('{{$type->number_pp}}');$('#date_pp').val('{{$type->date_pp}}');$('#amount').val('{{$type->amount}}');$('#btnDeleteInvoice').css('display', 'inline-block');$('#btnDeleteInvoice').attr('href','{{route('department.reestr.delete_obligation_invoice', $type->id)}}');">
																<td>{{$type->number_pp}}</td>
																<td>{{$type->date_pp}}</td>
																<td>{{is_numeric($type->amount) ? number_format($type->amount,2,',',' ') : $type->amount}}</td>
															</tr>
															<?php if(is_numeric($type->amount)) $amount_pr += $type->amount; ?>
														@endforeach
														<tr>
															<td></td>
															<td style='text-align: right;'>Итого:</td>
															<td>{{number_format($amount_pr,2,',',' ')}}</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												@if(Auth::User()->hasRole()->role != 'Администрация')
													<button type='button' data-toggle="modal" data-target="#invoice"  class='btn btn-primary' onclick="$('#name_organization_block').css('display','none');$('#type_incoming_obligation_block').css('display','none');$('#type_incoming_obligation').prop('disabled', true);$('#formObligationInvoice').attr('action','{{route('department.reestr.create_obligation_invoice', $contract->id)}}');$('#invoiceModalLabel').text('Добавление товарной накладной');$('#type_invoice').val('6');$('#number_pp_label').text('№ товарной накладной:');$('#date_pp_label').text('Дата товарной накладной:');$('#amount_label').text('Сумма:');$('#name_organization').val('');$('#number_pp').val('');$('#date_pp').val('');$('#amount').val('');$('#btnDeleteInvoice').css('display','none');$('#btnDeleteInvoice').attr('href','');">Добавить товарную накладную</button>
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class='row'>
											<div class="col-md-12">
												<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
													<thead>
														<tr>
															<th style='text-align: center;'>№ п/п</th>
															<th style='text-align: center;'>Дата п/п</th>
															<th style='text-align: center;'>Сумма</th>
														</tr>
													</thead>
													<tbody>
														<?php $amount_pr = 0; ?>
														@foreach($type7 as $type)
															<tr class='rowsContract cursorPointer' type='button' data-toggle="modal" data-target="#invoice" onclick="$('#name_organization_block').css('display','none');$('#type_incoming_obligation_block').css('display','none');$('#type_incoming_obligation').prop('disabled', true);$('#formObligationInvoice').attr('action','{{route('department.reestr.update_obligation_invoice', $type->id)}}');$('#invoiceModalLabel').text('Редактирование п/п');$('#type_invoice').val('7');$('#number_pp_label').text('№ п/п:');$('#date_pp_label').text('Дата п/п:');$('#amount_label').text('Сумма п/п:');$('#name_organization').val('');$('#number_pp').val('{{$type->number_pp}}');$('#date_pp').val('{{$type->date_pp}}');$('#amount').val('{{$type->amount}}');$('#btnDeleteInvoice').css('display', 'inline-block');$('#btnDeleteInvoice').attr('href','{{route('department.reestr.delete_obligation_invoice', $type->id)}}');">
																<td>{{$type->number_pp}}</td>
																<td>{{$type->date_pp}}</td>
																<td>{{is_numeric($type->amount) ? number_format($type->amount,2,',',' ') : $type->amount}}</td>
															</tr>
															<?php if(is_numeric($type->amount)) $amount_pr += $type->amount; ?>
														@endforeach
														<tr>
															<td></td>
															<td style='text-align: right;'>Итого:</td>
															<td>{{number_format($amount_pr,2,',',' ')}}</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												@if(Auth::User()->hasRole()->role != 'Администрация')
													<button type='button' data-toggle="modal" data-target="#invoice" class='btn btn-primary' onclick="$('#name_organization_block').css('display','none');$('#type_incoming_obligation_block').css('display','none');$('#type_incoming_obligation').prop('disabled', true);$('#formObligationInvoice').attr('action','{{route('department.reestr.create_obligation_invoice', $contract->id)}}');$('#invoiceModalLabel').text('Добавление п/п');$('#type_invoice').val('7');$('#number_pp_label').text('№ п/п:');$('#date_pp_label').text('Дата п/п:');$('#amount_label').text('Сумма п/п:');$('#name_organization').val('');$('#number_pp').val('');$('#date_pp').val('');$('#amount').val('');$('#btnDeleteInvoice').css('display','none');$('#btnDeleteInvoice').attr('href','');">Добавить п/п</button>
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class='row'>
											<div class="col-md-12">
												<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
													<thead>
														<tr>
															<th style='text-align: center;'>№ Акта</th>
															<th style='text-align: center;'>Дата Акта</th>
															<th style='text-align: center;'>Сумма</th>
														</tr>
													</thead>
													<tbody>
														<?php $amount_pr = 0; ?>
														@foreach($type8 as $type)
															<tr class='rowsContract cursorPointer' type='button' data-toggle="modal" data-target="#invoice" onclick="$('#name_organization_block').css('display','none');$('#type_incoming_obligation_block').css('display','none');$('#type_incoming_obligation').prop('disabled', true);$('#formObligationInvoice').attr('action','{{route('department.reestr.update_obligation_invoice', $type->id)}}');$('#invoiceModalLabel').text('Редактирование акта');$('#type_invoice').val('8');$('#number_pp_label').text('№ акта:');$('#date_pp_label').text('Дата акта:');$('#amount_label').text('Сумма акта:');$('#name_organization').val('');$('#number_pp').val('{{$type->number_pp}}');$('#date_pp').val('{{$type->date_pp}}');$('#amount').val('{{$type->amount}}');$('#btnDeleteInvoice').css('display', 'inline-block');$('#btnDeleteInvoice').attr('href','{{route('department.reestr.delete_obligation_invoice', $type->id)}}');">
																<td>{{$type->number_pp}}</td>
																<td>{{$type->date_pp}}</td>
																<td>{{is_numeric($type->amount) ? number_format($type->amount,2,',',' ') : $type->amount}}</td>
															</tr>
															<?php if(is_numeric($type->amount)) $amount_pr += $type->amount; ?>
														@endforeach
														<tr>
															<td></td>
															<td style='text-align: right;'>Итого:</td>
															<td>{{number_format($amount_pr,2,',',' ')}}</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												@if(Auth::User()->hasRole()->role != 'Администрация')
													<button type='button' data-toggle="modal" data-target="#invoice"  class='btn btn-primary' onclick="$('#name_organization_block').css('display','none');$('#type_incoming_obligation_block').css('display','none');$('#type_incoming_obligation').prop('disabled', true);$('#formObligationInvoice').attr('action','{{route('department.reestr.create_obligation_invoice', $contract->id)}}');$('#invoiceModalLabel').text('Добавление акта');$('#type_invoice').val('8');$('#number_pp_label').text('№ акта:');$('#date_pp_label').text('Дата акта:');$('#amount_label').text('Сумма акта:');$('#name_organization').val('');$('#number_pp').val('');$('#date_pp').val('');$('#amount').val('');$('#btnDeleteInvoice').css('display','none');$('#btnDeleteInvoice').attr('href','');">Добавить акт</button>
												@endif
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class='row'>
							<div class="col-md-7">
								<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
									<thead>
										<tr>
											<th style='text-align: center;'>Наименование показателя</th>
											<th style='text-align: center;'>Предусмотрено Контрактом</th>
											<th style='text-align: center;'>Исполнено</th>
											<th style='text-align: center;'>Причина не своевременного исполнения контракта</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Дата начала исполнения</td>
											<td>{{$obligation->date_b_contract}}</td>
											<td>{{$obligation->date_b_complete}}</td>
											<td>{{$obligation->date_b_comment}}</td>
										</tr>
										<tr>
											<td>Дата окончания исполнения</td>
											<td>{{$obligation->date_e_contract}}</td>
											<td>{{$obligation->date_e_complete}}</td>
											<td>{{$obligation->date_e_comment}}</td>
										</tr>
										<tr>
											<td>Цена Контракта</td>
											<td>{{$obligation->cena_contract}}</td>
											<td>{{$obligation->cena_complete}}</td>
											<td>{{$obligation->cena_comment}}</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="col-md-1">
							</div>
							<div class="col-md-3">
								<!--
								<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
									<thead>
										<tr>
											<th style='text-align: center;'>Регламентируемый срок размещения отчета</th>
											<th style='text-align: center;'>Дата размещения отчета об исполнении государственного Контракта</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>{{$obligation->date_complete}}</td>
											<td>{{$obligation->date_full_complete}}</td>
										</tr>
									</tbody>
								</table>
								-->
							</div>
						</div>
					</div>
					<div id='editContent' style='display: none;'>
						<form method='POST' action="{{route('department.reestr.update_obligation', $contract->id)}}">
							{{csrf_field()}}
							<div class='row'>
								<div class="col-md-12">
									<div class='row'>
										<div class="col-md-12">
											<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
												<thead>
													<tr>
														<th style='text-align: center;'>Обеспечение заявки</th>
														<th style='text-align: center;'>Обеспечение исполнения Контракта</th>
													</tr>
												</thead>
											</table>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-3">
											<div class='row'>
												<div class="col-md-12">
													<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
														<thead>
															<tr>
																<th colspan='4' style='text-align: center;'>Поступление обеспечения заявки до <input class='datepicker form-control' value='{{ $obligation->date_incoming_application }}' name='date_incoming_application'/></th>
															</tr>
															<tr>
																<th style='text-align: center;'>Наименование организации</th>
																<th style='text-align: center;'>№ п/п</th>
																<th style='text-align: center;'>Дата п/п</th>
																<th style='text-align: center;'>Сумма</th>
															</tr>
														</thead>
														<tbody>
															<tr><td></td><td></td><td></td><td></td></tr>
															<tr><td></td><td></td><td></td><td></td></tr>
															<tr><td></td><td></td><td></td><td></td></tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class='row'>
												<div class="col-md-12">
													<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
														<thead>
															<tr>
																<th colspan='3' style='text-align: center;'>Возврат обеспечения заявки до <input class='datepicker form-control' value='{{ $obligation->date_outgoing_application }}' name='date_outgoing_application'/></th>
															</tr>
															<tr>
																<th style='text-align: center;'>№ п/п</th>
																<th style='text-align: center;'>Дата п/п</th>
																<th style='text-align: center;'>Сумма</th>
															</tr>
														</thead>
														<tbody>
															<tr><td></td><td></td><td></td></tr>
															<tr><td></td><td></td><td></td></tr>
															<tr><td></td><td></td><td></td></tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class='row'>
												<div class="col-md-12">
													<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
														<thead>
															<tr>
																<th colspan='3' style='text-align: center;'>Поступление обеспечения исполнения до <input class='datepicker form-control' value='{{ $obligation->date_incoming_complete }}' name='date_incoming_complete'/></th>
															</tr>
															<tr>
																<th style='text-align: center;'>№ п/п</th>
																<th style='text-align: center;'>Дата п/п</th>
																<th style='text-align: center;'>Сумма</th>
															</tr>
														</thead>
														<tbody>
															<tr><td></td><td></td><td></td></tr>
															<tr><td></td><td></td><td></td></tr>
															<tr><td></td><td></td><td></td></tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class='row'>
												<div class="col-md-12">
													<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
														<thead>
															<tr>
																<th colspan='3' style='text-align: center;'>Возврат обеспечения исполнения до <input class='datepicker form-control' value='{{ $obligation->date_outgoing_complete }}' name='date_outgoing_complete'/></th>
															</tr>
															<tr>
																<th style='text-align: center;'>№ п/п</th>
																<th style='text-align: center;'>Дата п/п</th>
																<th style='text-align: center;'>Сумма</th>
															</tr>
														</thead>
														<tbody>
															<tr><td></td><td></td><td></td></tr>
															<tr><td></td><td></td><td></td></tr>
															<tr><td></td><td></td><td></td></tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class='row'>
								<div class="col-md-7">
									<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
										<thead>
											<tr>
												<th style='text-align: center;'>Наименование показателя</th>
												<th style='text-align: center;'>Предусмотрено Контрактом</th>
												<th style='text-align: center;'>Исполнено</th>
												<th style='text-align: center;'>Причина не своевременного исполнения контракта</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Дата начала исполнения</td>
												<td><input class='datepicker form-control' value='{{ $obligation->date_b_contract }}' name='date_b_contract'/></td>
												<td><input class='datepicker form-control' value='{{ $obligation->date_b_complete }}' name='date_b_complete'/></td>
												<td><input class='form-control' value='{{ $obligation->date_b_comment }}' name='date_b_comment'/></td>
											</tr>
											<tr>
												<td>Дата окончания исполнения</td>
												<td><input class='datepicker form-control' value='{{ $obligation->date_e_contract }}' name='date_e_contract'/></td>
												<td><input class='datepicker form-control' value='{{ $obligation->date_e_complete }}' name='date_e_complete'/></td>
												<td><input class='form-control' value='{{ $obligation->date_e_comment }}' name='date_e_comment'/></td>
											</tr>
											<tr>
												<td>Цена Контракта</td>
												<td><input class='form-control' value='{{ $obligation->cena_contract }}' name='cena_contract'/></td>
												<td><input class='form-control' value='{{ $obligation->cena_complete }}' name='cena_complete'/></td>
												<td><input class='form-control' value='{{ $obligation->cena_comment }}' name='cena_comment'/></td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="col-md-1">
								</div>
								<div class="col-md-3">
									<!--
									<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
										<thead>
											<tr>
												<th style='text-align: center;'>Регламентируемый срок размещения отчета</th>
												<th style='text-align: center;'>Дата размещения отчета об исполнении государственного Контракта</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><input class='datepicker form-control' value='{{ $obligation->date_complete }}' name='date_complete'/></td>
												<td><input class='datepicker form-control' value='{{ $obligation->date_full_complete }}' name='date_full_complete'/></td>
											</tr>
										</tbody>
									</table>
									-->
								</div>
							</div>
							<div class='row'>
								<button class='btn btn-primary' type='submit' style='float: right;'>Сохранить изменения</button>
							</div>
						</form>
					</div>
					<div class='row' style='height: 25px;'>
					</div>
				</div>
				<!-- Модальное окно счета -->
				<div class="modal fade" id="invoice" tabindex="-1" role="dialog" aria-labelledby="invoiceModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form id='formObligationInvoice' method='POST' action='{{route("department.reestr.create_obligation_invoice", $contract->id)}}'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="invoiceModalLabel">Добавление счета</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='modal-body'>
									<div class='row'>
										<div class='col-md-12' style='display: none;'>
											<input id='type_invoice' type='text' value='' name='type_invoice'/>
										</div>
									</div>
									<div id='name_organization_block' class='row' style='display: none;'>
										<div class="col-md-4">
											<label id='name_organization_label'>Наименование организации:</label>
										</div>
										<div class="col-md-8">
											<input id='name_organization' class='form-control' type='text' value='' name='name_organization'/>
										</div>
									</div>
									<div id='type_incoming_obligation_block' class='row' style='display: none;'>
										<div class="col-md-4">
											<label id='name_type_incoming_obligation'>Тип поступления обеспечения:</label>
										</div>
										<div class="col-md-8">
											<select id='type_incoming_obligation' class='form-control' name='type_incoming_obligation'>
												<option value=0>Платёжное поручение</option>
												<option value=1>Банковская гарантия</option>
											</select>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-4">
											<label id='number_pp_label'>№ п/п:</label>
										</div>
										<div class="col-md-8">
											<input id='number_pp' class='form-control' type='text' value='' name='number_pp' required/>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-4">
											<label id='date_pp_label'>Дата п/п:</label>
										</div>
										<div class="col-md-8">
											<input id='date_pp' class='datepicker form-control' type='text' value='' name='date_pp' required/>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-4">
											<label id='amount_label'>Сумма п/п:</label>
										</div>
										<div class="col-md-8">
											<input id='amount' class='form-control check-number' type='text' value='' name='amount' required/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
									<button id='btnDeleteInvoice' class='btn btn-danger btn-href' href='' type='button'>Удалить</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
						</div>
					</div>
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
