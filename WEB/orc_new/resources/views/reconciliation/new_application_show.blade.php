@extends('layouts.header')

@section('title')
	Заявка
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		<style>
			.form-group {
				margin-bottom: 0px;
			}
		</style>
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Отдел управления договорами' OR Auth::User()->hasRole()->role == 'Десятый отдел' OR Auth::User()->hasRole()->role == 'Администрация' OR Auth::User()->hasRole()->role == 'Второй отдел')
				<div class="content">
					@if(!isset($is_new_application))
						<form id='main_form_new_application' method='POST' action='{{route("new_applications.update", $new_application->id)}}'>
					@else
						<form method='POST' action='{{route("new_applications.store")}}?method={{$method}}'>
					@endif
						{{csrf_field()}}
						<div class='row'>
							<div class="col-md-2">
								@if($new_application->is_contract_new_application == 1)
									<label>На заключение Д/К</label>
								@else
									<label>РКМ и др.</label>
								@endif
							</div>
							<div class="col-md-4">
								<input class='form-control' style='color:red; text-align:center;' type='text' value='<?php 
									if($new_application->date_registration_new_application)
										if(!$new_application->result_outgoing_new_application){
											if(time() - strtotime($new_application->date_registration_new_application) > 518400)
												echo 'Нет ответа по заявке более 5 дней!';
										}
								?>' readonly />
							</div>
						</div>
						<div class='row'>
							<div class="col-md-6">
								<div class='row'>
									<div class="col-md-2">
										<div class="form-group">
											<label>Номер п/п</label>
											<input class='form-control' type='text' value='{{$new_application->number_pp_new_application}}' readonly />
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class='small-text'>Дата регистрации заявки</label>
											<input class='datepicker form-control' type='text' value='{{old("date_registration_new_application") ? old("date_registration_new_application") : $new_application->date_registration_new_application}}' name='date_registration_new_application' required />
										</div>
									</div>
									<div class="col-md-5">
										<label>Контрагент</label>
										<div class="form-group">
											<select id="sel4" class='form-control select_counterpartie_reestr {{$errors->has("id_counterpartie_new_application") ? print("inputError ") : print("")}}' name='id_counterpartie_new_application' required >
												<option></option>
												<option value='{{$new_application->id_counterpartie_new_application}}' full_name='{{$new_application->full_name_counterpartie_contract}}' inn='{{$new_application->inn_counterpartie_contract}}' selected>{{$new_application->name_counterpartie_contract}}</option>
											</select>
											@if($errors->has('id_counterpartie_new_application'))
												<label class='msgError'>{{$errors->first('id_counterpartie_new_application')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<button type='button' data-toggle="modal" data-target="#chose_counterpartie" class="btn btn-primary" style='margin-top: 27px;'>Выбрать</button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class='row'>
									<div class="col-md-3">
										<div class="form-group">
											<label>Заявка Исх. №</label>
											<input class='form-control' type='text' name='number_outgoing_new_application' value='{{old("number_outgoing_new_application") ? old("number_outgoing_new_application") : $new_application->number_outgoing_new_application}}' />
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>н/Вх.</label>
											<input class='form-control' type='text' name='number_incoming_new_application' value='{{old("number_incoming_new_application") ? old("number_incoming_new_application") : $new_application->number_incoming_new_application}}' />
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class='small-text'>Ответ по Заявке Исх. №</label>
											<input class='form-control' type='text' name='result_outgoing_new_application' value='{{old("result_outgoing_new_application") ? old("result_outgoing_new_application") : $new_application->result_outgoing_new_application}}' />
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label>Исполнитель</label>
											<input class='form-control' type='text' name='executor_new_application' value='{{old("executor_new_application") ? old("executor_new_application") : $new_application->executor_new_application}}' />
										</div>
									</div>									
								</div>
							</div>
						</div>
						<div class='row'>
							<div class='col-md-3'>
								<div class='form-group'>
									<label>Предмет (содержание заявки)</label>
									<textarea class='form-control {{$errors->has("item_new_application") ? print("inputError ") : print("")}}' name='item_new_application' type="text" style="width: 100%;" rows='2' required>{{ old('item_new_application') ? old('item_new_application') : $new_application->item_new_application }}</textarea>
								</div>
							</div>
							<div class='col-md-3'>
								<div class='form-group'>
									<label>Цель (если указана)</label>
									<textarea class='form-control {{$errors->has("name_work_new_application") ? print("inputError ") : print("")}}' name='name_work_new_application' type="text" style="width: 100%;" rows='2'>{{ old('name_work_new_application') ? old('name_work_new_application') : $new_application->name_work_new_application }}</textarea>
								</div>
							</div>
							<div class='col-md-3'>
								<div class='form-group'>
									<label>Срок исполнения (если указан)</label>
									<textarea class='form-control {{$errors->has("term_maturity_new_application") ? print("inputError ") : print("")}}' name='term_maturity_new_application' type="text" style="width: 100%;" rows='2'>{{ old('term_maturity_new_application') ? old('term_maturity_new_application') : $new_application->term_maturity_new_application }}</textarea>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Телефон</label>
									<input class='form-control' type='text' name='telephone_new_application' value='{{old("telephone_new_application") ? old("telephone_new_application") : $new_application->telephone_new_application}}' />
								</div>
							</div>
						</div>
						<div class='row'>
							@if($new_application->is_contract_new_application == 1)
								<div class='col-md-3'>
									<div class="form-group">
										<label class='form-check-label' for='on_dk_new_application'>На заключение Д/К</label>
										@if(old('on_dk_new_application'))
											<input id='on_dk_new_application' class='form-check-input' name='on_dk_new_application' type="checkbox" checked />
										@else
											@if ($new_application->on_dk_new_application == 1)
												<input id='on_dk_new_application' class='form-check-input' name='on_dk_new_application' type="checkbox" checked />
											@else
												<input id='on_dk_new_application' class='form-check-input' name='on_dk_new_application' type="checkbox"/>
											@endif
										@endif
									</div>
								</div>
								<div class='col-md-6'>
								</div>
							@else
								<div class='col-md-2'>
									<div class="form-group">
										<label class='form-check-label' for='call_price_new_application'>Запрос цены</label>
										@if(old('call_price_new_application'))
											<input id='call_price_new_application' class='form-check-input' name='call_price_new_application' type="checkbox" checked />
										@else
											@if ($new_application->call_price_new_application == 1)
												<input id='call_price_new_application' class='form-check-input' name='call_price_new_application' type="checkbox" checked />
											@else
												<input id='call_price_new_application' class='form-check-input' name='call_price_new_application' type="checkbox"/>
											@endif
										@endif
									</div>
								</div>
								<div class='col-md-2'>
									<div class="form-group">
										<label class='form-check-label' for='result_vp_new_application'>Заключение ВП МО РФ</label>
										@if(old('result_vp_new_application'))
											<input id='result_vp_new_application' class='form-check-input' name='result_vp_new_application' type="checkbox" checked />
										@else
											@if ($new_application->result_vp_new_application == 1)
												<input id='result_vp_new_application' class='form-check-input' name='result_vp_new_application' type="checkbox" checked />
											@else
												<input id='result_vp_new_application' class='form-check-input' name='result_vp_new_application' type="checkbox"/>
											@endif
										@endif
									</div>
								</div>
								<div class='col-md-1'>
									<div class="form-group">
										<label class='form-check-label' for='rkm_new_application'>РКМ</label>
										@if(old('rkm_new_application'))
											<input id='rkm_new_application' class='form-check-input' name='rkm_new_application' type="checkbox" checked />
										@else
											@if ($new_application->rkm_new_application == 1)
												<input id='rkm_new_application' class='form-check-input' name='rkm_new_application' type="checkbox" checked />
											@else
												<input id='rkm_new_application' class='form-check-input' name='rkm_new_application' type="checkbox"/>
											@endif
										@endif
									</div>
								</div>
								<div class='col-md-2'>
									<div class="form-group">
										<label class='form-check-label' for='other_new_application'>Иное</label>
										@if(old('other_new_application'))
											<input id='other_new_application' class='form-check-input' name='other_new_application' type="checkbox" checked />
										@else
											@if ($new_application->other_new_application == 1)
												<input id='other_new_application' class='form-check-input' name='other_new_application' type="checkbox" checked />
											@else
												<input id='other_new_application' class='form-check-input' name='other_new_application' type="checkbox"/>
											@endif
										@endif
									</div>
								</div>
								<div class='col-md-2'>
								</div>
							@endif
							@if(!isset($is_new_application))
								<div class='col-md-1'>
									<div class="form-group">
										<button class='btn btn-primary' type='button' data-toggle='modal' data-target='#editResolutionContract' style='width: 100px;'>Сканы</button>
									</div>
								</div>
								<div class='col-md-2'>
									<div class="form-group">
										<button type='button' class="btn btn-primary btn-href" href="{{route('new_applications.copying', $new_application->id)}}" style='width: 100px;'>Переписка</button>
									</div>
								</div>
							@endif
						</div>
						<div class='row'>
							<div class='col-md-5'>
								<div class='row'>
									<div class='col-md-4'>
										<div class="form-group">
											@if(old('isp_new_application'))
												<input id='isp_new_application' class='form-check-input' name='isp_new_application' type="checkbox" checked />
											@else
												@if ($new_application->isp_new_application == 1)
													<input id='isp_new_application' class='form-check-input' name='isp_new_application' type="checkbox" checked />
												@else
													<input id='isp_new_application' class='form-check-input' name='isp_new_application' type="checkbox"/>
												@endif
											@endif
											<label class='form-check-label' for='isp_new_application'>Испытания</label>
										</div>
									</div>
									<div class='col-md-3'>
										<div class="form-group">
											@if(old('goz_new_application'))
												<input id='goz_new_application' class='form-check-input' name='goz_new_application' type="checkbox" checked />
											@else
												@if ($new_application->goz_new_application == 1)
													<input id='goz_new_application' class='form-check-input' name='goz_new_application' type="checkbox" checked />
												@else
													<input id='goz_new_application' class='form-check-input' name='goz_new_application' type="checkbox"/>
												@endif
											@endif
											<label class='form-check-label' for='goz_new_application'>ГОЗ</label>
										</div>
									</div>
									<div class='col-md-5'>
										<div class="form-group">
											@if(old('interfactory_new_application'))
												<input id='interfactory_new_application' class='form-check-input' name='interfactory_new_application' type="checkbox" checked />
											@else
												@if ($new_application->interfactory_new_application == 1)
													<input id='interfactory_new_application' class='form-check-input' name='interfactory_new_application' type="checkbox" checked />
												@else
													<input id='interfactory_new_application' class='form-check-input' name='interfactory_new_application' type="checkbox"/>
												@endif
											@endif
											<label class='form-check-label' for='interfactory_new_application'>Межзаводские</label>
										</div>
									</div>
									<div class='col-md-4'>
										<div class="form-group">
											@if(old('sb_new_application'))
												<input id='sb_new_application' class='form-check-input' name='sb_new_application' type="checkbox" checked />
											@else
												@if ($new_application->sb_new_application == 1)
													<input id='sb_new_application' class='form-check-input' name='sb_new_application' type="checkbox" checked />
												@else
													<input id='sb_new_application' class='form-check-input' name='sb_new_application' type="checkbox"/>
												@endif
											@endif
											<label class='form-check-label' for='sb_new_application'>Сборка</label>
										</div>
									</div>
									<div class='col-md-3'>
										<div class="form-group">
											@if(old('export_new_application'))
												<input id='export_new_application' class='form-check-input' name='export_new_application' type="checkbox" checked />
											@else
												@if ($new_application->export_new_application == 1)
													<input id='export_new_application' class='form-check-input' name='export_new_application' type="checkbox" checked />
												@else
													<input id='export_new_application' class='form-check-input' name='export_new_application' type="checkbox"/>
												@endif
											@endif
											<label class='form-check-label' for='export_new_application'>Экспорт</label>
										</div>
									</div>
									<div class='col-md-5'>
										<div class="form-group">
											@if(old('view_other_new_application'))
												<input id='view_other_new_application' class='form-check-input' name='view_other_new_application' type="checkbox" checked />
											@else
												@if ($new_application->view_other_new_application == 1)
													<input id='view_other_new_application' class='form-check-input' name='view_other_new_application' type="checkbox" checked />
												@else
													<input id='view_other_new_application' class='form-check-input' name='view_other_new_application' type="checkbox"/>
												@endif
											@endif
											<label class='form-check-label' for='view_other_new_application'>Иное</label>
										</div>
									</div>
									<div class='col-md-5'>
										<div class="form-group">
											@if(old('storage_new_application'))
												<input id='storage_new_application' class='form-check-input' name='storage_new_application' type="checkbox" checked />
											@else
												@if ($new_application->storage_new_application == 1)
													<input id='storage_new_application' class='form-check-input' name='storage_new_application' type="checkbox" checked />
												@else
													<input id='storage_new_application' class='form-check-input' name='storage_new_application' type="checkbox"/>
												@endif
											@endif
											<label class='form-check-label' for='storage_new_application'>Хранение</label>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class='col-md-12'>
										<table class='table table-bordered'>
											<thead>
												<tr>
													<th>Подразделение</th>
													<th>Исполнитель</th>
													<th>Заключение</th>
													<th>Дата</th>
													<th>ФИО</th>
												</tr>
											</thead>
											<tbody>
												@foreach($contractions as $contraction)
													<tr>
														<td>{{$contraction->department}}</td>
														<td>{{$contraction->executor}}</td>
														<td>{{$contraction->result}}</td>
														<td>{{$contraction->date}}</td>
														<td>{{$contraction->FIO}}</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
									<div class='col-md-12'>
										@if(isset($new_application->id))
											<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modalContractions'>Заключения</button>
										@endif
									</div>
								</div>
								<!--
								<div class='row'>
									<div class='col-md-6'>
										<div class="form-group">
											<label>Исполнитель отдела №2</label>
											<input class='form-control' type='text' name='executor_second_new_application' value='{{old("executor_second_new_application") ? old("executor_second_new_application") : $new_application->executor_second_new_application}}' />
										</div>
									</div>
								</div>
								<div class='row'>
									<div class='col-md-6' style='padding-right: 0px;'>
										<div class="form-group">
											<label>Заключение отдела №2</label>
											<input class='form-control' type='text' name='result_second_new_application' value='{{old("result_second_new_application") ? old("result_second_new_application") : $new_application->result_second_new_application}}' />
										</div>
									</div>
									<div class='col-md-3' style='padding-right: 0px;'>
										<div class="form-group">
											<label>Дата</label>
											<input class='form-control datepicker' type='text' name='date_second_new_application' value='{{old("date_second_new_application") ? old("date_second_new_application") : $new_application->date_second_new_application}}' />
										</div>
									</div>
									<div class='col-md-3' style='padding-right: 0px;'>
										<div class="form-group">
											<label>ФИО</label>
											<input class='form-control' type='text' name='fio_second_new_application' value='{{old("fio_second_new_application") ? old("fio_second_new_application") : $new_application->fio_second_new_application}}' />
										</div>
									</div>
								</div>
								<div class='row'>
									<div class='col-md-6'>
										<div class="form-group">
											<label>Исполнитель отдела №10</label>
											<input class='form-control' type='text' name='executor_ten_new_application' value='{{old("executor_ten_new_application") ? old("executor_ten_new_application") : $new_application->executor_ten_new_application}}' />
										</div>
									</div>
								</div>
								<div class='row'>
									<div class='col-md-6' style='padding-right: 0px;'>
										<div class="form-group">
											<label>Заключение отдела №10</label>
											<input class='form-control' type='text' name='result_ten_new_application' value='{{old("result_ten_new_application") ? old("result_ten_new_application") : $new_application->result_ten_new_application}}' />
										</div>
									</div>
									<div class='col-md-3' style='padding-right: 0px;'>
										<div class="form-group">
											<label>Дата</label>
											<input class='form-control datepicker' type='text' name='date_ten_new_application' value='{{old("date_ten_new_application") ? old("date_ten_new_application") : $new_application->date_ten_new_application}}' />
										</div>
									</div>
									<div class='col-md-3' style='padding-right: 0px;'>
										<div class="form-group">
											<label>ФИО</label>
											<input class='form-control' type='text' name='fio_ten_new_application' value='{{old("fio_ten_new_application") ? old("fio_ten_new_application") : $new_application->fio_ten_new_application}}' />
										</div>
									</div>
								</div>
								<div class='row'>
									<div class='col-md-6'>
										<div class="form-group">
											<label>Исполнитель ОГТ</label>
											<input class='form-control' type='text' name='executor_ogt_new_application' value='{{old("executor_ogt_new_application") ? old("executor_ogt_new_application") : $new_application->executor_ogt_new_application}}' />
										</div>
									</div>
								</div>
								<div class='row'>
									<div class='col-md-6' style='padding-right: 0px;'>
										<div class="form-group">
											<label>Заключение главного технолога</label>
											<input class='form-control' type='text' name='result_ogt_new_application' value='{{old("result_ogt_new_application") ? old("result_ogt_new_application") : $new_application->result_ogt_new_application}}' />
										</div>
									</div>
									<div class='col-md-3' style='padding-right: 0px;'>
										<div class="form-group">
											<label>Дата</label>
											<input class='form-control datepicker' type='text' name='date_ogt_new_application' value='{{old("date_ogt_new_application") ? old("date_ogt_new_application") : $new_application->date_ogt_new_application}}' />
										</div>
									</div>
									<div class='col-md-3' style='padding-right: 0px;'>
										<div class="form-group">
											<label>ФИО</label>
											<input class='form-control' type='text' name='fio_ogt_new_application' value='{{old("fio_ogt_new_application") ? old("fio_ogt_new_application") : $new_application->fio_ogt_new_application}}' />
										</div>
									</div>
								</div>
								-->
							</div>
							<div class='col-md-7'>
								<div class='row'>
									<div class='col-md-12'>
										<label>Направлено по резолюции директора филиала (главного инженера) на согласование:</label>
									</div>
									<div class='col-md-12'>
										<table class='table table-bordered'>
											<thead>
												<tr>
													<th>ФИО</th>
													<th>Дата</th>
													<th>Согласовано (не согласовано)</th>
													<th>Замечания (предложения и др.)</th>
												</tr>
											</thead>
											<tbody>
												@foreach($directed_list as $user)
													<tr>
														<td>{{$user->surname}} {{$user->name}} {{$user->patronymic}}</td>
														<td>{{isset($user->date_check_agree_reconciliation) ? $user->date_check_agree_reconciliation : $user->date_check_reconciliation}}</td>
														<td>
															@if($user->check_agree_reconciliation == 1)
																Согласовано
															@elseif($user->check_reconciliation == 1)
																Ознакомлен(а)
															@endif
														</td>
														<td></td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
									<div class='col-md-12'>
										@if(isset($new_application->id))
											<button type='button' class='btn btn-primary btn-href' href="{{route('new_applications.reconciliation', $new_application->id)}}">Согласование</button>
										@endif
									</div>
								</div>
								@if($new_application->is_contract_new_application == 1)
									<div class='row'>
										<div class='col-md-12'>
											<label>Начальник СИП: Решение о заключении (не заключении) Д/К по Заявке</label>
										</div>
									</div>
									<div class='row'>
										<div class='col-md-6'>
											<div class="form-group">
												<label>Решение</label>
												<input class='form-control' type='text' name='result_sip_new_application' value='{{old("result_sip_new_application") ? old("result_sip_new_application") : $new_application->result_sip_new_application}}' />
											</div>
										</div>
										<div class='col-md-3'>
											<div class="form-group">
												<label>Дата</label>
												<input class='form-control datepicker' type='text' name='date_sip_new_application' value='{{old("date_sip_new_application") ? old("date_sip_new_application") : $new_application->date_sip_new_application}}' />
											</div>
										</div>
										<div class='col-md-3'>
											<div class="form-group">
												<label>ФИО</label>
												<input class='form-control' type='text' name='fio_sip_new_application' value='{{old("fio_sip_new_application") ? old("fio_sip_new_application") : $new_application->fio_sip_new_application}}' />
											</div>
										</div>
									</div>
								@else
									<div class='row'>
										<div class='col-md-12'>
											<div class='row'>
												<div class='col-md-3'>
												</div>
												<div class='col-md-4'>
													<label>Решение по Заявке</label>
												</div>
												<div class='col-md-2'>
													<label>Дата</label>
												</div>
												<div class='col-md-3'>
													<label>ФИО</label>
												</div>
											</div>
											<div class='row'>
												<div class='col-md-3'>
													<label class='small-text'>Начальник СИП</label>
												</div>
												<div class='col-md-4'>
													<input class='form-control' type='text' name='result_sip_new_application' value='{{old("result_sip_new_application") ? old("result_sip_new_application") : $new_application->result_sip_new_application}}' />
												</div>
												<div class='col-md-2'>
													<input class='form-control datepicker' type='text' name='date_sip_new_application' value='{{old("date_sip_new_application") ? old("date_sip_new_application") : $new_application->date_sip_new_application}}' />
												</div>
												<div class='col-md-3'>
													<input class='form-control' type='text' name='fio_sip_new_application' value='{{old("fio_sip_new_application") ? old("fio_sip_new_application") : $new_application->fio_sip_new_application}}' />
												</div>
											</div>
											<div class='row'>
												<div class='col-md-3'>
													<label class='small-text'>Зам. директора филиала по экономике и финансам</label>
												</div>
												<div class='col-md-4'>
													<input class='form-control' type='text' name='result_fin_new_application' value='{{old("result_fin_new_application") ? old("result_fin_new_application") : $new_application->result_fin_new_application}}' />
												</div>
												<div class='col-md-2'>
													<input class='form-control datepicker' type='text' name='date_fin_new_application' value='{{old("date_fin_new_application") ? old("date_fin_new_application") : $new_application->date_fin_new_application}}' />
												</div>
												<div class='col-md-3'>
													<input class='form-control' type='text' name='fio_fin_new_application' value='{{old("fio_fin_new_application") ? old("fio_fin_new_application") : $new_application->fio_fin_new_application}}' />
												</div>
											</div>
										</div>
									</div>
								@endif
								<div class='row'>
									<div class='col-md-5'>
									</div>
									<div class='col-md-2'>
										<label>Результат</label>
									</div>
								</div>
								<div class='row'>
									<div class='col-md-3'>
									</div>
									<div class='col-md-3'>
										<div class="form-group">
											@if(old('agree_new_application'))
												<input id='agree_new_application' class='form-check-input' name='agree_new_application' type="checkbox" checked />
											@else
												@if ($new_application->agree_new_application == 1)
													<input id='agree_new_application' class='form-check-input' name='agree_new_application' type="checkbox" checked />
												@else
													<input id='agree_new_application' class='form-check-input' name='agree_new_application' type="checkbox"/>
												@endif
											@endif
											<label class='form-check-label' for='agree_new_application'>Заявка принята</label>
										</div>
									</div>
									<div class='col-md-3'>
										<div class="form-group">
											@if(old('rejection_new_application'))
												<input id='rejection_new_application' class='form-check-input' name='rejection_new_application' type="checkbox" checked />
											@else
												@if ($new_application->rejection_new_application == 1)
													<input id='rejection_new_application' class='form-check-input' name='rejection_new_application' type="checkbox" checked />
												@else
													<input id='rejection_new_application' class='form-check-input' name='rejection_new_application' type="checkbox"/>
												@endif
											@endif
											<label class='form-check-label' for='rejection_new_application'>Заявка отклонена</label>
										</div>
									</div>
								</div>
								@if(!isset($is_new_application))
									@if($new_application->is_contract_new_application == 1)
										<div class='row'>
											<div class='col-md-12'>
												<label>Составление ведомости:</label>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-3'>
												<div class="form-group">
													<label>Дата составления</label>
												</div>
											</div>
											<div class='col-md-3'>
												<div class="form-group">
													<label>Исполнитель</label>
												</div>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-3'>
												<div class="form-group">
													<input class='form-control datepicker' type='text' name='date_roll_new_application' value='{{old("date_roll_new_application") ? old("date_roll_new_application") : $new_application->date_roll_new_application}}' />
												</div>
											</div>
											<div class='col-md-3'>
												<div class="form-group">
													<input class='form-control' type='text' name='execution_roll_new_application' value='{{old("execution_roll_new_application") ? old("execution_roll_new_application") : $new_application->execution_roll_new_application}}' />
												</div>
											</div>
											<div class='col-md-6'>
												<div class="form-group">
													<button class='btn btn-primary' type='button' data-toggle='modal' data-target='#editResolutionRollContract'>Сканы ведомостей</button>
												</div>
											</div>
										</div>
									@endif
								@endif
								<div class='row'>
									<div class='col-md-3'>
										<div class="form-group">
											<label class='small-text'>Дата поступления Заявки в ПЭО</label>
											<input class='form-control datepicker' type='text' name='date_reception_peo_new_application' value='{{old("date_reception_peo_new_application") ? old("date_reception_peo_new_application") : $new_application->date_reception_peo_new_application}}' />
										</div>
									</div>
									@if($new_application->is_contract_new_application == 1)
										<div class='col-md-4'>
											<div class="form-group">
												<label class='small-text'>Ответственный ПЭО за заключение Д/К</label>
												<input class='form-control' type='text' name='executor_peo_new_application' value='{{old("executor_peo_new_application") ? old("executor_peo_new_application") : $new_application->executor_peo_new_application}}' />
											</div>
										</div>
										<div class='col-md-3'>
											<div class="form-group">
												<label>Количество Д/К по Заявке</label>
												<input class='form-control' type='text' value='{{old("count_dk_new_application") ? old("count_dk_new_application") : $new_application->count_dk_new_application}}' readonly />
											</div>
										</div>
									@else
										<div class='col-md-6'>
											<div class="form-group">
												<label class='small-text'>Ответственный ПЭО за исполнение ответа по Заявке</label>
												<input class='form-control' type='text' name='executor_peo_new_application' value='{{old("executor_peo_new_application") ? old("executor_peo_new_application") : $new_application->executor_peo_new_application}}' />
											</div>
										</div>
									@endif
								</div>
								@if($new_application->is_rkm_new_application == 1)
									<div class='row'>
										<div class='col-md-5'>
										</div>
										<div class='col-md-2'>
											<label>Цена в РКМ</label>
										</div>
									</div>
									<div class='row'>
										<div class='col-md-1'>
										</div>
										<div class='col-md-4'>
											<div class="form-group">
												<div class='row'>
													<div class='col-md-12'>
														<label>Цена ориентировочная</label>
													</div>
												</div>
												<div class='row'>
													<div class='col-md-10'>
														<input class='form-control check-number' type='text' name='price_approximate_new_application' value='{{old("price_approximate_new_application") ? old("price_approximate_new_application") : $new_application->price_approximate_new_application}}' />
													</div>
													<div class='col-md-2'>
														@if(old('check_approximate_new_application'))
															<input id='check_approximate_new_application' class='form-check-input' name='check_approximate_new_application' type="checkbox" checked />
														@else
															@if ($new_application->check_approximate_new_application == 1)
																<input id='check_approximate_new_application' class='form-check-input' name='check_approximate_new_application' type="checkbox" checked />
															@else
																<input id='check_approximate_new_application' class='form-check-input' name='check_approximate_new_application' type="checkbox"/>
															@endif
														@endif
													</div>
												</div>
											</div>
										</div>
										<div class='col-md-1'>
										</div>
										<div class='col-md-4'>
											<div class="form-group">
												<div class='row'>
													<div class='col-md-12'>
														<label>Цена фиксированная</label>
													</div>
												</div>
												<div class='row'>
													<div class='col-md-10'>
														<input class='form-control check-number' type='text' name='price_fixed_new_application' value='{{old("price_fixed_new_application") ? old("price_fixed_new_application") : $new_application->price_fixed_new_application}}' />
													</div>
													<div class='col-md-2'>
														@if(old('check_fixed_new_application'))
															<input id='check_fixed_new_application' class='form-check-input' name='check_fixed_new_application' type="checkbox" checked />
														@else
															@if ($new_application->check_fixed_new_application == 1)
																<input id='check_fixed_new_application' class='form-check-input' name='check_fixed_new_application' type="checkbox" checked />
															@else
																<input id='check_fixed_new_application' class='form-check-input' name='check_fixed_new_application' type="checkbox"/>
															@endif
														@endif
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class='row'>
										<div class='col-md-6'>
											<div class="form-group">
												<label>Ответ (Краткое содержание)</label>
												<input class='form-control' type='text' name='answer_new_application' value='{{old("answer_new_application") ? old("answer_new_application") : $new_application->answer_new_application}}' />
											</div>
										</div>
										<div class='col-md-3'>
											<div class="form-group">
												<label>Исх. (н/Вх.)</label>
												<input class='form-control' type='text' name='out_in_answer_new_application' value='{{old("out_in_answer_new_application") ? old("out_in_answer_new_application") : $new_application->out_in_answer_new_application}}' />
											</div>
										</div>
										<div class='col-md-3'>
											<div class="form-group">
												<label>Дата</label>
												<input class='form-control datepicker' type='text' name='date_answer_new_application' value='{{old("date_answer_new_application") ? old("date_answer_new_application") : $new_application->date_answer_new_application}}' />
											</div>
										</div>
									</div>
								@endif
							</div>
						</div>
						<div class='row'>
							<div class='col-md-8'>
							</div>
							@if(!isset($is_new_application))
								@if($new_application->is_contract_new_application == 1)
									<div class='col-md-2'>
										<div class="form-group">
											<button type='submit' class="btn btn-primary" onclick="$('#main_form_new_application').attr('action', '{{route('new_applications.create_contract', $new_application->id)}}');" {{$new_application->id_contract_new_application != null ? 'disabled' : '' }}>Перевести в договор СИП</button>
										</div>
									</div>
								@else
									<div class='col-md-1'></div>
								@endif
								<div class='col-md-1'>
									<div class="form-group">
										<button type='submit' class="btn btn-primary">Сохранить</button>
									</div>
								</div>
							@else
								<div class='col-md-1'></div>
								<div class='col-md-1'>
									<div class="form-group">
										<button type='submit' class="btn btn-primary">Создать</button>
									</div>
								</div>
							@endif
						</div>
					</form>
				</div>
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
				</script>
				<!-- Окно просмотра сканов заявки -->
				<div class="modal fade" id="editResolutionContract" tabindex="-1" role="dialog" aria-labelledby="showResolutionContractModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div id='showInRowEditResolution'>
								<div class="modal-header">
									<h5 class="modal-title" id="showResolutionContractModalLabel">Сканы договора</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='modal-body'>
									<div class='row'>
										<div class="col-md-3">
											<label>Резолюция:</label>
										</div>
										<div class="col-md-5">
											@if(Auth::User()->hasRole()->role != 'Администрация')
												<button id='add_new_resolution' type='button' class='btn btn-secondary steps' first_step='#editResolutionContract #showInRowEditResolution' second_step='#editResolutionContract #formInShowNewResolution'>Добавить скан</button>
											@endif
										</div>
										<div class="col-md-4" style='text-align: right;'>
											<button type='button' class='btn btn-secondary steps' first_step='#editResolutionContract #showInRowEditResolution' second_step='#editResolutionContract #updateInShowNewResolution'>Управление сканами</button>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-3">
										</div>
										<div class="col-md-6">
											<select id='resolution_list_only_contract' name='resolution_list' class='form-control {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
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
										<div class="col-md-3">
											<button type='button' class='btn btn-primary open_resolution' resolution_block='resolution_list_only_contract' style='width: 122px;'>Открыть скан</button>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</div>
							<form id='formInShowNewResolution' method='POST' file='true' enctype='multipart/form-data' action='{{route("resolution_store", $new_application->id)}}' style='display: none;'>
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
											<input type='text' value='id_new_application_resolution' name='real_name_document'/>
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
									<button type="button" class="btn btn-secondary steps" first_step='#editResolutionContract #formInShowNewResolution' second_step='#editResolutionContract #showInRowEditResolution'>Закрыть</button>
								</div>
							</form>
							<div id='updateInShowNewResolution' style='display: none;'>
								<div class="modal-header">
									<h5 class="modal-title" id="updateApplicationModalLabel">Управление резолюциями</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='modal-body'>
									<div class='row'>
										<div class='col-md-12'>
											<div id='divMessageContract'>
											</div>
											<table class="table" style='margin: 0 auto;'>
												<thead>
													<tr>
														<th>Название резолюции</th>
														<th>Удаление</th>
													</tr>
												</thead>
												<tbody>
													@foreach($resolutions as $resolution)
														@if($resolution->deleted_at == null)
															<tr class='rowsContract'>
																<td>{{$resolution->real_name_resolution}}</td>
																<td><button class='btn btn-danger ajax-send-on-delete-href' ajax-href="{{route('resolution_contract_delete_ajax', $resolution->id)}}" ajax-method='GET' div-message='#divMessageContract'>Удалить</button></td>
															</tr>
														@endif
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary steps" first_step='#editResolutionContract #updateInShowNewResolution' second_step='#editResolutionContract #showInRowEditResolution'>Закрыть</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Окно просмотра сканов ведомости заявки -->
				<div class="modal fade" id="editResolutionRollContract" tabindex="-1" role="dialog" aria-labelledby="showResolutionRollContractModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div id='showInRowEditResolutionRoll'>
								<div class="modal-header">
									<h5 class="modal-title" id="showResolutionRollContractModalLabel">Сканы ведомости</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='modal-body'>
									<div class='row'>
										<div class="col-md-3">
											<label>Резолюция:</label>
										</div>
										<div class="col-md-5">
											@if(Auth::User()->hasRole()->role != 'Администрация')
												<button id='add_new_resolution' type='button' class='btn btn-secondary steps' first_step='#editResolutionRollContract #showInRowEditResolutionRoll' second_step='#editResolutionRollContract #formInShowNewResolutionRoll'>Добавить скан</button>
											@endif
										</div>
										<div class="col-md-4" style='text-align: right;'>
											<button type='button' class='btn btn-secondary steps' first_step='#editResolutionRollContract #showInRowEditResolutionRoll' second_step='#editResolutionRollContract #updateInShowNewResolutionRoll'>Управление сканами</button>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-3">
										</div>
										<div class="col-md-6">
											<select id='resolution_list_only_contract' name='resolution_list' class='form-control {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
												@if(count($resolutions_roll) > 0)
													@foreach($resolutions_roll as $resolution)
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
										<div class="col-md-3">
											<button type='button' class='btn btn-primary open_resolution' resolution_block='resolution_list_only_contract' style='width: 122px;'>Открыть скан</button>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</div>
							<form id='formInShowNewResolutionRoll' method='POST' file='true' enctype='multipart/form-data' action='{{route("resolution_store", $new_application->id)}}' style='display: none;'>
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
											<input type='text' value='id_new_application_roll_resolution' name='real_name_document'/>
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
									<button type="button" class="btn btn-secondary steps" first_step='#editResolutionRollContract #formInShowNewResolutionRoll' second_step='#editResolutionRollContract #showInRowEditResolutionRoll'>Закрыть</button>
								</div>
							</form>
							<div id='updateInShowNewResolutionRoll' style='display: none;'>
								<div class="modal-header">
									<h5 class="modal-title" id="updateApplicationModalLabel">Управление резолюциями</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='modal-body'>
									<div class='row'>
										<div class='col-md-12'>
											<div id='divMessageContract'>
											</div>
											<table class="table" style='margin: 0 auto;'>
												<thead>
													<tr>
														<th>Название резолюции</th>
														<th>Удаление</th>
													</tr>
												</thead>
												<tbody>
													@foreach($resolutions_roll as $resolution)
														@if($resolution->deleted_at == null)
															<tr class='rowsContract'>
																<td>{{$resolution->real_name_resolution}}</td>
																<td><button class='btn btn-danger ajax-send-on-delete-href' ajax-href="{{route('resolution_contract_delete_ajax', $resolution->id)}}" ajax-method='GET' div-message='#divMessageContract'>Удалить</button></td>
															</tr>
														@endif
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary steps" first_step='#editResolutionRollContract #updateInShowNewResolutionRoll' second_step='#editResolutionRollContract #showInRowEditResolutionRoll'>Закрыть</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Окно просмотра заключений -->
				<div class="modal fade" id="modalContractions" tabindex="-1" role="dialog" aria-labelledby="showModalContractions" aria-hidden="true">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<div id='showAllContractions'>
								<div class="modal-header">
									<h5 class="modal-title" id="showModalContractions">Заключения</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='modal-body'>
									<div class='row'>
										<div class='col-md-12'>
											<table class='table table-bordered'>
												<thead>
													<tr>
														<th>Подразделение</th>
														<th>Исполнитель</th>
														<th>Заключение</th>
														<th>Дата</th>
														<th>ФИО</th>
														<th>Удалить</th>
													</tr>
												</thead>
												<tbody>
													@foreach($contractions as $contraction)
														<tr>
															<td>{{$contraction->department}}</td>
															<td>{{$contraction->executor}}</td>
															<td>{{$contraction->result}}</td>
															<td>{{$contraction->date}}</td>
															<td>{{$contraction->FIO}}</td>
															<td><button class='btn btn-danger btn-href' type='button' href="{{route('new_application_contractions.destroy', $contraction->id)}}">Удалить</button></td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
										<div class='col-md-12'>
											<button class='btn btn-primary steps' type='button' first_step='#modalContractions #showAllContractions' second_step='#modalContractions #formNewApplicationContraction'>Добавить заключение</button>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</div>
							<form id='formNewApplicationContraction' method='POST' file='true' enctype='multipart/form-data' action='{{route("new_application_contractions.store", $new_application->id)}}' style='display: none;'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="updateApplicationContractionModalLabel">Добавление заключения</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='modal-body'>
									<div class='row'>
										<div class='col-md-12'>
											<label>Наименование подраздения</label>
											<input type='text' name='department' class='form-control' required />
										</div>
									</div>
									<div class='row'>
										<div class='col-md-12'>
											<label>Исполнитель подразделения</label>
											<input type='text' name='executor' class='form-control' required />
										</div>
									</div>
									<div class='row'>
										<div class='col-md-12'>
											<label>Заключение</label>
											<input type='text' name='result' class='form-control' required />
										</div>
									</div>
									<div class='row'>
										<div class='col-md-12'>
											<label>Дата</label>
											<input type='text' name='date' class='form-control datepicker' required />
										</div>
									</div>
									<div class='row'>
										<div class='col-md-12'>
											<label>ФИО</label>
											<input type='text' name='FIO' class='form-control' required />
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' type='button'>Добавить заключение</button>
									<button type="button" class="btn btn-secondary steps" first_step='#modalContractions #formNewApplicationContraction' second_step='#modalContractions #showAllContractions'>Закрыть</button>
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