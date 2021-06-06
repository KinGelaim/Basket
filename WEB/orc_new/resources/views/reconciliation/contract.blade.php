@extends('layouts.header')

@section('title')
	Карточка договора
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Отдел управления договорами' OR Auth::User()->hasRole()->role == 'Десятый отдел' OR Auth::User()->hasRole()->role == 'Администрация')
				@if (Route::has('login'))
					<div class="top-right links">
						
					</div>
				@endif

				<div class="content">
					<form method='POST' action='{{route("department.reconciliation.update", $contract->id)}}'>
						{{csrf_field()}}
						<div class="row">
							<div class="col-md-12">
								@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
									@if($contract->number_contract == null)
										<button class="btn btn-primary rowsManagementClickContracts" type="button" contract_renumber='{{ route("department.management.new_number",$contract->id) }}'>Присвоить номер</button>
									@else
										<button class="btn btn-primary" type="button" disabled>Присвоить номер</button>
									@endif
								@endif
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for='numberContract'>Номер договора</label>
									<input class='form-control' type='text' value='{{$contract->number_contract}}' disabled />
								</div>
								<div class="form-group">
									<label for='numberContractContrpartie'>Номер договора контрагента</label>
									<input id='numberContractContrpartie' class='form-control' type='text' value='{{$reestr->number_counterpartie_contract_reestr}}' name='number_counterpartie_contract_reestr' />
								</div>
								<div class="row">
									<div class="col-md-12">
										<label for='sel2'>Название предприятия</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<select class="form-control" id="sel2" disabled>
												<option>{{ $contract->name_counterpartie_contract }}</option>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="sel1">Вид работ</span></label>
									<select id="sel1" class='form-control {{$errors->has("id_view_contract") ? print("inputError ") : print("")}}' name='id_view_contract'>
										<option></option>
										@if($viewContracts)
											@foreach($viewContracts as $viewContract)
												@if(old('id_view_contract'))
													@if(old('id_view_contract') == $viewContract->id)
														<option value='{{$viewContract->id}}' selected>{{ $viewContract->name_view_contract }}</option>
													@else
														<option value='{{$viewContract->id}}'>{{ $viewContract->name_view_contract }}</option>
													@endif
												@else
													@if($contract->id_view_contract == $viewContract->id)
														<option value='{{$viewContract->id}}' selected>{{ $viewContract->name_view_contract }}</option>
													@else
														<option value='{{$viewContract->id}}'>{{ $viewContract->name_view_contract }}</option>
													@endif
												@endif
											@endforeach
										@endif
									</select>
									@if($errors->has('id_view_contract'))
										<label class='msgError'>{{$errors->first('id_view_contract')}}</label>
									@endif
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-12">
												<div class='form-group'>
													<label for='nameWork'>Наименование работ</label>
													<textarea id='nameWork' class='form-control' type="text" style="width: 100%;" rows='7' name='name_work_contract'>{{$contract->name_work_contract}}</textarea>
												</div>
											</div>
										</div>
										
										<!--<div class="row">
											<div class="col-md-12">
												<button class='btn btn-primary' type='button' onclick="$('#detailingModalLabel').text('Новое изделие');
																										$('#detailing form').attr('action', '{{ route('department.second.new_isp_create', $contract->id)}}');
																										$('#name_element').val('');
																										$('#name_view_work').val('');
																										$('#count_elements').val(''); 
																										$('#year_isp').val({{date('Y', time())}});
																										$('#detailing').modal('show');">Добавить изделие</button>
											</div>
											<div class="col-md-12">
												<table class="table" style='margin: 0 auto;'>
													<thead>
														<tr>
															<th>Изделие</th>
															<th>Кол-во</th>
														</tr>
													</thead>
													<tbody>
														@foreach($isps as $isp)
															<tr class='rowsContract cursorPointer' onclick="$('#detailingModalLabel').text('Изделие {{$isp->name_element}}');
																											$('#detailing form').attr('action', '{{ route('department.second.new_isp_update', $isp->id)}}');
																											$('#name_element option').each(function(e){
																												if($(this).val() == {{$isp->elID}}) 
																													$(this).prop('selected',true);
																												else
																													$(this).prop('selected',false);
																												});
																											$('#name_view_work option').each(function(e){ 
																												if($(this).val() == <?php if($isp->id_view_work_elements) echo $isp->id_view_work_elements; else echo '-1'; ?>) 
																													$(this).prop('selected',true);
																												else
																													$(this).prop('selected',false);
																												});
																											$('#count_elements').val({{$isp->count_isp ? $isp->count_isp : $isp->january + $isp->february + $isp->march + $isp->april + $isp->may + $isp->june + $isp->july + $isp->august + $isp->september + $isp->october + $isp->november + $isp->december}});
																											$('#year_isp').val({{$isp->year}});
																											$('#detailing').modal('show');">
																<td>{{$isp->name_element}}</td>
																<td>{{$isp->count_isp ? $isp->count_isp : $isp->january + $isp->february + $isp->march + $isp->april + $isp->may + $isp->june + $isp->july + $isp->august + $isp->september + $isp->october + $isp->november + $isp->december}}</td>
															</tr>
														@endforeach
													</tbody>
												</table>
											</div>
										</div>-->
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<div class='row'>
											<div class="col-md-12">
												<div class='form-check'>
													@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел')
														@if($contract->name_works_goz == "ГОЗ")
															<input id='gozCheck' class='form-check-input' name='goz_contract' type="checkbox" onclick="$(this).prop('checked', true); $('#exportCheck').prop('checked', false); $('#otherCheck').prop('checked', false);" checked />
														@else
															<input id='gozCheck' class='form-check-input' name='goz_contract' type="checkbox" onclick="$(this).prop('checked', true); $('#exportCheck').prop('checked', false); $('#otherCheck').prop('checked', false);" />
														@endif
													@else
														@if($contract->name_works_goz == "ГОЗ")
															<input id='gozCheck' class='form-check-input' type="checkbox" checked disabled />
														@else
															<input id='gozCheck' class='form-check-input' type="checkbox" disabled />
														@endif
													@endif
													<label class='form-check-label' for='gozCheck'>ГОЗ</label>
												</div>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<div class='form-check'>
													@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел')
														@if($contract->name_works_goz == "Экспорт")
															<input id='exportCheck' class='form-check-input' name='export_contract' type="checkbox" onclick="$(this).prop('checked', true); $('#gozCheck').prop('checked', false); $('#otherCheck').prop('checked', false);" checked />
														@else
															<input id='exportCheck' class='form-check-input' name='export_contract' type="checkbox" onclick="$(this).prop('checked', true); $('#gozCheck').prop('checked', false); $('#otherCheck').prop('checked', false);" />
														@endif
													@else
														@if($contract->name_works_goz == "Экспорт")
															<input id='exportCheck' class='form-check-input' type="checkbox" checked disabled />
														@else
															<input id='exportCheck' class='form-check-input' type="checkbox" disabled />
														@endif
													@endif
													<label class='form-check-label' for='exportCheck'>Экспорт</label>
												</div>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<div class='form-check'>
													@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел')
														@if($contract->name_works_goz == "Межзаводские")
															<input id='otherCheck' class='form-check-input' name='other_contract' type="checkbox" onclick="$(this).prop('checked', true); $('#exportCheck').prop('checked', false); $('#gozCheck').prop('checked', false);" checked />
														@else
															<input id='otherCheck' class='form-check-input' name='other_contract' type="checkbox" onclick="$(this).prop('checked', true); $('#exportCheck').prop('checked', false); $('#gozCheck').prop('checked', false);" />
														@endif
													@else
														@if($contract->name_works_goz == "Межзаводские")
															<input id='otherCheck' class='form-check-input' type="checkbox" checked disabled />
														@else
															<input id='otherCheck' class='form-check-input' type="checkbox" disabled />
														@endif
													@endif
													<label class='form-check-label' for='otherCheck'>Межзаводские</label>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-8">
										<div class="row">
											<div class='col-md-12'>
												@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел')
													@if(old('renouncement_contract'))
														<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" checked />
													@else
														@if($contract->renouncement_contract == 1)
															<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" checked />
														@else
															<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" />
														@endif
													@endif
												@else
													@if(old('renouncement_contract'))
														<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" checked disabled />
													@else
														@if($contract->renouncement_contract == 1)
															<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" checked disabled />
														@else
															<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" disabled />
														@endif
													@endif
												@endif
												<label class='form-check-label' for='break'>ОТКАЗ</label>
											</div>
										</div>
										<div class="row">
											<div class='col-md-12'>
												@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел')
													@if(old('archive_contract'))
														<input id='archive' class='form-check-input' name='archive_contract' type="checkbox" checked />
													@else
														@if($contract->archive_contract == 1)
															<input id='archive' class='form-check-input' name='archive_contract' type="checkbox" checked />
														@else
															<input id='archive' class='form-check-input' name='archive_contract' type="checkbox" />
														@endif
													@endif
												@else
													@if(old('archive_contract'))
														<input id='archive' class='form-check-input' name='archive_contract' type="checkbox" checked disabled />
													@else
														@if($contract->archive_contract == 1)
															<input id='archive' class='form-check-input' name='archive_contract' type="checkbox" checked disabled />
														@else
															<input id='archive' class='form-check-input' name='archive_contract' type="checkbox" disabled />
														@endif
													@endif
												@endif
												<label class='form-check-label' for='archive'>АРХИВ</label>
											</div>
										</div>
										<div class="row">
											<div class='col-md-12'>
												@if(old('reconciliation_contract'))
													<input id='reconciliation' class='form-check-input' name='reconciliation_contract' type="checkbox" checked disabled />
												@else
													<?php
														$prK = true;
														if($directed_list)
															foreach($directed_list as $directed)
																if($directed->check_agree_reconciliation == 0){
																	$prK = false;
																	break;
																}
														if(count($directed_list) == 0)
															$prK = false;
														if($contract->reconciliation_contract == 1 || $prK)
															echo "<input id='reconciliation' class='form-check-input' name='reconciliation_contract' type='checkbox' checked disabled />";
														else
															echo "<input id='reconciliation' class='form-check-input' name='reconciliation_contract' type='checkbox' disabled />";
													?>
												@endif
												<label class='form-check-label' for='reconciliation'>Согласован</label>
											</div>
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
													<input id='implementation_contract' ajax-href="{{route('ajax.set_complete_contract',$contract->id)}}" class='form-check-input completeCheck' type="checkbox" checked />
												@else
													<input id='implementation_contract' ajax-href="{{route('ajax.set_complete_contract',$contract->id)}}" class='form-check-input completeCheck' type="checkbox" />
												@endif
											@else
												<input id='implementation_contract' ajax-href="{{route('ajax.set_complete_contract',$contract->id)}}" class='form-check-input completeCheck' type="checkbox" />
											@endif
											<label class='form-check-label' for='implementation_contract'>Заключен</label>
										</div>
									</div>								
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class='form-group'>
											@if(count($states) > 0)
												<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%; overflow-x: scroll; <!--white-space: nowrap;-->" rows='5' cols='2' readonly>@foreach($states as $state){{$state->date_state . '   ' . $state->name_state . '   ' . $state->surname . ' ' . mb_substr($state->name, 0, 1) . '.' . mb_substr($state->patronymic, 0, 1) . '.&#13;&#10;'}}@endforeach</textarea>
											@else
												<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%;" rows='5' readonly></textarea>
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
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-5">
												<div class="row">
													<div class="col-md-4">
														<div class="row">
															<label>Дата Дог./Контр. на 1 л.</label>
														</div>
														<div class="row">
															@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел')
																<input name='date_contract_on_first_reestr' class='datepicker form-control' type='text' value="{{old('date_contract_on_first_reestr') ? old('date_contract_on_first_reestr') : $reestr->date_contract_on_first_reestr}}"/>
															@else
																<input name='date_contract_on_first_reestr' class='form-control' type='text' value="{{old('date_contract_on_first_reestr') ? old('date_contract_on_first_reestr') : $reestr->date_contract_on_first_reestr}}" readonly />
															@endif
														</div>
														<div class="row">
															<label>Срок исполнения</label>
														</div>
														<div class="row">
															@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел')
																<input name='date_maturity_date_reestr' class='datepicker form-control' type='text' value="{{old('date_maturity_date_reestr') ? old('date_maturity_date_reestr') : $reestr->date_maturity_date_reestr}}"/>
															@else
																<input name='date_maturity_date_reestr' class='form-control' type='text' value="{{old('date_maturity_date_reestr') ? old('date_maturity_date_reestr') : $reestr->date_maturity_date_reestr}}" readonly />
															@endif
															@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел')
																@if(old('date_test'))
																	<input id='date_test' name='date_test' class='form-check-input' type="checkbox" checked />
																@else
																	@if($reestr->date_maturity_reestr)
																		<input id='date_test' name='date_test' class='form-check-input' type="checkbox" checked />
																	@else
																		<input id='date_test' name='date_test' class='form-check-input' type="checkbox" />
																	@endif
																@endif
															@else
																@if(old('date_test'))
																	<input id='date_test' name='date_test' class='form-check-input' type="checkbox" checked disabled />
																@else
																	@if($reestr->date_maturity_reestr)
																		<input id='date_test' name='date_test' class='form-check-input' type="checkbox" checked disabled />
																	@else
																		<input id='date_test' name='date_test' class='form-check-input' type="checkbox" disabled />
																	@endif
																@endif

															@endif
															<label for='date_test'>Не определен</label>
															@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел')
																@if(old('date_test'))
																	<textarea id='date_textarea' name='date_textarea' class='form-control' type="text" style="width: 100%;" rows='5'>{{old('date_textarea')}}</textarea>
																@else
																	@if($reestr->date_maturity_reestr)
																		<textarea id='date_textarea' name='date_textarea' class='form-control' type="text" style="width: 100%;" rows='5'>{{old('date_textarea') ? old('date_textarea') : $reestr->date_maturity_reestr}}</textarea>
																	@else
																		<textarea id='date_textarea' name='date_textarea' class='form-control' type="text" style="width: 100%;" rows='5' readonly>{{old('date_textarea')}}</textarea>
																	@endif
																@endif
															@else
																@if(old('date_test'))
																	<textarea id='date_textarea' name='date_textarea' class='form-control' type="text" style="width: 100%;" rows='5' disabled >{{old('date_textarea')}}</textarea>
																@else
																	@if($reestr->date_maturity_reestr)
																		<textarea id='date_textarea' name='date_textarea' class='form-control' type="text" style="width: 100%;" rows='5' disabled>{{old('date_textarea') ? old('date_textarea') : $reestr->date_maturity_reestr}}</textarea>
																	@else
																		<textarea id='date_textarea' name='date_textarea' class='form-control' type="text" style="width: 100%;" rows='5' readonly disabled>{{old('date_textarea')}}</textarea>
																	@endif
																@endif
															@endif
														</div>
													</div>
													<div class="col-md-8">
														<div class="row" style='margin-left: 5px;'>
															<label>Исполнитель</label>
														</div>
														<div class="row" style='margin-left: 5px;'>
															@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел')
																<select class='form-control' name='executor_contract_reestr'>
																	<option></option>
																	@if(old('executor_contract_reestr'))
																		@foreach($curators as $in_curators)
																			@if(old('executor_contract_reestr') == $in_curators->id)
																				<option value='{{$in_curators->id}}' selected>{{$in_curators->FIO}}</option>
																			@else
																				<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
																			@endif
																		@endforeach
																	@else
																		@foreach($curators as $in_curators)
																			@if($reestr->executor_contract_reestr == $in_curators->id)
																				<option value='{{$in_curators->id}}' selected>{{$in_curators->FIO}}</option>
																			@else
																				<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
																			@endif
																		@endforeach
																	@endif
																</select>
															@else
																<select class='form-control' name='executor_contract_reestr' disabled >
																	<option></option>
																	@if(old('executor_contract_reestr'))
																		@foreach($curators as $in_curators)
																			@if(old('executor_contract_reestr') == $in_curators->id)
																				<option value='{{$in_curators->id}}' selected>{{$in_curators->FIO}}</option>
																			@else
																				<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
																			@endif
																		@endforeach
																	@else
																		@foreach($curators as $in_curators)
																			@if($reestr->executor_contract_reestr == $in_curators->id)
																				<option value='{{$in_curators->id}}' selected>{{$in_curators->FIO}}</option>
																			@else
																				<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
																			@endif
																		@endforeach
																	@endif
																</select>

															@endif
														</div>
														<div class="row" style='margin-left: 5px;'>
															<label>Сумма начальная</label>
															@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел')
																<input id='amount_reestr' name='amount_reestr' class='form-control check-number' type='text' value="{{old('amount_reestr') ? old('amount_reestr') : $reestr->amount_reestr}}"/>
															@else
																<input id='amount_reestr' name='amount_reestr' class='form-control' type='text' value="{{old('amount_reestr') ? old('amount_reestr') : $reestr->amount_reestr}}" disabled />
															@endif
														</div>
														<div class="row" style='margin-left: 5px;'>
															<label>Сумма (окончательная)</label>
															@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел')
																<input id='amount_contract_reestr' name='amount_contract_reestr' class='form-control check-number' type='text' value="{{old('amount_contract_reestr') ? old('amount_contract_reestr') : $reestr->amount_contract_reestr}}"/>
															@else
																<input id='amount_contract_reestr' name='amount_contract_reestr' class='form-control' type='text' value="{{old('amount_contract_reestr') ? old('amount_contract_reestr') : $reestr->amount_contract_reestr}}" disabled />
															@endif
														</div>
														<div class="row" style='margin-left: 5px;'>
															@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел')
																@if(old('fix_amount_contract_reestr'))
																	<input id='fix_amount_contract_reestr' name='fix_amount_contract_reestr' class='form-check-input' type="checkbox" checked />
																@else
																	@if($reestr->fix_amount_contract_reestr)
																		<input id='fix_amount_contract_reestr' name='fix_amount_contract_reestr' class='form-check-input' type="checkbox" checked />
																	@else
																		<input id='fix_amount_contract_reestr' name='fix_amount_contract_reestr' class='form-check-input' type="checkbox" />
																	@endif
																@endif
															@else
																@if(old('fix_amount_contract_reestr'))
																	<input id='fix_amount_contract_reestr' name='fix_amount_contract_reestr' class='form-check-input' type="checkbox" checked disabled />
																@else
																	@if($reestr->fix_amount_contract_reestr)
																		<input id='fix_amount_contract_reestr' name='fix_amount_contract_reestr' class='form-check-input' type="checkbox" checked disabled />
																	@else
																		<input id='fix_amount_contract_reestr' name='fix_amount_contract_reestr' class='form-check-input' type="checkbox" disabled />
																	@endif
																@endif

															@endif
															<label for='fix_amount_contract_reestr'>Фиксированная сумма</label>
														</div>
														<div class="row" style='margin-left: 5px;'>
															@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел')
																@if(old('vat_reestr'))
																	<input id='vat_reestr' name='vat_reestr' class='form-check-input' type="checkbox" checked />
																@else
																	@if($reestr->vat_reestr)
																		<input id='vat_reestr' name='vat_reestr' class='form-check-input' type="checkbox" checked />
																	@else
																		<input id='vat_reestr' name='vat_reestr' class='form-check-input' type="checkbox" />
																	@endif
																@endif
															@else
																@if(old('vat_reestr'))
																	<input id='vat_reestr' name='vat_reestr' class='form-check-input' type="checkbox" checked disabled />
																@else
																	@if($reestr->vat_reestr)
																		<input id='vat_reestr' name='vat_reestr' class='form-check-input' type="checkbox" checked disabled />
																	@else
																		<input id='vat_reestr' name='vat_reestr' class='form-check-input' type="checkbox" disabled />
																	@endif
																@endif
															@endif
															<label for='vat_reestr'>НДС</label>
														</div>
														<div class="row" style='margin-left: 5px;'>
															@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел')
																@if(old('prepayment_reestr'))
																	<input id='prepayment_reestr' class='form-check-input' type="checkbox" checked />
																@else
																	@if($reestr->prepayment_reestr)
																		<input id='prepayment_reestr' class='form-check-input' type="checkbox" checked />
																	@else
																		<input id='prepayment_reestr' class='form-check-input' type="checkbox" />
																	@endif
																@endif
															@else
																@if(old('prepayment_reestr'))
																	<input id='prepayment_reestr' class='form-check-input' type="checkbox" checked disabled />
																@else
																	@if($reestr->prepayment_reestr)
																		<input id='prepayment_reestr' class='form-check-input' type="checkbox" checked disabled />
																	@else
																		<input id='prepayment_reestr' class='form-check-input' type="checkbox" disabled />
																	@endif
																@endif
															@endif
															<label for='prepayment_reestr'>Аванс предусмотрен</label>
														</div>
														<div id='input_prepayment_contract' class="row" style='margin-left: 5px; @if($reestr->prepayment_reestr) display:block; @else display:none; @endif'>
															@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел')
																<input name='prepayment_reestr' class='form-control check-number' type='text' value="{{old('prepayment_reestr') ? old('prepayment_reestr') : $reestr->prepayment_reestr}}"/>
															@else
																<input name='prepayment_reestr' class='form-control' type='text' value="{{old('prepayment_reestr') ? old('prepayment_reestr') : $reestr->prepayment_reestr}}" disabled />
															@endif
														</div>
														<div class="row" style='margin-left: 5px;'>
															@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел')
																@if(old('big_deal_reestr'))
																	<input id='big_deal_reestr' name='big_deal_reestr' class='form-check-input' type="checkbox" checked />
																@else
																	@if($reestr->big_deal_reestr)
																		<input id='big_deal_reestr' name='big_deal_reestr' class='form-check-input' type="checkbox" checked />
																	@else
																		<input id='big_deal_reestr' name='big_deal_reestr' class='form-check-input' type="checkbox" />
																	@endif
																@endif
															@else
																@if(old('big_deal_reestr'))
																	<input id='big_deal_reestr' name='big_deal_reestr' class='form-check-input' type="checkbox" checked disabled />
																@else
																	@if($reestr->big_deal_reestr)
																		<input id='big_deal_reestr' name='big_deal_reestr' class='form-check-input' type="checkbox" checked disabled />
																	@else
																		<input id='big_deal_reestr' name='big_deal_reestr' class='form-check-input' type="checkbox" disabled />
																	@endif
																@endif
															@endif
															<label for='big_deal_reestr'>Крупная сделка</label>
														</div>
													</div>
												</div>
												<!--<div class="row">
													<label>Процесс согласование</label>
												</div>
												<div class="row">
													<button class='btn btn-primary' data-toggle="modal" data-target="#process" type='button'>Добавить</button>
												</div>
												<div class="row">
													<table class="table table-bordered" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
														<thead style='text-align: center;'>
															<tr>
																<th>Процесс</th>
																<th>Запущен</th>
																<th>Завершен</th>
															</tr>
														</thead>
														<tbody>
															@foreach($reconciliations as $reconciliation)
																<tr>
																	<td>{{ $reconciliation->process_reconciliation }}</td>
																	<td>{{ $reconciliation->b_date_reconciliation }}</td>
																	<td>
																		<?php
																			if($reconciliation->e_date_reconciliation)
																				echo $reconciliation->e_date_reconciliation;
																			else
																				echo "<button class='btn btn-secondary btn-href' type='button' href='" . route('department.reconciliation.end_date',$reconciliation->id) . "'>Завершить</button>";
																		?>
																	</td>
																</tr>
															@endforeach
														</tbody>
													</table>
												</div>-->
											</div>
											<div class="col-md-5">
												<div class='row'>
													<div class="col-md-12">
														<label>Согласование</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														<div class='row'>
															<div class="col-md-2">
																<label>Ознак.</label>
															</div>
															<div class="col-md-2">
																<label>Согл.</label>
															</div>
															<div class="col-md-8">
																<label>Кому</label>
															</div>
														</div>
														@if($directed_list)
															@foreach($directed_list as $directed)
																<div class='row'>
																	<div class="col-md-2">
																		@if($directed->check_reconciliation == 1)
																			<input class='form-check-input' type="checkbox" checked disabled />
																		@else
																			<input class='form-check-input' type="checkbox" disabled />
																		@endif
																	</div>
																	<div class="col-md-2">
																		@if($directed->check_agree_reconciliation == 1)
																			<input class='form-check-input' type="checkbox" checked disabled />
																		@else
																			<input class='form-check-input' type="checkbox" disabled />
																		@endif
																	</div>
																	<div class="col-md-8">
																		<label class='form-check-label'>{{$directed->surname . ' ' . $directed->name . ' ' . $directed->patronymic}}</label>
																	</div>
																</div>
															@endforeach
															@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел')
																<button type='button' class='btn btn-primary btn-href' style='float: right; margin-left: 15px;' href='' title='Обновить'><!--☼--><span class="ui-icon ui-icon-1-1" style='background-size: 355px; background-position-x: -92px; background-position-y: -114px;'></span></button>
																<button type='button' class='btn btn-primary btn-href' style='float: right;' href='{{route("department.reconciliation.print_reconciliation",$contract->id)}}' title='Напечатать лист согласования'><!--☼--><span class="ui-icon ui-icon-1-1" style='background-size: 355px; background-position-x: -226px; background-position-y: -136px;'></span></button>
															@endif
														@endif
														<!--<div class='row'>
															<div class="col-md-2">
																@if(old('isp_dir'))
																	<input id='isp_dir' class='form-check-input' name='isp_dir' type="checkbox" checked />
																@else
																	@if($contract->isp_dir == 1)
																		<input id='isp_dir' class='form-check-input' name='isp_dir' type="checkbox" checked />
																	@else
																		<input id='isp_dir' class='form-check-input' name='isp_dir' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-2">
																@if(old('isp_dir_check'))
																	<input id='isp_dir_check' class='form-check-input' name='isp_dir_check' type="checkbox" checked />
																@else
																	@if($contract->isp_dir_check == 1)
																		<input id='isp_dir_check' class='form-check-input' name='isp_dir_check' type="checkbox" checked />
																	@else
																		<input id='isp_dir_check' class='form-check-input' name='isp_dir_check' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-8">
																<label class='form-check-label'>Исполнительный директор</label>
															</div>
														</div>
														<div class='row'>
															<div class="col-md-2">
																@if(old('zam_isp_dir_niokr'))
																	<input id='zam_isp_dir_niokr' class='form-check-input' name='zam_isp_dir_niokr' type="checkbox" checked />
																@else
																	@if($contract->zam_isp_dir_niokr == 1)
																		<input id='zam_isp_dir_niokr' class='form-check-input' name='zam_isp_dir_niokr' type="checkbox" checked />
																	@else
																		<input id='zam_isp_dir_niokr' class='form-check-input' name='zam_isp_dir_niokr' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-2">
																@if(old('zam_isp_dir_niokr_check'))
																	<input id='zam_isp_dir_niokr_check' class='form-check-input' name='zam_isp_dir_niokr_check' type="checkbox" checked />
																@else
																	@if($contract->zam_isp_dir_niokr_check == 1)
																		<input id='zam_isp_dir_niokr_check' class='form-check-input' name='zam_isp_dir_niokr_check' type="checkbox" checked />
																	@else
																		<input id='zam_isp_dir_niokr_check' class='form-check-input' name='zam_isp_dir_niokr_check' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-8">
																<label class='form-check-label'>Зам.исп.директора по НИОКР</label>
															</div>
														</div>
														<div class='row'>
															<div class="col-md-2">
																@if(old('main_in'))
																	<input id='main_in' class='form-check-input' name='main_in' type="checkbox" checked />
																@else
																	@if($contract->main_in == 1)
																		<input id='main_in' class='form-check-input' name='main_in' type="checkbox" checked />
																	@else
																		<input id='main_in' class='form-check-input' name='main_in' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-2">
																@if(old('main_in_check'))
																	<input id='main_in_check' class='form-check-input' name='main_in_check' type="checkbox" checked />
																@else
																	@if($contract->main_in_check == 1)
																		<input id='main_in_check' class='form-check-input' name='main_in_check' type="checkbox" checked />
																	@else
																		<input id='main_in_check' class='form-check-input' name='main_in_check' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-8">
																<label class='form-check-label'>Главный инженер</label>
															</div>
														</div>
														<div class='row'>
															<div class="col-md-2">
																@if(old('dir_sip'))
																	<input id='dir_sip' class='form-check-input' name='dir_sip' type="checkbox" checked />
																@else
																	@if($contract->dir_sip == 1)
																		<input id='dir_sip' class='form-check-input' name='dir_sip' type="checkbox" checked />
																	@else
																		<input id='dir_sip' class='form-check-input' name='dir_sip' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-2">
																@if(old('dir_sip_check'))
																	<input id='dir_sip_check' class='form-check-input' name='dir_sip_check' type="checkbox" checked />
																@else
																	@if($contract->dir_sip_check == 1)
																		<input id='dir_sip_check' class='form-check-input' name='dir_sip_check' type="checkbox" checked />
																	@else
																		<input id='dir_sip_check' class='form-check-input' name='dir_sip_check' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-8">
																<label class='form-check-label'>Начальник СИП</label>
															</div>
														</div>
														<div class='row'>
															<div class="col-md-2">
																@if(old('dir_peo'))
																	<input id='dir_peo' class='form-check-input' name='dir_peo' type="checkbox" checked />
																@else
																	@if($contract->dir_peo == 1)
																		<input id='dir_peo' class='form-check-input' name='dir_peo' type="checkbox" checked />
																	@else
																		<input id='dir_peo' class='form-check-input' name='dir_peo' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-2">
																@if(old('dir_peo_check'))
																	<input id='dir_peo_check' class='form-check-input' name='dir_peo_check' type="checkbox" checked />
																@else
																	@if($contract->dir_peo_check == 1)
																		<input id='dir_peo_check' class='form-check-input' name='dir_peo_check' type="checkbox" checked />
																	@else
																		<input id='dir_peo_check' class='form-check-input' name='dir_peo_check' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-8">
																<label class='form-check-label'>Начальник ПЭО</label>
															</div>
														</div>
														<div class='row'>
															<div class="col-md-2">
																@if(old('dep_2'))
																	<input id='dep_2' class='form-check-input' name='dep_2' type="checkbox" checked />
																@else
																	@if($contract->dep_2 == 1)
																		<input id='dep_2' class='form-check-input' name='dep_2' type="checkbox" checked />
																	@else
																		<input id='dep_2' class='form-check-input' name='dep_2' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-2">
																@if(old('dep_2_check'))
																	<input id='dep_2_check' class='form-check-input' name='dep_2_check' type="checkbox" checked />
																@else
																	@if($contract->dep_2_check == 1)
																		<input id='dep_2_check' class='form-check-input' name='dep_2_check' type="checkbox" checked />
																	@else
																		<input id='dep_2_check' class='form-check-input' name='dep_2_check' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-8">
																<label class='form-check-label'>Отдел 2</label>
															</div>
														</div>
														<div class='row'>
															<div class="col-md-2">
																@if(old('dep_15'))
																	<input id='dep_15' class='form-check-input' name='dep_15' type="checkbox" checked />
																@else
																	@if($contract->dep_15 == 1)
																		<input id='dep_15' class='form-check-input' name='dep_15' type="checkbox" checked />
																	@else
																		<input id='dep_15' class='form-check-input' name='dep_15' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-2">
																@if(old('dep_15_check'))
																	<input id='dep_15_check' class='form-check-input' name='dep_15_check' type="checkbox" checked />
																@else
																	@if($contract->dep_15_check == 1)
																		<input id='dep_15_check' class='form-check-input' name='dep_15_check' type="checkbox" checked />
																	@else
																		<input id='dep_15_check' class='form-check-input' name='dep_15_check' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-8">
																<label class='form-check-label'>Отдел 15</label>
															</div>
														</div>
														<div class='row'>
															<div class="col-md-2">
																@if(old('dep_93'))
																	<input id='dep_93' class='form-check-input' name='dep_93' type="checkbox" checked />
																@else
																	@if($contract->dep_93 == 1)
																		<input id='dep_93' class='form-check-input' name='dep_93' type="checkbox" checked />
																	@else
																		<input id='dep_93' class='form-check-input' name='dep_93' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-2">
																@if(old('dep_93_check'))
																	<input id='dep_93_check' class='form-check-input' name='dep_93_check' type="checkbox" checked />
																@else
																	@if($contract->dep_93_check == 1)
																		<input id='dep_93_check' class='form-check-input' name='dep_93_check' type="checkbox" checked />
																	@else
																		<input id='dep_93_check' class='form-check-input' name='dep_93_check' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-8">
																<label class='form-check-label'>Отдел 93</label>
															</div>
														</div>
														<div class='row'>
															<div class="col-md-2">
																@if(old('dep_main_tech'))
																	<input id='dep_main_tech' class='form-check-input' name='dep_main_tech' type="checkbox" checked />
																@else
																	@if($contract->dep_main_tech == 1)
																		<input id='dep_main_tech' class='form-check-input' name='dep_main_tech' type="checkbox" checked />
																	@else
																		<input id='dep_main_tech' class='form-check-input' name='dep_main_tech' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-2">
																@if(old('dep_main_tech_check'))
																	<input id='dep_main_tech_check' class='form-check-input' name='dep_main_tech_check' type="checkbox" checked />
																@else
																	@if($contract->dep_main_tech_check == 1)
																		<input id='dep_main_tech_check' class='form-check-input' name='dep_main_tech_check' type="checkbox" checked />
																	@else
																		<input id='dep_main_tech_check' class='form-check-input' name='dep_main_tech_check' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-8">
																<label class='form-check-label'>Отдел главного технолога</label>
															</div>
														</div>
														<div class='row'>
															<div class="col-md-2">
																@if(old('dep_10'))
																	<input id='dep_10' class='form-check-input' name='dep_10' type="checkbox" checked />
																@else
																	@if($contract->dep_10 == 1)
																		<input id='dep_10' class='form-check-input' name='dep_10' type="checkbox" checked />
																	@else
																		<input id='dep_10' class='form-check-input' name='dep_10' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-2">
																@if(old('dep_10_check'))
																	<input id='dep_10_check' class='form-check-input' name='dep_10_check' type="checkbox" checked />
																@else
																	@if($contract->dep_10_check == 1)
																		<input id='dep_10_check' class='form-check-input' name='dep_10_check' type="checkbox" checked />
																	@else
																		<input id='dep_10_check' class='form-check-input' name='dep_10_check' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-8">
																<label class='form-check-label'>Отдел 10</label>
															</div>
														</div>
														<div class='row'>
															<div class="col-md-2">
																@if(old('shop_1'))
																	<input id='shop_1' class='form-check-input' name='shop_1' type="checkbox" checked />
																@else
																	@if($contract->shop_1 == 1)
																		<input id='shop_1' class='form-check-input' name='shop_1' type="checkbox" checked />
																	@else
																		<input id='shop_1' class='form-check-input' name='shop_1' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-2">
																@if(old('shop_1_check'))
																	<input id='shop_1_check' class='form-check-input' name='shop_1_check' type="checkbox" checked />
																@else
																	@if($contract->shop_1_check == 1)
																		<input id='shop_1_check' class='form-check-input' name='shop_1_check' type="checkbox" checked />
																	@else
																		<input id='shop_1_check' class='form-check-input' name='shop_1_check' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-8">
																<label class='form-check-label'>Цех 1</label>
															</div>
														</div>
														<div class='row'>
															<div class="col-md-2">
																@if(old('shop_2'))
																	<input id='shop_2' class='form-check-input' name='shop_2' type="checkbox" checked />
																@else
																	@if($contract->shop_2 == 1)
																		<input id='shop_2' class='form-check-input' name='shop_2' type="checkbox" checked />
																	@else
																		<input id='shop_2' class='form-check-input' name='shop_2' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-2">
																@if(old('shop_2_check'))
																	<input id='shop_2_check' class='form-check-input' name='shop_2_check' type="checkbox" checked />
																@else
																	@if($contract->shop_2_check == 1)
																		<input id='shop_2_check' class='form-check-input' name='shop_2_check' type="checkbox" checked />
																	@else
																		<input id='shop_2_check' class='form-check-input' name='shop_2_check' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-8">
																<label class='form-check-label'>Цех 2</label>
															</div>
														</div>
														<div class='row'>
															<div class="col-md-2">
																@if(old('shop_3'))
																	<input id='shop_3' class='form-check-input' name='shop_3' type="checkbox" checked />
																@else
																	@if($contract->shop_3 == 1)
																		<input id='shop_3' class='form-check-input' name='shop_3' type="checkbox" checked />
																	@else
																		<input id='shop_3' class='form-check-input' name='shop_3' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-2">
																@if(old('shop_3_check'))
																	<input id='shop_3_check' class='form-check-input' name='shop_3_check' type="checkbox" checked />
																@else
																	@if($contract->shop_3_check == 1)
																		<input id='shop_3_check' class='form-check-input' name='shop_3_check' type="checkbox" checked />
																	@else
																		<input id='shop_3_check' class='form-check-input' name='shop_3_check' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-8">
																<label class='form-check-label'>Цех 3</label>
															</div>
														</div>
														<div class='row'>
															<div class="col-md-2">
																@if(old('ootiz'))
																	<input id='ootiz' class='form-check-input' name='ootiz' type="checkbox" checked />
																@else
																	@if($contract->ootiz == 1)
																		<input id='ootiz' class='form-check-input' name='ootiz' type="checkbox" checked />
																	@else
																		<input id='ootiz' class='form-check-input' name='ootiz' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-2">
																@if(old('ootiz_check'))
																	<input id='ootiz_check' class='form-check-input' name='ootiz_check' type="checkbox" checked />
																@else
																	@if($contract->ootiz_check == 1)
																		<input id='ootiz_check' class='form-check-input' name='ootiz_check' type="checkbox" checked />
																	@else
																		<input id='ootiz_check' class='form-check-input' name='ootiz_check' type="checkbox" />
																	@endif
																@endif
															</div>
															<div class="col-md-8">
																<label class='form-check-label'>ООТиЗ</label>
															</div>
														</div>-->
													</div>
												</div>
											</div>
											<div class="col-md-2">
												<div class='form-group row'>
													<div class='col-md-12' style='text-align: right;'>
														<button class='btn btn-primary' data-toggle="modal" data-target="#scan" type='button' style='width: 154px;'>Скан</button>
													</div>
												</div>
												<!--<div class='form-group row'>
													<div class='col-md-12' style='text-align: right;'>
														<button class='btn btn-primary btn-href' type='button' href='{{route("department.reconciliation.document",$number_application)}}' style='width: 154px;'>Документ</button>
													</div>
												</div>-->
												@if(Auth::User()->hasRole()->role == 'Десятый отдел')
													<div class='form-group row'>
														<div class='col-md-12' style='text-align: right;'>
															<button class='btn btn-primary btn-href' type='button' style='width: 154px;' href='{{ route("ten.show_contract", $contract->id) }}'>Комплектующие</button>
														</div>
													</div>
												@endif
												@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел' AND Auth::User()->hasRole()->role != 'Администрация')
													<div class='form-group row'>
														<div class='col-md-12' style='text-align: right;'>
															<button class='btn btn-primary' data-toggle="modal" data-target="#message" type='button' style='width: 154px;'>Прикрепить</button>
														</div>
													</div>
													<div class='form-group row'>
														<div class='col-md-12' style='text-align: right;'>
															<button class='btn btn-primary btn-href' type='button' style='width: 154px;' href='{{ route("reconciliation.contract.show", $contract->id) }}'>Согласование</button>
														</div>
													</div>
													<div class='form-group row'>
														<div class='col-md-12' style='text-align: right;'>
															<button class='btn btn-primary btn-href' type='button' style='width: 154px;' href='{{ route("ten.show_contract", $contract->id) }}'>Комплектующие</button>
														</div>
													</div>
													<div class='form-group row'>
														<div class="col-md-12" style='text-align: right;'>
															<button class='btn btn-primary btn-href' type='button' style='width: 154px;' href='{{route("department.contract_second.show", $contract->id)}}'>Наряды</button>
														</div>
													</div>
													<div class='form-group row'>
														<div class="col-md-12" style='text-align: right;'>
															<button class='btn btn-primary' data-toggle="modal" data-target="#invoice" type='button' style='width: 154px;'>Взаиморасчеты</button>
														</div>
													</div>
													<div class='form-group row'>
														<div class='col-md-12' style='text-align: right;'>
															<button type='submit' class="btn btn-primary" style='width: 154px;' disabled>Сохранить договор</button>
														</div>
													</div>
												@endif
												<div class='form-group row'>
													<div class='col-md-12' style='text-align: right;'>
														@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел' AND Auth::User()->hasRole()->role != 'Администрация')
															<button class='btn btn-secondary btn-href' type='button' href='{{route("department.reconciliation.document",$number_application)}}' style='width: 154px;'>Выход</button>
														@else
															<button class='btn btn-secondary btn-href' type='button' href='{{route("department.management.contracts")}}' style='width: 154px;'>Выход</button>
														@endif
													</div>
												</div>
												<!--<div class='form-group row'>
													<div class='col-md-12' style='text-align: right;'>
														<button type='button' class="btn btn-secondary btn-href" href="{{ route('department.reconciliation') }}" style='width: 154px;'>Выход</button>
													</div>
												</div>-->
												<!--<div class='form-group row'>
													<div class='col-md-12' style='text-align: right;'>
														<button class='btn btn-primary' type='button' style='width: 125px;' onclick='alert("Список протоколов!\nКакие изменения?\nВ меню входящих писем добавить пункт прикрепить протокол\nА в карточке выводить краткую таблицу");'>Протоколы</button>
													</div>
												</div>-->
											</div>
										</div>
										<div class="row">
											<div class="col-md-2">
												<label for='implementationInput2'>Выполнение</label>
											</div>
											<div class="col-md-4">
												<div class='form-group row'>
													<label for='amountImplementationContract' class='col-md-6 col-form-label'>Сумма с НДС, т.р.</label>
													<div class='col-md-6'>
														<input id='amountImplementationContract' class='form-control check-number {{$errors->has("amoun_implementation_contract") ? print("inputError ") : print("")}}' type='text' name='amoun_implementation_contract' value='{{ old("amoun_implementation_contract") ? old("amoun_implementation_contract") : $contract->amoun_implementation_contract }}'/>
														@if($errors->has('amoun_implementation_contract'))
															<label class='msgError'>{{$errors->first('amoun_implementation_contract')}}</label>
														@endif
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class='form-check'>
													@if(old("check_implementation_contract"))
														<input id='implementationСontract' class='form-check-input implementationCheck' type="checkbox" name='check_implementation_contract' checked />
													@else
														@if($contract->comment_implementation_contract == 'Выполнен')
															<input id='implementationСontract' class='form-check-input implementationCheck' type="checkbox" name='check_implementation_contract' checked />
														@else
															<input id='implementationСontract' class='form-check-input implementationCheck' type="checkbox" name='check_implementation_contract'/>
														@endif
													@endif
													<label class='form-check-label' for='implementationСontract'>Выполнен</label>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												@if(old("comment_implementation_contract"))
													<input id='implementationInput' class='form-control implementationInput' type='text' name='comment_implementation_contract' value='{{old("comment_implementation_contract") ? old("comment_implementation_contract") : $contract->comment_implementation_contract}}'/>
												@else
													@if($contract->comment_implementation_contract == 'Выполнен')
														<input id='implementationInput' class='form-control implementationInput' type='text' name='comment_implementation_contract' value='{{old("comment_implementation_contract") ? old("comment_implementation_contract") : $contract->comment_implementation_contract}}' style='display: none;'/>
													@else
														<input id='implementationInput' class='form-control implementationInput' type='text' name='comment_implementation_contract' value='{{old("comment_implementation_contract") ? old("comment_implementation_contract") : $contract->comment_implementation_contract}}'/>
													@endif
												@endif
											</div>
										</div>
										<div class='row'>
											<div class="col-md-5">
												<div class="row">
													<div class="col-md-12">
														<label>Контрольные точки</label>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<table class="table table-bordered" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px; text-align: center;'>
															<thead>
																<tr>
																	<th>Дата</th>
																	<th>Результат</th>
																	<th>Выполнение</th>
																</tr>
															</thead>
															<tbody>
																@foreach($checkpoints as $checkpoint)
																<tr <?php if($checkpoint->check_checkpoint == 0 && time() - strtotime($checkpoint->date_checkpoint) > 0) echo "style='color: red;'" ?>>
																	<td>{{ $checkpoint->date_checkpoint }}</td>
																	<td>{{ $checkpoint->message_checkpoint }}</td>
																	<td>
																		@if($checkpoint->check_checkpoint == 0)
																			<input class='form-check-input btn-href' href='{{ route("department.reconciliation.checkpoint_update", $checkpoint->id)}}?check=on' type="checkbox"/>
																		@else
																			<input class='form-check-input btn-href' href='{{ route("department.reconciliation.checkpoint_update", $checkpoint->id)}}?check=off' type="checkbox" checked />
																		@endif
																	</td>
																</tr>
																@endforeach
															</tbody>
														</table>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел' AND Auth::User()->hasRole()->role != 'Администрация')
															<button class='btn btn-primary' data-toggle="modal" data-target="#checkpoint" type='button'>Добавить контрольную точку</button>
														@endif
													</div>
												</div>
											</div>
											<div class="col-md-7">
												<div class="row">
													<div class="col-md-12">
														<label>Протоколы</label>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<table class="table table-bordered" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px; text-align: center;'>
															<thead>
																<tr>
																	<th>Название</th>
																	<th>№ исх</th>
																	<th>Дата</th>
																	<th>№ вх</th>
																	<th>Дата</th>
																	<th>Коммент.</th>
																	<th></th>
																</tr>
															</thead>
															<tbody>
																@foreach($protocols as $protocol)
																	@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел' AND Auth::User()->hasRole()->role != 'Администрация')
																		<tr class='rowsContract cursorPointer btn-href' href='{{route("reconciliation.application", $protocol->id)}}'>
																	@else
																		<tr class='rowsContract cursorPointer'>
																	@endif
																		<td>
																			{{ $protocol->name_protocol }}
																		</td>
																		<td>
																			{{ $protocol->number_outgoing }}
																		</td>
																		<td>
																			{{ date('d.m.Y', strtotime($protocol->date_outgoing)) }}
																		</td>
																		<td>
																			{{ $protocol->number_incoming }}
																		</td>
																		<td>
																			{{ date('d.m.Y', strtotime($protocol->date_incoming)) }}
																		</td>
																		<td>
																			{{ $protocol->comment_application }}
																		</td>
																		<td>
																			<?php
																				if($protocol->check_reconciliation == true)
																					echo "<input class='form-check-input' type='checkbox' checked disabled />";
																				else
																					echo "<input class='form-check-input' type='checkbox' disabled />";
																			?>
																		</td>
																	</tr>
																@endforeach
																@foreach($protocols_oud as $protocol)
																	@if($protocol->is_protocol == 1)
																		<tr class='rowsContract cursorPointer btn-href' href='{{route("department.reestr.show_protocols", $protocol->id_contract)}}'>
																	@elseif($protocol->is_additional_agreement == 1)
																		<tr class='rowsContract cursorPointer btn-href' href='{{route("department.reestr.show_additional_agreements", $protocol->id_contract)}}'>
																	@else
																		<tr>
																	@endif
																		<td>{{$protocol->name_protocol}}</td>
																		<td></td>
																		<td></td>
																		<td></td>
																		<td></td>
																		<td>ОУД</td>
																		<td></td>
																	</tr>
																@endforeach
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!--<div class="row">
								<div class="col-md-11" style='text-align: right;'>
									<button type='button' class="btn btn-secondary btn-href" style="float: right;" href="{{ route('department.reconciliation') }}">Выход</button>
								</div>
								<div class="col-md-1" style='text-align: right;'>
									<button type='submit' class="btn btn-primary" style="float: right;">Сохранить договор</button>
								</div>
							</div>-->
						</div>
						<div class="row">
						</div>
					</form>
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
											@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел' AND Auth::User()->hasRole()->role != 'Администрация')
												<button id='add_new_resolution' type='button' class='btn btn-secondary'>Добавить скан</button>
											@endif
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
											<input id='new_file_resolution' type='file' name='new_file_resolution'/>
										</div>
										<div class='col-md-6'>
											<input id='date_resolution' name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
										</div>
									</div>
									<div class='row'>
										<div class='col-md-12'>
											<label>Наименование документа</label>
											<input id='real_name_resolution' type='text' name='real_name_resolution' class='form-control'/>
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
				<!-- Модальное окно писем -->
				<div class="modal fade" id="message" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true" attr-open-modal='{{Session::has("delete_message") || Session::has("create_message") ? print("open") : print("")}}'>
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<form id='form_message' method='POST' action='{{ route("department.reconciliation.reconciliation_contract_message", $contract->id) }}'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="messageModalLabel">Прикрепление писем</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='form-group row'>
										
									</div>
									<div id='message_div' class='form-group row'>
										<!-- Выпадающий список писем -->
										@if(count($my_applications) > 0)
											<div class="col-md-12">
												<label>Прикрепленные:</label>
											</div>
											<!--<div class='col-md-8' style='margin-top: 5px;'>
												<select id='select_message0' class='form-control'>
													@foreach($my_applications as $application)
														<option value="{{ $application->id }}" number_application="{{$application->number_application}}" number_outgoing="{{$application->number_outgoing}}" date_outgoing="{{ date('d.m.Y', strtotime($application->date_outgoing)) }}" number_incoming="{{$application->number_incoming}}" date_incoming="{{ date('d.m.Y', strtotime($application->date_incoming)) }}" theme_application="{{$application->theme_application}}" is_protocol="{{$application->is_protocol}}" name_protocol="{{$application->name_protocol}}" comment_application="{{$application->comment_application}}" href_create_protocol="{{route('department.reconciliation.store_protocol',$application->id)}}">Номер письма: {{$application->number_application}} / Входящий номер: {{$application->number_incoming}}</option>
													@endforeach
												<select>
											</div>
											<div class='col-md-4' style='margin-top: 5px;'>
												<button type='button' class='btn btn-primary btn_select_message' for_message='select_message0'>Просмотреть</button>
											</div>-->
											<div class='col-md-12'>
												<table class="table table-bordered" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px; text-align: center;'>
													<thead>
														<tr>
															<th>Протокол/Доп.согл</th>
															<th>№ исх</th>
															<th>Дата</th>
															<th>№ вх</th>
															<th>Дата</th>
															<th>Тема</th>
															<th style='width: 120px;'></th>
														</tr>
													</thead>
													<tbody>
														@foreach($my_applications as $application)
															<tr title='Создать протокол' class='rowsContract cursorPointer table_select_message cursorProtocol' number_application="{{$application->number_application}}" number_outgoing="{{$application->number_outgoing}}" date_outgoing="{{ date('d.m.Y', strtotime($application->date_outgoing)) }}" number_incoming="{{$application->number_incoming}}" date_incoming="{{ date('d.m.Y', strtotime($application->date_incoming)) }}" theme_application="{{$application->theme_application}}" is_protocol="{{$application->is_protocol}}" name_protocol="{{$application->name_protocol}}" is_additional_agreement="{{$application->is_additional_agreement}}" comment_application="{{$application->comment_application}}" href_create_protocol="{{route('department.reconciliation.store_protocol',$application->id)}}">
																<td>
																	<?php
																		if($application->is_protocol == 1)
																			echo "<input class='form-check-input' type='checkbox' checked disabled />";
																		else
																			echo "<input class='form-check-input' type='checkbox' disabled />";
																	?>
																</td>																
																<td>
																	{{ $application->number_outgoing }}
																</td>
																<td>
																	{{ date('d.m.Y', strtotime($application->date_outgoing)) }}
																</td>
																<td>
																	{{ $application->number_incoming }}
																</td>
																<td>
																	{{ date('d.m.Y', strtotime($application->date_incoming)) }}
																</td>
																<td>
																	{{ $application->theme_application }}
																</td>
																<td class='table_coll_btn'>
																	<button type="button" class="btn btn-secondary btn-href" href='{{route("department.reconciliation.reconciliation_contract_message_delete", $application->id)}}'>Открепить</button>
																</td>
															</tr>
														@endforeach
													</tbody>
												</table>
											</div>
										@else
											<div class="col-md-12">
												<label>Прикрепленных протоколов нет!</label>
											</div>
										@endif
										<div class="col-md-12" style='margin-top: 5px;'>
											<label>Прикрепить:</label>
										</div>
										<!-- Выпадающий список писем будущих -->
										<div class='col-md-12'>
											<table class="table table-bordered" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px; text-align: center;'>
												<thead>
													<tr>
														<th></th>
														<th>№ исх</th>
														<th>Дата</th>
														<th>№ вх</th>
														<th>Дата</th>
														<th>Тема</th>
													</tr>
												</thead>
												<tbody>
													<?php
														$select_message = 0;
													?>
													@if($applications)
														@foreach($applications as $application)
															<tr class='rowsContract cursorPointer tableRowsMessage' for_check='check_message{{$select_message}}'>
																<td>
																	<input id='check_message{{$select_message}}' class='form-check-input' name='select_message[{{$select_message}}]' type='checkbox' value='{{$application->id}}'/>
																</td>
																<td>
																	{{ $application->number_outgoing }}
																</td>
																<td>
																	{{ date('d.m.Y', strtotime($application->date_outgoing)) }}
																</td>
																<td>
																	{{ $application->number_incoming }}
																</td>
																<td>
																	{{ date('d.m.Y', strtotime($application->date_incoming)) }}
																</td>
																<td>
																	{{ $application->theme_application }}
																</td>
															</tr>
															<?php
																$select_message++;
															?>
														@endforeach
													@endif
												</tbody>
											</table>
										</div>
									</div>
									<!--<div class='form-group row'>
										<div class="col-md-12">
											@if($applications)
												<button id='add_message' type='button' class='btn btn-primary' new_message='@foreach($applications as $application)<option value="{{ $application->id }}" number_application="{{$application->number_application}}" number_outgoing="{{$application->number_outgoing}}" date_outgoing="{{ $application->date_outgoing != null ? date("d.m.Y", strtotime($application->date_outgoing)) : '' }}" number_incoming="{{$application->number_incoming}}" date_incoming="{{ $application->date_incoming != null ? date("d.m.Y", strtotime($application->date_incoming)) : '' }}" theme_application="{{$application->theme_application}}">Номер письма: {{$application->number_application}} / Входящий номер: {{$application->number_incoming}}</option>@endforeach'>Добавить входящее письмо</button>
											@endif
										</div>
									</div>-->
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary'>Сохранить</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
							<div id='this_message' style='display: none;'>
								<div class="modal-header">
									<h5 class="modal-title">Письмо</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='form-group row'>
										<div class="col-md-12">
											<label>Номер письма:</label>
										</div>
										<div class="col-md-12">
											<input id='number_application' class='form-control' type='text' readonly />
										</div>
										<div class="col-md-6">
											<label>Номер исх.:</label>
										</div>
										<div class="col-md-6">
											<label>Дата:</label>
										</div>
										<div class="col-md-6">
											<input id='number_outgoing' class='form-control' type='text' readonly />
										</div>
										<div class="col-md-6">
											<input id='date_outgoing' class='form-control' type='text' readonly />
										</div>
										<div class="col-md-6">
											<label>Номер вх.:</label>
										</div>
										<div class="col-md-6">
											<label>Дата:</label>
										</div>
										<div class="col-md-6">
											<input id='number_incoming' class='form-control' type='text' readonly />
										</div>
										<div class="col-md-6">
											<input id='date_incoming' class='form-control' type='text' readonly />
										</div>
										<div class="col-md-12">
											<label>Тема:</label>
										</div>
										<div class="col-md-12">
											<textarea id='theme_application' class='form-control' type="text" style="width: 100%;" rows='4' readonly></textarea>
										</div>
										<div class="col-md-4">
											<label id='label_text_protocol'>Название протокола:</label>
										</div>
										<form id='form_create_protocol' method='POST' action=''>
											{{csrf_field()}}
											<div class="col-md-8">
												<input id='additional_agreement' class='form-check-input' type="checkbox" name='additional_agreement'/>
												<label for='additional_agreement'>доп. соглашение</label>
											</div>
											<div class="col-md-12">
												<input id='name_protocol' name='name_protocol' class='form-control' type='text' required/>
											</div>
											<!--<div class="col-md-6">
												<select id='directed_application' name='directed_protocol' class='form-control {{$errors->has("directed_protocol") ? print("inputError ") : print("")}}' required>
													<option></option>
													@foreach($all_users as $user)
														@if(old('directed_protocol'))
															@if(old('directed_protocol') == $user->id)
																<option value='{{$user->id}}' real_name='{{ $user->surname . " " . $user->name . " " . $user->patronymic}}' selected>{{ $user->surname . ' ' . mb_substr($user->name,0,1) . '.' . mb_substr($user->patronymic,0,1) . '.' }}</option>
															@else
																<option value='{{$user->id}}' real_name='{{ $user->surname . " " . $user->name . " " . $user->patronymic}}'>{{ $user->surname . ' ' . mb_substr($user->name,0,1) . '.' . mb_substr($user->patronymic,0,1) . '.' }}</option>
															@endif
														@else												
															<option value='{{$user->id}}' real_name='{{ $user->surname . " " . $user->name . " " . $user->patronymic}}'>{{ $user->surname . ' ' . mb_substr($user->name,0,1) . '.' . mb_substr($user->patronymic,0,1) . '.' }}</option>
														@endif
													@endforeach
												</select>
											</div>-->
											<div class="col-md-12" style='margin-top: 5px;'>
												<button id='create_protocol' type="submit" class="btn btn-primary">Согласование</button>
											</div>
										</form>
									</div>
								</div>
								<div class="modal-footer">
									<button id='close_message' type="button" class="btn btn-secondary">Закрыть письмо</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Модальное окно нового процесса -->
				<div class="modal fade" id="process" tabindex="-1" role="dialog" aria-labelledby="processModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form method='POST' action='{{ route("department.reconciliation.create_process", $contract->id) }}'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="processModalLabel">Новый процесс согласования</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Процесс:</label>
										</div>
										<div class="col-md-4">
											<input class='form-control' type='text' name='process_reconciliation' value=''/>
										</div>
										<div class="col-md-4">
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Запущен:</label>
										</div>
										<div class="col-md-4">
											<input class='form-control datepicker' type='text' name='b_date_reconciliation' value="{{ date('d.m.Y', time())}}" readonly />
										</div>
										<div class="col-md-4">
										</div>
									</div>
									<!--<div class='form-group row'>
										<div class="col-md-3">
											<label>Завершен:</label>
										</div>
										<div class="col-md-4">
											<input class='form-control datepicker' type='text' value=''/>
										</div>
										<div class="col-md-4">
										</div>
									</div>-->
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' style='width: 122px;'>Добавить</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Модальное окно новой контрольной точки -->
				<div class="modal fade" id="checkpoint" tabindex="-1" role="dialog" aria-labelledby="checkpointModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form method='POST' action='{{ route("department.reconciliation.checkpoint_store", $contract->id) }}'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="checkpointModalLabel">Новая контрольная точка</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Дата:</label>
										</div>
										<div class="col-md-7">
											<input class='form-control datepicker' type='text' name='date_checkpoint' value=""/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Результат:</label>
										</div>
										<div class="col-md-7">
											<input name='message_checkpoint' class='form-control' type='text' value=''/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел')
										<button type='submit' class='btn btn-primary' style='width: 122px;'>Добавить</button>
									@endif
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Модальное окно деталей -->
				<div class="modal fade" id="detailing" tabindex="-1" role="dialog" aria-labelledby="detailingModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form method='POST' action=''>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="detailingModalLabel">Новое изделие</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Изделие:</label>
										</div>
										<div class="col-md-7">
											<select id='name_element' name='id_element' class='form-control'>
												<option></option>
												@if(old('id_element'))
													@foreach($elements as $element)
														@if(old('id_element') == $element->id)
															<option value="{{$element->id}}" selected>{{$element->name_element}}</option>
														@else
															<option value="{{$element->id}}">{{$element->name_element}}</option>
														@endif
													@endforeach
												@else
													@foreach($elements as $element)
															<option value="{{$element->id}}">{{$element->name_element}}</option>
													@endforeach
												@endif
											</select>
										</div>
										<div class="col-md-4">
										</div>
									</div>
									@if($contract->name_view_contract == "Испытания контрольные")
										<div class='form-group row'>
											<div class="col-md-3">
												<label>Наименование испытания:</label>
											</div>
											<div class="col-md-7">
												<select id='name_view_work' name='name_view_contract' class='form-control'>
													<option></option>
													@if(old('name_view_contract'))
														@foreach($view_work_elements as $view)
															@if(old('name_view_contract') == $view->id)
																<option value="{{$view->id}}" selected>{{$view->name_view_work_elements}}</option>
															@else
																<option value="{{$view->id}}">{{$view->name_view_work_elements}}</option>
															@endif
														@endforeach
													@else
														@foreach($view_work_elements as $view)
															<option value="{{$view->id}}">{{$view->name_view_work_elements}}</option>
														@endforeach
													@endif
												</select>
											</div>
											<div class="col-md-4">
											</div>
										</div>
									@endif
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Количество:</label>
										</div>
										<div class="col-md-7">
											<input id='count_elements' class='form-control' type='text' name='count_elements' value="{{old('count_elements')}}"/>
										</div>
										<div class="col-md-4">
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Год:</label>
										</div>
										<div class="col-md-7">
											<input id='year_isp' class='form-control' type='text' name='year_isp' value="{{ old('year_isp') ? old('year_isp') : date('Y', time())}}"/>
										</div>
										<div class="col-md-4">
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' style='width: 122px;'>Сохранить</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Модальное окно присвоения номера договора -->
				<div class="modal fade" id="selectContract" tabindex="-1" role="dialog" aria-labelledby="selectContractModalLabel" aria-hidden="true" attr-open-modal='{{$errors->has("number_contract") || $errors->has("number_pp") || $errors->has("index_dep") || $errors->has("year_contract") ? print("open") : print("")}}'>
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form id='send_new_number' method='POST' action="{{ route('department.management.new_number',$contract->id) }}">
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="selectContractModalLabel">Договор</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for='number_pp'>№ п/п</label>
												<input id='number_pp' class='change_contract_number form-control {{$errors->has("number_pp") ? print("inputError ") : print("")}}' name='number_pp' type='text' value='{{old("number_pp")}}' required/>
												@if($errors->has('number_pp'))
													<label class='msgError'>{{$errors->first('number_pp')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for='index_dep'>Индекс подразд.</label>
												<select id='index_dep' class='change_contract_number form-control {{$errors->has("index_dep") ? print("inputError ") : print("")}}' name='index_dep' type='text' value='{{old("index_dep")}}' required>
													@if(old('index_dep'))
														<option>{{old('index_dep')}}</option>
													@endif
													<option></option>
													@foreach($departments as $department)
														<option value='{{$department->index_department}}'>{{$department->index_department}} {{$department->name_department}}</option>
													@endforeach
												</select>
												@if($errors->has('index_dep'))
													<label class='msgError'>{{$errors->first('index_dep')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for='year_contract'>Год</label>
												<input id='year_contract' class='change_contract_number form-control {{$errors->has("year_contract") ? print("inputError ") : print("")}}' name='year_contract' type='text' value='{{old("year_contract") ? old("year_contract") : date("Y", time())}}' required/>
												@if($errors->has('year_contract'))
													<label class='msgError'>{{$errors->first('year_contract')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label for='numberContract'>Номер договора</label>
												<input id='numberContract' class='form-control {{$errors->has("number_contract") ? print("inputError ") : print("")}}' name='number_contract' type='text' value='{{old("number_contract")}}' readonly />
												@if($errors->has('number_contract'))
													<label class='msgError'>{{$errors->first('number_contract')}}</label>
												@endif
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="submit" class="btn btn-primary">Сохранить</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Счета -->
				<div class="modal fade" id="invoice" tabindex="-1" role="dialog" aria-labelledby="invoiceModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="invoiceModalLabel">Счета</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class='row'>
									<div class="col-md-12">
										<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
											<thead>
												<tr>
													<th rowspan='7' style='text-align: center; vertical-align: middle; max-width: 94px;'>По предприятию</th>
												</tr>
												<tr>
													<th  colspan='2'>Аванс</th>
													<th>{{$amount_prepayments_all}} р.</th>
												</tr>
												<tr>
													<th  colspan='2'>Оказано услуг</th>
													<th>{{$amount_invoices_all}} р.</th>
												</tr>
												<tr>
													<th  colspan='2'>Окончательный расчет</th>
													<th>{{$amount_payments_all}} р.</th>
												</tr>
												<tr>
													<th rowspan='2' style='vertical-align: middle;'>Задолженность</th>
													<th>Дебет</th>
													<th>{{(($amount_prepayments_all + $amount_payments_all) - $amount_invoices_all - $amount_returns_all) > 0 ? ($amount_prepayments_all + $amount_payments_all) - $amount_invoices_all - $amount_returns_all : 0}} р.</th>
												</tr>
												<tr>
													<th>Кредит</th>
													<th>{{($amount_invoices_all - ($amount_prepayments_all + $amount_payments_all) + $amount_returns_all) > 0 ? $amount_invoices_all - ($amount_prepayments_all + $amount_payments_all) + $amount_returns_all : 0}} р.</th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
											<thead>
												<tr>
													<th rowspan='7' style='text-align: center; vertical-align: middle; max-width: 94px;'>Оплата и исполнение договора</th>
												</tr>
												<tr>
													<th  colspan='2'>Аванс</th>
													<th>{{$amount_prepayments}} р.</th>
												</tr>
												<tr>
													<th  colspan='2'>Оказано услуг</th>
													<th>{{$amount_invoices}} р.</th>
												</tr>
												<tr>
													<th  colspan='2'>Окончательный расчет</th>
													<th>{{$amount_payments}} р.</th>
												</tr>
												<tr>
													<th rowspan='2' style='vertical-align: middle;'>Задолженность</th>
													<th>Дебет</th>
													<th>{{(($amount_prepayments + $amount_payments) - $amount_invoices - $amount_returns) > 0 ? ($amount_prepayments + $amount_payments) - $amount_invoices - $amount_returns : 0}} р.</th>
												</tr>
												<tr>
													<th>Кредит</th>
													<th>{{($amount_invoices - ($amount_prepayments + $amount_payments) + $amount_returns) > 0 ? $amount_invoices - ($amount_prepayments + $amount_payments) + $amount_returns : 0}} р.</th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
							</div>
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
															<th>Автор</th>
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
																<td>{{$state->surname . ' ' . mb_substr($state->name, 0, 1) . '.' . mb_substr($state->patronymic, 0, 1) . '.'}}</td>
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
													<input id='date_state' class='form-control {{$errors->has("date_state") ? print("inputError ") : print("")}}' name='date_state' value='{{date("d.m.Y", time())}}' readonly />
													@if($errors->has('date_state'))
														<label class='msgError'>{{$errors->first('date_state')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class='col-md-12'>
											@if(Auth::User()->hasRole()->role != 'Администрация')
												<button id='btn_add_state' class='btn btn-secondary' type='button' clear_date='{{date("d.m.Y", time())}}' action_state='{{ route("department.ekonomic.new_state",$contract->id)}}' style='margin-top: 10px;'>Добавить состояние</button>
											@endif
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
				<script>
					if($('#selectContract').attr('attr-open-modal') == 'open1')
						$('#selectContract').modal('show');
					if($('#message').attr('attr-open-modal') == 'open1')
						$('#message').modal('show');
					$('#additional_agreement').on('click', function(){
						if($('#additional_agreement').prop('checked')){
							$('#label_text_protocol').text('Название доп. соглашения:');
							$('#name_protocol').val('Доп.соглашение ');
						}
						else{
							$('#label_text_protocol').text('Название протокола:');
							$('#name_protocol').val('Протокол ');
						}
					});
				</script>
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
