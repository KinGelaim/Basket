@extends('layouts.header')

@section('title')
	Входящие письма
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Администрация')
				<div class="content">
					<form method='POST' action='{{ route("department.reconciliation.reconciliation_document_message", $document->id) }}'>
						{{csrf_field()}}
						<div class="row">
							<div class="col-md-12">
								<input class='form-control' type='text' value='{{$counterpartie}}' readonly />
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<label for="sel4">Выберите поле для поиска</label>
									<select class="form-control" id="sel4">
										<option></option>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<label >Поиск</label>
								<input class='form-control' type='text' value=''/>
							</div>
							<div class="col-md-1">
								<button class="btn btn-primary" type="button" href="" style="margin-top: 26px;">Поиск</button>
							</div>
							<div class="col-md-1">
								<button class='btn btn-primary' data-toggle="modal" data-target="#newApplicationPEO" type='button' style="margin-top: 26px;">Новый документ</button>
							</div>
							<div class="col-md-6">
								@if(Auth::User()->hasRole()->role != 'Администрация')
									<button class="btn btn-primary" type="submit" style="margin-top: 26px; float: right;">Прикрепить</button>
								@endif
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
									<thead>
										<tr>
											<th></th>
											<th>Номер записи</th>
											<th>Исх. №</th>
											<th>Дата</th>
											<th>Вх. №</th>
											<th>Дата</th>
											<th>Тема</th>
											<th>Просмотреть</th>
										</tr>
									</thead>
									<tbody>
										<?php $count_check_message = 0; ?>
										@foreach($applications as $application)
											<tr class='rowsContract cursorPointer rowsMessage' id_application='{{$application->id}}' for_check='check_message{{$count_check_message}}'>
												<td>
													<input id='check_message{{$count_check_message}}' class='form-check-input' type="checkbox"/>
												</td>
												<td>
													{{ $application->number_application }}
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
												<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("reconciliation.application.show", $application->id)}}'>Просмотреть</button></td>
											</tr>
											<?php $count_check_message++; ?>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</form>
				</div>
				<!-- Модальное окно новой записи -->
				<div class="modal fade" id="newApplicationPEO" tabindex="-1" role="dialog" aria-labelledby="newApplicationModalLabel" aria-hidden="true" attr-open-modal='{{$errors->has("number_application") || $errors->has("number_outgoing") || $errors->has("date_outgoing") || $errors->has("number_incoming") || $errors->has("date_incoming") || $errors->has("directed_application") || $errors->has("date_directed") ? print("open") : print("")}}'>
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
											<input id='valIdCounterpartie' class='form-control' name='id_counterpartie_application' type='text' value='{{old("id_counterpartie_application") ? old("id_counterpartie_application") : $id_counterpartie }}' style='display: none;'/>
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
