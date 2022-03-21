@extends('layouts.header')

@section('title')
	Переписка по заявке
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Отдел управления договорами' OR Auth::User()->hasRole()->role == 'Десятый отдел' OR Auth::User()->hasRole()->role == 'Администрация')
				<div class="content">
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
										<input class='form-control' type='text' value='{{old("date_registration_new_application") ? old("date_registration_new_application") : $new_application->date_registration_new_application}}' name='date_registration_new_application' readonly />
									</div>
								</div>
								<div class="col-md-7">
									<label>Контрагент</label>
									<div class="form-group">
										<select id="sel4" class='form-control select_counterpartie_reestr {{$errors->has("id_counterpartie_new_application") ? print("inputError ") : print("")}}' name='id_counterpartie_new_application' disabled >
											<option></option>
											<option value='{{$new_application->id_counterpartie_new_application}}' full_name='{{$new_application->full_name_counterpartie_contract}}' inn='{{$new_application->inn_counterpartie_contract}}' selected>{{$new_application->name_counterpartie_contract}}</option>
										</select>
										@if($errors->has('id_counterpartie_new_application'))
											<label class='msgError'>{{$errors->first('id_counterpartie_new_application')}}</label>
										@endif
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class='row'>
								<div class="col-md-6">
									<div class="form-group">
										<label>Заявка Исх. №</label>
										<input class='form-control' type='text' name='number_outgoing_new_application' value='{{old("number_outgoing_new_application") ? old("number_outgoing_new_application") : $new_application->number_outgoing_new_application}}' readonly />
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>н/Вх.</label>
										<input class='form-control' type='text' name='number_incoming_new_application' value='{{old("number_incoming_new_application") ? old("number_incoming_new_application") : $new_application->number_incoming_new_application}}' readonly />
									</div>
								</div>								
							</div>
						</div>
						<div class='col-md-2'>
							<div class="form-group">
								<button class='btn btn-primary' type='button' data-toggle='modal' data-target='#editResolutionContract' style='width: 100px; margin-top: 32px;'>Сканы</button>
							</div>
						</div>
					</div>
					<div class='row'>
						<div class='col-md-12'>
							<label>Список писем прикрепленных к заявке</label>
						</div>
						<div class="col-md-12">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>Исх. №</th>
										<th>Дата</th>
										<th>н/Вх. №</th>
										<th>Дата</th>
										<th>Тема (Содержание письма)</th>
										<th>Скан</th>
									</tr>
								</thead>
								<tbody>
									@foreach($applications as $application)
										<tr class='rowsContract'>
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
												{{ $application->theme_application }}
											</td>
											<td>
												<button class='btn btn-primary rowsAdditionalDocumentResolution' type='button' style='width: 154px;' additional_document='{{$application}}' href_add_resolution="{{route('resolution_store', $application->id)}}">Сканы</button>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
					<div class='row'>
						<div class='col-md-12'>
							<button class='btn btn-primary' type='button' data-toggle='modal' data-target='#newApplicationCopying'>Добавить письмо</button>
						</div>
					</div>
					<div class='row'>
						<div class='col-md-12'>
							<label>Список Договоров (Контрактов)</label>
						</div>
						<div class="col-md-12">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>№ Договора (Контракта) НТИИМ</th>
										<th>№ Договора (Контракта) Контрагента</th>
										<th>Вид работ</th>
										<th>Предмет</th>
										<th>Ответственный исполнитель</th>
										<th>Начальная сумма</th>
										<th>Окончательная сумма</th>
										<th>Срок исполнения</th>
									</tr>
								</thead>
								<tbody>
									@foreach($contracts as $contract)
										<tr class='rowsContract cursorPointer btn-href' href="{{route('department.ekonomic.contract_new_reestr', $contract->id)}}">
											<td>
												{{ $contract->number_contract }}
											</td>
											<td>
												{{ $contract->number_counterpartie_contract_reestr }}
											</td>
											<td>
												{{ $contract->name_view_contract }}
											</td>
											<td>
												{{ $contract->item_contract }}
											</td>
											<td>
												{{ $contract->executor_contract_reestr }}
											</td>
											<td>
												{{ $contract->amount_begin_reestr }}
											</td>
											<td>
												{{ $contract->amount_reestr }}
											</td>
											<td>
												{{ $contract->date_maturity_reestr }}
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
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
				<!-- Модальное окно нового письма -->
				<div class="modal fade" id="newApplicationCopying" tabindex="-1" role="dialog" aria-labelledby="newApplicationModalLabel" aria-hidden="true" attr-open-modal='{{$errors->has("number_application") || $errors->has("number_outgoing") || $errors->has("date_outgoing") || $errors->has("number_incoming") || $errors->has("date_incoming") || $errors->has("directed_application") || $errors->has("date_directed") ? print("open") : print("")}}'>
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form method='POST' file='true' enctype='multipart/form-data' action='{{route("department.chancery.store_for_new_application", $new_application->id)}}'>
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
											<input id='valIdCounterpartie' class='form-control' name='id_counterpartie_application' type='text' value='{{old("id_counterpartie_application") ? old("id_counterpartie_application") : $new_application->id_counterpartie_new_application}}' style='display: none;'/>
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
				<!-- Окно просмотра сканов для писем -->
				<div class="modal fade" id="edit_resolutions" tabindex="-1" role="dialog" aria-labelledby="showResolutionModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div id='showInRowEditResolution'>
								<div class="modal-header">
									<h5 class="modal-title" id="showResolutionModalLabel">Сканы договорного материала</h5>
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
												<button id='add_new_resolution' type='button' class='btn btn-secondary steps' first_step='#edit_resolutions #showInRowEditResolution' second_step='#edit_resolutions #formInShowNewResolution'>Добавить скан</button>
											@endif
										</div>
										<div class="col-md-4" style='text-align: right;'>
											<button type='button' class='btn btn-secondary steps' first_step='#edit_resolutions #showInRowEditResolution' second_step='#edit_resolutions #updateInShowNewResolution'>Управление сканами</button>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-3">
										</div>
										<div class="col-md-6">
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
										<div class="col-md-3">
											<button type='button' class='btn btn-primary open_resolution' resolution_block='showInRowEditResolution #resolution_list' style='width: 122px;'>Открыть скан</button>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</div>
							<form id='formInShowNewResolution' method='POST' file='true' enctype='multipart/form-data' action='' style='display: none;'>
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
									<button type="button" class="btn btn-secondary steps" first_step='#edit_resolutions #formInShowNewResolution' second_step='#edit_resolutions #showInRowEditResolution'>Закрыть</button>
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
											<div id='divMessagePrDs'>
											</div>
											<table class="table" style='margin: 0 auto;'>
												<thead>
													<tr>
														<th>Название резолюции</th>
														<th>Удаление</th>
													</tr>
												</thead>
												<tbody id='resolution_table_tbody'>
													
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary steps" first_step='#edit_resolutions #updateInShowNewResolution' second_step='#edit_resolutions #showInRowEditResolution'>Закрыть</button>
								</div>
							</div>
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