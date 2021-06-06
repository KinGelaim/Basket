@extends('layouts.header')

@section('title')
	Реестр
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
				@if (Route::has('login'))
					<div class="top-right links">
						
					</div>
				@endif
				
				@if($contract)
					@if($contract->is_sip_contract == '1')
						<?php 
							if(Auth::User()->hasRole()->role == 'Отдел управления договорами')
								$is_disabled = 'disabled';
							else
								$is_disabled = '';
						?>
						<div class="content">
							<form method='POST' action="{{route('department.ekonomic.update_reestr', $contract->id)}}">
								{{csrf_field()}}
								<div class="row">
									<div class="col-md-3">
										<input class='form-control' style='color:red; text-align:center;' type='text' value='<?php 
											if($reestr->date_registration_project_reestr)
												if(!$reestr->date_signing_contract_reestr){
													if(time() - strtotime($reestr->date_registration_project_reestr) > 2592000)
														echo 'Не подписан более 30 дней!';
												}else
													if(strtotime($reestr->date_signing_contract_reestr) - strtotime($reestr->date_registration_project_reestr) > 2592000)
														echo 'Не был подписан более 30 дней!';
										?>' readonly />
									</div>
									<div class="col-md-2">
										<label for='amount_contract_reestr'>Сумма по договору (фиксир.)</label>
									</div>
									<div class="col-md-2">
										<input id='amount_contract_reestr' class='form-control' name='amount_contract_reestr' type='text' value='{{old("amount_contract_reestr") ? old("amount_contract_reestr") : $reestr->amount_contract_reestr}}' {{$is_disabled}}/>
									</div>
									<div class="col-md-1">
										<div class='form-check'>
											<label class='form-check-label' for='gozCheck'>ГОЗ</label>
											@if(old('goz_contract'))
												<input id='gozCheck' class='form-check-input' name='goz_contract' type="checkbox" onclick="$('#exportCheck').prop('checked', !$('#exportCheck').prop('checked'))" checked {{$is_disabled}}/>
											@else
												@if($contract->id_goz_contract == 1)
													<input id='gozCheck' class='form-check-input' name='goz_contract' type="checkbox" onclick="$('#exportCheck').prop('checked', !$('#exportCheck').prop('checked'))" checked {{$is_disabled}}/>
												@else
													<input id='gozCheck' class='form-check-input' name='goz_contract' type="checkbox" onclick="$('#exportCheck').prop('checked', !$('#exportCheck').prop('checked'))" {{$is_disabled}}/>
												@endif
											@endif
										</div>
										<div class='form-check'>
											<label class='form-check-label' for='exportCheck'>Экспорт</label>
											@if(old('goz_contract'))
												<input id='exportCheck' class='form-check-input' type="checkbox" onclick="$('#gozCheck').prop('checked', !$('#gozCheck').prop('checked'))" {{$is_disabled}}/>
											@else
												@if($contract->id_goz_contract != 1)
													<input id='exportCheck' class='form-check-input' type="checkbox" onclick="$('#gozCheck').prop('checked', !$('#gozCheck').prop('checked'))" checked {{$is_disabled}}/>
												@else
													<input id='exportCheck' class='form-check-input' type="checkbox" onclick="$('#gozCheck').prop('checked', !$('#gozCheck').prop('checked'))" {{$is_disabled}}/>
												@endif
											@endif
										</div>
									</div>
									<div class="col-md-1">
										<div class="form-check">
											@if(old('renouncement_contract'))
												<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" checked {{$is_disabled}}/>
											@else
												@if($contract->renouncement_contract == 1)
													<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" checked {{$is_disabled}}/>
												@else
													<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" {{$is_disabled}}/>
												@endif
											@endif
											<label class='form-check-label' for='break'>ОТКАЗ</label>
										</div>
										<div class="form-check">
											@if(old('archive_contract'))
												<input id='archive' class='form-check-input' name='archive_contract' type="checkbox" checked {{$is_disabled}}/>
											@else
												@if($contract->archive_contract == 1)
													<input id='archive' class='form-check-input' name='archive_contract' type="checkbox" checked {{$is_disabled}}/>
												@else
													<input id='archive' class='form-check-input' name='archive_contract' type="checkbox" {{$is_disabled}}/>
												@endif
											@endif
											<label class='form-check-label' for='archive'>АРХИВ</label>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<label>Название предприятия</label>
									</div>
									<div class="col-md-3">

									</div>
									<div class="col-md-3">

									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<select id="sel4" class='form-control {{$errors->has("id_counterpartie_contract") ? print("inputError ") : print("")}}' name='id_counterpartie_contract' {{$is_disabled}}>
												<option></option>
												@if($counterparties)
													@foreach($counterparties as $counterpartie)
														@if(old('id_counterpartie_contract'))
															@if(old('id_counterpartie_contract') == $counterpartie->id)
																<option value='{{$counterpartie->id}}' selected>{{ $counterpartie->name_full }}</option>
															@else
																@if(Auth::User()->hasRole()->role == 'Администратор')
																	<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
																@elseif(Auth::User()->hasRole()->role == 'Отдел управления договорами')
																	@if($counterpartie->is_sip_counterpartie == '0')
																		<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
																	@endif
																@else
																	@if($counterpartie->is_sip_counterpartie == '1')
																		<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
																	@endif
																@endif													
															@endif
														@else
															@if($contract->id_counterpartie_contract == $counterpartie->id)
																<option value='{{$counterpartie->id}}' selected>{{ $counterpartie->name_full }}</option>
															@else
																@if(Auth::User()->hasRole()->role == 'Администратор')
																	<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
																@elseif(Auth::User()->hasRole()->role == 'Отдел управления договорами')
																	@if($counterpartie->is_sip_counterpartie == '0')
																		<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
																	@endif
																@else
																	@if($counterpartie->is_sip_counterpartie == '1')
																		<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
																	@endif
																@endif	
															@endif
														@endif
													@endforeach
												@endif
											</select>
											@if($errors->has('id_counterpartie_contract'))
												<label class='msgError'>{{$errors->first('id_counterpartie_contract')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-12">
										<div class='row'>
											<div class="col-md-2">
												<label for='number_counterpartie_contract_reestr'>№ дог. контрагента</label>
											</div>
											<div class="col-md-10">
												<input id='number_counterpartie_contract_reestr' class='form-control {{$errors->has("number_counterpartie_contract_reestr") ? print("inputError ") : print("")}}' name='number_counterpartie_contract_reestr' value='{{old("number_counterpartie_contract_reestr") ? old("number_counterpartie_contract_reestr") : $reestr->number_counterpartie_contract_reestr}}' {{$is_disabled}}/>
												@if($errors->has('number_counterpartie_contract_reestr'))
													<label class='msgError'>{{$errors->first('number_counterpartie_contract_reestr')}}</label>
												@endif
											</div>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-3">
										<div class="row">
											<div class="col-md-12">
												<div class='form-group'>
													<label for='nameWork'>Наименование работ</label>
													<textarea id='nameWork' class='form-control {{$errors->has("name_work_contract") ? print("inputError ") : print("")}}' name='name_work_contract' type="text" style="width: 100%;" rows='4' {{$is_disabled}}>{{ old('name_work_contract') ? old('name_work_contract') : $contract->name_work_contract }}</textarea>
													@if($errors->has('name_work_contract'))
														<label class='msgError'>{{$errors->first('name_work_contract')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label for='lastCompleteContract'>Состояние заключения договора</label>
											</div>								
										</div>
										<div class="row">
											<div class="col-md-12">
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
											<div class="col-md-12">
												<div class='form-group'>
													@if(count($states) > 0)
														@if($states[count($states) - 1]->name_state == "Заключен" OR $states[count($states) - 1]->name_state == "Заключён")
															<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%; display: none;" rows='3' disabled></textarea>
														@else
															<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%;" rows='3' disabled>{{$states[count($states) - 1]->name_state}}</textarea>
														@endif
													@else
														<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%;" rows='3' disabled></textarea>
													@endif
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<button class="btn btn-primary" data-toggle="modal" data-target="#history_states" type='button'>История состояний</button>
											</div>
										</div>
									</div>
									<div class="col-md-9">
										<div class="row">
											<div class="col-md-2">
												<label for='executor_reestr'>Исполнитель</label>
												<select class='form-control' name='executor_reestr' {{$is_disabled}}>
													<option></option>
													@if(old('executor_reestr'))
														@foreach($curators as $in_curators)
															@if(old('executor_reestr') == $in_curators->id)
																<option value='{{$in_curators->id}}' selected>{{$in_curators->FIO}}</option>
															@else
																<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
															@endif
														@endforeach
													@else
														@foreach($curators as $in_curators)
															@if($reestr->executor_reestr == $in_curators->id)
																<option value='{{$in_curators->id}}' selected>{{$in_curators->FIO}}</option>
															@else
																<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
															@endif
														@endforeach
													@endif
												</select>
												@if($errors->has('executor_reestr'))
													<label class='msgError'>{{$errors->first('executor_reestr')}}</label>
												@endif
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label for="sel3">Вид работ</span></label>
													<select id="sel3" class='form-control {{$errors->has("id_view_work_contract") ? print("inputError ") : print("")}}' name='id_view_work_contract' {{$is_disabled}}>
														<option></option>
														@if($viewWorks)
															@foreach($viewWorks as $viewWork)
																@if(old('id_view_work_contract'))
																	@if(old('id_view_work_contract') == $viewWork->id)
																		<option value='{{$viewWork->id}}' selected>{{ $viewWork->name_view_work }}</option>
																	@else
																		<option value='{{$viewWork->id}}'>{{ $viewWork->name_view_work }}</option>
																	@endif
																@else
																	@if($contract->id_view_work_contract == $viewWork->id)
																		<option value='{{$viewWork->id}}' selected>{{ $viewWork->name_view_work }}</option>
																	@else
																		<option value='{{$viewWork->id}}'>{{ $viewWork->name_view_work }}</option>
																	@endif
																@endif
															@endforeach
														@endif
													</select>
													@if($errors->has('id_view_work_contract'))
														<label class='msgError'>{{$errors->first('id_view_work_contract')}}</label>
													@endif
												</div>
											</div>
											<div class="col-md-7">
												<div class='form-group row'>
													<div class='col-md-12' style='text-align: right;'>
														<button class='btn btn-primary' data-toggle="modal" data-target="#scan" type='button'>Сканы</button>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-2">
												<div class='row'>
													<div class="col-md-12">
														<label for='date_contract_reestr'>Срок действия договора</label>
													</div>
												</div>
											</div>
											<div class="col-md-5">
												<div class='row'>
													<div class="col-md-1">
														<label for='date_b_contract_reestr'>с</label>
													</div>
													<div class="col-md-5">
														<input id='date_b_contract_reestr' class='datepicker form-control {{$errors->has("date_b_contract_reestr") ? print("inputError ") : print("")}}' name='date_b_contract_reestr' value='{{ old("date_b_contract_reestr") ? old("date_b_contract_reestr") : $reestr->date_b_contract_reestr }}' {{$is_disabled}}/>
													</div>
													<div class="col-md-1">
														<label for='date_e_contract_reestr'>по</label>
													</div>
													<div class="col-md-5">
														<input id='date_e_contract_reestr' class='datepicker form-control {{$errors->has("date_e_contract_reestr") ? print("inputError ") : print("")}}' name='date_e_contract_reestr' value='{{ old("date_e_contract_reestr") ? old("date_e_contract_reestr") : $reestr->date_e_contract_reestr }}' {{$is_disabled}}/>
													</div>
												</div>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-10">
												<input id='date_contract_reestr' class='form-control {{$errors->has("date_contract_reestr") ? print("inputError ") : print("")}}' name='date_contract_reestr' value='{{ old("date_contract_reestr") ? old("date_contract_reestr") : $reestr->date_contract_reestr }}' {{$is_disabled}}/>
											</div>
										</div>
										<div class="row">
											<div class="col-md-10">
												<label for='date_maturity_reestr'>Срок исполнения обязательств</label>
												<input id='date_maturity_reestr' class='form-control {{$errors->has("date_maturity_reestr") ? print("inputError ") : print("")}}' name='date_maturity_reestr' value='{{ old("date_maturity_reestr") ? old("date_maturity_reestr") : $reestr->date_maturity_reestr }}' {{$is_disabled}}/>
											</div>
										</div>
										<div class="row">
											<div class="col-md-10">
												<div class='row'>
													<div class="col-md-1">
														<label for='amount_reestr'>Сумма (начальная)</label>
													</div>
													<div class="col-md-3">
														<input id='amount_reestr' class='form-control {{$errors->has("amount_reestr") ? print("inputError ") : print("")}}' name='amount_reestr' value='{{old("amount_reestr") ? old("amount_reestr") : $reestr->amount_reestr}}' {{$is_disabled}}/>
													</div>
													<div class="col-md-2">
														<select id="sel8" class='form-control {{$errors->has("unit_reestr") ? print("inputError ") : print("")}}' name='unit_reestr' {{$is_disabled}}>
															<option></option>
															@foreach($units as $unit)
																@if(old('unit_reestr'))
																	<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
																@else
																	@if($reestr->unit_reestr == $unit->id)
																		<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
																	@else
																		<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
																	@endif
																@endif
															@endforeach
														</select>
													</div>
													<div class="col-md-2">
														<label for='VAT'>НДС</label>
														@if(old('vat_reestr'))
															<input id='VAT' class='form-check-input' name='vat_reestr' type="checkbox" checked {{$is_disabled}}/>
														@else
															@if($reestr->vat_reestr)
																<input id='VAT' class='form-check-input' name='vat_reestr' type="checkbox" checked {{$is_disabled}}/>
															@else
																<input id='VAT' class='form-check-input' name='vat_reestr' type="checkbox" {{$is_disabled}}/>
															@endif
														@endif
													</div>
												</div>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-10">
												<input class='form-control {{$errors->has("amount_comment_reestr") ? print("inputError ") : print("")}}' name='amount_comment_reestr' value='{{old("amount_comment_reestr") ? old("amount_comment_reestr") : $reestr->amount_comment_reestr}}' {{$is_disabled}}/>
											</div>
										</div>
										<div class="row">
											<div class="col-md-10">
												<label for='payment_order_reestr'>Порядок оплаты</label>
												<textarea id='payment_order_reestr' class='form-control' type="text" style="width: 100%;" rows='3' name='payment_order_reestr' {{$is_disabled}}>{{old("payment_order_reestr") ? old("payment_order_reestr") : $reestr->payment_order_reestr}}</textarea>
											</div>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-12">
										<hr style='border-color: black;'/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-12">
										<div class='row'>
											<div class="col-md-3">
												<div class="form-group">
													<label for='numberContract'>Номер договора</label>
													<input id='numberContract' class='form-control {{$errors->has("number_contract") ? print("inputError ") : print("")}}' name='number_contract' type='text' value='{{old("number_contract") ? old("number_contract") : $contract->number_contract}}'/>
													@if($errors->has('number_contract'))
														<label class='msgError'>{{$errors->first('number_contract')}}</label>
													@endif
												</div>
											</div>
											<div class="col-md-1">
												<div class="form-group">
													<label for='year_contract'>Год</label>
													<input id='year_contract' class='form-control {{$errors->has("year_contract") ? print("inputError ") : print("")}}' name='year_contract' type='text' value='{{old("year_contract") ? old("year_contract") : $contract->year_contract}}'/>
													@if($errors->has('year_contract'))
														<label class='msgError'>{{$errors->first('year_contract')}}</label>
													@endif
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label for="sel5">Отбор поставщика</span></label>
													<select id="sel5" class='form-control {{$errors->has("selection_supplier_reestr") ? print("inputError ") : print("")}}' name='selection_supplier_reestr'>
														<option></option>
														@foreach($selection_suppliers as $selection_supplier)
															@if(old('selection_supplier_reestr'))
																@if(old('selection_supplier_reestr') == $selection_supplier->id)
																	<option value='{{$selection_supplier->id}}' selected>{{$selection_supplier->name_selection_supplier}}</option>
																@else
																	<option value='{{$selection_supplier->id}}'>{{$selection_supplier->name_selection_supplier}}</option>
																@endif
															@else
																@if($reestr->selection_supplier_reestr == $selection_supplier->id)
																	<option value='{{$selection_supplier->id}}' selected>{{$selection_supplier->name_selection_supplier}}</option>
																@else
																	<option value='{{$selection_supplier->id}}'>{{$selection_supplier->name_selection_supplier}}</option>
																@endif
															@endif
														@endforeach
													</select>
													@if($errors->has('selection_supplier_reestr'))
														<label class='msgError'>{{$errors->first('selection_supplier_reestr')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-2">
												<label for='sel6'>Тип документа</label>
												<select id="sel6" class='form-control {{$errors->has("type_document_reestr") ? print("inputError ") : print("")}}' name='type_document_reestr'>
													<option></option>
													@foreach($type_documents as $type_document)
														@if(old('type_document_reestr'))
															@if(old('type_document_reestr') == $type_document->id)
																<option value='{{$type_document->id}}' selected>{{$type_document->name_type_document}}</option>
															@else
																<option value='{{$type_document->id}}'>{{$type_document->name_type_document}}</option>
															@endif
														@else
															@if($reestr->type_document_reestr == $type_document->id)
																<option value='{{$type_document->id}}' selected>{{$type_document->name_type_document}}</option>
															@else
																<option value='{{$type_document->id}}'>{{$type_document->name_type_document}}</option>
															@endif
														@endif
													@endforeach
												</select>
											</div>
											<div class="col-md-2">
												<label for='place_save_contract_reestr'>Место хранения</label>
												<input id='place_save_contract_reestr' class='form-control {{$errors->has("place_save_contract_reestr") ? print("inputError ") : print("")}}' name='place_save_contract_reestr' value='{{old("place_save_contract_reestr") ? old("place_save_contract_reestr") : $reestr->place_save_contract_reestr}}'/>
												@if($errors->has('place_save_contract'))
													<label class='msgError'>{{$errors->first('place_save_contract')}}</label>
												@endif
											</div>
											<div class="col-md-1" style='text-align: right;'>
												<label class='form-check-label'>Оригинал договора</label>
											</div>
											<div class="col-md-2">
												<div class="row">
													<div class='col-md-5'>
														<label for='oudCheck' class='form-check-label'>ОУД</label>
													</div>
													<div class='col-md-7'>
														@if(old('oud_original_contract_reestr'))
															<input id='oudCheck' class='form-check-input' name='oud_original_contract_reestr' type="checkbox" checked />
														@else
															@if($reestr->oud_original_contract_reestr == 1)
																<input id='oudCheck' class='form-check-input' name='oud_original_contract_reestr' type="checkbox" checked />
															@else
																<input id='oudCheck' class='form-check-input' name='oud_original_contract_reestr' type="checkbox" />
															@endif
														@endif
													</div>
												</div>
												<div class="row">
													<div class='col-md-5'>
														<label for='otdCheck' class='form-check-label'>Отдел 31</label>
													</div>
													<div class='col-md-7'>
														@if(old('otd_original_contract_reestr'))
															<input id='otdCheck' class='form-check-input' name='otd_original_contract_reestr' type="checkbox" checked />
														@else
															@if($reestr->otd_original_contract_reestr == 1)
																<input id='otdCheck' class='form-check-input' name='otd_original_contract_reestr' type="checkbox" checked />
															@else
																<input id='otdCheck' class='form-check-input' name='otd_original_contract_reestr' type="checkbox"/>
															@endif
														@endif
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-9">
												<div class='row'>
													<div class="col-md-9">
														<div class="row col-md-12" style='text-align: center;'>
															<label>Согласование крупной сделки</label>
														</div>
														<div class="row col-md-12">
															<div class="col-md-2">
																<label for='number_inquiry_reestr'>Запрос №</label>
															</div>
															<div class="col-md-5">
																<input id='number_inquiry_reestr' class='form-control {{$errors->has("number_inquiry_reestr") ? print("inputError ") : print("")}}' name='number_inquiry_reestr' value='{{old("number_inquiry_reestr") ? old("number_inquiry_reestr") : $reestr->number_inquiry_reestr}}'/>
																@if($errors->has('number_inquiry_reestr'))
																	<label class='msgError'>{{$errors->first('number_inquiry_reestr')}}</label>
																@endif
															</div>
															<div class="col-md-1">
																<label for='date_inquiry_reestr'>от</label>
															</div>
															<div class="col-md-3">
																<input id='date_inquiry_reestr' class='datepicker form-control {{$errors->has("date_inquiry_reestr") ? print("inputError ") : print("")}}' name='date_inquiry_reestr' value='{{old("date_inquiry_reestr") ? old("date_inquiry_reestr") : $reestr->date_inquiry_reestr}}'/>
																@if($errors->has('date_inquiry_reestr'))
																	<label class='msgError'>{{$errors->first('date_inquiry_reestr')}}</label>
																@endif
															</div>
														</div>
														<div class="row col-md-12">
															<div class="col-md-2">
																<label for='number_answer_reestr'>Ответ №</label>
															</div>
															<div class="col-md-5">
																<input id='number_answer_reestr' class='form-control {{$errors->has("number_answer_reestr") ? print("inputError ") : print("")}}' name='number_answer_reestr' value='{{old("number_answer_reestr") ? old("number_answer_reestr") : $reestr->number_answer_reestr}}'/>
																@if($errors->has('number_answer_reestr'))
																	<label class='msgError'>{{$errors->first('number_answer_reestr')}}</label>
																@endif
															</div>
															<div class="col-md-1">
																<label for='date_answer_reestr'>от</label>
															</div>
															<div class="col-md-3">
																<input id='date_answer_reestr' class='datepicker form-control {{$errors->has("date_answer_reestr") ? print("inputError ") : print("")}}' name='date_answer_reestr' value='{{old("date_answer_reestr") ? old("date_answer_reestr") : $reestr->date_answer_reestr}}'/>
																@if($errors->has('date_answer_reestr'))
																	<label class='msgError'>{{$errors->first('date_answer_reestr')}}</label>
																@endif
															</div>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class="row col-md-8">
														<div class="col-md-1">
															<div class='row'>
																<div class="col-md-7">
																	<label for='procurement_reestr'>Закуп</label>
																</div>
																<div class="col-md-5">
																	@if(old('procurement_reestr'))
																		<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" checked />
																	@else
																		@if($reestr->procurement_reestr)
																			<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" checked />
																		@else
																			<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" />
																		@endif
																	@endif
																</div>
															</div>
														</div>
														<div class="col-md-4">
															<div class='row'>
																<div class="col-md-5">
																	<label for='sel7'>Основание</label>
																</div>
																<div class="col-md-7">
																	<select id="sel7" class='form-control {{$errors->has("base_reestr") ? print("inputError ") : print("")}}' name='base_reestr'>
																		<option></option>
																		@foreach($bases as $base)
																			@if(old('base_reestr'))
																				@if(old('base_reestr') == $base->id)
																					<option value='{{$base->id}}' selected>{{$base->name_base}}</option>
																				@else
																					<option value='{{$base->id}}'>{{$base->name_base}}</option>
																				@endif
																			@else
																				@if($reestr->base_reestr == $base->id)
																					<option value='{{$base->id}}' selected>{{$base->name_base}}</option>
																				@else
																					<option value='{{$base->id}}'>{{$base->name_base}}</option>
																				@endif
																			@endif
																		@endforeach
																	</select>
																</div>
															</div>
														</div>
														<div class="col-md-2">
															<div class='row'>
																<div class="col-md-7">
																	<label for='marketing_reestr'>Сбыт</label>
																</div>
																<div class="col-md-5">
																	@if(old('marketing_reestr'))
																		<input id='marketing_reestr' class='form-check-input' name='marketing_reestr' type="checkbox" checked />
																	@else
																		@if($reestr->marketing_reestr)
																			<input id='marketing_reestr' class='form-check-input' name='marketing_reestr' type="checkbox" checked />
																		@else
																			<input id='marketing_reestr' class='form-check-input' name='marketing_reestr' type="checkbox" />
																		@endif
																	@endif
																</div>
															</div>
														</div>
														<div class="col-md-3">
															<div class='row'>
																<div class="col-md-7">
																	<label for='investments_reestr'>Инвестиции</label>
																</div>
																<div class="col-md-5">
																	@if(old('investments_reestr'))
																		<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" checked />
																	@else
																		@if($reestr->investments_reestr)
																			<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" checked />
																		@else
																			<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" />
																		@endif
																	@endif
																</div>
															</div>
														</div>
														<div class="col-md-2">
															<div class='row'>
																<div class="col-md-7">
																	<label for='other_reestr'>Иные</label>
																</div>
																<div class="col-md-5">
																	@if(old('other_reestr'))
																		<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" checked />
																	@else
																		@if($reestr->other_reestr)
																			<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" checked />
																		@else
																			<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" />
																		@endif
																	@endif
																</div>
															</div>
														</div>
													</div>
													<div class="row col-md-4">
														<div class="col-md-12">
															<div class='row'>
																<div class="col-md-3">
																	<label for='okpo_reestr'>ОКПО</label>
																</div>
																<div class="col-md-7">
																	<input id='okpo_reestr' class='form-control {{$errors->has("okpo_reestr") ? print("inputError ") : print("")}}' name='okpo_reestr' value='{{old("okpo_reestr") ? old("okpo_reestr") : $reestr->okpo_reestr}}'/>
																</div>
															</div>
														</div>
														<div class="col-md-12">
															<div class='row'>
																<div class="col-md-3">
																	<label for='okved_reestr'>ОКВЭД</label>
																</div>
																<div class="col-md-7">
																	<input id='okved_reestr' class='form-control {{$errors->has("okved_reestr") ? print("inputError ") : print("")}}' name='okved_reestr' value='{{old("okved_reestr") ? old("okved_reestr") : $reestr->okved_reestr}}'/>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-3">
												<div class="row col-md-12">
													<div class='row'>
														<div class="col-md-8">
															<label for='number_contestants_reestr'>Кол. участников конкурса</label>
														</div>
														<div class="col-md-4">
															<input id='number_contestants_reestr' class='form-control {{$errors->has("number_contestants_reestr") ? print("inputError ") : print("")}}' name='number_contestants_reestr' value='{{old("number_contestants_reestr") ? old("number_contestants_reestr") : $reestr->number_contestants_reestr}}'/>
															@if($errors->has('number_contestants_reestr'))
																<label class='msgError'>{{$errors->first('number_contestants_reestr')}}</label>
															@endif
														</div>
													</div>
												</div>
												<div class="row col-md-12">
													<div class='row'>
														<div class="col-md-8">
															<label for='denied_admission_reestr'>Отказано в допуске к участию</label>
														</div>
														<div class="col-md-4">
															<input id='denied_admission_reestr' class='form-control {{$errors->has("denied_admission_reestr") ? print("inputError ") : print("")}}' name='denied_admission_reestr' value='{{old("denied_admission_reestr") ? old("denied_admission_reestr") : $reestr->denied_admission_reestr}}'/>
															@if($errors->has('denied_admission_reestr'))
																<label class='msgError'>{{$errors->first('denied_admission_reestr')}}</label>
															@endif
														</div>
													</div>
												</div>
												<div class="row col-md-12" style='text-align: left;'>
													<div class='row'>
														<div class="col-md-6">
															<label>Обеспечение</label>
														</div>
														<div class="col-md-3" style='text-align: center;'>
															<label>Заявки</label>
														</div>
														<div class="col-md-3" style='text-align: center;'>
															<label>Договора</label>
														</div>
													</div>
													<div class='row'>
														<div class="col-md-6">
															<label>Наличные</label>
														</div>
														<div class="col-md-3" style='text-align: center;'>
															@if(old('cash_order_reestr'))
																<input class='form-check-input' name='cash_order_reestr' type="checkbox" checked />
															@else
																@if($reestr->cash_order_reestr)
																	<input class='form-check-input' name='cash_order_reestr' type="checkbox" checked />
																@else
																	<input class='form-check-input' name='cash_order_reestr' type="checkbox" />
																@endif
															@endif
														</div>
														<div class="col-md-3" style='text-align: center;'>
															@if(old('cash_contract_reestr'))
																<input class='form-check-input' name='cash_contract_reestr' type="checkbox" checked />
															@else
																@if($reestr->cash_contract_reestr)
																	<input class='form-check-input' name='cash_contract_reestr' type="checkbox" checked />
																@else
																	<input class='form-check-input' name='cash_contract_reestr' type="checkbox" />
																@endif
															@endif
														</div>
													</div>
													<div class='row'>
														<div class="col-md-6">
															<label>Безналичные</label>
														</div>
														<div class="col-md-3" style='text-align: center;'>
															@if(old('non_cash_order_reestr'))
																<input class='form-check-input' name='non_cash_order_reestr' type="checkbox" checked />
															@else
																@if($reestr->non_cash_order_reestr)
																	<input class='form-check-input' name='non_cash_order_reestr' type="checkbox" checked />
																@else
																	<input class='form-check-input' name='non_cash_order_reestr' type="checkbox"/>
																@endif
															@endif
														</div>
														<div class="col-md-3" style='text-align: center;'>
															@if(old('non_cash_contract_reestr'))
																<input class='form-check-input' name='non_cash_contract_reestr' type="checkbox" checked />
															@else
																@if($reestr->non_cash_contract_reestr)
																	<input class='form-check-input' name='non_cash_contract_reestr' type="checkbox" checked />
																@else
																	<input class='form-check-input' name='non_cash_contract_reestr' type="checkbox" />
																@endif
															@endif
														</div>
													</div>
													<div class='row'>
														<div class="col-md-6">
															<label for='date_execution_reestr'>Дата исполнения</label>
														</div>
														<div class="col-md-6">
															<input id='date_execution_reestr' class='datepicker form-control {{$errors->has("date_execution_reestr") ? print("inputError ") : print("")}}' name='date_execution_reestr' value='{{old("date_execution_reestr") ? old("date_execution_reestr") : $reestr->date_execution_reestr}}'/>
															@if($errors->has('date_execution_reestr'))
																<label class='msgError'>{{$errors->first('date_execution_reestr')}}</label>
															@endif
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-2">
												<label for='prolongation_reestr'>Пролонгация</label>
												@if(old('prolongation_reestr'))
													<input id='prolongation_reestr' class='form-check-input' type="checkbox" name='prolongation_reestr' checked />
												@else
													@if($reestr->prolongation_reestr)
														<input id='prolongation_reestr' class='form-check-input' type="checkbox" name='prolongation_reestr' checked />
													@else
														<input id='prolongation_reestr' class='form-check-input' type="checkbox" name='prolongation_reestr'/>
													@endif
												@endif
											</div>
											<div class="col-md-2">
												<label for='re_registration_reestr'>Перерегистрация</label>
												@if(old('re_registration_reestr'))
													<input id='re_registration_reestr' class='form-check-input' type="checkbox" name='re_registration_reestr' checked />
												@else
													@if($reestr->re_registration_reestr)
														<input id='re_registration_reestr' class='form-check-input' type="checkbox" name='re_registration_reestr' checked />
													@else
														<input id='re_registration_reestr' class='form-check-input' type="checkbox" name='re_registration_reestr'/>
													@endif
												@endif
											</div>
											<div class="col-md-2">
												<!--<button class="btn btn-secondary" style="float: right;" type='button'>Дополнительные соглашения</button>-->
											</div>
											<div class="col-md-3">
												<!--<button class="btn btn-secondary" style="float: left;" type='button'>Протокол разногласий / согласования</button>-->
											</div>
											<div class="col-md-3">
												<button type='submit' class="btn btn-primary" style="float: right;">Сохранить договор</button>
											</div>
										</div>
										<div class="row">
										</div>
									</div>
								</div>
							</form>
						</div>
					@else
						<div class="content">
							<form method='POST' action="{{route('department.ekonomic.update_reestr', $contract->id)}}">
								{{csrf_field()}}
								<div class="row">
									<div class="col-md-3">
										<input class='form-control' style='color:red; text-align:center;' type='text' value='<?php 
											if($reestr->date_registration_project_reestr)
												if(!$reestr->date_signing_contract_reestr){
													if(time() - strtotime($reestr->date_registration_project_reestr) > 2592000)
														echo 'Не подписан более 30 дней!';
												}else
													if(strtotime($reestr->date_signing_contract_reestr) - strtotime($reestr->date_registration_project_reestr) > 2592000)
														echo 'Не был подписан более 30 дней!';
										?>' readonly />
									</div>
									<div class="col-md-2">
										<label for='amount_contract_reestr'>Сумма по договору (фиксир.)</label>
									</div>
									<div class="col-md-2">
										<input id='amount_contract_reestr' class='form-control' name='amount_contract_reestr' type='text' value='{{old("amount_contract_reestr") ? old("amount_contract_reestr") : $reestr->amount_contract_reestr}}'/>
									</div>
									<!--<div class="col-md-1">
										<label for='amount_invoice_reestr'>Сумма по счетам</label>
									</div>
									<div class="col-md-2">
										<input id='amount_invoice_reestr' class='form-control' name='amount_invoice_reestr' type='text' value='{{old("amount_invoice_reestr") ? old("amount_invoice_reestr") : $reestr->amount_invoice_reestr}}'/>
									</div>-->
								</div>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label for='numberContract'>Номер договора</label>
											<input id='numberContract' class='form-control {{$errors->has("number_contract") ? print("inputError ") : print("")}}' name='number_contract' type='text' value='{{old("number_contract") ? old("number_contract") : $contract->number_contract}}'/>
											@if($errors->has('number_contract'))
												<label class='msgError'>{{$errors->first('number_contract')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-1">
										<div class="form-group">
											<label for='year_contract'>Год</label>
											<input id='year_contract' class='form-control {{$errors->has("year_contract") ? print("inputError ") : print("")}}' name='year_contract' type='text' value='{{old("year_contract") ? old("year_contract") : $contract->year_contract}}'/>
											@if($errors->has('year_contract'))
												<label class='msgError'>{{$errors->first('year_contract')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-1">
										<div class='form-check'>
											<label class='form-check-label' for='gozCheck'>ГОЗ</label>
											@if(old('goz_contract'))
												<input id='gozCheck' class='form-check-input' name='goz_contract' type="checkbox" onclick="$('#exportCheck').prop('checked', !$('#exportCheck').prop('checked'))" checked />
											@else
												@if($contract->id_goz_contract == 1)
													<input id='gozCheck' class='form-check-input' name='goz_contract' type="checkbox" onclick="$('#exportCheck').prop('checked', !$('#exportCheck').prop('checked'))" checked />
												@else
													<input id='gozCheck' class='form-check-input' name='goz_contract' type="checkbox" onclick="$('#exportCheck').prop('checked', !$('#exportCheck').prop('checked'))"/>
												@endif
											@endif
										</div>
										<div class='form-check'>
											<label class='form-check-label' for='exportCheck'>Экспорт</label>
											@if(old('goz_contract'))
												<input id='exportCheck' class='form-check-input' type="checkbox" onclick="$('#gozCheck').prop('checked', !$('#gozCheck').prop('checked'))" />
											@else
												@if($contract->id_goz_contract != 1)
													<input id='exportCheck' class='form-check-input' type="checkbox" onclick="$('#gozCheck').prop('checked', !$('#gozCheck').prop('checked'))" checked />
												@else
													<input id='exportCheck' class='form-check-input' type="checkbox" onclick="$('#gozCheck').prop('checked', !$('#gozCheck').prop('checked'))"/>
												@endif
											@endif
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="sel3">Вид работ</span></label>
											<select id="sel3" class='form-control {{$errors->has("id_view_work_contract") ? print("inputError ") : print("")}}' name='id_view_work_contract'>
												<option></option>
												@if($viewWorks)
													@foreach($viewWorks as $viewWork)
														@if(old('id_view_work_contract'))
															@if(old('id_view_work_contract') == $viewWork->id)
																<option value='{{$viewWork->id}}' selected>{{ $viewWork->name_view_work }}</option>
															@else
																<option value='{{$viewWork->id}}'>{{ $viewWork->name_view_work }}</option>
															@endif
														@else
															@if($contract->id_view_work_contract == $viewWork->id)
																<option value='{{$viewWork->id}}' selected>{{ $viewWork->name_view_work }}</option>
															@else
																<option value='{{$viewWork->id}}'>{{ $viewWork->name_view_work }}</option>
															@endif
														@endif
													@endforeach
												@endif
											</select>
											@if($errors->has('id_view_work_contract'))
												<label class='msgError'>{{$errors->first('id_view_work_contract')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="sel5">Отбор поставщика</span></label>
											<select id="sel5" class='form-control {{$errors->has("selection_supplier_reestr") ? print("inputError ") : print("")}}' name='selection_supplier_reestr'>
												<option></option>
												@foreach($selection_suppliers as $selection_supplier)
													@if(old('selection_supplier_reestr'))
														@if(old('selection_supplier_reestr') == $selection_supplier->id)
															<option value='{{$selection_supplier->id}}' selected>{{$selection_supplier->name_selection_supplier}}</option>
														@else
															<option value='{{$selection_supplier->id}}'>{{$selection_supplier->name_selection_supplier}}</option>
														@endif
													@else
														@if($reestr->selection_supplier_reestr == $selection_supplier->id)
															<option value='{{$selection_supplier->id}}' selected>{{$selection_supplier->name_selection_supplier}}</option>
														@else
															<option value='{{$selection_supplier->id}}'>{{$selection_supplier->name_selection_supplier}}</option>
														@endif
													@endif
												@endforeach
											</select>
											@if($errors->has('selection_supplier_reestr'))
												<label class='msgError'>{{$errors->first('selection_supplier_reestr')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-1">
										<div class="row col-md-12">
											@if(old('renouncement_contract'))
												<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" checked />
											@else
												@if($contract->renouncement_contract == 1)
													<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" checked />
												@else
													<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox"/>
												@endif
											@endif
											<label class='form-check-label' for='break'>ОТКАЗ</label>
										</div>
										<div class="row col-md-12">
											@if(old('archive_contract'))
												<input id='archive' class='form-check-input' name='archive_contract' type="checkbox" checked />
											@else
												@if($contract->archive_contract == 1)
													<input id='archive' class='form-check-input' name='archive_contract' type="checkbox" checked />
												@else
													<input id='archive' class='form-check-input' name='archive_contract' type="checkbox"/>
												@endif
											@endif
											<label class='form-check-label' for='archive'>АРХИВ</label>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
										<label for='sel6'>Тип документа</label>
										<select id="sel6" class='form-control {{$errors->has("type_document_reestr") ? print("inputError ") : print("")}}' name='type_document_reestr'>
											<option></option>
											@foreach($type_documents as $type_document)
												@if(old('type_document_reestr'))
													@if(old('type_document_reestr') == $type_document->id)
														<option value='{{$type_document->id}}' selected>{{$type_document->name_type_document}}</option>
													@else
														<option value='{{$type_document->id}}'>{{$type_document->name_type_document}}</option>
													@endif
												@else
													@if($reestr->type_document_reestr == $type_document->id)
														<option value='{{$type_document->id}}' selected>{{$type_document->name_type_document}}</option>
													@else
														<option value='{{$type_document->id}}'>{{$type_document->name_type_document}}</option>
													@endif
												@endif
											@endforeach
										</select>
									</div>
									<div class="col-md-2">
										<label for='place_save_contract_reestr'>Место хранения</label>
										<input id='place_save_contract_reestr' class='form-control {{$errors->has("place_save_contract_reestr") ? print("inputError ") : print("")}}' name='place_save_contract_reestr' value='{{old("place_save_contract_reestr") ? old("place_save_contract_reestr") : $reestr->place_save_contract_reestr}}'/>
										@if($errors->has('place_save_contract'))
											<label class='msgError'>{{$errors->first('place_save_contract')}}</label>
										@endif
									</div>
									<div class="col-md-2">
										<label for='executor_reestr'>Исполнитель</label>
										<select class='form-control' name='executor_reestr'>
											<option></option>
											@if(old('executor_reestr'))
												@foreach($curators as $in_curators)
													@if(old('executor_reestr') == $in_curators->id)
														<option value='{{$in_curators->id}}' selected>{{$in_curators->FIO}}</option>
													@else
														<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
													@endif
												@endforeach
											@else
												@foreach($curators as $in_curators)
													@if($reestr->executor_reestr == $in_curators->id)
														<option value='{{$in_curators->id}}' selected>{{$in_curators->FIO}}</option>
													@else
														<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
													@endif
												@endforeach
											@endif
										</select>
										@if($errors->has('executor_reestr'))
											<label class='msgError'>{{$errors->first('executor_reestr')}}</label>
										@endif
									</div>
									<div class="col-md-1" style='text-align: right;'>
										<label class='form-check-label'>Оригинал договора</label>
									</div>
									<div class="col-md-2">
										<div class="row">
											<div class='col-md-5'>
												<label for='oudCheck' class='form-check-label'>ОУД</label>
											</div>
											<div class='col-md-7'>
												@if(old('oud_original_contract_reestr'))
													<input id='oudCheck' class='form-check-input' name='oud_original_contract_reestr' type="checkbox" checked />
												@else
													@if($reestr->oud_original_contract_reestr == 1)
														<input id='oudCheck' class='form-check-input' name='oud_original_contract_reestr' type="checkbox" checked />
													@else
														<input id='oudCheck' class='form-check-input' name='oud_original_contract_reestr' type="checkbox" />
													@endif
												@endif
											</div>
										</div>
										<div class="row">
											<div class='col-md-5'>
												<label for='otdCheck' class='form-check-label'>Отдел 31</label>
											</div>
											<div class='col-md-7'>
												@if(old('otd_original_contract_reestr'))
													<input id='otdCheck' class='form-check-input' name='otd_original_contract_reestr' type="checkbox" checked />
												@else
													@if($reestr->otd_original_contract_reestr == 1)
														<input id='otdCheck' class='form-check-input' name='otd_original_contract_reestr' type="checkbox" checked />
													@else
														<input id='otdCheck' class='form-check-input' name='otd_original_contract_reestr' type="checkbox"/>
													@endif
												@endif
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-9">
										<div class='row'>
											<div class="col-md-3">
												<div class="row">
													<div class="col-md-7">
														<label for='date_registration_project_reestr'>Дата регистрации проекта</label>
													</div>
													<div class="col-md-5">
														<input id='date_registration_project_reestr' class='datepicker form-control {{$errors->has("date_registration_project_reestr") ? print("inputError ") : print("")}}' name='date_registration_project_reestr' value='{{old("date_registration_project_reestr") ? old("date_registration_project_reestr") : $reestr->date_registration_project_reestr}}'/>
														@if($errors->has('date_registration_project_reestr'))
															<label class='msgError'>{{$errors->first('date_registration_project_reestr')}}</label>
														@endif
													</div>
												</div>
												<div class="row">
													<div class="col-md-7">
														<label for='date_signing_contract_reestr'>Дата подписания договора</label>
													</div>
													<div class="col-md-5">
														<input id='date_signing_contract_reestr' class='datepicker form-control {{$errors->has("date_signing_contract_reestr") ? print("inputError ") : print("")}}' name='date_signing_contract_reestr' value='{{old("date_signing_contract_reestr") ? old("date_signing_contract_reestr") : $reestr->date_signing_contract_reestr}}'/>
														@if($errors->has('date_signing_contract_reestr'))
															<label class='msgError'>{{$errors->first('date_signing_contract_reestr')}}</label>
														@endif
													</div>
												</div>
												<div class="row">
													<div class="col-md-7">
														<label for='date_the_end_contract_reestr'>Дата сдачи договора</label>
													</div>
													<div class="col-md-5">
														<input id='date_the_end_contract_reestr' class='datepicker form-control {{$errors->has("date_the_end_contract_reestr") ? print("inputError ") : print("")}}' name='date_the_end_contract_reestr' value='{{old("date_the_end_contract_reestr") ? old("date_the_end_contract_reestr") : $reestr->date_the_end_contract_reestr}}'/>
														@if($errors->has('date_the_end_contract_reestr'))
															<label class='msgError'>{{$errors->first('date_the_end_contract_reestr')}}</label>
														@endif
													</div>
												</div>
											</div>
											<div class="col-md-9">
												<div class="row col-md-12" style='text-align: center;'>
													<label>Согласование крупной сделки</label>
												</div>
												<div class="row col-md-12">
													<div class="col-md-2">
														<label for='number_inquiry_reestr'>Запрос №</label>
													</div>
													<div class="col-md-5">
														<input id='number_inquiry_reestr' class='form-control {{$errors->has("number_inquiry_reestr") ? print("inputError ") : print("")}}' name='number_inquiry_reestr' value='{{old("number_inquiry_reestr") ? old("number_inquiry_reestr") : $reestr->number_inquiry_reestr}}'/>
														@if($errors->has('number_inquiry_reestr'))
															<label class='msgError'>{{$errors->first('number_inquiry_reestr')}}</label>
														@endif
													</div>
													<div class="col-md-1">
														<label for='date_inquiry_reestr'>от</label>
													</div>
													<div class="col-md-3">
														<input id='date_inquiry_reestr' class='datepicker form-control {{$errors->has("date_inquiry_reestr") ? print("inputError ") : print("")}}' name='date_inquiry_reestr' value='{{old("date_inquiry_reestr") ? old("date_inquiry_reestr") : $reestr->date_inquiry_reestr}}'/>
														@if($errors->has('date_inquiry_reestr'))
															<label class='msgError'>{{$errors->first('date_inquiry_reestr')}}</label>
														@endif
													</div>
												</div>
												<div class="row col-md-12">
													<div class="col-md-2">
														<label for='number_answer_reestr'>Ответ №</label>
													</div>
													<div class="col-md-5">
														<input id='number_answer_reestr' class='form-control {{$errors->has("number_answer_reestr") ? print("inputError ") : print("")}}' name='number_answer_reestr' value='{{old("number_answer_reestr") ? old("number_answer_reestr") : $reestr->number_answer_reestr}}'/>
														@if($errors->has('number_answer_reestr'))
															<label class='msgError'>{{$errors->first('number_answer_reestr')}}</label>
														@endif
													</div>
													<div class="col-md-1">
														<label for='date_answer_reestr'>от</label>
													</div>
													<div class="col-md-3">
														<input id='date_answer_reestr' class='datepicker form-control {{$errors->has("date_answer_reestr") ? print("inputError ") : print("")}}' name='date_answer_reestr' value='{{old("date_answer_reestr") ? old("date_answer_reestr") : $reestr->date_answer_reestr}}'/>
														@if($errors->has('date_answer_reestr'))
															<label class='msgError'>{{$errors->first('date_answer_reestr')}}</label>
														@endif
													</div>
												</div>
											</div>
										</div>
										<div class='row'>
											<div class="row col-md-8">
												<div class="col-md-1">
													<div class='row'>
														<div class="col-md-7">
															<label for='procurement_reestr'>Закуп</label>
														</div>
														<div class="col-md-5">
															@if(old('procurement_reestr'))
																<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" checked />
															@else
																@if($reestr->procurement_reestr)
																	<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" checked />
																@else
																	<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" />
																@endif
															@endif
														</div>
													</div>
												</div>
												<div class="col-md-4">
													<div class='row'>
														<div class="col-md-5">
															<label for='sel7'>Основание</label>
														</div>
														<div class="col-md-7">
															<select id="sel7" class='form-control {{$errors->has("base_reestr") ? print("inputError ") : print("")}}' name='base_reestr'>
																<option></option>
																@foreach($bases as $base)
																	@if(old('base_reestr'))
																		@if(old('base_reestr') == $base->id)
																			<option value='{{$base->id}}' selected>{{$base->name_base}}</option>
																		@else
																			<option value='{{$base->id}}'>{{$base->name_base}}</option>
																		@endif
																	@else
																		@if($reestr->base_reestr == $base->id)
																			<option value='{{$base->id}}' selected>{{$base->name_base}}</option>
																		@else
																			<option value='{{$base->id}}'>{{$base->name_base}}</option>
																		@endif
																	@endif
																@endforeach
															</select>
														</div>
													</div>
												</div>
												<div class="col-md-2">
													<div class='row'>
														<div class="col-md-7">
															<label for='marketing_reestr'>Сбыт</label>
														</div>
														<div class="col-md-5">
															@if(old('marketing_reestr'))
																<input id='marketing_reestr' class='form-check-input' name='marketing_reestr' type="checkbox" checked />
															@else
																@if($reestr->marketing_reestr)
																	<input id='marketing_reestr' class='form-check-input' name='marketing_reestr' type="checkbox" checked />
																@else
																	<input id='marketing_reestr' class='form-check-input' name='marketing_reestr' type="checkbox" />
																@endif
															@endif
														</div>
													</div>
												</div>
												<div class="col-md-3">
													<div class='row'>
														<div class="col-md-7">
															<label for='investments_reestr'>Инвестиции</label>
														</div>
														<div class="col-md-5">
															@if(old('investments_reestr'))
																<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" checked />
															@else
																@if($reestr->investments_reestr)
																	<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" checked />
																@else
																	<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" />
																@endif
															@endif
														</div>
													</div>
												</div>
												<div class="col-md-2">
													<div class='row'>
														<div class="col-md-7">
															<label for='other_reestr'>Иные</label>
														</div>
														<div class="col-md-5">
															@if(old('other_reestr'))
																<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" checked />
															@else
																@if($reestr->other_reestr)
																	<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" checked />
																@else
																	<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" />
																@endif
															@endif
														</div>
													</div>
												</div>
											</div>
											<div class="row col-md-4">
												<div class="col-md-12">
													<div class='row'>
														<div class="col-md-3">
															<label for='okpo_reestr'>ОКПО</label>
														</div>
														<div class="col-md-7">
															<input id='okpo_reestr' class='form-control {{$errors->has("okpo_reestr") ? print("inputError ") : print("")}}' name='okpo_reestr' value='{{old("okpo_reestr") ? old("okpo_reestr") : $reestr->okpo_reestr}}'/>
														</div>
													</div>
												</div>
												<div class="col-md-12">
													<div class='row'>
														<div class="col-md-3">
															<label for='okved_reestr'>ОКВЭД</label>
														</div>
														<div class="col-md-7">
															<input id='okved_reestr' class='form-control {{$errors->has("okved_reestr") ? print("inputError ") : print("")}}' name='okved_reestr' value='{{old("okved_reestr") ? old("okved_reestr") : $reestr->okved_reestr}}'/>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="row col-md-12">
											<div class='row'>
												<div class="col-md-8">
													<label for='number_contestants_reestr'>Кол. участников конкурса</label>
												</div>
												<div class="col-md-4">
													<input id='number_contestants_reestr' class='form-control {{$errors->has("number_contestants_reestr") ? print("inputError ") : print("")}}' name='number_contestants_reestr' value='{{old("number_contestants_reestr") ? old("number_contestants_reestr") : $reestr->number_contestants_reestr}}'/>
													@if($errors->has('number_contestants_reestr'))
														<label class='msgError'>{{$errors->first('number_contestants_reestr')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class="row col-md-12">
											<div class='row'>
												<div class="col-md-8">
													<label for='denied_admission_reestr'>Отказано в допуске к участию</label>
												</div>
												<div class="col-md-4">
													<input id='denied_admission_reestr' class='form-control {{$errors->has("denied_admission_reestr") ? print("inputError ") : print("")}}' name='denied_admission_reestr' value='{{old("denied_admission_reestr") ? old("denied_admission_reestr") : $reestr->denied_admission_reestr}}'/>
													@if($errors->has('denied_admission_reestr'))
														<label class='msgError'>{{$errors->first('denied_admission_reestr')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class="row col-md-12" style='text-align: left;'>
											<div class='row'>
												<div class="col-md-6">
													<label>Обеспечение</label>
												</div>
												<div class="col-md-3" style='text-align: center;'>
													<label>Заявки</label>
												</div>
												<div class="col-md-3" style='text-align: center;'>
													<label>Договора</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-6">
													<label>Наличные</label>
												</div>
												<div class="col-md-3" style='text-align: center;'>
													@if(old('cash_order_reestr'))
														<input class='form-check-input' name='cash_order_reestr' type="checkbox" checked />
													@else
														@if($reestr->cash_order_reestr)
															<input class='form-check-input' name='cash_order_reestr' type="checkbox" checked />
														@else
															<input class='form-check-input' name='cash_order_reestr' type="checkbox" />
														@endif
													@endif
												</div>
												<div class="col-md-3" style='text-align: center;'>
													@if(old('cash_contract_reestr'))
														<input class='form-check-input' name='cash_contract_reestr' type="checkbox" checked />
													@else
														@if($reestr->cash_contract_reestr)
															<input class='form-check-input' name='cash_contract_reestr' type="checkbox" checked />
														@else
															<input class='form-check-input' name='cash_contract_reestr' type="checkbox" />
														@endif
													@endif
												</div>
											</div>
											<div class='row'>
												<div class="col-md-6">
													<label>Безналичные</label>
												</div>
												<div class="col-md-3" style='text-align: center;'>
													@if(old('non_cash_order_reestr'))
														<input class='form-check-input' name='non_cash_order_reestr' type="checkbox" checked />
													@else
														@if($reestr->non_cash_order_reestr)
															<input class='form-check-input' name='non_cash_order_reestr' type="checkbox" checked />
														@else
															<input class='form-check-input' name='non_cash_order_reestr' type="checkbox"/>
														@endif
													@endif
												</div>
												<div class="col-md-3" style='text-align: center;'>
													@if(old('non_cash_contract_reestr'))
														<input class='form-check-input' name='non_cash_contract_reestr' type="checkbox" checked />
													@else
														@if($reestr->non_cash_contract_reestr)
															<input class='form-check-input' name='non_cash_contract_reestr' type="checkbox" checked />
														@else
															<input class='form-check-input' name='non_cash_contract_reestr' type="checkbox" />
														@endif
													@endif
												</div>
											</div>
											<div class='row'>
												<div class="col-md-6">
													<label for='date_execution_reestr'>Дата исполнения</label>
												</div>
												<div class="col-md-6">
													<input id='date_execution_reestr' class='datepicker form-control {{$errors->has("date_execution_reestr") ? print("inputError ") : print("")}}' name='date_execution_reestr' value='{{old("date_execution_reestr") ? old("date_execution_reestr") : $reestr->date_execution_reestr}}'/>
													@if($errors->has('date_execution_reestr'))
														<label class='msgError'>{{$errors->first('date_execution_reestr')}}</label>
													@endif
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<label>Название предприятия</label>
									</div>
									<div class="col-md-3">

									</div>
									<div class="col-md-3">

									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<select id="sel4" class='form-control {{$errors->has("id_counterpartie_contract") ? print("inputError ") : print("")}}' name='id_counterpartie_contract'>
												<option></option>
												@if($counterparties)
													@foreach($counterparties as $counterpartie)
														@if(old('id_counterpartie_contract'))
															@if(old('id_counterpartie_contract') == $counterpartie->id)
																<option value='{{$counterpartie->id}}' selected>{{ $counterpartie->name_full }}</option>
															@else
																@if(Auth::User()->hasRole()->role == 'Администратор')
																	<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
																@elseif(Auth::User()->hasRole()->role == 'Отдел управления договорами')
																	@if($counterpartie->is_sip_counterpartie == '0')
																		<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
																	@endif
																@else
																	@if($counterpartie->is_sip_counterpartie == '1')
																		<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
																	@endif
																@endif													
															@endif
														@else
															@if($contract->id_counterpartie_contract == $counterpartie->id)
																<option value='{{$counterpartie->id}}' selected>{{ $counterpartie->name_full }}</option>
															@else
																@if(Auth::User()->hasRole()->role == 'Администратор')
																	<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
																@elseif(Auth::User()->hasRole()->role == 'Отдел управления договорами')
																	@if($counterpartie->is_sip_counterpartie == '0')
																		<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
																	@endif
																@else
																	@if($counterpartie->is_sip_counterpartie == '1')
																		<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
																	@endif
																@endif
															@endif
														@endif
													@endforeach
												@endif
											</select>
											@if($errors->has('id_counterpartie_contract'))
												<label class='msgError'>{{$errors->first('id_counterpartie_contract')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-12">
										<div class='row'>
											<div class="col-md-2">
												<label for='number_counterpartie_contract_reestr'>№ дог. контрагента</label>
											</div>
											<div class="col-md-10">
												<input id='number_counterpartie_contract_reestr' class='form-control {{$errors->has("number_counterpartie_contract_reestr") ? print("inputError ") : print("")}}' name='number_counterpartie_contract_reestr' value='{{old("number_counterpartie_contract_reestr") ? old("number_counterpartie_contract_reestr") : $reestr->number_counterpartie_contract_reestr}}'/>
												@if($errors->has('number_counterpartie_contract_reestr'))
													<label class='msgError'>{{$errors->first('number_counterpartie_contract_reestr')}}</label>
												@endif
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<div class="row">
											<div class="col-md-12">
												<div class='form-group'>
													<label for='nameWork'>Наименование работ</label>
													<textarea id='nameWork' class='form-control {{$errors->has("name_work_contract") ? print("inputError ") : print("")}}' name='name_work_contract' type="text" style="width: 100%;" rows='4'>{{ old('name_work_contract') ? old('name_work_contract') : $contract->name_work_contract }}</textarea>
													@if($errors->has('name_work_contract'))
														<label class='msgError'>{{$errors->first('name_work_contract')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label for='lastCompleteContract'>Состояние заключения договора</label>
											</div>								
										</div>
										<div class="row">
											<div class="col-md-12">
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
											<div class="col-md-12">
												<div class='form-group'>
													@if(count($states) > 0)
														@if($states[count($states) - 1]->name_state == "Заключен" OR $states[count($states) - 1]->name_state == "Заключён")
															<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%; display: none;" rows='3' disabled></textarea>
														@else
															<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%;" rows='3' disabled>{{$states[count($states) - 1]->name_state}}</textarea>
														@endif
													@else
														<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%;" rows='3' disabled></textarea>
													@endif
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<button class="btn btn-primary" data-toggle="modal" data-target="#history_states" type='button'>История состояний</button>
											</div>
										</div>
									</div>
									<div class="col-md-9">
										<div class="row">
											<div class="col-md-3">
												<label for='okdp_reestr'>ОКДП</label>
												<div class="row col-md-12">
													<input id='okdp_reestr' class='form-control {{$errors->has("okdp_reestr") ? print("inputError ") : print("")}}' name='okdp_reestr' value='{{ old("okdp_reestr") ? old("okdp_reestr") : $reestr->okdp_reestr }}'/>
												</div>
											</div>
											<div class="col-md-7">
												<label>Гарантия банка</label>
												<div class='row'>
													<div class="col-md-1">
														<label for='date_bank_reestr'>до</label>
													</div>
													<div class="col-md-2">
														<input id='date_bank_reestr' class='datepicker form-control {{$errors->has("date_bank_reestr") ? print("inputError ") : print("")}}' name='date_bank_reestr' value='{{ old("date_bank_reestr") ? old("date_bank_reestr") : $reestr->date_bank_reestr }}'/>
													</div>
													<div class="col-md-1">
														<label for='amount_bank_reestr'>Сумма</label>
													</div>
													<div class="col-md-3">
														<input id='amount_bank_reestr' class='form-control {{$errors->has("amount_bank_reestr") ? print("inputError ") : print("")}}' name='amount_bank_reestr' value='{{ old("amount_bank_reestr") ? old("amount_bank_reestr") : $reestr->amount_bank_reestr }}'/>
													</div>
													<div class="col-md-1">
														<label for='bank_reestr'>Банк</label>
													</div>
													<div class="col-md-4">
														<input id='bank_reestr' class='form-control {{$errors->has("bank_reestr") ? print("inputError ") : print("")}}' name='bank_reestr' value='{{ old("bank_reestr") ? old("bank_reestr") : $reestr->bank_reestr }}'/>
													</div>
												</div>
											</div>
											<div class="col-md-2">
												<div class='form-group row'>
													<div class='col-md-12' style='text-align: right;'>
														<button class='btn btn-primary' data-toggle="modal" data-target="#scan" type='button'>Сканы</button>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-2">
												<div class='row'>
													<div class="col-md-12">
														<label for='date_contract_reestr'>Срок действия договора</label>
													</div>
												</div>
											</div>
											<div class="col-md-5">
												<div class='row'>
													<div class="col-md-1">
														<label for='date_b_contract_reestr'>с</label>
													</div>
													<div class="col-md-5">
														<input id='date_b_contract_reestr' class='datepicker form-control {{$errors->has("date_b_contract_reestr") ? print("inputError ") : print("")}}' name='date_b_contract_reestr' value='{{ old("date_b_contract_reestr") ? old("date_b_contract_reestr") : $reestr->date_b_contract_reestr }}'/>
													</div>
													<div class="col-md-1">
														<label for='date_e_contract_reestr'>по</label>
													</div>
													<div class="col-md-5">
														<input id='date_e_contract_reestr' class='datepicker form-control {{$errors->has("date_e_contract_reestr") ? print("inputError ") : print("")}}' name='date_e_contract_reestr' value='{{ old("date_e_contract_reestr") ? old("date_e_contract_reestr") : $reestr->date_e_contract_reestr }}'/>
													</div>
												</div>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-10">
												<input id='date_contract_reestr' class='form-control {{$errors->has("date_contract_reestr") ? print("inputError ") : print("")}}' name='date_contract_reestr' value='{{ old("date_contract_reestr") ? old("date_contract_reestr") : $reestr->date_contract_reestr }}'/>
											</div>
										</div>
										<div class="row">
											<div class="col-md-10">
												<label for='date_maturity_reestr'>Срок исполнения обязательств</label>
												<input id='date_maturity_reestr' class='form-control {{$errors->has("date_maturity_reestr") ? print("inputError ") : print("")}}' name='date_maturity_reestr' value='{{ old("date_maturity_reestr") ? old("date_maturity_reestr") : $reestr->date_maturity_reestr }}'/>
											</div>
										</div>
										<div class="row">
											<div class="col-md-10">
												<div class='row'>
													<div class="col-md-1">
														<label for='amount_reestr'>Сумма (начальная)</label>
													</div>
													<div class="col-md-3">
														<input id='amount_reestr' class='form-control {{$errors->has("amount_reestr") ? print("inputError ") : print("")}}' name='amount_reestr' value='{{old("amount_reestr") ? old("amount_reestr") : $reestr->amount_reestr}}'/>
													</div>
													<div class="col-md-2">
														<select id="sel8" class='form-control {{$errors->has("unit_reestr") ? print("inputError ") : print("")}}' name='unit_reestr'>
															<option></option>
															@foreach($units as $unit)
																@if(old('unit_reestr'))
																	<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
																@else
																	@if($reestr->unit_reestr == $unit->id)
																		<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
																	@else
																		<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
																	@endif
																@endif
															@endforeach
														</select>
													</div>
													<div class="col-md-2">
														<label for='VAT'>НДС</label>
														@if(old('vat_reestr'))
															<input id='VAT' class='form-check-input' name='vat_reestr' type="checkbox" checked />
														@else
															@if($reestr->vat_reestr)
																<input id='VAT' class='form-check-input' name='vat_reestr' type="checkbox" checked />
															@else
																<input id='VAT' class='form-check-input' name='vat_reestr' type="checkbox"/>
															@endif
														@endif
													</div>
												</div>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-10">
												<input class='form-control {{$errors->has("amount_comment_reestr") ? print("inputError ") : print("")}}' name='amount_comment_reestr' value='{{old("amount_comment_reestr") ? old("amount_comment_reestr") : $reestr->amount_comment_reestr}}'/>
											</div>
										</div>
										<div class="row">
											<div class="col-md-10">
												<label for='payment_order_reestr'>Порядок оплаты</label>
												<textarea id='payment_order_reestr' class='form-control' type="text" style="width: 100%;" rows='3' name='payment_order_reestr'>{{old("payment_order_reestr") ? old("payment_order_reestr") : $reestr->payment_order_reestr}}</textarea>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
										<label for='prolongation_reestr'>Пролонгация</label>
										@if(old('prolongation_reestr'))
											<input id='prolongation_reestr' class='form-check-input' type="checkbox" name='prolongation_reestr' checked />
										@else
											@if($reestr->prolongation_reestr)
												<input id='prolongation_reestr' class='form-check-input' type="checkbox" name='prolongation_reestr' checked />
											@else
												<input id='prolongation_reestr' class='form-check-input' type="checkbox" name='prolongation_reestr'/>
											@endif
										@endif
									</div>
									<div class="col-md-2">
										<label for='re_registration_reestr'>Перерегистрация</label>
										@if(old('re_registration_reestr'))
											<input id='re_registration_reestr' class='form-check-input' type="checkbox" name='re_registration_reestr' checked />
										@else
											@if($reestr->re_registration_reestr)
												<input id='re_registration_reestr' class='form-check-input' type="checkbox" name='re_registration_reestr' checked />
											@else
												<input id='re_registration_reestr' class='form-check-input' type="checkbox" name='re_registration_reestr'/>
											@endif
										@endif
									</div>
									<div class="col-md-2">
										<!--<button class="btn btn-secondary" style="float: right;" type='button'>Дополнительные соглашения</button>-->
									</div>
									<div class="col-md-3">
										<!--<button class="btn btn-secondary" style="float: left;" type='button'>Протокол разногласий / согласования</button>-->
									</div>
									<div class="col-md-3">
										<button type='submit' class="btn btn-primary" style="float: right;">Сохранить договор</button>
									</div>
								</div>
								<div class="row">
								</div>
							</form>
						</div>
					@endif
					<!-- Модальное окно история состояний -->
					<div class="modal fade" id="history_states" tabindex="-1" role="dialog" aria-labelledby="historyStatesModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form method='POST' action="{{ route('department.ekonomic.new_state',$contract->id)}}">
									{{csrf_field()}}
									<div class="modal-header">
										<h5 class="modal-title" id="historyStatesModalLabel">История состояний</h5>
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
															</tr>
														</thead>
														<tbody>
															@foreach($states as $state)
																<tr class='rowsContract cursorPointer updateState' id_state='{{$state->id}}' 
																													name_state='{{$state->name_state}}' 
																													date_state='{{$state->date_state}}' 
																													action_state='{{ route("department.ekonomic.update_state",$contract->id)}}'
																													destroy_state='{{ route("department.ekonomic.destroy_state",$state->id)}}'>
																	<td>{{$state->name_state}}</td>
																	<td>{{$state->date_state}}</td>
																</tr>
															@endforeach
														</tbody>
													</table>
												</div>
											@endif
											<div id='add_history_states' class='col-md-12' style='display: none;'>
												<div class='form-group row col-md-12'>
													<input id='id_state' class='form-control' type='text' name='id_state' style='display: none;'/>
												</div>
												<div class='form-group row col-md-12'>
													<label for='new_name_state' class='col-md-3 col-form-label'>Наименование</label>
													<div class='col-md-9'>
														<input id='new_name_state' class='form-control {{$errors->has("new_name_state") ? print("inputError ") : print("")}}' type='text' name='new_name_state'/>
														@if($errors->has('new_name_state'))
															<label class='msgError'>{{$errors->first('new_name_state')}}</label>
														@endif
													</div>
												</div>
												<div class='form-group row col-md-12'>
													<label for='date_state' class='col-md-3 col-form-label'>Дата</label>
													<div class='col-md-9'>
														<input id='date_state' class='datepicker form-control {{$errors->has("date_state") ? print("inputError ") : print("")}}' name='date_state' value='{{date("d.m.Y", time())}}'/>
														@if($errors->has('date_state'))
															<label class='msgError'>{{$errors->first('date_state')}}</label>
														@endif
													</div>
												</div>
											</div>
											<div class='col-md-12'>
												<button id='btn_add_state' class='btn btn-secondary' type='button' clear_date='{{date("d.m.Y", time())}}' action_state='{{ route("department.ekonomic.new_state",$contract->id)}}'>Добавить состояние</button>
											</div>
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
												<button id='add_new_resolution' type='button' class='btn btn-secondary'>Добавить скан</button>
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
								<form id='form_new_application' method='POST' file='true' enctype='multipart/form-data' action='{{route("resolution_store", $contract->id)}}' style='display: none;'>
									{{csrf_field()}}
									<div class="modal-header">
										<h5 class="modal-title" id="updateApplicationModalLabel">Добавление резолюции</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class='modal-body'>
										<div class='row'>
											<div class='col-md-6' style='display: none;'>
												<input type='text' value='id_contract_resolution' name='real_name_document'/>
											</div>
											<div class='col-md-6'>
												<input type='file' name='new_file_resolution'/>
											</div>
											<div class='col-md-6'>
												<input id='date_resolution' name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
										<button id='btn_close_new_application' type="button" class="btn btn-secondary">Закрыть</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				@else
					<div class="content">
						<form method='POST' action="{{route('department.ekonomic.create_reestr')}}">
							{{csrf_field()}}
							<div class="row">
								<div class="col-md-3">
									<input class='form-control' style='color:red; text-align:center;' type='text' value='<?php 
										if($reestr->date_registration_project_reestr)
											if(!$reestr->date_signing_contract_reestr){
												if(time() - strtotime($reestr->date_registration_project_reestr) > 2592000)
													echo 'Не подписан более 30 дней!';
											}else
												if(strtotime($reestr->date_signing_contract_reestr) - strtotime($reestr->date_registration_project_reestr) > 2592000)
													echo 'Не был подписан более 30 дней!';
									?>' readonly />
								</div>
								<div class="col-md-2">
									<label for='amount_contract_reestr'>Сумма по договору (фиксир.)</label>
								</div>
								<div class="col-md-2">
									<input id='amount_contract_reestr' class='form-control' name='amount_contract_reestr' type='text' value='{{old("amount_contract_reestr")}}'/>
								</div>
								<!--<div class="col-md-1">
									<label for='amount_invoice_reestr'>Сумма по счетам</label>
								</div>
								<div class="col-md-2">
									<input id='amount_invoice_reestr' class='form-control' name='amount_invoice_reestr' type='text' value='{{old("amount_invoice_reestr")}}'/>
								</div>-->
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for='numberContract'>Номер договора</label>
										<input id='numberContract' class='form-control {{$errors->has("number_contract") ? print("inputError ") : print("")}}' name='number_contract' type='text' value='{{old("number_contract")}}'/>
										@if($errors->has('number_contract'))
											<label class='msgError'>{{$errors->first('number_contract')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-1">
									<div class="form-group">
										<label for='year_contract'>Год</label>
										<input id='year_contract' class='form-control {{$errors->has("year_contract") ? print("inputError ") : print("")}}' name='year_contract' type='text' value='{{old("year_contract")}}' required/>
										@if($errors->has('year_contract'))
											<label class='msgError'>{{$errors->first('year_contract')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-1">
									<div class='form-check'>
										<label class='form-check-label' for='gozCheck'>ГОЗ</label>
										@if(old('goz_contract'))
											<input id='gozCheck' class='form-check-input' name='goz_contract' type="checkbox" onclick="$('#exportCheck').prop('checked', !$('#exportCheck').prop('checked'))" checked />
										@else
											<input id='gozCheck' class='form-check-input' name='goz_contract' type="checkbox" onclick="$('#exportCheck').prop('checked', !$('#exportCheck').prop('checked'))"/>
										@endif
									</div>
									<div class='form-check'>
										<label class='form-check-label' for='exportCheck'>Экспорт</label>
										@if(old('goz_contract'))
											<input id='exportCheck' class='form-check-input' type="checkbox" onclick="$('#gozCheck').prop('checked', !$('#gozCheck').prop('checked'))"/>
										@else
											<input id='exportCheck' class='form-check-input' type="checkbox" onclick="$('#gozCheck').prop('checked', !$('#gozCheck').prop('checked'))" checked />
										@endif
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="sel3">Вид работ</span></label>
										<select id="sel3" class='form-control {{$errors->has("id_view_work_contract") ? print("inputError ") : print("")}}' name='id_view_work_contract' required>
											<option></option>
											@if($viewWorks)
												@foreach($viewWorks as $viewWork)
													@if(old('id_view_work_contract'))
														@if(old('id_view_work_contract') == $viewWork->id)
															<option value='{{$viewWork->id}}' selected>{{ $viewWork->name_view_work }}</option>
														@else
															<option value='{{$viewWork->id}}'>{{ $viewWork->name_view_work }}</option>
														@endif
													@else
														<option value='{{$viewWork->id}}'>{{ $viewWork->name_view_work }}</option>
													@endif
												@endforeach
											@endif
										</select>
										@if($errors->has('id_view_work_contract'))
											<label class='msgError'>{{$errors->first('id_view_work_contract')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="sel5">Отбор поставщика</span></label>
										<select id="sel5" class='form-control {{$errors->has("selection_supplier_reestr") ? print("inputError ") : print("")}}' name='selection_supplier_reestr'>
											<option></option>
											@foreach($selection_suppliers as $selection_supplier)
												@if(old('selection_supplier_reestr'))
													@if(old('selection_supplier_reestr') == $selection_supplier->id)
														<option value='{{$selection_supplier->id}}' selected>{{$selection_supplier->name_selection_supplier}}</option>
													@else
														<option value='{{$selection_supplier->id}}'>{{$selection_supplier->name_selection_supplier}}</option>
													@endif
												@else
													<option value='{{$selection_supplier->id}}'>{{$selection_supplier->name_selection_supplier}}</option>
												@endif
											@endforeach
										</select>
										@if($errors->has('selection_supplier_reestr'))
											<label class='msgError'>{{$errors->first('selection_supplier_reestr')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-1">
									<div class="row col-md-12">
										@if(old('renouncement_contract'))
											<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" checked />
										@else
											<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox"/>
										@endif
										<label class='form-check-label' for='break'>ОТКАЗ</label>
									</div>
									<div class="row col-md-12">
										@if(old('archive_contract'))
											<input id='archive' class='form-check-input' name='archive_contract' type="checkbox" checked />
										@else
											<input id='archive' class='form-check-input' name='archive_contract' type="checkbox"/>
										@endif
										<label class='form-check-label' for='archive'>АРХИВ</label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<label for='sel6'>Тип документа</label>
									<select id="sel6" class='form-control {{$errors->has("type_document_reestr") ? print("inputError ") : print("")}}' name='type_document_reestr'>
										<option></option>
										@foreach($type_documents as $type_document)
											@if(old('type_document_reestr'))
												@if(old('type_document_reestr') == $type_document->id)
													<option value='{{$type_document->id}}' selected>{{$type_document->name_type_document}}</option>
												@else
													<option value='{{$type_document->id}}'>{{$type_document->name_type_document}}</option>
												@endif
											@else
												<option value='{{$type_document->id}}'>{{$type_document->name_type_document}}</option>
											@endif
										@endforeach
									</select>
								</div>
								<div class="col-md-2">
									<label for='place_save_contract_reestr'>Место хранения</label>
									<input id='place_save_contract_reestr' class='form-control {{$errors->has("place_save_contract_reestr") ? print("inputError ") : print("")}}' name='place_save_contract_reestr' value='{{old("place_save_contract_reestr")}}'/>
									@if($errors->has('place_save_contract'))
										<label class='msgError'>{{$errors->first('place_save_contract')}}</label>
									@endif
								</div>
								<div class="col-md-2">
									<label for='executor_reestr'>Исполнитель</label>
									<select class='form-control' name='executor_reestr'>
										<option></option>
										@if(old('executor_reestr'))
											@foreach($curators as $in_curators)
												@if(old('executor_reestr') == $in_curators->id)
													<option value='{{$in_curators->id}}' selected>{{$in_curators->FIO}}</option>
												@else
													<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
												@endif
											@endforeach
										@else
											@foreach($curators as $in_curators)
												<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
											@endforeach
										@endif
									</select>
									@if($errors->has('executor_reestr'))
										<label class='msgError'>{{$errors->first('executor_reestr')}}</label>
									@endif
								</div>
								<div class="col-md-1" style='text-align: right;'>
									<label class='form-check-label'>Оригинал договора</label>
								</div>
								<div class="col-md-2">
									<div class="row">
										<div class='col-md-5'>
											<label for='oudCheck' class='form-check-label'>ОУД</label>
										</div>
										<div class='col-md-7'>
											@if(old('oud_original_contract_reestr'))
												<input id='oudCheck' class='form-check-input' name='oud_original_contract_reestr' type="checkbox" checked />
											@else
												<input id='oudCheck' class='form-check-input' name='oud_original_contract_reestr' type="checkbox" />
											@endif
										</div>
									</div>
									<div class="row">
										<div class='col-md-5'>
											<label for='otdCheck' class='form-check-label'>Отдел 31</label>
										</div>
										<div class='col-md-7'>
											@if(old('otd_original_contract_reestr'))
												<input id='otdCheck' class='form-check-input' name='otd_original_contract_reestr' type="checkbox" checked />
											@else
												<input id='otdCheck' class='form-check-input' name='otd_original_contract_reestr' type="checkbox"/>
											@endif
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-9">
									<div class='row'>
										<div class="col-md-3">
											<div class="row">
												<div class="col-md-7">
													<label for='date_registration_project_reestr'>Дата регистрации проекта</label>
												</div>
												<div class="col-md-5">
													<input id='date_registration_project_reestr' class='datepicker form-control {{$errors->has("date_registration_project_reestr") ? print("inputError ") : print("")}}' name='date_registration_project_reestr' value='{{old("date_registration_project_reestr")}}'/>
													@if($errors->has('date_registration_project_reestr'))
														<label class='msgError'>{{$errors->first('date_registration_project_reestr')}}</label>
													@endif
												</div>
											</div>
											<div class="row">
												<div class="col-md-7">
													<label for='date_signing_contract_reestr'>Дата подписания договора</label>
												</div>
												<div class="col-md-5">
													<input id='date_signing_contract_reestr' class='datepicker form-control {{$errors->has("date_signing_contract_reestr") ? print("inputError ") : print("")}}' name='date_signing_contract_reestr' value='{{old("date_signing_contract_reestr")}}'/>
													@if($errors->has('date_signing_contract_reestr'))
														<label class='msgError'>{{$errors->first('date_signing_contract_reestr')}}</label>
													@endif
												</div>
											</div>
											<div class="row">
												<div class="col-md-7">
													<label for='date_the_end_contract_reestr'>Дата сдачи договора</label>
												</div>
												<div class="col-md-5">
													<input id='date_the_end_contract_reestr' class='datepicker form-control {{$errors->has("date_the_end_contract_reestr") ? print("inputError ") : print("")}}' name='date_the_end_contract_reestr' value='{{old("date_the_end_contract_reestr")}}'/>
													@if($errors->has('date_the_end_contract_reestr'))
														<label class='msgError'>{{$errors->first('date_the_end_contract_reestr')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class="col-md-9">
											<div class="row col-md-12" style='text-align: center;'>
												<label>Согласование крупной сделки</label>
											</div>
											<div class="row col-md-12">
												<div class="col-md-2">
													<label for='number_inquiry_reestr'>Запрос №</label>
												</div>
												<div class="col-md-5">
													<input id='number_inquiry_reestr' class='form-control {{$errors->has("number_inquiry_reestr") ? print("inputError ") : print("")}}' name='number_inquiry_reestr' value='{{old("number_inquiry_reestr")}}'/>
													@if($errors->has('number_inquiry_reestr'))
														<label class='msgError'>{{$errors->first('number_inquiry_reestr')}}</label>
													@endif
												</div>
												<div class="col-md-1">
													<label for='date_inquiry_reestr'>от</label>
												</div>
												<div class="col-md-3">
													<input id='date_inquiry_reestr' class='datepicker form-control {{$errors->has("date_inquiry_reestr") ? print("inputError ") : print("")}}' name='date_inquiry_reestr' value='{{old("date_inquiry_reestr")}}'/>
													@if($errors->has('date_inquiry_reestr'))
														<label class='msgError'>{{$errors->first('date_inquiry_reestr')}}</label>
													@endif
												</div>
											</div>
											<div class="row col-md-12">
												<div class="col-md-2">
													<label for='number_answer_reestr'>Ответ №</label>
												</div>
												<div class="col-md-5">
													<input id='number_answer_reestr' class='form-control {{$errors->has("number_answer_reestr") ? print("inputError ") : print("")}}' name='number_answer_reestr' value='{{old("number_answer_reestr")}}'/>
													@if($errors->has('number_answer_reestr'))
														<label class='msgError'>{{$errors->first('number_answer_reestr')}}</label>
													@endif
												</div>
												<div class="col-md-1">
													<label for='date_answer_reestr'>от</label>
												</div>
												<div class="col-md-3">
													<input id='date_answer_reestr' class='datepicker form-control {{$errors->has("date_answer_reestr") ? print("inputError ") : print("")}}' name='date_answer_reestr' value='{{old("date_answer_reestr")}}'/>
													@if($errors->has('date_answer_reestr'))
														<label class='msgError'>{{$errors->first('date_answer_reestr')}}</label>
													@endif
												</div>
											</div>
										</div>
									</div>
									<div class='row'>
										<div class="row col-md-8">
											<div class="col-md-1">
												<div class='row'>
													<div class="col-md-7">
														<label for='procurement_reestr'>Закуп</label>
													</div>
													<div class="col-md-5">
														@if(old('procurement_reestr'))
															<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" checked />
														@else
															<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" />
														@endif
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class='row'>
													<div class="col-md-5">
														<label for='sel7'>Основание</label>
													</div>
													<div class="col-md-7">
														<select id="sel7" class='form-control {{$errors->has("base_reestr") ? print("inputError ") : print("")}}' name='base_reestr'>
															<option></option>
															@foreach($bases as $base)
																@if(old('base_reestr'))
																	@if(old('base_reestr') == $base->id)
																		<option value='{{$base->id}}' selected>{{$base->name_base}}</option>
																	@else
																		<option value='{{$base->id}}'>{{$base->name_base}}</option>
																	@endif
																@else
																	<option value='{{$base->id}}'>{{$base->name_base}}</option>
																@endif
															@endforeach
														</select>
													</div>
												</div>
											</div>
											<div class="col-md-2">
												<div class='row'>
													<div class="col-md-7">
														<label for='marketing_reestr'>Сбыт</label>
													</div>
													<div class="col-md-5">
														@if(old('marketing_reestr'))
															<input id='marketing_reestr' class='form-check-input' name='marketing_reestr' type="checkbox" checked />
														@else
															<input id='marketing_reestr' class='form-check-input' name='marketing_reestr' type="checkbox" />
														@endif
													</div>
												</div>
											</div>
											<div class="col-md-3">
												<div class='row'>
													<div class="col-md-7">
														<label for='investments_reestr'>Инвестиции</label>
													</div>
													<div class="col-md-5">
														@if(old('investments_reestr'))
															<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" checked />
														@else
															<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" />
														@endif
													</div>
												</div>
											</div>
											<div class="col-md-2">
												<div class='row'>
													<div class="col-md-7">
														<label for='other_reestr'>Иные</label>
													</div>
													<div class="col-md-5">
														@if(old('other_reestr'))
															<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" checked />
														@else
															<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" />
														@endif
													</div>
												</div>
											</div>
										</div>
										<div class="row col-md-4">
											<div class="col-md-12">
												<div class='row'>
													<div class="col-md-3">
														<label for='okpo_reestr'>ОКПО</label>
													</div>
													<div class="col-md-7">
														<input id='okpo_reestr' class='form-control {{$errors->has("okpo_reestr") ? print("inputError ") : print("")}}' name='okpo_reestr' value='{{old("okpo_reestr")}}'/>
													</div>
												</div>
											</div>
											<div class="col-md-12">
												<div class='row'>
													<div class="col-md-3">
														<label for='okved_reestr'>ОКВЭД</label>
													</div>
													<div class="col-md-7">
														<input id='okved_reestr' class='form-control {{$errors->has("okved_reestr") ? print("inputError ") : print("")}}' name='okved_reestr' value='{{old("okved_reestr")}}'/>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="row col-md-12">
										<div class='row'>
											<div class="col-md-8">
												<label for='number_contestants_reestr'>Кол. участников конкурса</label>
											</div>
											<div class="col-md-4">
												<input id='number_contestants_reestr' class='form-control {{$errors->has("number_contestants_reestr") ? print("inputError ") : print("")}}' name='number_contestants_reestr' value='{{old("number_contestants_reestr")}}'/>
												@if($errors->has('number_contestants_reestr'))
													<label class='msgError'>{{$errors->first('number_contestants_reestr')}}</label>
												@endif
											</div>
										</div>
									</div>
									<div class="row col-md-12">
										<div class='row'>
											<div class="col-md-8">
												<label for='denied_admission_reestr'>Отказано в допуске к участию</label>
											</div>
											<div class="col-md-4">
												<input id='denied_admission_reestr' class='form-control {{$errors->has("denied_admission_reestr") ? print("inputError ") : print("")}}' name='denied_admission_reestr' value='{{old("denied_admission_reestr")}}'/>
												@if($errors->has('denied_admission_reestr'))
													<label class='msgError'>{{$errors->first('denied_admission_reestr')}}</label>
												@endif
											</div>
										</div>
									</div>
									<div class="row col-md-12" style='text-align: left;'>
										<div class='row'>
											<div class="col-md-6">
												<label>Обеспечение</label>
											</div>
											<div class="col-md-3" style='text-align: center;'>
												<label>Заявки</label>
											</div>
											<div class="col-md-3" style='text-align: center;'>
												<label>Договора</label>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-6">
												<label>Наличные</label>
											</div>
											<div class="col-md-3" style='text-align: center;'>
												@if(old('cash_order_reestr'))
													<input class='form-check-input' name='cash_order_reestr' type="checkbox" checked />
												@else
													<input class='form-check-input' name='cash_order_reestr' type="checkbox" />
												@endif
											</div>
											<div class="col-md-3" style='text-align: center;'>
												@if(old('cash_contract_reestr'))
													<input class='form-check-input' name='cash_contract_reestr' type="checkbox" checked />
												@else
													<input class='form-check-input' name='cash_contract_reestr' type="checkbox" />
												@endif
											</div>
										</div>
										<div class='row'>
											<div class="col-md-6">
												<label>Безналичные</label>
											</div>
											<div class="col-md-3" style='text-align: center;'>
												@if(old('non_cash_order_reestr'))
													<input class='form-check-input' name='non_cash_order_reestr' type="checkbox" checked />
												@else
													<input class='form-check-input' name='non_cash_order_reestr' type="checkbox"/>
												@endif
											</div>
											<div class="col-md-3" style='text-align: center;'>
												@if(old('non_cash_contract_reestr'))
													<input class='form-check-input' name='non_cash_contract_reestr' type="checkbox" checked />
												@else
													<input class='form-check-input' name='non_cash_contract_reestr' type="checkbox" />
												@endif
											</div>
										</div>
										<div class='row'>
											<div class="col-md-6">
												<label for='date_execution_reestr'>Дата исполнения</label>
											</div>
											<div class="col-md-6">
												<input id='date_execution_reestr' class='datepicker form-control {{$errors->has("date_execution_reestr") ? print("inputError ") : print("")}}' name='date_execution_reestr' value='{{old("date_execution_reestr")}}'/>
												@if($errors->has('date_execution_reestr'))
													<label class='msgError'>{{$errors->first('date_execution_reestr')}}</label>
												@endif
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<label>Название предприятия</label>
								</div>
								<div class="col-md-3">

								</div>
								<div class="col-md-3">

								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<select id="sel4" class='form-control {{$errors->has("id_counterpartie_contract") ? print("inputError ") : print("")}}' name='id_counterpartie_contract' required>
											<option></option>
											@if($counterparties)
												@foreach($counterparties as $counterpartie)
													@if(old('id_counterpartie_contract'))
														@if(old('id_counterpartie_contract') == $counterpartie->id)
															<option value='{{$counterpartie->id}}' selected>{{ $counterpartie->name_full }}</option>
														@else
															@if(Auth::User()->hasRole()->role == 'Администратор')
																<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
															@elseif(Auth::User()->hasRole()->role == 'Отдел управления договорами')
																@if($counterpartie->is_sip_counterpartie == '0')
																	<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
																@endif
															@else
																@if($counterpartie->is_sip_counterpartie == '1')
																	<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
																@endif
															@endif													
														@endif
													@else
														@if(Auth::User()->hasRole()->role == 'Администратор')
															<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
														@elseif(Auth::User()->hasRole()->role == 'Отдел управления договорами')
															@if($counterpartie->is_sip_counterpartie == '0')
																<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
															@endif
														@else
															@if($counterpartie->is_sip_counterpartie == '1')
																<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
															@endif
														@endif
													@endif
												@endforeach
											@endif
										</select>
										@if($errors->has('id_counterpartie_contract'))
											<label class='msgError'>{{$errors->first('id_counterpartie_contract')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-12">
									<div class='row'>
										<div class="col-md-2">
											<label for='number_counterpartie_contract_reestr'>№ дог. контрагента</label>
										</div>
										<div class="col-md-10">
											<input id='number_counterpartie_contract_reestr' class='form-control {{$errors->has("number_counterpartie_contract_reestr") ? print("inputError ") : print("")}}' name='number_counterpartie_contract_reestr' value='{{old("number_counterpartie_contract_reestr")}}'/>
											@if($errors->has('number_counterpartie_contract_reestr'))
												<label class='msgError'>{{$errors->first('number_counterpartie_contract_reestr')}}</label>
											@endif
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="row">
										<div class="col-md-12">
											<div class='form-group'>
												<label for='nameWork'>Наименование работ</label>
												<textarea id='nameWork' class='form-control {{$errors->has("name_work_contract") ? print("inputError ") : print("")}}' name='name_work_contract' type="text" style="width: 100%;" rows='4' required>{{ old('name_work_contract') }}</textarea>
												@if($errors->has('name_work_contract'))
													<label class='msgError'>{{$errors->first('name_work_contract')}}</label>
												@endif
											</div>
										</div>
									</div>
									<!--<div class="row">
										<div class="col-md-12">
											<label for='lastCompleteContract'>Состояние заключения договора</label>
										</div>								
									</div>
									<div class="row">
										<div class="col-md-12">
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
										<div class="col-md-12">
											<div class='form-group'>
												@if(count($states) > 0)
													@if($states[count($states) - 1]->name_state == "Заключен" OR $states[count($states) - 1]->name_state == "Заключён")
														<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%; display: none;" rows='3' disabled></textarea>
													@else
														<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%;" rows='3' disabled>{{$states[count($states) - 1]->name_state}}</textarea>
													@endif
												@else
													<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%;" rows='3' disabled></textarea>
												@endif
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class="btn btn-primary" data-toggle="modal" data-target="#history_states" type='button'>История состояний</button>
										</div>
									</div>-->
								</div>
								<div class="col-md-9">
									<div class="row">
										<div class="col-md-3">
											<label for='okdp_reestr'>ОКДП</label>
											<div class="row col-md-12">
												<input id='okdp_reestr' class='form-control {{$errors->has("okdp_reestr") ? print("inputError ") : print("")}}' name='okdp_reestr' value='{{ old("okdp_reestr") }}'/>
											</div>
										</div>
										<div class="col-md-7">
											<label>Гарантия банка</label>
											<div class='row'>
												<div class="col-md-1">
													<label for='date_bank_reestr'>до</label>
												</div>
												<div class="col-md-2">
													<input id='date_bank_reestr' class='datepicker form-control {{$errors->has("date_bank_reestr") ? print("inputError ") : print("")}}' name='date_bank_reestr' value='{{ old("date_bank_reestr") }}'/>
												</div>
												<div class="col-md-1">
													<label for='amount_bank_reestr'>Сумма</label>
												</div>
												<div class="col-md-3">
													<input id='amount_bank_reestr' class='form-control {{$errors->has("amount_bank_reestr") ? print("inputError ") : print("")}}' name='amount_bank_reestr' value='{{ old("amount_bank_reestr") }}'/>
												</div>
												<div class="col-md-1">
													<label for='bank_reestr'>Банк</label>
												</div>
												<div class="col-md-4">
													<input id='bank_reestr' class='form-control {{$errors->has("bank_reestr") ? print("inputError ") : print("")}}' name='bank_reestr' value='{{ old("bank_reestr") }}'/>
												</div>
											</div>
										</div>
										<div class="col-md-2">
											<div class='form-group row'>
												<div class='col-md-12' style='text-align: right;'>
													<!--<button class='btn btn-primary' data-toggle="modal" data-target="#scan" type='button'>Сканы</button>-->
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-2">
											<div class='row'>
												<div class="col-md-12">
													<label for='date_contract_reestr'>Срок действия договора</label>
												</div>
											</div>
										</div>
										<div class="col-md-5">
											<div class='row'>
												<div class="col-md-1">
													<label for='date_b_contract_reestr'>с</label>
												</div>
												<div class="col-md-5">
													<input id='date_b_contract_reestr' class='datepicker form-control {{$errors->has("date_b_contract_reestr") ? print("inputError ") : print("")}}' name='date_b_contract_reestr' value='{{ old("date_b_contract_reestr") }}'/>
												</div>
												<div class="col-md-1">
													<label for='date_e_contract_reestr'>по</label>
												</div>
												<div class="col-md-5">
													<input id='date_e_contract_reestr' class='datepicker form-control {{$errors->has("date_e_contract_reestr") ? print("inputError ") : print("")}}' name='date_e_contract_reestr' value='{{ old("date_e_contract_reestr") }}'/>
												</div>
											</div>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-10">
											<input id='date_contract_reestr' class='form-control {{$errors->has("date_contract_reestr") ? print("inputError ") : print("")}}' name='date_contract_reestr' value='{{ old("date_contract_reestr") }}'/>
										</div>
									</div>
									<div class="row">
										<div class="col-md-10">
											<label for='date_maturity_reestr'>Срок исполнения обязательств</label>
											<input id='date_maturity_reestr' class='form-control {{$errors->has("date_maturity_reestr") ? print("inputError ") : print("")}}' name='date_maturity_reestr' value='{{ old("date_maturity_reestr") }}'/>
										</div>
									</div>
									<div class="row">
										<div class="col-md-10">
											<div class='row'>
												<div class="col-md-1">
													<label for='amount_reestr'>Сумма (начальная)</label>
												</div>
												<div class="col-md-3">
													<input id='amount_reestr' class='form-control {{$errors->has("amount_reestr") ? print("inputError ") : print("")}}' name='amount_reestr' value='{{old("amount_reestr")}}'/>
												</div>
												<div class="col-md-2">
													<select id="sel8" class='form-control {{$errors->has("unit_reestr") ? print("inputError ") : print("")}}' name='unit_reestr'>
														<option></option>
														@foreach($units as $unit)
															@if(old('unit_reestr'))
																<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
															@else
																<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
															@endif
														@endforeach
													</select>
												</div>
												<div class="col-md-2">
													<label for='VAT'>НДС</label>
													@if(old('vat_reestr'))
														<input id='VAT' class='form-check-input' name='vat_reestr' type="checkbox" checked />
													@else
														<input id='VAT' class='form-check-input' name='vat_reestr' type="checkbox"/>
													@endif
												</div>
											</div>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-10">
											<input class='form-control {{$errors->has("amount_comment_reestr") ? print("inputError ") : print("")}}' name='amount_comment_reestr' value='{{old("amount_comment_reestr")}}'/>
										</div>
									</div>
									<div class="row">
										<div class="col-md-10">
											<label for='payment_order_reestr'>Порядок оплаты</label>
											<textarea id='payment_order_reestr' class='form-control' type="text" style="width: 100%;" rows='3' name='payment_order_reestr'>{{old("payment_order_reestr")}}</textarea>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<label for='prolongation_reestr'>Пролонгация</label>
									@if(old('prolongation_reestr'))
										<input id='prolongation_reestr' class='form-check-input' type="checkbox" name='prolongation_reestr' checked />
									@else
										<input id='prolongation_reestr' class='form-check-input' type="checkbox" name='prolongation_reestr'/>
									@endif
								</div>
								<div class="col-md-2">
									<label for='re_registration_reestr'>Перерегистрация</label>
									@if(old('re_registration_reestr'))
										<input id='re_registration_reestr' class='form-check-input' type="checkbox" name='re_registration_reestr' checked />
									@else
										<input id='re_registration_reestr' class='form-check-input' type="checkbox" name='re_registration_reestr'/>
									@endif
								</div>
								<div class="col-md-2">
									<!--<button class="btn btn-secondary" style="float: right;" type='button'>Дополнительные соглашения</button>-->
								</div>
								<div class="col-md-3">
									<!--<button class="btn btn-secondary" style="float: left;" type='button'>Протокол разногласий / согласования</button>-->
								</div>
								<div class="col-md-3">
									<button type='submit' class="btn btn-primary" style="float: right;">Создать договор</button>
								</div>
							</div>
							<div class="row">
							</div>
						</form>
					</div>
				@endif
			@else
				@if($contract)
					<div class="content">
						<div class="row">
							<div class="col-md-3">
								<input class='form-control' style='color:red; text-align:center;' type='text' value='<?php 
									if($reestr->date_registration_project_reestr)
										if(!$reestr->date_signing_contract_reestr){
											if(time() - strtotime($reestr->date_registration_project_reestr) > 2592000)
												echo 'Не подписан более 30 дней!';
										}else
											if(strtotime($reestr->date_signing_contract_reestr) - strtotime($reestr->date_registration_project_reestr) > 2592000)
												echo 'Не был подписан более 30 дней!';
								?>' readonly />
							</div>
							<div class="col-md-2">
								<label for='amount_contract_reestr'>Сумма по договору (фиксир.)</label>
							</div>
							<div class="col-md-2">
								<input id='amount_contract_reestr' class='form-control' name='amount_contract_reestr' type='text' value='{{old("amount_contract_reestr") ? old("amount_contract_reestr") : $reestr->amount_contract_reestr}}' readonly />
							</div>
							<!--<div class="col-md-1">
								<label for='amount_invoice_reestr'>Сумма по счетам</label>
							</div>
							<div class="col-md-2">
								<input id='amount_invoice_reestr' class='form-control' name='amount_invoice_reestr' type='text' value='{{old("amount_invoice_reestr") ? old("amount_invoice_reestr") : $reestr->amount_invoice_reestr}}'/>
							</div>-->
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for='numberContract'>Номер договора</label>
									<input id='numberContract' class='form-control {{$errors->has("number_contract") ? print("inputError ") : print("")}}' name='number_contract' type='text' value='{{old("number_contract") ? old("number_contract") : $contract->number_contract}}' readonly />
									@if($errors->has('number_contract'))
										<label class='msgError'>{{$errors->first('number_contract')}}</label>
									@endif
								</div>
							</div>
							<div class="col-md-1">
								<div class="form-group">
									<label for='year_contract'>Год</label>
									<input id='year_contract' class='form-control {{$errors->has("year_contract") ? print("inputError ") : print("")}}' name='year_contract' type='text' value='{{old("year_contract") ? old("year_contract") : $contract->year_contract}}' readonly />
									@if($errors->has('year_contract'))
										<label class='msgError'>{{$errors->first('year_contract')}}</label>
									@endif
								</div>
							</div>
							<div class="col-md-1">
								<div class='form-check'>
									<label class='form-check-label' for='gozCheck'>ГОЗ</label>
									@if(old('goz_contract'))
										<input id='gozCheck' class='form-check-input' name='goz_contract' type="checkbox" onclick="$('#exportCheck').prop('checked', !$('#exportCheck').prop('checked'))" checked disabled />
									@else
										@if($contract->id_goz_contract == 1)
											<input id='gozCheck' class='form-check-input' name='goz_contract' type="checkbox" onclick="$('#exportCheck').prop('checked', !$('#exportCheck').prop('checked'))" checked disabled />
										@else
											<input id='gozCheck' class='form-check-input' name='goz_contract' type="checkbox" onclick="$('#exportCheck').prop('checked', !$('#exportCheck').prop('checked'))" disabled />
										@endif
									@endif
								</div>
								<div class='form-check'>
									<label class='form-check-label' for='exportCheck'>Экспорт</label>
									@if(old('goz_contract'))
										<input id='exportCheck' class='form-check-input' type="checkbox" onclick="$('#gozCheck').prop('checked', !$('#gozCheck').prop('checked'))" disabled />
									@else
										@if($contract->id_goz_contract != 1)
											<input id='exportCheck' class='form-check-input' type="checkbox" onclick="$('#gozCheck').prop('checked', !$('#gozCheck').prop('checked'))" checked disabled />
										@else
											<input id='exportCheck' class='form-check-input' type="checkbox" onclick="$('#gozCheck').prop('checked', !$('#gozCheck').prop('checked'))" disabled />
										@endif
									@endif
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="sel3">Вид работ</span></label>
									<select id="sel3" class='form-control {{$errors->has("id_view_work_contract") ? print("inputError ") : print("")}}' name='id_view_work_contract' disabled>
										<option></option>
										@if($viewWorks)
											@foreach($viewWorks as $viewWork)
												@if(old('id_view_work_contract'))
													@if(old('id_view_work_contract') == $viewWork->id)
														<option value='{{$viewWork->id}}' selected>{{ $viewWork->name_view_work }}</option>
													@else
														<option value='{{$viewWork->id}}'>{{ $viewWork->name_view_work }}</option>
													@endif
												@else
													@if($contract->id_view_work_contract == $viewWork->id)
														<option value='{{$viewWork->id}}' selected>{{ $viewWork->name_view_work }}</option>
													@else
														<option value='{{$viewWork->id}}'>{{ $viewWork->name_view_work }}</option>
													@endif
												@endif
											@endforeach
										@endif
									</select>
									@if($errors->has('id_view_work_contract'))
										<label class='msgError'>{{$errors->first('id_view_work_contract')}}</label>
									@endif
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="sel5">Отбор поставщика</span></label>
									<select id="sel5" class='form-control {{$errors->has("selection_supplier_reestr") ? print("inputError ") : print("")}}' name='selection_supplier_reestr' disabled>
										<option></option>
										@foreach($selection_suppliers as $selection_supplier)
											@if(old('selection_supplier_reestr'))
												@if(old('selection_supplier_reestr') == $selection_supplier->id)
													<option value='{{$selection_supplier->id}}' selected>{{$selection_supplier->name_selection_supplier}}</option>
												@else
													<option value='{{$selection_supplier->id}}'>{{$selection_supplier->name_selection_supplier}}</option>
												@endif
											@else
												@if($reestr->selection_supplier_reestr == $selection_supplier->id)
													<option value='{{$selection_supplier->id}}' selected>{{$selection_supplier->name_selection_supplier}}</option>
												@else
													<option value='{{$selection_supplier->id}}'>{{$selection_supplier->name_selection_supplier}}</option>
												@endif
											@endif
										@endforeach
									</select>
									@if($errors->has('selection_supplier_reestr'))
										<label class='msgError'>{{$errors->first('selection_supplier_reestr')}}</label>
									@endif
								</div>
							</div>
							<div class="col-md-1">
								<div class="row col-md-12">
									@if(old('renouncement_contract'))
										<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" checked disabled />
									@else
										@if($contract->renouncement_contract == 1)
											<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" checked disabled />
										@else
											<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" disabled />
										@endif
									@endif
									<label class='form-check-label' for='break'>ОТКАЗ</label>
								</div>
								<div class="row col-md-12">
									@if(old('archive_contract'))
										<input id='archive' class='form-check-input' name='archive_contract' type="checkbox" checked disabled />
									@else
										@if($contract->archive_contract == 1)
											<input id='archive' class='form-check-input' name='archive_contract' type="checkbox" checked disabled />
										@else
											<input id='archive' class='form-check-input' name='archive_contract' type="checkbox" disabled />
										@endif
									@endif
									<label class='form-check-label' for='archive'>АРХИВ</label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<label for='sel6'>Тип документа</label>
								<select id="sel6" class='form-control {{$errors->has("type_document_reestr") ? print("inputError ") : print("")}}' name='type_document_reestr' disabled >
									<option></option>
									@foreach($type_documents as $type_document)
										@if(old('type_document_reestr'))
											@if(old('type_document_reestr') == $type_document->id)
												<option value='{{$type_document->id}}' selected>{{$type_document->name_type_document}}</option>
											@else
												<option value='{{$type_document->id}}'>{{$type_document->name_type_document}}</option>
											@endif
										@else
											@if($reestr->type_document_reestr == $type_document->id)
												<option value='{{$type_document->id}}' selected>{{$type_document->name_type_document}}</option>
											@else
												<option value='{{$type_document->id}}'>{{$type_document->name_type_document}}</option>
											@endif
										@endif
									@endforeach
								</select>
							</div>
							<div class="col-md-2">
								<label for='place_save_contract_reestr'>Место хранения</label>
								<input id='place_save_contract_reestr' class='form-control {{$errors->has("place_save_contract_reestr") ? print("inputError ") : print("")}}' name='place_save_contract_reestr' value='{{old("place_save_contract_reestr") ? old("place_save_contract_reestr") : $reestr->place_save_contract_reestr}}' readonly />
								@if($errors->has('place_save_contract'))
									<label class='msgError'>{{$errors->first('place_save_contract')}}</label>
								@endif
							</div>
							<div class="col-md-2">
								<label for='executor_reestr'>Исполнитель</label>
								<select class='form-control' name='executor_reestr' disabled>
									<option></option>
									@if(old('executor_reestr'))
										@foreach($curators as $in_curators)
											@if(old('executor_reestr') == $in_curators->id)
												<option value='{{$in_curators->id}}' selected>{{$in_curators->FIO}}</option>
											@else
												<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
											@endif
										@endforeach
									@else
										@foreach($curators as $in_curators)
											@if($reestr->executor_reestr == $in_curators->id)
												<option value='{{$in_curators->id}}' selected>{{$in_curators->FIO}}</option>
											@else
												<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
											@endif
										@endforeach
									@endif
								</select>
								@if($errors->has('executor_reestr'))
									<label class='msgError'>{{$errors->first('executor_reestr')}}</label>
								@endif
							</div>
							<div class="col-md-1" style='text-align: right;'>
								<label class='form-check-label'>Оригинал договора</label>
							</div>
							<div class="col-md-2">
								<div class="row">
									<div class='col-md-5'>
										<label for='oudCheck' class='form-check-label'>ОУД</label>
									</div>
									<div class='col-md-7'>
										@if(old('oud_original_contract_reestr'))
											<input id='oudCheck' class='form-check-input' name='oud_original_contract_reestr' type="checkbox" checked disabled />
										@else
											@if($reestr->oud_original_contract_reestr == 1)
												<input id='oudCheck' class='form-check-input' name='oud_original_contract_reestr' type="checkbox" checked disabled />
											@else
												<input id='oudCheck' class='form-check-input' name='oud_original_contract_reestr' type="checkbox" disabled />
											@endif
										@endif
									</div>
								</div>
								<div class="row">
									<div class='col-md-5'>
										<label for='otdCheck' class='form-check-label'>Отдел 31</label>
									</div>
									<div class='col-md-7'>
										@if(old('otd_original_contract_reestr'))
											<input id='otdCheck' class='form-check-input' name='otd_original_contract_reestr' type="checkbox" checked disabled />
										@else
											@if($reestr->otd_original_contract_reestr == 1)
												<input id='otdCheck' class='form-check-input' name='otd_original_contract_reestr' type="checkbox" checked disabled />
											@else
												<input id='otdCheck' class='form-check-input' name='otd_original_contract_reestr' type="checkbox" disabled />
											@endif
										@endif
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-9">
								<div class='row'>
									<div class="col-md-3">
										<div class="row">
											<div class="col-md-7">
												<label for='date_registration_project_reestr'>Дата регистрации проекта</label>
											</div>
											<div class="col-md-5">
												<input id='date_registration_project_reestr' class='datepicker form-control {{$errors->has("date_registration_project_reestr") ? print("inputError ") : print("")}}' name='date_registration_project_reestr' value='{{old("date_registration_project_reestr") ? old("date_registration_project_reestr") : $reestr->date_registration_project_reestr}}' disabled />
												@if($errors->has('date_registration_project_reestr'))
													<label class='msgError'>{{$errors->first('date_registration_project_reestr')}}</label>
												@endif
											</div>
										</div>
										<div class="row">
											<div class="col-md-7">
												<label for='date_signing_contract_reestr'>Дата подписания договора</label>
											</div>
											<div class="col-md-5">
												<input id='date_signing_contract_reestr' class='datepicker form-control {{$errors->has("date_signing_contract_reestr") ? print("inputError ") : print("")}}' name='date_signing_contract_reestr' value='{{old("date_signing_contract_reestr") ? old("date_signing_contract_reestr") : $reestr->date_signing_contract_reestr}}' disabled />
												@if($errors->has('date_signing_contract_reestr'))
													<label class='msgError'>{{$errors->first('date_signing_contract_reestr')}}</label>
												@endif
											</div>
										</div>
										<div class="row">
											<div class="col-md-7">
												<label for='date_the_end_contract_reestr'>Дата сдачи договора</label>
											</div>
											<div class="col-md-5">
												<input id='date_the_end_contract_reestr' class='datepicker form-control {{$errors->has("date_the_end_contract_reestr") ? print("inputError ") : print("")}}' name='date_the_end_contract_reestr' value='{{old("date_the_end_contract_reestr") ? old("date_the_end_contract_reestr") : $reestr->date_the_end_contract_reestr}}' disabled />
												@if($errors->has('date_the_end_contract_reestr'))
													<label class='msgError'>{{$errors->first('date_the_end_contract_reestr')}}</label>
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-9">
										<div class="row col-md-12" style='text-align: center;'>
											<label>Согласование крупной сделки</label>
										</div>
										<div class="row col-md-12">
											<div class="col-md-2">
												<label for='number_inquiry_reestr'>Запрос №</label>
											</div>
											<div class="col-md-5">
												<input id='number_inquiry_reestr' class='form-control {{$errors->has("number_inquiry_reestr") ? print("inputError ") : print("")}}' name='number_inquiry_reestr' value='{{old("number_inquiry_reestr") ? old("number_inquiry_reestr") : $reestr->number_inquiry_reestr}}' readonly />
												@if($errors->has('number_inquiry_reestr'))
													<label class='msgError'>{{$errors->first('number_inquiry_reestr')}}</label>
												@endif
											</div>
											<div class="col-md-1">
												<label for='date_inquiry_reestr'>от</label>
											</div>
											<div class="col-md-3">
												<input id='date_inquiry_reestr' class='datepicker form-control {{$errors->has("date_inquiry_reestr") ? print("inputError ") : print("")}}' name='date_inquiry_reestr' value='{{old("date_inquiry_reestr") ? old("date_inquiry_reestr") : $reestr->date_inquiry_reestr}}' disabled />
												@if($errors->has('date_inquiry_reestr'))
													<label class='msgError'>{{$errors->first('date_inquiry_reestr')}}</label>
												@endif
											</div>
										</div>
										<div class="row col-md-12">
											<div class="col-md-2">
												<label for='number_answer_reestr'>Ответ №</label>
											</div>
											<div class="col-md-5">
												<input id='number_answer_reestr' class='form-control {{$errors->has("number_answer_reestr") ? print("inputError ") : print("")}}' name='number_answer_reestr' value='{{old("number_answer_reestr") ? old("number_answer_reestr") : $reestr->number_answer_reestr}}' readonly />
												@if($errors->has('number_answer_reestr'))
													<label class='msgError'>{{$errors->first('number_answer_reestr')}}</label>
												@endif
											</div>
											<div class="col-md-1">
												<label for='date_answer_reestr'>от</label>
											</div>
											<div class="col-md-3">
												<input id='date_answer_reestr' class='datepicker form-control {{$errors->has("date_answer_reestr") ? print("inputError ") : print("")}}' name='date_answer_reestr' value='{{old("date_answer_reestr") ? old("date_answer_reestr") : $reestr->date_answer_reestr}}' disabled />
												@if($errors->has('date_answer_reestr'))
													<label class='msgError'>{{$errors->first('date_answer_reestr')}}</label>
												@endif
											</div>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class="row col-md-8">
										<div class="col-md-1">
											<div class='row'>
												<div class="col-md-7">
													<label for='procurement_reestr'>Закуп</label>
												</div>
												<div class="col-md-5">
													@if(old('procurement_reestr'))
														<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" checked disabled />
													@else
														@if($reestr->procurement_reestr)
															<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" checked disabled />
														@else
															<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" disabled />
														@endif
													@endif
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class='row'>
												<div class="col-md-5">
													<label for='sel7'>Основание</label>
												</div>
												<div class="col-md-7">
													<select id="sel7" class='form-control {{$errors->has("base_reestr") ? print("inputError ") : print("")}}' name='base_reestr' disabled>
														<option></option>
														@foreach($bases as $base)
															@if(old('base_reestr'))
																@if(old('base_reestr') == $base->id)
																	<option value='{{$base->id}}' selected>{{$base->name_base}}</option>
																@else
																	<option value='{{$base->id}}'>{{$base->name_base}}</option>
																@endif
															@else
																@if($reestr->base_reestr == $base->id)
																	<option value='{{$base->id}}' selected>{{$base->name_base}}</option>
																@else
																	<option value='{{$base->id}}'>{{$base->name_base}}</option>
																@endif
															@endif
														@endforeach
													</select>
												</div>
											</div>
										</div>
										<div class="col-md-2">
											<div class='row'>
												<div class="col-md-7">
													<label for='marketing_reestr'>Сбыт</label>
												</div>
												<div class="col-md-5">
													@if(old('marketing_reestr'))
														<input id='marketing_reestr' class='form-check-input' name='marketing_reestr' type="checkbox" checked disabled />
													@else
														@if($reestr->marketing_reestr)
															<input id='marketing_reestr' class='form-check-input' name='marketing_reestr' type="checkbox" checked disabled />
														@else
															<input id='marketing_reestr' class='form-check-input' name='marketing_reestr' type="checkbox" disabled />
														@endif
													@endif
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class='row'>
												<div class="col-md-7">
													<label for='investments_reestr'>Инвестиции</label>
												</div>
												<div class="col-md-5">
													@if(old('investments_reestr'))
														<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" checked disabled />
													@else
														@if($reestr->investments_reestr)
															<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" checked disabled />
														@else
															<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" disabled />
														@endif
													@endif
												</div>
											</div>
										</div>
										<div class="col-md-2">
											<div class='row'>
												<div class="col-md-7">
													<label for='other_reestr'>Иные</label>
												</div>
												<div class="col-md-5">
													@if(old('other_reestr'))
														<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" checked disabled />
													@else
														@if($reestr->other_reestr)
															<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" checked disabled />
														@else
															<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" disabled />
														@endif
													@endif
												</div>
											</div>
										</div>
									</div>
									<div class="row col-md-4">
										<div class="col-md-12">
											<div class='row'>
												<div class="col-md-3">
													<label for='okpo_reestr'>ОКПО</label>
												</div>
												<div class="col-md-7">
													<input id='okpo_reestr' class='form-control {{$errors->has("okpo_reestr") ? print("inputError ") : print("")}}' name='okpo_reestr' value='{{old("okpo_reestr") ? old("okpo_reestr") : $reestr->okpo_reestr}}' readonly />
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class='row'>
												<div class="col-md-3">
													<label for='okved_reestr'>ОКВЭД</label>
												</div>
												<div class="col-md-7">
													<input id='okved_reestr' class='form-control {{$errors->has("okved_reestr") ? print("inputError ") : print("")}}' name='okved_reestr' value='{{old("okved_reestr") ? old("okved_reestr") : $reestr->okved_reestr}}' readonly />
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="row col-md-12">
									<div class='row'>
										<div class="col-md-8">
											<label for='number_contestants_reestr'>Кол. участников конкурса</label>
										</div>
										<div class="col-md-4">
											<input id='number_contestants_reestr' class='form-control {{$errors->has("number_contestants_reestr") ? print("inputError ") : print("")}}' name='number_contestants_reestr' value='{{old("number_contestants_reestr") ? old("number_contestants_reestr") : $reestr->number_contestants_reestr}}' readonly />
											@if($errors->has('number_contestants_reestr'))
												<label class='msgError'>{{$errors->first('number_contestants_reestr')}}</label>
											@endif
										</div>
									</div>
								</div>
								<div class="row col-md-12">
									<div class='row'>
										<div class="col-md-8">
											<label for='denied_admission_reestr'>Отказано в допуске к участию</label>
										</div>
										<div class="col-md-4">
											<input id='denied_admission_reestr' class='form-control {{$errors->has("denied_admission_reestr") ? print("inputError ") : print("")}}' name='denied_admission_reestr' value='{{old("denied_admission_reestr") ? old("denied_admission_reestr") : $reestr->denied_admission_reestr}}' readonly />
											@if($errors->has('denied_admission_reestr'))
												<label class='msgError'>{{$errors->first('denied_admission_reestr')}}</label>
											@endif
										</div>
									</div>
								</div>
								<div class="row col-md-12" style='text-align: left;'>
									<div class='row'>
										<div class="col-md-6">
											<label>Обеспечение</label>
										</div>
										<div class="col-md-3" style='text-align: center;'>
											<label>Заявки</label>
										</div>
										<div class="col-md-3" style='text-align: center;'>
											<label>Договора</label>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-6">
											<label>Наличные</label>
										</div>
										<div class="col-md-3" style='text-align: center;'>
											@if(old('cash_order_reestr'))
												<input class='form-check-input' name='cash_order_reestr' type="checkbox" checked disabled />
											@else
												@if($reestr->cash_order_reestr)
													<input class='form-check-input' name='cash_order_reestr' type="checkbox" checked disabled />
												@else
													<input class='form-check-input' name='cash_order_reestr' type="checkbox" disabled />
												@endif
											@endif
										</div>
										<div class="col-md-3" style='text-align: center;'>
											@if(old('cash_contract_reestr'))
												<input class='form-check-input' name='cash_contract_reestr' type="checkbox" checked disabled />
											@else
												@if($reestr->cash_contract_reestr)
													<input class='form-check-input' name='cash_contract_reestr' type="checkbox" checked disabled />
												@else
													<input class='form-check-input' name='cash_contract_reestr' type="checkbox" disabled />
												@endif
											@endif
										</div>
									</div>
									<div class='row'>
										<div class="col-md-6">
											<label>Безналичные</label>
										</div>
										<div class="col-md-3" style='text-align: center;'>
											@if(old('non_cash_order_reestr'))
												<input class='form-check-input' name='non_cash_order_reestr' type="checkbox" checked disabled />
											@else
												@if($reestr->non_cash_order_reestr)
													<input class='form-check-input' name='non_cash_order_reestr' type="checkbox" checked disabled />
												@else
													<input class='form-check-input' name='non_cash_order_reestr' type="checkbox" disabled />
												@endif
											@endif
										</div>
										<div class="col-md-3" style='text-align: center;'>
											@if(old('non_cash_contract_reestr'))
												<input class='form-check-input' name='non_cash_contract_reestr' type="checkbox" checked disabled />
											@else
												@if($reestr->non_cash_contract_reestr)
													<input class='form-check-input' name='non_cash_contract_reestr' type="checkbox" checked disabled />
												@else
													<input class='form-check-input' name='non_cash_contract_reestr' type="checkbox" disabled />
												@endif
											@endif
										</div>
									</div>
									<div class='row'>
										<div class="col-md-6">
											<label for='date_execution_reestr'>Дата исполнения</label>
										</div>
										<div class="col-md-6">
											<input id='date_execution_reestr' class='datepicker form-control {{$errors->has("date_execution_reestr") ? print("inputError ") : print("")}}' name='date_execution_reestr' value='{{old("date_execution_reestr") ? old("date_execution_reestr") : $reestr->date_execution_reestr}}' disabled />
											@if($errors->has('date_execution_reestr'))
												<label class='msgError'>{{$errors->first('date_execution_reestr')}}</label>
											@endif
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<label>Название предприятия</label>
							</div>
							<div class="col-md-3">

							</div>
							<div class="col-md-3">

							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<select id="sel4" class='form-control {{$errors->has("id_counterpartie_contract") ? print("inputError ") : print("")}}' name='id_counterpartie_contract' disabled>
										<option></option>
											@if($counterparties)
												@foreach($counterparties as $counterpartie)
													@if(old('id_counterpartie_contract'))
														@if(old('id_counterpartie_contract') == $counterpartie->id)
															<option value='{{$counterpartie->id}}' selected>{{ $counterpartie->name_full }}</option>
														@else
															@if(Auth::User()->hasRole()->role == 'Администратор')
																<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
															@elseif(Auth::User()->hasRole()->role == 'Отдел управления договорами')
																@if($counterpartie->is_sip_counterpartie == '0')
																	<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
																@endif
															@else
																@if($counterpartie->is_sip_counterpartie == '1')
																	<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
																@endif
															@endif													
														@endif
													@else
														@if($contract->id_counterpartie_contract == $counterpartie->id)
															<option value='{{$counterpartie->id}}' selected>{{ $counterpartie->name_full }}</option>
														@else
															@if(Auth::User()->hasRole()->role == 'Администратор')
																<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
															@elseif(Auth::User()->hasRole()->role == 'Отдел управления договорами')
																@if($counterpartie->is_sip_counterpartie == '0')
																	<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
																@endif
															@else
																@if($counterpartie->is_sip_counterpartie == '1')
																	<option value='{{$counterpartie->id}}'>{{ $counterpartie->name_full }}</option>
																@endif
															@endif	
														@endif
													@endif
												@endforeach
											@endif
									</select>
									@if($errors->has('id_counterpartie_contract'))
										<label class='msgError'>{{$errors->first('id_counterpartie_contract')}}</label>
									@endif
								</div>
							</div>
							<div class="col-md-12">
								<div class='row'>
									<div class="col-md-2">
										<label for='number_counterpartie_contract_reestr'>№ дог. контрагента</label>
									</div>
									<div class="col-md-10">
										<input id='number_counterpartie_contract_reestr' class='form-control {{$errors->has("number_counterpartie_contract_reestr") ? print("inputError ") : print("")}}' name='number_counterpartie_contract_reestr' value='{{old("number_counterpartie_contract_reestr") ? old("number_counterpartie_contract_reestr") : $reestr->number_counterpartie_contract_reestr}}' readonly />
										@if($errors->has('number_counterpartie_contract_reestr'))
											<label class='msgError'>{{$errors->first('number_counterpartie_contract_reestr')}}</label>
										@endif
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="row">
									<div class="col-md-12">
										<div class='form-group'>
											<label for='nameWork'>Наименование работ</label>
											<textarea id='nameWork' class='form-control {{$errors->has("name_work_contract") ? print("inputError ") : print("")}}' name='name_work_contract' type="text" style="width: 100%;" rows='4' readonly>{{ old('name_work_contract') ? old('name_work_contract') : $contract->name_work_contract }}</textarea>
											@if($errors->has('name_work_contract'))
												<label class='msgError'>{{$errors->first('name_work_contract')}}</label>
											@endif
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label for='lastCompleteContract'>Состояние заключения договора</label>
									</div>								
								</div>
								<div class="row">
									<div class="col-md-12">
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
									<div class="col-md-12">
										<div class='form-group'>
											@if(count($states) > 0)
												@if($states[count($states) - 1]->name_state == "Заключен" OR $states[count($states) - 1]->name_state == "Заключён")
													<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%; display: none;" rows='3' disabled></textarea>
												@else
													<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%;" rows='3' disabled>{{$states[count($states) - 1]->name_state}}</textarea>
												@endif
											@else
												<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%;" rows='3' disabled></textarea>
											@endif
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<button class="btn btn-primary" data-toggle="modal" data-target="#history_states" type='button'>История состояний</button>
									</div>
								</div>
							</div>
							<div class="col-md-9">
								<div class="row">
									<div class="col-md-3">
										<label for='okdp_reestr'>ОКДП</label>
										<div class="row col-md-12">
											<input id='okdp_reestr' class='form-control {{$errors->has("okdp_reestr") ? print("inputError ") : print("")}}' name='okdp_reestr' value='{{ old("okdp_reestr") ? old("okdp_reestr") : $reestr->okdp_reestr }}' readonly />
										</div>
									</div>
									<div class="col-md-7">
										<label>Гарантия банка</label>
										<div class='row'>
											<div class="col-md-1">
												<label for='date_bank_reestr'>до</label>
											</div>
											<div class="col-md-2">
												<input id='date_bank_reestr' class='datepicker form-control {{$errors->has("date_bank_reestr") ? print("inputError ") : print("")}}' name='date_bank_reestr' value='{{ old("date_bank_reestr") ? old("date_bank_reestr") : $reestr->date_bank_reestr }}' readonly />
											</div>
											<div class="col-md-1">
												<label for='amount_bank_reestr'>Сумма</label>
											</div>
											<div class="col-md-3">
												<input id='amount_bank_reestr' class='form-control {{$errors->has("amount_bank_reestr") ? print("inputError ") : print("")}}' name='amount_bank_reestr' value='{{ old("amount_bank_reestr") ? old("amount_bank_reestr") : $reestr->amount_bank_reestr }}' readonly />
											</div>
											<div class="col-md-1">
												<label for='bank_reestr'>Банк</label>
											</div>
											<div class="col-md-4">
												<input id='bank_reestr' class='form-control {{$errors->has("bank_reestr") ? print("inputError ") : print("")}}' name='bank_reestr' value='{{ old("bank_reestr") ? old("bank_reestr") : $reestr->bank_reestr }}' readonly />
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class='form-group row'>
											<div class='col-md-12' style='text-align: right;'>
												<button class='btn btn-primary' data-toggle="modal" data-target="#scan" type='button'>Сканы</button>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
										<div class='row'>
											<div class="col-md-12">
												<label for='date_contract_reestr'>Срок действия договора</label>
											</div>
										</div>
									</div>
									<div class="col-md-5">
										<div class='row'>
											<div class="col-md-1">
												<label for='date_b_contract_reestr'>с</label>
											</div>
											<div class="col-md-5">
												<input id='date_b_contract_reestr' class='datepicker form-control {{$errors->has("date_b_contract_reestr") ? print("inputError ") : print("")}}' name='date_b_contract_reestr' value='{{ old("date_b_contract_reestr") ? old("date_b_contract_reestr") : $reestr->date_b_contract_reestr }}' readonly />
											</div>
											<div class="col-md-1">
												<label for='date_e_contract_reestr'>по</label>
											</div>
											<div class="col-md-5">
												<input id='date_e_contract_reestr' class='datepicker form-control {{$errors->has("date_e_contract_reestr") ? print("inputError ") : print("")}}' name='date_e_contract_reestr' value='{{ old("date_e_contract_reestr") ? old("date_e_contract_reestr") : $reestr->date_e_contract_reestr }}' readonly />
											</div>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-10">
										<input id='date_contract_reestr' class='form-control {{$errors->has("date_contract_reestr") ? print("inputError ") : print("")}}' name='date_contract_reestr' value='{{ old("date_contract_reestr") ? old("date_contract_reestr") : $reestr->date_contract_reestr }}' readonly />
									</div>
								</div>
								<div class="row">
									<div class="col-md-10">
										<label for='date_maturity_reestr'>Срок исполнения обязательств</label>
										<input id='date_maturity_reestr' class='form-control {{$errors->has("date_maturity_reestr") ? print("inputError ") : print("")}}' name='date_maturity_reestr' value='{{ old("date_maturity_reestr") ? old("date_maturity_reestr") : $reestr->date_maturity_reestr }}' readonly />
									</div>
								</div>
								<div class="row">
									<div class="col-md-10">
										<div class='row'>
											<div class="col-md-1">
												<label for='amount_reestr'>Сумма (начальная)</label>
											</div>
											<div class="col-md-3">
												<input id='amount_reestr' class='form-control {{$errors->has("amount_reestr") ? print("inputError ") : print("")}}' name='amount_reestr' value='{{old("amount_reestr") ? old("amount_reestr") : $reestr->amount_reestr}}' readonly />
											</div>
											<div class="col-md-2">
												<select id="sel8" class='form-control {{$errors->has("unit_reestr") ? print("inputError ") : print("")}}' name='unit_reestr' disabled>
													<option></option>
													@foreach($units as $unit)
														@if(old('unit_reestr'))
															<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
														@else
															@if($reestr->unit_reestr == $unit->id)
																<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
															@else
																<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
															@endif
														@endif
													@endforeach
												</select>
											</div>
											<div class="col-md-2">
												<label for='VAT'>НДС</label>
												@if(old('vat_reestr'))
													<input id='VAT' class='form-check-input' name='vat_reestr' type="checkbox" checked disabled />
												@else
													@if($reestr->vat_reestr)
														<input id='VAT' class='form-check-input' name='vat_reestr' type="checkbox" checked disabled />
													@else
														<input id='VAT' class='form-check-input' name='vat_reestr' type="checkbox" disabled />
													@endif
												@endif
											</div>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-10">
										<input class='form-control {{$errors->has("amount_comment_reestr") ? print("inputError ") : print("")}}' name='amount_comment_reestr' value='{{old("amount_comment_reestr") ? old("amount_comment_reestr") : $reestr->amount_comment_reestr}}' readonly/>
									</div>
								</div>
								<div class="row">
									<div class="col-md-10">
										<label for='payment_order_reestr'>Порядок оплаты</label>
										<textarea id='payment_order_reestr' class='form-control' type="text" style="width: 100%;" rows='3' name='payment_order_reestr' readonly>{{old("payment_order_reestr") ? old("payment_order_reestr") : $reestr->payment_order_reestr}}</textarea>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<label for='prolongation_reestr'>Пролонгация</label>
								@if(old('prolongation_reestr'))
									<input id='prolongation_reestr' class='form-check-input' type="checkbox" name='prolongation_reestr' disabled checked />
								@else
									@if($reestr->prolongation_reestr)
										<input id='prolongation_reestr' class='form-check-input' type="checkbox" name='prolongation_reestr' disabled checked />
									@else
										<input id='prolongation_reestr' class='form-check-input' type="checkbox" name='prolongation_reestr' disabled />
									@endif
								@endif
							</div>
							<div class="col-md-2">
								<label for='re_registration_reestr'>Перерегистрация</label>
								@if(old('re_registration_reestr'))
									<input id='re_registration_reestr' class='form-check-input' type="checkbox" name='re_registration_reestr' disabled checked />
								@else
									@if($reestr->re_registration_reestr)
										<input id='re_registration_reestr' class='form-check-input' type="checkbox" name='re_registration_reestr' disabled checked />
									@else
										<input id='re_registration_reestr' class='form-check-input' type="checkbox" name='re_registration_reestr' disabled />
									@endif
								@endif
							</div>
							<div class="col-md-2">
								<!--<button class="btn btn-secondary" style="float: right;" type='button'>Дополнительные соглашения</button>-->
							</div>
							<div class="col-md-3">
								<!--<button class="btn btn-secondary" style="float: left;" type='button'>Протокол разногласий / согласования</button>-->
							</div>
							<div class="col-md-3">
								
							</div>
						</div>
						<div class="row">
						</div>
					</div>
					<!-- Модальное окно история состояний -->
					<div class="modal fade" id="history_states" tabindex="-1" role="dialog" aria-labelledby="historyStatesModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form method='POST' action="{{ route('department.ekonomic.new_state',$contract->id)}}">
									{{csrf_field()}}
									<div class="modal-header">
										<h5 class="modal-title" id="historyStatesModalLabel">История состояний</h5>
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
															</tr>
														</thead>
														<tbody>
															@foreach($states as $state)
																<tr class='rowsContract' id_state='{{$state->id}}' 
																													name_state='{{$state->name_state}}' 
																													date_state='{{$state->date_state}}' 
																													action_state='{{ route("department.ekonomic.update_state",$contract->id)}}'
																													destroy_state='{{ route("department.ekonomic.destroy_state",$state->id)}}'>
																	<td>{{$state->name_state}}</td>
																	<td>{{$state->date_state}}</td>
																</tr>
															@endforeach
														</tbody>
													</table>
												</div>
											@endif
											<div id='add_history_states' class='col-md-12' style='display: none;'>
												<div class='form-group row col-md-12'>
													<input id='id_state' class='form-control' type='text' name='id_state' style='display: none;'/>
												</div>
												<div class='form-group row col-md-12'>
													<label for='new_name_state' class='col-md-3 col-form-label'>Наименование</label>
													<div class='col-md-9'>
														<input id='new_name_state' class='form-control {{$errors->has("new_name_state") ? print("inputError ") : print("")}}' type='text' name='new_name_state'/>
														@if($errors->has('new_name_state'))
															<label class='msgError'>{{$errors->first('new_name_state')}}</label>
														@endif
													</div>
												</div>
												<div class='form-group row col-md-12'>
													<label for='date_state' class='col-md-3 col-form-label'>Дата</label>
													<div class='col-md-9'>
														<input id='date_state' class='datepicker form-control {{$errors->has("date_state") ? print("inputError ") : print("")}}' name='date_state' value='{{date("d.m.Y", time())}}'/>
														@if($errors->has('date_state'))
															<label class='msgError'>{{$errors->first('date_state')}}</label>
														@endif
													</div>
												</div>
											</div>
											<div class='col-md-12'>
												<!--<button id='btn_add_state' class='btn btn-secondary' type='button' clear_date='{{date("d.m.Y", time())}}' action_state='{{ route("department.ekonomic.new_state",$contract->id)}}'>Добавить состояние</button>-->
											</div>
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
												<!--<button id='add_new_resolution' type='button' class='btn btn-secondary'>Добавить скан</button>-->
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
								<form id='form_new_application' method='POST' file='true' enctype='multipart/form-data' action='{{route("resolution_store", $contract->id)}}' style='display: none;'>
									{{csrf_field()}}
									<div class="modal-header">
										<h5 class="modal-title" id="updateApplicationModalLabel">Добавление резолюции</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class='modal-body'>
										<div class='row'>
											<div class='col-md-6' style='display: none;'>
												<input type='text' value='id_contract_resolution' name='real_name_document'/>
											</div>
											<div class='col-md-6'>
												<input type='file' name='new_file_resolution'/>
											</div>
											<div class='col-md-6'>
												<input id='date_resolution' name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
										<button id='btn_close_new_application' type="button" class="btn btn-secondary">Закрыть</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				@endif
			@endif
		@else
			<div class="alert alert-danger">
				Необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection
