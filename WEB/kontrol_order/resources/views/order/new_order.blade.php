@extends('layouts.header')

@section('title')
	Добавить приказ
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
					<form method='POST' action="{{route('orders.store_order')}}" file='true' enctype='multipart/form-data'>
						{{csrf_field()}}
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<label>Номер документа</label>
									<input class='form-control' type='text' value='{{$last_number_document+1}}' readonly />
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Дата постановки на учет</label>
									<input class='form-control' type='text' value='{{date("d.m.Y", time())}}' readonly />
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Время постановки на учёт</label>
									<input class='form-control' type='text' value='{{date("H:i", time())}}' readonly />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for='card_print_executor'>Карточка распечатана исполнителем</label>
									<input id='card_print_executor' class='form-check-input' name='card_print_executor' type="checkbox" />
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
														<option value='{{$counterpartie->id}}'>{{ $counterpartie->name }}</option>
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
																<option value='{{$type_document->id}}'>{{ $type_document->name }}</option>
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
											<input id='punkt_order' name='punkt_order' class='form-control' type='text' value='' />
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for='number_order'>Номер приказа</label>
											<input id='number_order' name='number_order' class='form-control' type='text' value='' required />
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for='date_order'>Дата приказа</label>
											<input id='date_order' name='date_order' class='form-control datepicker' type='text' value='' required />
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
																<option value='{{$kontrol_period->id}}'>{{ $kontrol_period->name }}</option>
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
										<textarea id='short_maintenance' class='form-control' type="text" style="width: 100%;" rows='3' name='short_maintenance' required></textarea>
									</div>
									<div class="col-md-6">
										<label for='maintenance_order'>Содержание приказа</label>
										<textarea id='maintenance_order' class='form-control' type="text" style="width: 100%;" rows='3' name='maintenance_order' required></textarea>
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
											<button class='btn btn-primary' data-toggle="modal" data-target="#notifications" type='button' style='float: right; width: 184px;' disabled>Уведомления</button>
										</div>
									</div>
								</div>
								<div class="row">
									<div class='col-md-12'>
										<div class="form-group">
											<button class='btn btn-primary' data-toggle="modal" data-target="#move_times" type='button' style='float: right; width: 184px;' disabled>Переносы сроков</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<label for='date_maturity'><b>Срок исполнения</b></label>
									<input id='date_maturity' name='date_maturity' class='form-control datepicker' type='text' value='' required />
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Срок действия, дн.</label>
									<input class='form-control' type='text' value='' readonly />
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Осталось, дн.</label>
									<input class='form-control' type='text' value='' readonly />
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Перенос срока</label>
									<input class='form-control' type='text' value='' readonly />
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Дата служ. о переносе</label>
									<input class='form-control' type='text' value='' readonly />
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Рассылка уведом.</label>
									<input class='form-control' type='text' value='' readonly />
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
														<option value='{{$executor->id}}' position='{{$executor->position_department}}' telephone='{{$executor->telephone}}'>{{ $executor->surname }} {{ $executor->name }} {{ $executor->patronymic }}</option>
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
									<input id='position' class='form-control' type='text' value='' readonly />
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Телефон</label>
									<input id='telephone' class='form-control' type='text' value='' readonly />
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group">
									<label for='second_executor'>Соисполнители</label>
									<input id='second_executor' name='second_executor' class='form-control' type='text' value=''/>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for='events'>Мероприятия, проведенные в ходе исполнения поручения</label>
									<textarea id='events' class='form-control' type="text" style="width: 100%;" rows='5' name='events'></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<label for='date_complete_executor'>Дата исполнения</label>
									<input id='date_complete_executor' name='date_complete_executor' class='form-control datepicker' type='text' value=''/>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Характеристика</label>
									<input class='form-control' type='text' value='' readonly />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for='archive'>Снять с контроля (перенос в архив)</label>
									<input id='archive' name='archive' class='form-check-input' type='checkbox'/>
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
									<button type='submit' class="btn btn-primary" style="float: right;">Сохранить приказ</button>
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
		@else
			<div class="alert alert-danger">
				Необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection