@extends('layouts.header')

@section('title')
	Приказы
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Канцелярия')
				<div class="content">
					<form>
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<label for="selSearch">Выберите поле для поиска</label>
									<select class="form-control" id="selSearch" name='search_name'>
										<option></option>
										<option value='number_contract' <?php if($search_name == 'number_contract') echo 'selected'; ?>>Номер документа</option>
										<option value='name_view_contract' <?php if($search_name == 'name_view_contract') echo 'selected'; ?>>Дата регистрации документа</option>
										<option value='name_work_contract' <?php if($search_name == 'name_work_contract') echo 'selected'; ?>>Номер приказа</option>
										<option value='item_contract' <?php if($search_name == 'item_contract') echo 'selected'; ?>>Дата приказа</option>
										<option value='app_outgoing_number_reestr' <?php if($search_name == 'app_outgoing_number_reestr') echo 'selected'; ?>>Срок исполнения</option>
										<option value='number_counterpartie_contract_reestr' <?php if($search_name == 'number_counterpartie_contract_reestr') echo 'selected'; ?>>Исполнитель</option>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<label >Поиск</label>
								<input class='form-control' type='text' value='{{$search_value}}' name='search_value'/>
							</div>
							<div class="col-md-1">
								<button class="btn btn-primary" type="submit" href="" style="margin-top: 26px;">Поиск</button>
							</div>
							<div class="col-md-3">
							</div>
							<div class="col-md-4">
								<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#print" style="margin-top: 26px; float: right;">Отчеты</button>
								<button class='btn btn-primary' type='button' data-toggle="modal" data-target="#dictionary" style="margin-top: 26px; float: right; margin-right: 5px;">Справочники</button>
								<button class="btn btn-primary btn-href" type="button" href="{{ route('orders.new_order') }}" style="margin-top: 26px; float: right; margin-right: 10px;">Добавить приказ</button>
							</div>
						</div>
					</form>
					<div class="row">
						<div class="col-md-12">
							Список приказов
						</div>
					</div>
					<div class="row">
						<div id="allcontracts" class="col-md-12">
							@if($orders)
								<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
									<thead>
										<tr>
											<th style='text-align: center; vertical-align: middle;'>Номер, дата<br/>регистрации<br/>документа</th>										
											<th style='text-align: center; vertical-align: middle;'>Организация</th>
											<th style='text-align: center; vertical-align: middle;'>Тип документа</th>
											<th style='text-align: center; vertical-align: middle;'>Номер, дата<br/>приказа</th>
											<th style='text-align: center; vertical-align: middle;'>Срок исполнения/<br/>Дата рассылки</th>
											<th style='text-align: center; vertical-align: middle;'>Перенос<br/> срока / Дата<br/>служ.записки<br/>о переносе</th>
											<th style='text-align: center; vertical-align: middle;'>Срок действия/<br/>Осталось дн.<br/>Периодичность<br/>контроля</th>
											<th style='text-align: center; vertical-align: middle;'>Ответственный за<br/>контроль /<br/>Соисполнители</th>
											@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Канцелярия')
												<th style='text-align: center; vertical-align: middle;'>Удаление</th>
											@endif
										</tr>
									</thead>
									<tbody>
										@foreach($orders as $order)
											<tr class='rowsContract cursorPointer btn-href' href="{{route('orders.show_order', $order->id)}}">
												<td style='text-align: center; vertical-align: middle; <?php if($order->last_postponement ? ceil((strtotime($order->last_postponement['date_postponement'])-time())/60/60/24) < 0 : ceil((strtotime($order->date_maturity)-time())/60/60/24) < 0) echo "color: red;"?>'>
													{{ $order->number_document }}<br/>{{date('d.m.Y',strtotime($order->created_at))}}
												</td>
												<td>
													{{ $order->counterpartie }}
												</td>
												<td>
													{{ $order->type_document }}
												</td>
												<td style='text-align: center; vertical-align: middle;'>
													{{ $order->number_order }}<br/>{{$order->date_order}}
												</td>
												<td style='text-align: center; vertical-align: middle;'>
													{{ date('d.m.Y',strtotime($order->date_maturity)) }}<br/>
												</td>
												<td style='text-align: center; vertical-align: middle;'>
													{{$order->last_postponement ? date('d.m.Y', strtotime($order->last_postponement['date_postponement'])) : ''}}<br/>{{$order->last_postponement['date_service'] ? date('d.m.Y', strtotime($order->last_postponement['date_service'])) : ''}}
												</td>
												<td style='text-align: center; vertical-align: middle;'>
													{{$order->last_postponement ? ((strtotime($order->last_postponement['date_postponement'])-strtotime(date("d.m.Y",strtotime($order->created_at))))/60/60/24) : ((strtotime($order->date_maturity)-strtotime(date("d.m.Y",strtotime($order->created_at))))/60/60/24)}} / {{$order->last_postponement ? ceil((strtotime($order->last_postponement['date_postponement'])-time())/60/60/24) : ceil((strtotime($order->date_maturity)-time())/60/60/24)}}<br/>{{$order->period_kontrol}}
												</td>
												<td>
													{{$order->surname}} {{substr($order->name,0,2)}}.{{substr($order->patronymic,0,2)}}.<br/>{{$order->second_executor}}
												</td>
												@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Канцелярия')
													<td>
														<button type='button' class='btn btn-danger btn-href' type='button' href='{{route("orders.delete_order",$order->id)}}' disabled >Удалить</button>
													</td>
												@endif
											</tr>
										@endforeach
									</tbody>
								</table>
							@endif
						</div>
						<div class="col-md-12" style="text-align: center;">
							
						</div>
					</div>
					<!-- Модальное окно справочников -->
					<div class="modal fade" id="dictionary" tabindex="-1" role="dialog" aria-labelledby="dictionaryModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="dictionaryModalLabel">Формирование отчетов</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='row'>
										<div class='col-md-4'>
											<button class="btn btn-primary btn-href" href="{{route('counterpartie.main')}}" type="button" style='width: 170px;'>Организации</button>
										</div>
										<div class='col-md-4'>
											<button class="btn btn-primary btn-href" href="{{route('type_document.main')}}" type="button" style='width: 170px;'>Типы документов</button>
										</div>
										<div class='col-md-4'>
											<button class="btn btn-primary btn-href" href="{{route('kontrol_period.main')}}" type="button" style='width: 170px;'>Периоды контроля</button>
										</div>
									</div>
									<div class='row'>
										<div class='col-md-4'>
											<button class="btn btn-primary btn-href" href="{{route('user.main')}}" type="button" style='width: 170px;'>Исполнители</button>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</div>
						</div>
					</div>
					<!-- Модальное окно отчетов -->
					<div class="modal fade" id="print" tabindex="-1" role="dialog" aria-labelledby="printModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="printModalLabel">Формирование отчетов</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div id='all_notify'>
										<div class='row'>
											<div class='col-md-4 col-md-offset-4 text-all-center'>
												<label>Уведомления</label>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-4'>
												<button class="btn btn-primary btn-href" href="{{route('print_notify')}}?name_table=result" type="button" style='width: 170px;'>Уведомление<br/>(сводный)</button>
											</div>
											<div class='col-md-4'>
												<button class="btn btn-primary steps" first_step='#all_notify' second_step='#executor_notify' type="button" style='width: 170px;'>Уведомление<br/>(по Отв.)</button>
											</div>
											<div class='col-md-4'>
												<button class="btn btn-primary steps" first_step='#all_notify' second_step='#number_document_notify' type="button" style='width: 170px;'>Уведомление<br/>(по № док.)</button>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-4'>
												<button class="btn btn-primary steps" first_step='#all_notify' second_step='#month_notify' type="button" style='width: 170px;'>К исполнению в<br/>течение месяца</button>
											</div>
											<div class='col-md-4'>
												<button class="btn btn-primary btn-href" href="{{route('print_notify')}}?name_table=today" type="button" style='width: 170px;'>К рассылке<br/>СЕГОДНЯ</button>
											</div>
											<div class='col-md-4'>
												<button class="btn btn-primary steps" first_step='#all_notify' second_step='#period_notify' type="button" style='width: 170px;'>Периодичность<br/>контроля</button>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-4 col-md-offset-4 text-all-center'>
												<label>Отчёты</label>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-4'>
												<button class="btn btn-primary steps replace-attr" first_step='#all_notify' second_step='#executor_report' replace_element='#name_report' replace_attr='value' replace_value='no_complete' type="button" style='width: 170px;'>Не исполненные</button>
											</div>
											<div class='col-md-4'>
												<button class="btn btn-primary steps replace-attr" first_step='#all_notify' second_step='#executor_report' replace_element='#name_report' replace_attr='value' replace_value='month' type="button" style='width: 170px;'>К исполнению<br/>в месяце</button>
											</div>
											<div class='col-md-4'>
												<button class="btn btn-primary steps replace-attr" first_step='#all_notify' second_step='#executor_report' replace_element='#name_report' replace_attr='value' replace_value='complete' type="button" style='width: 170px;'>Исполненные</button>
											</div>
										</div>
									</div>
									<div id='executor_report' style='display: none;'>
										<form method='GET' action="{{route('print_report')}}">
											<input id='name_report' style='display: none' name='name_report' value='' />
											<div class='row'>
												<div class='col-md-12'>
													<div class="form-group">
														<label for='id_executor'>Исполнитель</label>
														<div class="form-group">
															<select id="id_executor" class='form-control {{$errors->has("id_executor") ? print("inputError ") : print("")}}' name='id_executor'>
																<option></option>
																@if($executors)
																	@foreach($executors as $executor)
																		<option value='{{$executor->id}}' position='{{$executor->position_department}}' telephone='{{$executor->telephone}}'>{{ $executor->surname }} {{ $executor->name }} {{ $executor->patronymic }}</option>												
																	@endforeach
																@endif
															</select>
															@if($errors->has('id_executor'))
																<label class='msgError'>{{$errors->first('id_executor')}}</label>
															@endif
														</div>
													</div>
												</div>
											</div>
											<div class='row'>
												<div class='col-md-4'>
												</div>
												<div class='col-md-4'>
													<button class="btn btn-secondary steps" first_step='#executor_report' second_step='#all_notify' type="button">Назад</button>
												</div>
												<div class='col-md-4'>
													<button class="btn btn-primary" type="submit">Сформировать</button>
												</div>
											</div>
										</form>
									</div>
									<div id='executor_notify' style='display: none;'>
										<form method='GET' action="{{route('print_notify')}}">
											<input style='display: none' name='name_table' value='executor' />
											<div class='row'>
												<div class='col-md-12'>
													<div class="form-group">
														<label for='id_executor'>Отв. за контроль</label>
														<div class="form-group">
															<select id="id_executor" class='form-control {{$errors->has("id_executor") ? print("inputError ") : print("")}}' name='id_executor' required >
																<option></option>
																@if($executors)
																	@foreach($executors as $executor)
																		<option value='{{$executor->id}}' position='{{$executor->position_department}}' telephone='{{$executor->telephone}}'>{{ $executor->surname }} {{ $executor->name }} {{ $executor->patronymic }}</option>												
																	@endforeach
																@endif
															</select>
															@if($errors->has('id_executor'))
																<label class='msgError'>{{$errors->first('id_executor')}}</label>
															@endif
														</div>
													</div>
												</div>
											</div>
											<div class='row'>
												<div class='col-md-4'>
												</div>
												<div class='col-md-4'>
													<button class="btn btn-secondary steps" first_step='#executor_notify' second_step='#all_notify' type="button">Назад</button>
												</div>
												<div class='col-md-4'>
													<button class="btn btn-primary" type="submit">Сформировать</button>
												</div>
											</div>
										</form>
									</div>
									<div id='number_document_notify' style='display: none;'>
										<form method='GET' action="{{route('print_notify')}}">
											<input style='display: none' name='name_table' value='number_document' />
											<div class='row'>
												<div class='col-md-12'>
													<div class="form-group">
														<label>Номер документа</label>
														<input class='form-control' name='number_document' value='' required />
													</div>
												</div>
											</div>
											<div class='row'>
												<div class='col-md-4'>
												</div>
												<div class='col-md-4'>
													<button class="btn btn-secondary steps" first_step='#number_document_notify' second_step='#all_notify' type="button">Назад</button>
												</div>
												<div class='col-md-4'>
													<button class="btn btn-primary" type="submit">Сформировать</button>
												</div>
											</div>
										</form>
									</div>
									<div id='month_notify' style='display: none;'>
										<form method='GET' action="{{route('print_notify')}}">
											<input style='display: none' name='name_table' value='month' />
											<div class='row'>
												<div class='col-md-12'>
													<div class="form-group">
														<label for='month'>Месяц</label>
														<div class="form-group">
															<select id="month" class='form-control' name='month' required >
																<option value='01'>Январь</option>
																<option value='02'>Февраль</option>
																<option value='03'>Март</option>
																<option value='04'>Апрель</option>
																<option value='05'>Май</option>
																<option value='06'>Июнь</option>
																<option value='07'>Июль</option>
																<option value='08'>Август</option>
																<option value='09'>Сентябрь</option>
																<option value='10'>Октябрь</option>
																<option value='11'>Ноябрь</option>
																<option value='12'>Декабрь</option>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class='row'>
												<div class='col-md-4'>
												</div>
												<div class='col-md-4'>
													<button class="btn btn-secondary steps" first_step='#month_notify' second_step='#all_notify' type="button">Назад</button>
												</div>
												<div class='col-md-4'>
													<button class="btn btn-primary" type="submit">Сформировать</button>
												</div>
											</div>
										</form>
									</div>
									<div id='period_notify' style='display: none;'>
										<form method='GET' action="{{route('print_notify')}}">
											<input style='display: none' name='name_table' value='period' />
											<div class='row'>
												<div class='col-md-12'>
													<div class="form-group">
														<label>Начальная дата</label>
														<input class='form-control datepicker' name='beginPeriod' value='' required />
													</div>
												</div>
											</div>
											<div class='row'>
												<div class='col-md-12'>
													<div class="form-group">
														<label>Конечная дата</label>
														<input class='form-control datepicker' name='endPeriod' value='' required />
													</div>
												</div>
											</div>
											<div class='row'>
												<div class='col-md-4'>
												</div>
												<div class='col-md-4'>
													<button class="btn btn-secondary steps" first_step='#period_notify' second_step='#all_notify' type="button">Назад</button>
												</div>
												<div class='col-md-4'>
													<button class="btn btn-primary" type="submit">Сформировать</button>
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
