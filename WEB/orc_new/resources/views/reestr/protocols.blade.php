@extends('layouts.header')

@section('title')
	Протоколы
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="container">
				@foreach($protocols as $protocol)
					<div class='row'>
						<div class="col-md-3">
							<label>Наименование протокола:</label>
						</div>
						<div class="col-md-7">
							<input class='form-control' type='text' value='{{$protocol->name_protocol}}' readonly />
						</div>
					</div>
					<div class='row'>
						<div class="col-md-3">
							<label>Дата протокола на 1 л.:</label>
						</div>
						<div class="col-md-7">
							<input class='form-control' type='text' value='{{$protocol->date_on_first_protocol}}' readonly />
						</div>
					</div>
					<div class='row'>
						<div class="col-md-3">
							<label>Дата регистрации:</label>
						</div>
						<div class="col-md-7">
							<input class='form-control' type='text' value='{{$protocol->date_registration_protocol}}' readonly />
						</div>
					</div>
					<div class='row'>
						<div class="col-md-3">
							<label>Дата подписания ФКП "НТИИМ"</label>
						</div>
						<div class="col-md-7">
							<input class='form-control' type='text' value='{{$protocol->date_signing_protocol}}' readonly />
						</div>
					</div>
					<div class='row'>
						<div class="col-md-3">
							<label>Дата подписания контрагентом</label>
						</div>
						<div class="col-md-7">
							<input class='form-control' type='text' value='{{$protocol->date_signing_counterpartie_protocol}}' readonly />
						</div>
					</div>
					<div class='row'>
						<div class="col-md-3">
							<label>Дата вступления в силу</label>
						</div>
						<div class="col-md-7">
							<input class='form-control' type='text' value='{{$protocol->date_entry_ento_force_additional_agreement}}' readonly />
						</div>
					</div>
					<div class='row'>
						<div class="col-md-1">
							<label>Дата сдачи на хранение в ОУД</label>
						</div>
						<div class="col-md-2">
							<div class='row'>
								<div class="col-md-12">
									<label>скан (эл. вариант)</label>
									@if($protocol->is_oud_el)
										<input class='form-check-input' name='is_oud_el' type="checkbox" checked />
									@else
										<input class='form-check-input' name='is_oud_el' type="checkbox" />
									@endif
								</div>
							</div>
							<div class='row'>
								<div class="col-md-12">
									<label>оригинал</label>
									@if($protocol->is_oud)
										<input class='form-check-input' name='is_oud' type="checkbox" checked />
									@else
										<input class='form-check-input' name='is_oud' type="checkbox"/>
									@endif
								</div>
							</div>
						</div>
						<div class="col-md-7">
							<div class='row'>
								<div class="col-md-12">
									<input class='form-control' type='text' value='{{$protocol->date_oud_el_protocol}}' readonly />
								</div>
							</div>
							<div class='row'>
								<div class="col-md-12">
									<input class='form-control' type='text' value='{{$protocol->date_oud_protocol}}' readonly />
								</div>
							</div>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-1">
							<label>Дата сдачи на хранение в отдел №31</label>
						</div>
						<div class="col-md-2">
							<div class='row'>
								<div class="col-md-12">
									<label>скан (эл. вариант)</label>
									@if($protocol->is_dep_el)
										<input class='form-check-input' name='is_dep_el' type="checkbox" checked />
									@else
										<input class='form-check-input' name='is_dep_el' type="checkbox"/>
									@endif
								</div>
							</div>
							<div class='row'>
								<div class="col-md-12">
									<label>оригинал</label>
									@if($protocol->is_dep)
										<input class='form-check-input' name='is_dep' type="checkbox" checked />
									@else
										<input class='form-check-input' name='is_dep' type="checkbox" />
									@endif
								</div>
							</div>
						</div>
						<div class="col-md-7">
							<div class='row'>
								<div class="col-md-12">
									<input class='form-control' type='text' value='{{$protocol->date_dep_el_protocol}}' readonly />
								</div>
							</div>
							<div class='row'>
								<div class="col-md-12">
									<input class='form-control' type='text' value='{{$protocol->date_dep_protocol}}' readonly />
								</div>
							</div>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-3">
							<label>Сканы:</label>
						</div>
						<div class="col-md-7">
							<select id='resolution_list_{{$protocol->id}}' class='form-control'>
								@if(count($protocol->resolutions) > 0)
									@foreach($protocol->resolutions as $res)
										@if(Session::has('new_scan'))
											@if(Session('new_scan')->id == $res->id)
												<option value='http://{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}' delete_href='{{route("resolution_delete",$res->id)}}' selected>{{$res->real_name_resolution}}</option>
											@else
												<option value='http://{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}' delete_href='{{route("resolution_delete",$res->id)}}'>{{$res->real_name_resolution}}</option>
											@endif
										@else
											@if($res->type_resolution == 1)
												<option value='http://{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}' delete_href='{{route("resolution_delete",$res->id)}}' style='color: rgb(239,19,198);'>{{$res->real_name_resolution}}</option>
											@else
												<option value='http://{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}' delete_href='{{route("resolution_delete",$res->id)}}'>{{$res->real_name_resolution}}</option>
											@endif
										@endif
									@endforeach
								@else
									<option></option>
								@endif
							</select>
						</div>
						<div class="col-md-2">
							<button type='button' class='open_resolution btn btn-primary' style='width: 122px;' resolution_block='resolution_list_{{$protocol->id}}'>Открыть скан</button>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-3">
						</div>
						<div class='col-md-3'>
							<button type='button' data-toggle="modal" data-target="#edit_protocol"  class='btn-edit-protocol btn btn-secondary' 
														href_edit_protocol="{{route('department.reestr.update_protocol', $protocol->id)}}" 
														name_protocol='{{$protocol->name_protocol}}' 
														date_on_first_protocol='{{$protocol->date_on_first_protocol}}' 
														date_registration_protocol='{{$protocol->date_registration_protocol}}' 
														date_signing_protocol='{{$protocol->date_signing_protocol}}' 
														date_signing_counterpartie_protocol='{{$protocol->date_signing_counterpartie_protocol}}'
														date_entry_ento_force_additional_agreement='{{$protocol->date_entry_ento_force_additional_agreement}}'
														is_oud_el='{{$protocol->is_oud_el}}'
														is_oud='{{$protocol->is_oud}}'
														is_dep_el='{{$protocol->is_dep_el}}'
														is_dep='{{$protocol->is_dep}}'
														date_oud_el_protocol='{{$protocol->date_oud_el_protocol}}'
														date_oud_protocol='{{$protocol->date_oud_protocol}}'
														date_dep_el_protocol='{{$protocol->date_dep_el_protocol}}'
														date_dep_protocol='{{$protocol->date_dep_protocol}}'>Редактировать протокол</button>
						</div>
						<div class="col-md-2">
							@if(Auth::User()->hasRole()->role == 'Администратор')
								<button type='button' class='btn btn-danger btn-href' href="{{route('department.reestr.delete_protocol', $protocol->id)}}">Удалить протокол</button>
							@endif
						</div>
						<div class="col-md-2">
							@if(Auth::User()->hasRole()->role != 'Администрация')
								<button type='button' data-toggle="modal" data-target="#new_resolution"  class='btn btn-primary' style='width: 122px;' onclick="$('#formToStoreNewResolution').attr('action', $(this).attr('href_resolution'));" href_resolution='{{route("resolution_store",$protocol->id)}}'>Добавить скан</button>
							@endif
						</div>
						<div class="col-md-2">
							@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->surname == 'Бастрыкова')
								<button type='button' class='btn btn-danger delete_resolution' style='width: 122px;' for_select='#resolution_list_{{$protocol->id}}'>Удалить скан</button>
							@endif
						</div>
					</div>
				@endforeach
				@if(Auth::User()->hasRole()->role != 'Администрация')
					<button type='button' data-toggle="modal" data-target="#new_protocol"  class='btn btn-primary'>Добавить протокол</button>
				@endif
			</div>
			<!-- Модальное окно нового протокола -->
			<div class="modal fade" id="new_protocol" tabindex="-1" role="dialog" aria-labelledby="newProtocolModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<form method='POST' action='{{route("department.reestr.store_protocol",$id_contract)}}'>
							{{csrf_field()}}
							<div class="modal-header">
								<h5 class="modal-title" id="newProtocolModalLabel">Добавление протокола</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class='modal-body'>
								<div class='row'>
									<div class="col-md-4">
										<label>Наименование протокола:</label>
									</div>
									<div class="col-md-8">
										<input class='form-control' name='name_protocol' type='text' value='' required/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата протокола на 1 л.:</label>
									</div>
									<div class="col-md-8">
										<input class='datepicker form-control' name='date_on_first_protocol' type='text' value=''/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата регистрации:</label>
									</div>
									<div class="col-md-8">
										<input class='datepicker form-control' name='date_registration_protocol' type='text' value='' required/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата подписания ФКП "НТИИМ"</label>
									</div>
									<div class="col-md-8">
										<input class='datepicker form-control' name='date_signing_protocol' type='text' value=''/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата подписания контрагентом</label>
									</div>
									<div class="col-md-8">
										<input class='datepicker form-control' name='date_signing_counterpartie_protocol' type='text' value=''/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата вступления в силу</label>
									</div>
									<div class="col-md-8">
										<input class='datepicker form-control' name='date_entry_ento_force_additional_agreement' type='text' value=''/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-2">
										<label>Дата сдачи на хранение в ОУД</label>
									</div>
									<div class="col-md-4">
										<div class='row'>
											<div class="col-md-12">
												<label for='is_oud_el'>скан (эл. вариант)</label>
												<input id='is_oud_el' class='form-check-input' name='is_oud_el' type="checkbox"/>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<label for='is_oud'>оригинал</label>
												<input id='is_oud' class='form-check-input' name='is_oud' type="checkbox"/>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class='row'>
											<div class="col-md-12">
												<input class='datepicker form-control' name='date_oud_el_protocol' type='text' value=''/>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<input class='datepicker form-control' name='date_oud_protocol' type='text' value=''/>
											</div>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-2">
										<label>Дата сдачи на хранение в отдел №31</label>
									</div>
									<div class="col-md-4">
										<div class='row'>
											<div class="col-md-12">
												<label for='is_dep_el'>скан (эл. вариант)</label>
												<input id='is_dep_el' class='form-check-input' name='is_dep_el' type="checkbox"/>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<label for='is_dep'>оригинал</label>
												<input id='is_dep' class='form-check-input' name='is_dep' type="checkbox"/>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class='row'>
											<div class="col-md-12">
												<input class='datepicker form-control' name='date_dep_el_protocol' type='text' value=''/>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<input class='datepicker form-control' name='date_dep_protocol' type='text' value=''/>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type='submit' class='btn btn-primary' type='button'>Добавить</button>
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- Модальное окно редактирования протокола -->
			<div class="modal fade" id="edit_protocol" tabindex="-1" role="dialog" aria-labelledby="editProtocolModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<form id='formToUpdateProtocol' method='POST' action=''>
							{{csrf_field()}}
							<div class="modal-header">
								<h5 class="modal-title" id="editProtocolModalLabel">Редактирование протокола</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class='modal-body'>
								<div class='row'>
									<div class="col-md-4">
										<label>Наименование протокола:</label>
									</div>
									<div class="col-md-8">
										<input id='update_name_protocol' class='form-control' name='name_protocol' type='text' value='' required/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата протокола на 1 л.:</label>
									</div>
									<div class="col-md-8">
										<input id='update_date_on_first_protocol' class='datepicker form-control' name='date_on_first_protocol' type='text' value=''/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата регистрации:</label>
									</div>
									<div class="col-md-8">
										<input id='update_date_registration_protocol' class='datepicker form-control' name='date_registration_protocol' type='text' value='' required/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата подписания ФКП "НТИИМ"</label>
									</div>
									<div class="col-md-8">
										<input id='update_date_signing_protocol' class='datepicker form-control' name='date_signing_protocol' type='text' value=''/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата подписания контрагентом</label>
									</div>
									<div class="col-md-8">
										<input id='update_date_signing_counterpartie_protocol' class='datepicker form-control' name='date_signing_counterpartie_protocol' type='text' value=''/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата вступления в силу</label>
									</div>
									<div class="col-md-8">
										<input id='update_date_entry_ento_force_additional_agreement' class='datepicker form-control' name='date_entry_ento_force_additional_agreement' type='text' value=''/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-2">
										<label>Дата сдачи на хранение в ОУД</label>
									</div>
									<div class="col-md-4">
										<div class='row'>
											<div class="col-md-12">
												<label for='update_is_oud_el'>скан (эл. вариант)</label>
												<input id='update_is_oud_el' class='form-check-input' name='is_oud_el' type="checkbox"/>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<label for='update_is_oud'>оригинал</label>
												<input id='update_is_oud' class='form-check-input' name='is_oud' type="checkbox"/>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class='row'>
											<div class="col-md-12">
												<input id='update_date_oud_el_protocol' class='datepicker form-control' name='date_oud_el_protocol' type='text' value=''/>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<input id='update_date_oud_protocol' class='datepicker form-control' name='date_oud_protocol' type='text' value=''/>
											</div>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-2">
										<label>Дата сдачи на хранение в отдел №31</label>
									</div>
									<div class="col-md-4">
										<div class='row'>
											<div class="col-md-12">
												<label for='update_is_dep_el'>скан (эл. вариант)</label>
												<input id='update_is_dep_el' class='form-check-input' name='is_dep_el' type="checkbox"/>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<label for='update_is_dep'>оригинал</label>
												<input id='update_is_dep' class='form-check-input' name='is_dep' type="checkbox"/>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class='row'>
											<div class="col-md-12">
												<input id='update_date_dep_el_protocol' class='datepicker form-control' name='date_dep_el_protocol' type='text' value=''/>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<input id='update_date_dep_protocol' class='datepicker form-control' name='date_dep_protocol' type='text' value=''/>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								@if(Auth::User()->hasRole()->role != 'Администрация')
									<button type='submit' class='btn btn-primary' type='button'>Изменить</button>
								@endif
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- Модальное окно новой резолюции -->
			<div class="modal fade" id="new_resolution" tabindex="-1" role="dialog" aria-labelledby="newResolutionModalLabel" aria-hidden="true" attr-open-modal='{{$errors->has("number_application_update") || $errors->has("number_outgoing_update") || $errors->has("date_outgoing_update") || $errors->has("number_incoming_update") || $errors->has("date_incoming_update") || $errors->has("directed_application_update") || $errors->has("date_directed_update") || Session::has("new_scan") ? print("open") : print("")}}'>
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<form id='formToStoreNewResolution' method='POST' file='true' enctype='multipart/form-data' action=''>
							{{csrf_field()}}
							<div class="modal-header">
								<h5 class="modal-title" id="newResolutionModalLabel">Добавление резолюции</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class='modal-body'>
								<div class='row'>
									<div class='col-md-6' style='display: none;'>
										<input type='text' value='id_protocol_resolution' name='real_name_document'/>
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
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		@else
			<div class="alert alert-danger">
				Для просмотра данной страницы необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection
