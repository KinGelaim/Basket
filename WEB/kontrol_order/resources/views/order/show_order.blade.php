@extends('layouts.header')

@section('title')
	Приказ
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if(Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Канцелярия')
				@if(Route::has('login'))
					<div class="top-right links">
						
					</div>
				@endif
				<div class="content">
					<form method='POST' action="{{route('orders.update_order', $order->id)}}">
						{{csrf_field()}}
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<label>Номер документа</label>
									<input class='form-control' type='text' value='{{$order->number_document}}' readonly />
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Дата постановки на учет</label>
									<input class='form-control' type='text' value='{{date("d.m.Y", strtotime($order->created_at))}}' readonly />
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Время постановки на учёт</label>
									<input class='form-control' type='text' value='{{date("H:i", strtotime($order->created_at))}}' readonly />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for='card_print_executor'>Карточка распечатана исполнителем</label>
									@if($order->card_print_executor)
										<input id='card_print_executor' class='form-check-input' name='card_print_executor' type="checkbox" checked />
									@else
										<input id='card_print_executor' class='form-check-input' name='card_print_executor' type="checkbox" />
									@endif
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for='id_counterpartie'>Организация</label>
									<div class="form-group">
										<select id="id_counterpartie" class='form-control {{$errors->has("id_counterpartie") ? print("inputError ") : print("")}}' name='id_counterpartie' required >
											<option></option>
											@if($counterparties)
												@foreach($counterparties as $counterpartie)
													@if(old('id_counterpartie'))
														@if(old('id_counterpartie') == $counterpartie->id)
															<option value='{{$counterpartie->id}}' selected>{{ $counterpartie->name }}</option>
														@else
															<option value='{{$counterpartie->id}}'>{{ $counterpartie->name }}</option>												
														@endif
													@else
														@if($order->id_counterpartie == $counterpartie->id)
															<option value='{{$counterpartie->id}}' selected>{{ $counterpartie->name }}</option>
														@else
															<option value='{{$counterpartie->id}}'>{{ $counterpartie->name }}</option>												
														@endif
													@endif
												@endforeach
											@endif
										</select>
										@if($errors->has('id_counterpartie'))
											<label class='msgError'>{{$errors->first('id_counterpartie')}}</label>
										@endif
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-11">
								<div class='row'>
									<div class="col-md-2">
										<div class="form-group">
											<label for='type_document'>Тип документа</label>
											<div class="form-group">
												<select id="type_document" class='form-control {{$errors->has("type_document") ? print("inputError ") : print("")}}' name='type_document' required >
													<option></option>
													@if($type_documents)
														@foreach($type_documents as $type_document)
															@if(old('type_document'))
																@if(old('type_document') == $type_document->id)
																	<option value='{{$type_document->id}}' selected>{{ $type_document->name }}</option>
																@else
																	<option value='{{$type_document->id}}'>{{ $type_document->name }}</option>												
																@endif
															@else
																@if($order->id_type_document == $type_document->id)
																	<option value='{{$type_document->id}}' selected>{{ $type_document->name }}</option>
																@else
																	<option value='{{$type_document->id}}'>{{ $type_document->name }}</option>												
																@endif
															@endif
														@endforeach
													@endif
												</select>
												@if($errors->has('type_document'))
													<label class='msgError'>{{$errors->first('type_document')}}</label>
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for='punkt_order'>Пункт приказа</label>
											<input id='punkt_order' name='punkt_order' class='form-control' type='text' value='{{$order->punkt_order}}' />
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for='number_order'>Номер приказа</label>
											<input id='number_order' name='number_order' class='form-control' type='text' value='{{$order->number_order}}' required />
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for='date_order'>Дата приказа</label>
											<input id='date_order' name='date_order' class='form-control datepicker' type='text' value='{{$order->date_order}}' required />
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for='id_period_kontrol'>Периодичность контроля</label>
											<div class="form-group">
												<select id="id_period_kontrol" class='form-control {{$errors->has("id_period_kontrol") ? print("inputError ") : print("")}}' name='id_period_kontrol'>
													<option></option>
													@if($kontrol_periods)
														@foreach($kontrol_periods as $kontrol_period)
															@if(old('id_period_kontrol'))
																@if(old('id_period_kontrol') == $kontrol_period->id)
																	<option value='{{$kontrol_period->id}}' selected>{{ $kontrol_period->name }}</option>
																@else
																	<option value='{{$kontrol_period->id}}'>{{ $kontrol_period->name }}</option>												
																@endif
															@else
																@if($order->id_period_kontrol == $kontrol_period->id)
																	<option value='{{$kontrol_period->id}}' selected>{{ $kontrol_period->name }}</option>
																@else
																	<option value='{{$kontrol_period->id}}'>{{ $kontrol_period->name }}</option>												
																@endif
															@endif
														@endforeach
													@endif
												</select>
												@if($errors->has('id_period_kontrol'))
													<label class='msgError'>{{$errors->first('id_period_kontrol')}}</label>
												@endif
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-5">
										<label for='short_maintenance'>Краткое содержане</label>
										<textarea id='short_maintenance' class='form-control' type="text" style="width: 100%;" rows='3' name='short_maintenance' required>{{$order->short_maintenance}}</textarea>
									</div>
									<div class="col-md-6">
										<label for='maintenance_order'>Содержание приказа</label>
										<textarea id='maintenance_order' class='form-control' type="text" style="width: 100%;" rows='3' name='maintenance_order' required>{{$order->maintenance_order}}</textarea>
									</div>
								</div>
							</div>
							<div class='col-md-1'>
								<div class="row">
									<div class='col-md-12'>
										<div class="form-group">
											<button class='btn btn-primary' data-toggle="modal" data-target="#scan" type='button' style='float: right; width: 184px;'>Сканы</button>
										</div>
									</div>
								</div>
								<div class="row">
									<div class='col-md-12'>
										<div class="form-group">
											<button class='btn btn-primary' data-toggle="modal" data-target="#notifycations" type='button' style='float: right; width: 184px;'>Уведомления</button>
										</div>
									</div>
								</div>
								<div class="row">
									<div class='col-md-12'>
										<div class="form-group">
											<button class='btn btn-primary' data-toggle="modal" data-target="#postponements" type='button' style='float: right; width: 184px;'>Переносы сроков</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<label for='date_maturity'><b>Срок исполнения</b></label>
									<input id='date_maturity' name='date_maturity' class='form-control datepicker' type='text' value='{{date("d.m.Y", strtotime($order->date_maturity))}}' required />
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Срок действия, дн.</label>
									<input class='form-control' type='text' value='{{$last_postponement ? (strtotime($last_postponement->date_postponement)-strtotime(date("d.m.Y",strtotime($order->created_at))))/60/60/24 : (strtotime($order->date_maturity)-strtotime(date("d.m.Y",strtotime($order->created_at))))/60/60/24}}' readonly />
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Осталось, дн.</label>
									<input class='form-control' type='text' value='{{$last_postponement ? ceil((strtotime($last_postponement->date_postponement)-time())/60/60/24) : ceil((strtotime($order->date_maturity)-time())/60/60/24)}}' readonly />
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Перенос срока</label>
									<input class='form-control' type='text' value='{{$last_postponement ? date("d.m.Y", strtotime($last_postponement->date_postponement)) : ''}}' readonly />
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Дата служ. о переносе</label>
									<input class='form-control' type='text' value='{{$last_postponement ? date("d.m.Y", strtotime($last_postponement->date_service)) : ''}}' readonly />
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Рассылка уведом.</label>
									<input class='form-control' type='text' value='{{$last_notifycation ? date("d.m.Y", strtotime($last_notifycation->created_at)) : ''}}' readonly />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<label for='id_executor'>Отв. за контроль</label>
									<div class="form-group">
										<select id="id_executor" class='form-control {{$errors->has("id_executor") ? print("inputError ") : print("")}}' name='id_executor' required >
											<option></option>
											@if($executors)
												@foreach($executors as $executor)
													@if(old('id_executor'))
														@if(old('id_executor') == $executor->id)
															<option value='{{$executor->id}}' position='{{$executor->position_department}}' telephone='{{$executor->telephone}}' selected>{{ $executor->surname }} {{ $executor->name }} {{ $executor->patronymic }}</option>
														@else
															<option value='{{$executor->id}}' position='{{$executor->position_department}}' telephone='{{$executor->telephone}}'>{{ $executor->surname }} {{ $executor->name }} {{ $executor->patronymic }}</option>												
														@endif
													@else
														@if($order->id_executor == $executor->id)
															<option value='{{$executor->id}}' position='{{$executor->position_department}}' telephone='{{$executor->telephone}}' selected>{{ $executor->surname }} {{ $executor->name }} {{ $executor->patronymic }}</option>
														@else
															<option value='{{$executor->id}}' position='{{$executor->position_department}}' telephone='{{$executor->telephone}}'>{{ $executor->surname }} {{ $executor->name }} {{ $executor->patronymic }}</option>												
														@endif
													@endif
												@endforeach
											@endif
										</select>
										@if($errors->has('id_executor'))
											<label class='msgError'>{{$errors->first('id_executor')}}</label>
										@endif
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Должность</label>
									<input id='position' class='form-control' type='text' value='{{$order->position_executor}}' readonly />
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Телефон</label>
									<input id='telephone' class='form-control' type='text' value='{{$order->telephone_executor}}' readonly />
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group">
									<label for='second_executor'>Соисполнители</label>
									<input id='second_executor' name='second_executor' class='form-control' type='text' value='{{$order->second_executor}}'/>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for='events'>Мероприятия, проведенные в ходе исполнения поручения</label>
									<textarea id='events' class='form-control' type="text" style="width: 100%;" rows='5' name='events'>{{$order->events}}</textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<label for='date_complete_executor'>Дата исполнения</label>
									<input id='date_complete_executor' name='date_complete_executor' class='form-control datepicker' type='text' value='<?php if($order->date_complete_executor) echo date("d.m.Y", strtotime($order->date_complete_executor));?>'/>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Характеристика</label>
									<input class='form-control' type='text' value='{{$order->date_complete_executor ? ($last_postponement ? ceil((strtotime($last_postponement->date_postponement)-strtotime($order->date_maturity))/60/60/24) : ceil((strtotime($order->date_complete_executor)-strtotime($order->date_maturity))/60/60/24)) : ''}}' readonly />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for='archive'>Снять с контроля (перенос в архив)</label>
									@if($order->archive)
										<input id='archive' name='archive' class='form-check-input' type='checkbox' checked />
									@else
										<input id='archive' name='archive' class='form-check-input' type='checkbox'/>
									@endif
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
									<button type='submit' class="btn btn-primary" style="float: right;">Сохранить изменения</button>
								@endif
							</div>
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
									<h5 class="modal-title" id="scanModalLabel">Сканы приказа</h5>
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
											<button type='button' class='btn btn-secondary steps' first_step='#form_all_application' second_step='#form_new_application'>Добавить скан</button>
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
											<button id='delete_resolution' type='button' class='btn btn-danger' style='width: 122px;'>Удалить скан</button>
										</div>
										<div class="col-md-3">
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
							<form id='form_new_application' method='POST' file='true' enctype='multipart/form-data' action='{{route("resolution_store", $order->id)}}' style='display: none;'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="updateApplicationModalLabel">Добавление резолюции</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='modal-body'>
									<div class='row'>
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
									<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
									<button type="button" class="btn btn-secondary steps" first_step='#form_new_application' second_step='#form_all_application'>Закрыть</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Модальное окно уведомлений -->
				<div class="modal fade" id="notifycations" tabindex="-1" role="dialog" aria-labelledby="notifyModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="notifyModalLabel">Уведомления</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class='row'>
									<div class='col-md-4 col-md-offset-4 text-all-center'>
										<label>Дата</label>
									</div>
								</div>
								@foreach($notifycations as $notifycation)
									<div class='row'>
										<div class='col-md-4 col-md-offset-4 text-all-center'>
											<p>{{date('d.m.Y', strtotime($notifycation->created_at))}}</p>
										</div>
									</div>
								@endforeach
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
							</div>
						</div>
					</div>
				</div>
				<!-- Модальное окно уведомлений -->
				<div class="modal fade" id="notifycations" tabindex="-1" role="dialog" aria-labelledby="notifyModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="notifyModalLabel">Уведомления</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class='row'>
									<div class='col-md-4 col-md-offset-4 text-all-center'>
										<label>Дата</label>
									</div>
								</div>
								@foreach($notifycations as $notifycation)
									<div class='row'>
										<div class='col-md-4 col-md-offset-4 text-all-center'>
											<p>{{date('d.m.Y', strtotime($notifycation->created_at))}}</p>
										</div>
									</div>
								@endforeach
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
							</div>
						</div>
					</div>
				</div>
				<!-- Модальное окно переносов сроков -->
				<div class="modal fade" id="postponements" tabindex="-1" role="dialog" aria-labelledby="postponementStatesModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="postponementStatesModalLabel">Перенос сроков</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class='row'>
									<div id='all_postponements' class='col-md-12'>
										<table class="table" style='margin: 0 auto;'>
											<thead>
												<tr>
													<th>Дата служебной</th>
													<th>Дата переноса</th>
												</tr>
											</thead>
											<tbody>
												@foreach($postponements as $postponement)
													<tr class='rowsContract cursorPointer'>
														<td>{{date('d.m.Y', strtotime($postponement->date_service))}}</td>
														<td>{{date('d.m.Y', strtotime($postponement->date_postponement))}}</td>
													</tr>
												@endforeach
											</tbody>
										</table>
										<button class='btn btn-primary steps' first_step='#all_postponements' second_step='#add_postponement' type='button' style='margin-top: 10px;'>Добавить перенос срока</button>
									</div>
									<div id='add_postponement' class='col-md-12' style='display: none;'>
										<form method='POST' action="{{ route('store_postponement',$order->id)}}">
											{{csrf_field()}}
											<div class='form-group row col-md-12'>
												<label for='date_service' class='col-md-3 col-form-label'>Дата служебной</label>
												<div class='col-md-9'>
													<input id='date_service' class='datepicker form-control {{$errors->has("date_service") ? print("inputError ") : print("")}}' type='text' name='date_service' value='{{date("d.m.Y", time())}}' required />
													@if($errors->has('date_service'))
														<label class='msgError'>{{$errors->first('date_service')}}</label>
													@endif
												</div>
											</div>
											<div class='form-group row col-md-12'>
												<label for='date_postponement' class='col-md-3 col-form-label'>Дата переноса</label>
												<div class='col-md-9'>
													<input id='date_postponement' class='datepicker form-control {{$errors->has("date_postponement") ? print("inputError ") : print("")}}' name='date_postponement' required />
													@if($errors->has('date_postponement'))
														<label class='msgError'>{{$errors->first('date_postponement')}}</label>
													@endif
												</div>
											</div>
											<div class='form-group row col-md-8'>
											</div>
											<div class='form-group row col-md-3'>
												<button class='btn btn-primary' type='submit'>Добавить</button>
											</div>
											<div class='form-group row col-md-1'>
												<button class='btn btn-secondary steps' first_step='#add_postponement' second_step='#all_postponements' type='button'>Назад</button>
											</div>
										</form>
									</div>
								</div>									
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
							</div>
						</div>
					</div>
				</div>
			@endif
		@else
			<div class="alert alert-danger">
				Необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection