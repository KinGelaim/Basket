@extends('layouts.header')

@section('title')
	Новый реестр
@endsection

@section('content')
	<?php
		if(isset($_GET['time']))
			$time_load_start = microtime(1);
	?>
	<div class="flex-center position-ref full-height">
		@if(Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Отдел управления договорами' OR Auth::User()->hasRole()->role == 'Администрация')
				@if(Route::has('login'))
					<div class="top-right links">
						
					</div>
				@endif
				
				@if($contract)
					@if($contract->is_sip_contract == '1')
						<!-- РЕДАКТИРОВАНИЕ СИПОВСКОГО КОНТРАКТА -->
						<?php
							$is_disabled = '';
							if(Auth::User()->hasRole()->role == 'Отдел управления договорами')
								if(Auth::User()->surname != 'Бастрыкова' && Auth::User()->surname != 'Филиппова')
									$is_disabled = 'readonly';
							$is_peo_disabled = '';
							if(count(explode("‐",$contract->number_contract))>1)
								if(Auth::User()->hasRole()->role != 'Планово-экономический отдел' && explode("‐",$contract->number_contract)[1] == '02')
									$is_peo_disabled = 'readonly';
						?>
						<div class="content">
							<form method='POST' action="{{route('department.ekonomic.update_reestr', $contract->id)}}">
								{{csrf_field()}}
								<div class="row border-top border-bottom border-left border-right">
									<div class="col-md-2 border-top border-left border-bottom">
										<label>Контрагент</label>
										<div class="form-group">
											<select id="sel4" class='form-control select_counterpartie_reestr {{$errors->has("id_counterpartie_contract") ? print("inputError ") : print("")}}' name='id_counterpartie_contract' {{$is_disabled}}>
												@if($is_disabled == '')
													<option></option>
												@endif
												@if(!isset($_GET['contr']))
													@if($counterparties)
														@foreach($counterparties as $counterpartie)
															@if($is_disabled == '')
																@if(old('id_counterpartie_contract'))
																	@if(old('id_counterpartie_contract') == $counterpartie->id)
																		<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}' selected>{{ $counterpartie->name }}</option>
																	@else
																		@if(Auth::User()->hasRole()->role == 'Администратор')
																			<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
																		@elseif(Auth::User()->hasRole()->role == 'Отдел управления договорами')
																			<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
																		@else
																			@if($counterpartie->is_sip_counterpartie == '1')
																				<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
																			@endif
																		@endif													
																	@endif
																@else
																	@if($contract->id_counterpartie_contract == $counterpartie->id)
																		<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}' selected>{{ $counterpartie->name }}</option>
																	@else
																		@if(Auth::User()->hasRole()->role == 'Администратор')
																			<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
																		@elseif(Auth::User()->hasRole()->role == 'Отдел управления договорами')
																			<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
																		@else
																			@if($counterpartie->is_sip_counterpartie == '1')
																				<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
																			@endif
																		@endif
																	@endif
																@endif
															@else
																@if($contract->id_counterpartie_contract == $counterpartie->id)
																	<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}' selected>{{ $counterpartie->name }}</option>
																@endif
															@endif
														@endforeach
													@endif
												@endif
											</select>
											@if($errors->has('id_counterpartie_contract'))
												<label class='msgError'>{{$errors->first('id_counterpartie_contract')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-1 border-top border-bottom">
										<div class="form-group">
											<button type='button' data-toggle="modal" data-target="#chose_counterpartie" class="btn btn-primary" style='margin-top: 27px;'>Выбрать</button>
										</div>
									</div>
									<div class="col-md-2 border-top border-bottom">
										<div class="form-group">
											<label>Внимание!</label>
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
									</div>
									<div class="col-md-3 border-top border-bottom border-right">
										<div class="col-md-7">
											<div class="form-group">
												<label for='numberContract'>Номер договора</label>
												<input id='numberContract' class='form-control {{$errors->has("number_contract") ? print("inputError ") : print("")}}' name='number_contract' type='text' value='{{old("number_contract") ? old("number_contract") : $contract->number_contract}}' readonly />
												@if($errors->has('number_contract'))
													<label class='msgError'>{{$errors->first('number_contract')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-5" style='text-align: center;'>
											<div class='form-group' style='margin-top: 27px;'>
												@if(isset($prev_contract))
													<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $prev_contract)}}" title='Назад' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
												@else
													<button class='btn btn-primary' title='Назад' disabled type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
												@endif
												@if(isset($next_contract))
													<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $next_contract)}}" title='Вперед' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
												@else
													<button class='btn btn-primary' title='Вперед' disabled type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-2 border-bottom border-top">
										<div class="form-group">
											<label for='amount_contract_reestr'>Сумма (окончательная)</label>
											<input id='amount_contract_reestr' class='form-control check-number' name='amount_contract_reestr' type='text' value='{{old("amount_contract_reestr") ? old("amount_contract_reestr") : $reestr->amount_contract_reestr}}' {{$is_disabled}}/>
										</div>
									</div>
									<div class="col-md-2 border-bottom border-right border-top">
										<div class="form-group ">
											<label for='amount_invoice_reestr'>Сумма по счетам</label>
											<input id='amount_invoice_reestr' class='form-control check-number' name='amount_invoice_reestr' type='text' value='{{$reestr->amount_invoice_reestr}}' disabled />
										</div>
									</div>
								</div>
								<div class="row border-right border-left">
									<div class="col-md-1 border-top border-bottom">
										<div class="form-group">
											<label for='number_pp'>№ п/п</label>
											<input id='number_pp' class='change_contract_number form-control {{$errors->has("number_pp") ? print("inputError ") : print("")}}' name='number_pp' type='text' value='{{old("number_pp") ? old("number_pp") : (strlen($contract->number_contract) > 0 ? explode("‐",$contract->number_contract)[0] : "")}}' required/>
											@if($errors->has('number_pp'))
												<label class='msgError'>{{$errors->first('number_pp')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-1 border-top border-bottom">
										<div class="form-group">
											<label for='index_dep' style='font-size: 12px;'>Индекс подразд.</label>
											<select id='index_dep' class='change_contract_number form-control {{$errors->has("index_dep") ? print("inputError ") : print("")}}' name='index_dep' type='text' value='{{old("index_dep")}}' required>
												@if(old('index_dep'))
													<option>{{old('index_dep')}}</option>
												@endif
												<option></option>
												@foreach($departments as $department)
													@if(count(explode("‐",$contract->number_contract))>1)
														@if(explode("‐",$contract->number_contract)[1] == $department->index_department)
															<option value='{{$department->index_department}}' selected>{{$department->index_department}} {{$department->name_department}}</option>
														@else
															<option value='{{$department->index_department}}'>{{$department->index_department}} {{$department->name_department}}</option>
														@endif
													@else
														<option value='{{$department->index_department}}'>{{$department->index_department}} {{$department->name_department}}</option>
													@endif
												@endforeach
											</select>
											@if($errors->has('index_dep'))
												<label class='msgError'>{{$errors->first('index_dep')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-1 border-right border-top border-bottom">
										<div class="form-group">
											<label for='year_contract'>Год</label>
											<input id='year_contract' class='change_contract_number form-control {{$errors->has("year_contract") ? print("inputError ") : print("")}}' name='year_contract' type='text' value='{{old("year_contract") ? old("year_contract") : $contract->year_contract}}' required />
											@if($errors->has('year_contract'))
												<label class='msgError'>{{$errors->first('year_contract')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-2 border-top border-bottom">
										<div class="form-group">
											<label for='executor_contract_reestr'>Исполнитель по Дог./Контр.</label>
											<select class='form-control' name='executor_contract_reestr' {{$is_disabled}}>
												<option></option>
												@if(old('executor_reestr'))
													@foreach($curators_sip as $in_curators)
														@if(old('executor_contract_reestr') == $in_curators->id)
															<option value='{{$in_curators->id}}' selected>{{$in_curators->FIO}}</option>
														@else
															<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
														@endif
													@endforeach
												@else
													@foreach($curators_sip as $in_curators)
														@if($reestr->executor_contract_reestr == $in_curators->id)
															<option value='{{$in_curators->id}}' selected>{{$in_curators->FIO}}</option>
														@else
															<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
														@endif
													@endforeach
												@endif
											</select>
										</div>
									</div>
									<div class="col-md-2 border-top border-bottom">
										<div class="form-group">
											<label for='executor_reestr'>Исполнитель ОУД</label>
											<select class='form-control' name='executor_reestr' {{$is_disabled}}>
												@if($is_disabled == '')
													<option></option>
												@endif
												@if($is_disabled == '')
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
												@else
													@foreach($curators as $in_curators)
														@if($reestr->executor_reestr == $in_curators->id)
															<option value='{{$in_curators->id}}' selected>{{$in_curators->FIO}}</option>
														@endif
													@endforeach
												@endif
											</select>
											@if($errors->has('executor_reestr'))
												<label class='msgError'>{{$errors->first('executor_reestr')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-2 border-left border-top border-bottom">
										<div class="form-group">
											<label for='date_save_contract_reestr' style='font-size: 12px;'>Дата сдачи Дог./Контр. на хранение</label>
											<input id='date_save_contract_reestr' class='datepicker form-control {{$errors->has("date_save_contract_reestr") ? print("inputError ") : print("")}}' name='date_save_contract_reestr' value='{{old("date_save_contract_reestr") ? old("date_save_contract_reestr") : $reestr->date_save_contract_reestr}}'/>
											@if($errors->has('date_save_contract_reestr'))
												<label class='msgError'>{{$errors->first('date_save_contract_reestr')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-2 border-top">
										<div class="form-group">
											<label for='place_save_contract_reestr'>Место хранения</label>
											<input id='place_save_contract_reestr' class='form-control {{$errors->has("place_save_contract_reestr") ? print("inputError ") : print("")}}' name='place_save_contract_reestr' value='{{old("place_save_contract_reestr") ? old("place_save_contract_reestr") : $reestr->place_save_contract_reestr}}'/>
											@if($errors->has('place_save_contract'))
												<label class='msgError'>{{$errors->first('place_save_contract')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-1 border-top">
										<div class="form-group">
											<label for='sel6' style='font-size: 12px;'>Тип документа</label>
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
									</div>
								</div>
								<div class='row'>
									<div class="col-md-9">
										<div class='row'>
											<div class="col-md-9">
												<div class='row'>
													<div class="col-md-4 border-top border-left">
														<div class="form-group">
															<label for='date_contract_on_first_reestr'>Дата Дог./Контр. на 1 л.</label>
															<input id='date_contract_on_first_reestr' class='form-control {{$errors->has("date_contract_on_first_reestr") ? print("inputError ") : print("")}}' name='date_contract_on_first_reestr' value='{{old("date_contract_on_first_reestr") ? old("date_contract_on_first_reestr") : $reestr->date_contract_on_first_reestr}}' {{$is_peo_disabled}} />
															@if($errors->has('date_contract_on_first_reestr'))
																<label class='msgError'>{{$errors->first('date_contract_on_first_reestr')}}</label>
															@endif
														</div>
													</div>
													<div class="col-md-4 border-top">
														<div class="form-group">
															<label for='date_signing_contract_reestr'>Дата подписания ФКП "НТИИМ"</label>
															<input id='date_signing_contract_reestr' class='datepicker form-control {{$errors->has("date_signing_contract_reestr") ? print("inputError ") : print("")}}' name='date_signing_contract_reestr' value='{{old("date_signing_contract_reestr") ? old("date_signing_contract_reestr") : $reestr->date_signing_contract_reestr}}' {{$is_disabled}}/>
															@if($errors->has('date_signing_contract_reestr'))
																<label class='msgError'>{{$errors->first('date_signing_contract_reestr')}}</label>
															@endif
														</div>
													</div>
													<div class="col-md-4 border-top">
														<div class="form-group">
															<label for='date_control_signing_contract_reestr' style='font-size: 12px;'>Контрольный срок подписания Дог./Контр.</label>
															<input id='date_control_signing_contract_reestr' class='datepicker form-control {{$errors->has("date_control_signing_contract_reestr") ? print("inputError ") : print("")}}' name='date_control_signing_contract_reestr' value='{{old("date_control_signing_contract_reestr") ? old("date_control_signing_contract_reestr") : $reestr->date_control_signing_contract_reestr}}' {{$is_disabled}}/>
															@if($errors->has('date_control_signing_contract_reestr'))
																<label class='msgError'>{{$errors->first('date_control_signing_contract_reestr')}}</label>
															@endif
														</div>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-4 border-left border-bottom">
														<div class="form-group">
															<label id='label_date_registration_project_reestr' for='date_registration_project_reestr'>Дата регистрации проекта</label>
															<!--@if(old('application_reestr'))
																<input id='application_reestr' class='form-check-input' style='float: right;' name='application_reestr' type="checkbox" checked {{$is_disabled}}/>
															@else
																@if($reestr->application_reestr)
																	<input id='application_reestr' class='form-check-input' style='float: right;' name='application_reestr' type="checkbox" checked {{$is_disabled}}/>
																@else
																	<input id='application_reestr' class='form-check-input' style='float: right;' name='application_reestr' type="checkbox" {{$is_disabled}}/>
																@endif
															@endif-->
															<!--<label for='application_reestr' style='float: right; margin-right: 5px;'>Заявка</label>-->
															<input id='date_registration_project_reestr' class='datepicker form-control {{$errors->has("date_registration_project_reestr") ? print("inputError ") : print("")}}' name='date_registration_project_reestr' value='{{old("date_registration_project_reestr") ? old("date_registration_project_reestr") : $reestr->date_registration_project_reestr}}' {{$is_disabled}}/>
															@if($errors->has('date_registration_project_reestr'))
																<label class='msgError'>{{$errors->first('date_registration_project_reestr')}}</label>
															@endif
														</div>
													</div>
													<div class="col-md-4 border-bottom">
														<div class="form-group">
															<label for='date_signing_contract_counterpartie_reestr'>Дата подписания Контрагентом</label>
															<input id='date_signing_contract_counterpartie_reestr' class='datepicker form-control {{$errors->has("date_signing_contract_counterpartie_reestr") ? print("inputError ") : print("")}}' name='date_signing_contract_counterpartie_reestr' value='{{old("date_signing_contract_counterpartie_reestr") ? old("date_signing_contract_counterpartie_reestr") : $reestr->date_signing_contract_counterpartie_reestr}}' {{$is_disabled}}/>
															@if($errors->has('date_signing_contract_counterpartie_reestr'))
																<label class='msgError'>{{$errors->first('date_signing_contract_counterpartie_reestr')}}</label>
															@endif
														</div>
													</div>
													<div class="col-md-4 border-bottom">
														<div class="form-group">
															<label for='date_entry_into_force_reestr'>Дата вступления Дог./Контр. в силу</label>
															<input id='date_entry_into_force_reestr' class='datepicker form-control {{$errors->has("date_entry_into_force_reestr") ? print("inputError ") : print("")}}' name='date_entry_into_force_reestr' value='{{old("date_entry_into_force_reestr") ? old("date_entry_into_force_reestr") : $reestr->date_entry_into_force_reestr}}' {{$is_disabled}}/>
															@if($errors->has('date_entry_into_force_reestr'))
																<label class='msgError'>{{$errors->first('date_entry_into_force_reestr')}}</label>
															@endif
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-3">
												<div class='row'>
													<div class="col-md-6 border-top">
														<div class="form-group">
															<label for='protocols_reestr'>Протоколы</label>
															<input id='protocols_reestr' class='form-control {{$errors->has("protocols_reestr") ? print("inputError ") : print("")}}' name='protocols_reestr' value='{{$big_date_protocol != null ? $big_date_protocol : ""}}' readonly />
														</div>
													</div>
													<div class="col-md-6 border-top border-right">
														<div class="form-group">
															<label for='add_agreements_reestr'>ДС</label>
															<input id='add_agreements_reestr' class='form-control {{$errors->has("add_agreements_reestr") ? print("inputError ") : print("")}}' name='add_agreements_reestr' value='{{$big_date_add_agreement != null ? $big_date_add_agreement : ""}}' readonly />
														</div>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-6 border-bottom">
														<div class="form-group">
															<label for='sel9'>Согл./Не согл.</label>
															<select id="sel9" class='form-control {{$errors->has("reconciliation_protocol_reestr") ? print("inputError ") : print("")}}' name='reconciliation_protocol_reestr' {{$is_disabled != '' ? 'disabled' : ''}}>
																<option value='0' {{$reestr->reconciliation_protocol_reestr == 0 ? 'selected' : ''}}></option>
																<option value='1' {{$reestr->reconciliation_protocol_reestr == 1 ? 'selected' : ''}}>Согласовано</option>
																<option value='2' {{$reestr->reconciliation_protocol_reestr == 2 ? 'selected' : ''}}>Не согласовано</option>
															</select>
														</div>
													</div>
													<div class="col-md-6 border-bottom border-right">
														<div class="form-group">
															<label for='sel10'>Согл./Не согл.</label>
															<select id="sel10" class='form-control {{$errors->has("reconciliation_agreement_reestr") ? print("inputError ") : print("")}}' name='reconciliation_agreement_reestr' {{$is_disabled != '' ? 'disabled' : ''}}>
																<option value='0' {{$reestr->reconciliation_agreement_reestr == 0 ? 'selected' : ''}}></option>
																<option value='1' {{$reestr->reconciliation_agreement_reestr == 1 ? 'selected' : ''}}>Согласовано</option>
																<option value='2' {{$reestr->reconciliation_agreement_reestr == 2 ? 'selected' : ''}}>Не согласовано</option>
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class='row'>
											<div class="col-md-3">
												<div class="form-group">
													<label class='form-check-label' for='break'>ОТКАЗ</label>
													@if(old('renouncement_contract'))
														<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" checked {{$is_disabled}}/>
													@else
														@if($contract->renouncement_contract == 1)
															<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" checked {{$is_disabled}}/>
														@else
															<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" {{$is_disabled}}/>
														@endif
													@endif
												</div>
												<div class="form-group">
													<label class='form-check-label' for='archive_contract'>АРХИВ</label>
													@if(old('archive_contract'))
														<input id='archive_contract' class='form-check-input' name='archive_contract' type="checkbox" checked {{$is_disabled}}/>
													@else
														@if($contract->archive_contract == 1)
															<input id='archive_contract' class='form-check-input' name='archive_contract' type="checkbox" checked {{$is_disabled}}/>
														@else
															<input id='archive_contract' class='form-check-input' name='archive_contract' type="checkbox" {{$is_disabled}}/>
														@endif
													@endif
												</div>
											</div>
											<div class="col-md-9 border-right">
												<div class="form-group">
													<label for='document_success_renouncement_reestr'>Документ, подтверждающий отказ</label>
													<input id='document_success_renouncement_reestr' class='form-control {{$errors->has("document_success_renouncement_reestr") ? print("inputError ") : print("")}}' name='document_success_renouncement_reestr' value='{{old("document_success_renouncement_reestr") ? old("document_success_renouncement_reestr") : $contract->document_success_renouncement_reestr}}' {{$is_disabled}}/>
													@if($errors->has('document_success_renouncement_reestr'))
														<label class='msgError'>{{$errors->first('document_success_renouncement_reestr')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12 border-bottom border-right">
												<div class="form-group">
													<label for='number_aftair_renouncement_reestr'>№ дела</label>
													<input id='number_aftair_renouncement_reestr' class='form-control {{$errors->has("number_aftair_renouncement_reestr") ? print("inputError ") : print("")}}' name='number_aftair_renouncement_reestr' value='{{old("number_aftair_renouncement_reestr") ? old("number_aftair_renouncement_reestr") : $contract->number_aftair_renouncement_reestr}}' {{$is_disabled}}/>
													@if($errors->has('number_aftair_renouncement_reestr'))
														<label class='msgError'>{{$errors->first('number_aftair_renouncement_reestr')}}</label>
													@endif
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-2 border-bottom border-left border-top">
										<div class="form-group">
											<label for="sel3">Вид договора</span></label>
											<select id="sel3" class='form-control {{$errors->has("id_view_contract") ? print("inputError ") : print("")}}' name='id_view_contract' {{$is_disabled}}>
												@if($is_disabled == '')
													<option></option>
												@endif
												@if($viewContracts)
													@foreach($viewContracts as $viewContract)
														@if($is_disabled == '')
															@if(old('id_view_contract'))
																@if(old('id_view_contract') == $viewContract->id)
																	<option value='{{$viewContract->id}}' selected>{{ $viewContract->name_view_contract }}</option>
																@else
																	<option value='{{$viewContract->id}}'>{{ $viewContract->name_view_contract }}</option>
																@endif
															@else
																@if($reestr->id_view_contract == $viewContract->id)
																	<option value='{{$viewContract->id}}' selected>{{ $viewContract->name_view_contract }}</option>
																@else
																	<option value='{{$viewContract->id}}'>{{ $viewContract->name_view_contract }}</option>
																@endif
															@endif
														@else
															@if($reestr->id_view_contract == $viewContract->id)
																<option value='{{$viewContract->id}}' selected>{{ $viewContract->name_view_contract }}</option>
															@endif
														@endif
													@endforeach
												@endif
											</select>
											@if($errors->has('id_view_contract'))
												<label class='msgError'>{{$errors->first('id_view_contract')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-2 border-top border-bottom border-left">
										<div class="form-group">
											<label for="sel5">Дата регистрации заявки</span></label>
											<input id='date_registration_application_reestr' class='datepicker form-control {{$errors->has("date_registration_application_reestr") ? print("inputError ") : print("")}}' name='date_registration_application_reestr' value='{{old("date_registration_application_reestr") ? old("date_registration_application_reestr") : $reestr->date_registration_application_reestr}}' {{$is_disabled}}/>
											@if($errors->has('date_registration_application_reestr'))
												<label class='msgError'>{{$errors->first('date_registration_application_reestr')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-2 border-top border-bottom">
										<div class="form-group">
											<label for='app_outgoing_number_reestr'>Заявка исх. №</label>
											@if($reestr->app_outgoing_number_reestr == null)
												<input id='app_outgoing_number_reestr' class='form-control {{$errors->has("app_outgoing_number_reestr") ? print("inputError ") : print("")}}' name='app_outgoing_number_reestr' value='{{old("app_outgoing_number_reestr") ? old("app_outgoing_number_reestr") : $reestr->app_outgoing_number_reestr}}' />
											@else
												<input id='app_outgoing_number_reestr' class='form-control {{$errors->has("app_outgoing_number_reestr") ? print("inputError ") : print("")}}' name='app_outgoing_number_reestr' value='{{$reestr->app_outgoing_number_reestr}}' disabled />
											@endif
											@if($errors->has('app_outgoing_number_reestr'))
												<label class='msgError'>{{$errors->first('app_outgoing_number_reestr')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-2 border-top border-bottom border-right">
										<div class="form-group">
											<label for='app_incoming_number_reestr'>Вх. №</label>
											@if($reestr->app_incoming_number_reestr == null)
												<input id='app_incoming_number_reestr' class='form-control {{$errors->has("app_incoming_number_reestr") ? print("inputError ") : print("")}}' name='app_incoming_number_reestr' value='{{old("app_incoming_number_reestr") ? old("app_incoming_number_reestr") : $reestr->app_incoming_number_reestr}}' />
											@else
												<input id='app_incoming_number_reestr' class='form-control {{$errors->has("app_incoming_number_reestr") ? print("inputError ") : print("")}}' name='app_incoming_number_reestr' value='{{$reestr->app_incoming_number_reestr}}' disabled />
											@endif
											@if($errors->has('app_incoming_number_reestr'))
												<label class='msgError'>{{$errors->first('app_incoming_number_reestr')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-2 border-top border-bottom border-left">
										<div class="form-group">
											<label for='result_second_department_date_reestr'>Заключение отдела №2 Дата</label>
											<input id='result_second_department_date_reestr' class='datepicker form-control {{$errors->has("result_second_department_date_reestr") ? print("inputError ") : print("")}}' name='result_second_department_date_reestr' value='{{old("result_second_department_date_reestr") ? old("result_second_department_date_reestr") : $reestr->result_second_department_date_reestr}}' {{$is_disabled}}/>
											@if($errors->has('result_second_department_date_reestr'))
												<label class='msgError'>{{$errors->first('result_second_department_date_reestr')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-2 border-top border-bottom border-right">
										<div class="form-group">
											<label for='result_second_department_number_reestr'>№</label>
											<input id='result_second_department_number_reestr' class='form-control {{$errors->has("result_second_department_number_reestr") ? print("inputError ") : print("")}}' name='result_second_department_number_reestr' value='{{old("result_second_department_number_reestr") ? old("result_second_department_number_reestr") : $reestr->result_second_department_number_reestr}}' {{$is_disabled}}/>
											@if($errors->has('result_second_department_number_reestr'))
												<label class='msgError'>{{$errors->first('result_second_department_number_reestr')}}</label>
											@endif
										</div>
									</div>
								</div>
								<div class='row'>
									<input id='is_new_reestr' class='form-check-input' name='is_new_reestr' type="checkbox" checked style='display: none;'/>
									<div class="col-md-5">
										<div class='row'>
											<div class="col-md-6 border-top border-bottom border-right border-left">
												<div class="form-group">
													<label for="sel5">Отбор поставщика</span></label>
													<select id="sel5" class='form-control {{$errors->has("selection_supplier_reestr") ? print("inputError ") : print("")}}' name='selection_supplier_reestr' {{$is_disabled != '' ? 'disabled' : ''}}>
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
											<div class="col-md-3 border-top border-bottom border-left">
												<div class='row'>
													<div class="col-md-12">
														@if(old('marketing_reestr'))
															<input id='marketing_reestr' class='form-check-input' name='marketing_reestr' type="checkbox" checked />
														@else
															@if($reestr->marketing_reestr)
																<input id='marketing_reestr' class='form-check-input' name='marketing_reestr' type="checkbox" checked />
															@else
																<input id='marketing_reestr' class='form-check-input' name='marketing_reestr' type="checkbox" />
															@endif
														@endif
														<label for='marketing_reestr'>Сбыт</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														@if(old('marketing_goz_reestr'))
															<input id='marketing_goz_reestr' class='form-check-input' name='marketing_goz_reestr' type="checkbox" checked />
														@else
															@if($reestr->marketing_goz_reestr)
																<input id='marketing_goz_reestr' class='form-check-input' name='marketing_goz_reestr' type="checkbox" checked />
															@else
																<input id='marketing_goz_reestr' class='form-check-input' name='marketing_goz_reestr' type="checkbox" />
															@endif
														@endif
														<label for='marketing_goz_reestr'>ГОЗ</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														@if(old('participation_reestr'))
															<input id='participation_reestr' class='form-check-input' name='participation_reestr' type="checkbox" checked />
														@else
															@if($reestr->participation_reestr)
																<input id='participation_reestr' class='form-check-input' name='participation_reestr' type="checkbox" checked />
															@else
																<input id='participation_reestr' class='form-check-input' name='participation_reestr' type="checkbox" />
															@endif
														@endif
														<label for='participation_reestr'>Участие</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														@if(old('marketing_fz_223_reestr'))
															<input id='marketing_fz_223_reestr' class='form-check-input' name='marketing_fz_223_reestr' type="checkbox" checked />
														@else
															@if($reestr->marketing_fz_223_reestr)
																<input id='marketing_fz_223_reestr' class='form-check-input' name='marketing_fz_223_reestr' type="checkbox" checked />
															@else
																<input id='marketing_fz_223_reestr' class='form-check-input' name='marketing_fz_223_reestr' type="checkbox" />
															@endif
														@endif
														<label for='marketing_fz_223_reestr'>223-ФЗ</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														@if(old('marketing_fz_44_reestr'))
															<input id='marketing_fz_44_reestr' class='form-check-input' name='marketing_fz_44_reestr' type="checkbox" checked />
														@else
															@if($reestr->marketing_fz_44_reestr)
																<input id='marketing_fz_44_reestr' class='form-check-input' name='marketing_fz_44_reestr' type="checkbox" checked />
															@else
																<input id='marketing_fz_44_reestr' class='form-check-input' name='marketing_fz_44_reestr' type="checkbox" />
															@endif
														@endif
														<label for='marketing_fz_44_reestr'>44-ФЗ</label>
													</div>
												</div>
											</div>
											<div class="col-md-5 border-top border-bottom">
												<div class='row'>
													<div class="col-md-12">
														@if(old('procurement_reestr'))
															<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" checked />
														@else
															@if($reestr->procurement_reestr)
																<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" checked />
															@else
																<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" />
															@endif
														@endif
														<label for='procurement_reestr'>Закуп</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														@if(old('single_provider_reestr'))
															<input id='single_provider_reestr' class='form-check-input' name='single_provider_reestr' type="checkbox" checked />
														@else
															@if($reestr->single_provider_reestr)
																<input id='single_provider_reestr' class='form-check-input' name='single_provider_reestr' type="checkbox" checked />
															@else
																<input id='single_provider_reestr' class='form-check-input' name='single_provider_reestr' type="checkbox" />
															@endif
														@endif
														<label for='single_provider_reestr'>Единственный поставщик</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														@if(old('own_funds_reestr'))
															<input id='own_funds_reestr' class='form-check-input' name='own_funds_reestr' type="checkbox" checked />
														@else
															@if($reestr->own_funds_reestr)
																<input id='own_funds_reestr' class='form-check-input' name='own_funds_reestr' type="checkbox" checked />
															@else
																<input id='own_funds_reestr' class='form-check-input' name='own_funds_reestr' type="checkbox" />
															@endif
														@endif
														<label for='own_funds_reestr'>Собственные средства</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														@if(old('investments_reestr'))
															<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" checked />
														@else
															@if($reestr->investments_reestr)
																<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" checked />
															@else
																<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" />
															@endif
														@endif
														<label for='investments_reestr'>Инвестиции</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														@if(old('purchase_reestr'))
															<input id='purchase_reestr' class='form-check-input' name='purchase_reestr' type="checkbox" checked />
														@else
															@if($reestr->purchase_reestr)
																<input id='purchase_reestr' class='form-check-input' name='purchase_reestr' type="checkbox" checked />
															@else
																<input id='purchase_reestr' class='form-check-input' name='purchase_reestr' type="checkbox" />
															@endif
														@endif
														<label for='purchase_reestr'>Закупка у СМСП</label>
													</div>
												</div>
											</div>
											<div class="col-md-4 border-top border-bottom border-right" style='padding-bottom: 33px;'>
												<!--<div class='row'>
													<div class="col-md-12">
														@if(old('procurement_fz_223_reestr'))
															<input id='procurement_fz_223_reestr' class='form-check-input' name='procurement_fz_223_reestr' type="checkbox" checked />
														@else
															@if($reestr->procurement_fz_223_reestr)
																<input id='procurement_fz_223_reestr' class='form-check-input' name='procurement_fz_223_reestr' type="checkbox" checked />
															@else
																<input id='procurement_fz_223_reestr' class='form-check-input' name='procurement_fz_223_reestr' type="checkbox" />
															@endif
														@endif
														<label for='procurement_fz_223_reestr'>223-ФЗ</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														@if(old('procurement_fz_44_reestr'))
															<input id='procurement_fz_44_reestr' class='form-check-input' name='procurement_fz_44_reestr' type="checkbox" checked />
														@else
															@if($reestr->procurement_fz_44_reestr)
																<input id='procurement_fz_44_reestr' class='form-check-input' name='procurement_fz_44_reestr' type="checkbox" checked />
															@else
																<input id='procurement_fz_44_reestr' class='form-check-input' name='procurement_fz_44_reestr' type="checkbox" />
															@endif
														@endif
														<label for='procurement_fz_44_reestr'>44-ФЗ</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														@if(old('procurement_goz_reestr'))
															<input id='procurement_goz_reestr' class='form-check-input' name='procurement_goz_reestr' type="checkbox" checked />
														@else
															@if($reestr->procurement_goz_reestr)
																<input id='procurement_goz_reestr' class='form-check-input' name='procurement_goz_reestr' type="checkbox" checked />
															@else
																<input id='procurement_goz_reestr' class='form-check-input' name='procurement_goz_reestr' type="checkbox" />
															@endif
														@endif
														<label for='procurement_goz_reestr'>ГОЗ</label>
													</div>
												</div>-->
												<div class='row'>
													<div class="col-md-12">
														@if(old('export_reestr'))
															<input id='export_reestr' class='form-check-input' name='export_reestr' type="checkbox" checked />
														@else
															@if($reestr->export_reestr)
																<input id='export_reestr' class='form-check-input' name='export_reestr' type="checkbox" checked />
															@else
																<input id='export_reestr' class='form-check-input' name='export_reestr' type="checkbox" />
															@endif
														@endif
														<label for='export_reestr'>Экспорт</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														@if(old('interfactory_reestr'))
															<input id='interfactory_reestr' class='form-check-input' name='interfactory_reestr' type="checkbox" checked />
														@else
															@if($reestr->interfactory_reestr)
																<input id='interfactory_reestr' class='form-check-input' name='interfactory_reestr' type="checkbox" checked />
															@else
																<input id='interfactory_reestr' class='form-check-input' name='interfactory_reestr' type="checkbox" />
															@endif
														@endif
														<label for='interfactory_reestr'>Межзаводские</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														@if(old('other_reestr'))
															<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" checked />
														@else
															@if($reestr->other_reestr)
																<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" checked />
															@else
																<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" />
															@endif
														@endif
														<label for='other_reestr'>Иное</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														@if(old('mob_reestr'))
															<input id='mob_reestr' class='form-check-input' name='mob_reestr' type="checkbox" checked />
														@else
															@if($reestr->mob_reestr)
																<input id='mob_reestr' class='form-check-input' name='mob_reestr' type="checkbox" checked />
															@else
																<input id='mob_reestr' class='form-check-input' name='mob_reestr' type="checkbox" />
															@endif
														@endif
														<label for='mob_reestr'>МОБ</label>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-7 border-all">
										<div class="row" style='text-align: center;'>
											<div class="col-md-12">
												<label>Согласование крупной сделки</label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-2">
												<label for='number_inquiry_reestr'>Запрос №</label>
											</div>
											<div class="col-md-3">
												<input id='number_inquiry_reestr' class='form-control {{$errors->has("number_inquiry_reestr") ? print("inputError ") : print("")}}' name='number_inquiry_reestr' value='{{old("number_inquiry_reestr") ? old("number_inquiry_reestr") : $reestr->number_inquiry_reestr}}'/>
												@if($errors->has('number_inquiry_reestr'))
													<label class='msgError'>{{$errors->first('number_inquiry_reestr')}}</label>
												@endif
											</div>
											<div class="col-md-1">
												<label for='date_inquiry_reestr'>от</label>
											</div>
											<div class="col-md-2">
												<input id='date_inquiry_reestr' class='datepicker form-control {{$errors->has("date_inquiry_reestr") ? print("inputError ") : print("")}}' name='date_inquiry_reestr' value='{{old("date_inquiry_reestr") ? old("date_inquiry_reestr") : $reestr->date_inquiry_reestr}}'/>
												@if($errors->has('date_inquiry_reestr'))
													<label class='msgError'>{{$errors->first('date_inquiry_reestr')}}</label>
												@endif
											</div>
											<div class="col-md-2">
												<label for='days_reconciliation_reestr'>Срок действия согласования крупной сделки</label>
											</div>
											<div class="col-md-2">
												<input id='days_reconciliation_reestr' class='form-control {{$errors->has("days_reconciliation_reestr") ? print("inputError ") : print("")}}' name='days_reconciliation_reestr' value='{{old("days_reconciliation_reestr") ? old("days_reconciliation_reestr") : $reestr->days_reconciliation_reestr}}'/>
												@if($errors->has('days_reconciliation_reestr'))
													<label class='msgError'>{{$errors->first('days_reconciliation_reestr')}}</label>
												@endif
											</div>
										</div>
										<div class="row">
											<div class="col-md-2">
												<label for='number_answer_reestr'>Ответ №</label>
											</div>
											<div class="col-md-3">
												<input id='number_answer_reestr' class='form-control {{$errors->has("number_answer_reestr") ? print("inputError ") : print("")}}' name='number_answer_reestr' value='{{old("number_answer_reestr") ? old("number_answer_reestr") : $reestr->number_answer_reestr}}'/>
												@if($errors->has('number_answer_reestr'))
													<label class='msgError'>{{$errors->first('number_answer_reestr')}}</label>
												@endif
											</div>
											<div class="col-md-1">
												<label for='date_answer_reestr'>от</label>
											</div>
											<div class="col-md-2">
												<input id='date_answer_reestr' class='datepicker form-control {{$errors->has("date_answer_reestr") ? print("inputError ") : print("")}}' name='date_answer_reestr' value='{{old("date_answer_reestr") ? old("date_answer_reestr") : $reestr->date_answer_reestr}}'/>
												@if($errors->has('date_answer_reestr'))
													<label class='msgError'>{{$errors->first('date_answer_reestr')}}</label>
												@endif
											</div>
											<div class="col-md-2">
												<label for='count_mounth_reestr'>Количество месяцев</label>
											</div>
											<div class="col-md-2">
												<input id='count_mounth_reestr' class='form-control {{$errors->has("count_mounth_reestr") ? print("inputError ") : print("")}}' name='count_mounth_reestr' value='{{old("count_mounth_reestr") ? old("count_mounth_reestr") : $reestr->count_mounth_reestr}}'/>
												@if($errors->has('count_mounth_reestr'))
													<label class='msgError'>{{$errors->first('count_mounth_reestr')}}</label>
												@endif
											</div>
										</div>
										<div class="row border-top">
											<div class="col-md-2">
												<label>Сроки согласования проекта договора исполнителей</label>
											</div>
											<div class="col-md-3">
												<label for='begin_date_reconciliation_reestr'>Начало согласования (дата)</label>
												<input id='begin_date_reconciliation_reestr' class='datepicker form-control {{$errors->has("begin_date_reconciliation_reestr") ? print("inputError ") : print("")}}' name='begin_date_reconciliation_reestr' value='{{old("begin_date_reconciliation_reestr") ? old("begin_date_reconciliation_reestr") : $reestr->begin_date_reconciliation_reestr}}'/>
												@if($errors->has('begin_date_reconciliation_reestr'))
													<label class='msgError'>{{$errors->first('begin_date_reconciliation_reestr')}}</label>
												@endif
											</div>
											<div class="col-md-3">
												<label for='end_date_reconciliation_reestr'>Окончание согласования (дата)</label>
												<input id='end_date_reconciliation_reestr' class='datepicker form-control {{$errors->has("end_date_reconciliation_reestr") ? print("inputError ") : print("")}}' name='end_date_reconciliation_reestr' value='{{old("end_date_reconciliation_reestr") ? old("end_date_reconciliation_reestr") : $reestr->end_date_reconciliation_reestr}}'/>
												@if($errors->has('end_date_reconciliation_reestr'))
													<label class='msgError'>{{$errors->first('end_date_reconciliation_reestr')}}</label>
												@endif
											</div>
											<div class="col-md-4">
												<label for='count_days_reconciliation_reestr'>Общее количество дней согласования</label>
												<input id='count_days_reconciliation_reestr' class='form-control {{$errors->has("count_days_reconciliation_reestr") ? print("inputError ") : print("")}}' name='count_days_reconciliation_reestr' value='{{old("count_days_reconciliation_reestr") ? old("count_days_reconciliation_reestr") : $reestr->count_days_reconciliation_reestr}}'/>
												@if($errors->has('count_days_reconciliation_reestr'))
													<label class='msgError'>{{$errors->first('count_days_reconciliation_reestr')}}</label>
												@endif
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for='miniCounterpartie'>Полное наименование контрагента</label>
											<input id='miniCounterpartie' class='form-control {{$errors->has("number_contract") ? print("inputError ") : print("")}}' type='text' value='{{ $contract->full_name_counterpartie_contract }}' readonly />
										</div>
									</div>
									<div class="col-md-3">
										<label>ИНН</label>
										<input id='inn_counterpartie' class='form-control' value='{{$contract->inn_counterpartie_contract}}' readonly />
									</div>
									@if($contract->id_counterpartie_contract)
										<div class='col-md-3'>
											<button class='btn btn-primary btn-href' type='button' style='float: right; margin-top: 24px;' href="{{route('counterpartie.edit', $contract->id_counterpartie_contract)}}">Информация о контрагенте</button>
										</div>
									@endif
								</div>
								<div class="row border-all">
									<div class="col-md-12">
										<div class='row'>
											<div class="col-md-6">
												<div class="form-group">
													<label for='number_counterpartie_contract_reestr'>№ дог. контрагента</label>
													<input id='number_counterpartie_contract_reestr' class='form-control {{$errors->has("number_counterpartie_contract_reestr") ? print("inputError ") : print("")}}' name='number_counterpartie_contract_reestr' value='{{old("number_counterpartie_contract_reestr") ? old("number_counterpartie_contract_reestr") : $reestr->number_counterpartie_contract_reestr}}' {{$is_peo_disabled}} />
													@if($errors->has('number_counterpartie_contract_reestr'))
														<label class='msgError'>{{$errors->first('number_counterpartie_contract_reestr')}}</label>
													@endif
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label for='igk_reestr'>ИГК</label>
													<input id='igk_reestr' class='form-control {{$errors->has("igk_reestr") ? print("inputError ") : print("")}}' name='igk_reestr' value='{{ old("igk_reestr") ? old("igk_reestr") : $reestr->igk_reestr }}' {{$is_peo_disabled}} />
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label for='ikz_reestr'>ИКЗ</label>
													<input id='ikz_reestr' class='form-control {{$errors->has("ikz_reestr") ? print("inputError ") : print("")}}' name='ikz_reestr' value='{{ old("ikz_reestr") ? old("ikz_reestr") : $reestr->ikz_reestr }}' {{$is_disabled}}/>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3 border-all">
										<div class="row">
											<div class="col-md-12">
												<div class='form-group'>
													<label for='itemContract'>Предмет договора/контракта</label>
													<textarea id='itemContract' class='form-control {{$errors->has("item_contract") ? print("inputError ") : print("")}}' name='item_contract' type="text" style="width: 100%;" rows='4' {{$is_disabled}}>{{ old('item_contract') ? old('item_contract') : $contract->item_contract }}</textarea>
													@if($errors->has('item_contract'))
														<label class='msgError'>{{$errors->first('item_contract')}}</label>
													@endif
												</div>
											</div>
											<div class="col-md-12">
												<label for='okpd_2_reestr'>ОКПД 2</label>
												<input id='okpd_2_reestr' class='form-control {{$errors->has("okpd_2_reestr") ? print("inputError ") : print("")}}' name='okpd_2_reestr' value='{{ old("okpd_2_reestr") ? old("okpd_2_reestr") : $reestr->okpd_2_reestr }}' {{$is_disabled}}/>
											</div>
											<div class="col-md-12">
												<div class='form-group'>
													<label for='nameWork'>Цель заключения Дог./Контр.</label>
													<textarea id='nameWork' class='form-control {{$errors->has("name_work_contract") ? print("inputError ") : print("")}}' name='name_work_contract' type="text" style="width: 100%;" rows='4' {{$is_peo_disabled}}>{{ old('name_work_contract') ? old('name_work_contract') : $contract->name_work_contract }}</textarea>
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
														<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%; overflow-x: scroll; white-space: nowrap;" rows='5' cols='2' readonly>@foreach($states as $state){{$state->date_state . '   ' . $state->name_state . '   ' . $state->surname . ' ' . mb_substr($state->name, 0, 1) . '.' . mb_substr($state->patronymic, 0, 1) . '.&#13;&#10;'}}@endforeach</textarea>
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
										<div class="row">
											<div class="col-md-4">
											</div>
											<div class="col-md-1">
												<div class='form-group'>
													@if(isset($prev_contract))
														<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $prev_contract)}}" title='Назад' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
													@else
														<button class='btn btn-primary' title='Назад' disabled type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
													@endif
												</div>
											</div>
											<div class="col-md-1">
											</div>
											<div class="col-md-1">
												<div class='form-group'>
													@if(isset($next_contract))
														<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $next_contract)}}" title='Вперед' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
													@else
														<button class='btn btn-primary' title='Вперед' disabled type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
													@endif
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-9 border-all">
										<div class="row">
											<div class="col-md-12">
												<label>Гарантия банка</label>
											</div>
											<div class="col-md-2">
												<div class='row'>
													<div class="col-md-2">
														<label for='date_bank_reestr'>До</label>
													</div>
													<div class="col-md-10">
														<input id='date_bank_reestr' class='form-control {{$errors->has("date_bank_reestr") ? print("inputError ") : print("")}}' name='date_bank_reestr' value='{{ old("date_bank_reestr") ? old("date_bank_reestr") : $reestr->date_bank_reestr }}' {{$is_disabled}}/>
													</div>
												</div>
											</div>
											<div class="col-md-5">
												<div class='row'>
													<div class="col-md-1">
														<label for='amount_bank_reestr'>Сумма</label>
													</div>
													<div class="col-md-5">
														<input id='amount_bank_reestr' class='form-control check-number {{$errors->has("amount_bank_reestr") ? print("inputError ") : print("")}}' name='amount_bank_reestr' value='{{ old("amount_bank_reestr") ? old("amount_bank_reestr") : $reestr->amount_bank_reestr }}' {{$is_disabled}}/>
													</div>
													<div class="col-md-1">
														<label for='bank_reestr'>Банк</label>
													</div>
													<div class="col-md-5">
														<input id='bank_reestr' class='form-control {{$errors->has("bank_reestr") ? print("inputError ") : print("")}}' name='bank_reestr' value='{{ old("bank_reestr") ? old("bank_reestr") : $reestr->bank_reestr }}' {{$is_disabled}}/>
													</div>
												</div>
											</div>
											<div class='col-md-5'>
												<button class='btn btn-primary' data-toggle="modal" data-target="#scan" type='button' style='float: right; width: 184px;'>Сканы</button>
											</div>
										</div>
										<div class="row border-top">
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
														<input id='date_b_contract_reestr' class='form-control {{$errors->has("date_b_contract_reestr") ? print("inputError ") : print("")}}' name='date_b_contract_reestr' value='{{ old("date_b_contract_reestr") ? old("date_b_contract_reestr") : $reestr->date_b_contract_reestr }}' {{$is_disabled}}/>
													</div>
													<div class="col-md-1">
														<label for='date_e_contract_reestr'>по</label>
													</div>
													<div class="col-md-5">
														<input id='date_e_contract_reestr' class='form-control {{$errors->has("date_e_contract_reestr") ? print("inputError ") : print("")}}' name='date_e_contract_reestr' value='{{ old("date_e_contract_reestr") ? old("date_e_contract_reestr") : $reestr->date_e_contract_reestr }}' {{$is_disabled}}/>
													</div>
												</div>
											</div>
											<div class='col-md-5'>
												<button class='btn btn-primary btn-href' style='float: right; width: 184px;' type='button' href="{{route('department.reestr.show_obligation', $contract->id)}}" {{$is_disabled}}>Исполнение Дог./Контр.</button>
											</div>
										</div>
										<div class='row border-bottom'>
											<div class="col-md-10">
												<div class="form-group">
													<input id='date_contract_reestr' class='form-control {{$errors->has("date_contract_reestr") ? print("inputError ") : print("")}}' name='date_contract_reestr' value='{{ old("date_contract_reestr") ? old("date_contract_reestr") : $reestr->date_contract_reestr }}' {{$is_disabled}}/>
												</div>
											</div>
											<div class='col-md-2'>
												<button class='btn btn-primary btn-href' style='float: right; width: 184px;' type='button' href="{{route('department.reestr.show_protocols', $contract->id)}}">ПР/ПСР/ПУР</button>
											</div>
										</div>
										<div class="row border-bottom">
											<div class="col-md-10">
												<div class="form-group">
													<label for='date_maturity_reestr'>Срок исполнения обязательств</label>
													<input id='date_maturity_reestr' class='form-control {{$errors->has("date_maturity_reestr") ? print("inputError ") : print("")}}' name='date_maturity_reestr' value='{{ old("date_maturity_reestr") ? old("date_maturity_reestr") : $reestr->date_maturity_reestr }}' {{$is_peo_disabled}} />
												</div>
											</div>
											<div class="col-md-2">
												<button class='btn btn-primary btn-href' style='float: right; width: 184px;' type='button' href="{{route('department.reestr.show_additional_agreements', $contract->id)}}">ДС</button>
											</div>
											<div class="col-md-2">
												<button class='btn btn-primary btn-href' style='float: right; width: 184px; margin-top: 5px;' type='button' href="{{route('department.reestr.show_amount_invoice', $contract->id)}}">Сумма по счетам</button>
											</div>
										</div>
										<div class="row">
											<div class="col-md-10">
												<div class='row'>
													<div class="col-md-1">
														<label for='amount_reestr'>Сумма</label>
													</div>
													<div class="col-md-2">
														<input id='amount_reestr' class='form-control check-number {{$errors->has("amount_reestr") ? print("inputError ") : print("")}}' name='amount_reestr' value='{{old("amount_reestr") ? old("amount_reestr") : $reestr->amount_reestr}}' {{$is_disabled}}/>
													</div>
													<div class="col-md-2">
														<select id="sel8" class='form-control {{$errors->has("unit_reestr") ? print("inputError ") : print("")}}' name='unit_reestr' {{$is_disabled != '' ? 'disabled' : ''}}>
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
															<input id='VAT' class='form-check-input' name='vat_reestr' type="checkbox" checked {{$is_disabled != '' ? 'disabled' : ''}}/>
														@else
															@if($reestr->vat_reestr)
																<input id='VAT' class='form-check-input' name='vat_reestr' type="checkbox" checked {{$is_disabled != '' ? 'disabled' : ''}}/>
															@else
																<input id='VAT' class='form-check-input' name='vat_reestr' type="checkbox" {{$is_disabled != '' ? 'disabled' : ''}}/>
															@endif
														@endif
													</div>
													<div class="col-md-3">
														<label for='approximate_amount_reestr'>Ориентировочная</label>
														@if(old('approximate_amount_reestr'))
															<input id='approximate_amount_reestr' class='form-check-input' name='approximate_amount_reestr' type="checkbox" checked {{$is_disabled != '' ? 'disabled' : ''}}/>
														@else
															@if($reestr->approximate_amount_reestr)
																<input id='approximate_amount_reestr' class='form-check-input' name='approximate_amount_reestr' type="checkbox" checked {{$is_disabled != '' ? 'disabled' : ''}}/>
															@else
																<input id='approximate_amount_reestr' class='form-check-input' name='approximate_amount_reestr' type="checkbox" {{$is_disabled != '' ? 'disabled' : ''}}/>
															@endif
														@endif
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														<input class='form-control {{$errors->has("amount_comment_reestr") ? print("inputError ") : print("")}}' name='amount_comment_reestr' value='{{old("amount_comment_reestr") ? old("amount_comment_reestr") : $reestr->amount_comment_reestr}}' {{$is_disabled}}/>
													</div>
												</div>
											</div>
											<div class="col-md-2">
												<button class='btn btn-primary' style='float: right; width: 184px; margin-top: 5px;' data-toggle="modal" data-target="#invoice" type='button'>Взаиморасчеты</button>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-6">
												<div class='row'>
													<div class="col-md-2">
														<label for='nmcd_reestr'>НМЦД/НМЦК</label>
													</div>
													<div class="col-md-3">
														<input id='nmcd_reestr' class='form-control check-number {{$errors->has("nmcd_reestr") ? print("inputError ") : print("")}}' name='nmcd_reestr' value='{{old("nmcd_reestr") ? old("nmcd_reestr") : $reestr->nmcd_reestr}}' {{$is_disabled}}/>
													</div>
													<div class="col-md-3">
														<select class='form-control {{$errors->has("nmcd_unit_reestr") ? print("inputError ") : print("")}}' name='nmcd_unit_reestr' {{$is_disabled != '' ? 'disabled' : ''}}>
															<option></option>
															@foreach($units as $unit)
																@if(old('nmcd_unit_reestr'))
																	<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
																@else
																	@if($reestr->nmcd_unit_reestr == $unit->id)
																		<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
																	@else
																		<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
																	@endif
																@endif
															@endforeach
														</select>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class='row'>
													<div class="col-md-2">
														<label for='economy_reestr'>Экономия</label>
													</div>
													<div class="col-md-3">
														<input id='economy_reestr' class='form-control check-number {{$errors->has("economy_reestr") ? print("inputError ") : print("")}}' name='economy_reestr' value='{{old("economy_reestr") ? old("economy_reestr") : $reestr->economy_reestr}}' {{$is_disabled}}/>
													</div>
													<div class="col-md-3">
														<select class='form-control {{$errors->has("economy_unit_reestr") ? print("inputError ") : print("")}}' name='economy_unit_reestr' {{$is_disabled != '' ? 'disabled' : ''}}>
															<option></option>
															@foreach($units as $unit)
																@if(old('economy_unit_reestr'))
																	<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
																@else
																	@if($reestr->economy_unit_reestr == $unit->id)
																		<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
																	@else
																		<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
																	@endif
																@endif
															@endforeach
														</select>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-10">
												<label>Порядок оплаты</label>
												<div class='row'>
													<div class="col-md-6">
														<label>Аванс</label>
														<textarea id='prepayment_order_reestr' class='form-control' type="text" style="width: 100%;" rows='2' name='prepayment_order_reestr' {{$is_disabled}}>{{old("prepayment_order_reestr") ? old("prepayment_order_reestr") : $reestr->prepayment_order_reestr}}</textarea>
													</div>
													<div class="col-md-6">
														<label>Окончат. расчет</label>
														<textarea id='score_order_reestr' class='form-control' type="text" style="width: 100%;" rows='2' name='score_order_reestr' {{$is_disabled}}>{{old("score_order_reestr") ? old("score_order_reestr") : $reestr->score_order_reestr}}</textarea>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														<label>Иное</label>
														<textarea id='payment_order_reestr' class='form-control' type="text" style="width: 100%;" rows='2' name='payment_order_reestr' {{$is_disabled}}>{{old("payment_order_reestr") ? old("payment_order_reestr") : $reestr->payment_order_reestr}}</textarea>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-2">
												<label for='prolongation_reestr'>Пролонгация</label>
												@if(old('prolongation_reestr'))
													<input id='prolongation_reestr' class='form-check-input' type="checkbox" name='prolongation_reestr' checked {{$is_disabled}}/>
												@else
													@if($reestr->prolongation_reestr)
														<input id='prolongation_reestr' class='form-check-input' type="checkbox" name='prolongation_reestr' checked {{$is_disabled}}/>
													@else
														<input id='prolongation_reestr' class='form-check-input' type="checkbox" name='prolongation_reestr' {{$is_disabled}}/>
													@endif
												@endif
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
										
									</div>
									<div class="col-md-2">
										
									</div>
									<div class="col-md-2">
										
									</div>
									<div class="col-md-3">
										
									</div>
									<div class="col-md-3">
										@if(Auth::User()->hasRole()->role != 'Администрация')
											<button type='submit' class="btn btn-primary" style="float: right;">Сохранить договор</button>
										@endif
									</div>
								</div>
								<div class="row">
								</div>
							</form>
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
															<th>{{($amount_invoices - ($amount_prepayments + $amount_payments) + $amount_returns) > 0 ? $amount_invoices - ($amount_prepayments + $amount_payments) + $amount_returns : 0}} р.</th>
														</tr>
														<tr>
															<th>Кредит</th>
															<th>{{(($amount_prepayments + $amount_payments) - $amount_invoices - $amount_returns) > 0 ? ($amount_prepayments + $amount_payments) - $amount_invoices - $amount_returns : 0}} р.</th>
														</tr>
													</thead>
												</table>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-6">
												@if($scores)
													<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
														<thead>
															<tr>
																<th colspan='3' style='text-align: center;'>СЧЕТ НА ОПЛАТУ</th>
															</tr>
															<tr>
																<th>№ сч</th>
																<th>Дата</th>
																<th>Сумма</th>
															</tr>
														</thead>
														<tbody>
															@foreach($scores as $score)
																<tr class="rowsContract">
																	<td>
																		{{ $score->number_invoice }}
																	</td>
																	<td>
																		{{ $score->date_invoice ? date('d.m.Y', strtotime($score->date_invoice)) : '' }}
																	</td>
																	<td>
																		{{ $score->amount_p_invoice }}
																	</td>
																</tr>
															@endforeach
														</tbody>
													</table>
												@endif
											</div>
											<div class="col-md-6">
												@if($prepayments)
													<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
														<thead>
															<tr>
																<th colspan='3' style='text-align: center;'>СЧЕТ НА АВАНС</th>
															</tr>
															<tr>
																<th>№ сч</th>
																<th>Дата</th>
																<th>Сумма</th>
															</tr>
														</thead>
														<tbody>
															@foreach($prepayments as $prepayment)
																<tr class="rowsContract">
																	<td>
																		{{ $prepayment->number_invoice }}
																	</td>
																	<td>
																		{{ $prepayment->date_invoice ? date('d.m.Y', strtotime($prepayment->date_invoice)) : '' }}
																	</td>
																	<td>
																		{{ $prepayment->amount_p_invoice }}
																	</td>
																</tr>
															@endforeach
														</tbody>
													</table>
												@endif
											</div>
										</div>
										<div class='row'>
											<div class="col-md-6">
												@if($invoices)
													<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
														<thead>
															<tr>
																<th colspan='3' style='text-align: center;'>ОКАЗАНО УСЛУГ (Счет-фактура)</th>
															</tr>
															<tr>
																<th>№ п/п</th>
																<th>Дата</th>
																<th>Сумма</th>
															</tr>
														</thead>
														<tbody>
															@foreach($invoices as $invoice)
																<tr class="rowsContract">
																	<td>
																		{{ $invoice->number_invoice }}
																	</td>
																	<td>
																		{{ $invoice->date_invoice ? date('d.m.Y', strtotime($invoice->date_invoice)) : '' }}
																	</td>
																	<td>
																		{{ $invoice->amount_p_invoice }}
																	</td>
																</tr>
															@endforeach
														</tbody>
													</table>
												@endif
											</div>
											<div class="col-md-6">
												@if($payments)
													<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
														<thead>
															<tr>
																<th colspan='3' style='text-align: center;'>ОПЛАТА ОКАЗАННЫХ УСЛУГ</th>
															</tr>
															<tr>
																<th>№ п/п</th>
																<th>Дата</th>
																<th>Сумма</th>
															</tr>
														</thead>
														<tbody>
															@foreach($payments as $payment)
																<tr class="rowsContract">
																	<td>
																		{{ $payment->number_invoice }}
																	</td>
																	<td>
																		{{ $payment->date_invoice ? date('d.m.Y', strtotime($payment->date_invoice)) : '' }}
																	</td>
																	<td>
																		{{ $payment->amount_p_invoice }}
																	</td>
																</tr>
															@endforeach
														</tbody>
													</table>
												@endif
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
									</div>
								</div>
							</div>
						</div>
					@else
						<!-- РЕЕСТРА ДОГОВОРА (НЕ СИП!) -->
						<div class="content">
							<form method='POST' action="{{route('department.ekonomic.update_reestr', $contract->id)}}">
								{{csrf_field()}}
								<div class="row border-top border-bottom border-left border-right">
									<div class="col-md-2 border-top border-left border-bottom">
										<div class="form-group">
											<label>Контрагент</label>
											<div class="form-group">
												<select id="sel4" class='form-control select_counterpartie_reestr {{$errors->has("id_counterpartie_contract") ? print("inputError ") : print("")}}' name='id_counterpartie_contract'>
													<option></option>
													@if($counterparties)
														@foreach($counterparties as $counterpartie)
															@if(old('id_counterpartie_contract'))
																@if(old('id_counterpartie_contract') == $counterpartie->id)
																	<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}' selected>{{ $counterpartie->name }}</option>
																@else
																	@if(Auth::User()->hasRole()->role == 'Администратор')
																		<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
																	@elseif(Auth::User()->hasRole()->role == 'Отдел управления договорами')
																		<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
																	@else
																		@if($counterpartie->is_sip_counterpartie == '1')
																			<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
																		@endif
																	@endif													
																@endif
															@else
																@if($contract->id_counterpartie_contract == $counterpartie->id)
																	<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}' selected>{{ $counterpartie->name }}</option>
																@else
																	@if(Auth::User()->hasRole()->role == 'Администратор')
																		<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
																	@elseif(Auth::User()->hasRole()->role == 'Отдел управления договорами')
																		<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
																	@else
																		@if($counterpartie->is_sip_counterpartie == '1')
																			<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
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
									</div>
									<div class="col-md-1 border-top border-bottom">
										<div class="form-group">
											<button type='button' data-toggle="modal" data-target="#chose_counterpartie" class="btn btn-primary" style='margin-top: 27px;'>Выбрать</button>
										</div>
									</div>
									<div class="col-md-2 border-top border-bottom">
										<div class="form-group">
											<label>Внимание!</label>
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
									</div>
									<div class="col-md-3 border-top border-bottom border-right">
										<div class='col-md-7'>
											<div class="form-group">
												<label for='numberContract'>Номер договора</label>
												<input id='numberContract' class='form-control {{$errors->has("number_contract") ? print("inputError ") : print("")}}' name='number_contract' type='text' value='{{old("number_contract") ? old("number_contract") : $contract->number_contract}}' readonly />
												@if($errors->has('number_contract'))
													<label class='msgError'>{{$errors->first('number_contract')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-5" style='text-align: center;'>
											<div class='form-group' style='margin-top: 27px;'>
												@if(isset($prev_contract))
													<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $prev_contract)}}" title='Назад' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
												@else
													<button class='btn btn-primary' title='Назад' disabled type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
												@endif
												@if(isset($next_contract))
													<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $next_contract)}}" title='Вперед' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
												@else
													<button class='btn btn-primary' title='Вперед' disabled type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-2 border-bottom border-top">
										<div class="form-group">
											<label for='amount_contract_reestr'>Сумма (окончательная)</label>
											<input id='amount_contract_reestr' class='form-control check-number' name='amount_contract_reestr' type='text' value='{{old("amount_contract_reestr") ? old("amount_contract_reestr") : $reestr->amount_contract_reestr}}'/>
										</div>
									</div>
									<div class="col-md-2 border-bottom border-top border-right">
										<div class="form-group">
											<label for='amount_invoice_reestr'>Сумма по счетам</label>
											<input id='amount_invoice_reestr' class='form-control check-number' name='amount_invoice_reestr' type='text' value='{{$reestr->amount_invoice_reestr}}' disabled />
										</div>
									</div>
								</div>
								<div class="row border-right border-left">
									<div class="col-md-1 border-top border-bottom">
										<div class="form-group">
											<label for='number_pp'>№ п/п</label>
											<input id='number_pp' class='change_contract_number form-control {{$errors->has("number_pp") ? print("inputError ") : print("")}}' name='number_pp' type='text' value='{{old("number_pp") ? old("number_pp") : (strlen($contract->number_contract) > 0 ? explode("‐",$contract->number_contract)[0] : "")}}' required/>
											@if($errors->has('number_pp'))
												<label class='msgError'>{{$errors->first('number_pp')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-1 border-top border-bottom">
										<div class="form-group">
											<label for='index_dep' style='font-size: 12px;'>Индекс подразд.</label>
											<select id='index_dep' class='change_contract_number form-control {{$errors->has("index_dep") ? print("inputError ") : print("")}}' name='index_dep' type='text' value='{{old("index_dep")}}' required>
												@if(old('index_dep'))
													<option>{{old('index_dep')}}</option>
												@endif
												<option></option>
												@foreach($departments as $department)
													@if(count(explode("‐",$contract->number_contract))>1)
														@if(explode("‐",$contract->number_contract)[1] == $department->index_department)
															<option value='{{$department->index_department}}' selected>{{$department->index_department}} {{$department->name_department}}</option>
														@else
															<option value='{{$department->index_department}}'>{{$department->index_department}} {{$department->name_department}}</option>
														@endif
													@else
														<option value='{{$department->index_department}}'>{{$department->index_department}} {{$department->name_department}}</option>
													@endif
												@endforeach
											</select>
											@if($errors->has('index_dep'))
												<label class='msgError'>{{$errors->first('index_dep')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-1 border-top border-bottom border-right">
										<div class="form-group">
											<label for='year_contract'>Год</label>
											<input id='year_contract' class='change_contract_number form-control {{$errors->has("year_contract") ? print("inputError ") : print("")}}' name='year_contract' type='text' value='{{old("year_contract") ? old("year_contract") : $contract->year_contract}}' required />
											@if($errors->has('year_contract'))
												<label class='msgError'>{{$errors->first('year_contract')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-2 border-top border-bottom">
										<div class="form-group">
											<label for='executor_contract_reestr'>Исполнитель по Дог./Контр.</label>
											<input id='executor_contract_reestr' class='form-control' name='executor_contract_reestr' type='text' value='{{old("executor_contract_reestr") ? old("executor_contract_reestr") : $reestr->executor_contract_reestr}}'/>
										</div>
									</div>
									<div class="col-md-2 border-top border-bottom">
										<div class="form-group">
											<label for='executor_reestr'>Исполнитель ОУД</label>
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
									</div>
									<div class="col-md-2 border-left border-top border-bottom" style='font-size: 12px;'>
										<div class="form-group">
											<label for='date_save_contract_reestr'>Дата сдачи Дог./Контр. на хранение</label>
											<input id='date_save_contract_reestr' class='datepicker form-control {{$errors->has("date_save_contract_reestr") ? print("inputError ") : print("")}}' name='date_save_contract_reestr' value='{{old("date_save_contract_reestr") ? old("date_save_contract_reestr") : $reestr->date_save_contract_reestr}}'/>
											@if($errors->has('date_save_contract_reestr'))
												<label class='msgError'>{{$errors->first('date_save_contract_reestr')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-2 border-top">
										<div class="form-group">
											<label for='place_save_contract_reestr'>Место хранения</label>
											<input id='place_save_contract_reestr' class='form-control {{$errors->has("place_save_contract_reestr") ? print("inputError ") : print("")}}' name='place_save_contract_reestr' value='{{old("place_save_contract_reestr") ? old("place_save_contract_reestr") : $reestr->place_save_contract_reestr}}'/>
											@if($errors->has('place_save_contract'))
												<label class='msgError'>{{$errors->first('place_save_contract')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-1 border-top">
										<div class="form-group">
											<label for='sel6' style='font-size: 12px;'>Тип документа</label>
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
									</div>
								</div>
								<div class='row'>
									<div class="col-md-9">
										<div class='row'>
											<div class="col-md-9">
												<div class='row'>
													<div class="col-md-4 border-top border-left">
														<div class="form-group">
															<label for='date_contract_on_first_reestr'>Дата Дог./Контр. на 1 л.</label>
															<input id='date_contract_on_first_reestr' class='datepicker form-control {{$errors->has("date_contract_on_first_reestr") ? print("inputError ") : print("")}}' name='date_contract_on_first_reestr' value='{{old("date_contract_on_first_reestr") ? old("date_contract_on_first_reestr") : $reestr->date_contract_on_first_reestr}}'/>
															@if($errors->has('date_contract_on_first_reestr'))
																<label class='msgError'>{{$errors->first('date_contract_on_first_reestr')}}</label>
															@endif
														</div>
													</div>
													<div class="col-md-4 border-top">
														<div class="form-group">
															<label for='date_signing_contract_reestr'>Дата подписания ФКП "НТИИМ"</label>
															<input id='date_signing_contract_reestr' class='datepicker form-control {{$errors->has("date_signing_contract_reestr") ? print("inputError ") : print("")}}' name='date_signing_contract_reestr' value='{{old("date_signing_contract_reestr") ? old("date_signing_contract_reestr") : $reestr->date_signing_contract_reestr}}'/>
															@if($errors->has('date_signing_contract_reestr'))
																<label class='msgError'>{{$errors->first('date_signing_contract_reestr')}}</label>
															@endif
														</div>
													</div>
													<div class="col-md-4 border-top" style='font-size: 12px;'>
														<div class="form-group">
															<label for='date_control_signing_contract_reestr'>Контрольный срок подписания Дог./Контр.</label>
															<input id='date_control_signing_contract_reestr' class='datepicker form-control {{$errors->has("date_control_signing_contract_reestr") ? print("inputError ") : print("")}}' name='date_control_signing_contract_reestr' value='{{old("date_control_signing_contract_reestr") ? old("date_control_signing_contract_reestr") : $reestr->date_control_signing_contract_reestr}}'/>
															@if($errors->has('date_control_signing_contract_reestr'))
																<label class='msgError'>{{$errors->first('date_control_signing_contract_reestr')}}</label>
															@endif
														</div>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-4 border-left border-bottom">
														<div class="form-group">
															<label id='label_date_registration_project_reestr' for='date_registration_project_reestr'>{{ $reestr->application_reestr ? 'Дата регистрации заявки' : 'Дата регистрации проекта'}}</label>
															@if(old('application_reestr'))
																<input id='application_reestr' class='form-check-input' style='float: right;' name='application_reestr' type="checkbox" checked />
															@else
																@if($reestr->application_reestr)
																	<input id='application_reestr' class='form-check-input' style='float: right;' name='application_reestr' type="checkbox" checked />
																@else
																	<input id='application_reestr' class='form-check-input' style='float: right;' name='application_reestr' type="checkbox"/>
																@endif
															@endif
															<label for='application_reestr' style='float: right; margin-right: 5px;'>Заявка</label>
															<input id='date_registration_project_reestr' class='datepicker form-control {{$errors->has("date_registration_project_reestr") ? print("inputError ") : print("")}}' name='date_registration_project_reestr' value='{{old("date_registration_project_reestr") ? old("date_registration_project_reestr") : $reestr->date_registration_project_reestr}}'/>
															@if($errors->has('date_registration_project_reestr'))
																<label class='msgError'>{{$errors->first('date_registration_project_reestr')}}</label>
															@endif
														</div>
													</div>
													<div class="col-md-4 border-bottom">
														<div class="form-group">
															<label for='date_signing_contract_counterpartie_reestr'>Дата подписания Контрагентом</label>
															<input id='date_signing_contract_counterpartie_reestr' class='datepicker form-control {{$errors->has("date_signing_contract_counterpartie_reestr") ? print("inputError ") : print("")}}' name='date_signing_contract_counterpartie_reestr' value='{{old("date_signing_contract_counterpartie_reestr") ? old("date_signing_contract_counterpartie_reestr") : $reestr->date_signing_contract_counterpartie_reestr}}'/>
															@if($errors->has('date_signing_contract_counterpartie_reestr'))
																<label class='msgError'>{{$errors->first('date_signing_contract_counterpartie_reestr')}}</label>
															@endif
														</div>
													</div>
													<div class="col-md-4 border-bottom">
														<div class="form-group">
															<label for='date_entry_into_force_reestr'>Дата вступления Дог./Контр. в силу</label>
															<input id='date_entry_into_force_reestr' class='datepicker form-control {{$errors->has("date_entry_into_force_reestr") ? print("inputError ") : print("")}}' name='date_entry_into_force_reestr' value='{{old("date_entry_into_force_reestr") ? old("date_entry_into_force_reestr") : $reestr->date_entry_into_force_reestr}}'/>
															@if($errors->has('date_entry_into_force_reestr'))
																<label class='msgError'>{{$errors->first('date_entry_into_force_reestr')}}</label>
															@endif
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-3">
												<div class='row'>
													<div class="col-md-6 border-top">
														<div class="form-group">
															<label for='protocols_reestr'>Протоколы</label>
															<input id='protocols_reestr' class='form-control {{$errors->has("protocols_reestr") ? print("inputError ") : print("")}}' name='protocols_reestr' value='{{$big_date_protocol != null ? $big_date_protocol : ""}}' readonly />
														</div>
													</div>
													<div class="col-md-6 border-top border-right">
														<div class="form-group">
															<label for='add_agreements_reestr'>ДС</label>
															<input id='add_agreements_reestr' class='form-control {{$errors->has("add_agreements_reestr") ? print("inputError ") : print("")}}' name='add_agreements_reestr' value='{{$big_date_add_agreement != null ? $big_date_add_agreement : ""}}' readonly />
														</div>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-6 border-bottom">
														<div class="form-group">
															<label for='sel9'>Согл./Не согл.</label>
															<select id="sel9" class='form-control {{$errors->has("reconciliation_protocol_reestr") ? print("inputError ") : print("")}}' name='reconciliation_protocol_reestr'>
																<option value='0' {{$reestr->reconciliation_protocol_reestr == 0 ? 'selected' : ''}}></option>
																<option value='1' {{$reestr->reconciliation_protocol_reestr == 1 ? 'selected' : ''}}>Согласовано</option>
																<option value='2' {{$reestr->reconciliation_protocol_reestr == 2 ? 'selected' : ''}}>Не согласовано</option>
															</select>
														</div>
													</div>
													<div class="col-md-6 border-bottom border-right">
														<div class="form-group">
															<label for='sel10'>Согл./Не согл.</label>
															<select id="sel10" class='form-control {{$errors->has("reconciliation_agreement_reestr") ? print("inputError ") : print("")}}' name='reconciliation_agreement_reestr'>
																<option value='0' {{$reestr->reconciliation_agreement_reestr == 0 ? 'selected' : ''}}></option>
																<option value='1' {{$reestr->reconciliation_agreement_reestr == 1 ? 'selected' : ''}}>Согласовано</option>
																<option value='2' {{$reestr->reconciliation_agreement_reestr == 2 ? 'selected' : ''}}>Не согласовано</option>
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class='row'>
											<div class="col-md-3">
												<div class="form-group">
													<label class='form-check-label' for='break'>ОТКАЗ</label>
													@if(old('renouncement_contract'))
														<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" checked />
													@else
														@if($contract->renouncement_contract == 1)
															<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" checked />
														@else
															<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox"/>
														@endif
													@endif
												</div>
												<div class="form-group">
													<label class='form-check-label' for='archive_contract'>АРХИВ</label>
													@if(old('archive_contract'))
														<input id='archive_contract' class='form-check-input' name='archive_contract' type="checkbox" checked />
													@else
														@if($contract->archive_contract == 1)
															<input id='archive_contract' class='form-check-input' name='archive_contract' type="checkbox" checked />
														@else
															<input id='archive_contract' class='form-check-input' name='archive_contract' type="checkbox"/>
														@endif
													@endif
												</div>
											</div>
											<div class="col-md-9 border-right">
												<div class="form-group">
													<label for='document_success_renouncement_reestr'>Документ, подтверждающий отказ</label>
													<input id='document_success_renouncement_reestr' class='form-control {{$errors->has("document_success_renouncement_reestr") ? print("inputError ") : print("")}}' name='document_success_renouncement_reestr' value='{{old("document_success_renouncement_reestr") ? old("document_success_renouncement_reestr") : $contract->document_success_renouncement_reestr}}'/>
													@if($errors->has('document_success_renouncement_reestr'))
														<label class='msgError'>{{$errors->first('document_success_renouncement_reestr')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12 border-bottom border-right">
												<div class="form-group">
													<label for='number_aftair_renouncement_reestr'>№ дела</label>
													<input id='number_aftair_renouncement_reestr' class='form-control {{$errors->has("number_aftair_renouncement_reestr") ? print("inputError ") : print("")}}' name='number_aftair_renouncement_reestr' value='{{old("number_aftair_renouncement_reestr") ? old("number_aftair_renouncement_reestr") : $contract->number_aftair_renouncement_reestr}}'/>
													@if($errors->has('number_aftair_renouncement_reestr'))
														<label class='msgError'>{{$errors->first('number_aftair_renouncement_reestr')}}</label>
													@endif
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-2 border-bottom border-left border-top">
										<div class="form-group">
											<label for="sel3">Вид договора</span></label>
											<select id="sel3" class='form-control {{$errors->has("id_view_contract") ? print("inputError ") : print("")}}' name='id_view_contract'>
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
															@if($reestr->id_view_contract == $viewContract->id)
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
									</div>
									<div class="col-md-2 border-top border-bottom border-right">
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
									<div class="col-md-2 border-top border-bottom border-left">
										<div class="form-group">
											<label for='app_outgoing_number_reestr'>Заявка исх. №</label>
											<input id='app_outgoing_number_reestr' class='form-control {{$errors->has("app_outgoing_number_reestr") ? print("inputError ") : print("")}}' name='app_outgoing_number_reestr' value='{{old("app_outgoing_number_reestr") ? old("app_outgoing_number_reestr") : $reestr->app_outgoing_number_reestr}}'/>
											@if($errors->has('app_outgoing_number_reestr'))
												<label class='msgError'>{{$errors->first('app_outgoing_number_reestr')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-2 border-top border-bottom border-right">
										<div class="form-group">
											<label for='app_incoming_number_reestr'>Вх. №</label>
											<input id='app_incoming_number_reestr' class='form-control {{$errors->has("app_incoming_number_reestr") ? print("inputError ") : print("")}}' name='app_incoming_number_reestr' value='{{old("app_incoming_number_reestr") ? old("app_incoming_number_reestr") : $reestr->app_incoming_number_reestr}}'/>
											@if($errors->has('app_incoming_number_reestr'))
												<label class='msgError'>{{$errors->first('app_incoming_number_reestr')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-2 border-top border-bottom border-left">
										<div class="form-group">
											<label for='result_second_department_date_reestr'>Заключение отдела №2 Дата</label>
											<input id='result_second_department_date_reestr' class='datepicker form-control {{$errors->has("result_second_department_date_reestr") ? print("inputError ") : print("")}}' name='result_second_department_date_reestr' value='{{old("result_second_department_date_reestr") ? old("result_second_department_date_reestr") : $reestr->result_second_department_date_reestr}}'/>
											@if($errors->has('result_second_department_date_reestr'))
												<label class='msgError'>{{$errors->first('result_second_department_date_reestr')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-2 border-top border-bottom border-right">
										<div class="form-group">
											<label for='result_second_department_number_reestr'>№</label>
											<input id='result_second_department_number_reestr' class='form-control {{$errors->has("result_second_department_number_reestr") ? print("inputError ") : print("")}}' name='result_second_department_number_reestr' value='{{old("result_second_department_number_reestr") ? old("result_second_department_number_reestr") : $reestr->result_second_department_number_reestr}}'/>
											@if($errors->has('result_second_department_number_reestr'))
												<label class='msgError'>{{$errors->first('result_second_department_number_reestr')}}</label>
											@endif
										</div>
									</div>
								</div>
								<div class='row'>
									<input id='is_new_reestr' class='form-check-input' name='is_new_reestr' type="checkbox" checked style='display: none;'/>
									<div class="col-md-5">
										<div class="col-md-3 border-top border-bottom border-left">
											<div class='row'>
												<div class="col-md-12">
													@if(old('marketing_reestr'))
														<input id='marketing_reestr' class='form-check-input' name='marketing_reestr' type="checkbox" checked />
													@else
														@if($reestr->marketing_reestr)
															<input id='marketing_reestr' class='form-check-input' name='marketing_reestr' type="checkbox" checked />
														@else
															<input id='marketing_reestr' class='form-check-input' name='marketing_reestr' type="checkbox" />
														@endif
													@endif
													<label for='marketing_reestr'>Сбыт</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													@if(old('marketing_goz_reestr'))
														<input id='marketing_goz_reestr' class='form-check-input' name='marketing_goz_reestr' type="checkbox" checked />
													@else
														@if($reestr->marketing_goz_reestr)
															<input id='marketing_goz_reestr' class='form-check-input' name='marketing_goz_reestr' type="checkbox" checked />
														@else
															<input id='marketing_goz_reestr' class='form-check-input' name='marketing_goz_reestr' type="checkbox" />
														@endif
													@endif
													<label for='marketing_goz_reestr'>ГОЗ</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													@if(old('participation_reestr'))
														<input id='participation_reestr' class='form-check-input' name='participation_reestr' type="checkbox" checked />
													@else
														@if($reestr->participation_reestr)
															<input id='participation_reestr' class='form-check-input' name='participation_reestr' type="checkbox" checked />
														@else
															<input id='participation_reestr' class='form-check-input' name='participation_reestr' type="checkbox" />
														@endif
													@endif
													<label for='participation_reestr'>Участие</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													@if(old('marketing_fz_223_reestr'))
														<input id='marketing_fz_223_reestr' class='form-check-input' name='marketing_fz_223_reestr' type="checkbox" checked />
													@else
														@if($reestr->marketing_fz_223_reestr)
															<input id='marketing_fz_223_reestr' class='form-check-input' name='marketing_fz_223_reestr' type="checkbox" checked />
														@else
															<input id='marketing_fz_223_reestr' class='form-check-input' name='marketing_fz_223_reestr' type="checkbox" />
														@endif
													@endif
													<label for='marketing_fz_223_reestr'>223-ФЗ</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													@if(old('marketing_fz_44_reestr'))
														<input id='marketing_fz_44_reestr' class='form-check-input' name='marketing_fz_44_reestr' type="checkbox" checked />
													@else
														@if($reestr->marketing_fz_44_reestr)
															<input id='marketing_fz_44_reestr' class='form-check-input' name='marketing_fz_44_reestr' type="checkbox" checked />
														@else
															<input id='marketing_fz_44_reestr' class='form-check-input' name='marketing_fz_44_reestr' type="checkbox" />
														@endif
													@endif
													<label for='marketing_fz_44_reestr'>44-ФЗ</label>
												</div>
											</div>
										</div>
										<div class="col-md-5 border-top border-bottom">
											<div class='row'>
												<div class="col-md-12">
													@if(old('procurement_reestr'))
														<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" checked />
													@else
														@if($reestr->procurement_reestr)
															<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" checked />
														@else
															<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" />
														@endif
													@endif
													<label for='procurement_reestr'>Закуп</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													@if(old('single_provider_reestr'))
														<input id='single_provider_reestr' class='form-check-input' name='single_provider_reestr' type="checkbox" checked />
													@else
														@if($reestr->single_provider_reestr)
															<input id='single_provider_reestr' class='form-check-input' name='single_provider_reestr' type="checkbox" checked />
														@else
															<input id='single_provider_reestr' class='form-check-input' name='single_provider_reestr' type="checkbox" />
														@endif
													@endif
													<label for='single_provider_reestr'>Единственный поставщик</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													@if(old('own_funds_reestr'))
														<input id='own_funds_reestr' class='form-check-input' name='own_funds_reestr' type="checkbox" checked />
													@else
														@if($reestr->own_funds_reestr)
															<input id='own_funds_reestr' class='form-check-input' name='own_funds_reestr' type="checkbox" checked />
														@else
															<input id='own_funds_reestr' class='form-check-input' name='own_funds_reestr' type="checkbox" />
														@endif
													@endif
													<label for='own_funds_reestr'>Собственные средства</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													@if(old('investments_reestr'))
														<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" checked />
													@else
														@if($reestr->investments_reestr)
															<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" checked />
														@else
															<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" />
														@endif
													@endif
													<label for='investments_reestr'>Инвестиции</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													@if(old('purchase_reestr'))
														<input id='purchase_reestr' class='form-check-input' name='purchase_reestr' type="checkbox" checked />
													@else
														@if($reestr->purchase_reestr)
															<input id='purchase_reestr' class='form-check-input' name='purchase_reestr' type="checkbox" checked />
														@else
															<input id='purchase_reestr' class='form-check-input' name='purchase_reestr' type="checkbox" />
														@endif
													@endif
													<label for='purchase_reestr'>Закупка у СМСП</label>
												</div>
											</div>
										</div>
										<div class="col-md-4 border-top border-bottom border-right" style='padding-bottom: 33px;'>
											<!--<div class='row'>
												<div class="col-md-12">
													@if(old('procurement_fz_223_reestr'))
														<input id='procurement_fz_223_reestr' class='form-check-input' name='procurement_fz_223_reestr' type="checkbox" checked />
													@else
														@if($reestr->procurement_fz_223_reestr)
															<input id='procurement_fz_223_reestr' class='form-check-input' name='procurement_fz_223_reestr' type="checkbox" checked />
														@else
															<input id='procurement_fz_223_reestr' class='form-check-input' name='procurement_fz_223_reestr' type="checkbox" />
														@endif
													@endif
													<label for='procurement_fz_223_reestr'>223-ФЗ</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													@if(old('procurement_fz_44_reestr'))
														<input id='procurement_fz_44_reestr' class='form-check-input' name='procurement_fz_44_reestr' type="checkbox" checked />
													@else
														@if($reestr->procurement_fz_44_reestr)
															<input id='procurement_fz_44_reestr' class='form-check-input' name='procurement_fz_44_reestr' type="checkbox" checked />
														@else
															<input id='procurement_fz_44_reestr' class='form-check-input' name='procurement_fz_44_reestr' type="checkbox" />
														@endif
													@endif
													<label for='procurement_fz_44_reestr'>44-ФЗ</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													@if(old('procurement_goz_reestr'))
														<input id='procurement_goz_reestr' class='form-check-input' name='procurement_goz_reestr' type="checkbox" checked />
													@else
														@if($reestr->procurement_goz_reestr)
															<input id='procurement_goz_reestr' class='form-check-input' name='procurement_goz_reestr' type="checkbox" checked />
														@else
															<input id='procurement_goz_reestr' class='form-check-input' name='procurement_goz_reestr' type="checkbox" />
														@endif
													@endif
													<label for='procurement_goz_reestr'>ГОЗ</label>
												</div>
											</div>-->
											<div class='row'>
												<div class="col-md-12">
													@if(old('export_reestr'))
														<input id='export_reestr' class='form-check-input' name='export_reestr' type="checkbox" checked />
													@else
														@if($reestr->export_reestr)
															<input id='export_reestr' class='form-check-input' name='export_reestr' type="checkbox" checked />
														@else
															<input id='export_reestr' class='form-check-input' name='export_reestr' type="checkbox" />
														@endif
													@endif
													<label for='export_reestr'>Экспорт</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													@if(old('interfactory_reestr'))
														<input id='interfactory_reestr' class='form-check-input' name='interfactory_reestr' type="checkbox" checked />
													@else
														@if($reestr->interfactory_reestr)
															<input id='interfactory_reestr' class='form-check-input' name='interfactory_reestr' type="checkbox" checked />
														@else
															<input id='interfactory_reestr' class='form-check-input' name='interfactory_reestr' type="checkbox" />
														@endif
													@endif
													<label for='interfactory_reestr'>Межзаводские</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													@if(old('other_reestr'))
														<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" checked />
													@else
														@if($reestr->other_reestr)
															<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" checked />
														@else
															<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" />
														@endif
													@endif
													<label for='other_reestr'>Иное</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													@if(old('mob_reestr'))
														<input id='mob_reestr' class='form-check-input' name='mob_reestr' type="checkbox" checked />
													@else
														@if($reestr->mob_reestr)
															<input id='mob_reestr' class='form-check-input' name='mob_reestr' type="checkbox" checked />
														@else
															<input id='mob_reestr' class='form-check-input' name='mob_reestr' type="checkbox" />
														@endif
													@endif
													<label for='mob_reestr'>МОБ</label>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-7 border-all">
										<div class="row" style='text-align: center;'>
											<div class="col-md-12">
												<label>Согласование крупной сделки</label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-2">
												<label for='number_inquiry_reestr'>Запрос №</label>
											</div>
											<div class="col-md-3">
												<input id='number_inquiry_reestr' class='form-control {{$errors->has("number_inquiry_reestr") ? print("inputError ") : print("")}}' name='number_inquiry_reestr' value='{{old("number_inquiry_reestr") ? old("number_inquiry_reestr") : $reestr->number_inquiry_reestr}}'/>
												@if($errors->has('number_inquiry_reestr'))
													<label class='msgError'>{{$errors->first('number_inquiry_reestr')}}</label>
												@endif
											</div>
											<div class="col-md-1">
												<label for='date_inquiry_reestr'>от</label>
											</div>
											<div class="col-md-2">
												<input id='date_inquiry_reestr' class='datepicker form-control {{$errors->has("date_inquiry_reestr") ? print("inputError ") : print("")}}' name='date_inquiry_reestr' value='{{old("date_inquiry_reestr") ? old("date_inquiry_reestr") : $reestr->date_inquiry_reestr}}'/>
												@if($errors->has('date_inquiry_reestr'))
													<label class='msgError'>{{$errors->first('date_inquiry_reestr')}}</label>
												@endif
											</div>
											<div class="col-md-2">
												<label for='days_reconciliation_reestr'>Срок действия согласования крупной сделки</label>
											</div>
											<div class="col-md-2">
												<input id='days_reconciliation_reestr' class='form-control {{$errors->has("days_reconciliation_reestr") ? print("inputError ") : print("")}}' name='days_reconciliation_reestr' value='{{old("days_reconciliation_reestr") ? old("days_reconciliation_reestr") : $reestr->days_reconciliation_reestr}}'/>
												@if($errors->has('days_reconciliation_reestr'))
													<label class='msgError'>{{$errors->first('days_reconciliation_reestr')}}</label>
												@endif
											</div>
										</div>
										<div class="row">
											<div class="col-md-2">
												<label for='number_answer_reestr'>Ответ №</label>
											</div>
											<div class="col-md-3">
												<input id='number_answer_reestr' class='form-control {{$errors->has("number_answer_reestr") ? print("inputError ") : print("")}}' name='number_answer_reestr' value='{{old("number_answer_reestr") ? old("number_answer_reestr") : $reestr->number_answer_reestr}}'/>
												@if($errors->has('number_answer_reestr'))
													<label class='msgError'>{{$errors->first('number_answer_reestr')}}</label>
												@endif
											</div>
											<div class="col-md-1">
												<label for='date_answer_reestr'>от</label>
											</div>
											<div class="col-md-2">
												<input id='date_answer_reestr' class='datepicker form-control {{$errors->has("date_answer_reestr") ? print("inputError ") : print("")}}' name='date_answer_reestr' value='{{old("date_answer_reestr") ? old("date_answer_reestr") : $reestr->date_answer_reestr}}'/>
												@if($errors->has('date_answer_reestr'))
													<label class='msgError'>{{$errors->first('date_answer_reestr')}}</label>
												@endif
											</div>
											<div class="col-md-2">
												<label for='count_mounth_reestr'>Количество месяцев</label>
											</div>
											<div class="col-md-2">
												<input id='count_mounth_reestr' class='form-control {{$errors->has("count_mounth_reestr") ? print("inputError ") : print("")}}' name='count_mounth_reestr' value='{{old("count_mounth_reestr") ? old("count_mounth_reestr") : $reestr->count_mounth_reestr}}'/>
												@if($errors->has('count_mounth_reestr'))
													<label class='msgError'>{{$errors->first('count_mounth_reestr')}}</label>
												@endif
											</div>
										</div>
										<div class="row border-top">
											<div class="col-md-2">
												<label>Сроки согласования проекта договора исполнителей</label>
											</div>
											<div class="col-md-3">
												<label for='begin_date_reconciliation_reestr'>Начало согласования (дата)</label>
												<input id='begin_date_reconciliation_reestr' class='datepicker form-control {{$errors->has("begin_date_reconciliation_reestr") ? print("inputError ") : print("")}}' name='begin_date_reconciliation_reestr' value='{{old("begin_date_reconciliation_reestr") ? old("begin_date_reconciliation_reestr") : $reestr->begin_date_reconciliation_reestr}}'/>
												@if($errors->has('begin_date_reconciliation_reestr'))
													<label class='msgError'>{{$errors->first('begin_date_reconciliation_reestr')}}</label>
												@endif
											</div>
											<div class="col-md-3">
												<label for='end_date_reconciliation_reestr'>Окончание согласования (дата)</label>
												<input id='end_date_reconciliation_reestr' class='datepicker form-control {{$errors->has("end_date_reconciliation_reestr") ? print("inputError ") : print("")}}' name='end_date_reconciliation_reestr' value='{{old("end_date_reconciliation_reestr") ? old("end_date_reconciliation_reestr") : $reestr->end_date_reconciliation_reestr}}'/>
												@if($errors->has('end_date_reconciliation_reestr'))
													<label class='msgError'>{{$errors->first('end_date_reconciliation_reestr')}}</label>
												@endif
											</div>
											<div class="col-md-4">
												<label for='count_days_reconciliation_reestr'>Общее количество дней согласования</label>
												<input id='count_days_reconciliation_reestr' class='form-control {{$errors->has("count_days_reconciliation_reestr") ? print("inputError ") : print("")}}' name='count_days_reconciliation_reestr' value='{{old("count_days_reconciliation_reestr") ? old("count_days_reconciliation_reestr") : $reestr->count_days_reconciliation_reestr}}'/>
												@if($errors->has('count_days_reconciliation_reestr'))
													<label class='msgError'>{{$errors->first('count_days_reconciliation_reestr')}}</label>
												@endif
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label for='miniCounterpartie'>Полное наименование контрагента</label>
										<input id='miniCounterpartie' class='form-control {{$errors->has("number_contract") ? print("inputError ") : print("")}}' type='text' value='{{ $contract->full_name_counterpartie_contract }}' readonly />
									</div>
									<div class="col-md-3">
										<label>ИНН</label>
										<input id='inn_counterpartie' class='form-control' value='{{$contract->inn_counterpartie_contract}}' readonly />
									</div>
									@if($contract->id_counterpartie_contract)
										<div class='col-md-3'>
											<button class='btn btn-primary btn-href' type='button' style='float: right; margin-top: 24px;' href="{{route('counterpartie.edit', $contract->id_counterpartie_contract)}}">Информация о контрагенте</button>
										</div>
									@endif
								</div>
								<div class="row border-all">
									<div class="col-md-12">
										<div class='row'>
											<div class="col-md-6">
												<div class="form-group">
													<label for='number_counterpartie_contract_reestr'>№ дог. контрагента</label>
													<input id='number_counterpartie_contract_reestr' class='form-control {{$errors->has("number_counterpartie_contract_reestr") ? print("inputError ") : print("")}}' name='number_counterpartie_contract_reestr' value='{{old("number_counterpartie_contract_reestr") ? old("number_counterpartie_contract_reestr") : $reestr->number_counterpartie_contract_reestr}}'/>
													@if($errors->has('number_counterpartie_contract_reestr'))
														<label class='msgError'>{{$errors->first('number_counterpartie_contract_reestr')}}</label>
													@endif
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label for='igk_reestr'>ИГК</label>
													<input id='igk_reestr' class='form-control {{$errors->has("igk_reestr") ? print("inputError ") : print("")}}' name='igk_reestr' value='{{ old("igk_reestr") ? old("igk_reestr") : $reestr->igk_reestr }}'/>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label for='ikz_reestr'>ИКЗ</label>
													<input id='ikz_reestr' class='form-control {{$errors->has("ikz_reestr") ? print("inputError ") : print("")}}' name='ikz_reestr' value='{{ old("ikz_reestr") ? old("ikz_reestr") : $reestr->ikz_reestr }}'/>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3 border-all">
										<div class="row">
											<div class="col-md-12">
												<div class='form-group'>
													<label for='itemContract'>Предмет договора/контракта</label>
													<textarea id='itemContract' class='form-control {{$errors->has("item_contract") ? print("inputError ") : print("")}}' name='item_contract' type="text" style="width: 100%;" rows='4'>{{ old('item_contract') ? old('item_contract') : $contract->item_contract }}</textarea>
													@if($errors->has('item_contract'))
														<label class='msgError'>{{$errors->first('item_contract')}}</label>
													@endif
												</div>
											</div>
											<div class="col-md-12">
												<label for='okpd_2_reestr'>ОКПД 2</label>
												<input id='okpd_2_reestr' class='form-control {{$errors->has("okpd_2_reestr") ? print("inputError ") : print("")}}' name='okpd_2_reestr' value='{{ old("okpd_2_reestr") ? old("okpd_2_reestr") : $reestr->okpd_2_reestr }}'/>
											</div>
											<div class="col-md-12">
												<div class='form-group'>
													<label for='nameWork'>Цель заключения Дог./Контр.</label>
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
														<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%; overflow-x: scroll; white-space: nowrap;" rows='5' cols='2' readonly>@foreach($states as $state){{$state->date_state . '   ' . $state->name_state . '   ' . $state->surname . ' ' . mb_substr($state->name, 0, 1) . '.' . mb_substr($state->patronymic, 0, 1) . '.&#13;&#10;'}}@endforeach</textarea>
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
										<div class="row">
											<div class="col-md-4">
											</div>
											<div class="col-md-1">
												<div class='form-group'>
													@if(isset($prev_contract))
														<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $prev_contract)}}" title='Назад' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
													@else
														<button class='btn btn-primary' title='Назад' disabled type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
													@endif
												</div>
											</div>
											<div class="col-md-1">
											</div>
											<div class="col-md-1">
												<div class='form-group'>
													@if(isset($next_contract))
														<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $next_contract)}}" title='Вперед' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
													@else
														<button class='btn btn-primary' title='Вперед' disabled type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
													@endif
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-9 border-all">
										<div class="row">
											<div class="col-md-12">
												<label>Гарантия банка</label>
											</div>
											<div class="col-md-2">
												<div class='row'>
													<div class="col-md-2">
														<label for='date_bank_reestr'>До</label>
													</div>
													<div class="col-md-10">
														<input id='date_bank_reestr' class='datepicker form-control {{$errors->has("date_bank_reestr") ? print("inputError ") : print("")}}' name='date_bank_reestr' value='{{ old("date_bank_reestr") ? old("date_bank_reestr") : $reestr->date_bank_reestr }}'/>
													</div>
												</div>
											</div>
											<div class="col-md-5">
												<div class='row'>
													<div class="col-md-1">
														<label for='amount_bank_reestr'>Сумма</label>
													</div>
													<div class="col-md-5">
														<input id='amount_bank_reestr' class='form-control check-number {{$errors->has("amount_bank_reestr") ? print("inputError ") : print("")}}' name='amount_bank_reestr' value='{{ old("amount_bank_reestr") ? old("amount_bank_reestr") : $reestr->amount_bank_reestr }}'/>
													</div>
													<div class="col-md-1">
														<label for='bank_reestr'>Банк</label>
													</div>
													<div class="col-md-5">
														<input id='bank_reestr' class='form-control {{$errors->has("bank_reestr") ? print("inputError ") : print("")}}' name='bank_reestr' value='{{ old("bank_reestr") ? old("bank_reestr") : $reestr->bank_reestr }}'/>
													</div>
												</div>
											</div>
											<div class='col-md-5'>
												<button class='btn btn-primary' data-toggle="modal" data-target="#scan" type='button' style='float: right; width: 184px;'>Сканы</button>
											</div>
										</div>
										<div class="row border-top">
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
											<div class='col-md-5'>
												<button class='btn btn-primary btn-href' style='float: right; width: 184px;' type='button' href="{{route('department.reestr.show_obligation', $contract->id)}}">Исполнение Дог./Контр.</button>
											</div>
										</div>
										<div class='row border-bottom'>
											<div class="col-md-10">
												<div class="form-group">
													<input id='date_contract_reestr' class='form-control {{$errors->has("date_contract_reestr") ? print("inputError ") : print("")}}' name='date_contract_reestr' value='{{ old("date_contract_reestr") ? old("date_contract_reestr") : $reestr->date_contract_reestr }}'/>
												</div>
											</div>
											<div class='col-md-2'>
												<button class='btn btn-primary btn-href' style='float: right; width: 184px;' type='button' href="{{route('department.reestr.show_protocols', $contract->id)}}">ПР/ПСР/ПУР</button>
											</div>
										</div>
										<div class="row border-bottom">
											<div class="col-md-10">
												<div class="form-group">
													<label for='date_maturity_reestr'>Срок исполнения обязательств</label>
													<input id='date_maturity_reestr' class='form-control {{$errors->has("date_maturity_reestr") ? print("inputError ") : print("")}}' name='date_maturity_reestr' value='{{ old("date_maturity_reestr") ? old("date_maturity_reestr") : $reestr->date_maturity_reestr }}'/>
												</div>
											</div>
											<div class="col-md-2">
												<button class='btn btn-primary btn-href' style='float: right; width: 184px;' type='button' href="{{route('department.reestr.show_additional_agreements', $contract->id)}}">ДС</button>
											</div>
											<div class="col-md-2">
												<button class='btn btn-primary btn-href' style='float: right; width: 184px; margin-top: 5px;' type='button' href="{{route('department.reestr.show_amount_invoice', $contract->id)}}">Сумма по счетам</button>
											</div>
										</div>
										<div class="row">
											<div class="col-md-10">
												<div class='row'>
													<div class="col-md-1">
														<label for='amount_reestr'>Сумма</label>
													</div>
													<div class="col-md-2">
														<input id='amount_reestr' class='form-control check-number {{$errors->has("amount_reestr") ? print("inputError ") : print("")}}' name='amount_reestr' value='{{old("amount_reestr") ? old("amount_reestr") : $reestr->amount_reestr}}'/>
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
													<div class="col-md-3">
														<label for='approximate_amount_reestr'>Ориентировочная</label>
														@if(old('approximate_amount_reestr'))
															<input id='approximate_amount_reestr' class='form-check-input' name='approximate_amount_reestr' type="checkbox" checked />
														@else
															@if($reestr->vat_reestr)
																<input id='approximate_amount_reestr' class='form-check-input' name='approximate_amount_reestr' type="checkbox" checked />
															@else
																<input id='approximate_amount_reestr' class='form-check-input' name='approximate_amount_reestr' type="checkbox"/>
															@endif
														@endif
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														<input class='form-control {{$errors->has("amount_comment_reestr") ? print("inputError ") : print("")}}' name='amount_comment_reestr' value='{{old("amount_comment_reestr") ? old("amount_comment_reestr") : $reestr->amount_comment_reestr}}'/>
													</div>
												</div>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-6">
												<div class='row'>
													<div class="col-md-2">
														<label for='nmcd_reestr'>НМЦД/НМЦК</label>
													</div>
													<div class="col-md-3">
														<input id='nmcd_reestr' class='form-control check-number {{$errors->has("nmcd_reestr") ? print("inputError ") : print("")}}' name='nmcd_reestr' value='{{old("nmcd_reestr") ? old("nmcd_reestr") : $reestr->nmcd_reestr}}'/>
													</div>
													<div class="col-md-3">
														<select class='form-control {{$errors->has("nmcd_unit_reestr") ? print("inputError ") : print("")}}' name='nmcd_unit_reestr'>
															<option></option>
															@foreach($units as $unit)
																@if(old('nmcd_unit_reestr'))
																	<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
																@else
																	@if($reestr->nmcd_unit_reestr == $unit->id)
																		<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
																	@else
																		<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
																	@endif
																@endif
															@endforeach
														</select>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class='row'>
													<div class="col-md-2">
														<label for='economy_reestr'>Экономия</label>
													</div>
													<div class="col-md-3">
														<input id='economy_reestr' class='form-control check-number {{$errors->has("economy_reestr") ? print("inputError ") : print("")}}' name='economy_reestr' value='{{old("economy_reestr") ? old("economy_reestr") : $reestr->economy_reestr}}'/>
													</div>
													<div class="col-md-3">
														<select class='form-control {{$errors->has("economy_unit_reestr") ? print("inputError ") : print("")}}' name='economy_unit_reestr'>
															<option></option>
															@foreach($units as $unit)
																@if(old('economy_unit_reestr'))
																	<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
																@else
																	@if($reestr->economy_unit_reestr == $unit->id)
																		<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
																	@else
																		<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
																	@endif
																@endif
															@endforeach
														</select>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-10">
												<label>Порядок оплаты</label>
												<div class='row'>
													<div class="col-md-6">
														<label>Аванс</label>
														<textarea id='prepayment_order_reestr' class='form-control' type="text" style="width: 100%;" rows='2' name='prepayment_order_reestr'>{{old("prepayment_order_reestr") ? old("prepayment_order_reestr") : $reestr->prepayment_order_reestr}}</textarea>
													</div>
													<div class="col-md-6">
														<label>Окончат. расчет</label>
														<textarea id='score_order_reestr' class='form-control' type="text" style="width: 100%;" rows='2' name='score_order_reestr'>{{old("score_order_reestr") ? old("score_order_reestr") : $reestr->score_order_reestr}}</textarea>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														<label>Иное</label>
														<textarea id='payment_order_reestr' class='form-control' type="text" style="width: 100%;" rows='2' name='payment_order_reestr'>{{old("payment_order_reestr") ? old("payment_order_reestr") : $reestr->payment_order_reestr}}</textarea>
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
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
										
									</div>
									<div class="col-md-2">
										
									</div>
									<div class="col-md-2">
										
									</div>
									<div class="col-md-3">
										
									</div>
									<div class="col-md-3">
										@if(Auth::User()->hasRole()->role != 'Администрация')
											<button type='submit' class="btn btn-primary" style="float: right;">Сохранить договор</button>
										@endif
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
												@if(Auth::User()->hasRole()->role != 'Администрация')
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
															<option value='http://{{$resolution->path_resolution}}' download_href='resolution_download/{{$resolution->id}}' delete_href='{{route("resolution_delete",$resolution->id)}}'>{{$resolution->real_name_resolution}}</option>
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
											<div class="col-md-3">
												@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->surname == 'Бастрыкова')
													<button id='delete_resolution' type='button' class='btn btn-danger' style='width: 122px;'>Удалить скан</button>
												@endif
											</div>
											<div class="col-md-3">
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
												<input id='new_file_resolution' type='file' name='new_file_resolution' value='D:\'/>
											</div>
											<div class='col-md-6'>
												<input id='date_resolution' name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-12'>
												<label>Наименование резолюции</label>
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
				@else
					<!-- ДОБАВЛЕНИЕ КОНТРАКТА В РЕЕСТР (НЕ СИП) -->
					<div class="content">
						<form method='POST' action="{{route('department.ekonomic.create_reestr')}}" file='true' enctype='multipart/form-data'>
							{{csrf_field()}}
							<div class="row border-top border-bottom border-left border-right">
								<div class="col-md-2 border-top border-left border-bottom">
									<label>Контрагент</label>
									<div class="form-group">
										<select id="sel4" class='form-control select_counterpartie_reestr {{$errors->has("id_counterpartie_contract") ? print("inputError ") : print("")}}' name='id_counterpartie_contract'>
											<option></option>
											@if($counterparties)
												@foreach($counterparties as $counterpartie)
													@if(old('id_counterpartie_contract'))
														@if(old('id_counterpartie_contract') == $counterpartie->id)
															<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}' selected>{{ $counterpartie->name }}</option>
														@else
															@if(Auth::User()->hasRole()->role == 'Администратор')
																<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
															@elseif(Auth::User()->hasRole()->role == 'Отдел управления договорами')
																<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
															@else
																@if($counterpartie->is_sip_counterpartie == '1')
																	<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
																@endif
															@endif													
														@endif
													@else
														@if(Auth::User()->hasRole()->role == 'Администратор')
															<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
														@elseif(Auth::User()->hasRole()->role == 'Отдел управления договорами')
															<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
														@else
															@if($counterpartie->is_sip_counterpartie == '1')
																<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
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
								<div class="col-md-1 border-top border-bottom">
									<div class="form-group">
										<button type='button' class="btn btn-primary" data-toggle="modal" data-target="#chose_counterpartie" style='margin-top: 27px;'>Выбрать</button>
									</div>
								</div>
								<div class="col-md-2 border-top border-bottom">
									<div class="form-group">
										<label>Внимание!</label>
										<input class='form-control' style='color:red; text-align:center;' type='text' value='' readonly />
									</div>
								</div>
								<div class="col-md-3 border-top border-bottom border-right">
									<div class="form-group">
										<label for='numberContract'>Номер договора</label>
										<input id='numberContract' class='form-control {{$errors->has("number_contract") ? print("inputError ") : print("")}}' name='number_contract' type='text' value='{{old("number_contract")}}' readonly />
										@if($errors->has('number_contract'))
											<label class='msgError'>{{$errors->first('number_contract')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-2 border-top border-bottom">
									<div class="form-group">
										<label for='amount_contract_reestr'>Сумма (окончательная)</label>
										<input id='amount_contract_reestr' class='form-control check-number' name='amount_contract_reestr' type='text' value='{{old("amount_contract_reestr")}}'/>
									</div>
								</div>
								<div class="col-md-2 border-top border-bottom border-right">
									<div class="form-group">
										<label for='amount_invoice_reestr'>Сумма по счетам</label>
										<input id='amount_invoice_reestr' class='form-control check-number' name='amount_invoice_reestr' type='text' value='{{$reestr->amount_invoice_reestr}}' disabled />
									</div>
								</div>
							</div>
							<div class="row border-right border-left">
								<div class="col-md-1 border-top border-bottom">
									<div class="form-group">
										<label for='number_pp'>№ п/п</label>
										<input id='number_pp' class='change_contract_number form-control {{$errors->has("number_pp") ? print("inputError ") : print("")}}' name='number_pp' type='text' value='{{old("number_pp")}}' required/>
										@if($errors->has('number_pp'))
											<label class='msgError'>{{$errors->first('number_pp')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-1 border-top border-bottom">
									<div class="form-group">
										<label for='index_dep' style='font-size: 12px;'>Индекс подразд.</label>
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
								<div class="col-md-1 border-top border-bottom border-right">
									<div class="form-group">
										<label for='year_contract'>Год</label>
										<input id='year_contract' class='change_contract_number form-control {{$errors->has("year_contract") ? print("inputError ") : print("")}}' name='year_contract' type='text' value='{{old("year_contract")}}' required />
										@if($errors->has('year_contract'))
											<label class='msgError'>{{$errors->first('year_contract')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-2 border-top border-bottom">
									<div class="form-group">
										<label for='executor_contract_reestr'>Исполнитель по Дог./Контр.</label>
										<input id='executor_contract_reestr' class='form-control' name='executor_contract_reestr' type='text' value='{{old("executor_contract_reestr")}}'/>
									</div>
								</div>
								<div class="col-md-2 border-top border-bottom">
									<div class="form-group">
										<label for='executor_reestr'>Исполнитель ОУД</label>
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
								</div>
								<div class="col-md-2 border-top border-bottom border-left">
									<div class="form-group">
										<label for='date_save_contract_reestr' style='font-size: 12px;'>Дата сдачи Дог./Контр. на хранение</label>
										<input id='date_save_contract_reestr' class='datepicker form-control {{$errors->has("date_save_contract_reestr") ? print("inputError ") : print("")}}' name='date_save_contract_reestr' value='{{old("date_save_contract_reestr")}}'/>
										@if($errors->has('date_save_contract_reestr'))
											<label class='msgError'>{{$errors->first('date_save_contract_reestr')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-2 border-top">
									<div class="form-group">
										<label for='place_save_contract_reestr'>Место хранения</label>
										<input id='place_save_contract_reestr' class='form-control {{$errors->has("place_save_contract_reestr") ? print("inputError ") : print("")}}' name='place_save_contract_reestr' value='{{old("place_save_contract_reestr")}}'/>
										@if($errors->has('place_save_contract'))
											<label class='msgError'>{{$errors->first('place_save_contract')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-1 border-top border-right">
									<div class="form-group">
										<label for='sel6' style='font-size: 12px;'>Тип документа</label>
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
								</div>
							</div>
							<div class='row'>
								<div class="col-md-9">
									<div class='row'>
										<div class="col-md-9">
											<div class='row'>
												<div class="col-md-4 border-top border-left">
													<div class="form-group">
														<label for='date_contract_on_first_reestr'>Дата Дог./Контр. на 1 л.</label>
														<input id='date_contract_on_first_reestr' class='datepicker form-control {{$errors->has("date_contract_on_first_reestr") ? print("inputError ") : print("")}}' name='date_contract_on_first_reestr' value='{{old("date_contract_on_first_reestr")}}'/>
														@if($errors->has('date_contract_on_first_reestr'))
															<label class='msgError'>{{$errors->first('date_contract_on_first_reestr')}}</label>
														@endif
													</div>
												</div>
												<div class="col-md-4 border-top">
													<div class="form-group">
														<label for='date_signing_contract_reestr'>Дата подписания ФКП "НТИИМ"</label>
														<input id='date_signing_contract_reestr' class='datepicker form-control {{$errors->has("date_signing_contract_reestr") ? print("inputError ") : print("")}}' name='date_signing_contract_reestr' value='{{old("date_signing_contract_reestr")}}'/>
														@if($errors->has('date_signing_contract_reestr'))
															<label class='msgError'>{{$errors->first('date_signing_contract_reestr')}}</label>
														@endif
													</div>
												</div>
												<div class="col-md-4 border-top" style='font-size: 12px;'>
													<div class="form-group">
														<label for='date_control_signing_contract_reestr'>Контрольный срок подписания Дог./Контр.</label>
														<input id='date_control_signing_contract_reestr' class='datepicker form-control {{$errors->has("date_control_signing_contract_reestr") ? print("inputError ") : print("")}}' name='date_control_signing_contract_reestr' value='{{old("date_control_signing_contract_reestr")}}'/>
														@if($errors->has('date_control_signing_contract_reestr'))
															<label class='msgError'>{{$errors->first('date_control_signing_contract_reestr')}}</label>
														@endif
													</div>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-4 border-left border-bottom">
													<div class="form-group">
														<label id='label_date_registration_project_reestr' for='date_registration_project_reestr'>{{ $reestr->application_reestr ? 'Дата регистрации заявки' : 'Дата регистрации проекта'}}</label>
														@if(old('application_reestr'))
															<input id='application_reestr' class='form-check-input' style='float: right;' name='application_reestr' type="checkbox" checked />
														@else
															<input id='application_reestr' class='form-check-input' style='float: right;' name='application_reestr' type="checkbox"/>
														@endif
														<label for='application_reestr' style='float: right; margin-right: 5px;'>Заявка</label>
														<input id='date_registration_project_reestr' class='datepicker form-control {{$errors->has("date_registration_project_reestr") ? print("inputError ") : print("")}}' name='date_registration_project_reestr' value='{{old("date_registration_project_reestr")}}'/>
														@if($errors->has('date_registration_project_reestr'))
															<label class='msgError'>{{$errors->first('date_registration_project_reestr')}}</label>
														@endif
													</div>
												</div>
												<div class="col-md-4 border-bottom">
													<div class="form-group">
														<label for='date_signing_contract_counterpartie_reestr'>Дата подписания Контрагентом</label>
														<input id='date_signing_contract_counterpartie_reestr' class='datepicker form-control {{$errors->has("date_signing_contract_counterpartie_reestr") ? print("inputError ") : print("")}}' name='date_signing_contract_counterpartie_reestr' value='{{old("date_signing_contract_counterpartie_reestr")}}'/>
														@if($errors->has('date_signing_contract_counterpartie_reestr'))
															<label class='msgError'>{{$errors->first('date_signing_contract_counterpartie_reestr')}}</label>
														@endif
													</div>
												</div>
												<div class="col-md-4 border-bottom">
													<div class="form-group">
														<label for='date_entry_into_force_reestr'>Дата вступления Дог./Контр. в силу</label>
														<input id='date_entry_into_force_reestr' class='datepicker form-control {{$errors->has("date_entry_into_force_reestr") ? print("inputError ") : print("")}}' name='date_entry_into_force_reestr' value='{{old("date_entry_into_force_reestr")}}'/>
														@if($errors->has('date_entry_into_force_reestr'))
															<label class='msgError'>{{$errors->first('date_entry_into_force_reestr')}}</label>
														@endif
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class='row'>
												<div class="col-md-6 border-top">
													<div class="form-group">
														<label for='protocols_reestr'>Протоколы</label>
														<input id='protocols_reestr' class='form-control {{$errors->has("protocols_reestr") ? print("inputError ") : print("")}}' name='protocols_reestr' value='{{old("protocols_reestr")}}' readonly />
														@if($errors->has('protocols_reestr'))
															<label class='msgError'>{{$errors->first('protocols_reestr')}}</label>
														@endif
													</div>
												</div>
												<div class="col-md-6 border-top border-right">
													<div class="form-group">
														<label for='add_agreements_reestr'>ДС</label>
														<input id='add_agreements_reestr' class='form-control {{$errors->has("add_agreements_reestr") ? print("inputError ") : print("")}}' name='add_agreements_reestr' value='{{old("add_agreements_reestr")}}' readonly />
														@if($errors->has('add_agreements_reestr'))
															<label class='msgError'>{{$errors->first('add_agreements_reestr')}}</label>
														@endif
													</div>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-6 border-bottom">
													<div class="form-group">
														<label for='sel9'>Согл./Не согл.</label>
														<select id="sel9" class='form-control {{$errors->has("reconciliation_protocol_reestr") ? print("inputError ") : print("")}}' name='reconciliation_protocol_reestr'>
															<option value='0'></option>
															<option value='1'>Согласовано</option>
															<option value='2'>Не согласовано</option>
														</select>
													</div>
												</div>
												<div class="col-md-6 border-bottom border-right">
													<div class="form-group">
														<label for='sel10'>Согл./Не согл.</label>
														<select id="sel10" class='form-control {{$errors->has("reconciliation_agreement_reestr") ? print("inputError ") : print("")}}' name='reconciliation_agreement_reestr'>
															<option value='0'></option>
															<option value='1'>Согласовано</option>
															<option value='2'>Не согласовано</option>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class='row'>
										<div class="col-md-3">
											<div class="form-group">
												<label class='form-check-label' for='break'>ОТКАЗ</label>
												@if(old('renouncement_contract'))
													<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" checked />
												@else
													<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox"/>
												@endif
											</div>
											<div class="form-group">
												<label class='form-check-label' for='archive_contract'>АРХИВ</label>
												@if(old('archive_contract'))
													<input id='archive_contract' class='form-check-input' name='archive_contract' type="checkbox" checked />
												@else
													<input id='archive_contract' class='form-check-input' name='archive_contract' type="checkbox"/>
												@endif
											</div>
										</div>
										<div class="col-md-9 border-right">
											<div class="form-group">
												<label for='document_success_renouncement_reestr'>Документ, подтверждающий отказ</label>
												<input id='document_success_renouncement_reestr' class='form-control {{$errors->has("document_success_renouncement_reestr") ? print("inputError ") : print("")}}' name='document_success_renouncement_reestr' value='{{old("document_success_renouncement_reestr")}}'/>
												@if($errors->has('document_success_renouncement_reestr'))
													<label class='msgError'>{{$errors->first('document_success_renouncement_reestr')}}</label>
												@endif
											</div>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-12 border-right border-bottom">
											<div class="form-group">
												<label for='number_aftair_renouncement_reestr'>№ дела</label>
												<input id='number_aftair_renouncement_reestr' class='form-control {{$errors->has("number_aftair_renouncement_reestr") ? print("inputError ") : print("")}}' name='number_aftair_renouncement_reestr' value='{{old("number_aftair_renouncement_reestr")}}'/>
												@if($errors->has('number_aftair_renouncement_reestr'))
													<label class='msgError'>{{$errors->first('number_aftair_renouncement_reestr')}}</label>
												@endif
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class='row'>
								<div class="col-md-2 border-bottom border-left border-top">
									<div class="form-group">
										<label for="sel3">Вид договора</span></label>
										<select id="sel3" class='form-control {{$errors->has("id_view_contract") ? print("inputError ") : print("")}}' name='id_view_contract'>
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
														<option value='{{$viewContract->id}}'>{{ $viewContract->name_view_contract }}</option>
													@endif
												@endforeach
											@endif
										</select>
										@if($errors->has('id_view_contract'))
											<label class='msgError'>{{$errors->first('id_view_contract')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-2 border-top border-bottom border-right">
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
								<div class="col-md-2 border-top border-bottom border-left">
									<div class="form-group">
										<label for='app_outgoing_number_reestr'>Заявка исх. №</label>
										<input id='app_outgoing_number_reestr' class='form-control {{$errors->has("app_outgoing_number_reestr") ? print("inputError ") : print("")}}' name='app_outgoing_number_reestr' value='{{old("app_outgoing_number_reestr")}}'/>
										@if($errors->has('app_outgoing_number_reestr'))
											<label class='msgError'>{{$errors->first('app_outgoing_number_reestr')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-2 border-top border-bottom border-right">
									<div class="form-group">
										<label for='app_incoming_number_reestr'>Вх. №</label>
										<input id='app_incoming_number_reestr' class='form-control {{$errors->has("app_incoming_number_reestr") ? print("inputError ") : print("")}}' name='app_incoming_number_reestr' value='{{old("app_incoming_number_reestr")}}'/>
										@if($errors->has('app_incoming_number_reestr'))
											<label class='msgError'>{{$errors->first('app_incoming_number_reestr')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-2 border-top border-bottom border-left">
									<div class="form-group">
										<label for='result_second_department_date_reestr'>Заключение отдела №2 Дата</label>
										<input id='result_second_department_date_reestr' class='datepicker form-control {{$errors->has("result_second_department_date_reestr") ? print("inputError ") : print("")}}' name='result_second_department_date_reestr' value='{{old("result_second_department_date_reestr")}}'/>
										@if($errors->has('result_second_department_date_reestr'))
											<label class='msgError'>{{$errors->first('result_second_department_date_reestr')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-2 border-top border-bottom border-right">
									<div class="form-group">
										<label for='result_second_department_number_reestr'>№</label>
										<input id='result_second_department_number_reestr' class='form-control {{$errors->has("result_second_department_number_reestr") ? print("inputError ") : print("")}}' name='result_second_department_number_reestr' value='{{old("result_second_department_number_reestr")}}'/>
										@if($errors->has('result_second_department_number_reestr'))
											<label class='msgError'>{{$errors->first('result_second_department_number_reestr')}}</label>
										@endif
									</div>
								</div>
							</div>
							<div class='row'>
								<input id='is_new_reestr' class='form-check-input' name='is_new_reestr' type="checkbox" checked style='display: none;'/>
								<div class="col-md-5">
									<div class="col-md-3 border-top border-bottom border-left">
										<div class='row'>
											<div class="col-md-12">
												@if(old('marketing_reestr'))
													<input id='marketing_reestr' class='form-check-input' name='marketing_reestr' type="checkbox" checked />
												@else
													<input id='marketing_reestr' class='form-check-input' name='marketing_reestr' type="checkbox" />
												@endif
												<label for='marketing_reestr'>Сбыт</label>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												@if(old('marketing_goz_reestr'))
													<input id='marketing_goz_reestr' class='form-check-input' name='marketing_goz_reestr' type="checkbox" checked />
												@else
													<input id='marketing_goz_reestr' class='form-check-input' name='marketing_goz_reestr' type="checkbox" />
												@endif
												<label for='marketing_goz_reestr'>ГОЗ</label>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												@if(old('participation_reestr'))
													<input id='participation_reestr' class='form-check-input' name='participation_reestr' type="checkbox" checked />
												@else
													<input id='participation_reestr' class='form-check-input' name='participation_reestr' type="checkbox" />
												@endif
												<label for='participation_reestr'>Участие</label>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												@if(old('marketing_fz_223_reestr'))
													<input id='marketing_fz_223_reestr' class='form-check-input' name='marketing_fz_223_reestr' type="checkbox" checked />
												@else
													<input id='marketing_fz_223_reestr' class='form-check-input' name='marketing_fz_223_reestr' type="checkbox" />
												@endif
												<label for='marketing_fz_223_reestr'>223-ФЗ</label>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												@if(old('marketing_fz_44_reestr'))
													<input id='marketing_fz_44_reestr' class='form-check-input' name='marketing_fz_44_reestr' type="checkbox" checked />
												@else
													<input id='marketing_fz_44_reestr' class='form-check-input' name='marketing_fz_44_reestr' type="checkbox" />
												@endif
												<label for='marketing_fz_44_reestr'>44-ФЗ</label>
											</div>
										</div>
									</div>
									<div class="col-md-5 border-top border-bottom">
										<div class='row'>
											<div class="col-md-12">
												@if(old('procurement_reestr'))
													<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" checked />
												@else
													<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" />
												@endif
												<label for='procurement_reestr'>Закуп</label>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												@if(old('single_provider_reestr'))
													<input id='single_provider_reestr' class='form-check-input' name='single_provider_reestr' type="checkbox" checked />
												@else
													<input id='single_provider_reestr' class='form-check-input' name='single_provider_reestr' type="checkbox" />
												@endif
												<label for='single_provider_reestr'>Единственный поставщик</label>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												@if(old('own_funds_reestr'))
													<input id='own_funds_reestr' class='form-check-input' name='own_funds_reestr' type="checkbox" checked />
												@else
													<input id='own_funds_reestr' class='form-check-input' name='own_funds_reestr' type="checkbox" />
												@endif
												<label for='own_funds_reestr'>Собственные средства</label>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												@if(old('investments_reestr'))
													<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" checked />
												@else
													<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" />
												@endif
												<label for='investments_reestr'>Инвестиции</label>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												@if(old('purchase_reestr'))
													<input id='purchase_reestr' class='form-check-input' name='purchase_reestr' type="checkbox" checked />
												@else
													<input id='purchase_reestr' class='form-check-input' name='purchase_reestr' type="checkbox" />
												@endif
												<label for='purchase_reestr'>Закупка у СМСП</label>
											</div>
										</div>
									</div>
									<div class="col-md-4 border-top border-bottom border-right" style='padding-bottom: 33px;'>
										<!--<div class='row'>
											<div class="col-md-12">
												@if(old('procurement_fz_223_reestr'))
													<input id='procurement_fz_223_reestr' class='form-check-input' name='procurement_fz_223_reestr' type="checkbox" checked />
												@else
													<input id='procurement_fz_223_reestr' class='form-check-input' name='procurement_fz_223_reestr' type="checkbox" />
												@endif
												<label for='procurement_fz_223_reestr'>223-ФЗ</label>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												@if(old('procurement_fz_44_reestr'))
													<input id='procurement_fz_44_reestr' class='form-check-input' name='procurement_fz_44_reestr' type="checkbox" checked />
												@else
													<input id='procurement_fz_44_reestr' class='form-check-input' name='procurement_fz_44_reestr' type="checkbox" />
												@endif
												<label for='procurement_fz_44_reestr'>44-ФЗ</label>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												@if(old('procurement_goz_reestr'))
													<input id='procurement_goz_reestr' class='form-check-input' name='procurement_goz_reestr' type="checkbox" checked />
												@else
													<input id='procurement_goz_reestr' class='form-check-input' name='procurement_goz_reestr' type="checkbox" />
												@endif
												<label for='procurement_goz_reestr'>ГОЗ</label>
											</div>
										</div>-->
										<div class='row'>
											<div class="col-md-12">
												@if(old('export_reestr'))
													<input id='export_reestr' class='form-check-input' name='export_reestr' type="checkbox" checked />
												@else
													<input id='export_reestr' class='form-check-input' name='export_reestr' type="checkbox" />
												@endif
												<label for='export_reestr'>Экспорт</label>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												@if(old('interfactory_reestr'))
													<input id='interfactory_reestr' class='form-check-input' name='interfactory_reestr' type="checkbox" checked />
												@else
													<input id='interfactory_reestr' class='form-check-input' name='interfactory_reestr' type="checkbox" />
												@endif
												<label for='interfactory_reestr'>Межзаводские</label>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												@if(old('other_reestr'))
													<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" checked />
												@else
													<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" />
												@endif
												<label for='other_reestr'>Иное</label>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												@if(old('mob_reestr'))
													<input id='mob_reestr' class='form-check-input' name='mob_reestr' type="checkbox" checked />
												@else
													<input id='mob_reestr' class='form-check-input' name='mob_reestr' type="checkbox" />
												@endif
												<label for='mob_reestr'>МОБ</label>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-7 border-all">
									<div class="row" style='text-align: center;'>
										<div class="col-md-12">
											<label>Согласование крупной сделки</label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-2">
											<label for='number_inquiry_reestr'>Запрос №</label>
										</div>
										<div class="col-md-3">
											<input id='number_inquiry_reestr' class='form-control {{$errors->has("number_inquiry_reestr") ? print("inputError ") : print("")}}' name='number_inquiry_reestr' value='{{old("number_inquiry_reestr")}}'/>
											@if($errors->has('number_inquiry_reestr'))
												<label class='msgError'>{{$errors->first('number_inquiry_reestr')}}</label>
											@endif
										</div>
										<div class="col-md-1">
											<label for='date_inquiry_reestr'>от</label>
										</div>
										<div class="col-md-2">
											<input id='date_inquiry_reestr' class='datepicker form-control {{$errors->has("date_inquiry_reestr") ? print("inputError ") : print("")}}' name='date_inquiry_reestr' value='{{old("date_inquiry_reestr")}}'/>
											@if($errors->has('date_inquiry_reestr'))
												<label class='msgError'>{{$errors->first('date_inquiry_reestr')}}</label>
											@endif
										</div>
										<div class="col-md-2">
											<label for='days_reconciliation_reestr'>Срок действия согласования крупной сделки</label>
										</div>
										<div class="col-md-2">
											<input id='days_reconciliation_reestr' class='form-control {{$errors->has("days_reconciliation_reestr") ? print("inputError ") : print("")}}' name='days_reconciliation_reestr' value='{{old("days_reconciliation_reestr")}}'/>
											@if($errors->has('days_reconciliation_reestr'))
												<label class='msgError'>{{$errors->first('days_reconciliation_reestr')}}</label>
											@endif
										</div>
									</div>
									<div class="row">
										<div class="col-md-2">
											<label for='number_answer_reestr'>Ответ №</label>
										</div>
										<div class="col-md-3">
											<input id='number_answer_reestr' class='form-control {{$errors->has("number_answer_reestr") ? print("inputError ") : print("")}}' name='number_answer_reestr' value='{{old("number_answer_reestr")}}'/>
											@if($errors->has('number_answer_reestr'))
												<label class='msgError'>{{$errors->first('number_answer_reestr')}}</label>
											@endif
										</div>
										<div class="col-md-1">
											<label for='date_answer_reestr'>от</label>
										</div>
										<div class="col-md-2">
											<input id='date_answer_reestr' class='datepicker form-control {{$errors->has("date_answer_reestr") ? print("inputError ") : print("")}}' name='date_answer_reestr' value='{{old("date_answer_reestr")}}'/>
											@if($errors->has('date_answer_reestr'))
												<label class='msgError'>{{$errors->first('date_answer_reestr')}}</label>
											@endif
										</div>
										<div class="col-md-2">
											<label for='count_mounth_reestr'>Количество месяцев</label>
										</div>
										<div class="col-md-2">
											<input id='count_mounth_reestr' class='form-control {{$errors->has("count_mounth_reestr") ? print("inputError ") : print("")}}' name='count_mounth_reestr' value='{{old("count_mounth_reestr")}}'/>
											@if($errors->has('count_mounth_reestr'))
												<label class='msgError'>{{$errors->first('count_mounth_reestr')}}</label>
											@endif
										</div>
									</div>
									<div class="row border-top">
										<div class="col-md-2">
											<label>Сроки согласования проекта договора исполнителей</label>
										</div>
										<div class="col-md-3">
											<label for='begin_date_reconciliation_reestr'>Начало согласования (дата)</label>
											<input id='begin_date_reconciliation_reestr' class='datepicker form-control {{$errors->has("begin_date_reconciliation_reestr") ? print("inputError ") : print("")}}' name='begin_date_reconciliation_reestr' value='{{old("begin_date_reconciliation_reestr")}}'/>
											@if($errors->has('begin_date_reconciliation_reestr'))
												<label class='msgError'>{{$errors->first('begin_date_reconciliation_reestr')}}</label>
											@endif
										</div>
										<div class="col-md-3">
											<label for='end_date_reconciliation_reestr'>Окончание согласования (дата)</label>
											<input id='end_date_reconciliation_reestr' class='datepicker form-control {{$errors->has("end_date_reconciliation_reestr") ? print("inputError ") : print("")}}' name='end_date_reconciliation_reestr' value='{{old("end_date_reconciliation_reestr")}}'/>
											@if($errors->has('end_date_reconciliation_reestr'))
												<label class='msgError'>{{$errors->first('end_date_reconciliation_reestr')}}</label>
											@endif
										</div>
										<div class="col-md-4">
											<label for='count_days_reconciliation_reestr'>Общее количество дней согласования</label>
											<input id='count_days_reconciliation_reestr' class='form-control {{$errors->has("count_days_reconciliation_reestr") ? print("inputError ") : print("")}}' name='count_days_reconciliation_reestr' value='{{old("count_days_reconciliation_reestr")}}'/>
											@if($errors->has('count_days_reconciliation_reestr'))
												<label class='msgError'>{{$errors->first('count_days_reconciliation_reestr')}}</label>
											@endif
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for='miniCounterpartie'>Полное наименование контрагента</label>
										<input id='miniCounterpartie' class='form-control {{$errors->has("number_contract") ? print("inputError ") : print("")}}' type='text' value='' readonly />
									</div>
								</div>
								<div class="col-md-3">
									<label>ИНН</label>
									<input class='form-control' value='' readonly />
								</div>
							</div>
							<div class="row border-all">
								<div class="col-md-12">
									<div class='row'>
										<div class="col-md-6">
											<div class="form-group">
												<label for='number_counterpartie_contract_reestr'>№ дог. контрагента</label>
												<input id='number_counterpartie_contract_reestr' class='form-control {{$errors->has("number_counterpartie_contract_reestr") ? print("inputError ") : print("")}}' name='number_counterpartie_contract_reestr' value='{{old("number_counterpartie_contract_reestr")}}'/>
												@if($errors->has('number_counterpartie_contract_reestr'))
													<label class='msgError'>{{$errors->first('number_counterpartie_contract_reestr')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for='igk_reestr'>ИГК</label>
												<input id='igk_reestr' class='form-control {{$errors->has("igk_reestr") ? print("inputError ") : print("")}}' name='igk_reestr' value='{{ old("igk_reestr") }}'/>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for='ikz_reestr'>ИКЗ</label>
												<input id='ikz_reestr' class='form-control {{$errors->has("ikz_reestr") ? print("inputError ") : print("")}}' name='ikz_reestr' value='{{ old("ikz_reestr") }}'/>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3 border-all">
									<div class="row">
										<div class="col-md-12">
											<div class='form-group'>
												<label for='itemContract'>Предмет договора/контракта</label>
												<textarea id='itemContract' class='form-control {{$errors->has("item_contract") ? print("inputError ") : print("")}}' name='item_contract' type="text" style="width: 100%;" rows='4'>{{ old('item_contract') }}</textarea>
												@if($errors->has('item_contract'))
													<label class='msgError'>{{$errors->first('item_contract')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-12">
											<label for='okpd_2_reestr'>ОКПД 2</label>
											<input id='okpd_2_reestr' class='form-control {{$errors->has("okpd_2_reestr") ? print("inputError ") : print("")}}' name='okpd_2_reestr' value='{{ old("okpd_2_reestr") }}'/>
										</div>
										<div class="col-md-12">
											<div class='form-group'>
												<label for='nameWork'>Цель заключения Дог./Контр.</label>
												<textarea id='nameWork' class='form-control {{$errors->has("name_work_contract") ? print("inputError ") : print("")}}' name='name_work_contract' type="text" style="width: 100%;" rows='4'>{{ old('name_work_contract') }}</textarea>
												@if($errors->has('name_work_contract'))
													<label class='msgError'>{{$errors->first('name_work_contract')}}</label>
												@endif
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-9 border-all">
									<div class="row">
										<div class="col-md-12">
											<label>Гарантия банка</label>
										</div>
										<div class="col-md-2">
											<div class='row'>
												<div class="col-md-2">
													<label for='date_bank_reestr'>До</label>
												</div>
												<div class="col-md-10">
													<input id='date_bank_reestr' class='datepicker form-control {{$errors->has("date_bank_reestr") ? print("inputError ") : print("")}}' name='date_bank_reestr' value='{{ old("date_bank_reestr") }}'/>
												</div>
											</div>
										</div>
										<div class="col-md-5">
											<div class='row'>
												<div class="col-md-1">
													<label for='amount_bank_reestr'>Сумма</label>
												</div>
												<div class="col-md-5">
													<input id='amount_bank_reestr' class='form-control check-number {{$errors->has("amount_bank_reestr") ? print("inputError ") : print("")}}' name='amount_bank_reestr' value='{{ old("amount_bank_reestr") }}'/>
												</div>
												<div class="col-md-1">
													<label for='bank_reestr'>Банк</label>
												</div>
												<div class="col-md-5">
													<input id='bank_reestr' class='form-control {{$errors->has("bank_reestr") ? print("inputError ") : print("")}}' name='bank_reestr' value='{{ old("bank_reestr") }}'/>
												</div>
											</div>
										</div>
										<div class='col-md-5'>
											<button class='btn btn-primary' data-toggle="modal" data-target="#scan" type='button' style='float: right; width: 184px;'>Скан</button>
										</div>
									</div>
									<div class="row border-top">
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
									<div class='row border-bottom'>
										<div class="col-md-10">
											<div class="form-group">
												<input id='date_contract_reestr' class='form-control {{$errors->has("date_contract_reestr") ? print("inputError ") : print("")}}' name='date_contract_reestr' value='{{ old("date_contract_reestr") }}'/>
											</div>
										</div>
									</div>
									<div class="row border-bottom">
										<div class="col-md-10">
											<div class="form-group">
												<label for='date_maturity_reestr'>Срок исполнения обязательств</label>
												<input id='date_maturity_reestr' class='form-control {{$errors->has("date_maturity_reestr") ? print("inputError ") : print("")}}' name='date_maturity_reestr' value='{{ old("date_maturity_reestr") }}'/>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-10">
											<div class='row'>
												<div class="col-md-1">
													<label for='amount_reestr'>Сумма</label>
												</div>
												<div class="col-md-2">
													<input id='amount_reestr' class='form-control check-number {{$errors->has("amount_reestr") ? print("inputError ") : print("")}}' name='amount_reestr' value='{{old("amount_reestr") }}'/>
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
												<div class="col-md-3">
													<label for='approximate_amount_reestr'>Ориентировочная</label>
													@if(old('approximate_amount_reestr'))
														<input id='approximate_amount_reestr' class='form-check-input' name='approximate_amount_reestr' type="checkbox" checked />
													@else
														<input id='approximate_amount_reestr' class='form-check-input' name='approximate_amount_reestr' type="checkbox"/>
													@endif
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													<input class='form-control {{$errors->has("amount_comment_reestr") ? print("inputError ") : print("")}}' name='amount_comment_reestr' value='{{old("amount_comment_reestr") }}'/>
												</div>
											</div>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-6">
											<div class='row'>
												<div class="col-md-2">
													<label for='nmcd_reestr'>НМЦД/НМЦК</label>
												</div>
												<div class="col-md-3">
													<input id='nmcd_reestr' class='form-control check-number {{$errors->has("nmcd_reestr") ? print("inputError ") : print("")}}' name='nmcd_reestr' value='{{old("nmcd_reestr") }}'/>
												</div>
												<div class="col-md-3">
													<select class='form-control {{$errors->has("nmcd_unit_reestr") ? print("inputError ") : print("")}}' name='nmcd_unit_reestr'>
														<option></option>
														@foreach($units as $unit)
															@if(old('nmcd_unit_reestr'))
																<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
															@else
																<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
															@endif
														@endforeach
													</select>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class='row'>
												<div class="col-md-2">
													<label for='economy_reestr'>Экономия</label>
												</div>
												<div class="col-md-3">
													<input id='economy_reestr' class='form-control check-number {{$errors->has("economy_reestr") ? print("inputError ") : print("")}}' name='economy_reestr' value='{{old("economy_reestr") }}'/>
												</div>
												<div class="col-md-3">
													<select class='form-control {{$errors->has("economy_unit_reestr") ? print("inputError ") : print("")}}' name='economy_unit_reestr'>
														<option></option>
														@foreach($units as $unit)
															@if(old('economy_unit_reestr'))
																<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
															@else
																<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
															@endif
														@endforeach
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-10">
											<label>Порядок оплаты</label>
											<div class='row'>
												<div class="col-md-6">
													<label>Аванс</label>
													<textarea id='prepayment_order_reestr' class='form-control' type="text" style="width: 100%;" rows='2' name='prepayment_order_reestr'>{{old("prepayment_order_reestr") }}</textarea>
												</div>
												<div class="col-md-6">
													<label>Окончат. расчет</label>
													<textarea id='score_order_reestr' class='form-control' type="text" style="width: 100%;" rows='2' name='score_order_reestr'>{{old("score_order_reestr") }}</textarea>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													<label>Иное</label>
													<textarea id='payment_order_reestr' class='form-control' type="text" style="width: 100%;" rows='2' name='payment_order_reestr'>{{old("payment_order_reestr") }}</textarea>
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
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									
								</div>
								<div class="col-md-2">
									
								</div>
								<div class="col-md-2">
									
								</div>
								<div class="col-md-3">
									
								</div>
								<div class="col-md-3">
									@if(Auth::User()->hasRole()->role != 'Администрация')
										<button type='submit' class="btn btn-primary" style="float: right;">Сохранить договор</button>
									@endif
								</div>
							</div>
							<div class="row">
							</div>
							<!-- Модальное окно резолюции -->
							<div class="modal fade" id="scan" tabindex="-1" role="dialog" aria-labelledby="scanModalLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
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
													<input id='new_file_resolution' type='file' name='new_file_resolution' value='D:\'/>
												</div>
												<div class='col-md-6'>
													<input id='date_resolution' name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
												</div>
											</div>
											<div class='row'>
												<div class='col-md-12'>
													<label>Наименование резолюции</label>
													<input id='real_name_resolution' type='text' name='real_name_resolution' class='form-control'/>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-primary" data-dismiss="modal">Принять</button>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				@endif
				<!-- Модальное окно выбора контрагента -->
				<div class="modal fade" id="chose_counterpartie" tabindex="-1" role="dialog" aria-labelledby="choseCounterpartieModalLabel" aria-hidden="true" attr-open-modal="@if(\Session::has('search_counterparties')){{print('open')}}@endif">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<form method='POST' action="{{route('department.reestr.search_counterpartie')}}">
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="choseCounterpartieModalLabel">Выбор контрагента</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									@includeif('layouts.search', ['search_arr_value'=>['name'=>'Контрагент','name_full'=>'Полное наименование','inn'=>'ИНН']])
									@if(\Session::has('search_counterparties'))
										<div class="row">
											<div class="col-md-12">
												<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
													<thead>
														<tr>
															<th>Контрагент</th>
															<th>Полное наименование</th>
															<th>Выбрать</th>
														</tr>
													</thead>
													<tbody>
														@foreach(\Session::get('search_counterparties') as $counterpartie)
															<tr class='rowsContract'>
																<td>{{$counterpartie->name}}</td>
																<td>{{$counterpartie->name_full}}</td>
																<td><button type='button' class='btn btn-primary chose-counterpartie' type='button' id_counterpartie='{{$counterpartie->id}}'>Выбрать</button></td>
															</tr>
														@endforeach
													</tbody>
												</table>
											</div>
										</div>
									@endif
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<script>
					if($('#chose_counterpartie').attr('attr-open-modal') == 'open1')
						$('#chose_counterpartie').modal('show');
				</script>
			@else
				@if($contract)
					<!-- ПРОСМОТР РЕЕСТРА! -->
					<div class="content">
						<form method='POST' action="{{route('department.ekonomic.update_reestr', $contract->id)}}">
							{{csrf_field()}}
							<div class="row">
								<div class="col-md-2">
									<label>Контрагент</label>
									<div class="form-group">
										<select id="sel4" class='form-control select_counterpartie_reestr {{$errors->has("id_counterpartie_contract") ? print("inputError ") : print("")}}' name='id_counterpartie_contract' disabled >
											<option></option>
											@if($counterparties)
												@foreach($counterparties as $counterpartie)
													@if(old('id_counterpartie_contract'))
														@if(old('id_counterpartie_contract') == $counterpartie->id)
															<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}' selected>{{ $counterpartie->name }}</option>
														@else
															@if(Auth::User()->hasRole()->role == 'Администратор')
																<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
															@elseif(Auth::User()->hasRole()->role == 'Отдел управления договорами')
																<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
															@else
																@if($counterpartie->is_sip_counterpartie == '1')
																	<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
																@endif
															@endif													
														@endif
													@else
														@if($contract->id_counterpartie_contract == $counterpartie->id)
															<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}' selected>{{ $counterpartie->name }}</option>
														@else
															@if(Auth::User()->hasRole()->role == 'Администратор')
																<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
															@elseif(Auth::User()->hasRole()->role == 'Отдел управления договорами')
																<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
															@else
																@if($counterpartie->is_sip_counterpartie == '1')
																	<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
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
								<div class="col-md-1">
										<div class="form-group">
											<button type='button' class="btn btn-primary" data-toggle="modal" data-target="#chose_counterpartie" style='margin-top: 27px;' disabled>Выбрать</button>
										</div>
									</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>Внимание!</label>
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
								</div>
								<div class="col-md-3">
									<div class="col-md-7">
										<div class="form-group">
											<label for='numberContract'>Номер договора</label>
											<input id='numberContract' class='form-control {{$errors->has("number_contract") ? print("inputError ") : print("")}}' name='number_contract' type='text' value='{{old("number_contract") ? old("number_contract") : $contract->number_contract}}' readonly />
											@if($errors->has('number_contract'))
												<label class='msgError'>{{$errors->first('number_contract')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-5" style='text-align: center;'>
										<div class='form-group' style='margin-top: 27px;'>
											@if(isset($prev_contract))
												<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $prev_contract)}}" title='Назад' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
											@else
												<button class='btn btn-primary' title='Назад' disabled type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
											@endif
											@if(isset($next_contract))
												<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $next_contract)}}" title='Вперед' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
											@else
												<button class='btn btn-primary' title='Вперед' disabled type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
											@endif
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<label for='amount_contract_reestr'>Сумма (окончательная)</label>
									<input id='amount_contract_reestr' class='form-control check-number' name='amount_contract_reestr' type='text' value='{{old("amount_contract_reestr") ? old("amount_contract_reestr") : $reestr->amount_contract_reestr}}' readonly />
								</div>
								<div class="col-md-2">
									<label for='amount_invoice_reestr'>Сумма по счетам</label>
									<input id='amount_invoice_reestr' class='form-control check-number' name='amount_invoice_reestr' type='text' value='{{$reestr->amount_invoice_reestr}}' disabled />
								</div>
							</div>
							<div class="row">
								<div class="col-md-1">
									<div class="form-group">
										<label for='number_pp'>№ п/п</label>
										<input id='number_pp' class='change_contract_number form-control {{$errors->has("number_pp") ? print("inputError ") : print("")}}' name='number_pp' type='text' value='{{old("number_pp") ? old("number_pp") : (strlen($contract->number_contract) > 0 ? explode("‐",$contract->number_contract)[0] : "")}}' readonly />
										@if($errors->has('number_pp'))
											<label class='msgError'>{{$errors->first('number_pp')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-1">
									<div class="form-group">
										<label for='index_dep'>Индекс подразд.</label>
										<select id='index_dep' class='change_contract_number form-control {{$errors->has("index_dep") ? print("inputError ") : print("")}}' name='index_dep' type='text' value='{{old("index_dep")}}' disabled >
											@if(old('index_dep'))
												<option>{{old('index_dep')}}</option>
											@endif
											<option></option>
											@foreach($departments as $department)
												@if(count(explode("‐",$contract->number_contract))>1)
													@if(explode("‐",$contract->number_contract)[1] == $department->index_department)
														<option value='{{$department->index_department}}' selected>{{$department->index_department}} {{$department->name_department}}</option>
													@else
														<option value='{{$department->index_department}}'>{{$department->index_department}} {{$department->name_department}}</option>
													@endif
												@else
													<option value='{{$department->index_department}}'>{{$department->index_department}} {{$department->name_department}}</option>
												@endif
											@endforeach
										</select>
										@if($errors->has('index_dep'))
											<label class='msgError'>{{$errors->first('index_dep')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-1">
									<div class="form-group">
										<label for='year_contract'>Год</label>
										<input id='year_contract' class='change_contract_number form-control {{$errors->has("year_contract") ? print("inputError ") : print("")}}' name='year_contract' type='text' value='{{old("year_contract") ? old("year_contract") : $contract->year_contract}}' readonly />
										@if($errors->has('year_contract'))
											<label class='msgError'>{{$errors->first('year_contract')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-2">
									<label for='executor_contract_reestr'>Исполнитель по Дог./Контр.</label>
									<input id='executor_contract_reestr' class='form-control' name='executor_contract_reestr' type='text' value='{{old("executor_contract_reestr") ? old("executor_contract_reestr") : $reestr->executor_contract_reestr}}' readonly />
								</div>
								<div class="col-md-2">
									<label for='executor_reestr'>Исполнитель ОУД</label>
									<select class='form-control' name='executor_reestr' disabled >
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
								<div class="col-md-2">
									<label for='date_save_contract_reestr'>Дата сдачи Дог./Контр. на хранение</label>
									<input id='date_save_contract_reestr' class='form-control {{$errors->has("date_save_contract_reestr") ? print("inputError ") : print("")}}' name='date_save_contract_reestr' value='{{old("date_save_contract_reestr") ? old("date_save_contract_reestr") : $reestr->date_save_contract_reestr}}' readonly />
									@if($errors->has('date_save_contract_reestr'))
										<label class='msgError'>{{$errors->first('date_save_contract_reestr')}}</label>
									@endif
								</div>
								<div class="col-md-2">
									<label for='place_save_contract_reestr'>Место хранения</label>
									<input id='place_save_contract_reestr' class='form-control {{$errors->has("place_save_contract_reestr") ? print("inputError ") : print("")}}' name='place_save_contract_reestr' value='{{old("place_save_contract_reestr") ? old("place_save_contract_reestr") : $reestr->place_save_contract_reestr}}' readonly />
									@if($errors->has('place_save_contract'))
										<label class='msgError'>{{$errors->first('place_save_contract')}}</label>
									@endif
								</div>
								<div class="col-md-1">
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
							</div>
							<div class='row'>
								<div class="col-md-9">
									<div class='row'>
										<div class="col-md-9">
											<div class='row'>
												<div class="col-md-4">
													<label for='date_contract_on_first_reestr'>Дата Дог./Контр. на 1 л.</label>
													<input id='date_contract_on_first_reestr' class='form-control {{$errors->has("date_contract_on_first_reestr") ? print("inputError ") : print("")}}' name='date_contract_on_first_reestr' value='{{old("date_contract_on_first_reestr") ? old("date_contract_on_first_reestr") : $reestr->date_contract_on_first_reestr}}' readonly />
													@if($errors->has('date_contract_on_first_reestr'))
														<label class='msgError'>{{$errors->first('date_contract_on_first_reestr')}}</label>
													@endif
												</div>
												<div class="col-md-4">
													<label for='date_signing_contract_reestr'>Дата подписания ФКП "НТИИМ"</label>
													<input id='date_signing_contract_reestr' class='form-control {{$errors->has("date_signing_contract_reestr") ? print("inputError ") : print("")}}' name='date_signing_contract_reestr' value='{{old("date_signing_contract_reestr") ? old("date_signing_contract_reestr") : $reestr->date_signing_contract_reestr}}' readonly />
													@if($errors->has('date_signing_contract_reestr'))
														<label class='msgError'>{{$errors->first('date_signing_contract_reestr')}}</label>
													@endif
												</div>
												<div class="col-md-4">
													<label for='date_control_signing_contract_reestr'>Контрольный срок подписания Дог./Контр.</label>
													<input id='date_control_signing_contract_reestr' class='form-control {{$errors->has("date_control_signing_contract_reestr") ? print("inputError ") : print("")}}' name='date_control_signing_contract_reestr' value='{{old("date_control_signing_contract_reestr") ? old("date_control_signing_contract_reestr") : $reestr->date_control_signing_contract_reestr}}' readonly />
													@if($errors->has('date_control_signing_contract_reestr'))
														<label class='msgError'>{{$errors->first('date_control_signing_contract_reestr')}}</label>
													@endif
												</div>
											</div>
											<div class='row'>
												<div class="col-md-4">
													<label id='label_date_registration_project_reestr' for='date_registration_project_reestr'>{{ $reestr->application_reestr ? 'Дата регистрации заявки' : 'Дата регистрации проекта'}}</label>
													@if($reestr->application_reestr)
														<input id='application_reestr' class='form-check-input' style='float: right;' name='application_reestr' type="checkbox" checked disabled />
													@else
														<input id='application_reestr' class='form-check-input' style='float: right;' name='application_reestr' type="checkbox" disabled />
													@endif
													<label for='application_reestr' style='float: right; margin-right: 5px;'>Заявка</label>
													<input id='date_registration_project_reestr' class='form-control {{$errors->has("date_registration_project_reestr") ? print("inputError ") : print("")}}' name='date_registration_project_reestr' value='{{old("date_registration_project_reestr") ? old("date_registration_project_reestr") : $reestr->date_registration_project_reestr}}' readonly />
													@if($errors->has('date_registration_project_reestr'))
														<label class='msgError'>{{$errors->first('date_registration_project_reestr')}}</label>
													@endif
												</div>
												<div class="col-md-4">
													<label for='date_signing_contract_counterpartie_reestr'>Дата подписания Контрагентом</label>
													<input id='date_signing_contract_counterpartie_reestr' class='form-control {{$errors->has("date_signing_contract_counterpartie_reestr") ? print("inputError ") : print("")}}' name='date_signing_contract_counterpartie_reestr' value='{{old("date_signing_contract_counterpartie_reestr") ? old("date_signing_contract_counterpartie_reestr") : $reestr->date_signing_contract_counterpartie_reestr}}' readonly />
													@if($errors->has('date_signing_contract_counterpartie_reestr'))
														<label class='msgError'>{{$errors->first('date_signing_contract_counterpartie_reestr')}}</label>
													@endif
												</div>
												<div class="col-md-4">
													<label for='date_entry_into_force_reestr'>Дата вступления Дог./Контр. в силу</label>
													<input id='date_entry_into_force_reestr' class='form-control {{$errors->has("date_entry_into_force_reestr") ? print("inputError ") : print("")}}' name='date_entry_into_force_reestr' value='{{old("date_entry_into_force_reestr") ? old("date_entry_into_force_reestr") : $reestr->date_entry_into_force_reestr}}' readonly />
													@if($errors->has('date_entry_into_force_reestr'))
														<label class='msgError'>{{$errors->first('date_entry_into_force_reestr')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class='row'>
												<div class="col-md-6">
													<label for='protocols_reestr'>Протоколы</label>
													<input id='protocols_reestr' class='form-control {{$errors->has("protocols_reestr") ? print("inputError ") : print("")}}' name='protocols_reestr' value='{{$big_date_protocol != null ? $big_date_protocol : ""}}' readonly />
												</div>
												<div class="col-md-6">
													<label for='add_agreements_reestr'>ДС</label>
													<input id='add_agreements_reestr' class='form-control {{$errors->has("add_agreements_reestr") ? print("inputError ") : print("")}}' name='add_agreements_reestr' value='{{$big_date_add_agreement != null ? $big_date_add_agreement : ""}}' readonly />
												</div>
											</div>
											<div class='row'>
												<div class="col-md-6">
													<label for='sel9'>Согл./Не согл.</label>
													<select id="sel9" class='form-control {{$errors->has("reconciliation_protocol_reestr") ? print("inputError ") : print("")}}' name='reconciliation_protocol_reestr' disabled >
														<option value='0' {{$reestr->reconciliation_protocol_reestr == 0 ? 'selected' : ''}}></option>
														<option value='1' {{$reestr->reconciliation_protocol_reestr == 1 ? 'selected' : ''}}>Согласовано</option>
														<option value='2' {{$reestr->reconciliation_protocol_reestr == 2 ? 'selected' : ''}}>Не согласовано</option>
													</select>
												</div>
												<div class="col-md-6">
													<label for='sel10'>Согл./Не согл.</label>
													<select id="sel10" class='form-control {{$errors->has("reconciliation_agreement_reestr") ? print("inputError ") : print("")}}' name='reconciliation_agreement_reestr' disabled >
														<option value='0' {{$reestr->reconciliation_agreement_reestr == 0 ? 'selected' : ''}}></option>
														<option value='1' {{$reestr->reconciliation_agreement_reestr == 1 ? 'selected' : ''}}>Согласовано</option>
														<option value='2' {{$reestr->reconciliation_agreement_reestr == 2 ? 'selected' : ''}}>Не согласовано</option>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class='row'>
										<div class="col-md-3">
											<div class="form-group">
											<label class='form-check-label' for='break'>ОТКАЗ</label>
												@if($contract->renouncement_contract == 1)
													<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" checked disabled />
												@else
													<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" disabled />
												@endif
											</div>
											<div class="form-group">
												<label class='form-check-label' for='archive_contract'>АРХИВ</label>
												@if($contract->archive_contract == 1)
													<input id='archive_contract' class='form-check-input' name='archive_contract' type="checkbox" checked disabled />
												@else
													<input id='archive_contract' class='form-check-input' name='archive_contract' type="checkbox" disabled />
												@endif
											</div>
										</div>
										<div class="col-md-9">
											<label for='document_success_renouncement_reestr'>Документ, подтверждающий отказ</label>
											<input id='document_success_renouncement_reestr' class='form-control {{$errors->has("document_success_renouncement_reestr") ? print("inputError ") : print("")}}' name='document_success_renouncement_reestr' value='{{old("document_success_renouncement_reestr") ? old("document_success_renouncement_reestr") : $contract->document_success_renouncement_reestr}}' readonly />
											@if($errors->has('document_success_renouncement_reestr'))
												<label class='msgError'>{{$errors->first('document_success_renouncement_reestr')}}</label>
											@endif
										</div>
									</div>
									<div class='row'>
										<div class="col-md-12">
											<label for='number_aftair_renouncement_reestr'>№ дела</label>
											<input id='number_aftair_renouncement_reestr' class='form-control {{$errors->has("number_aftair_renouncement_reestr") ? print("inputError ") : print("")}}' name='number_aftair_renouncement_reestr' value='{{old("number_aftair_renouncement_reestr") ? old("number_aftair_renouncement_reestr") : $contract->number_aftair_renouncement_reestr}}' readonly />
											@if($errors->has('number_aftair_renouncement_reestr'))
												<label class='msgError'>{{$errors->first('number_aftair_renouncement_reestr')}}</label>
											@endif
										</div>
									</div>
								</div>
							</div>
							<div class='row'>
								<div class="col-md-2">
									<div class="form-group">
										<label for="sel3">Вид договора</span></label>
										<select id="sel3" class='form-control {{$errors->has("id_view_contract") ? print("inputError ") : print("")}}' name='id_view_contract' disabled >
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
														@if($reestr->id_view_contract == $viewContract->id)
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
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="sel5">Отбор поставщика</span></label>
										<select id="sel5" class='form-control {{$errors->has("selection_supplier_reestr") ? print("inputError ") : print("")}}' name='selection_supplier_reestr' disabled >
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
								<div class="col-md-2">
									<label for='app_outgoing_number_reestr'>Заявка исх. №</label>
									<input id='app_outgoing_number_reestr' class='form-control {{$errors->has("app_outgoing_number_reestr") ? print("inputError ") : print("")}}' name='app_outgoing_number_reestr' value='{{old("app_outgoing_number_reestr") ? old("app_outgoing_number_reestr") : $reestr->app_outgoing_number_reestr}}' readonly />
									@if($errors->has('app_outgoing_number_reestr'))
										<label class='msgError'>{{$errors->first('app_outgoing_number_reestr')}}</label>
									@endif
								</div>
								<div class="col-md-2">
									<label for='app_incoming_number_reestr'>Вх. №</label>
									<input id='app_incoming_number_reestr' class='form-control {{$errors->has("app_incoming_number_reestr") ? print("inputError ") : print("")}}' name='app_incoming_number_reestr' value='{{old("app_incoming_number_reestr") ? old("app_incoming_number_reestr") : $reestr->app_incoming_number_reestr}}' readonly />
									@if($errors->has('app_incoming_number_reestr'))
										<label class='msgError'>{{$errors->first('app_incoming_number_reestr')}}</label>
									@endif
								</div>
								<div class="col-md-2">
									<label for='result_second_department_date_reestr'>Заключение отдела №2 Дата</label>
									<input id='result_second_department_date_reestr' class='form-control {{$errors->has("result_second_department_date_reestr") ? print("inputError ") : print("")}}' name='result_second_department_date_reestr' value='{{old("result_second_department_date_reestr") ? old("result_second_department_date_reestr") : $reestr->result_second_department_date_reestr}}' readonly />
									@if($errors->has('result_second_department_date_reestr'))
										<label class='msgError'>{{$errors->first('result_second_department_date_reestr')}}</label>
									@endif
								</div>
								<div class="col-md-2">
									<label for='result_second_department_number_reestr'>№</label>
									<input id='result_second_department_number_reestr' class='form-control {{$errors->has("result_second_department_number_reestr") ? print("inputError ") : print("")}}' name='result_second_department_number_reestr' value='{{old("result_second_department_number_reestr") ? old("result_second_department_number_reestr") : $reestr->result_second_department_number_reestr}}' readonly />
									@if($errors->has('result_second_department_number_reestr'))
										<label class='msgError'>{{$errors->first('result_second_department_number_reestr')}}</label>
									@endif
								</div>
							</div>
							<div class='row'>
								<input id='is_new_reestr' class='form-check-input' name='is_new_reestr' type="checkbox" checked style='display: none;'/>
								<div class="col-md-5">
									<div class="col-md-3">
										<div class='row'>
											@if($reestr->marketing_reestr)
												<input id='marketing_reestr' class='form-check-input' name='marketing_reestr' type="checkbox" checked disabled />
											@else
												<input id='marketing_reestr' class='form-check-input' name='marketing_reestr' type="checkbox" disabled />
											@endif
											<label for='marketing_reestr'>Сбыт</label>
										</div>
										<div class='row'>
											@if($reestr->marketing_goz_reestr)
												<input id='marketing_goz_reestr' class='form-check-input' name='marketing_goz_reestr' type="checkbox" checked disabled />
											@else
												<input id='marketing_goz_reestr' class='form-check-input' name='marketing_goz_reestr' type="checkbox" disabled />
											@endif
											<label for='marketing_goz_reestr'>ГОЗ</label>
										</div>
										<div class='row'>
											@if($reestr->participation_reestr)
												<input id='participation_reestr' class='form-check-input' name='participation_reestr' type="checkbox" checked disabled />
											@else
												<input id='participation_reestr' class='form-check-input' name='participation_reestr' type="checkbox" disabled />
											@endif
											<label for='participation_reestr'>Участие</label>
										</div>
										<div class='row'>
											@if(old('marketing_fz_223_reestr'))
												<input id='marketing_fz_223_reestr' class='form-check-input' name='marketing_fz_223_reestr' type="checkbox" checked disabled />
											@else
												@if($reestr->marketing_fz_223_reestr)
													<input id='marketing_fz_223_reestr' class='form-check-input' name='marketing_fz_223_reestr' type="checkbox" checked disabled />
												@else
													<input id='marketing_fz_223_reestr' class='form-check-input' name='marketing_fz_223_reestr' type="checkbox" disabled />
												@endif
											@endif
											<label for='marketing_fz_223_reestr'>223-ФЗ</label>
										</div>
										<div class='row'>
											@if(old('marketing_fz_44_reestr'))
												<input id='marketing_fz_44_reestr' class='form-check-input' name='marketing_fz_44_reestr' type="checkbox" checked disabled />
											@else
												@if($reestr->marketing_fz_44_reestr)
													<input id='marketing_fz_44_reestr' class='form-check-input' name='marketing_fz_44_reestr' type="checkbox" checked disabled />
												@else
													<input id='marketing_fz_44_reestr' class='form-check-input' name='marketing_fz_44_reestr' type="checkbox" disabled />
												@endif
											@endif
											<label for='marketing_fz_44_reestr'>44-ФЗ</label>
										</div>
									</div>
									<div class="col-md-5">
										<div class='row'>
											@if(old('procurement_reestr'))
												<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" checked disabled />
											@else
												@if($reestr->procurement_reestr)
													<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" checked disabled />
												@else
													<input id='procurement_reestr' class='form-check-input' name='procurement_reestr' type="checkbox" disabled />
												@endif
											@endif
											<label for='procurement_reestr'>Закуп</label>
										</div>
										<div class='row'>
											@if(old('single_provider_reestr'))
												<input id='single_provider_reestr' class='form-check-input' name='single_provider_reestr' type="checkbox" checked disabled />
											@else
												@if($reestr->single_provider_reestr)
													<input id='single_provider_reestr' class='form-check-input' name='single_provider_reestr' type="checkbox" checked disabled />
												@else
													<input id='single_provider_reestr' class='form-check-input' name='single_provider_reestr' type="checkbox" disabled />
												@endif
											@endif
											<label for='single_provider_reestr'>Единственный поставщик</label>
										</div>
										<div class='row'>
											@if(old('own_funds_reestr'))
												<input id='own_funds_reestr' class='form-check-input' name='own_funds_reestr' type="checkbox" checked disabled />
											@else
												@if($reestr->own_funds_reestr)
													<input id='own_funds_reestr' class='form-check-input' name='own_funds_reestr' type="checkbox" checked disabled />
												@else
													<input id='own_funds_reestr' class='form-check-input' name='own_funds_reestr' type="checkbox" disabled />
												@endif
											@endif
											<label for='own_funds_reestr'>Собственные средства</label>
										</div>
										<div class='row'>
											@if(old('investments_reestr'))
												<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" checked disabled />
											@else
												@if($reestr->investments_reestr)
													<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" checked disabled />
												@else
													<input id='investments_reestr' class='form-check-input' name='investments_reestr' type="checkbox" disabled />
												@endif
											@endif
											<label for='investments_reestr'>Инвестиции</label>
										</div>
										<div class='row'>
											@if(old('purchase_reestr'))
												<input id='purchase_reestr' class='form-check-input' name='purchase_reestr' type="checkbox" checked disabled />
											@else
												@if($reestr->purchase_reestr)
													<input id='purchase_reestr' class='form-check-input' name='purchase_reestr' type="checkbox" checked disabled />
												@else
													<input id='purchase_reestr' class='form-check-input' name='purchase_reestr' type="checkbox" disabled />
												@endif
											@endif
											<label for='purchase_reestr'>Закупка у СМСП</label>
										</div>
									</div>
									<div class="col-md-4">
										<!--<div class='row'>
											@if(old('procurement_fz_223_reestr'))
												<input id='procurement_fz_223_reestr' class='form-check-input' name='procurement_fz_223_reestr' type="checkbox" checked disabled />
											@else
												@if($reestr->procurement_fz_223_reestr)
													<input id='procurement_fz_223_reestr' class='form-check-input' name='procurement_fz_223_reestr' type="checkbox" checked disabled />
												@else
													<input id='procurement_fz_223_reestr' class='form-check-input' name='procurement_fz_223_reestr' type="checkbox" disabled />
												@endif
											@endif
											<label for='procurement_fz_223_reestr'>223-ФЗ</label>
										</div>
										<div class='row'>
											@if(old('procurement_fz_44_reestr'))
												<input id='procurement_fz_44_reestr' class='form-check-input' name='procurement_fz_44_reestr' type="checkbox" checked disabled />
											@else
												@if($reestr->procurement_fz_44_reestr)
													<input id='procurement_fz_44_reestr' class='form-check-input' name='procurement_fz_44_reestr' type="checkbox" checked disabled />
												@else
													<input id='procurement_fz_44_reestr' class='form-check-input' name='procurement_fz_44_reestr' type="checkbox" disabled />
												@endif
											@endif
											<label for='procurement_fz_44_reestr'>44-ФЗ</label>
										</div>
										<div class='row'>
											@if(old('procurement_goz_reestr'))
												<input id='procurement_goz_reestr' class='form-check-input' name='procurement_goz_reestr' type="checkbox" checked disabled />
											@else
												@if($reestr->procurement_goz_reestr)
													<input id='procurement_goz_reestr' class='form-check-input' name='procurement_goz_reestr' type="checkbox" checked disabled />
												@else
													<input id='procurement_goz_reestr' class='form-check-input' name='procurement_goz_reestr' type="checkbox" disabled />
												@endif
											@endif
											<label for='procurement_goz_reestr'>ГОЗ</label>
										</div>-->
										<div class='row'>
											<div class="col-md-12">
												@if(old('export_reestr'))
													<input id='export_reestr' class='form-check-input' name='export_reestr' type="checkbox" checked disabled />
												@else
													@if($reestr->export_reestr)
														<input id='export_reestr' class='form-check-input' name='export_reestr' type="checkbox" checked disabled />
													@else
														<input id='export_reestr' class='form-check-input' name='export_reestr' type="checkbox" disabled />
													@endif
												@endif
												<label for='export_reestr'>Экспорт</label>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												@if(old('interfactory_reestr'))
													<input id='interfactory_reestr' class='form-check-input' name='interfactory_reestr' type="checkbox" checked disabled />
												@else
													@if($reestr->interfactory_reestr)
														<input id='interfactory_reestr' class='form-check-input' name='interfactory_reestr' type="checkbox" checked disabled />
													@else
														<input id='interfactory_reestr' class='form-check-input' name='interfactory_reestr' type="checkbox" disabled />
													@endif
												@endif
												<label for='interfactory_reestr'>Межзаводские</label>
											</div>
										</div>
										<div class='row'>
											@if(old('other_reestr'))
												<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" checked disabled />
											@else
												@if($reestr->other_reestr)
													<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" checked disabled />
												@else
													<input id='other_reestr' class='form-check-input' name='other_reestr' type="checkbox" disabled />
												@endif
											@endif
											<label for='other_reestr'>Иное</label>
										</div>
										<div class='row'>
											@if(old('mob_reestr'))
												<input id='mob_reestr' class='form-check-input' name='mob_reestr' type="checkbox" checked disabled />
											@else
												@if($reestr->mob_reestr)
													<input id='mob_reestr' class='form-check-input' name='mob_reestr' type="checkbox" checked disabled />
												@else
													<input id='mob_reestr' class='form-check-input' name='mob_reestr' type="checkbox" disabled />
												@endif
											@endif
											<label for='mob_reestr'>МОБ</label>
										</div>
									</div>
								</div>
								<div class="col-md-7">
									<div class="row" style='text-align: center;'>
										<div class="col-md-12">
											<label>Согласование крупной сделки</label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-2">
											<label for='number_inquiry_reestr'>Запрос №</label>
										</div>
										<div class="col-md-3">
											<input id='number_inquiry_reestr' class='form-control {{$errors->has("number_inquiry_reestr") ? print("inputError ") : print("")}}' name='number_inquiry_reestr' value='{{old("number_inquiry_reestr") ? old("number_inquiry_reestr") : $reestr->number_inquiry_reestr}}' readonly />
											@if($errors->has('number_inquiry_reestr'))
												<label class='msgError'>{{$errors->first('number_inquiry_reestr')}}</label>
											@endif
										</div>
										<div class="col-md-1">
											<label for='date_inquiry_reestr'>от</label>
										</div>
										<div class="col-md-2">
											<input id='date_inquiry_reestr' class='form-control {{$errors->has("date_inquiry_reestr") ? print("inputError ") : print("")}}' name='date_inquiry_reestr' value='{{old("date_inquiry_reestr") ? old("date_inquiry_reestr") : $reestr->date_inquiry_reestr}}' readonly />
											@if($errors->has('date_inquiry_reestr'))
												<label class='msgError'>{{$errors->first('date_inquiry_reestr')}}</label>
											@endif
										</div>
										<div class="col-md-2">
											<label for='days_reconciliation_reestr'>Срок действия согласования крупной сделки</label>
										</div>
										<div class="col-md-2">
											<input id='days_reconciliation_reestr' class='form-control {{$errors->has("days_reconciliation_reestr") ? print("inputError ") : print("")}}' name='days_reconciliation_reestr' value='{{old("days_reconciliation_reestr") ? old("days_reconciliation_reestr") : $reestr->days_reconciliation_reestr}}' readonly />
											@if($errors->has('days_reconciliation_reestr'))
												<label class='msgError'>{{$errors->first('days_reconciliation_reestr')}}</label>
											@endif
										</div>
									</div>
									<div class="row">
										<div class="col-md-2">
											<label for='number_answer_reestr'>Ответ №</label>
										</div>
										<div class="col-md-3">
											<input id='number_answer_reestr' class='form-control {{$errors->has("number_answer_reestr") ? print("inputError ") : print("")}}' name='number_answer_reestr' value='{{old("number_answer_reestr") ? old("number_answer_reestr") : $reestr->number_answer_reestr}}' readonly />
											@if($errors->has('number_answer_reestr'))
												<label class='msgError'>{{$errors->first('number_answer_reestr')}}</label>
											@endif
										</div>
										<div class="col-md-1">
											<label for='date_answer_reestr'>от</label>
										</div>
										<div class="col-md-2">
											<input id='date_answer_reestr' class='form-control {{$errors->has("date_answer_reestr") ? print("inputError ") : print("")}}' name='date_answer_reestr' value='{{old("date_answer_reestr") ? old("date_answer_reestr") : $reestr->date_answer_reestr}}' readonly />
											@if($errors->has('date_answer_reestr'))
												<label class='msgError'>{{$errors->first('date_answer_reestr')}}</label>
											@endif
										</div>
										<div class="col-md-2">
											<label for='count_mounth_reestr'>Количество месяцев</label>
										</div>
										<div class="col-md-2">
											<input id='count_mounth_reestr' class='form-control {{$errors->has("count_mounth_reestr") ? print("inputError ") : print("")}}' name='count_mounth_reestr' value='{{old("count_mounth_reestr") ? old("count_mounth_reestr") : $reestr->count_mounth_reestr}}' readonly />
											@if($errors->has('count_mounth_reestr'))
												<label class='msgError'>{{$errors->first('count_mounth_reestr')}}</label>
											@endif
										</div>
									</div>
									<div class="row">
										<div class="col-md-2">
											<label>Сроки согласования проекта договора исполнителей</label>
										</div>
										<div class="col-md-3">
											<label for='begin_date_reconciliation_reestr'>Начало согласования (дата)</label>
											<input id='begin_date_reconciliation_reestr' class='form-control {{$errors->has("begin_date_reconciliation_reestr") ? print("inputError ") : print("")}}' name='begin_date_reconciliation_reestr' value='{{old("begin_date_reconciliation_reestr") ? old("begin_date_reconciliation_reestr") : $reestr->begin_date_reconciliation_reestr}}' readonly />
											@if($errors->has('begin_date_reconciliation_reestr'))
												<label class='msgError'>{{$errors->first('begin_date_reconciliation_reestr')}}</label>
											@endif
										</div>
										<div class="col-md-3">
											<label for='end_date_reconciliation_reestr'>Окончание согласования (дата)</label>
											<input id='end_date_reconciliation_reestr' class='form-control {{$errors->has("end_date_reconciliation_reestr") ? print("inputError ") : print("")}}' name='end_date_reconciliation_reestr' value='{{old("end_date_reconciliation_reestr") ? old("end_date_reconciliation_reestr") : $reestr->end_date_reconciliation_reestr}}' readonly />
											@if($errors->has('end_date_reconciliation_reestr'))
												<label class='msgError'>{{$errors->first('end_date_reconciliation_reestr')}}</label>
											@endif
										</div>
										<div class="col-md-4">
											<label for='count_days_reconciliation_reestr'>Общее количество дней согласования</label>
											<input id='count_days_reconciliation_reestr' class='form-control {{$errors->has("count_days_reconciliation_reestr") ? print("inputError ") : print("")}}' name='count_days_reconciliation_reestr' value='{{old("count_days_reconciliation_reestr") ? old("count_days_reconciliation_reestr") : $reestr->count_days_reconciliation_reestr}}' readonly />
											@if($errors->has('count_days_reconciliation_reestr'))
												<label class='msgError'>{{$errors->first('count_days_reconciliation_reestr')}}</label>
											@endif
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for='miniCounterpartie'>Полное наименование контрагента</label>
										<input id='miniCounterpartie' class='form-control {{$errors->has("number_contract") ? print("inputError ") : print("")}}' type='text' value='{{ $contract->name_counterpartie_contract }}' readonly />
									</div>
								</div>
								<div class="col-md-3">
									<label>ИНН</label>
									<input id='inn_counterpartie' class='form-control' value='{{$contract->inn_counterpartie_contract}}' readonly />
								</div>
								@if($contract->id_counterpartie_contract)
									<div class='col-md-3'>
										<button class='btn btn-primary btn-href' type='button' style='float: right; margin-top: 24px;' href="{{route('counterpartie.edit', $contract->id_counterpartie_contract)}}">Информация о контрагенте</button>
									</div>
								@endif
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class='row'>
										<div class="col-md-6">
											<label for='number_counterpartie_contract_reestr'>№ дог. контрагента</label>
											<input id='number_counterpartie_contract_reestr' class='form-control {{$errors->has("number_counterpartie_contract_reestr") ? print("inputError ") : print("")}}' name='number_counterpartie_contract_reestr' value='{{old("number_counterpartie_contract_reestr") ? old("number_counterpartie_contract_reestr") : $reestr->number_counterpartie_contract_reestr}}' readonly />
											@if($errors->has('number_counterpartie_contract_reestr'))
												<label class='msgError'>{{$errors->first('number_counterpartie_contract_reestr')}}</label>
											@endif
										</div>
										<div class="col-md-3">
											<label for='igk_reestr'>ИГК</label>
											<input id='igk_reestr' class='form-control {{$errors->has("igk_reestr") ? print("inputError ") : print("")}}' name='igk_reestr' value='{{ old("igk_reestr") ? old("igk_reestr") : $reestr->igk_reestr }}' readonly />
										</div>
										<div class="col-md-3">
											<label for='ikz_reestr'>ИКЗ</label>
											<input id='ikz_reestr' class='form-control {{$errors->has("ikz_reestr") ? print("inputError ") : print("")}}' name='ikz_reestr' value='{{ old("ikz_reestr") ? old("ikz_reestr") : $reestr->ikz_reestr }}' readonly />
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="row">
										<div class="col-md-12">
											<div class='form-group'>
												<label for='itemContract'>Предмет договора/контракта</label>
												<textarea id='itemContract' class='form-control {{$errors->has("item_contract") ? print("inputError ") : print("")}}' name='item_contract' type="text" style="width: 100%;" rows='4' readonly>{{ old('item_contract') ? old("item_contract") : $reestr->item_contract }}</textarea>
												@if($errors->has('item_contract'))
													<label class='msgError'>{{$errors->first('item_contract')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-12">
											<label for='okpd_2_reestr'>ОКПД 2</label>
											<input id='okpd_2_reestr' class='form-control {{$errors->has("okpd_2_reestr") ? print("inputError ") : print("")}}' name='okpd_2_reestr' value='{{ old("okpd_2_reestr") ? old("okpd_2_reestr") : $reestr->okpd_2_reestr }}' readonly />
										</div>
										<div class="col-md-12">
											<div class='form-group'>
												<label for='nameWork'>Цель заключения Дог./Контр.</label>
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
													<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%;" rows='5' readonly>@foreach($states as $state){{$state->date_state . '   ' . $state->name_state . '   ' . $state->surname . ' ' . mb_substr($state->name, 0, 1) . '.' . mb_substr($state->patronymic, 0, 1) . '.'}}
													@endforeach
													</textarea>
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
									<div class="row">
										<div class="col-md-4">
										</div>
										<div class="col-md-1">
											@if(isset($prev_contract))
												<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $prev_contract)}}" title='Назад' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
											@else
												<button class='btn btn-primary' title='Назад' disabled type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
											@endif
										</div>
										<div class="col-md-1">
										</div>
										<div class="col-md-1">
											@if(isset($next_contract))
												<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $next_contract)}}" title='Вперед' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
											@else
												<button class='btn btn-primary' title='Вперед' disabled type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
											@endif
										</div>
									</div>
								</div>
								<div class="col-md-9">
									<div class="row">
										<div class="col-md-12">
											<label>Гарантия банка</label>
										</div>
										<div class="col-md-2">
											<div class='row'>
												<div class="col-md-2">
													<label for='date_bank_reestr'>До</label>
												</div>
												<div class="col-md-10">
													<input id='date_bank_reestr' class='form-control {{$errors->has("date_bank_reestr") ? print("inputError ") : print("")}}' name='date_bank_reestr' value='{{ old("date_bank_reestr") ? old("date_bank_reestr") : $reestr->date_bank_reestr }}' readonly />
												</div>
											</div>
										</div>
										<div class="col-md-5">
											<div class='row'>
												<div class="col-md-1">
													<label for='amount_bank_reestr'>Сумма</label>
												</div>
												<div class="col-md-5">
													<input id='amount_bank_reestr' class='form-control check-number {{$errors->has("amount_bank_reestr") ? print("inputError ") : print("")}}' name='amount_bank_reestr' value='{{ old("amount_bank_reestr") ? old("amount_bank_reestr") : $reestr->amount_bank_reestr }}' readonly />
												</div>
												<div class="col-md-1">
													<label for='bank_reestr'>Банк</label>
												</div>
												<div class="col-md-5">
													<input id='bank_reestr' class='form-control {{$errors->has("bank_reestr") ? print("inputError ") : print("")}}' name='bank_reestr' value='{{ old("bank_reestr") ? old("bank_reestr") : $reestr->bank_reestr }}' readonly />
												</div>
											</div>
										</div>
										<div class='col-md-5'>
											<button class='btn btn-primary' data-toggle="modal" data-target="#scan" type='button' style='float: right; width: 184px;'>Сканы</button>
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
													<input id='date_b_contract_reestr' class='form-control {{$errors->has("date_b_contract_reestr") ? print("inputError ") : print("")}}' name='date_b_contract_reestr' value='{{ old("date_b_contract_reestr") ? old("date_b_contract_reestr") : $reestr->date_b_contract_reestr }}' readonly />
												</div>
												<div class="col-md-1">
													<label for='date_e_contract_reestr'>по</label>
												</div>
												<div class="col-md-5">
													<input id='date_e_contract_reestr' class='form-control {{$errors->has("date_e_contract_reestr") ? print("inputError ") : print("")}}' name='date_e_contract_reestr' value='{{ old("date_e_contract_reestr") ? old("date_e_contract_reestr") : $reestr->date_e_contract_reestr }}' readonly />
												</div>
											</div>
										</div>
										<div class='col-md-5'>
											<button class='btn btn-primary btn-href' style='float: right; width: 184px;' type='button' href="{{route('department.reestr.show_obligation', $contract->id)}}">Исполнение Дог./Контр.</button>
											<!--<button class='btn btn-primary btn-href' style='float: right; width: 184px;' type='button' href="{{route('department.reestr.show_protocols', $contract->id)}}">ПР/ПСР/ПУР</button>-->
										</div>
									</div>
									<div class='row'>
										<div class="col-md-10">
											<input id='date_contract_reestr' class='form-control {{$errors->has("date_contract_reestr") ? print("inputError ") : print("")}}' name='date_contract_reestr' value='{{ old("date_contract_reestr") ? old("date_contract_reestr") : $reestr->date_contract_reestr }}' readonly />
										</div>
										<div class='col-md-2'>
											<!--<button class='btn btn-primary btn-href' style='float: right; width: 184px;' type='button' href="{{route('department.reestr.show_additional_agreements', $contract->id)}}">ДС</button>-->
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
													<label for='amount_reestr'>Сумма</label>
												</div>
												<div class="col-md-2">
													<input id='amount_reestr' class='form-control check-number {{$errors->has("amount_reestr") ? print("inputError ") : print("")}}' name='amount_reestr' value='{{old("amount_reestr") ? old("amount_reestr") : $reestr->amount_reestr}}' readonly />
												</div>
												<div class="col-md-2">
													<select id="sel8" class='form-control {{$errors->has("unit_reestr") ? print("inputError ") : print("")}}' name='unit_reestr' disabled >
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
												<div class="col-md-3">
													<label for='approximate_amount_reestr'>Ориентировочная</label>
													@if(old('approximate_amount_reestr'))
														<input id='approximate_amount_reestr' class='form-check-input' name='approximate_amount_reestr' type="checkbox" checked disabled />
													@else
														@if($reestr->approximate_amount_reestr)
															<input id='approximate_amount_reestr' class='form-check-input' name='approximate_amount_reestr' type="checkbox" checked disabled />
														@else
															<input id='approximate_amount_reestr' class='form-check-input' name='approximate_amount_reestr' type="checkbox" disabled />
														@endif
													@endif
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													<input class='form-control {{$errors->has("amount_comment_reestr") ? print("inputError ") : print("")}}' name='amount_comment_reestr' value='{{old("amount_comment_reestr") ? old("amount_comment_reestr") : $reestr->amount_comment_reestr}}' readonly />
												</div>
											</div>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-6">
											<div class='row'>
												<div class="col-md-2">
													<label for='nmcd_reestr'>НМЦД/НМЦК</label>
												</div>
												<div class="col-md-3">
													<input id='nmcd_reestr' class='form-control check-number {{$errors->has("nmcd_reestr") ? print("inputError ") : print("")}}' name='nmcd_reestr' value='{{old("nmcd_reestr") ? old("nmcd_reestr") : $reestr->nmcd_reestr}}' readonly />
												</div>
												<div class="col-md-3">
													<select class='form-control {{$errors->has("nmcd_unit_reestr") ? print("inputError ") : print("")}}' name='nmcd_unit_reestr' disabled>
														<option></option>
														@foreach($units as $unit)
															@if(old('nmcd_unit_reestr'))
																<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
															@else
																@if($reestr->nmcd_unit_reestr == $unit->id)
																	<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
																@else
																	<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
																@endif
															@endif
														@endforeach
													</select>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class='row'>
												<div class="col-md-2">
													<label for='economy_reestr'>Экономия</label>
												</div>
												<div class="col-md-3">
													<input id='economy_reestr' class='form-control check-number {{$errors->has("economy_reestr") ? print("inputError ") : print("")}}' name='economy_reestr' value='{{old("economy_reestr") ? old("economy_reestr") : $reestr->economy_reestr}}' readonly />
												</div>
												<div class="col-md-3">
													<select class='form-control {{$errors->has("economy_unit_reestr") ? print("inputError ") : print("")}}' name='economy_unit_reestr' disabled>
														<option></option>
														@foreach($units as $unit)
															@if(old('economy_unit_reestr'))
																<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
															@else
																@if($reestr->economy_unit_reestr == $unit->id)
																	<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
																@else
																	<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
																@endif
															@endif
														@endforeach
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-10">
											<label>Порядок оплаты</label>
											<div class='row'>
												<div class="col-md-6">
													<label>Аванс</label>
													<textarea id='prepayment_order_reestr' class='form-control' type="text" style="width: 100%;" rows='2' name='prepayment_order_reestr' readonly>{{old("prepayment_order_reestr") ? old("prepayment_order_reestr") : $reestr->prepayment_order_reestr}}</textarea>
												</div>
												<div class="col-md-6">
													<label>Окончат. расчет</label>
													<textarea id='score_order_reestr' class='form-control' type="text" style="width: 100%;" rows='2' name='score_order_reestr' readonly>{{old("score_order_reestr") ? old("score_order_reestr") : $reestr->score_order_reestr}}</textarea>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													<label>Иное</label>
													<textarea id='payment_order_reestr' class='form-control' type="text" style="width: 100%;" rows='2' name='payment_order_reestr' readonly>{{old("payment_order_reestr") ? old("payment_order_reestr") : $reestr->payment_order_reestr}}</textarea>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-2">
											<label for='prolongation_reestr'>Пролонгация</label>
											@if($reestr->prolongation_reestr)
												<input id='prolongation_reestr' class='form-check-input' type="checkbox" name='prolongation_reestr' checked disabled />
											@else
												<input id='prolongation_reestr' class='form-check-input' type="checkbox" name='prolongation_reestr' disabled />
											@endif
										</div>
									</div>
								</div>
							</div>
							<div class="row">
							</div>
						</form>
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
																<tr class='rowsContract' id_state='{{$state->id}}' 
																													name_state='{{$state->name_state}}' 
																													date_state='{{$state->date_state}}' 
																													action_state='{{ route("department.ekonomic.update_state",$contract->id)}}'
																													destroy_state='{{ route("department.ekonomic.destroy_state",$state->id)}}'>
																	<td>{{$state->name_state}}</td>
																	<td>{{$state->date_state}}</td>
																	<td>{{$state->id_user}}</td>
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
															<option value='http://{{$resolution->path_resolution}}' download_href='resolution_download/{{$resolution->id}}' delete_href='{{route("resolution_delete",$resolution->id)}}'>{{$resolution->real_name_resolution}}</option>
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
											<div class="col-md-3">
												@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->surname == 'Бастрыкова')
													<button id='delete_resolution' type='button' class='btn btn-danger' style='width: 122px;'>Удалить скан</button>
												@endif
											</div>
											<div class="col-md-3">
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
												<label>Наименование резолюции</label>
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
				@endif
			@endif
		@else
			<div class="alert alert-danger">
				Необходимо авторизоваться!
			</div>
		@endif
	</div>
	<?php
		if(isset($_GET['time'])){
			$time_load_end = microtime(1);
			$time_load = $time_load_end - $time_load_start;
			dd($time_load);
		}
	?>
@endsection