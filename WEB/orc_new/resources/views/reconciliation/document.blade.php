@extends('layouts.header')

@section('title')
	Карточка заявки
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Десятый отдел' OR Auth::User()->hasRole()->role == 'Администрация')
				<div class="content">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label>Номер заявки</label>
								<input class='form-control' type='text' value='{{$number_document}}' readonly />
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Дата создания заявки</label>
								<input class='form-control' type='text' value='{{$date_document}}' readonly />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Контрагент</label>
								<input class='form-control' type='text' value='{{$counterpartie}}' readonly />
							</div>
						</div>
					</div>
					@if(count($my_parents) > 0)
						<div class="row">
							<div class="col-md-12">
								Список "родительских" заявок
							</div>
						</div>
						<div class="row">
							<div class="col-md-7">
								<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
									<thead>
										<tr>
											<th style='min-width: 65px;'>№ исх.</th>
											<th style='min-width: 90px;'>Дата</th>
											<th style='min-width: 65px;'>№ вх.</th>
											<th style='min-width: 90px;'>Дата</th>
											<th>Тема</th>
										</tr>
									</thead>
									<tbody>
										@foreach($my_parents as $parents)
											@if($parents != null)
												@foreach($parents as $application)
													<tr class='rowsContract cursorPointer btn-href' id_contact='{{$application->idApp}}' href='{{ route("department.reconciliation.document", $application->number_application) }}'>
														<td>{{$application->number_outgoing}}</td>
														<td>{{ $application->date_outgoing != null ? date('d.m.Y', strtotime($application->date_outgoing)) : '' }}</td>
														<td>{{$application->number_incoming}}</td>
														<td>{{ $application->date_incoming != null ? date('d.m.Y', strtotime($application->date_incoming)) : '' }}</td>
														<td>{{$application->theme_application}}</td>
													</tr>
												@endforeach
											@endif
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					@endif
					@if(count($my_childs) > 0)
						<div class="row">
							<div class="col-md-12">
								Список "дочерних" заявок
							</div>
						</div>
						<div class="row">
							<div class="col-md-5">
								<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
									<thead>
										<tr>
											<th style='min-width: 65px;'>№ исх.</th>
											<th style='min-width: 90px;'>Дата</th>
											<th style='min-width: 65px;'>№ вх.</th>
											<th style='min-width: 90px;'>Дата</th>
											<th>Тема</th>
										</tr>
									</thead>
									<tbody>
										@foreach($my_childs as $childs)
											@if($childs != null)
												@foreach($childs as $application)
													<tr class='rowsContract cursorPointer btn-href' id_contact='{{$application->idApp}}' href='{{ route("department.reconciliation.document", $application->number_application) }}'>
														<td>{{$application->number_outgoing}}</td>
														<td>{{ $application->date_outgoing != null ? date('d.m.Y', strtotime($application->date_outgoing)) : '' }}</td>
														<td>{{$application->number_incoming}}</td>
														<td>{{ $application->date_incoming != null ? date('d.m.Y', strtotime($application->date_incoming)) : '' }}</td>
														<td>{{$application->theme_application}}</td>
													</tr>
												@endforeach
											@endif
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					@endif
					<div class="row">
						<div class="col-md-12">
							Список писем прикрепленных к заявке
						</div>
					</div>
					<div class="row">
						<div class="col-md-10">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>№ исх.</th>
										<th>Дата</th>
										<th>№ вх.</th>
										<th>Дата</th>
										<th>Тема</th>
										<th>Просмотреть</th>
										<th>Создание заявки</th>
										<th>Открепить</th>
									</tr>
								</thead>
								<tbody>
									<tr class='rowsContract'>
										<td>{{$documents[0]->number_outgoing}}</td>
										<td>{{ $documents[0]->date_outgoing != null ? date('d.m.Y', strtotime($documents[0]->date_outgoing)) : '' }}</td>
										<td>{{$documents[0]->number_incoming}}</td>
										<td>{{ $documents[0]->date_incoming != null ? date('d.m.Y', strtotime($documents[0]->date_incoming)) : '' }}</td>
										<td>{{$documents[0]->theme_application}}</td>
										<td><button class="btn btn-primary btn-href" type="button" href='{{route("reconciliation.application.show", $documents[0]->appID)}}'>Просмотреть</button></td>
										<td>Родительское письмо</td>
										<td><button class="btn btn-primary" type="button" data-toggle="modal" data-target="#updateApplication">Редактировать</button></td>
									</tr>
									@foreach($my_applications as $application)
										<tr class='rowsContract' id_contact='{{$application->id}}'>
											<td>{{$application->number_outgoing}}</td>
											<td>{{ $application->date_outgoing != null ? date('d.m.Y', strtotime($application->date_outgoing)) : '' }}</td>
											<td>{{$application->number_incoming}}</td>
											<td>{{ $application->date_incoming != null ? date('d.m.Y', strtotime($application->date_incoming)) : '' }}</td>
											<td>{{$application->theme_application}}</td>
											<td><button class="btn btn-primary btn-href" type="button" href='{{route("reconciliation.application.show", $application->id)}}'>Просмотреть</button></td>
											<td>
												@if(Auth::User()->hasRole()->role != 'Администрация')
													@if($application->is_protocol == 0 && $application->id_contract_application == null)
														<button class="btn btn-primary btn-href" type="button" href='{{route("department.reconciliation.create_new_document",$application->id)}}'>Создать</button>
													@else
														<button class="btn btn-primary" type="button" disabled>Создать</button>
													@endif
												@endif
											</td>
											<td>
												@if(Auth::User()->hasRole()->role != 'Администрация')
													@if($application->is_protocol == 0 && $application->id_contract_application == null)
														<button class="btn btn-primary btn-href" type="button" href='{{route("department.reconciliation.reconciliation_document_message_delete", $application->id)}}'>Открепить</button>
													@else
														<button class="btn btn-primary" type="button" disabled>Открепить</button>
													@endif
												@endif
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						<div class="col-md-2">
							<div class='row'>
								<div class="col-md-12">
									<!--<button class="btn btn-primary" data-toggle="modal" data-target="#message" type="button" style="margin-top: 26px;">Входящие письма</button>-->
									<button class="btn btn-primary btn-href" type="button" style="margin-top: 26px; float: right; margin-right: 10px;" href='{{route("department.reconciliation.reconciliation_document_message_show", $documents[0]->id)}}'>Входящие письма</button>
								</div>
							</div>
							<div class='row'>
								<div class="col-md-12">
									<button class="btn btn-primary btn-href" type="button" style="margin-top: 26px; float: right; margin-right: 10px; width: 145px;" href="{{route('ten.document_components', $documents[0]->id)}}">Комплектация</button>
								</div>
							</div>
							<div class='row'>
								<div class="col-md-12">
									<!--<button class="btn btn-primary" data-toggle="modal" data-target="#resolution" type="button" style="margin-top: 26px; float: right; margin-right: 10px;">Добавить скан</button>-->
									<button class="btn btn-primary btn-href" type="button" style="margin-top: 26px; float: right; margin-right: 10px; width: 145px;" href="{{route('tree_map.show', $documents[0]->id)}}">Граф заявки</button>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<button id='addNewContract' class="btn btn-primary" type="button" href="{{ route('department.ekonomic.create', $number_document) }}" style="margin-top: 26px; float: right; margin-right: 10px;">Добавить договор</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							Список договоров
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>№ договора НТИИМ</th>
										<th>№ договора (контрагент)</th>
										<th>Вид работ</th>
										<th>Наименование работ</th>
										<th>Ответственный исполнитель</th>
										<th>Начальная сумма</th>
										<th>Окончательная сумма</th>
										<th>Срок исполнения</th>
									</tr>
								</thead>
								<tbody>
									@foreach($result as $key=>$value)
										@foreach($value as $key2=>$value2)
											<tr class='rowsContract cursorPointer btn-href' id_document='{{$value2->id}}' href="{{ route('department.reconciliation.show', $value2->id)}}">
												<td>
													{{ $value2->number_contract }}
												</td>
												<td>
													{{ $value2->number_counterpartie_contract_reestr }}
												</td>
												<td>
													{{ $value2->name_view_contract }}
												</td>
												<td>
													{{ $value2->name_work_contract }}
												</td>
												<td>
													{{ $value2->curator_contract }}
												</td>
												<td>
													{{ is_numeric($value2->amount_reestr) ? number_format($value2->amount_reestr, 2, ',', '&nbsp;') : $value2->amount_reestr }} <br/>
												</td>
												<td>
													{{ is_numeric($value2->amount_contract_reestr) ? number_format($value2->amount_contract_reestr, 2, ',', '&nbsp;') : $value2->amount_contract_reestr }} <br/>
												</td>
												<td>
													{{ $value2->date_maturity_date_reestr ? $value2->date_maturity_date_reestr : $value2->date_maturity_reestr }} <br/>
												</td>
											</tr>
										@endforeach
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- Модальное окно писем -->
				<div class="modal fade" id="message" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form id='form_message' method='POST' action='{{ route("department.reconciliation.reconciliation_document_message", $documents[0]->id) }}'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="messageModalLabel">Прикрепление писем</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div id='message_div' class='form-group row'>
										<div class="col-md-12" style='margin-top: 5px;'>
											<label>Выберите письма:</label>
										</div>
										<!-- Выпадающий список писем будущих -->
									</div>
									<div class='form-group row'>
										<div class="col-md-12">
											<button id='add_message' type='button' class='btn btn-primary' new_message='@foreach($applications as $application)<option value="{{ $application->id }}" number_application="{{$application->number_application}}" number_outgoing="{{$application->number_outgoing}}" date_outgoing="{{ $application->date_outgoing != null ? date("d.m.Y", strtotime($application->date_outgoing)) : '' }}" number_incoming="{{$application->number_incoming}}" date_incoming="{{ $application->date_incoming != null ? date("d.m.Y", strtotime($application->date_incoming)) : '' }}" theme_application="{{$application->theme_application}}">Номер письма: {{$application->number_application}} / Входящий номер: {{$application->number_incoming}}</option>@endforeach'>Добавить входящее письмо</button>
										</div>
									</div>
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
									</div>
								</div>
								<div class="modal-footer">
									<button id='close_message' type="button" class="btn btn-secondary">Закрыть письмо</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Модальное окно резолюции -->
				<div class="modal fade" id="resolution" tabindex="-1" role="dialog" aria-labelledby="scanModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form id='form_all_application' method='POST' action=''>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="scanModalLabel">Скан родительской заявки</h5>
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
							<form id='form_new_application' method='POST' file='true' enctype='multipart/form-data' action='{{route("resolution_store", $documents[0]->id)}}' style='display: none;'>
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
											<input type='text' value='id_application_resolution' name='real_name_document'/>
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
				<!-- Модальное окно редактирования родительской записи -->
				<div class="modal fade" id="updateApplication" tabindex="-1" role="dialog" aria-labelledby="updateApplicationModalLabel" aria-hidden="true" attr-open-modal='{{$errors->has("number_application_update") || $errors->has("number_outgoing_update") || $errors->has("date_outgoing_update") || $errors->has("number_incoming_update") || $errors->has("date_incoming_update") || $errors->has("directed_application_update") || $errors->has("date_directed_update") || Session::has("new_scan") ? print("open") : print("")}}'>
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form id='form_all_application' method='POST' action="{{route('department.chancery.update',$documents[0]->appID)}}">
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="updateApplicationModalLabel">Редактирование документа</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div id='all_aplication' class="modal-body">
									<!--<div class='row'>
										<div class="col-md-12">
											<input id='valIdCounterpartie_update' class='form-control' name='id_counterpartie_application' type='text' value='old{{("id_counterpartie_application")}}' style='display: none;'/>
											<input id='valNameCounterpartie_update' class='form-control' name='name_counterpartie_application' type='text' value='{{old("name_counterpartie_application")}}' style='display: none;'/>
											<input id='valTelephoneCounterpartie_update' class='form-control' name='telephone_counterpartie_application' type='text' value='{{old("telephone_counterpartie_application")}}' style='display: none;'/>
											<input id='valCuratorCounterpartie_update' class='form-control' name='curator_counterpartie_application' type='text' value='{{old("curator_counterpartie_application")}}' style='display: none;'/>
											<input id='date_begin_update' class='form-control' name='date_begin' type='text' value='{{old("date_begin")}}' style='display: none;'/>
											<input id='date_end_update' class='form-control' name='date_end' type='text' value='{{old("date_end")}}' style='display: none;'/>
											<input id='action' class='form-control' name='action' type='text' value='{{old("action")}}' style='display: none;'/>
										</div>
									</div>-->
									<div class='form-group row'>
										<div class="col-md-3">
											<label>№ записи:</label>
										</div>
										<div class="col-md-4">
											<input name='number_application_update' class='form-control {{$errors->has("number_application_update") ? print("inputError ") : print("")}}' type='text' value='{{old("number_application_update") ? old("number_application_update") : $documents[0]->number_application}}' readonly required />
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
											<input id='number_outgoing' name='number_outgoing_update' class='form-control {{$errors->has("number_outgoing_update") ? print("inputError ") : print("")}}' type='text' value='{{old("number_outgoing_update") ? old("number_outgoing_update") : $documents[0]->number_outgoing}}'/>
											@if($errors->has('number_outgoing_update'))
												<label class='msgError'>{{$errors->first('number_outgoing_update')}}</label>
											@endif
										</div>
										<div class="col-md-1">
											<label>Дата:</label>
										</div>
										<div class="col-md-4">
											<input name='date_outgoing_update' class='datepicker form-control {{$errors->has("date_outgoing_update") ? print("inputError ") : print("")}}' type='text' value="{{old('date_outgoing_update') ? old('date_outgoing_update') : ($documents[0]->date_outgoing != null ? date('d.m.Y', strtotime($documents[0]->date_outgoing)) : '')}}"/>
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
											<input id='number_incoming' name='number_incoming_update' class='form-control {{$errors->has("number_incoming_update") ? print("inputError ") : print("")}}' type='text' value='{{old("number_incoming_update") ? old("number_incoming_update") : $documents[0]->number_incoming}}'/>
											@if($errors->has('number_incoming_update'))
												<label class='msgError'>{{$errors->first('number_incoming_update')}}</label>
											@endif
										</div>
										<div class="col-md-1">
											<label>Дата:</label>
										</div>
										<div class="col-md-4">
											<input name='date_incoming_update' class='datepicker form-control {{$errors->has("date_incoming_update") ? print("inputError ") : print("")}}' type='text' value="{{old('date_incoming_update') ? old('date_incoming_update') : ($documents[0]->date_incoming != null ? date('d.m.Y', strtotime($documents[0]->date_incoming)) : '')}}"/>
											@if($errors->has('date_incoming_update'))
												<label class='msgError'>{{$errors->first('date_incoming_update')}}</label>
											@endif
										</div>
									</div>
									<div class='row'>
										<div class="col-md-3">
											<label>Тема:</label>
										</div>
										<div class="col-md-9">
											<textarea id='theme_application' name='theme_application_update' class='form-control' type="text" style="width: 100%;" rows='4'>{{old('theme_application_update') ? old('theme_application_update') : $documents[0]->theme_application}}</textarea>
											@if($errors->has('theme_application'))
												<label class='msgError'>{{$errors->first('theme_application')}}</label>
											@endif
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
