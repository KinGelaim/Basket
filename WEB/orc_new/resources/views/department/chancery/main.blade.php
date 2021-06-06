@extends('layouts.header')

@section('title')
	Канцелярия
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Канцелярия' OR Auth::User()->hasRole()->role == 'Администрация')
				<div class="content">
					<div id='divError' class="row">
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for='sel1'>Контрагент</label>
								<select class="form-control" id="selectCounerChancery">
									<option value="">Все контрагенты</option>
									@if($counterparties)
										@if(old('name_counterpartie_application'))
											@foreach($counterparties as $counter)
												@if(old('name_counterpartie_application') == $counter->name)
													<option curator='{{old("curator_counterpartie_application")}}' telephone='{{old("telephone_counterpartie_application")}}' value='{{old("id_counterpartie_application")}}' realName='{{old("name_counterpartie_application")}}' selected>{{old('name_counterpartie_application')}}</option>
												@else
													<option curator='{{$counter->FIO}}' telephone='{{$counter->telephone}}' value='{{$counter->id}}' realName='{{ $counter->name }}'>{{ $counter->name }}</option>
												@endif
											@endforeach
										@elseif($counterpartie)
											@foreach($counterparties as $counter)
												@if($counterpartie == $counter->name)
													<option curator='{{$counter->FIO}}' telephone='{{$counter->telephone}}' value='{{$counter->id}}' realName='{{ $counter->name }}' selected>{{$counterpartie}}</option>
												@else
													<option curator='{{$counter->FIO}}' telephone='{{$counter->telephone}}' value='{{$counter->id}}' realName='{{ $counter->name }}'>{{ $counter->name }}</option>
												@endif
											@endforeach
										@elseif(Session::has('id_counterpartie_application'))
											@foreach($counterparties as $counter)
												@if(Session('id_counterpartie_application') == $counter->id)
													<option curator='{{$counter->FIO}}' telephone='{{$counter->telephone}}' value='{{$counter->id}}' realName='{{ $counter->name }}' selected>{{ $counter->name }}</option>
												@else
													<option curator='{{$counter->FIO}}' telephone='{{$counter->telephone}}' value='{{$counter->id}}' realName='{{ $counter->name }}'>{{ $counter->name }}</option>
												@endif
											@endforeach
										@else
											@foreach($counterparties as $counter)
												<option curator='{{$counter->FIO}}' telephone='{{$counter->telephone}}' value='{{$counter->id}}' realName='{{ $counter->name }}'>{{ $counter->name }}</option>
											@endforeach
										@endif
									@endif
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group row">
								<div class="col-md-1">
									<label for='curator'>Куратор ПЭО</label>
								</div>
								<div class="col-md-3">
									<input id='curator' class='form-control' type='text' disabled value='{{old("curator_counterpartie_application")}}'/>
								</div>
								<div class="col-md-1">
									<label for='telephone'>Телефон</label>
								</div>
								<div class="col-md-3">
									<input id='telephone' class='form-control' type='text' disabled value='{{old("telephone_counterpartie_application")}}'/>
								</div>
								<div class="col-md-4">
									@if(Auth::User()->hasRole()->role != 'Администрация')
										<button id='btnNewApplication' class='btn btn-primary' data-toggle="modal" data-target="#newApplication">Новый документ</button>
									@endif
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group row">
								<div class="col-md-1">
									<label for=''>Период с</label>
								</div>
								<div class="col-md-3">
									@if(old('date_begin'))
										<input id='beginDate' class='datepicker form-control' value="{{old('date_begin')}}"/>
									@elseif($date_begin)
										<input id='beginDate' class='datepicker form-control' value="{{$date_begin}}"/>
									@else
										<input id='beginDate' class='datepicker form-control' value="01.01.{{date('Y', time())}}"/>
									@endif
								</div>
								<div class="col-md-1">
									<label for=''>по</label>
								</div>
								<div class="col-md-3">
									@if(old('date_end'))
										<input id='endDate' class='datepicker form-control' value="{{old('date_end')}}"/>
									@elseif($date_end)
										<input id='endDate' class='datepicker form-control' value="{{$date_end}}"/>
									@else
										<input id='endDate' class='datepicker form-control' value="{{date('d.m.Y', time())}}"/>
									@endif
								</div>
							</div>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-12">
							<button id='refreshApplication' class='btn btn-primary' href="{{ route('department.chancery')}}">Обновить список</button>
						</div>
					</div>
					<div class="row">
						<div id="allApplications" class="col-md-12">
							@if($applications)
								<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
									<thead>
										<tr>
											<!--<th>№ записи</th>
											<th>Дата</th>-->
											<th>Исх №</th>
											<th>Дата</th>
											<th>Вх №</th>
											<th>Дата</th>
											<th>Направлено</th>
											<th>Дата</th>
											<th>Тема</th>
											<th>Исполнители</th>
											@if(Auth::User()->hasRole()->role != 'Администрация')
												<th>Удаление</th>
											@endif
										</tr>
									</thead>
									<tbody>
										@foreach($applications as $application)
											<tr class="rowsContract cursorPointer rowsApplications" href="{{route('department.chancery.update',$application->id)}}"
															number_application='{{$application->number_application}}'
															number_outgoing='{{$application->number_outgoing}}'
															number_incoming='{{$application->number_incoming}}'
															date_incoming='{{ $application->date_incoming ? date("d.m.Y", strtotime($application->date_incoming)) : '' }}'
															date_outgoing='{{ $application->date_outgoing ? date("d.m.Y", strtotime($application->date_outgoing)) : '' }}'
															real_directed_application='{{$application->directed_application}}'
															directed_application="<?php 
																						$prArr = explode(' ', $application->directed_application);
																						if(count($prArr) > 0){
																							echo $prArr[0];
																							if(count($prArr) > 1)
																								echo ' ' . mb_substr($prArr[1], 0, 1);
																								if(count($prArr) > 2)
																								echo '.' . mb_substr($prArr[2], 0, 1) . '.';
																						}
																					?>"
															date_directed='{{$application->date_directed}}'
															resolution_application='{{$application->resolution_application}}'
															date_resolution='{{$application->date_resolution}}'
															theme_application='{{$application->theme_application}}'
															isp_dir='{{$application->isp_dir}}'
															zam_isp_dir_niokr='{{$application->zam_isp_dir_niokr}}'
															main_in='{{$application->main_in}}'
															dir_sip='{{$application->dir_sip}}'
															dir_peo='{{$application->dir_peo}}'
															isp_dir_check='{{$application->isp_dir_check}}'
															zam_isp_dir_niokr_check='{{$application->zam_isp_dir_niokr_check}}'
															main_in_check='{{$application->main_in_check}}'
															dir_sip_check='{{$application->dir_sip_check}}'
															dir_peo_check='{{$application->dir_peo_check}}'
															action_resolution='{{route("resolution_store", $application->id)}}'
															resolutions_list='<?php array_key_exists($application->id,$resolutions) ? print($resolutions[$application->id]) : [] ?>'
															directed_list='<?php array_key_exists($application->id,$directed_list) ? print($directed_list[$application->id]) : [] ?>'>
												<!--<td>
													{{ $application->number_application }}
												</td>
												<td>
													{{ $application->date_application ? date('d.m.Y', strtotime($application->date_application)) : '' }}
												</td>-->
												<td>
													{{ $application->number_outgoing }}
												</td>
												<td>
													{{ $application->date_outgoing ? date('d.m.Y', strtotime($application->date_outgoing)) : '' }}
												</td>
												<td>
													{{ $application->number_incoming }}
												</td>
												<td>
													{{ $application->date_incoming ? date('d.m.Y', strtotime($application->date_incoming)) : '' }}
												</td>
												<td>
													<?php 
														$prArr = explode(' ', $application->directed_application);
														if(count($prArr) > 0){
															echo $prArr[0];
															if(count($prArr) > 1)
																echo ' ' . mb_substr($prArr[1], 0, 1);
																if(count($prArr) > 2)
																	echo '.' . mb_substr($prArr[2], 0, 1) . '.';
														}
													?>
												</td>
												<td>
													{{ $application->date_directed }}
												</td>
												<td style='max-width: 410px;'>
													<!--@if($application->id_contract_application OR $application->count_documents > 0 OR $application->id_document_application)
														<input class='form-check-input' type="checkbox" disabled checked />
													@else
														<input class='form-check-input' type="checkbox" disabled />
													@endif-->
													{{ $application->theme_application }}
												</td>
												<td>
													<?php
														$prArrDirected = [];
														if(array_key_exists($application->id,$directed_list))
															foreach($directed_list[$application->id] as $directed)
															{
																$prName = $directed->surname . ' ' . mb_substr($directed->name, 0, 1) . '.' . mb_substr($directed->patronymic, 0, 1);
																if(!in_array($prName, $prArrDirected)){
																	array_push($prArrDirected, $prName);
																	print($prName . '.<br/>');
																}
															}
														/*if($application->isp_dir == 1){
															if($application->isp_dir_check == 0)
																echo '<span style="color: red;">Исполнительный директор</span>';
															else
																echo 'Исполнительный директор';
															echo '<br/>';
														}
														if($application->zam_isp_dir_niokr == 1){
															if($application->zam_isp_dir_niokr_check == 0)
																echo '<span style="color: red;">Зам.исп.директора НИОКР</span>';
															else
																echo 'Зам.исп.директора НИОКР';
															echo '<br/>';
														}
														if($application->main_in == 1){
															if($application->main_in_check == 0)
																echo '<span style="color: red;">Главный инженер</span>';
															else
																echo 'Главный инженер';
															echo '<br/>';
														}
														if($application->dir_sip == 1){
															if($application->dir_sip_check == 0)
																echo '<span style="color: red;">Начальник СИП</span>';
															else
																echo 'Начальник СИП';
															echo '<br/>';
														}
														if($application->dir_peo == 1){
															if($application->dir_peo_check == 0)
																echo '<span style="color: red;">Начальник ПЭО</span>';
															else
																echo 'Начальник ПЭО';
															echo '<br/>';
														}*/
													?>
												</td>
												@if(Auth::User()->hasRole()->role != 'Администрация')
													<td class='table_coll_btn'><button type='button' class='btn btn-danger btn-href' type='button' href='{{route("department.chancery.delete",$application->id)}}'>Удалить</button></td>
												@endif
											</tr>
										@endforeach
									</tbody>
								</table>
							@endif
						</div>
						<div class="col-md-12" style="text-align: center;">
							@if($count_paginate)
								<nav aria-label="Page navigation example">
								  <ul class="pagination justify-content-center">
									@if($prev_page)
										<li class="page-item">
										  <a class="page-link" href="?page={{$prev_page}}{{$link}}" tabindex="-1">Предыдущая</a>
										</li>
									@else
										<li class="page-item disabled">
										  <a class="page-link" href="" tabindex="-1">Предыдущая</a>
										</li>
									@endif
									@for($i = 1; $i < $count_paginate+1; $i++)
										@if($i == $page)
											<li class="page-item active"><a class="page-link" href="?page={{$i}}{{$link}}" style='z-index: 1;'>{{$i}}</a></li>
										@else
											<li class="page-item"><a class="page-link" href="?page={{$i}}{{$link}}" style='z-index: 1;'>{{$i}}</a></li>
										@endif
									@endfor
									@if($next_page)
										<li class="page-item">
										  <a class="page-link" href="?page={{$next_page}}{{$link}}">Следующая</a>
										</li>
									@else
										<li class="page-item disabled">
										  <a class="page-link" href="">Следующая</a>
										</li>
									@endif
								  </ul>
								</nav>
							@endif
						</div>
					</div>
				</div>
				<!-- Модальное окно новой записи -->
				<div class="modal fade" id="newApplication" tabindex="-1" role="dialog" aria-labelledby="newApplicationModalLabel" aria-hidden="true" attr-open-modal='{{$errors->has("number_application") || $errors->has("number_outgoing") || $errors->has("date_outgoing") || $errors->has("number_incoming") || $errors->has("date_incoming") || $errors->has("directed_application") || $errors->has("date_directed") ? print("open") : print("")}}'>
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form method='POST' file='true' enctype='multipart/form-data' action='{{route("department.chancery.store")}}'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="newApplicationModalLabel">Новый документ</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='row'>
										<div class="col-md-12">
											<input id='valIdCounterpartie' class='form-control' name='id_counterpartie_application' type='text' value='{{old("id_counterpartie_application")}}' style='display: none;'/>
											<input id='valNameCounterpartie' class='form-control' name='name_counterpartie_application' type='text' value='{{old("name_counterpartie_application")}}' style='display: none;'/>
											<input id='valTelephoneCounterpartie' class='form-control' name='telephone_counterpartie_application' type='text' value='{{old("telephone_counterpartie_application")}}' style='display: none;'/>
											<input id='valCuratorCounterpartie' class='form-control' name='curator_counterpartie_application' type='text' value='{{old("curator_counterpartie_application")}}' style='display: none;'/>
											<input id='date_begin' class='form-control' name='date_begin' type='text' value='{{old("date_begin")}}' style='display: none;'/>
											<input id='date_end' class='form-control' name='date_end' type='text' value='{{old("date_end")}}' style='display: none;'/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>№ записи:</label>
										</div>
										<div class="col-md-4">
											<input name='number_application' class='form-control {{$errors->has("number_application") ? print("inputError ") : print("")}}' type='text' value='{{old("number_application") ? old("number_application") : ($last_number_application+1)}}' readonly required />
											@if($errors->has('number_application'))
												<label class='msgError'>{{$errors->first('number_application')}}</label>
											@endif
										</div>
										<div class="col-md-3" style='padding-top: 7px;'>
											<input id='outgoing_document' class='form-check-input' type="checkbox" disabled />
											<label for='outgoing_document'>Исходящее</label>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>№ исх.:</label>
										</div>
										<div class="col-md-4">
											<input name='number_outgoing' class='form-control {{$errors->has("number_outgoing") ? print("inputError ") : print("")}}' type='text' value='{{old("number_outgoing")}}'/>
											@if($errors->has('number_outgoing'))
												<label class='msgError'>{{$errors->first('number_outgoing')}}</label>
											@endif
										</div>
										<div class="col-md-4">
											<input name='date_outgoing' class='datepicker form-control {{$errors->has("date_outgoing") ? print("inputError ") : print("")}}' type='text' value="{{old('date_outgoing') ? old('date_outgoing') : date('d.m.Y', time())}}"/>
											@if($errors->has('date_outgoing'))
												<label class='msgError'>{{$errors->first('date_outgoing')}}</label>
											@endif
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>№ вх.:</label>
										</div>
										<div class="col-md-4">
											<input name='number_incoming' class='form-control {{$errors->has("number_incoming") ? print("inputError ") : print("")}}' type='text' value='{{old("number_incoming")}}'/>
											@if($errors->has('number_incoming'))
												<label class='msgError'>{{$errors->first('number_incoming')}}</label>
											@endif
										</div>
										<div class="col-md-4">
											<input name='date_incoming' class='datepicker form-control {{$errors->has("date_incoming") ? print("inputError ") : print("")}}' type='text' value="{{old('date_incoming') ? old('date_incoming') : date('d.m.Y', time())}}"/>
											@if($errors->has('date_incoming'))
												<label class='msgError'>{{$errors->first('date_incoming')}}</label>
											@endif
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Кому:</label>
										</div>
										<div class="col-md-4">
											<select name='directed_application' class='form-control {{$errors->has("directed_application") ? print("inputError ") : print("")}}' id='selectDirected' required>
												<option></option>
												@foreach($all_users as $user)
													@if(old('directed_application'))
														@if(old('directed_application') == $user->id)
															<option value='{{$user->id}}' real_name='{{ $user->surname . " " . $user->name . " " . $user->patronymic}}' selected>{{ $user->surname . ' ' . mb_substr($user->name,0,1) . '.' . mb_substr($user->patronymic,0,1) . '.' }}</option>
														@else
															<option value='{{$user->id}}' real_name='{{ $user->surname . " " . $user->name . " " . $user->patronymic}}'>{{ $user->surname . ' ' . mb_substr($user->name,0,1) . '.' . mb_substr($user->patronymic,0,1) . '.' }}</option>
														@endif
													@else
														<option value='{{$user->id}}' real_name='{{ $user->surname . " " . $user->name . " " . $user->patronymic}}'>{{ $user->surname . ' ' . mb_substr($user->name,0,1) . '.' . mb_substr($user->patronymic,0,1) . '.' }}</option>
													@endif
												@endforeach
											</select>
										</div>
										<div class="col-md-4">
											<input name='date_directed' class='datepicker form-control {{$errors->has("date_directed") ? print("inputError ") : print("")}}' type='text' value="{{old('date_directed') ? old('date_directed') : date('d.m.Y', time())}}"/>
											@if($errors->has('date_directed'))
												<label class='msgError'>{{$errors->first('date_directed')}}</label>
											@endif
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Резолюция:</label>
										</div>
										<div class='col-md-6' style='display: none;'>
											<input type='text' value='id_application_resolution' name='real_name_document'/>
										</div>
										<div class='col-md-7'>
											<div class='row'>
												<div class='col-md-12'>
													<label class='btn btn-secondary' for='files'>Добавить скан</label>
													<input id='files' type='file' name='new_file_resolution' style='display: none;'/>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											
										</div>
										<div class="col-md-4" style='margin-top: 5px;'>
											<input id='date_resolution' name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
											@if($errors->has('date_resolution'))
												<label class='msgError'>{{$errors->first('date_resolution')}}</label>
											@endif
										</div>
									</div>
									<div class='row'>
										<div class="col-md-3">
											<label>Тема:</label>
										</div>
										<div class="col-md-8">
											<textarea name='theme_application' class='form-control' type="text" style="width: 100%;" rows='4'>{{old('theme_application')}}</textarea>
											@if($errors->has('theme_application'))
												<label class='msgError'>{{$errors->first('theme_application')}}</label>
											@endif
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div id='all_users' style='display: none;' all_users='{{$all_users}}'></div>
				<!-- Модальное окно редактирования записи -->
				<div class="modal fade" id="updateApplication" tabindex="-1" role="dialog" aria-labelledby="updateApplicationModalLabel" aria-hidden="true" attr-open-modal='{{$errors->has("number_application_update") || $errors->has("number_outgoing_update") || $errors->has("date_outgoing_update") || $errors->has("number_incoming_update") || $errors->has("date_incoming_update") || $errors->has("directed_application_update") || $errors->has("date_directed_update") || Session::has("new_scan") ? print("open") : print("")}}'>
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form id='form_all_application' method='POST' action='{{old("action")}}'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="updateApplicationModalLabel">Редактирование документа</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div id='all_aplication' class="modal-body">
									<div class='row'>
										<div class="col-md-12">
											<input id='valIdCounterpartie_update' class='form-control' name='id_counterpartie_application' type='text' value='old{{("id_counterpartie_application")}}' style='display: none;'/>
											<input id='valNameCounterpartie_update' class='form-control' name='name_counterpartie_application' type='text' value='{{old("name_counterpartie_application")}}' style='display: none;'/>
											<input id='valTelephoneCounterpartie_update' class='form-control' name='telephone_counterpartie_application' type='text' value='{{old("telephone_counterpartie_application")}}' style='display: none;'/>
											<input id='valCuratorCounterpartie_update' class='form-control' name='curator_counterpartie_application' type='text' value='{{old("curator_counterpartie_application")}}' style='display: none;'/>
											<input id='date_begin_update' class='form-control' name='date_begin' type='text' value='{{old("date_begin")}}' style='display: none;'/>
											<input id='date_end_update' class='form-control' name='date_end' type='text' value='{{old("date_end")}}' style='display: none;'/>
											<input id='action' class='form-control' name='action' type='text' value='{{old("action")}}' style='display: none;'/>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>№ записи:</label>
										</div>
										<div class="col-md-4">
											<input id='number_application' name='number_application_update' class='form-control {{$errors->has("number_application_update") ? print("inputError ") : print("")}}' type='text' value='{{old("number_application_update")}}' readonly required />
											@if($errors->has('number_application_update'))
												<label class='msgError'>{{$errors->first('number_application_update')}}</label>
											@endif
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>№ исх.:</label>
										</div>
										<div class="col-md-4">
											<input id='number_outgoing' name='number_outgoing_update' class='form-control {{$errors->has("number_outgoing_update") ? print("inputError ") : print("")}}' type='text' value='{{old("number_outgoing_update")}}'/>
											@if($errors->has('number_outgoing_update'))
												<label class='msgError'>{{$errors->first('number_outgoing_update')}}</label>
											@endif
										</div>
										<div class="col-md-4">
											<input id='date_outgoing' name='date_outgoing_update' class='datepicker form-control {{$errors->has("date_outgoing_update") ? print("inputError ") : print("")}}' type='text' value="{{old('date_outgoing_update') ? old('date_outgoing_update') : date('d.m.Y', time())}}"/>
											@if($errors->has('date_outgoing_update'))
												<label class='msgError'>{{$errors->first('date_outgoing_update')}}</label>
											@endif
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>№ вх.:</label>
										</div>
										<div class="col-md-4">
											<input id='number_incoming' name='number_incoming_update' class='form-control {{$errors->has("number_incoming_update") ? print("inputError ") : print("")}}' type='text' value='{{old("number_incoming_update")}}'/>
											@if($errors->has('number_incoming_update'))
												<label class='msgError'>{{$errors->first('number_incoming_update')}}</label>
											@endif
										</div>
										<div class="col-md-4">
											<input id='date_incoming' name='date_incoming_update' class='datepicker form-control {{$errors->has("date_incoming_update") ? print("inputError ") : print("")}}' type='text' value="{{old('date_incoming_update') ? old('date_incoming_update') : date('d.m.Y', time())}}"/>
											@if($errors->has('date_incoming_update'))
												<label class='msgError'>{{$errors->first('date_incoming_update')}}</label>
											@endif
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<label>Кому:</label>
										</div>
										<div class="col-md-4">
											<select id='directed_application' name='directed_application_update' class='form-control {{$errors->has("directed_application_update") ? print("inputError ") : print("")}}' id='selectDirected_update' required>
												<option></option>
												@foreach($all_users as $user)
													@if(old('directed_application_update'))
														@if(old('directed_application_update') == $user->id)
															<option value='{{$user->id}}' real_name='{{ $user->surname . " " . $user->name . " " . $user->patronymic}}' selected>{{ $user->surname . ' ' . mb_substr($user->name,0,1) . '.' . mb_substr($user->patronymic,0,1) . '.' }}</option>
														@else
															<option value='{{$user->id}}' real_name='{{ $user->surname . " " . $user->name . " " . $user->patronymic}}'>{{ $user->surname . ' ' . mb_substr($user->name,0,1) . '.' . mb_substr($user->patronymic,0,1) . '.' }}</option>
														@endif
													@else												
														<option value='{{$user->id}}' real_name='{{ $user->surname . " " . $user->name . " " . $user->patronymic}}'>{{ $user->surname . ' ' . mb_substr($user->name,0,1) . '.' . mb_substr($user->patronymic,0,1) . '.' }}</option>
													@endif
												@endforeach
											</select>
										</div>
										<div class="col-md-4">
											<input id='date_directed' name='date_directed_update' class='datepicker form-control {{$errors->has("date_directed_update") ? print("inputError ") : print("")}}' type='text' value="{{old('date_directed_update') ? old('date_directed_update') : date('d.m.Y', time())}}"/>
											@if($errors->has('date_directed_update'))
												<label class='msgError'>{{$errors->first('date_directed_update')}}</label>
											@endif
										</div>
									</div>
									<div class='row'>
										<div class="col-md-3">
											<label>Тема:</label>
										</div>
										<div class="col-md-8">
											<textarea id='theme_application' name='theme_application_update' class='form-control' type="text" style="width: 100%;" rows='4'>{{old('theme_application_update')}}</textarea>
											@if($errors->has('theme_application'))
												<label class='msgError'>{{$errors->first('theme_application')}}</label>
											@endif
										</div>
									</div>
									<div class='form-group row'>
										<div class='row'>
											<div class="col-md-12">
												<div class="col-md-3">
													<label>Исполнители:</label>
												</div>
												<div id='directed_list' class="col-md-6" style='max-height: 135px; overflow-y: auto;'>
													@if(Session::has("all_directed_list"))
														@foreach(Session("all_directed_list") as $directed)
															<label>{{$directed->surname . ' ' . $directed->name . ' ' . $directed->patronymic}}</label><br/>
														@endforeach
													@endif
													@if(Session::has('list_new_direction'))
														<?php $count_new_direction = 0; ?>
														@foreach(Session('list_new_direction') as $key=>$value)
															<input class="form-control" type="text" value="{{$value}}" style="margin-top: 2px;" readonly />
															<input class="form-control new_directed_input_list" type="text" name="name_new_direction[{{$count_new_direction}}]" value="{{$key}}" style="display: none;" readonly />
															<?php $count_new_direction++; ?>
														@endforeach
													@endif
												</div>
												<!--<div class="col-md-6">
													<label>Согласовано:</label>
												</div>-->
											</div>
											<div class="col-md-12">
												<div class="col-md-3">
													<label>Выберите согласующего:</label>
												</div>
												<div class="col-md-7" style='margin-top: 5px;'>
													<select id='directed_select' class='form-control'>
														<option></option>
														@foreach($all_users as $user)
															<option value='{{$user->id}}' real_name='{{ $user->surname . " " . $user->name . " " . $user->patronymic}}'>{{ $user->surname . ' ' . $user->name . ' ' . $user->patronymic }}</option>
														@endforeach
													</select>
												</div>
											</div>
											<!--<div class="col-md-12">
												<div class="col-md-3">
												</div>
												<div class="col-md-7" style='margin-top: 5px;'>
													<button id='add_direction' type='button' class='btn btn-secondary'>Добавить исполнителя</button>
												</div>
											</div>-->
										</div>
										<div class='row'>
											<!--<div class="col-md-12">
												<div class="col-md-6">
													<div class='col-md-12'>
														@if(old('isp_dir'))
															<input id='isp_dir' class='form-check-input' name='isp_dir' type="checkbox" checked />
														@else
															<input id='isp_dir' class='form-check-input' name='isp_dir' type="checkbox"/>
														@endif
														<label class='form-check-label' for='isp_dir'>Исполнительный директор</label>
													</div>
													<div class='col-md-12'>
														@if(old('zam_isp_dir_niokr'))
															<input id='zam_isp_dir_niokr' class='form-check-input' name='zam_isp_dir_niokr' type="checkbox" checked />
														@else
															<input id='zam_isp_dir_niokr' class='form-check-input' name='zam_isp_dir_niokr' type="checkbox"/>
														@endif
														<label class='form-check-label' for='zam_isp_dir_niokr'>Зам.исп.директора по НИОКР</label>
													</div>
													<div class='col-md-12'>
														@if(old('main_in'))
															<input id='main_in' class='form-check-input' name='main_in' type="checkbox" checked />
														@else
															<input id='main_in' class='form-check-input' name='main_in' type="checkbox"/>
														@endif
														<label class='form-check-label' for='main_in'>Главный инженер</label>
													</div>
													<div class='col-md-12'>
														@if(old('dir_sip'))
															<input id='dir_sip' class='form-check-input' name='dir_sip' type="checkbox" checked />
														@else
															<input id='dir_sip' class='form-check-input' name='dir_sip' type="checkbox"/>
														@endif
														<label class='form-check-label' for='dir_sip'>Начальник СИП</label>
													</div>
													<div class='col-md-12'>
														@if(old('dir_peo'))
															<input id='dir_peo' class='form-check-input' name='dir_peo' type="checkbox" checked />
														@else
															<input id='dir_peo' class='form-check-input' name='dir_peo' type="checkbox"/>
														@endif
														<label class='form-check-label' for='dir_peo'>Начальник ПЭО</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class='col-md-12'>
														@if(old('isp_dir_check'))
															<input id='isp_dir_check' class='form-check-input' name='isp_dir_check' type="checkbox" checked />
														@else
															<input id='isp_dir_check' class='form-check-input' name='isp_dir_check' type="checkbox"/>
														@endif
														<label class='form-check-label' for='isp_dir_check'>Исполнительный директор</label>
													</div>
													<div class='col-md-12'>
														@if(old('zam_isp_dir_niokr_check'))
															<input id='zam_isp_dir_niokr_check' class='form-check-input' name='zam_isp_dir_niokr_check' type="checkbox" checked />
														@else
															<input id='zam_isp_dir_niokr_check' class='form-check-input' name='zam_isp_dir_niokr_check' type="checkbox"/>
														@endif
														<label class='form-check-label' for='zam_isp_dir_niokr_check'>Зам.исп.директора по НИОКР</label>
													</div>
													<div class='col-md-12'>
														@if(old('main_in_check'))
															<input id='main_in_check' class='form-check-input' name='main_in_check' type="checkbox" checked />
														@else
															<input id='main_in_check' class='form-check-input' name='main_in_check' type="checkbox"/>
														@endif
														<label class='form-check-label' for='main_in_check'>Главный инженер</label>
													</div>
													<div class='col-md-12'>
														@if(old('dir_sip_check'))
															<input id='dir_sip_check' class='form-check-input' name='dir_sip_check' type="checkbox" checked />
														@else
															<input id='dir_sip_check' class='form-check-input' name='dir_sip_check' type="checkbox"/>
														@endif
														<label class='form-check-label' for='dir_sip_check'>Начальник СИП</label>
													</div>
													<div class='col-md-12'>
														@if(old('dir_peo_check'))
															<input id='dir_peo_check' class='form-check-input' name='dir_peo_check' type="checkbox" checked />
														@else
															<input id='dir_peo_check' class='form-check-input' name='dir_peo_check' type="checkbox"/>
														@endif
														<label class='form-check-label' for='dir_peo_check'>Начальник ПЭО</label>
													</div>
												</div>
											</div>-->
										</div>
									</div>
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
										<div class="col-md-3">
										</div>
										<div class="col-md-7">
											<select id='resolution_list' name='resolution_list' class='form-control {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
												@if(Session::has("new_scan"))
													@foreach(Session("all_scan") as $res)
														@if(Session("new_scan")->id == $res->id)
															<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}' selected>{{$res->real_name_resolution}}</option>
														@else
															<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}'>{{$res->real_name_resolution}}</option>
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
										</div>
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
									@if(Auth::User()->hasRole()->role != 'Администрация')
										<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
									@endif
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
							<form id='form_new_application' method='POST' file='true' enctype='multipart/form-data' action='' style='display: none;'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="updateApplicationModalLabel">Добавление резолюции</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='modal-body'>
									<!-- Блок для отображения информации после сохранения скана -->
									<div style='display: none;'>
										<input id='valIdCounterpartie_update_scan' class='form-control' name='id_counterpartie_application' type='text' value='old{{("id_counterpartie_application")}}'/>
										<input id='valNameCounterpartie_update_scan' class='form-control' name='name_counterpartie_application' type='text' value='{{old("name_counterpartie_application")}}'/>
										<input id='valTelephoneCounterpartie_update_scan' class='form-control' name='telephone_counterpartie_application' type='text' value='{{old("telephone_counterpartie_application")}}'/>
										<input id='valCuratorCounterpartie_update_scan' class='form-control' name='curator_counterpartie_application' type='text' value='{{old("curator_counterpartie_application")}}'/>
										<input id='date_begin_update_scan' class='form-control' name='date_begin' type='text' value='{{old("date_begin")}}'/>
										<input id='date_end_update_scan' class='form-control' name='date_end' type='text' value='{{old("date_end")}}'/>
										<input id='action_scan' class='form-control' name='action' type='text' value='{{old("action")}}'/>
									
										<input id='number_application_scan' name='number_application_update' class='form-control' type='text' value='{{old("number_application_update")}}' readonly />
										<input id='number_outgoing_scan' name='number_outgoing_update' class='form-control' type='text' value='{{old("number_outgoing_update")}}'/>
										<input id='date_outgoing_scan' name='date_outgoing_update' class='form-control' type='text' value="{{old('date_outgoing_update')}}"/>
										<input id='number_incoming_scan' name='number_incoming_update' class='form-control' type='text' value='{{old("number_incoming_update")}}'/>
										<input id='date_incoming_scan' name='date_incoming_update' class='form-control' type='text' value="{{old('date_incoming_update')}}"/>
										<select id='directed_application_scan' name='directed_application_update' class='form-control' id='selectDirected_update_scan'>
											@if(old('directed_application_update'))
												<option>{{old('directed_application_update')}}</option>
											@endif
											<option selected></option>
											@foreach($all_users as $user)
												<option value='{{$user->id}}'>{{ $user->surname . ' ' . mb_substr($user->name,0,1) . '.' . mb_substr($user->patronymic,0,1) . '.' }}</option>
											@endforeach
										</select>
										<input id='date_directed_scan' name='date_directed_update' class='form-control' type='text' value="{{old('date_directed_update')}}"/>
										<textarea id='theme_application_scan' name='theme_application_update' class='form-control' type="text">{{old('theme_application_update')}}</textarea>
										<input id='isp_dir_scan' class='form-check-input' name='isp_dir' type="checkbox"/>
										<input id='zam_isp_dir_niokr_scan' class='form-check-input' name='zam_isp_dir_niokr' type="checkbox"/>
										<input id='main_in_scan' class='form-check-input' name='main_in' type="checkbox"/>
										<input id='dir_sip_scan' class='form-check-input' name='dir_sip' type="checkbox"/>
										<input id='dir_peo_scan' class='form-check-input' name='dir_peo' type="checkbox"/>
										<input id='isp_dir_check_scan' class='form-check-input' name='isp_dir_check' type="checkbox"/>
										<input id='zam_isp_dir_niokr_check_scan' class='form-check-input' name='zam_isp_dir_niokr_check' type="checkbox"/>
										<input id='main_in_check_scan' class='form-check-input' name='main_in_check' type="checkbox"/>
										<input id='dir_sip_check_scan' class='form-check-input' name='dir_sip_check' type="checkbox"/>
										<input id='dir_peo_check_scan' class='form-check-input' name='dir_peo_check' type="checkbox"/>
										<div id='directed_block_scan'>
										</div>
									</div>
									<div class='row'>
										<div class='col-md-6' style='display: none;'>
											<input type='text' value='id_application_resolution' name='real_name_document'/>
										</div>
										<div class='col-md-6'>
											<input type='file' name='new_file_resolution'/>
										</div>
										<div class='col-md-6'>
											<input name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
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
				<script>
					if($('#updateApplication').attr('attr-open-modal') == 'open1')
						$('#updateApplication').modal('show');
					else
						if($('#newApplication').attr('attr-open-modal') == 'open1')
							$('#newApplication').modal('show');
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
