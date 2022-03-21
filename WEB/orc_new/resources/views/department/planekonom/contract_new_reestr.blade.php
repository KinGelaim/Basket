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
			@if(isset($_GET['isSmallPage']))
				<style>
					.content{
						font-size: 10px;
					}
					.form-control{
						font-size: 12px;
						height: 27px;
					}
					.btn{
						font-size: 12px;
						line-height: 1.2;
					}
					.row{
						margin-top: 0px;
						line-height: 1;
					}
					.col-md-1,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,
					.col-md-7,.col-md-8,.col-md-9,.col-md-10,.col-md-11,.col-md-12{
						padding-left: 10px;
						padding-right: 10px;
					}
					.form-group{
						margin: 0px;
						margin-bottom: 3px;
					}
					.navbar{
						margin-bottom: 2px;
					}
					.border-all, .border-top, .border-bottom, .border-left, .border-right{
						border: none;
					}
				</style>
			@endif
			
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
								if(Auth::User()->surname != 'Бастрыкова' && Auth::User()->surname != 'Филиппова' && Auth::User()->surname != 'Кошарнова' && Auth::User()->surname != 'Едемская' && Auth::User()->surname != 'Гуринова'&& Auth::User()->surname != 'Морозова')
									$is_disabled = 'readonly';
							$is_peo_disabled = false;
							if(count(explode("‐",$contract->number_contract))>1)
								if(Auth::User()->hasRole()->role != 'Планово-экономический отдел' && explode("‐",$contract->number_contract)[1] == '02')
									$is_peo_disabled = true;
						?>
						<div class="content">
							<form method='POST' action="{{route('department.ekonomic.update_reestr', $contract->id)}}">
								{{csrf_field()}}
								<div class="row">
									<div class='col-md-9 border-top border-bottom border-left border-right'>
										<div class='row'>
											<div class="col-md-4">
												<label>Контрагент</label>
												<div class="form-group">
													<select id="sel4" class='form-control select_counterpartie_reestr {{$errors->has("id_counterpartie_contract") ? print("inputError ") : print("")}}' name='id_counterpartie_contract' required {{$is_disabled}}>
														@if($is_disabled == '')
															<option></option>
														@endif
														<option value='{{$contract->id_counterpartie_contract}}' full_name='{{$contract->full_name_counterpartie_contract}}' inn='{{$contract->inn_counterpartie_contract}}' selected>{{$contract->name_counterpartie_contract}}</option>
													</select>
													@if($errors->has('id_counterpartie_contract'))
														<label class='msgError'>{{$errors->first('id_counterpartie_contract')}}</label>
													@endif
												</div>
											</div>
											<div class="col-md-1">
												<div class="form-group">
													<button type='button' data-toggle="modal" data-target="#chose_counterpartie" class="btn btn-primary" style='margin-top: 27px;'>Выбрать</button>
												</div>
											</div>
											<div class="col-md-3">
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
											<div class="col-md-4">
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
															@if(isset($_GET['isSmallPage']))
																<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $prev_contract)}}?isSmallPage=true" title='Назад' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
															@else
																<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $prev_contract)}}" title='Назад' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
															@endif
														@else
															<button class='btn btn-primary' title='Назад' disabled type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
														@endif
														@if(isset($next_contract))
															@if(isset($_GET['isSmallPage']))
																<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $next_contract)}}?isSmallPage=true" title='Вперед' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
															@else
																<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $next_contract)}}" title='Вперед' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
															@endif
														@else
															<button class='btn btn-primary' title='Вперед' disabled type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
														@endif
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class='col-md-3 border-bottom border-right border-top'>
										<div class='row'>
											<div class="col-md-6">
												<div class="form-group">
													<label for='amount_contract_reestr' class='small-text'>Сумма (окончательная)</label>
													<input id='amount_contract_reestr' class='form-control check-number' name='amount_contract_reestr' type='text' value='{{old("amount_contract_reestr") ? old("amount_contract_reestr") : $reestr->amount_contract_reestr}}' {{$is_disabled}}/>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group ">
													<label for='amount_invoice_reestr'>Сумма по счетам</label>
													<input id='amount_invoice_reestr' class='form-control check-number' name='amount_invoice_reestr' type='text' value='{{$reestr->amount_invoice_reestr}}' disabled />
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class='col-md-7'>
										<div class='row'>
											<div class='col-md-6 border-left border-right border-top border-bottom'>
												<div class='row'>
													<div class="col-md-4">
														<div class="form-group">
															<label for='number_pp'>№ п/п</label>
															<input id='number_pp' class='change_contract_number form-control {{$errors->has("number_pp") ? print("inputError ") : print("")}}' name='number_pp' type='text' value='{{old("number_pp") ? old("number_pp") : (strlen($contract->number_contract) > 0 ? explode("‐",$contract->number_contract)[0] : "")}}' required/>
															@if($errors->has('number_pp'))
																<label class='msgError'>{{$errors->first('number_pp')}}</label>
															@endif
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label for='index_dep' style='font-size: 12px;'>Индекс подразд.</label>
															<select id='index_dep' class='change_contract_number form-control {{$errors->has("index_dep") ? print("inputError ") : print("")}}' name='index_dep' type='text' value='{{old("index_dep")}}' required>
																@if(old('index_dep'))
																	<option>{{old('index_dep')}}</option>
																@endif
																<option></option>
																@foreach($departments as $department)
																	@if(count(explode("‐",$contract->number_contract)) > 1)
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
													<div class="col-md-4">
														<div class="form-group">
															<label for='year_contract'>Год</label>
															<input id='year_contract' class='change_contract_number form-control {{$errors->has("year_contract") ? print("inputError ") : print("")}}' name='year_contract' type='text' value='{{old("year_contract") ? old("year_contract") : $contract->year_contract}}' required />
															@if($errors->has('year_contract'))
																<label class='msgError'>{{$errors->first('year_contract')}}</label>
															@endif
														</div>
													</div>
												</div>
											</div>
											<div class='col-md-6 border-left border-right border-top border-bottom'>
												<div class='row'>
													<div class="col-md-6">
														<div class="form-group">
															<label for='executor_contract_reestr' class='small-text'>Исполнитель по Дог./Контр.</label>
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
													<div class="col-md-6">
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
												</div>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-12 border-left border-top border-right border-bottom'>
												<div class='row'>
													<div class="col-md-4">
														<div class="form-group">
															<label for='date_contract_on_first_reestr'>Дата Дог./Контр. на 1 л.</label>
															<input id='date_contract_on_first_reestr' class='datepicker form-control {{$errors->has("date_contract_on_first_reestr") ? print("inputError ") : print("")}}' name='date_contract_on_first_reestr' value='{{old("date_contract_on_first_reestr") ? old("date_contract_on_first_reestr") : $reestr->date_contract_on_first_reestr}}'/>
															@if($errors->has('date_contract_on_first_reestr'))
																<label class='msgError'>{{$errors->first('date_contract_on_first_reestr')}}</label>
															@endif
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label for='date_signing_contract_reestr' style='font-size: 11px;'>Дата подписания ф-л "НТИИМ"(ФКП "НТИИМ")</label>
															<input id='date_signing_contract_reestr' class='datepicker form-control {{$errors->has("date_signing_contract_reestr") ? print("inputError ") : print("")}}' name='date_signing_contract_reestr' value='{{old("date_signing_contract_reestr") ? old("date_signing_contract_reestr") : $reestr->date_signing_contract_reestr}}' {{$is_disabled}}/>
															@if($errors->has('date_signing_contract_reestr'))
																<label class='msgError'>{{$errors->first('date_signing_contract_reestr')}}</label>
															@endif
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label for='date_control_signing_contract_reestr' class='small-text'>Контрольный срок подписания Дог./Контр.</label>
															<input id='date_control_signing_contract_reestr' class='datepicker form-control {{$errors->has("date_control_signing_contract_reestr") ? print("inputError ") : print("")}}' name='date_control_signing_contract_reestr' value='{{old("date_control_signing_contract_reestr") ? old("date_control_signing_contract_reestr") : $reestr->date_control_signing_contract_reestr}}' {{$is_disabled}}/>
															@if($errors->has('date_control_signing_contract_reestr'))
																<label class='msgError'>{{$errors->first('date_control_signing_contract_reestr')}}</label>
															@endif
														</div>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-4">
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
													<div class="col-md-4">
														<div class="form-group">
															<label for='date_signing_contract_counterpartie_reestr'>Дата подписания Контрагентом</label>
															<input id='date_signing_contract_counterpartie_reestr' class='datepicker form-control {{$errors->has("date_signing_contract_counterpartie_reestr") ? print("inputError ") : print("")}}' name='date_signing_contract_counterpartie_reestr' value='{{old("date_signing_contract_counterpartie_reestr") ? old("date_signing_contract_counterpartie_reestr") : $reestr->date_signing_contract_counterpartie_reestr}}' {{$is_disabled}}/>
															@if($errors->has('date_signing_contract_counterpartie_reestr'))
																<label class='msgError'>{{$errors->first('date_signing_contract_counterpartie_reestr')}}</label>
															@endif
														</div>
													</div>
													<div class="col-md-4">
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
										</div>
									</div>
									<div class='col-md-5 border-left border-top border-right border-bottom'>
										<div class='row'>
											<div class="col-md-5">
												<div class="form-group">
													<label for='date_save_contract_reestr' style='font-size: 11px;'>Дата сдачи Д/К на хранение оригинала</label>
													<input id='date_save_contract_reestr' class='datepicker form-control {{$errors->has("date_save_contract_reestr") ? print("inputError ") : print("")}}' name='date_save_contract_reestr' value='{{old("date_save_contract_reestr") ? old("date_save_contract_reestr") : $reestr->date_save_contract_reestr}}'/>
													@if($errors->has('date_save_contract_reestr'))
														<label class='msgError'>{{$errors->first('date_save_contract_reestr')}}</label>
													@endif
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for='place_save_contract_reestr'>Место хранения</label>
													<input id='place_save_contract_reestr' class='form-control {{$errors->has("place_save_contract_reestr") ? print("inputError ") : print("")}}' name='place_save_contract_reestr' value='{{old("place_save_contract_reestr") ? old("place_save_contract_reestr") : $reestr->place_save_contract_reestr}}'/>
													@if($errors->has('place_save_contract'))
														<label class='msgError'>{{$errors->first('place_save_contract')}}</label>
													@endif
												</div>
											</div>
											<div class="col-md-3">
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
											<div class="col-md-5">
												<div class="form-group">
													<label for='date_save_contract_el_reestr' style='font-size: 12px;'>Дата сдачи Д/К на хранение скана</label>
													<input id='date_save_contract_el_reestr' class='datepicker form-control {{$errors->has("date_save_contract_el_reestr") ? print("inputError ") : print("")}}' name='date_save_contract_el_reestr' value='{{old("date_save_contract_el_reestr") ? old("date_save_contract_el_reestr") : $reestr->date_save_contract_el_reestr}}'/>
													@if($errors->has('date_save_contract_el_reestr'))
														<label class='msgError'>{{$errors->first('date_save_contract_el_reestr')}}</label>
													@endif
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for='count_save_contract_reestr'>Срок хранения по</label>
													<input id='count_save_contract_reestr' class='datepicker form-control {{$errors->has("count_save_contract_reestr") ? print("inputError ") : print("")}}' name='count_save_contract_reestr' value='{{old("count_save_contract_reestr") ? old("count_save_contract_reestr") : $reestr->count_save_contract_reestr}}'/>
													@if($errors->has('count_save_contract_reestr'))
														<label class='msgError'>{{$errors->first('count_save_contract_reestr')}}</label>
													@endif
												</div>
											</div>
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
													@if(Auth::User()->hasRole()->role != 'Планово-экономический отдел')
														@if(old('archive_contract'))
															<input id='archive_contract' class='form-check-input' name='archive_contract' type="checkbox" checked {{$is_disabled}} />
														@else
															@if($contract->archive_contract == 1)
																<input id='archive_contract' class='form-check-input' name='archive_contract' type="checkbox" checked {{$is_disabled}} />
															@else
																<input id='archive_contract' class='form-check-input' name='archive_contract' type="checkbox" {{$is_disabled}} />
															@endif
														@endif
													@else
														@if(old('archive_contract'))
															<input id='archive_contract' class='form-check-input' name='archive_contract' type="checkbox" checked disabled />
														@else
															@if($contract->archive_contract == 1)
																<input id='archive_contract' class='form-check-input' name='archive_contract' type="checkbox" checked disabled />
															@else
																<input id='archive_contract' class='form-check-input' name='archive_contract' type="checkbox" disabled />
															@endif
														@endif
													@endif
												</div>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-5">
												<div class="form-group">
													<label for='document_success_renouncement_reestr' class='small-text'>Документ, подтверждающий отказ</label>
													<input id='document_success_renouncement_reestr' class='form-control {{$errors->has("document_success_renouncement_reestr") ? print("inputError ") : print("")}}' name='document_success_renouncement_reestr' value='{{old("document_success_renouncement_reestr") ? old("document_success_renouncement_reestr") : $contract->document_success_renouncement_reestr}}' {{$is_disabled}}/>
													@if($errors->has('document_success_renouncement_reestr'))
														<label class='msgError'>{{$errors->first('document_success_renouncement_reestr')}}</label>
													@endif
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<label for='date_renouncement_contract' class='small-text'>Дата отказа</label>
													<input id='date_renouncement_contract' class='form-control datepicker {{$errors->has("date_renouncement_contract") ? print("inputError ") : print("")}}' name='date_renouncement_contract' value='{{old("date_renouncement_contract") ? old("date_renouncement_contract") : $contract->date_renouncement_contract}}' {{$is_disabled}}/>
													@if($errors->has('date_renouncement_contract'))
														<label class='msgError'>{{$errors->first('date_renouncement_contract')}}</label>
													@endif
												</div>
											</div>
											<div class="col-md-5">
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
								<div class="row">
									<div class='col-md-2 border-left border-top border-right border-bottom'>
										<div class='row'>
											<div class="col-md-12">
												<div class="form-group">
													<label for="sel3">Вид договора</span></label>
													<select id="sel3" class='form-control {{$errors->has("id_view_contract") ? print("inputError ") : print("")}}' name='id_view_contract' required {{$is_disabled}}>
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
										</div>
										<div class='row'>
											<div class="col-md-12">
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
									</div>
									<div class='col-md-4 border-left border-top border-right border-bottom'>
										<div class='row'>
											<div class="col-md-6">
												<div class="form-group">
													<label for="date_registration_application_reestr">Дата регистрации заявки</span></label>
													<input id='date_registration_application_reestr' class='datepicker form-control {{$errors->has("date_registration_application_reestr") ? print("inputError ") : print("")}}' name='date_registration_application_reestr' value='{{old("date_registration_application_reestr") ? old("date_registration_application_reestr") : $reestr->date_registration_application_reestr}}' {{$is_disabled}}/>
													@if($errors->has('date_registration_application_reestr'))
														<label class='msgError'>{{$errors->first('date_registration_application_reestr')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-6">
												<div class="form-group">
													<label for='app_outgoing_number_reestr'>Заявка исх. №</label>
													@if($reestr->app_outgoing_number_reestr == null OR Auth::User()->hasRole()->role == 'Администратор')
														<input id='app_outgoing_number_reestr' class='form-control {{$errors->has("app_outgoing_number_reestr") ? print("inputError ") : print("")}}' name='app_outgoing_number_reestr' value='{{old("app_outgoing_number_reestr") ? old("app_outgoing_number_reestr") : $reestr->app_outgoing_number_reestr}}' />
													@else
														<input id='app_outgoing_number_reestr' class='form-control {{$errors->has("app_outgoing_number_reestr") ? print("inputError ") : print("")}}' value='{{$reestr->app_outgoing_number_reestr}}' readonly />
													@endif
													@if($errors->has('app_outgoing_number_reestr'))
														<label class='msgError'>{{$errors->first('app_outgoing_number_reestr')}}</label>
													@endif
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for='app_incoming_number_reestr'>Вх. №</label>
													@if($reestr->app_incoming_number_reestr == null OR Auth::User()->hasRole()->role == 'Администратор')
														<input id='app_incoming_number_reestr' class='form-control {{$errors->has("app_incoming_number_reestr") ? print("inputError ") : print("")}}' name='app_incoming_number_reestr' value='{{old("app_incoming_number_reestr") ? old("app_incoming_number_reestr") : $reestr->app_incoming_number_reestr}}' />
													@else
														<input id='app_incoming_number_reestr' class='form-control {{$errors->has("app_incoming_number_reestr") ? print("inputError ") : print("")}}' value='{{$reestr->app_incoming_number_reestr}}' readonly />
													@endif
													@if($errors->has('app_incoming_number_reestr'))
														<label class='msgError'>{{$errors->first('app_incoming_number_reestr')}}</label>
													@endif
												</div>
											</div>
										</div>
									</div>
									<div class='col-md-2 border-left border-top border-right border-bottom'>
										<div class='row'>
											<div class="col-md-6">
												<div class="form-group">
													<label for='protocols_reestr'>Протоколы</label>
													<input id='protocols_reestr' class='form-control {{$errors->has("protocols_reestr") ? print("inputError ") : print("")}}' name='protocols_reestr' value='{{$big_date_protocol != null ? $big_date_protocol : ""}}' readonly />
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for='add_agreements_reestr'>ДС</label>
													<input id='add_agreements_reestr' class='form-control {{$errors->has("add_agreements_reestr") ? print("inputError ") : print("")}}' name='add_agreements_reestr' value='{{$big_date_add_agreement != null ? $big_date_add_agreement : ""}}' readonly />
												</div>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-6">
												<div class="form-group">
													<label for='sel9' class='small-text'>Согл./Не согл.</label>
													<select id="sel9" class='form-control {{$errors->has("reconciliation_protocol_reestr") ? print("inputError ") : print("")}}' name='reconciliation_protocol_reestr' {{$is_disabled != '' ? 'disabled' : ''}}>
														<option value='0' {{$reestr->reconciliation_protocol_reestr == 0 ? 'selected' : ''}}></option>
														<option value='1' {{$reestr->reconciliation_protocol_reestr == 1 ? 'selected' : ''}}>Согласовано</option>
														<option value='2' {{$reestr->reconciliation_protocol_reestr == 2 ? 'selected' : ''}}>Не согласовано</option>
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for='sel10' class='small-text'>Согл./Не согл.</label>
													<select id="sel10" class='form-control {{$errors->has("reconciliation_agreement_reestr") ? print("inputError ") : print("")}}' name='reconciliation_agreement_reestr' {{$is_disabled != '' ? 'disabled' : ''}}>
														<option value='0' {{$reestr->reconciliation_agreement_reestr == 0 ? 'selected' : ''}}></option>
														<option value='1' {{$reestr->reconciliation_agreement_reestr == 1 ? 'selected' : ''}}>Согласовано</option>
														<option value='2' {{$reestr->reconciliation_agreement_reestr == 2 ? 'selected' : ''}}>Не согласовано</option>
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class='col-md-2 border-left border-top border-right border-bottom'>
										<div class='row'>
											<div class="col-md-12">
												<div class="form-group">
													<label for='result_second_department_date_reestr'>Заключение отдела №2 Дата</label>
													<input id='result_second_department_date_reestr' class='datepicker form-control {{$errors->has("result_second_department_date_reestr") ? print("inputError ") : print("")}}' name='result_second_department_date_reestr' value='{{old("result_second_department_date_reestr") ? old("result_second_department_date_reestr") : $reestr->result_second_department_date_reestr}}' {{$is_disabled}}/>
													@if($errors->has('result_second_department_date_reestr'))
														<label class='msgError'>{{$errors->first('result_second_department_date_reestr')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<div class="form-group">
													<label for='result_second_department_number_reestr'>№</label>
													<input id='result_second_department_number_reestr' class='form-control {{$errors->has("result_second_department_number_reestr") ? print("inputError ") : print("")}}' name='result_second_department_number_reestr' value='{{old("result_second_department_number_reestr") ? old("result_second_department_number_reestr") : $reestr->result_second_department_number_reestr}}' {{$is_disabled}}/>
													@if($errors->has('result_second_department_number_reestr'))
														<label class='msgError'>{{$errors->first('result_second_department_number_reestr')}}</label>
													@endif
												</div>
											</div>
										</div>
									</div>
									<div class='col-md-2 border-all'>
										<div class='row'>
											<div class="col-md-12">
												<div class="form-group">
													<label for='date_complete_reestr'>Договор исполнен на</label>
													<input id='date_complete_reestr' class='datepicker form-control {{$errors->has("date_complete_reestr") ? print("inputError ") : print("")}}' name='date_complete_reestr' value='{{old("date_complete_reestr") ? old("date_complete_reestr") : $reestr->date_complete_reestr}}' {{$is_disabled}}/>
													@if($errors->has('date_complete_reestr'))
														<label class='msgError'>{{$errors->first('date_complete_reestr')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<div class="form-group">
													<label for='reestr_number_reestr'>Реестровый номер Д/К</label>
													<input id='reestr_number_reestr' class='form-control {{$errors->has("reestr_number_reestr") ? print("inputError ") : print("")}}' name='reestr_number_reestr' value='{{old("reestr_number_reestr") ? old("reestr_number_reestr") : $reestr->reestr_number_reestr}}' maxlength='30' {{$is_disabled}}/>
													@if($errors->has('reestr_number_reestr'))
														<label class='msgError'>{{$errors->first('reestr_number_reestr')}}</label>
													@endif
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class='row'>
									<input id='is_new_reestr' class='form-check-input' name='is_new_reestr' type="checkbox" checked style='display: none;'/>
								</div>
								<div class='row'>
									<div class="col-md-5 border-left border-top border-right border-bottom">
										<div class="col-md-3">
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
										<div class="col-md-5">
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
										<div class="col-md-4" style='padding-bottom: 33px;'>
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
												<label for='days_reconciliation_reestr' class='small-text'>Срок действия согласования крупной сделки</label>
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
												<label for='count_mounth_reestr' class='small-text'>Количество месяцев</label>
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
												<label class='small-text'>Сроки согласования проекта договора исполнителей</label>
											</div>
											<div class="col-md-3">
												<label for='begin_date_reconciliation_reestr' class='small-text'>Начало согласования (дата)</label>
												<input id='begin_date_reconciliation_reestr' class='datepicker form-control {{$errors->has("begin_date_reconciliation_reestr") ? print("inputError ") : print("")}}' name='begin_date_reconciliation_reestr' value='{{old("begin_date_reconciliation_reestr") ? old("begin_date_reconciliation_reestr") : $reestr->begin_date_reconciliation_reestr}}'/>
												@if($errors->has('begin_date_reconciliation_reestr'))
													<label class='msgError'>{{$errors->first('begin_date_reconciliation_reestr')}}</label>
												@endif
											</div>
											<div class="col-md-3">
												<label for='end_date_reconciliation_reestr' style='font-size: 11px;'>Окончание согласования (дата)</label>
												<input id='end_date_reconciliation_reestr' class='datepicker form-control {{$errors->has("end_date_reconciliation_reestr") ? print("inputError ") : print("")}}' name='end_date_reconciliation_reestr' value='{{old("end_date_reconciliation_reestr") ? old("end_date_reconciliation_reestr") : $reestr->end_date_reconciliation_reestr}}'/>
												@if($errors->has('end_date_reconciliation_reestr'))
													<label class='msgError'>{{$errors->first('end_date_reconciliation_reestr')}}</label>
												@endif
											</div>
											<div class="col-md-4">
												<label for='count_days_reconciliation_reestr' class='small-text'>Общее количество дней согласования</label>
												<input id='count_days_reconciliation_reestr' class='form-control {{$errors->has("count_days_reconciliation_reestr") ? print("inputError ") : print("")}}' name='count_days_reconciliation_reestr' value='{{old("count_days_reconciliation_reestr") ? old("count_days_reconciliation_reestr") : $reestr->count_days_reconciliation_reestr}}'/>
												@if($errors->has('count_days_reconciliation_reestr'))
													<label class='msgError'>{{$errors->first('count_days_reconciliation_reestr')}}</label>
												@endif
											</div>
										</div>
										<div class='row'>
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
													@if($is_peo_disabled)
														<textarea class='form-control {{$errors->has("item_contract") ? print("inputError ") : print("")}}' type="text" style="width: 100%;" rows='4' readonly >{{ $contract->item_contract }}</textarea>
													@else
														<textarea id='itemContract' class='form-control {{$errors->has("item_contract") ? print("inputError ") : print("")}}' name='item_contract' type="text" style="width: 100%;" rows='4' >{{ old('item_contract') ? old('item_contract') : $contract->item_contract }}</textarea>
													@endif
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
													<textarea id='nameWork' class='form-control {{$errors->has("name_work_contract") ? print("inputError ") : print("")}}' name='name_work_contract' type="text" style="width: 100%;" rows='4' required {{$is_disabled}} >{{ old('name_work_contract') ? old('name_work_contract') : $contract->name_work_contract }}</textarea>
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
														<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%;" rows='5' cols='2' readonly>@foreach($states as $state){{$state->date_state . '   ' . $state->name_state . '   ' . $state->surname . ' ' . mb_substr($state->name, 0, 1) . '.' . mb_substr($state->patronymic, 0, 1) . '.&#13;&#10;'}}@endforeach</textarea>
													@else
														<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%;" rows='5' readonly></textarea>
													@endif
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<button class="btn btn-primary" data-toggle="modal" data-target="#history_states" type='button'>История состояний</button>
											</div>
											<div class="col-md-6">
												<button class="btn btn-primary" data-toggle="modal" data-target="#work_states" type='button'>Выполнение работ</button>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
											</div>
											<div class="col-md-1">
												<div class='form-group'>
													@if(isset($prev_contract))
														@if(isset($_GET['isSmallPage']))
															<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $prev_contract)}}?isSmallPage=true" title='Назад' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
														@else
															<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $prev_contract)}}" title='Назад' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
														@endif
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
														@if(isset($_GET['isSmallPage']))
															<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $next_contract)}}?isSmallPage=true" title='Вперед' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
														@else
															<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $next_contract)}}" title='Вперед' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
														@endif
													@else
														<button class='btn btn-primary' title='Вперед' disabled type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
													@endif
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-7 border-all">
										<div class="row">
											<div class="col-md-12">
												<label>Обеспечение гарантийных обязательств</label>
											</div>
											<div class="col-md-3">
												<div class='row'>
													<div class="col-md-12">
														<label for='term_action_reestr'>Срок действия</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														<input id='term_action_reestr' class='form-control {{$errors->has("term_action_reestr") ? print("inputError ") : print("")}}' name='term_action_reestr' value='{{ old("term_action_reestr") ? old("term_action_reestr") : $reestr->term_action_reestr }}' {{$is_disabled}}/>
													</div>
												</div>
											</div>
											<div class="col-md-3">
												<div class='row'>
													<div class="col-md-12">
														<label for='date_bank_reestr'>Гарантия банка до</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														<input id='date_bank_reestr' class='form-control {{$errors->has("date_bank_reestr") ? print("inputError ") : print("")}}' name='date_bank_reestr' value='{{ old("date_bank_reestr") ? old("date_bank_reestr") : $reestr->date_bank_reestr }}' {{$is_disabled}}/>
													</div>
												</div>
											</div>
											<div class="col-md-2">
												<div class='row'>
													<div class="col-md-12">
														<label for='amount_bank_reestr'>Сумма</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														<input id='amount_bank_reestr' class='form-control check-number {{$errors->has("amount_bank_reestr") ? print("inputError ") : print("")}}' name='amount_bank_reestr' value='{{ old("amount_bank_reestr") ? old("amount_bank_reestr") : $reestr->amount_bank_reestr }}' {{$is_disabled}}/>
													</div>
												</div>
											</div>
											<div class='col-md-4'>
												<div class='row'>
													<div class="col-md-12">
														<label for='bank_reestr'>Банк</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														<input id='bank_reestr' class='form-control {{$errors->has("bank_reestr") ? print("inputError ") : print("")}}' name='bank_reestr' value='{{ old("bank_reestr") ? old("bank_reestr") : $reestr->bank_reestr }}' {{$is_disabled}}/>
													</div>
												</div>
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
											<div class="col-md-6">
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
														<input id='date_e_contract_reestr' class='form-control {{$errors->has("date_e_contract_reestr") ? print("inputError ") : print("")}}' value='{{ old("date_e_contract_reestr") ? old("date_e_contract_reestr") : $reestr->date_e_contract_reestr }}' readonly {{$is_disabled}}/>
													</div>
												</div>
											</div>
										</div>
										<div class='row border-bottom'>
											<div class="col-md-11">
												<div class="form-group">
													<input id='date_contract_reestr' class='form-control {{$errors->has("date_contract_reestr") ? print("inputError ") : print("")}}' value='{{ old("date_contract_reestr") ? old("date_contract_reestr") : $reestr->date_contract_reestr }}' readonly {{$is_disabled}} spellcheck='true'/>
												</div>
											</div>
											<div class='col-md-1'>
												<button type='button' class='btn btn-primary' data-toggle="modal" data-target="#modal_date_contract_reestr" title='Форма срока действия договора'><!--☼--><span class="ui-icon ui-icon-1-1" style='background-size: 355px; background-position-x: -49px; background-position-y: -179px;'></span></button>
											</div>
										</div>
										<div class="row border-bottom">
											<div class='col-md-12'>
												<div class="form-group">
													<div class='row'>
														<div class="col-md-9">
															<label for='date_maturity_reestr'>Срок исполнения обязательств</label>
														</div>
														<div class='col-md-2'>
															<label for='date_e_maturity_reestr'>До</label>
														</div>
													</div>
													<div class='row'>
														<div class="col-md-9">
															<input id='date_maturity_reestr' class='form-control {{$errors->has("date_maturity_reestr") ? print("inputError ") : print("")}}' value='{{ old("date_maturity_reestr") ? old("date_maturity_reestr") : $reestr->date_maturity_reestr }}' readonly spellcheck='true'/>
														</div>
														<div class='col-md-2'>
															<input id='date_e_maturity_reestr' class='form-control {{$errors->has("date_e_maturity_reestr") ? print("inputError ") : print("")}}' value='{{ old("date_e_maturity_reestr") ? old("date_e_maturity_reestr") : $reestr->date_e_maturity_reestr }}' readonly spellcheck='true'/>
														</div>
														<div class='col-md-1'>
															<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modal_date_maturity_reestr' title='Форма срока исполнения обязательств'><!--☼--><span class="ui-icon ui-icon-1-1" style='background-size: 355px; background-position-x: -49px; background-position-y: -179px;'></span></button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class='row'>
													<div class="col-md-12">
														<label for='amount_begin_reestr'>Цена при заключении Д/К</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-4">
														<input id='amount_begin_reestr' class='form-control check-number {{$errors->has("amount_begin_reestr") ? print("inputError ") : print("")}}' name='amount_begin_reestr' value='{{old("amount_begin_reestr") ? old("amount_begin_reestr") : $reestr->amount_begin_reestr}}' {{$is_disabled}}/>
													</div>
													<div class="col-md-4">
														<select class='form-control {{$errors->has("unit_begin_reestr") ? print("inputError ") : print("")}}' name='unit_begin_reestr' {{$is_disabled != '' ? 'disabled' : ''}}>
															<option></option>
															@foreach($units as $unit)
																@if(old('unit_begin_reestr'))
																	<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
																@else
																	@if($reestr->unit_begin_reestr == $unit->id)
																		<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
																	@else
																		<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
																	@endif
																@endif
															@endforeach
														</select>
													</div>
													<div class='col-md-4'>
														<label for='VAT_BEGIN'>НДС</label>
														@if(old('vat_begin_reestr'))
															<input id='VAT_BEGIN' class='form-check-input' name='vat_begin_reestr' type="checkbox" checked {{$is_disabled != '' ? 'disabled' : ''}}/>
														@else
															@if($reestr->vat_begin_reestr)
																<input id='VAT_BEGIN' class='form-check-input' name='vat_begin_reestr' type="checkbox" checked {{$is_disabled != '' ? 'disabled' : ''}}/>
															@else
																<input id='VAT_BEGIN' class='form-check-input' name='vat_begin_reestr' type="checkbox" {{$is_disabled != '' ? 'disabled' : ''}}/>
															@endif
														@endif
													</div>
												</div>
												<div class='row'>
													<div class="col-md-6">
														<label for='approximate_amount_begin_reestr'>Ориентировочная</label>
														@if(old('approximate_amount_begin_reestr'))
															<input id='approximate_amount_begin_reestr' class='form-check-input' name='approximate_amount_begin_reestr' type="checkbox" checked {{$is_disabled != '' ? 'disabled' : ''}}/>
														@else
															@if($reestr->approximate_amount_begin_reestr)
																<input id='approximate_amount_begin_reestr' class='form-check-input' name='approximate_amount_begin_reestr' type="checkbox" checked {{$is_disabled != '' ? 'disabled' : ''}}/>
															@else
																<input id='approximate_amount_begin_reestr' class='form-check-input' name='approximate_amount_begin_reestr' type="checkbox" {{$is_disabled != '' ? 'disabled' : ''}}/>
															@endif
														@endif
													</div>
													<div class="col-md-6">
														<label for='fixed_amount_begin_reestr'>Фиксированная</label>
														@if(old('fixed_amount_begin_reestr'))
															<input id='fixed_amount_begin_reestr' class='form-check-input' name='fixed_amount_begin_reestr' type="checkbox" checked {{$is_disabled != '' ? 'disabled' : ''}}/>
														@else
															@if($reestr->fixed_amount_begin_reestr)
																<input id='fixed_amount_begin_reestr' class='form-check-input' name='fixed_amount_begin_reestr' type="checkbox" checked {{$is_disabled != '' ? 'disabled' : ''}}/>
															@else
																<input id='fixed_amount_begin_reestr' class='form-check-input' name='fixed_amount_begin_reestr' type="checkbox" {{$is_disabled != '' ? 'disabled' : ''}}/>
															@endif
														@endif
													</div>
												</div>
											</div>
											<div class='col-md-6'>
												<div class='row'>
													<div class="col-md-12">
														<label for='amount_reestr'>Сумма Д/К</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-4">
														<input id='amount_reestr' class='form-control check-number {{$errors->has("amount_reestr") ? print("inputError ") : print("")}}' value='{{old("amount_reestr") ? old("amount_reestr") : $reestr->amount_reestr}}' readonly {{$is_disabled}}/>
													</div>
													<div class="col-md-3">
														<select id="sel8" class='form-control {{$errors->has("unit_reestr") ? print("inputError ") : print("")}}' disabled {{$is_disabled != '' ? 'disabled' : ''}}>
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
													<div class="col-md-3">
														<label for='VAT'>НДС</label>
														@if(old('vat_reestr'))
															<input id='VAT' class='form-check-input' type="checkbox" checked disabled {{$is_disabled != '' ? 'disabled' : ''}}/>
														@else
															@if($reestr->vat_reestr)
																<input id='VAT' class='form-check-input' type="checkbox" checked disabled {{$is_disabled != '' ? 'disabled' : ''}}/>
															@else
																<input id='VAT' class='form-check-input' type="checkbox" disabled {{$is_disabled != '' ? 'disabled' : ''}}/>
															@endif
														@endif
													</div>
													<div class='col-md-2'>
														<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modal_amount_reestr' title='Форма срока исполнения обязательств'><!--☼--><span class="ui-icon ui-icon-1-1" style='background-size: 355px; background-position-x: -49px; background-position-y: -179px;'></span></button>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-6">
														<label for='approximate_amount_reestr'>Ориентировочная</label>
														@if(old('approximate_amount_reestr'))
															<input id='approximate_amount_reestr' class='form-check-input' type="checkbox" checked disabled {{$is_disabled != '' ? 'disabled' : ''}}/>
														@else
															@if($reestr->approximate_amount_reestr)
																<input id='approximate_amount_reestr' class='form-check-input' type="checkbox" checked disabled {{$is_disabled != '' ? 'disabled' : ''}}/>
															@else
																<input id='approximate_amount_reestr' class='form-check-input' type="checkbox" disabled {{$is_disabled != '' ? 'disabled' : ''}}/>
															@endif
														@endif
													</div>
													<div class="col-md-6">
														<label for='fixed_amount_reestr'>Фиксированная</label>
														@if(old('fixed_amount_reestr'))
															<input id='fixed_amount_reestr' class='form-check-input' type="checkbox" checked disabled {{$is_disabled != '' ? 'disabled' : ''}}/>
														@else
															@if($reestr->fixed_amount_reestr)
																<input id='fixed_amount_reestr' class='form-check-input' type="checkbox" checked disabled {{$is_disabled != '' ? 'disabled' : ''}}/>
															@else
																<input id='fixed_amount_reestr' class='form-check-input' type="checkbox" disabled {{$is_disabled != '' ? 'disabled' : ''}}/>
															@endif
														@endif
													</div>
												</div>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<input class='form-control {{$errors->has("amount_comment_reestr") ? print("inputError ") : print("")}}' name='amount_comment_reestr' value='{{old("amount_comment_reestr") ? old("amount_comment_reestr") : $reestr->amount_comment_reestr}}' {{$is_disabled}} spellcheck='true'/>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-4">
												<div class='row'>
													<div class="col-md-12">
														<label for='end_term_repayment_reestr' class='small-text'>Конечный срок оплаты по Д/К</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-9">
														<input id='end_term_repayment_reestr' class='datepicker form-control {{$errors->has("end_term_repayment_reestr") ? print("inputError ") : print("")}}' name='end_term_repayment_reestr' value='{{old("end_term_repayment_reestr") ? old("end_term_repayment_reestr") : $reestr->end_term_repayment_reestr}}' {{$is_disabled}} />
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class='row'>
													<div class="col-md-12">
														<label for='nmcd_reestr'>НМЦД/НМЦК</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-7">
														<input id='nmcd_reestr' class='form-control check-number {{$errors->has("nmcd_reestr") ? print("inputError ") : print("")}}' name='nmcd_reestr' value='{{old("nmcd_reestr") ? old("nmcd_reestr") : $reestr->nmcd_reestr}}' {{$is_disabled}}/>
													</div>
													<div class="col-md-5">
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
											<div class="col-md-4">
												<div class='row'>
													<div class="col-md-12">
														<label for='economy_reestr'>Экономия</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-7">
														<input id='economy_reestr' class='form-control check-number {{$errors->has("economy_reestr") ? print("inputError ") : print("")}}' name='economy_reestr' value='{{old("economy_reestr") ? old("economy_reestr") : $reestr->economy_reestr}}' {{$is_disabled}}/>
													</div>
													<div class="col-md-5">
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
											<div class="col-md-12">
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
													<div class="col-md-6">
														<div class='form-group'>
															@if(old('prepayment_reestr'))
																<input class='form-check-input' type="checkbox" checked disabled />
															@else
																@if($reestr->prepayment_reestr || $reestr->percent_prepayment_reestr)
																	<input class='form-check-input' type="checkbox" checked disabled />
																@else
																	<input class='form-check-input' type="checkbox" disabled />
																@endif
															@endif
															<label style='margin: 0px;'>Аванс предусмотрен</label>
															<div class='row' style='margin: 0px;'>
																<div class='col-md-6' style='padding-left: 0px;'>
																	<div id='input_prepayment_contract' class='input_prepayment_contract' style='@if($reestr->prepayment_reestr || $reestr->percent_prepayment_reestr) display:block; @else display:none; @endif' disabled >
																		<label>%</label>
																		@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел')
																			<input class='form-control check-number' type='text' value="{{old('percent_prepayment_reestr') ? old('percent_prepayment_reestr') : $reestr->percent_prepayment_reestr}}" readonly />
																		@else
																			<input class='form-control' type='text' value="{{old('percent_prepayment_reestr') ? old('percent_prepayment_reestr') : $reestr->percent_prepayment_reestr}}" readonly />
																		@endif
																	</div>
																</div>
																<div class='col-md-6' style='padding-right: 0px;'>
																	<div id='input_prepayment_contract' class='input_prepayment_contract' style='@if($reestr->prepayment_reestr || $reestr->percent_prepayment_reestr) display:block; @else display:none; @endif' disabled>
																		<label>Сумма служ. ПЭО</label>
																		@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел')
																			<input class='form-control check-number' type='text' value="{{old('prepayment_reestr') ? old('prepayment_reestr') : $reestr->prepayment_reestr}}" readonly />
																		@else
																			<input class='form-control' type='text' value="{{old('prepayment_reestr') ? old('prepayment_reestr') : $reestr->prepayment_reestr}}" readonly />
																		@endif
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-6">
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
									<div class='col-md-2'>
										<div class='row'>
											<div class='col-md-12'>
												<button class='btn btn-primary' data-toggle="modal" data-target="#scan" type='button' style='float: right; width: 184px; margin-top: 5px;'>Сканы</button>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-12'>
												<button class='btn btn-primary btn-href' style='float: right; width: 184px; margin-top: 5px;' type='button' href="{{route('department.peo.show_additional_documents', $contract->id)}}">Догов. мат</button>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-12'>
												<button class='btn btn-primary btn-href' type='button'  href="{{route('tree_map.show_contract',$contract->id)}}" style='float: right; width: 184px; margin-top: 5px;'>Граф договора</button>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-12'>
												<button class='btn btn-primary btn-href' type='button'  href="{{route('journal.contract',$contract->id)}}" style='float: right; width: 184px; margin-top: 5px;'>История изменений</button>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-12'>
												<button class='btn btn-primary btn-href' type='button'  href="{{route('new_applications.copying', $contract->id_new_application_contract)}}" style='float: right; width: 184px; margin-top: 5px;' {{$contract->id_new_application_contract ? '' : 'disabled'}}>Переписка по заявке</button>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-12'>
												<button class='btn btn-primary btn-href' type='button'  href="{{route('department.contract_second.show', $contract->id)}}" style='float: right; width: 184px; margin-top: 5px;'>Наряды/Акты</button>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-12'>
												<button class='btn btn-primary btn-href' style='float: right; width: 184px; margin-top: 5px;' type='button' href="{{route('department.reestr.show_obligation', $contract->id)}}" {{$is_disabled}}>Исполнение Д/К</button>
											</div>
										</div>
										<!--
										<div class='row'>
											<div class='col-md-12'>
												<button class='btn btn-primary btn-href' style='float: right; width: 184px; margin-top: 5px;' type='button' href="{{route('department.reestr.show_protocols', $contract->id)}}" disabled >ПР/ПСР/ПУР</button>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-12'>
												<button class='btn btn-primary btn-href' style='float: right; width: 184px; margin-top: 5px;' type='button' href="{{route('department.reestr.show_additional_agreements', $contract->id)}}" disabled >ДС</button>
											</div>
										</div>
										-->
										<div class='row'>
											<div class='col-md-12'>
												<button class='btn btn-primary btn-href' style='float: right; width: 184px; margin-top: 5px;' type='button' href="{{route('department.reestr.show_amount_invoice', $contract->id)}}">Сумма по счетам</button>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-12'>
												<button class='btn btn-primary btn-href' style='float: right; width: 184px; margin-top: 5px;' type='button' href="{{route('department.reestr.show_specify', $contract->id)}}">Спецификация</button>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-12'>
												<button class='btn btn-primary' style='float: right; width: 184px; margin-top: 5px;' data-toggle="modal" data-target="#invoice" type='button'>Расчёты по Д/К</button>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-12'>
												<button class='btn btn-primary btn-href' href="{{route('ten.show_contract',$contract->id)}}" style='float: right; width: 184px; margin-top: 5px;' type='button'>Комплектация</button>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
										<label for='small_page'>Мелкий режим</label>
										@if(isset($_GET['isSmallPage']))
											<input id='small_page' class='form-check-input btn-href' href="{{route('department.ekonomic.contract_new_reestr', $contract->id)}}" type="checkbox" checked />
										@else
											<input id='small_page' class='form-check-input btn-href' href="{{route('department.ekonomic.contract_new_reestr', $contract->id)}}?isSmallPage=true" type="checkbox"/>
										@endif
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
															<th  colspan='2'>Выполнение</th>
															<th>{{$amount_invoices}} р.</th>
														</tr>
														<tr>
															<th  colspan='2'>Аванс</th>
															<th>{{$amount_prepayments}} р.</th>
														</tr>
														<tr>
															<th  colspan='2'>Окончательный расчет</th>
															<th>{{$amount_payments}} р.</th>
														</tr>
														<tr>
															<th rowspan='2' style='vertical-align: middle;'>Задолженность</th>
															<th>Дебет</th>
															<th>{{number_format((($amount_invoices - ($amount_prepayments + $amount_payments) + $amount_returns) > 0 ? $amount_invoices - ($amount_prepayments + $amount_payments) + $amount_returns : 0), 2, ',', ' ')}} р.</th>
														</tr>
														<tr>
															<th>Кредит</th>
															<th>{{number_format(((($amount_prepayments + $amount_payments) - $amount_invoices - $amount_returns) > 0 ? ($amount_prepayments + $amount_payments) - $amount_invoices - $amount_returns : 0), 2, ',', ' ')}} р.</th>
														</tr>
													</thead>
												</table>
											</div>
										</div>
										<div class='row'>
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
															<?php $pr_amount = 0; ?>
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
																<?php $pr_amount += $prepayment->amount_p_invoice; ?>
															@endforeach
															<tr>
																<td>
																<td><b>Итого:</b></td>
																<td>{{$pr_amount}}</td>
															</tr>
														</tbody>
													</table>
												@endif
											</div>
											<div class="col-md-6">
												@if($payments)
													<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
														<thead>
															<tr>
																<th colspan='3' style='text-align: center;'>ОПЛАТА АВАНСА</th>
															</tr>
															<tr>
																<th>№ п/п</th>
																<th>Дата</th>
																<th>Сумма</th>
															</tr>
														</thead>
														<tbody>
															<?php $pr_amount = 0; ?>
															@foreach($payments as $payment)
																@if($payment->is_prepayment_invoice)
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
																	<?php $pr_amount += $payment->amount_p_invoice; ?>
																@endif
															@endforeach
															<tr>
																<td>
																<td><b>Итого:</b></td>
																<td>{{$pr_amount}}</td>
															</tr>
														</tbody>
													</table>
												@endif
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
															<?php $pr_amount = 0; ?>
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
																<?php $pr_amount += $score->amount_p_invoice; ?>
															@endforeach
															<tr>
																<td>
																<td><b>Итого:</b></td>
																<td>{{$pr_amount}}</td>
															</tr>
														</tbody>
													</table>
												@endif
											</div>
											<div class="col-md-6">
												@if($payments)
													<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
														<thead>
															<tr>
																<th colspan='3' style='text-align: center;'>ОПЛАТА ТОВАРА, РАБОТ, УСЛУГ</th>
															</tr>
															<tr>
																<th>№ п/п</th>
																<th>Дата</th>
																<th>Сумма</th>
															</tr>
														</thead>
														<tbody>
															<?php $pr_amount = 0; ?>
															@foreach($payments as $payment)
																@if(!$payment->is_prepayment_invoice)
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
																	<?php $pr_amount += $payment->amount_p_invoice; ?>
																@endif
															@endforeach
															<tr>
																<td>
																<td><b>Итого:</b></td>
																<td>{{$pr_amount}}</td>
															</tr>
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
															<?php $pr_amount = 0; ?>
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
																<?php $pr_amount += $invoice->amount_p_invoice; ?>
															@endforeach
															<tr>
																<td>
																<td><b>Итого:</b></td>
																<td>{{$pr_amount}}</td>
															</tr>
														</tbody>
													</table>
												@endif
											</div>
											<div class="col-md-6">
												@if($returns)
													<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
														<thead>
															<tr>
																<th colspan='3' style='text-align: center;'>ВОЗВРАТ</th>
															</tr>
															<tr>
																<th>№ п/п</th>
																<th>Дата</th>
																<th>Сумма</th>
															</tr>
														</thead>
														<tbody>
															<?php $pr_amount = 0; ?>
															@foreach($returns as $return)
																<tr class="rowsContract">
																	<td>
																		{{ $return->number_invoice }}
																	</td>
																	<td>
																		{{ $return->date_invoice ? date('d.m.Y', strtotime($return->date_invoice)) : '' }}
																	</td>
																	<td>
																		{{ $return->amount_p_invoice }}
																	</td>
																</tr>
																<?php $pr_amount += $return->amount_p_invoice; ?>
															@endforeach
															<tr>
																<td>
																<td><b>Итого:</b></td>
																<td>{{$pr_amount}}</td>
															</tr>
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
								<div class="row">
									<div class='col-md-9 border-top border-bottom border-left border-right'>
										<div class='row'>
											<div class="col-md-4">
												<label>Контрагент</label>
												<div class="form-group">
													<select id="sel4" class='form-control select_counterpartie_reestr {{$errors->has("id_counterpartie_contract") ? print("inputError ") : print("")}}' name='id_counterpartie_contract' required>
														<option></option>
														<option value='{{$contract->id_counterpartie_contract}}' full_name='{{$contract->full_name_counterpartie_contract}}' inn='{{$contract->inn_counterpartie_contract}}' selected>{{$contract->name_counterpartie_contract}}</option>
													</select>
													@if($errors->has('id_counterpartie_contract'))
														<label class='msgError'>{{$errors->first('id_counterpartie_contract')}}</label>
													@endif
												</div>
											</div>
											<div class="col-md-1">
												<div class="form-group">
													<button type='button' data-toggle="modal" data-target="#chose_counterpartie" class="btn btn-primary" style='margin-top: 27px;'>Выбрать</button>
												</div>
											</div>
											<div class="col-md-3">
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
											<div class="col-md-4">
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
															@if(isset($_GET['isSmallPage']))
																<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $prev_contract)}}?isSmallPage=true" title='Назад' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
															@else
																<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $prev_contract)}}" title='Назад' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
															@endif
														@else
															<button class='btn btn-primary' title='Назад' disabled type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
														@endif
														@if(isset($next_contract))
															@if(isset($_GET['isSmallPage']))
																<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $next_contract)}}?isSmallPage=true" title='Вперед' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
															@else
																<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $next_contract)}}" title='Вперед' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
															@endif
														@else
															<button class='btn btn-primary' title='Вперед' disabled type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
														@endif
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class='col-md-3 border-bottom border-right border-top'>
										<div class='row'>
											<div class="col-md-6">
												<div class="form-group">
													<label for='amount_contract_reestr' class='small-text'>Сумма (окончательная)</label>
													<input id='amount_contract_reestr' class='form-control check-number' name='amount_contract_reestr' type='text' value='{{old("amount_contract_reestr") ? old("amount_contract_reestr") : $reestr->amount_contract_reestr}}'/>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for='amount_invoice_reestr'>Сумма по счетам</label>
													<input id='amount_invoice_reestr' class='form-control check-number' name='amount_invoice_reestr' type='text' value='{{$reestr->amount_invoice_reestr}}' disabled />
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class='col-md-7'>
										<div class='row'>
											<div class='col-md-6 border-left border-right border-top border-bottom'>
												<div class='row'>
													<div class="col-md-4">
														<div class="form-group">
															<label for='number_pp'>№ п/п</label>
															<input id='number_pp' class='change_contract_number form-control {{$errors->has("number_pp") ? print("inputError ") : print("")}}' name='number_pp' type='text' value='{{old("number_pp") ? old("number_pp") : (strlen($contract->number_contract) > 0 ? explode("‐",$contract->number_contract)[0] : "")}}' required/>
															@if($errors->has('number_pp'))
																<label class='msgError'>{{$errors->first('number_pp')}}</label>
															@endif
														</div>
													</div>
													<div class="col-md-4">
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
													<div class="col-md-4">
														<div class="form-group">
															<label for='year_contract'>Год</label>
															<input id='year_contract' class='change_contract_number form-control {{$errors->has("year_contract") ? print("inputError ") : print("")}}' name='year_contract' type='text' value='{{old("year_contract") ? old("year_contract") : $contract->year_contract}}' required />
															@if($errors->has('year_contract'))
																<label class='msgError'>{{$errors->first('year_contract')}}</label>
															@endif
														</div>
													</div>
												</div>
											</div>
											<div class='col-md-6 border-left border-right border-top border-bottom'>
												<div class='row'>
													<div class="col-md-6">
														<div class="form-group">
															<label for='executor_contract_reestr' class='small-text'>Исполнитель по Дог./Контр.</label>
															<input id='executor_contract_reestr' class='form-control' name='executor_contract_reestr' type='text' value='{{old("executor_contract_reestr") ? old("executor_contract_reestr") : $reestr->executor_contract_reestr}}'/>
														</div>
													</div>
													<div class="col-md-6">
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
												</div>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-12 border-left border-top border-right border-bottom'>
												<div class='row'>
													<div class="col-md-4">
														<div class="form-group">
															<label for='date_contract_on_first_reestr'>Дата Дог./Контр. на 1 л.</label>
															<input id='date_contract_on_first_reestr' class='datepicker form-control {{$errors->has("date_contract_on_first_reestr") ? print("inputError ") : print("")}}' name='date_contract_on_first_reestr' value='{{old("date_contract_on_first_reestr") ? old("date_contract_on_first_reestr") : $reestr->date_contract_on_first_reestr}}'/>
															@if($errors->has('date_contract_on_first_reestr'))
																<label class='msgError'>{{$errors->first('date_contract_on_first_reestr')}}</label>
															@endif
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label for='date_signing_contract_reestr' style='font-size: 11px;'>Дата подписания ф-л "НТИИМ"(ФКП "НТИИМ")</label>
															<input id='date_signing_contract_reestr' class='datepicker form-control {{$errors->has("date_signing_contract_reestr") ? print("inputError ") : print("")}}' name='date_signing_contract_reestr' value='{{old("date_signing_contract_reestr") ? old("date_signing_contract_reestr") : $reestr->date_signing_contract_reestr}}'/>
															@if($errors->has('date_signing_contract_reestr'))
																<label class='msgError'>{{$errors->first('date_signing_contract_reestr')}}</label>
															@endif
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label for='date_control_signing_contract_reestr' class='small-text'>Контрольный срок подписания Дог./Контр.</label>
															<input id='date_control_signing_contract_reestr' class='datepicker form-control {{$errors->has("date_control_signing_contract_reestr") ? print("inputError ") : print("")}}' name='date_control_signing_contract_reestr' value='{{old("date_control_signing_contract_reestr") ? old("date_control_signing_contract_reestr") : $reestr->date_control_signing_contract_reestr}}'/>
															@if($errors->has('date_control_signing_contract_reestr'))
																<label class='msgError'>{{$errors->first('date_control_signing_contract_reestr')}}</label>
															@endif
														</div>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-4">
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
													<div class="col-md-4">
														<div class="form-group">
															<label for='date_signing_contract_counterpartie_reestr'>Дата подписания Контрагентом</label>
															<input id='date_signing_contract_counterpartie_reestr' class='datepicker form-control {{$errors->has("date_signing_contract_counterpartie_reestr") ? print("inputError ") : print("")}}' name='date_signing_contract_counterpartie_reestr' value='{{old("date_signing_contract_counterpartie_reestr") ? old("date_signing_contract_counterpartie_reestr") : $reestr->date_signing_contract_counterpartie_reestr}}'/>
															@if($errors->has('date_signing_contract_counterpartie_reestr'))
																<label class='msgError'>{{$errors->first('date_signing_contract_counterpartie_reestr')}}</label>
															@endif
														</div>
													</div>
													<div class="col-md-4">
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
										</div>
									</div>
									<div class='col-md-5 border-left border-top border-right border-bottom'>
										<div class='row'>
											<div class="col-md-5">
												<div class="form-group">
													<label for='date_save_contract_reestr' style='font-size: 11px;'>Дата сдачи Д/К на хранение оригинала</label>
													<input id='date_save_contract_reestr' class='datepicker form-control {{$errors->has("date_save_contract_reestr") ? print("inputError ") : print("")}}' name='date_save_contract_reestr' value='{{old("date_save_contract_reestr") ? old("date_save_contract_reestr") : $reestr->date_save_contract_reestr}}'/>
													@if($errors->has('date_save_contract_reestr'))
														<label class='msgError'>{{$errors->first('date_save_contract_reestr')}}</label>
													@endif
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for='place_save_contract_reestr'>Место хранения</label>
													<input id='place_save_contract_reestr' class='form-control {{$errors->has("place_save_contract_reestr") ? print("inputError ") : print("")}}' name='place_save_contract_reestr' value='{{old("place_save_contract_reestr") ? old("place_save_contract_reestr") : $reestr->place_save_contract_reestr}}'/>
													@if($errors->has('place_save_contract'))
														<label class='msgError'>{{$errors->first('place_save_contract')}}</label>
													@endif
												</div>
											</div>
											<div class="col-md-3">
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
											<div class="col-md-5">
												<div class="form-group">
													<label for='date_save_contract_el_reestr' class='small-text'>Дата сдачи Д/К на хранение скана</label>
													<input id='date_save_contract_el_reestr' class='datepicker form-control {{$errors->has("date_save_contract_el_reestr") ? print("inputError ") : print("")}}' name='date_save_contract_el_reestr' value='{{old("date_save_contract_el_reestr") ? old("date_save_contract_el_reestr") : $reestr->date_save_contract_el_reestr}}'/>
													@if($errors->has('date_save_contract_el_reestr'))
														<label class='msgError'>{{$errors->first('date_save_contract_el_reestr')}}</label>
													@endif
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for='count_save_contract_reestr'>Срок хранения по</label>
													<input id='count_save_contract_reestr' class='datepicker form-control {{$errors->has("count_save_contract_reestr") ? print("inputError ") : print("")}}' name='count_save_contract_reestr' value='{{old("count_save_contract_reestr") ? old("count_save_contract_reestr") : $reestr->count_save_contract_reestr}}'/>
													@if($errors->has('count_save_contract_reestr'))
														<label class='msgError'>{{$errors->first('count_save_contract_reestr')}}</label>
													@endif
												</div>
											</div>
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
										</div>
										<div class='row'>
											<div class="col-md-5">
												<div class="form-group">
													<label for='document_success_renouncement_reestr' class='small-text'>Документ, подтверждающий отказ</label>
													<input id='document_success_renouncement_reestr' class='form-control {{$errors->has("document_success_renouncement_reestr") ? print("inputError ") : print("")}}' name='document_success_renouncement_reestr' value='{{old("document_success_renouncement_reestr") ? old("document_success_renouncement_reestr") : $contract->document_success_renouncement_reestr}}'/>
													@if($errors->has('document_success_renouncement_reestr'))
														<label class='msgError'>{{$errors->first('document_success_renouncement_reestr')}}</label>
													@endif
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<label for='date_renouncement_contract' class='small-text'>Дата отказа</label>
													<input id='date_renouncement_contract' class='form-control datepicker {{$errors->has("date_renouncement_contract") ? print("inputError ") : print("")}}' name='date_renouncement_contract' value='{{old("date_renouncement_contract") ? old("date_renouncement_contract") : $contract->date_renouncement_contract}}'/>
													@if($errors->has('date_renouncement_contract'))
														<label class='msgError'>{{$errors->first('date_renouncement_contract')}}</label>
													@endif
												</div>
											</div>
											<div class="col-md-5">
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
								<div class="row">
									<div class='col-md-2 border-left border-top border-right border-bottom'>
										<div class='row'>
											<div class="col-md-12">
												<div class="form-group">
													<label for="sel3">Вид договора</span></label>
													<select id="sel3" class='form-control {{$errors->has("id_view_contract") ? print("inputError ") : print("")}}' name='id_view_contract' required>
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
										</div>
										<div class='row'>
											<div class="col-md-12">
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
									</div>
									<div class='col-md-4 border-left border-top border-right border-bottom'>
										<div class='row'>
											<div class="col-md-6">
												<div class="form-group">
													<label for='app_outgoing_number_reestr'>Заявка исх. №</label>
													<input id='app_outgoing_number_reestr' class='form-control {{$errors->has("app_outgoing_number_reestr") ? print("inputError ") : print("")}}' name='app_outgoing_number_reestr' value='{{old("app_outgoing_number_reestr") ? old("app_outgoing_number_reestr") : $reestr->app_outgoing_number_reestr}}'/>
													@if($errors->has('app_outgoing_number_reestr'))
														<label class='msgError'>{{$errors->first('app_outgoing_number_reestr')}}</label>
													@endif
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for='app_incoming_number_reestr'>Вх. №</label>
													<input id='app_incoming_number_reestr' class='form-control {{$errors->has("app_incoming_number_reestr") ? print("inputError ") : print("")}}' name='app_incoming_number_reestr' value='{{old("app_incoming_number_reestr") ? old("app_incoming_number_reestr") : $reestr->app_incoming_number_reestr}}'/>
													@if($errors->has('app_incoming_number_reestr'))
														<label class='msgError'>{{$errors->first('app_incoming_number_reestr')}}</label>
													@endif
												</div>
											</div>
										</div>
									</div>
									<div class='col-md-2 border-left border-top border-right border-bottom'>
										<div class='row'>
											<div class="col-md-6">
												<div class="form-group">
													<label for='protocols_reestr'>Протоколы</label>
													<input id='protocols_reestr' class='form-control {{$errors->has("protocols_reestr") ? print("inputError ") : print("")}}' name='protocols_reestr' value='{{$big_date_protocol != null ? $big_date_protocol : ""}}' readonly />
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for='add_agreements_reestr'>ДС</label>
													<input id='add_agreements_reestr' class='form-control {{$errors->has("add_agreements_reestr") ? print("inputError ") : print("")}}' name='add_agreements_reestr' value='{{$big_date_add_agreement != null ? $big_date_add_agreement : ""}}' readonly />
												</div>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-6">
												<div class="form-group">
													<label for='sel9' class='small-text'>Согл./Не согл.</label>
													<select id="sel9" class='form-control {{$errors->has("reconciliation_protocol_reestr") ? print("inputError ") : print("")}}' name='reconciliation_protocol_reestr'>
														<option value='0' {{$reestr->reconciliation_protocol_reestr == 0 ? 'selected' : ''}}></option>
														<option value='1' {{$reestr->reconciliation_protocol_reestr == 1 ? 'selected' : ''}}>Согласовано</option>
														<option value='2' {{$reestr->reconciliation_protocol_reestr == 2 ? 'selected' : ''}}>Не согласовано</option>
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for='sel10' class='small-text'>Согл./Не согл.</label>
													<select id="sel10" class='form-control {{$errors->has("reconciliation_agreement_reestr") ? print("inputError ") : print("")}}' name='reconciliation_agreement_reestr'>
														<option value='0' {{$reestr->reconciliation_agreement_reestr == 0 ? 'selected' : ''}}></option>
														<option value='1' {{$reestr->reconciliation_agreement_reestr == 1 ? 'selected' : ''}}>Согласовано</option>
														<option value='2' {{$reestr->reconciliation_agreement_reestr == 2 ? 'selected' : ''}}>Не согласовано</option>
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class='col-md-2 border-left border-top border-right border-bottom'>
										<div class='row'>
											<div class="col-md-12">
												<div class="form-group">
													<label for='result_second_department_date_reestr'>Заключение отдела №2 Дата</label>
													<input id='result_second_department_date_reestr' class='datepicker form-control {{$errors->has("result_second_department_date_reestr") ? print("inputError ") : print("")}}' name='result_second_department_date_reestr' value='{{old("result_second_department_date_reestr") ? old("result_second_department_date_reestr") : $reestr->result_second_department_date_reestr}}'/>
													@if($errors->has('result_second_department_date_reestr'))
														<label class='msgError'>{{$errors->first('result_second_department_date_reestr')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<div class="form-group">
													<label for='result_second_department_number_reestr'>№</label>
													<input id='result_second_department_number_reestr' class='form-control {{$errors->has("result_second_department_number_reestr") ? print("inputError ") : print("")}}' name='result_second_department_number_reestr' value='{{old("result_second_department_number_reestr") ? old("result_second_department_number_reestr") : $reestr->result_second_department_number_reestr}}'/>
													@if($errors->has('result_second_department_number_reestr'))
														<label class='msgError'>{{$errors->first('result_second_department_number_reestr')}}</label>
													@endif
												</div>
											</div>
										</div>
									</div>
									<div class='col-md-2 border-all'>
										<div class='row'>
											<div class="col-md-12">
												<div class="form-group">
													<label for='date_complete_reestr'>Договор исполнен на</label>
													<input id='date_complete_reestr' class='datepicker form-control {{$errors->has("date_complete_reestr") ? print("inputError ") : print("")}}' name='date_complete_reestr' value='{{old("date_complete_reestr") ? old("date_complete_reestr") : $reestr->date_complete_reestr}}'/>
													@if($errors->has('date_complete_reestr'))
														<label class='msgError'>{{$errors->first('date_complete_reestr')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<div class="form-group">
													<label for='reestr_number_reestr'>Реестровый номер Д/К</label>
													<input id='reestr_number_reestr' class='form-control {{$errors->has("reestr_number_reestr") ? print("inputError ") : print("")}}' name='reestr_number_reestr' value='{{old("reestr_number_reestr") ? old("reestr_number_reestr") : $reestr->reestr_number_reestr}}' maxlength='30'/>
													@if($errors->has('reestr_number_reestr'))
														<label class='msgError'>{{$errors->first('reestr_number_reestr')}}</label>
													@endif
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class='row'>
									<input id='is_new_reestr' class='form-check-input' name='is_new_reestr' type="checkbox" checked style='display: none;'/>
								</div>
								<div class='row'>
									<div class="col-md-5 border-left border-top border-right border-bottom">
										<div class="col-md-3">
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
										<div class="col-md-5">
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
										<div class="col-md-4" style='padding-bottom: 33px;'>
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
												<label for='days_reconciliation_reestr' class='small-text'>Срок действия согласования крупной сделки</label>
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
												<label for='count_mounth_reestr' class='small-text'>Количество месяцев</label>
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
												<label class='small-text'>Сроки согласования проекта договора исполнителей</label>
											</div>
											<div class="col-md-3">
												<label for='begin_date_reconciliation_reestr' class='small-text'>Начало согласования (дата)</label>
												<input id='begin_date_reconciliation_reestr' class='datepicker form-control {{$errors->has("begin_date_reconciliation_reestr") ? print("inputError ") : print("")}}' name='begin_date_reconciliation_reestr' value='{{old("begin_date_reconciliation_reestr") ? old("begin_date_reconciliation_reestr") : $reestr->begin_date_reconciliation_reestr}}'/>
												@if($errors->has('begin_date_reconciliation_reestr'))
													<label class='msgError'>{{$errors->first('begin_date_reconciliation_reestr')}}</label>
												@endif
											</div>
											<div class="col-md-3">
												<label for='end_date_reconciliation_reestr' style='font-size: 11px;'>Окончание согласования (дата)</label>
												<input id='end_date_reconciliation_reestr' class='datepicker form-control {{$errors->has("end_date_reconciliation_reestr") ? print("inputError ") : print("")}}' name='end_date_reconciliation_reestr' value='{{old("end_date_reconciliation_reestr") ? old("end_date_reconciliation_reestr") : $reestr->end_date_reconciliation_reestr}}'/>
												@if($errors->has('end_date_reconciliation_reestr'))
													<label class='msgError'>{{$errors->first('end_date_reconciliation_reestr')}}</label>
												@endif
											</div>
											<div class="col-md-4">
												<label for='count_days_reconciliation_reestr' class='small-text'>Общее количество дней согласования</label>
												<input id='count_days_reconciliation_reestr' class='form-control {{$errors->has("count_days_reconciliation_reestr") ? print("inputError ") : print("")}}' name='count_days_reconciliation_reestr' value='{{old("count_days_reconciliation_reestr") ? old("count_days_reconciliation_reestr") : $reestr->count_days_reconciliation_reestr}}'/>
												@if($errors->has('count_days_reconciliation_reestr'))
													<label class='msgError'>{{$errors->first('count_days_reconciliation_reestr')}}</label>
												@endif
											</div>
										</div>
										<div class='row'>
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
													<textarea id='nameWork' class='form-control {{$errors->has("name_work_contract") ? print("inputError ") : print("")}}' name='name_work_contract' type="text" style="width: 100%;" rows='4' required>{{ old('name_work_contract') ? old('name_work_contract') : $contract->name_work_contract }}</textarea>
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
														<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%;" rows='5' cols='2' readonly>@foreach($states as $state){{$state->date_state . '   ' . $state->name_state . '   ' . $state->surname . ' ' . mb_substr($state->name, 0, 1) . '.' . mb_substr($state->patronymic, 0, 1) . '.&#13;&#10;'}}@endforeach</textarea>
													@else
														<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%;" rows='5' readonly></textarea>
													@endif
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<button class="btn btn-primary" data-toggle="modal" data-target="#history_states" type='button'>История состояний</button>
											</div>
											<div class="col-md-6">
												<button class="btn btn-primary" data-toggle="modal" data-target="#work_states" type='button'>Выполнение работ</button>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
											</div>
											<div class="col-md-1">
												<div class='form-group'>
													@if(isset($prev_contract))
														@if(isset($_GET['isSmallPage']))
															<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $prev_contract)}}?isSmallPage=true" title='Назад' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
														@else
															<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $prev_contract)}}" title='Назад' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
														@endif
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
														@if(isset($_GET['isSmallPage']))
															<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $next_contract)}}?isSmallPage=true" title='Вперед' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
														@else
															<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $next_contract)}}" title='Вперед' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
														@endif
													@else
														<button class='btn btn-primary' title='Вперед' disabled type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
													@endif
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-7 border-all">
										<div class="row">
											<div class="col-md-12">
												<label>Обеспечение гарантийных обязательств</label>
											</div>
											<div class="col-md-3">
												<div class='row'>
													<div class="col-md-12">
														<label for='term_action_reestr'>Срок действия</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														<input id='term_action_reestr' class='form-control {{$errors->has("term_action_reestr") ? print("inputError ") : print("")}}' name='term_action_reestr' value='{{ old("term_action_reestr") ? old("term_action_reestr") : $reestr->term_action_reestr }}'/>
													</div>
												</div>
											</div>
											<div class="col-md-3">
												<div class='row'>
													<div class="col-md-12">
														<label for='date_bank_reestr'>Гарантия банка до</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														<input id='date_bank_reestr' class='form-control {{$errors->has("date_bank_reestr") ? print("inputError ") : print("")}}' name='date_bank_reestr' value='{{ old("date_bank_reestr") ? old("date_bank_reestr") : $reestr->date_bank_reestr }}'/>
													</div>
												</div>
											</div>
											<div class="col-md-2">
												<div class='row'>
													<div class="col-md-12">
														<label for='amount_bank_reestr'>Сумма</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														<input id='amount_bank_reestr' class='form-control check-number {{$errors->has("amount_bank_reestr") ? print("inputError ") : print("")}}' name='amount_bank_reestr' value='{{ old("amount_bank_reestr") ? old("amount_bank_reestr") : $reestr->amount_bank_reestr }}'/>
													</div>
												</div>
											</div>
											<div class='col-md-4'>
												<div class='row'>
													<div class="col-md-12">
														<label for='bank_reestr'>Банк</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-12">
														<input id='bank_reestr' class='form-control {{$errors->has("bank_reestr") ? print("inputError ") : print("")}}' name='bank_reestr' value='{{ old("bank_reestr") ? old("bank_reestr") : $reestr->bank_reestr }}'/>
													</div>
												</div>
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
											<div class="col-md-6">
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
														<input id='date_e_contract_reestr' class='form-control {{$errors->has("date_e_contract_reestr") ? print("inputError ") : print("")}}' value='{{ old("date_e_contract_reestr") ? old("date_e_contract_reestr") : $reestr->date_e_contract_reestr }}' readonly />
													</div>
												</div>
											</div>
										</div>
										<div class='row border-bottom'>
											<div class="col-md-11">
												<div class="form-group">
													<input id='date_contract_reestr' class='form-control {{$errors->has("date_contract_reestr") ? print("inputError ") : print("")}}' value='{{ old("date_contract_reestr") ? old("date_contract_reestr") : $reestr->date_contract_reestr }}' readonly spellcheck='true'/>
												</div>
											</div>
											<div class='col-md-1'>
												<button type='button' class='btn btn-primary' data-toggle="modal" data-target="#modal_date_contract_reestr" title='Форма срока действия договора'><!--☼--><span class="ui-icon ui-icon-1-1" style='background-size: 355px; background-position-x: -49px; background-position-y: -179px;'></span></button>
											</div>
										</div>
										<div class="row border-bottom">
											<div class='col-md-12'>
												<div class="form-group">
													<div class='row'>
														<div class="col-md-9">
															<label for='date_maturity_reestr'>Срок исполнения обязательств</label>
														</div>
														<div class='col-md-2'>
															<label for='date_e_maturity_reestr'>До</label>
														</div>
													</div>
													<div class='row'>
														<div class="col-md-9">
															<input id='date_maturity_reestr' class='form-control {{$errors->has("date_maturity_reestr") ? print("inputError ") : print("")}}' value='{{ old("date_maturity_reestr") ? old("date_maturity_reestr") : $reestr->date_maturity_reestr }}' readonly spellcheck='true'/>
														</div>
														<div class='col-md-2'>
															<input id='date_e_maturity_reestr' class='form-control {{$errors->has("date_e_maturity_reestr") ? print("inputError ") : print("")}}' value='{{ old("date_e_maturity_reestr") ? old("date_e_maturity_reestr") : $reestr->date_e_maturity_reestr }}' readonly spellcheck='true'/>
														</div>
														<div class='col-md-1'>
															<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modal_date_maturity_reestr' title='Форма срока исполнения обязательств'><!--☼--><span class="ui-icon ui-icon-1-1" style='background-size: 355px; background-position-x: -49px; background-position-y: -179px;'></span></button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class='row'>
													<div class="col-md-12">
														<label for='amount_begin_reestr'>Цена при заключении Д/К</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-4">
														<input id='amount_begin_reestr' class='form-control check-number {{$errors->has("amount_begin_reestr") ? print("inputError ") : print("")}}' name='amount_begin_reestr' value='{{old("amount_begin_reestr") ? old("amount_begin_reestr") : $reestr->amount_begin_reestr}}'/>
													</div>
													<div class="col-md-4">
														<select class='form-control {{$errors->has("unit_begin_reestr") ? print("inputError ") : print("")}}' name='unit_begin_reestr'>
															<option></option>
															@foreach($units as $unit)
																@if(old('unit_begin_reestr'))
																	<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
																@else
																	@if($reestr->unit_begin_reestr == $unit->id)
																		<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
																	@else
																		<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
																	@endif
																@endif
															@endforeach
														</select>
													</div>
													<div class='col-md-4'>
														<label for='VAT_BEGIN'>НДС</label>
														@if(old('vat_begin_reestr'))
															<input id='VAT_BEGIN' class='form-check-input' name='vat_begin_reestr' type="checkbox" checked />
														@else
															@if($reestr->vat_begin_reestr)
																<input id='VAT_BEGIN' class='form-check-input' name='vat_begin_reestr' type="checkbox" checked />
															@else
																<input id='VAT_BEGIN' class='form-check-input' name='vat_begin_reestr' type="checkbox" />
															@endif
														@endif
													</div>
												</div>
												<div class='row'>
													<div class="col-md-6">
														<label for='approximate_amount_begin_reestr'>Ориентировочная</label>
														@if(old('approximate_amount_begin_reestr'))
															<input id='approximate_amount_begin_reestr' class='form-check-input' name='approximate_amount_begin_reestr' type="checkbox" checked />
														@else
															@if($reestr->approximate_amount_begin_reestr)
																<input id='approximate_amount_begin_reestr' class='form-check-input' name='approximate_amount_begin_reestr' type="checkbox" checked />
															@else
																<input id='approximate_amount_begin_reestr' class='form-check-input' name='approximate_amount_begin_reestr' type="checkbox" />
															@endif
														@endif
													</div>
													<div class="col-md-6">
														<label for='fixed_amount_begin_reestr'>Фиксированная</label>
														@if(old('fixed_amount_begin_reestr'))
															<input id='fixed_amount_begin_reestr' class='form-check-input' name='fixed_amount_begin_reestr' type="checkbox" checked />
														@else
															@if($reestr->fixed_amount_begin_reestr)
																<input id='fixed_amount_begin_reestr' class='form-check-input' name='fixed_amount_begin_reestr' type="checkbox" checked />
															@else
																<input id='fixed_amount_begin_reestr' class='form-check-input' name='fixed_amount_begin_reestr' type="checkbox" />
															@endif
														@endif
													</div>
												</div>
											</div>
											<div class='col-md-6'>
												<div class='row'>
													<div class="col-md-12">
														<label for='amount_reestr'>Сумма Д/К</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-4">
														<input id='amount_reestr' class='form-control check-number {{$errors->has("amount_reestr") ? print("inputError ") : print("")}}' value='{{old("amount_reestr") ? old("amount_reestr") : $reestr->amount_reestr}}' readonly />
													</div>
													<div class="col-md-3">
														<select id="sel8" class='form-control {{$errors->has("unit_reestr") ? print("inputError ") : print("")}}' disabled >
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
													<div class="col-md-3">
														<label for='VAT'>НДС</label>
														@if(old('vat_reestr'))
															<input id='VAT' class='form-check-input' type="checkbox" checked disabled />
														@else
															@if($reestr->vat_reestr)
																<input id='VAT' class='form-check-input' type="checkbox" checked disabled />
															@else
																<input id='VAT' class='form-check-input' type="checkbox" disabled />
															@endif
														@endif
													</div>
													<div class='col-md-2'>
														<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modal_amount_reestr' title='Форма срока исполнения обязательств'><!--☼--><span class="ui-icon ui-icon-1-1" style='background-size: 355px; background-position-x: -49px; background-position-y: -179px;'></span></button>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-6">
														<label for='approximate_amount_reestr'>Ориентировочная</label>
														@if(old('approximate_amount_reestr'))
															<input id='approximate_amount_reestr' class='form-check-input' type="checkbox" checked disabled />
														@else
															@if($reestr->approximate_amount_reestr)
																<input id='approximate_amount_reestr' class='form-check-input' type="checkbox" checked disabled />
															@else
																<input id='approximate_amount_reestr' class='form-check-input' type="checkbox" disabled />
															@endif
														@endif
													</div>
													<div class="col-md-6">
														<label for='fixed_amount_reestr'>Фиксированная</label>
														@if(old('fixed_amount_reestr'))
															<input id='fixed_amount_reestr' class='form-check-input' type="checkbox" checked disabled />
														@else
															@if($reestr->fixed_amount_reestr)
																<input id='fixed_amount_reestr' class='form-check-input' type="checkbox" checked disabled />
															@else
																<input id='fixed_amount_reestr' class='form-check-input' type="checkbox" disabled />
															@endif
														@endif
													</div>
												</div>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<input class='form-control {{$errors->has("amount_comment_reestr") ? print("inputError ") : print("")}}' name='amount_comment_reestr' value='{{old("amount_comment_reestr") ? old("amount_comment_reestr") : $reestr->amount_comment_reestr}}' spellcheck='true'/>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-4">
												<div class='row'>
													<div class="col-md-12">
														<label for='end_term_repayment_reestr' class='small-text'>Конечный срок оплаты по Д/К</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-9">
														<input id='end_term_repayment_reestr' class='datepicker form-control {{$errors->has("end_term_repayment_reestr") ? print("inputError ") : print("")}}' name='end_term_repayment_reestr' value='{{old("end_term_repayment_reestr") ? old("end_term_repayment_reestr") : $reestr->end_term_repayment_reestr}}' />
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class='row'>
													<div class="col-md-12">
														<label for='nmcd_reestr'>НМЦД/НМЦК</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-7">
														<input id='nmcd_reestr' class='form-control check-number {{$errors->has("nmcd_reestr") ? print("inputError ") : print("")}}' name='nmcd_reestr' value='{{old("nmcd_reestr") ? old("nmcd_reestr") : $reestr->nmcd_reestr}}'/>
													</div>
													<div class="col-md-5">
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
											<div class="col-md-4">
												<div class='row'>
													<div class="col-md-12">
														<label for='economy_reestr'>Экономия</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-7">
														<input id='economy_reestr' class='form-control check-number {{$errors->has("economy_reestr") ? print("inputError ") : print("")}}' name='economy_reestr' value='{{old("economy_reestr") ? old("economy_reestr") : $reestr->economy_reestr}}'/>
													</div>
													<div class="col-md-5">
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
											<div class="col-md-12">
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
									<div class='col-md-2'>
										<div class='row'>
											<div class='col-md-12'>
												<button class='btn btn-primary' data-toggle="modal" data-target="#scan" type='button' style='float: right; width: 184px; margin-top: 5px;'>Сканы</button>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-12'>
												<button class='btn btn-primary btn-href' style='float: right; width: 184px; margin-top: 5px;' type='button' href="{{route('department.peo.show_additional_documents', $contract->id)}}">Догов. мат</button>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-12'>
												<button class='btn btn-primary btn-href' type='button'  href="{{route('tree_map.show_contract',$contract->id)}}" style='float: right; width: 184px; margin-top: 5px;'>Граф договора</button>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-12'>
												<button class='btn btn-primary btn-href' type='button'  href="{{route('journal.contract',$contract->id)}}" style='float: right; width: 184px; margin-top: 5px;'>История изменений</button>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-12'>
												<button class='btn btn-primary btn-href' type='button'  href="{{route('new_applications.copying', $contract->id_new_application_contract)}}" style='float: right; width: 184px; margin-top: 5px;' {{$contract->id_new_application_contract ? '' : 'disabled'}}>Переписка по заявке</button>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-12'>
												<button class='btn btn-primary btn-href' style='float: right; width: 184px; margin-top: 5px;' type='button' href="{{route('department.reestr.show_obligation', $contract->id)}}">Исполнение Д/К</button>
											</div>
										</div>
										<!--
										<div class='row'>
											<div class='col-md-12'>
												<button class='btn btn-primary btn-href' style='float: right; width: 184px; margin-top: 5px;' type='button' href="{{route('department.reestr.show_protocols', $contract->id)}}" disabled >ПР/ПСР/ПУР</button>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-12'>
												<button class='btn btn-primary btn-href' style='float: right; width: 184px; margin-top: 5px;' type='button' href="{{route('department.reestr.show_additional_agreements', $contract->id)}}" disabled >ДС</button>
											</div>
										</div>
										-->
										<div class='row'>
											<div class='col-md-12'>
												<button class='btn btn-primary btn-href' style='float: right; width: 184px; margin-top: 5px;' type='button' href="{{route('department.reestr.show_amount_invoice', $contract->id)}}">Сумма по счетам</button>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-12'>
												<button class='btn btn-primary btn-href' style='float: right; width: 184px; margin-top: 5px;' type='button' href="{{route('department.reestr.show_specify', $contract->id)}}">Спецификация</button>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2">
										<label for='small_page'>Мелкий режим</label>
										@if(isset($_GET['isSmallPage']))
											<input id='small_page' class='form-check-input btn-href' href="{{route('department.ekonomic.contract_new_reestr', $contract->id)}}" type="checkbox" checked />
										@else
											<input id='small_page' class='form-check-input btn-href' href="{{route('department.ekonomic.contract_new_reestr', $contract->id)}}?isSmallPage=true" type="checkbox"/>
										@endif
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
					<!-- Модальное окно выполнения контракта -->
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
											<div id='table_history_work_states' class='col-md-12'>
												<table class="table" style='margin: 0 auto;'>
													<thead>
														<tr>
															<th>Наименование</th>
															<th>Дата</th>
															<th>Автор</th>
															<th>Удалить</th>
														</tr>
													</thead>
													<tbody>
														@if(isset($work_states))
															@foreach($work_states as $state)
																<tr class='rowsContract'>
																	<td>{{$state->name_state}}<br/>{{$state->comment_state}}</td>
																	<td>{{$state->date_state}}</td>
																	<td>{{$state->surname . ' ' . mb_substr($state->name, 0, 1) . '.' . mb_substr($state->patronymic, 0, 1) . '.'}}</td>
																	<td>
																		@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->surname == $state->surname)
																			<button type='button' class='btn btn-danger btn-href' type='button' href="{{route('department.ekonomic.destroy_state', $state->id)}}">Удалить</button>
																		@else
																			<button type='button' class='btn btn-danger' type='button' disabled>Удалить</button>
																		@endif
																	</td>
																</tr>
															@endforeach
														@endif
													</tbody>
												</table>
												<div class='col-md-12'>
													@if(Auth::User()->hasRole()->role != 'Администрация')
														<button class='btn btn-secondary steps' first_step='#table_history_work_states' second_step='#add_history_work_states' type='button' style='margin-top: 10px;'>Добавить стадию выполнения</button>
													@endif
												</div>
											</div>
											<div id='add_history_work_states' class='col-md-12' style='display: none;'>
												<div class='form-group row col-md-12'>
													<div class='col-md-12'>
														<label for='type_work' class='col-form-label'>Наименование</label>
														<select id='type_work' class='form-control {{$errors->has("unit_reestr") ? print("inputError ") : print("")}}' name='type_state' required>
															<option></option>
															<option>Оформляется</option>
															<option>Изделие не поступило на испытание</option>
															<option>В стадии выполнения</option>
															<option>Приостановлен</option>
															<option>Выполнен</option>
															<option>Отказ</option>
															<option>Другое</option>
														</select>
													</div>
												</div>
												<div class='form-group row col-md-12'>
													<div class='col-md-12'>
														<input id='new_name_work' class='form-control {{$errors->has("new_name_state") ? print("inputError ") : print("")}}' type='text' name='new_name_state' style='display: none;'/>
														@if($errors->has('new_name_state'))
															<label class='msgError'>{{$errors->first('new_name_state')}}</label>
														@endif
													</div>
												</div>
												<div class='form-group row col-md-12' style='display: none;'>
													<div class='col-md-12'>
														<input class='form-control' type='text' name='is_work_state' value='1'/>
													</div>
												</div>
												<div class='form-group row col-md-12'>
													<label class='col-md-3 col-form-label'>Примечение</label>
													<div class='col-md-9'>
														<input class='form-control {{$errors->has("comment_state") ? print("inputError ") : print("")}}' type='text' name='comment_state'/>
														@if($errors->has('comment_state'))
															<label class='msgError'>{{$errors->first('comment_state')}}</label>
														@endif
													</div>
												</div>
												<div class='form-group row col-md-12'>
													<label for='date_state' class='col-md-3 col-form-label'>Дата</label>
													<div class='col-md-9'>
														<input class='datepicker form-control {{$errors->has("date_state") ? print("inputError ") : print("")}}' name='date_state' value='{{date("d.m.Y", time())}}' />
														@if($errors->has('date_state'))
															<label class='msgError'>{{$errors->first('date_state')}}</label>
														@endif
													</div>
												</div>
												<div class='col-md-6' style='float: right;'>
													@if(Auth::User()->hasRole()->role != 'Администрация')
														<button class='btn btn-primary' type='sumbit' style='margin-top: 10px;'>Добавить</button>
													@endif
													@if(Auth::User()->hasRole()->role != 'Администрация')
														<button class='btn btn-secondary steps' first_step='#add_history_work_states' second_step='#table_history_work_states' type='button' style='margin-top: 10px;'>Отмена</button>
													@endif
												</div>
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
																<tr class='rowsContract cursorPointer updateState' id_state='{{$state->id}}' 
																													name_state='{{$state->name_state}}' 
																													comment_state='{{$state->comment_state}}' 
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
											<div id='add_history_states' class='col-md-12' style='display: none;'>
												<div class='form-group row col-md-12'>
													<input id='id_state' class='form-control' type='text' name='id_state' style='display: none;'/>
												</div>
												<div class='form-group row col-md-12'>
													<div class='col-md-12'>
														<label for='type_state' class='col-form-label'>Наименование</label>
														<select id='type_state' class='form-control {{$errors->has("unit_reestr") ? print("inputError ") : print("")}}' name='type_state' required>
															<option></option>
															<option>На согласовании</option>
															<option>Отправлен заказчику</option>
															<option>Заключён</option>
															<option>Другое</option>
														</select>
													</div>
												</div>
												<div class='form-group row col-md-12'>
													<div class='col-md-12'>
														<input id='new_name_state' class='form-control {{$errors->has("new_name_state") ? print("inputError ") : print("")}}' type='text' name='new_name_state' style='display: none;'/>
														@if($errors->has('new_name_state'))
															<label class='msgError'>{{$errors->first('new_name_state')}}</label>
														@endif
													</div>
												</div>
												<div class='form-group row col-md-12'>
													<label for='comment_state' class='col-md-3 col-form-label'>Примечение</label>
													<div class='col-md-9'>
														<input id='comment_state' class='form-control {{$errors->has("comment_state") ? print("inputError ") : print("")}}' type='text' name='comment_state'/>
														@if($errors->has('comment_state'))
															<label class='msgError'>{{$errors->first('comment_state')}}</label>
														@endif
													</div>
												</div>
												<div class='form-group row col-md-12'>
													<label for='date_state' class='col-md-3 col-form-label'>Дата</label>
													<div class='col-md-9'>
														<input id='date_state' class='datepicker form-control {{$errors->has("date_state") ? print("inputError ") : print("")}}' name='date_state' value='{{date("d.m.Y", time())}}' />
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
											<div class="col-md-5">
												<button type='button' class='btn btn-secondary steps' first_step='#form_all_application' second_step='#form_delete_all_resolution' style='float: right;'>Удаление сканов</button>
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-12">
												<select id='resolution_list' name='resolution_list' class='form-control {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
													@if(count($resolutions) > 0)
														@foreach($resolutions as $resolution)
															@if($resolution->deleted_at == null)
																@if($resolution->type_resolution == 1)
																	<option value='http://{{$resolution->path_resolution}}' download_href='resolution_download/{{$resolution->id}}' delete_href='{{route("resolution_delete",$resolution->id)}}' style='color: rgb(239,19,198);'>{{$resolution->real_name_resolution}}</option>
																@else
																	<option value='http://{{$resolution->path_resolution}}' download_href='resolution_download/{{$resolution->id}}' delete_href='{{route("resolution_delete",$resolution->id)}}'>{{$resolution->real_name_resolution}}</option>
																@endif
															@endif
														@endforeach
													@else
														<option></option>
													@endif
												</select>
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
												<button id='open_resolution' type='button' class='btn btn-primary' style='width: 122px;'>Открыть скан</button>
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
												<label>Наименование документа</label>
												<input id='real_name_resolution' type='text' name='real_name_resolution' class='form-control'/>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-12'>
												<label>Тип документа</label>
												<select id='type_resolution' type='text' name='type_resolution' class='form-control'>
													<option></option>
													@foreach($type_resolutions as $type_resolution)
														<option value='{{$type_resolution->id}}'>{{$type_resolution->name_type_resolution}}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
										<button id='btn_close_new_application' type="button" class="btn btn-secondary">Закрыть</button>
									</div>
								</form>
								<form id='form_delete_all_resolution' method='POST' action="{{route('resolution_update')}}" style='display: none;'>
									{{csrf_field()}}
									<div class="modal-header">
										<h5 class="modal-title" id="allApplicationModalLabel">Удаление резолюций</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class='modal-body'>
										<div class='form-group row'>
											<div class="col-md-12">
												<table class="table" style='margin: 0 auto;'>
													<thead>
														<tr>
															<th>Удаление</th>
															<th>Название резолюции</th>
														</tr>
													</thead>
													<tbody>
														<?php $count_check_message = 0; $count_old_delete = 0; ?>
														@foreach($resolutions as $resolution)
															@if($resolution->deleted_at != null)
																<tr class='rowsContract cursorPointer rowsMessage marked' id_application='{{$resolution->id}}' for_check='check_message{{$count_check_message}}' for_input='select_message{{$count_check_message}}'>
																	<td>
																		<input id='check_message{{$count_check_message}}' class='form-check-input' type="checkbox" checked />
																	</td>
																	<td>{{$resolution->real_name_resolution}}</td>
																	<input id='select_message{{$count_check_message}}' name='select_message[{{$count_check_message}}]' value='{{$resolution->id}}' style='display: none'/>
																	<input name='select_old_delete[{{$count_old_delete}}]' value='{{$resolution->id}}' style='display: none'/>
																</tr>
															@else
																<tr class='rowsContract cursorPointer rowsMessage' id_application='{{$resolution->id}}' for_check='check_message{{$count_check_message}}'>
																	<td>
																		<input id='check_message{{$count_check_message}}' class='form-check-input' type="checkbox"/>
																	</td>
																	<td>{{$resolution->real_name_resolution}}</td>
																</tr>
															@endif
															<?php $count_check_message++; $count_old_delete++; ?>
														@endforeach
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										@if(Auth::User()->hasRole()->role != 'Администрация')
											<button type='submit' class='btn btn-primary' type='button'>Удалить/Восстановить</button>
										@endif
										<button type="button" class="btn btn-secondary steps" first_step='#form_delete_all_resolution' second_step='#form_all_application'>Закрыть</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<!-- Модальное окно срок действия договора -->
					<div class="modal fade" id="modal_date_contract_reestr" tabindex="-1" role="dialog" aria-labelledby="dateContractModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="dateContractModalLabel">Срок действия Д/К</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div id='table_date_contract_reestr' class='row'>
										@if(count($all_reest_date_contract) > 0)
											<div class='col-md-12'>
												<table class="table" style='margin: 0 auto;'>
													<thead>
														<tr>
															<th>Наименование</th>
															<th>Срок действия Д/К</th>
															<th>До</th>
															@if(Auth::User()->hasRole()->role != 'Администрация')
																<th>Изменить</th>
															@endif
															@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
																<th>Удалить</th>
															@endif
														</tr>
													</thead>
													<tbody>
														@foreach($all_reest_date_contract as $reestr_date_contract)
															<tr class='rowsContract'>
																<td>{{$reestr_date_contract->name_date_contract}}</td>
																<td>{{$reestr_date_contract->term_date_contract}}</td>
																<td>{{$reestr_date_contract->end_date_contract}}</td>
																@if(Auth::User()->hasRole()->role != 'Администрация')
																	<td><button class='btn btn-primary btn-update-date-contract steps' type='button' reestr_date_contract='{{$reestr_date_contract}}' action_update='{{ route("reestr.date_contract.update", $reestr_date_contract->id)}}' first_step='#table_date_contract_reestr' second_step='#update_date_contract_reestr'>Изменить</button></td>
																@endif
																@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
																	<td><button class='btn btn-danger btn-href' type='button' href='{{route("reestr.date_contract.destroy", $reestr_date_contract->id)}}'>Удалить</button></td>
																@endif
															</tr>
														@endforeach
													</tbody>
												</table>
											</div>
										@endif
										<div class='col-md-12'>
											@if(Auth::User()->hasRole()->role != 'Администрация')
												<button class='btn btn-primary steps' type='button' style='margin-top: 10px;' first_step='#table_date_contract_reestr' second_step='#new_date_contract_reestr'>Добавить срок действия Д/К</button>
											@endif
										</div>
									</div>
									<div id='new_date_contract_reestr' class='row' style='display: none;'>
										<form method='POST' action='{{route("reestr.date_contract.store", $contract->id)}}'>
											{{csrf_field()}}
											<div class='col-md-12 form-group'>
												<label for='name_date_contract' class='col-md-3 col-form-label'>Наименование</label>
												<div class='col-md-9'>
													<input id='name_date_contract' class='form-control {{$errors->has("name_date_contract") ? print("inputError ") : print("")}}' type='text' name='name_date_contract' maxlength='100' required />
													@if($errors->has('name_date_contract'))
														<label class='msgError'>{{$errors->first('name_date_contract')}}</label>
													@endif
												</div>
											</div>
											<div class='col-md-12 form-group'>
												<label for='term_date_contract' class='col-md-3 col-form-label'>Срок действия</label>
												<div class='col-md-9'>
													<textarea id='term_date_contract' class='form-control {{$errors->has("term_date_contract") ? print("inputError ") : print("")}}' type='text' name='term_date_contract' required rows='3'></textarea>
													@if($errors->has('term_date_contract'))
														<label class='msgError'>{{$errors->first('term_date_contract')}}</label>
													@endif
												</div>
											</div>
											<div class='col-md-12 form-group'>
												<label for='end_date_contract' class='col-md-3 col-form-label'>До</label>
												<div class='col-md-9'>
													<input id='end_date_contract' class='datepicker form-control {{$errors->has("end_date_contract") ? print("inputError ") : print("")}}' type='text' name='end_date_contract'/>
													@if($errors->has('end_date_contract'))
														<label class='msgError'>{{$errors->first('end_date_contract')}}</label>
													@endif
												</div>
											</div>
											<div class='col-md-12 form-group'>
												<div class='row'>
													<div class='col-md-6'>
													</div>
													<div class='col-md-3'>
														<button type="submit" class="btn btn-primary">Добавить</button>
													</div>
													<div class='col-md-3'>
														<button type="button" class="btn btn-secondary steps" first_step='#new_date_contract_reestr' second_step='#table_date_contract_reestr'>Назад</button>
													</div>
												</div>
											</div>
										</form>
									</div>
									<div id='update_date_contract_reestr' class='row' style='display: none;'>
										<form id='form_update_date_contract_reestr' method='POST' action=''>
											{{csrf_field()}}
											<div class='col-md-12 form-group'>
												<label for='update_name_date_contract' class='col-md-3 col-form-label'>Наименование</label>
												<div class='col-md-9'>
													<input id='update_name_date_contract' class='form-control {{$errors->has("name_date_contract") ? print("inputError ") : print("")}}' type='text' name='name_date_contract' maxlength='100' required />
													@if($errors->has('name_date_contract'))
														<label class='msgError'>{{$errors->first('name_date_contract')}}</label>
													@endif
												</div>
											</div>
											<div class='col-md-12 form-group'>
												<label for='update_term_date_contract' class='col-md-3 col-form-label'>Срок действия</label>
												<div class='col-md-9'>
													<textarea id='update_term_date_contract' class='form-control {{$errors->has("term_date_contract") ? print("inputError ") : print("")}}' type='text' name='term_date_contract' required rows='3'></textarea>
													@if($errors->has('term_date_contract'))
														<label class='msgError'>{{$errors->first('term_date_contract')}}</label>
													@endif
												</div>
											</div>
											<div class='col-md-12 form-group'>
												<label for='update_end_date_contract' class='col-md-3 col-form-label'>До</label>
												<div class='col-md-9'>
													<input id='update_end_date_contract' class='datepicker form-control {{$errors->has("end_date_contract") ? print("inputError ") : print("")}}' type='text' name='end_date_contract'/>
													@if($errors->has('end_date_contract'))
														<label class='msgError'>{{$errors->first('end_date_contract')}}</label>
													@endif
												</div>
											</div>
											<div class='col-md-12 form-group'>
												<div class='row'>
													<div class='col-md-6'>
													</div>
													<div class='col-md-3'>
														<button type="submit" class="btn btn-primary">Сохранить</button>
													</div>
													<div class='col-md-3'>
														<button type="button" class="btn btn-secondary steps" first_step='#update_date_contract_reestr' second_step='#table_date_contract_reestr'>Назад</button>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</div>
						</div>
					</div>
					<!-- Модальное окно срок исполнения обязательств -->
					<div class="modal fade" id="modal_date_maturity_reestr" tabindex="-1" role="dialog" aria-labelledby="dateMaturityModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="dateMaturityModalLabel">Срок исполнения обязательств</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div id='table_date_maturity_reestr' class='row'>
										@if(count($all_reest_date_maturity) > 0)
											<div class='col-md-12'>
												<table class="table" style='margin: 0 auto;'>
													<thead>
														<tr>
															<th>Наименование</th>
															<th>Срок исполнения</th>
															<th>До</th>
															@if(Auth::User()->hasRole()->role != 'Администрация')
																<th>Изменить</th>
															@endif
															@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
																<th>Удалить</th>
															@endif
														</tr>
													</thead>
													<tbody>
														@foreach($all_reest_date_maturity as $reestr_date_maturity)
															<tr class='rowsContract'>
																<td>{{$reestr_date_maturity->name_date_maturity}}</td>
																<td>{{$reestr_date_maturity->term_date_maturity}}</td>
																<td>{{$reestr_date_maturity->end_date_maturity}}</td>
																@if(Auth::User()->hasRole()->role != 'Администрация')
																	<td><button class='btn btn-primary btn-update-date-maturity steps' type='button' reestr_date_maturity='{{$reestr_date_maturity}}' action_update='{{ route("reestr.date_maturity.update", $reestr_date_maturity->id)}}' first_step='#table_date_maturity_reestr' second_step='#update_date_maturity_reestr'>Изменить</button></td>
																@endif
																@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
																	<td><button class='btn btn-danger btn-href' type='button' href='{{route("reestr.date_maturity.destroy", $reestr_date_maturity->id)}}'>Удалить</button></td>
																@endif
															</tr>
														@endforeach
													</tbody>
												</table>
											</div>
										@endif
										<div class='col-md-12'>
											@if(Auth::User()->hasRole()->role != 'Администрация')
												<button class='btn btn-primary steps' type='button' style='margin-top: 10px;' first_step='#table_date_maturity_reestr' second_step='#new_date_maturity_reestr'>Добавить срок исполнения обязательств</button>
											@endif
										</div>
									</div>
									<div id='new_date_maturity_reestr' class='row' style='display: none;'>
										<form method='POST' action='{{route("reestr.date_maturity.store", $contract->id)}}'>
											{{csrf_field()}}
											<div class='col-md-12 form-group'>
												<label for='name_date_maturity' class='col-md-3 col-form-label'>Наименование</label>
												<div class='col-md-9'>
													<input id='name_date_maturity' class='form-control {{$errors->has("name_date_maturity") ? print("inputError ") : print("")}}' type='text' name='name_date_maturity' maxlength='100' required />
													@if($errors->has('name_date_maturity'))
														<label class='msgError'>{{$errors->first('name_date_maturity')}}</label>
													@endif
												</div>
											</div>
											<div class='col-md-12 form-group'>
												<label for='term_date_maturity' class='col-md-3 col-form-label'>Срок исполнения</label>
												<div class='col-md-9'>
													<textarea id='term_date_maturity' class='form-control {{$errors->has("term_date_maturity") ? print("inputError ") : print("")}}' type='text' name='term_date_maturity' required rows='3'></textarea>
													@if($errors->has('term_date_maturity'))
														<label class='msgError'>{{$errors->first('term_date_maturity')}}</label>
													@endif
												</div>
											</div>
											<div class='col-md-12 form-group'>
												<label for='end_date_maturity' class='col-md-3 col-form-label'>До</label>
												<div class='col-md-9'>
													<input id='end_date_maturity' class='datepicker form-control {{$errors->has("end_date_maturity") ? print("inputError ") : print("")}}' type='text' name='end_date_maturity'/>
													@if($errors->has('end_date_maturity'))
														<label class='msgError'>{{$errors->first('end_date_maturity')}}</label>
													@endif
												</div>
											</div>
											<div class='col-md-12 form-group'>
												<div class='row'>
													<div class='col-md-6'>
													</div>
													<div class='col-md-3'>
														<button type="submit" class="btn btn-primary">Добавить</button>
													</div>
													<div class='col-md-3'>
														<button type="button" class="btn btn-secondary steps" first_step='#new_date_maturity_reestr' second_step='#table_date_maturity_reestr'>Назад</button>
													</div>
												</div>
											</div>
										</form>
									</div>
									<div id='update_date_maturity_reestr' class='row' style='display: none;'>
										<form id='form_update_date_maturity_reestr' method='POST' action=''>
											{{csrf_field()}}
											<div class='col-md-12 form-group'>
												<label for='update_name_date_maturity' class='col-md-3 col-form-label'>Наименование</label>
												<div class='col-md-9'>
													<input id='update_name_date_maturity' class='form-control {{$errors->has("name_date_maturity") ? print("inputError ") : print("")}}' type='text' name='name_date_maturity' maxlength='100' required />
													@if($errors->has('name_date_maturity'))
														<label class='msgError'>{{$errors->first('name_date_maturity')}}</label>
													@endif
												</div>
											</div>
											<div class='col-md-12 form-group'>
												<label for='update_term_date_maturity' class='col-md-3 col-form-label'>Срок исполнения</label>
												<div class='col-md-9'>
													<textarea id='update_term_date_maturity' class='form-control {{$errors->has("term_date_maturity") ? print("inputError ") : print("")}}' type='text' name='term_date_maturity' required rows='3'></textarea>
													@if($errors->has('term_date_maturity'))
														<label class='msgError'>{{$errors->first('term_date_maturity')}}</label>
													@endif
												</div>
											</div>
											<div class='col-md-12 form-group'>
												<label for='update_end_date_maturity' class='col-md-3 col-form-label'>До</label>
												<div class='col-md-9'>
													<input id='update_end_date_maturity' class='datepicker form-control {{$errors->has("end_date_maturity") ? print("inputError ") : print("")}}' type='text' name='end_date_maturity'/>
													@if($errors->has('end_date_maturity'))
														<label class='msgError'>{{$errors->first('end_date_maturity')}}</label>
													@endif
												</div>
											</div>
											<div class='col-md-12 form-group'>
												<div class='row'>
													<div class='col-md-6'>
													</div>
													<div class='col-md-3'>
														<button type="submit" class="btn btn-primary">Сохранить</button>
													</div>
													<div class='col-md-3'>
														<button type="button" class="btn btn-secondary steps" first_step='#update_date_maturity_reestr' second_step='#table_date_maturity_reestr'>Назад</button>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</div>
						</div>
					</div>
					<!-- Модальное окно сумм -->
					<div class="modal fade" id="modal_amount_reestr" tabindex="-1" role="dialog" aria-labelledby="amountModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="amountModalLabel">Сумма Д/К</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div id='table_amount_reestr' class='row'>
										@if(count($all_reestr_amount) > 0)
											<div class='col-md-12'>
												<table class="table" style='margin: 0 auto;'>
													<thead>
														<tr>
															<th>Наименование</th>
															<th>Сумма</th>
															<th>Валюта</th>
															<th>Параметры</th>
															@if(Auth::User()->hasRole()->role != 'Администрация')
																<th>Изменить</th>
															@endif
															@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
																<th>Удалить</th>
															@endif
														</tr>
													</thead>
													<tbody>
														@foreach($all_reestr_amount as $reestr_amount)
															<tr class='rowsContract'>
																<td>{{$reestr_amount->name_amount}}</td>
																<td>{{$reestr_amount->value_amount}}</td>
																<td>{{$reestr_amount->name_unit}}</td>
																<td>
																	<?php
																		if ($reestr_amount->vat_amount)
																			echo 'НДС<br/>';
																		if ($reestr_amount->approximate_amount)
																			echo 'Ориентировочная<br/>';
																		if ($reestr_amount->fixed_amount)
																			echo 'Фиксированная<br/>';
																	?>
																</td>
																@if(Auth::User()->hasRole()->role != 'Администрация')
																	<td><button class='btn btn-primary btn-update-amount steps' type='button' reestr_amount='{{$reestr_amount}}' action_update='{{ route("reestr.amount.update", $reestr_amount->id)}}' first_step='#table_amount_reestr' second_step='#update_amount_reestr'>Изменить</button></td>
																@endif
																@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
																	<td><button class='btn btn-danger btn-href' type='button' href='{{route("reestr.amount.destroy", $reestr_amount->id)}}'>Удалить</button></td>
																@endif
															</tr>
														@endforeach
													</tbody>
												</table>
											</div>
										@endif
										<div class='col-md-12'>
											@if(Auth::User()->hasRole()->role != 'Администрация')
												<button class='btn btn-primary steps' type='button' style='margin-top: 10px;' first_step='#table_amount_reestr' second_step='#new_amount_reestr'>Добавить сумму</button>
											@endif
										</div>
									</div>
									<div id='new_amount_reestr' class='row' style='display: none;'>
										<form method='POST' action='{{route("reestr.amount.store", $contract->id)}}'>
											{{csrf_field()}}
											<div class='col-md-12 form-group'>
												<label for='name_amount' class='col-md-3 col-form-label'>Наименование</label>
												<div class='col-md-9'>
													<input id='name_amount' class='form-control {{$errors->has("name_amount") ? print("inputError ") : print("")}}' type='text' name='name_amount' maxlength='100' required />
													@if($errors->has('name_amount'))
														<label class='msgError'>{{$errors->first('name_amount')}}</label>
													@endif
												</div>
											</div>
											<div class='col-md-12 form-group'>
												<label for='value_amount' class='col-md-3 col-form-label'>Сумма</label>
												<div class='col-md-9'>
													<input id='value_amount' class='check-number form-control {{$errors->has("value_amount") ? print("inputError ") : print("")}}' type='text' name='value_amount' required />
													@if($errors->has('value_amount'))
														<label class='msgError'>{{$errors->first('value_amount')}}</label>
													@endif
												</div>
											</div>
											<div class='col-md-12 form-group'>
												<label for='unit_amount' class='col-md-3 col-form-label'>Валюта</label>
												<div class='col-md-9'>
													<select class='form-control {{$errors->has("unit_amount") ? print("inputError ") : print("")}}' name='unit_amount'>
														<option></option>
														@foreach($units as $unit)
															<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
														@endforeach
													</select>
												</div>
											</div>
											<div class='col-md-3 form-group'>
											</div>
											<div class='col-md-3 form-group'>
												<label for='vat_amount'>НДС</label>
												<input id='vat_amount' class='form-check-input' name='vat_amount' type="checkbox"/>
											</div>
											<div class='col-md-3 form-group'>
												<label for='approximate_amount'>Ориентировочная</label>
												<input id='approximate_amount' class='form-check-input' name='approximate_amount' type="checkbox"/>
											</div>
											<div class='col-md-3 form-group'>
												<label for='fixed_amount'>Фиксированная</label>
												<input id='fixed_amount' class='form-check-input' name='fixed_amount' type="checkbox"/>
											</div>
											<div class='col-md-12 form-group'>
												<div class='row'>
													<div class='col-md-6'>
													</div>
													<div class='col-md-3'>
														<button type="submit" class="btn btn-primary">Добавить</button>
													</div>
													<div class='col-md-3'>
														<button type="button" class="btn btn-secondary steps" first_step='#new_amount_reestr' second_step='#table_amount_reestr'>Назад</button>
													</div>
												</div>
											</div>
										</form>
									</div>
									<div id='update_amount_reestr' class='row' style='display: none;'>
										<form id='form_update_amount_reestr' method='POST' action=''>
											{{csrf_field()}}
											<div class='col-md-12 form-group'>
												<label for='update_name_amount' class='col-md-3 col-form-label'>Наименование</label>
												<div class='col-md-9'>
													<input id='update_name_amount' class='form-control {{$errors->has("name_amount") ? print("inputError ") : print("")}}' type='text' name='name_amount' maxlength='100' required />
													@if($errors->has('name_amount'))
														<label class='msgError'>{{$errors->first('name_amount')}}</label>
													@endif
												</div>
											</div>
											<div class='col-md-12 form-group'>
												<label for='update_value_amount' class='col-md-3 col-form-label'>Сумма</label>
												<div class='col-md-9'>
													<input id='update_value_amount' class='check-number form-control {{$errors->has("value_amount") ? print("inputError ") : print("")}}' type='text' name='value_amount' required />
													@if($errors->has('value_amount'))
														<label class='msgError'>{{$errors->first('value_amount')}}</label>
													@endif
												</div>
											</div>
											<div class='col-md-12 form-group'>
												<label for='update_unit_amount' class='col-md-3 col-form-label'>Валюта</label>
												<div class='col-md-9'>
													<select id='update_unit_amount' class='form-control {{$errors->has("unit_amount") ? print("inputError ") : print("")}}' name='unit_amount'>
														<option></option>
														@foreach($units as $unit)
															<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
														@endforeach
													</select>
												</div>
											</div>
											<div class='col-md-3 form-group'>
											</div>
											<div class='col-md-3 form-group'>
												<label for='update_vat_amount'>НДС</label>
												<input id='update_vat_amount' class='form-check-input' name='vat_amount' type="checkbox"/>
											</div>
											<div class='col-md-3 form-group'>
												<label for='update_approximate_amount'>Ориентировочная</label>
												<input id='update_approximate_amount' class='form-check-input' name='approximate_amount' type="checkbox"/>
											</div>
											<div class='col-md-3 form-group'>
												<label for='update_fixed_amount'>Фиксированная</label>
												<input id='update_fixed_amount' class='form-check-input' name='fixed_amount' type="checkbox"/>
											</div>
											<div class='col-md-12 form-group'>
												<div class='row'>
													<div class='col-md-6'>
													</div>
													<div class='col-md-3'>
														<button type="submit" class="btn btn-primary">Сохранить</button>
													</div>
													<div class='col-md-3'>
														<button type="button" class="btn btn-secondary steps" first_step='#update_amount_reestr' second_step='#table_amount_reestr'>Назад</button>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</div>
						</div>
					</div>
				@else
					<!-- ДОБАВЛЕНИЕ КОНТРАКТА В РЕЕСТР (НЕ СИП) -->
					<div class="content">
						<form method='POST' action="{{route('department.ekonomic.create_reestr')}}" file='true' enctype='multipart/form-data'>
							{{csrf_field()}}
							<div class="row">
								<div class='col-md-9 border-top border-bottom border-left border-right'>
									<div class='row'>
										<div class="col-md-4">
											<label>Контрагент</label>
											<div class="form-group">
												<select id="sel4" class='form-control select_counterpartie_reestr {{$errors->has("id_counterpartie_contract") ? print("inputError ") : print("")}}' name='id_counterpartie_contract' required>
													<option></option>
												</select>
												@if($errors->has('id_counterpartie_contract'))
													<label class='msgError'>{{$errors->first('id_counterpartie_contract')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-1">
											<div class="form-group">
												<button type='button' class="btn btn-primary" data-toggle="modal" data-target="#chose_counterpartie" style='margin-top: 27px;'>Выбрать</button>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Внимание!</label>
												<input class='form-control' style='color:red; text-align:center;' type='text' value='' readonly />
											</div>
										</div>
										<div class="col-md-4">
											<div class="col-md-7">
												<div class="form-group">
													<label for='numberContract'>Номер договора</label>
													<input id='numberContract' class='form-control {{$errors->has("number_contract") ? print("inputError ") : print("")}}' name='number_contract' type='text' value='{{old("number_contract")}}' readonly />
													@if($errors->has('number_contract'))
														<label class='msgError'>{{$errors->first('number_contract')}}</label>
													@endif
												</div>
											</div>
											<div class="col-md-5" style='text-align: center;'>
											</div>
										</div>
									</div>
								</div>
								<div class='col-md-3 border-bottom border-right border-top'>
									<div class='row'>
										<div class="col-md-6">
											<div class="form-group">
												<label for='amount_contract_reestr' class='small-text'>Сумма (окончательная)</label>
												<input id='amount_contract_reestr' class='form-control check-number' name='amount_contract_reestr' type='text' value='{{old("amount_contract_reestr")}}'/>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for='amount_invoice_reestr'>Сумма по счетам</label>
												<input id='amount_invoice_reestr' class='form-control check-number' name='amount_invoice_reestr' type='text' value='{{$reestr->amount_invoice_reestr}}' disabled />
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class='row'>
								<div class='col-md-7'>
									<div class='row'>
										<div class='col-md-6 border-left border-right border-top border-bottom'>
											<div class='row'>
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
												<div class="col-md-4">
													<div class="form-group">
														<label for='year_contract'>Год</label>
														<input id='year_contract' class='change_contract_number form-control {{$errors->has("year_contract") ? print("inputError ") : print("")}}' name='year_contract' type='text' value='{{old("year_contract")}}' required />
														@if($errors->has('year_contract'))
															<label class='msgError'>{{$errors->first('year_contract')}}</label>
														@endif
													</div>
												</div>
											</div>
										</div>
										<div class='col-md-6 border-left border-right border-top border-bottom'>
											<div class='row'>
												<div class="col-md-6">
													<div class="form-group">
														<label for='executor_contract_reestr' class='small-text'>Исполнитель по Дог./Контр.</label>
														<input id='executor_contract_reestr' class='form-control' name='executor_contract_reestr' type='text' value='{{old("executor_contract_reestr")}}'/>
													</div>
												</div>
												<div class="col-md-6">
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
											</div>
										</div>
									</div>
									<div class='row'>
										<div class='col-md-12 border-left border-top border-right border-bottom'>
											<div class='row'>
												<div class="col-md-4">
													<div class="form-group">
														<label for='date_contract_on_first_reestr'>Дата Дог./Контр. на 1 л.</label>
														<input id='date_contract_on_first_reestr' class='datepicker form-control {{$errors->has("date_contract_on_first_reestr") ? print("inputError ") : print("")}}' name='date_contract_on_first_reestr' value='{{old("date_contract_on_first_reestr")}}'/>
														@if($errors->has('date_contract_on_first_reestr'))
															<label class='msgError'>{{$errors->first('date_contract_on_first_reestr')}}</label>
														@endif
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label for='date_signing_contract_reestr' class='small-text'>Дата подписания ф-л "НТИИМ"(ФКП "НТИИМ")</label>
														<input id='date_signing_contract_reestr' class='datepicker form-control {{$errors->has("date_signing_contract_reestr") ? print("inputError ") : print("")}}' name='date_signing_contract_reestr' value='{{old("date_signing_contract_reestr")}}'/>
														@if($errors->has('date_signing_contract_reestr'))
															<label class='msgError'>{{$errors->first('date_signing_contract_reestr')}}</label>
														@endif
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label for='date_control_signing_contract_reestr' class='small-text'>Контрольный срок подписания Дог./Контр.</label>
														<input id='date_control_signing_contract_reestr' class='datepicker form-control {{$errors->has("date_control_signing_contract_reestr") ? print("inputError ") : print("")}}' name='date_control_signing_contract_reestr' value='{{old("date_control_signing_contract_reestr")}}'/>
														@if($errors->has('date_control_signing_contract_reestr'))
															<label class='msgError'>{{$errors->first('date_control_signing_contract_reestr')}}</label>
														@endif
													</div>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-4">
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
												<div class="col-md-4">
													<div class="form-group">
														<label for='date_signing_contract_counterpartie_reestr'>Дата подписания Контрагентом</label>
														<input id='date_signing_contract_counterpartie_reestr' class='datepicker form-control {{$errors->has("date_signing_contract_counterpartie_reestr") ? print("inputError ") : print("")}}' name='date_signing_contract_counterpartie_reestr' value='{{old("date_signing_contract_counterpartie_reestr")}}'/>
														@if($errors->has('date_signing_contract_counterpartie_reestr'))
															<label class='msgError'>{{$errors->first('date_signing_contract_counterpartie_reestr')}}</label>
														@endif
													</div>
												</div>
												<div class="col-md-4">
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
									</div>
								</div>
								<div class='col-md-5 border-left border-top border-right border-bottom'>
									<div class='row'>
										<div class="col-md-5">
											<div class="form-group">
												<label for='date_save_contract_reestr' style='font-size: 11px;'>Дата сдачи Д/К на хранение оригинала</label>
												<input id='date_save_contract_reestr' class='datepicker form-control {{$errors->has("date_save_contract_reestr") ? print("inputError ") : print("")}}' name='date_save_contract_reestr' value='{{old("date_save_contract_reestr")}}'/>
												@if($errors->has('date_save_contract_reestr'))
													<label class='msgError'>{{$errors->first('date_save_contract_reestr')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for='place_save_contract_reestr'>Место хранения</label>
												<input id='place_save_contract_reestr' class='form-control {{$errors->has("place_save_contract_reestr") ? print("inputError ") : print("")}}' name='place_save_contract_reestr' value='{{old("place_save_contract_reestr")}}'/>
												@if($errors->has('place_save_contract'))
													<label class='msgError'>{{$errors->first('place_save_contract')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-3">
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
										<div class="col-md-5">
											<div class="form-group">
												<label for='date_save_contract_el_reestr' class='small-text'>Дата сдачи Д/К на хранение скана</label>
												<input id='date_save_contract_el_reestr' class='datepicker form-control {{$errors->has("date_save_contract_el_reestr") ? print("inputError ") : print("")}}' name='date_save_contract_el_reestr' value='{{old("date_save_contract_el_reestr")}}'/>
												@if($errors->has('date_save_contract_el_reestr'))
													<label class='msgError'>{{$errors->first('date_save_contract_el_reestr')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for='count_save_contract_reestr'>Срок хранения по</label>
												<input id='count_save_contract_reestr' class='datepicker form-control {{$errors->has("count_save_contract_reestr") ? print("inputError ") : print("")}}' name='count_save_contract_reestr' value='{{old("count_save_contract_reestr")}}'/>
												@if($errors->has('count_save_contract_reestr'))
													<label class='msgError'>{{$errors->first('count_save_contract_reestr')}}</label>
												@endif
											</div>
										</div>
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
									</div>
									<div class='row'>
										<div class="col-md-5">
											<div class="form-group">
												<label for='document_success_renouncement_reestr' class='small-text'>Документ, подтверждающий отказ</label>
												<input id='document_success_renouncement_reestr' class='form-control {{$errors->has("document_success_renouncement_reestr") ? print("inputError ") : print("")}}' name='document_success_renouncement_reestr' value='{{old("document_success_renouncement_reestr")}}'/>
												@if($errors->has('document_success_renouncement_reestr'))
													<label class='msgError'>{{$errors->first('document_success_renouncement_reestr')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label for='date_renouncement_contract' class='small-text'>Дата отказа</label>
												<input id='date_renouncement_contract' class='form-control datepicker {{$errors->has("date_renouncement_contract") ? print("inputError ") : print("")}}' name='date_renouncement_contract' value='{{old("date_renouncement_contract")}}'/>
												@if($errors->has('date_renouncement_contract'))
													<label class='msgError'>{{$errors->first('date_renouncement_contract')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-5">
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
							<div class="row">
								<div class='col-md-2 border-left border-top border-right border-bottom'>
									<div class='row'>
										<div class="col-md-12">
											<div class="form-group">
												<label for="sel3">Вид договора</span></label>
												<select id="sel3" class='form-control {{$errors->has("id_view_contract") ? print("inputError ") : print("")}}' name='id_view_contract' required>
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
									</div>
									<div class='row'>
										<div class="col-md-12">
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
									</div>
								</div>
								<div class='col-md-4 border-left border-top border-right border-bottom'>
									<div class='row'>
										<div class="col-md-6">
											<div class="form-group">
												<label for='app_outgoing_number_reestr'>Заявка исх. №</label>
												<input id='app_outgoing_number_reestr' class='form-control {{$errors->has("app_outgoing_number_reestr") ? print("inputError ") : print("")}}' name='app_outgoing_number_reestr' value='{{old("app_outgoing_number_reestr")}}'/>
												@if($errors->has('app_outgoing_number_reestr'))
													<label class='msgError'>{{$errors->first('app_outgoing_number_reestr')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for='app_incoming_number_reestr'>Вх. №</label>
												<input id='app_incoming_number_reestr' class='form-control {{$errors->has("app_incoming_number_reestr") ? print("inputError ") : print("")}}' name='app_incoming_number_reestr' value='{{old("app_incoming_number_reestr")}}'/>
												@if($errors->has('app_incoming_number_reestr'))
													<label class='msgError'>{{$errors->first('app_incoming_number_reestr')}}</label>
												@endif
											</div>
										</div>
									</div>
								</div>
								<div class='col-md-2 border-left border-top border-right border-bottom'>
									<div class='row'>
										<div class="col-md-6">
											<div class="form-group">
												<label for='protocols_reestr'>Протоколы</label>
												<input id='protocols_reestr' class='form-control {{$errors->has("protocols_reestr") ? print("inputError ") : print("")}}' name='protocols_reestr' value='{{old("protocols_reestr")}}' readonly />
												@if($errors->has('protocols_reestr'))
													<label class='msgError'>{{$errors->first('protocols_reestr')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-6">
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
										<div class="col-md-6">
											<div class="form-group">
												<label for='sel9' class='small-text'>Согл./Не согл.</label>
												<select id="sel9" class='form-control {{$errors->has("reconciliation_protocol_reestr") ? print("inputError ") : print("")}}' name='reconciliation_protocol_reestr'>
													<option value='0'></option>
													<option value='1'>Согласовано</option>
													<option value='2'>Не согласовано</option>
												</select>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for='sel10' class='small-text'>Согл./Не согл.</label>
												<select id="sel10" class='form-control {{$errors->has("reconciliation_agreement_reestr") ? print("inputError ") : print("")}}' name='reconciliation_agreement_reestr'>
													<option value='0'></option>
													<option value='1'>Согласовано</option>
													<option value='2'>Не согласовано</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class='col-md-2 border-left border-top border-right border-bottom'>
									<div class='row'>
										<div class="col-md-12">
											<div class="form-group">
												<label for='result_second_department_date_reestr'>Заключение отдела №2 Дата</label>
												<input id='result_second_department_date_reestr' class='datepicker form-control {{$errors->has("result_second_department_date_reestr") ? print("inputError ") : print("")}}' name='result_second_department_date_reestr' value='{{old("result_second_department_date_reestr")}}'/>
												@if($errors->has('result_second_department_date_reestr'))
													<label class='msgError'>{{$errors->first('result_second_department_date_reestr')}}</label>
												@endif
											</div>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-12">
											<div class="form-group">
												<label for='result_second_department_number_reestr'>№</label>
												<input id='result_second_department_number_reestr' class='form-control {{$errors->has("result_second_department_number_reestr") ? print("inputError ") : print("")}}' name='result_second_department_number_reestr' value='{{old("result_second_department_number_reestr")}}'/>
												@if($errors->has('result_second_department_number_reestr'))
													<label class='msgError'>{{$errors->first('result_second_department_number_reestr')}}</label>
												@endif
											</div>
										</div>
									</div>
								</div>
								<div class='col-md-2 border-all'>
									<div class='row'>
										<div class="col-md-12">
											<div class="form-group">
												<label for='date_complete_reestr'>Договор исполнен на</label>
												<input id='date_complete_reestr' class='datepicker form-control {{$errors->has("date_complete_reestr") ? print("inputError ") : print("")}}' name='date_complete_reestr' value='{{old("date_complete_reestr")}}'/>
												@if($errors->has('date_complete_reestr'))
													<label class='msgError'>{{$errors->first('date_complete_reestr')}}</label>
												@endif
											</div>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-12">
											<div class="form-group">
												<label for='reestr_number_reestr'>Реестровый номер Д/К</label>
												<input id='reestr_number_reestr' class='form-control {{$errors->has("reestr_number_reestr") ? print("inputError ") : print("")}}' name='reestr_number_reestr' value='{{old("reestr_number_reestr")}}' maxlength='30'/>
												@if($errors->has('reestr_number_reestr'))
													<label class='msgError'>{{$errors->first('reestr_number_reestr')}}</label>
												@endif
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class='row'>
								<input id='is_new_reestr' class='form-check-input' name='is_new_reestr' type="checkbox" checked style='display: none;'/>
							</div>
							<div class='row'>
								<div class="col-md-5 border-left border-top border-right border-bottom">
									<div class="col-md-3">
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
									<div class="col-md-5">
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
									<div class="col-md-4" style='padding-bottom: 33px;'>
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
											<label for='days_reconciliation_reestr' class='small-text'>Срок действия согласования крупной сделки</label>
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
											<label for='count_mounth_reestr' class='small-text'>Количество месяцев</label>
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
											<label class='small-text'>Сроки согласования проекта договора исполнителей</label>
										</div>
										<div class="col-md-3">
											<label for='begin_date_reconciliation_reestr' class='small-text'>Начало согласования (дата)</label>
											<input id='begin_date_reconciliation_reestr' class='datepicker form-control {{$errors->has("begin_date_reconciliation_reestr") ? print("inputError ") : print("")}}' name='begin_date_reconciliation_reestr' value='{{old("begin_date_reconciliation_reestr")}}'/>
											@if($errors->has('begin_date_reconciliation_reestr'))
												<label class='msgError'>{{$errors->first('begin_date_reconciliation_reestr')}}</label>
											@endif
										</div>
										<div class="col-md-3">
											<label for='end_date_reconciliation_reestr' style='font-size: 11px;'>Окончание согласования (дата)</label>
											<input id='end_date_reconciliation_reestr' class='datepicker form-control {{$errors->has("end_date_reconciliation_reestr") ? print("inputError ") : print("")}}' name='end_date_reconciliation_reestr' value='{{old("end_date_reconciliation_reestr")}}'/>
											@if($errors->has('end_date_reconciliation_reestr'))
												<label class='msgError'>{{$errors->first('end_date_reconciliation_reestr')}}</label>
											@endif
										</div>
										<div class="col-md-4">
											<label for='count_days_reconciliation_reestr' class='small-text'>Общее количество дней согласования</label>
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
									<input id='inn_counterpartie' class='form-control' value='' readonly />
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
												<textarea id='nameWork' class='form-control {{$errors->has("name_work_contract") ? print("inputError ") : print("")}}' name='name_work_contract' type="text" style="width: 100%;" rows='4' required>{{ old('name_work_contract') }}</textarea>
												@if($errors->has('name_work_contract'))
													<label class='msgError'>{{$errors->first('name_work_contract')}}</label>
												@endif
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-7 border-all">
									<div class="row">
										<div class="col-md-12">
											<label>Обеспечение гарантийных обязательств</label>
										</div>
										<div class="col-md-3">
											<div class='row'>
												<div class="col-md-12">
													<label for='term_action_reestr'>Срок действия</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													<input id='term_action_reestr' class='form-control {{$errors->has("term_action_reestr") ? print("inputError ") : print("")}}' name='term_action_reestr' value='{{ old("term_action_reestr") }}'/>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class='row'>
												<div class="col-md-12">
													<label for='date_bank_reestr'>Гарантия банка до</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													<input id='date_bank_reestr' class='datepicker form-control {{$errors->has("date_bank_reestr") ? print("inputError ") : print("")}}' name='date_bank_reestr' value='{{ old("date_bank_reestr") }}'/>
												</div>
											</div>
										</div>
										<div class="col-md-2">
											<div class='row'>
												<div class="col-md-12">
													<label for='amount_bank_reestr'>Сумма</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													<input id='amount_bank_reestr' class='form-control check-number {{$errors->has("amount_bank_reestr") ? print("inputError ") : print("")}}' name='amount_bank_reestr' value='{{ old("amount_bank_reestr") }}'/>
												</div>
											</div>
										</div>
										<div class='col-md-4'>
											<div class='row'>
												<div class="col-md-12">
													<label for='bank_reestr'>Банк</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													<input id='bank_reestr' class='form-control {{$errors->has("bank_reestr") ? print("inputError ") : print("")}}' name='bank_reestr' value='{{ old("bank_reestr") }}'/>
												</div>
											</div>
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
										<div class="col-md-6">
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
										<div class="col-md-12">
											<div class="form-group">
												<input id='date_contract_reestr' class='form-control {{$errors->has("date_contract_reestr") ? print("inputError ") : print("")}}' name='date_contract_reestr' value='{{ old("date_contract_reestr") }}' spellcheck='true'/>
											</div>
										</div>
									</div>
									<div class="row border-bottom">
										<div class="col-md-12">
											<div class="form-group">
												<div class='row'>
													<div class="col-md-10">
														<label for='date_maturity_reestr'>Срок исполнения обязательств</label>
													</div>
													<div class="col-md-2">
														<label for='date_e_maturity_reestr'>До</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-10">
														<input id='date_maturity_reestr' class='form-control {{$errors->has("date_maturity_reestr") ? print("inputError ") : print("")}}' name='date_maturity_reestr' value='{{ old("date_maturity_reestr") }}' spellcheck='true'/>
													</div>
													<div class='col-md-2'>
														<input id='date_e_maturity_reestr' class='datepicker form-control {{$errors->has("date_e_maturity_reestr") ? print("inputError ") : print("")}}' name='date_e_maturity_reestr' value='{{ old("date_e_maturity_reestr") }}' spellcheck='true'/>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class='row'>
												<div class="col-md-12">
													<label for='amount_begin_reestr'>Цена при заключении Д/К</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-4">
													<input id='amount_begin_reestr' class='form-control check-number {{$errors->has("amount_begin_reestr") ? print("inputError ") : print("")}}' name='amount_begin_reestr' value='{{old("amount_begin_reestr")}}' />
												</div>
												<div class="col-md-4">
													<select class='form-control {{$errors->has("unit_begin_reestr") ? print("inputError ") : print("")}}' name='unit_begin_reestr'>
														<option></option>
														@foreach($units as $unit)
															@if(old('unit_begin_reestr'))
																<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
															@else
																<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
															@endif
														@endforeach
													</select>
												</div>
												<div class='col-md-4'>
													<label for='VAT_BEGIN'>НДС</label>
													@if(old('vat_begin_reestr'))
														<input id='VAT_BEGIN' class='form-check-input' name='vat_begin_reestr' type="checkbox" checked />
													@else
														<input id='VAT_BEGIN' class='form-check-input' name='vat_begin_reestr' type="checkbox"/>
													@endif
												</div>
											</div>
											<div class='row'>
												<div class="col-md-6">
													<label for='approximate_amount_begin_reestr'>Ориентировочная</label>
													@if(old('approximate_amount_begin_reestr'))
														<input id='approximate_amount_begin_reestr' class='form-check-input' name='approximate_amount_begin_reestr' type="checkbox" checked />
													@else
														<input id='approximate_amount_begin_reestr' class='form-check-input' name='approximate_amount_begin_reestr' type="checkbox"/>
													@endif
												</div>
												<div class="col-md-6">
													<label for='fixed_amount_begin_reestr'>Фиксированная</label>
													@if(old('fixed_amount_begin_reestr'))
														<input id='fixed_amount_begin_reestr' class='form-check-input' name='fixed_amount_begin_reestr' type="checkbox" checked />
													@else
														<input id='fixed_amount_begin_reestr' class='form-check-input' name='fixed_amount_begin_reestr' type="checkbox"/>
													@endif
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class='row'>
												<div class="col-md-12">
													<label for='amount_reestr'>Сумма Д/К</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-4">
													<input id='amount_reestr' class='form-control check-number {{$errors->has("amount_reestr") ? print("inputError ") : print("")}}' name='amount_reestr' value='{{old("amount_reestr") }}'/>
												</div>
												<div class="col-md-3">
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
												<div class="col-md-3">
													<label for='VAT'>НДС</label>
													@if(old('vat_reestr'))
														<input id='VAT' class='form-check-input' name='vat_reestr' type="checkbox" checked />
													@else
														<input id='VAT' class='form-check-input' name='vat_reestr' type="checkbox"/>
													@endif
												</div>
											</div>
											<div class='row'>
												<div class="col-md-6">
													<label for='approximate_amount_reestr'>Ориентировочная</label>
													@if(old('approximate_amount_reestr'))
														<input id='approximate_amount_reestr' class='form-check-input' name='approximate_amount_reestr' type="checkbox" checked />
													@else
														<input id='approximate_amount_reestr' class='form-check-input' name='approximate_amount_reestr' type="checkbox"/>
													@endif
												</div>
												<div class="col-md-6">
													<label for='fixed_amount_reestr'>Фиксированная</label>
													@if(old('fixed_amount_reestr'))
														<input id='fixed_amount_reestr' class='form-check-input' name='fixed_amount_reestr' type="checkbox" checked />
													@else
														<input id='fixed_amount_reestr' class='form-check-input' name='fixed_amount_reestr' type="checkbox"/>
													@endif
												</div>
											</div>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-12">
											<input class='form-control {{$errors->has("amount_comment_reestr") ? print("inputError ") : print("")}}' name='amount_comment_reestr' value='{{old("amount_comment_reestr") }}' spellcheck='true'/>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-4">
											<div class='row'>
												<div class="col-md-12">
													<label for='end_term_repayment_reestr' class='small-text'>Конечный срок оплаты по Д/К</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-9">
													<input id='end_term_repayment_reestr' class='datepicker form-control {{$errors->has("end_term_repayment_reestr") ? print("inputError ") : print("")}}' name='end_term_repayment_reestr' value='{{old("end_term_repayment_reestr")}}' />
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class='row'>
												<div class="col-md-12">
													<label for='nmcd_reestr'>НМЦД/НМЦК</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-7">
													<input id='nmcd_reestr' class='form-control check-number {{$errors->has("nmcd_reestr") ? print("inputError ") : print("")}}' name='nmcd_reestr' value='{{old("nmcd_reestr") }}'/>
												</div>
												<div class="col-md-5">
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
										<div class="col-md-4">
											<div class='row'>
												<div class="col-md-12">
													<label for='economy_reestr'>Экономия</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-7">
													<input id='economy_reestr' class='form-control check-number {{$errors->has("economy_reestr") ? print("inputError ") : print("")}}' name='economy_reestr' value='{{old("economy_reestr") }}'/>
												</div>
												<div class="col-md-5">
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
										<div class="col-md-12">
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
								<div class="col-md-2">
									<button class='btn btn-primary' data-toggle="modal" data-target="#scan" type='button' style='float: right; width: 184px;'>Скан</button>
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
													<label>Наименование документа</label>
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
																<td><button type='button' class='btn btn-primary chose-counterpartie-independent' type='button' id_counterpartie='{{$counterpartie->id}}' name_counterpartie='{{$counterpartie->name}}' full_name_counterpartie='{{$counterpartie->name_full}}' inn_counterpartie='{{$counterpartie->inn}}'>Выбрать</button></td>
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
					jQuery.ui.autocomplete.prototype._resizeMenu = function(){
						var ul = this.menu.element;
						ul.outerWidth(this.element.outerWidth());
					}
					var prepaymentPhraseList = ["Расчеты осуществляются:\n- аванс пропорционально полученным денежным средствам в размере до 50 % в течение 10 банковских дней с даты получения Заказчиком авансового платежа от Государственного заказчика",
										"Расчеты осуществляются:\n- авансирование в размере 50 % от общей стоимости работ в течение 10 банковских дней после подписания настоящего контракта",
										"Расчеты осуществляются:\n- предоплата (аванс) в размере 50 % в течение 10 банковских дней со дня заключения договора",
										"Авансовый платеж в размере 50 % от ориентировочной цены контракта в течение 10 банковских дней после двустороннего подписания контракта",
										"Расчеты осуществляются:\n- предоплата (аванс) в размере 50 % в течение 5 рабочих дней со дня заключения контракта путем перечисления денежных средств на отдельный счет",
										"- Аванс в размере 80 % от ориентировочной цены контракта в течение 10 рабочих дней после подписания контракта при условии получения соответствующих средств от Головного исполнителя и увеличении об открытии отдельного банковского счета в уполномоченном банке в соответствии с п. 2.1.13 контракта",
										"Расчеты осуществляются на основании выставляемых Поставщиком платежных документов в порядке:\n- аванс в размере 50 % от ориентировочной стоимости суммы контракта, в течение 10 банковских дней с момента подписания контракта при условии получения денежных средств от Государственного заказчика. Поставщик обязуется, в течение 5 банковских дней со дня получения аванса, выставить Покупателю счёт - фактуру на сумму аванса. Поставщик приступает к изготовлению продукции с момента авансирования",
										"Расчеты осуществляются:\n- аванс в размере 50 % в течение 10 банковских дней, с момента подписания контракта при условии получения денежных средств от Государственного заказчика, но не позднее 31.12.2020 г. В случае просрочки авансирования, сроки согласовываются сторонами ДС"];
					prepaymentPhraseList.sort();
					var scorePhraseList = ["- окончательный расчет производится Заказчиком в течение 10 банковских дней после получения окончательного расчета от Государственного заказчика, но не позднее 31.12.2020 г. Счета-фактуры выставляются в порядке, установленном налоговым законодательством",
										"- окончательный расчет за вычетом ранее произведенного авансового платежа в течение 10 банковских дней после подписания сторонами акта выполненных работ по фиксированной цене",
										"- окончательный расчет в течение 10 банковских дней после двухстороннего подписания акта выполненных работ. Расчеты осуществляются согласно протоколу фиксированной цены. Счет-фактура выставляется Заказчику после получения Исполнителем оформленного приемо-сдаточного акта.Окончательный расчет в размере в соответствии с оформленным сторонами протоколом фиксированной цены, в течение 10 банковских дней после подписания Заказчиком акта приемки выполненных работ и предоставления документов в соответствии с п. 2.1.6 контракта",
										"- окончательный расчет за вычетом ранее произведенного авансового платежа в течение 10 банковских дней после подписания сторонами акта сдачи-приемки выполненных работ по фиксированной цене. Расчет осуществляется согласно протоколу твердой фиксированной цены. Счет-фактура выставляется Заказчику после получения Исполнителем оформленного акта сдачи-приемки выполненных работ",
										"- окончательный расчет за вычетом ранее произведенного авансового платежа в течение 10 банковских дней после подписания акта сдачи-приемки выполненных работ (услуг) и получения соответствующего финансирования от Государственного Заказчика. Расчеты осуществляется согласно протоколу фиксированной цены",
										"- окончательный расчет в течение 10 банковских дней после подписания сторонами акта сдачи-приемки выполненных работ по фиксированной цене. Расчеты осуществляются согласно протоколу фиксированной цены. Счет-фактура выставляется Заказчику после получения Исполнителем оформленного акта сдачи-приемки выполненных работ",
										"- окончательный расчёт (с зачётом выданного аванса) производится Заказчиком в течение 10 банковских дней после подписания сторонами акта сдачи-приемки выполненных работ по фиксированной цене, получения окончательного расчета от государственного Заказчика и наличия счета на оплату. Расчет осуществляется согласно протоколу фиксированной цены. Счет-фактура выставляется Заказчику после получения Исполнителем оформленного акта сдачи-приемки выполненных работ",
										"- окончательный расчет в течение 5 рабочих дней со дня подписания акта сдачи-приемки выполненных работ путем перечисления денежных средств на отдельный счет",
										"- окончательный расчет осуществляется согласно протоколу фиксированной цены. Счет-фактура выставляется Заказчику после получения Исполнителем оформленного акта сдачи-приемки выполненных работ",
										"- окончательный расчет производится в соответствии с протоколом фиксированной цены за вычетом аванса, в течение 10 рабочих дней после получения соответствующих денежных средств от Головного исполнителя, и при условии предъявления Исполнителем документов в соответствии с п. 2.1.7, 2.1.8 контракта",
										"- окончательный расчет за поставленную продукцию производится в течение 30 банковских дней после подписания Покупателем накладной ТОРГ-12 и счета на оплату, по ценам Протоколов фиксированных цен с зачетом пропорциональной части авансового платежа, и получения денежных средств от Государственного заказчика. При получении предоплаты Поставщик не позднее 5 (пяти) рабочих дней, считая со дня получения от Покупателя сумм оплаты в счет будущей поставки изделий, обязан предоставить Покупателю авансовую счет - фактуру на сумму полученной предоплаты, оформленный в порядке, установленном действующим законодательством РФ, НДС при этом определяется в соответствии с п. 1 ст. 168 НК РФ. Датой исполнения обязательств по оплате является дата списания денежных средств с отдельного счета Покупателя.  Все платежи осуществляются с отдельного счета на отдельный счет. Счет-фактура направляется Покупателю по факту отгрузки продукции, принятой Покупателем по накладной ТОРГ-12",
										"- окончательный расчет за поставленную продукцию производится в течение 30 банковских дней после подписания Покупателем накладной ТОРГ-12 и счета  на оплату, и получения денежных средств от Государственного заказчика, но не позднее 31.12.2020 г.\nВсе платежи осуществляется с отдельного счета на отдельный счет.\nСчет-фрактура направляется Покупателю по факту отгрузки продукции, принятой Покупателем по накладной ТОРГ-12",
										"Оплата производится в течение 5 рабочих дней с момента фактического оказания Исполнителем данных услуг на основании акта выполненных работ, путем перечисления денежных средств на расчетный счет Исполнителя в размере 100 % (ста процентов) от стоимости оказываемых услуг",
										"Расчеты на основании акта сдачи-приемки выполненных работ (услуг) в течение 30 банковских дней после двухстороннего подписания акта выполненных работ",
										"Оплата производится в течение 5 дней с момента подписания фактического оказания Исполнителем данных услуг, на основании актов сдачи-приемки оказанных услуг",
										"Расчет производится путем осуществления покупателем 100% предварительной оплаты в течение 15 дней с момента заключения договора, на основании выставленного продавцом счета"];
					scorePhraseList.sort();
					var paymentPhraseList = ["Оплата 100 %",
												"Оплата производится в порядке авансового платежа 100% в течение 10 банковских дней после выставления счета договора",
												"Расчеты между сторонами производятся в безналичной форме платежными поручениями: в порядке предварительной оплату 100 %",
												"Расчеты продукцию производятся между Покупателем и Поставщиком путем авансовой оплаты платежными поручениями в размере 100% от общей суммы договора в течение 10 банковских дней после подписания договора, но не позднее, чем за два месяца до отгрузки",
												"Покупатель производит оплату продукции платежными поручениями в российских рублях на счет Поставщика на условиях 100% предоплаты в течение 10 банковских дней с момента получения Покупателем счета"];
					paymentPhraseList.sort();
					$('#prepayment_order_reestr').autocomplete({
						source: function(req, responseFn){
							var re = $.ui.autocomplete.escapeRegex(req.term);
							var matcher = new RegExp(re, "i");
							var a = $.grep(prepaymentPhraseList, function (item, index){
								return matcher.test(item);
							});
							responseFn(a);
						}
					});
					$('#score_order_reestr').autocomplete({
						source: function(req, responseFn){
							var re = $.ui.autocomplete.escapeRegex(req.term);
							var matcher = new RegExp(re, "i");
							var a = $.grep(scorePhraseList, function (item, index){
								return matcher.test(item);
							});
							responseFn(a);
						}
					});
					$('#payment_order_reestr').autocomplete({
						source: function(req, responseFn){
							var re = $.ui.autocomplete.escapeRegex(req.term);
							var matcher = new RegExp(re, "i");
							var a = $.grep(paymentPhraseList, function (item, index){
								return matcher.test(item);
							});
							responseFn(a);
						}
					});
				</script>
			@else
				@if($contract)
					<!-- ПРОСМОТР РЕЕСТРА! -->
					<div class="content">
						<form method='POST' action="{{route('department.ekonomic.update_reestr', $contract->id)}}">
							{{csrf_field()}}
							<div class="row">
								<div class='col-md-9 border-top border-bottom border-left border-right'>
									<div class='row'>
										<div class="col-md-4">
											<label>Контрагент</label>
											<div class="form-group">
												<select id="sel4" class='form-control select_counterpartie_reestr {{$errors->has("id_counterpartie_contract") ? print("inputError ") : print("")}}' name='id_counterpartie_contract' disabled >
													<option value='{{$contract->id_counterpartie_contract}}' full_name='{{$contract->full_name_counterpartie_contract}}' inn='{{$contract->inn_counterpartie_contract}}' selected>{{$contract->name_counterpartie_contract}}</option>
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
										<div class="col-md-3">
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
										<div class="col-md-4">
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
														@if(isset($_GET['isSmallPage']))
															<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $prev_contract)}}?isSmallPage=true" title='Назад' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
														@else
															<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $prev_contract)}}" title='Назад' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
														@endif
													@else
														<button class='btn btn-primary' title='Назад' disabled type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
													@endif
													@if(isset($next_contract))
														@if(isset($_GET['isSmallPage']))
															<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $next_contract)}}?isSmallPage=true" title='Вперед' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
														@else
															<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $next_contract)}}" title='Вперед' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
														@endif
													@else
														<button class='btn btn-primary' title='Вперед' disabled type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
													@endif
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class='col-md-3 border-bottom border-right border-top'>
									<div class='row'>
										<div class="col-md-6">
											<div class="form-group">
												<label for='amount_contract_reestr' class='small-text'>Сумма (окончательная)</label>
												<input id='amount_contract_reestr' class='form-control check-number' name='amount_contract_reestr' type='text' value='{{old("amount_contract_reestr") ? old("amount_contract_reestr") : $reestr->amount_contract_reestr}}' readonly />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for='amount_invoice_reestr'>Сумма по счетам</label>
												<input id='amount_invoice_reestr' class='form-control check-number' name='amount_invoice_reestr' type='text' value='{{$reestr->amount_invoice_reestr}}' readonly />
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class='row'>
								<div class='col-md-7'>
									<div class='row'>
										<div class='col-md-6 border-left border-right border-top border-bottom'>
											<div class='row'>
												<div class="col-md-4">
													<div class="form-group">
														<label for='number_pp'>№ п/п</label>
														<input id='number_pp' class='change_contract_number form-control {{$errors->has("number_pp") ? print("inputError ") : print("")}}' name='number_pp' type='text' value='{{old("number_pp") ? old("number_pp") : (strlen($contract->number_contract) > 0 ? explode("‐",$contract->number_contract)[0] : "")}}' readonly />
														@if($errors->has('number_pp'))
															<label class='msgError'>{{$errors->first('number_pp')}}</label>
														@endif
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label for='index_dep' style="font-size: 12px;">Индекс подразд.</label>
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
												<div class="col-md-4">
													<div class="form-group">
														<label for='year_contract'>Год</label>
														<input id='year_contract' class='change_contract_number form-control {{$errors->has("year_contract") ? print("inputError ") : print("")}}' name='year_contract' type='text' value='{{old("year_contract") ? old("year_contract") : $contract->year_contract}}' readonly />
														@if($errors->has('year_contract'))
															<label class='msgError'>{{$errors->first('year_contract')}}</label>
														@endif
													</div>
												</div>
											</div>
										</div>
										<div class='col-md-6 border-left border-right border-top border-bottom'>
											<div class='row'>
												<div class="col-md-6">
													<div class='form-group'>
														<label for='executor_contract_reestr' class='small-text'>Исполнитель по Дог./Контр.</label>
														<input id='executor_contract_reestr' class='form-control' name='executor_contract_reestr' type='text' value='{{old("executor_contract_reestr") ? old("executor_contract_reestr") : $reestr->executor_contract_reestr}}' readonly />
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
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
												</div>
											</div>
										</div>
									</div>
									<div class='row'>
										<div class='col-md-12 border-left border-top border-right border-bottom'>
											<div class='row'>
												<div class="col-md-4">
													<div class="form-group">
														<label for='date_contract_on_first_reestr'>Дата Дог./Контр. на 1 л.</label>
														<input id='date_contract_on_first_reestr' class='form-control {{$errors->has("date_contract_on_first_reestr") ? print("inputError ") : print("")}}' name='date_contract_on_first_reestr' value='{{old("date_contract_on_first_reestr") ? old("date_contract_on_first_reestr") : $reestr->date_contract_on_first_reestr}}' readonly />
														@if($errors->has('date_contract_on_first_reestr'))
															<label class='msgError'>{{$errors->first('date_contract_on_first_reestr')}}</label>
														@endif
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label for='date_signing_contract_reestr' style='font-size: 11px;'>Дата подписания ф-л "НТИИМ"(ФКП "НТИИМ")</label>
														<input id='date_signing_contract_reestr' class='form-control {{$errors->has("date_signing_contract_reestr") ? print("inputError ") : print("")}}' name='date_signing_contract_reestr' value='{{old("date_signing_contract_reestr") ? old("date_signing_contract_reestr") : $reestr->date_signing_contract_reestr}}' readonly />
														@if($errors->has('date_signing_contract_reestr'))
															<label class='msgError'>{{$errors->first('date_signing_contract_reestr')}}</label>
														@endif
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label for='date_control_signing_contract_reestr' class='small-text'>Контрольный срок подписания Дог./Контр.</label>
														<input id='date_control_signing_contract_reestr' class='form-control {{$errors->has("date_control_signing_contract_reestr") ? print("inputError ") : print("")}}' name='date_control_signing_contract_reestr' value='{{old("date_control_signing_contract_reestr") ? old("date_control_signing_contract_reestr") : $reestr->date_control_signing_contract_reestr}}' readonly />
														@if($errors->has('date_control_signing_contract_reestr'))
															<label class='msgError'>{{$errors->first('date_control_signing_contract_reestr')}}</label>
														@endif
													</div>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-4">
													<div class="form-group">
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
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label for='date_signing_contract_counterpartie_reestr'>Дата подписания Контрагентом</label>
														<input id='date_signing_contract_counterpartie_reestr' class='form-control {{$errors->has("date_signing_contract_counterpartie_reestr") ? print("inputError ") : print("")}}' name='date_signing_contract_counterpartie_reestr' value='{{old("date_signing_contract_counterpartie_reestr") ? old("date_signing_contract_counterpartie_reestr") : $reestr->date_signing_contract_counterpartie_reestr}}' readonly />
														@if($errors->has('date_signing_contract_counterpartie_reestr'))
															<label class='msgError'>{{$errors->first('date_signing_contract_counterpartie_reestr')}}</label>
														@endif
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label for='date_entry_into_force_reestr'>Дата вступления Дог./Контр. в силу</label>
														<input id='date_entry_into_force_reestr' class='form-control {{$errors->has("date_entry_into_force_reestr") ? print("inputError ") : print("")}}' name='date_entry_into_force_reestr' value='{{old("date_entry_into_force_reestr") ? old("date_entry_into_force_reestr") : $reestr->date_entry_into_force_reestr}}' readonly />
														@if($errors->has('date_entry_into_force_reestr'))
															<label class='msgError'>{{$errors->first('date_entry_into_force_reestr')}}</label>
														@endif
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class='col-md-5 border-left border-top border-right border-bottom'>
									<div class='row'>
										<div class="col-md-5">
											<div class="form-group">
												<label for='date_save_contract_reestr' style='font-size: 11px;'>Дата сдачи Д/К на хранение оригинала</label>
												<input id='date_save_contract_reestr' class='form-control {{$errors->has("date_save_contract_reestr") ? print("inputError ") : print("")}}' name='date_save_contract_reestr' value='{{old("date_save_contract_reestr") ? old("date_save_contract_reestr") : $reestr->date_save_contract_reestr}}' readonly />
												@if($errors->has('date_save_contract_reestr'))
													<label class='msgError'>{{$errors->first('date_save_contract_reestr')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for='place_save_contract_reestr'>Место хранения</label>
												<input id='place_save_contract_reestr' class='form-control {{$errors->has("place_save_contract_reestr") ? print("inputError ") : print("")}}' name='place_save_contract_reestr' value='{{old("place_save_contract_reestr") ? old("place_save_contract_reestr") : $reestr->place_save_contract_reestr}}' readonly />
												@if($errors->has('place_save_contract'))
													<label class='msgError'>{{$errors->first('place_save_contract')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for='sel6' style="font-size: 12px;">Тип документа</label>
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
									</div>
									<div class='row'>
										<div class="col-md-5">
											<div class="form-group">
												<label for='date_save_contract_el_reestr' class='small-text'>Дата сдачи Д/К на хранение скана</label>
												<input id='date_save_contract_el_reestr' class='form-control {{$errors->has("date_save_contract_el_reestr") ? print("inputError ") : print("")}}' name='date_save_contract_el_reestr' value='{{old("date_save_contract_el_reestr") ? old("date_save_contract_el_reestr") : $reestr->date_save_contract_el_reestr}}' readonly />
												@if($errors->has('date_save_contract_el_reestr'))
													<label class='msgError'>{{$errors->first('date_save_contract_el_reestr')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for='count_save_contract_reestr'>Срок хранения по</label>
												<input id='count_save_contract_reestr' class='form-control {{$errors->has("count_save_contract_reestr") ? print("inputError ") : print("")}}' name='count_save_contract_reestr' value='{{old("count_save_contract_reestr") ? old("count_save_contract_reestr") : $reestr->count_save_contract_reestr}}' readonly />
												@if($errors->has('count_save_contract_reestr'))
													<label class='msgError'>{{$errors->first('count_save_contract_reestr')}}</label>
												@endif
											</div>
										</div>
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
									</div>
									<div class='row'>
										<div class="col-md-5">
											<div class="form-group">
												<label for='document_success_renouncement_reestr' class='small-text'>Документ, подтверждающий отказ</label>
												<input id='document_success_renouncement_reestr' class='form-control {{$errors->has("document_success_renouncement_reestr") ? print("inputError ") : print("")}}' name='document_success_renouncement_reestr' value='{{old("document_success_renouncement_reestr") ? old("document_success_renouncement_reestr") : $contract->document_success_renouncement_reestr}}' readonly />
												@if($errors->has('document_success_renouncement_reestr'))
													<label class='msgError'>{{$errors->first('document_success_renouncement_reestr')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label for='date_renouncement_contract' class='small-text'>Дата отказа</label>
												<input id='date_renouncement_contract' class='form-control datepicker {{$errors->has("date_renouncement_contract") ? print("inputError ") : print("")}}' name='date_renouncement_contract' value='{{old("date_renouncement_contract") ? old("date_renouncement_contract") : $contract->date_renouncement_contract}}' readonly />
												@if($errors->has('date_renouncement_contract'))
													<label class='msgError'>{{$errors->first('date_renouncement_contract')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-5">
											<div class="form-group">
												<label for='number_aftair_renouncement_reestr'>№ дела</label>
												<input id='number_aftair_renouncement_reestr' class='form-control {{$errors->has("number_aftair_renouncement_reestr") ? print("inputError ") : print("")}}' name='number_aftair_renouncement_reestr' value='{{old("number_aftair_renouncement_reestr") ? old("number_aftair_renouncement_reestr") : $contract->number_aftair_renouncement_reestr}}' readonly />
												@if($errors->has('number_aftair_renouncement_reestr'))
													<label class='msgError'>{{$errors->first('number_aftair_renouncement_reestr')}}</label>
												@endif
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class='col-md-2 border-left border-top border-right border-bottom'>
									<div class='row'>
										<div class="col-md-12">
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
									</div>
									<div class='row'>
										<div class="col-md-12">
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
									</div>
								</div>
								<div class='col-md-4 border-left border-top border-right border-bottom'>
									<div class='row'>
										<div class="col-md-6">
											<div class="form-group">
												<label for='app_outgoing_number_reestr'>Заявка исх. №</label>
												<input id='app_outgoing_number_reestr' class='form-control {{$errors->has("app_outgoing_number_reestr") ? print("inputError ") : print("")}}' name='app_outgoing_number_reestr' value='{{old("app_outgoing_number_reestr") ? old("app_outgoing_number_reestr") : $reestr->app_outgoing_number_reestr}}' readonly />
												@if($errors->has('app_outgoing_number_reestr'))
													<label class='msgError'>{{$errors->first('app_outgoing_number_reestr')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for='app_incoming_number_reestr'>Вх. №</label>
												<input id='app_incoming_number_reestr' class='form-control {{$errors->has("app_incoming_number_reestr") ? print("inputError ") : print("")}}' name='app_incoming_number_reestr' value='{{old("app_incoming_number_reestr") ? old("app_incoming_number_reestr") : $reestr->app_incoming_number_reestr}}' readonly />
												@if($errors->has('app_incoming_number_reestr'))
													<label class='msgError'>{{$errors->first('app_incoming_number_reestr')}}</label>
												@endif
											</div>
										</div>
									</div>
								</div>
								<div class='col-md-2 border-left border-top border-right border-bottom'>
									<div class='row'>
										<div class="col-md-6">
											<div class="form-group">
												<label for='protocols_reestr'>Протоколы</label>
												<input id='protocols_reestr' class='form-control {{$errors->has("protocols_reestr") ? print("inputError ") : print("")}}' name='protocols_reestr' value='{{$big_date_protocol != null ? $big_date_protocol : ""}}' readonly />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for='add_agreements_reestr'>ДС</label>
												<input id='add_agreements_reestr' class='form-control {{$errors->has("add_agreements_reestr") ? print("inputError ") : print("")}}' name='add_agreements_reestr' value='{{$big_date_add_agreement != null ? $big_date_add_agreement : ""}}' readonly />
											</div>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-6">
											<div class="form-group">
												<label for='sel9' class='small-text'>Согл./Не согл.</label>
												<select id="sel9" class='form-control {{$errors->has("reconciliation_protocol_reestr") ? print("inputError ") : print("")}}' name='reconciliation_protocol_reestr' disabled >
													<option value='0' {{$reestr->reconciliation_protocol_reestr == 0 ? 'selected' : ''}}></option>
													<option value='1' {{$reestr->reconciliation_protocol_reestr == 1 ? 'selected' : ''}}>Согласовано</option>
													<option value='2' {{$reestr->reconciliation_protocol_reestr == 2 ? 'selected' : ''}}>Не согласовано</option>
												</select>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for='sel10' class='small-text'>Согл./Не согл.</label>
												<select id="sel10" class='form-control {{$errors->has("reconciliation_agreement_reestr") ? print("inputError ") : print("")}}' name='reconciliation_agreement_reestr' disabled >
													<option value='0' {{$reestr->reconciliation_agreement_reestr == 0 ? 'selected' : ''}}></option>
													<option value='1' {{$reestr->reconciliation_agreement_reestr == 1 ? 'selected' : ''}}>Согласовано</option>
													<option value='2' {{$reestr->reconciliation_agreement_reestr == 2 ? 'selected' : ''}}>Не согласовано</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class='col-md-2 border-left border-top border-right border-bottom'>
									<div class='row'>
										<div class="col-md-12">
											<div class="form-group">
												<label for='result_second_department_date_reestr'>Заключение отдела №2 Дата</label>
												<input id='result_second_department_date_reestr' class='form-control {{$errors->has("result_second_department_date_reestr") ? print("inputError ") : print("")}}' name='result_second_department_date_reestr' value='{{old("result_second_department_date_reestr") ? old("result_second_department_date_reestr") : $reestr->result_second_department_date_reestr}}' readonly />
												@if($errors->has('result_second_department_date_reestr'))
													<label class='msgError'>{{$errors->first('result_second_department_date_reestr')}}</label>
												@endif
											</div>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-12">
											<div class="form-group">
												<label for='result_second_department_number_reestr'>№</label>
												<input id='result_second_department_number_reestr' class='form-control {{$errors->has("result_second_department_number_reestr") ? print("inputError ") : print("")}}' name='result_second_department_number_reestr' value='{{old("result_second_department_number_reestr") ? old("result_second_department_number_reestr") : $reestr->result_second_department_number_reestr}}' readonly />
												@if($errors->has('result_second_department_number_reestr'))
													<label class='msgError'>{{$errors->first('result_second_department_number_reestr')}}</label>
												@endif
											</div>
										</div>
									</div>
								</div>
								<div class='col-md-2 border-all'>
									<div class='row'>
										<div class="col-md-12">
											<div class="form-group">
												<label for='date_complete_reestr'>Договор исполнен на</label>
												<input id='date_complete_reestr' class='datepicker form-control {{$errors->has("date_complete_reestr") ? print("inputError ") : print("")}}' name='date_complete_reestr' value='{{old("date_complete_reestr") ? old("date_complete_reestr") : $reestr->date_complete_reestr}}' readonly />
												@if($errors->has('date_complete_reestr'))
													<label class='msgError'>{{$errors->first('date_complete_reestr')}}</label>
												@endif
											</div>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-12">
											<div class="form-group">
												<label for='reestr_number_reestr'>Реестровый номер Д/К</label>
												<input id='reestr_number_reestr' class='datepicker form-control {{$errors->has("reestr_number_reestr") ? print("inputError ") : print("")}}' name='reestr_number_reestr' value='{{old("reestr_number_reestr") ? old("reestr_number_reestr") : $reestr->reestr_number_reestr}}' maxlength='30' readonly />
												@if($errors->has('reestr_number_reestr'))
													<label class='msgError'>{{$errors->first('reestr_number_reestr')}}</label>
												@endif
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class='row'>
								<input id='is_new_reestr' class='form-check-input' name='is_new_reestr' type="checkbox" checked style='display: none;'/>
							</div>
							<div class='row'>
								<div class="col-md-5 border-left border-top border-right border-bottom">
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
										<div class='row'>
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
											<label for='days_reconciliation_reestr' class='small-text'>Срок действия согласования крупной сделки</label>
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
											<label for='count_mounth_reestr' class='small-text'>Количество месяцев</label>
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
											<label class='small-text'>Сроки согласования проекта договора исполнителей</label>
										</div>
										<div class="col-md-3">
											<label for='begin_date_reconciliation_reestr' class='small-text'>Начало согласования (дата)</label>
											<input id='begin_date_reconciliation_reestr' class='form-control {{$errors->has("begin_date_reconciliation_reestr") ? print("inputError ") : print("")}}' name='begin_date_reconciliation_reestr' value='{{old("begin_date_reconciliation_reestr") ? old("begin_date_reconciliation_reestr") : $reestr->begin_date_reconciliation_reestr}}' readonly />
											@if($errors->has('begin_date_reconciliation_reestr'))
												<label class='msgError'>{{$errors->first('begin_date_reconciliation_reestr')}}</label>
											@endif
										</div>
										<div class="col-md-3">
											<label for='end_date_reconciliation_reestr' style='font-size: 11px;'>Окончание согласования (дата)</label>
											<input id='end_date_reconciliation_reestr' class='form-control {{$errors->has("end_date_reconciliation_reestr") ? print("inputError ") : print("")}}' name='end_date_reconciliation_reestr' value='{{old("end_date_reconciliation_reestr") ? old("end_date_reconciliation_reestr") : $reestr->end_date_reconciliation_reestr}}' readonly />
											@if($errors->has('end_date_reconciliation_reestr'))
												<label class='msgError'>{{$errors->first('end_date_reconciliation_reestr')}}</label>
											@endif
										</div>
										<div class="col-md-4">
											<label for='count_days_reconciliation_reestr' class='small-text'>Общее количество дней согласования</label>
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
							<div class="row border-all">
								<div class="col-md-12">
									<div class='row'>
										<div class="col-md-6">
											<div class="form-group">
												<label for='number_counterpartie_contract_reestr'>№ дог. контрагента</label>
												<input id='number_counterpartie_contract_reestr' class='form-control {{$errors->has("number_counterpartie_contract_reestr") ? print("inputError ") : print("")}}' name='number_counterpartie_contract_reestr' value='{{old("number_counterpartie_contract_reestr") ? old("number_counterpartie_contract_reestr") : $reestr->number_counterpartie_contract_reestr}}' readonly />
												@if($errors->has('number_counterpartie_contract_reestr'))
													<label class='msgError'>{{$errors->first('number_counterpartie_contract_reestr')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for='igk_reestr'>ИГК</label>
												<input id='igk_reestr' class='form-control {{$errors->has("igk_reestr") ? print("inputError ") : print("")}}' name='igk_reestr' value='{{ old("igk_reestr") ? old("igk_reestr") : $reestr->igk_reestr }}' readonly />
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for='ikz_reestr'>ИКЗ</label>
												<input id='ikz_reestr' class='form-control {{$errors->has("ikz_reestr") ? print("inputError ") : print("")}}' name='ikz_reestr' value='{{ old("ikz_reestr") ? old("ikz_reestr") : $reestr->ikz_reestr }}' readonly />
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
										<div class="col-md-6">
											<button class="btn btn-primary" data-toggle="modal" data-target="#history_states" type='button'>История состояний</button>
										</div>
										<div class="col-md-6">
											<button class="btn btn-primary" data-toggle="modal" data-target="#work_states" type='button'>Выполнение работ</button>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">
										</div>
										<div class="col-md-1">
											@if(isset($prev_contract))
												@if(isset($_GET['isSmallPage']))
													<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $prev_contract)}}?isSmallPage=true" title='Назад' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
												@else
													<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $prev_contract)}}" title='Назад' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
												@endif
											@else
												<button class='btn btn-primary' title='Назад' disabled type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -114px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
											@endif
										</div>
										<div class="col-md-1">
										</div>
										<div class="col-md-1">
											@if(isset($next_contract))
												@if(isset($_GET['isSmallPage']))
													<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $next_contract)}}?isSmallPage=true" title='Вперед' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
												@else
													<button class='btn btn-primary btn-href' href="{{route('department.ekonomic.contract_new_reestr', $next_contract)}}" title='Вперед' type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
												@endif
											@else
												<button class='btn btn-primary' title='Вперед' disabled type='button'><span class="ui-icon ui-icon-1-1" style="background-size: 355px; background-position-x: -69px; background-position-y: -268px; width: 18px; height: 18px;"></span></button>
											@endif
										</div>
									</div>
								</div>
								<div class="col-md-7 border-all">
									<div class="row">
										<div class="col-md-12">
											<label>Обеспечение гарантийных обязательств</label>
										</div>
										<div class="col-md-3">
											<div class='row'>
												<div class="col-md-12">
													<label for='term_action_reestr'>Срок действия</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													<input id='term_action_reestr' class='form-control {{$errors->has("term_action_reestr") ? print("inputError ") : print("")}}' name='term_action_reestr' value='{{ old("term_action_reestr") ? old("term_action_reestr") : $reestr->term_action_reestr }}' readonly />
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class='row'>
												<div class="col-md-12">
													<label for='date_bank_reestr'>Гарантия банка до</label>
												</div>
												<div class="col-md-12">
													<input id='date_bank_reestr' class='form-control {{$errors->has("date_bank_reestr") ? print("inputError ") : print("")}}' name='date_bank_reestr' value='{{ old("date_bank_reestr") ? old("date_bank_reestr") : $reestr->date_bank_reestr }}' readonly />
												</div>
											</div>
										</div>
										<div class="col-md-2">
											<div class='row'>
												<div class="col-md-12">
													<label for='amount_bank_reestr'>Сумма</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													<input id='amount_bank_reestr' class='form-control check-number {{$errors->has("amount_bank_reestr") ? print("inputError ") : print("")}}' name='amount_bank_reestr' value='{{ old("amount_bank_reestr") ? old("amount_bank_reestr") : $reestr->amount_bank_reestr }}' readonly />
												</div>
											</div>
										</div>
										<div class='col-md-4'>
											<div class='row'>
												<div class="col-md-12">
													<label for='bank_reestr'>Банк</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													<input id='bank_reestr' class='form-control {{$errors->has("bank_reestr") ? print("inputError ") : print("")}}' name='bank_reestr' value='{{ old("bank_reestr") ? old("bank_reestr") : $reestr->bank_reestr }}' readonly />
												</div>
											</div>
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
										<div class="col-md-6">
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
									</div>
									<div class='row border-bottom'>
										<div class="col-md-12">
											<div class="form-group">
												<input id='date_contract_reestr' class='form-control {{$errors->has("date_contract_reestr") ? print("inputError ") : print("")}}' name='date_contract_reestr' value='{{ old("date_contract_reestr") ? old("date_contract_reestr") : $reestr->date_contract_reestr }}' readonly />
											</div>
										</div>
									</div>
									<div class="row border-bottom">
										<div class='col-md-12'>
											<div class="form-group">
												<div class='row'>
													<div class="col-md-10">
														<label for='date_maturity_reestr'>Срок исполнения обязательств</label>
													</div>
													<div class='col-md-2'>
														<label for='date_e_maturity_reestr'>До</label>
													</div>
												</div>
												<div class='row'>
													<div class="col-md-10">
														<input id='date_maturity_reestr' class='form-control {{$errors->has("date_maturity_reestr") ? print("inputError ") : print("")}}' value='{{ old("date_maturity_reestr") ? old("date_maturity_reestr") : $reestr->date_maturity_reestr }}' readonly />
													</div>
													<div class='col-md-2'>
														<input id='date_e_maturity_reestr' class='form-control {{$errors->has("date_e_maturity_reestr") ? print("inputError ") : print("")}}' value='{{ old("date_e_maturity_reestr") ? old("date_e_maturity_reestr") : $reestr->date_e_maturity_reestr }}' readonly />
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class='row'>
												<div class="col-md-12">
													<label for='amount_begin_reestr'>Цена при заключении Д/К</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-4">
													<input id='amount_begin_reestr' class='form-control check-number {{$errors->has("amount_begin_reestr") ? print("inputError ") : print("")}}' name='amount_begin_reestr' value='{{old("amount_begin_reestr") ? old("amount_begin_reestr") : $reestr->amount_begin_reestr}}' readonly />
												</div>
												<div class="col-md-4">
													<select class='form-control {{$errors->has("unit_begin_reestr") ? print("inputError ") : print("")}}' name='unit_begin_reestr' disabled>
														<option></option>
														@foreach($units as $unit)
															@if(old('unit_begin_reestr'))
																<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
															@else
																@if($reestr->unit_begin_reestr == $unit->id)
																	<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
																@else
																	<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
																@endif
															@endif
														@endforeach
													</select>
												</div>
												<div class='col-md-4'>
													<label for='VAT_BEGIN'>НДС</label>
													@if(old('vat_begin_reestr'))
														<input id='VAT_BEGIN' class='form-check-input' name='vat_begin_reestr' type="checkbox" checked disabled />
													@else
														@if($reestr->vat_begin_reestr)
															<input id='VAT_BEGIN' class='form-check-input' name='vat_begin_reestr' type="checkbox" checked disabled />
														@else
															<input id='VAT_BEGIN' class='form-check-input' name='vat_begin_reestr' type="checkbox" disabled />
														@endif
													@endif
												</div>
											</div>
											<div class='row'>
												<div class="col-md-6">
													<label for='approximate_amount_begin_reestr'>Ориентировочная</label>
													@if(old('approximate_amount_begin_reestr'))
														<input id='approximate_amount_begin_reestr' class='form-check-input' name='approximate_amount_begin_reestr' type="checkbox" checked disabled />
													@else
														@if($reestr->approximate_amount_begin_reestr)
															<input id='approximate_amount_begin_reestr' class='form-check-input' name='approximate_amount_begin_reestr' type="checkbox" checked disabled />
														@else
															<input id='approximate_amount_begin_reestr' class='form-check-input' name='approximate_amount_begin_reestr' type="checkbox" disabled />
														@endif
													@endif
												</div>
												<div class="col-md-6">
													<label for='fixed_amount_begin_reestr'>Фиксированная</label>
													@if(old('fixed_amount_begin_reestr'))
														<input id='fixed_amount_begin_reestr' class='form-check-input' name='fixed_amount_begin_reestr' type="checkbox" checked disabled />
													@else
														@if($reestr->fixed_amount_begin_reestr)
															<input id='fixed_amount_begin_reestr' class='form-check-input' name='fixed_amount_begin_reestr' type="checkbox" checked disabled />
														@else
															<input id='fixed_amount_begin_reestr' class='form-check-input' name='fixed_amount_begin_reestr' type="checkbox" disabled />
														@endif
													@endif
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class='row'>
												<div class="col-md-12">
													<label for='amount_reestr'>Сумма Д/К</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-4">
													<input id='amount_reestr' class='form-control check-number {{$errors->has("amount_reestr") ? print("inputError ") : print("")}}' name='amount_reestr' value='{{old("amount_reestr") ? old("amount_reestr") : $reestr->amount_reestr}}' readonly />
												</div>
												<div class="col-md-3">
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
												<div class="col-md-3">
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
											<div class='row'>
												<div class="col-md-6">
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
												<div class="col-md-6">
													<label for='fixed_amount_reestr'>Фиксированная</label>
													@if(old('fixed_amount_reestr'))
														<input id='fixed_amount_reestr' class='form-check-input' name='fixed_amount_reestr' type="checkbox" checked disabled />
													@else
														@if($reestr->fixed_amount_reestr)
															<input id='fixed_amount_reestr' class='form-check-input' name='fixed_amount_reestr' type="checkbox" checked disabled />
														@else
															<input id='fixed_amount_reestr' class='form-check-input' name='fixed_amount_reestr' type="checkbox" disabled />
														@endif
													@endif
												</div>
											</div>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-12">
											<input class='form-control {{$errors->has("amount_comment_reestr") ? print("inputError ") : print("")}}' name='amount_comment_reestr' value='{{old("amount_comment_reestr") ? old("amount_comment_reestr") : $reestr->amount_comment_reestr}}' readonly />
										</div>
									</div>
									<div class='row'>
										<div class="col-md-4">
											<div class='row'>
												<div class="col-md-12">
													<label for='end_term_repayment_reestr' class='small-text'>Конечный срок оплаты по Д/К</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-9">
													<input id='end_term_repayment_reestr' class='form-control {{$errors->has("end_term_repayment_reestr") ? print("inputError ") : print("")}}' name='end_term_repayment_reestr' value='{{old("end_term_repayment_reestr") ? old("end_term_repayment_reestr") : $reestr->end_term_repayment_reestr}}' readonly />
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class='row'>
												<div class="col-md-12">
													<label for='nmcd_reestr'>НМЦД/НМЦК</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-7">
													<input id='nmcd_reestr' class='form-control check-number {{$errors->has("nmcd_reestr") ? print("inputError ") : print("")}}' name='nmcd_reestr' value='{{old("nmcd_reestr") ? old("nmcd_reestr") : $reestr->nmcd_reestr}}' readonly />
												</div>
												<div class="col-md-5">
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
										<div class="col-md-4">
											<div class='row'>
												<div class="col-md-12">
													<label for='economy_reestr'>Экономия</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-7">
													<input id='economy_reestr' class='form-control check-number {{$errors->has("economy_reestr") ? print("inputError ") : print("")}}' name='economy_reestr' value='{{old("economy_reestr") ? old("economy_reestr") : $reestr->economy_reestr}}' readonly />
												</div>
												<div class="col-md-5">
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
										<div class="col-md-12">
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
								<div class="col-md-2">
									<div class='row'>
										<div class="col-md-12">
											<button class='btn btn-primary' data-toggle="modal" data-target="#scan" type='button' style='float: right; width: 184px;'>Сканы</button>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' style='float: right; width: 184px;' type='button' href="{{route('department.peo.show_additional_documents', $contract->id)}}">Догов. мат</button>
										</div>
									</div>
									<div class='row'>
										<div class='col-md-12'>
											<button class='btn btn-primary btn-href' type='button'  href="{{route('tree_map.show_contract',$contract->id)}}" style='float: right; width: 184px;'>Граф договора</button>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' style='float: right; width: 184px;' type='button' href="{{route('department.reestr.show_obligation', $contract->id)}}">Исполнение Д/К</button>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-12">
											<button class='btn btn-primary btn-href' style='float: right; width: 184px;' type='button' href="{{route('ten.show_contract', $contract->id)}}">Комплектация</button>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<label for='small_page'>Мелкий режим</label>
									@if(isset($_GET['isSmallPage']))
										<input id='small_page' class='form-check-input btn-href' href="{{route('department.ekonomic.contract_new_reestr', $contract->id)}}" type="checkbox" checked />
									@else
										<input id='small_page' class='form-check-input btn-href' href="{{route('department.ekonomic.contract_new_reestr', $contract->id)}}?isSmallPage=true" type="checkbox"/>
									@endif
								</div>
							</div>
						</form>
					</div>
					<!-- Модальное окно выполнения контракта -->
					<div class="modal fade" id="work_states" tabindex="-1" role="dialog" aria-labelledby="workStatesModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="workStatesModalLabel">Выполнение работ</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='row'>
										<div id='table_history_work_states' class='col-md-12'>
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
															@if($resolution->deleted_at == null)
																<option value='http://{{$resolution->path_resolution}}' download_href='resolution_download/{{$resolution->id}}' delete_href='{{route("resolution_delete",$resolution->id)}}'>{{$resolution->real_name_resolution}}</option>
															@endif
														@endforeach
													@else
														<option></option>
													@endif
												</select>
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
												<button id='open_resolution' type='button' class='btn btn-primary' style='width: 122px;'>Открыть скан</button>
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