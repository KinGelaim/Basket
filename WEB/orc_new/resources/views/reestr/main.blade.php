@extends('layouts.header')

@section('title')
	Реестр договоров
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role != 'Пользователь')
				<div class="content">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="sel1">Выберите год</label>
								<select class="form-control" id="sel1">
									<option value="">Все года</option>
									@if($year)
										@if($years)
											@foreach($years as $in_years)
												@if($year == $in_years->year_contract)
													<option selected>{{ $in_years->year_contract }}</option>
												@else
													<option>{{ $in_years->year_contract }}</option>
												@endif
											@endforeach
										@endif
									@else
										@if($years)
											@foreach($years as $year)
												<option>{{ $year->year_contract }}</option>
											@endforeach
										@endif
									@endif
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="sel2">Выберите вид договора</label>
								<select class="form-control" id="sel2">
									<option value="">Все виды договоров</option>
									@if($viewContract)
										@if($viewContracts)
											@foreach($viewContracts as $in_viewContracts)
												@if($viewContract == $in_viewContracts->name_view_contract)
													<option selected>{{ $in_viewContracts->name_view_contract }}</option>
												@else
													<option>{{ $in_viewContracts->name_view_contract }}</option>
												@endif
											@endforeach
										@endif
									@else
										@if($viewContracts)
											@foreach($viewContracts as $viewContract)
												<option>{{ $viewContract->name_view_contract }}</option>
											@endforeach
										@endif
									@endif
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="sel3">Выберите подразделение</label>
								<select class="form-control" id="sel3">
									<option value="all">Все подразделения</option>
									@if($department)
										@if($departments)
											@foreach($departments as $in_departments)
												@if($department == $in_departments->index_department)
													<option value='{{ $in_departments->index_department }}' selected>{{ $in_departments->index_department }} {{ $in_departments->name_department }}</option>
												@else
													<option value='{{ $in_departments->index_department }}'>{{ $in_departments->index_department }} {{ $in_departments->name_department }}</option>
												@endif
											@endforeach
										@endif
									@else
										@if($departments)
											@foreach($departments as $department)
												<option value='{{ $department->index_department }}'>{{ $department->index_department }} {{ $department->name_department }}</option>
											@endforeach
										@endif
									@endif
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<button id='refreshContractReestr' class="btn btn-primary" type="button" href="{{ route('reestr.show') }}" style="margin-top: 26px;">Обновить список</button>
						</div>
						@if(Auth::User()->hasRole()->role == 'Отдел 22' OR Auth::User()->hasRole()->role == 'Администратор')
							<div class="col-md-1">
								<button class="btn btn-primary btn-href" type="button" href="{{ route('counterpartie.main') }}" style="margin-top: 26px;">Контрагенты</button>
							</div>
						@endif
					</div>
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label for="sel4">Выберите контрагента</label>
								<select class="form-control" id="sel4">
									<option value="">Все контрагенты</option>
									@if($counterpartie)
										@if($counterparties)
											@foreach($counterparties as $in_counterparties)
												@if($counterpartie == $in_counterparties->id)
													<option selected value='{{$in_counterparties->id}}'>{{ $in_counterparties->name }}</option>
												@else
													<option value='{{$in_counterparties->id}}'>{{ $in_counterparties->name }}</option>
												@endif
											@endforeach
										@endif
									@else
										@if($counterparties)
											@foreach($counterparties as $in_counterparties)
												<option value='{{$in_counterparties->id}}'>{{ $in_counterparties->name }}</option>
											@endforeach
										@endif
									@endif
								</select>
							</div>
						</div>
						<div class="col-md-1">
							<div class="form-group">
								<button type='button' data-toggle="modal" data-target="#chose_counterpartie" class="btn btn-primary" style='margin-top: 27px;'>Выбрать</button>
							</div>
						</div>
						<div class="col-md-3">
							<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#print" style="margin-top: 26px; float: right;">Отчеты</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							@includeif('layouts.search', ['search_arr_value'=>['number_contract'=>'№ договора','item_contract'=>'Предмет договора']])
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							Список договоров
						</div>
					</div>
					<div class="row">
						<div id="allcontracts" class="col-md-12">
							@if($contracts)
								<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
									<thead>
										<tr>
											<th class='cursorPointer btn-href' href='{{ route("reestr.show") }}?sorting=cast_number_pp&sort_p={{$re_sort}}&{{$link}}'>Номер договора<span>{{ $sort == 'cast_number_pp' ? $sort_span : ''}}</th>
											<th>Вид договора</th>
											<th>Предмет договора</th>
											<th>Цель/Основание договора</th>
											<th>Сумма</th>
											<th>Контрагент</th>
											@if(Auth::User()->hasRole()->role == 'Администратор')
												<th>Удаление</th>
											@endif
										</tr>
									</thead>
									<tbody>
										@foreach($contracts as $contract)
											<tr class='rowsContract cursorPointer btn-href' id_contact='{{$contract->id}}' href='{{ route("department.ekonomic.contract_new_reestr",$contract->id) }}' contextmenu="my-right-click-menu-{{$contract->id}}">
												<td>
													{{ $contract->number_contract }}
												</td>
												<td>
													{{ $contract->name_view_contract }}
												</td>
												<td>
													{{ $contract->item_contract }}
												</td>
												<td>
													{{ $contract->name_work_contract }}
												</td>
												<td style='width: 120px;'>
													{{ $contract->amount_reestr }}
												</td>
												<td>
													{{ $contract->name_counterpartie_contract }}
												</td>
												@if(Auth::User()->hasRole()->role == 'Администратор')
													<td>
														<button type='button' class='btn btn-danger btn-href' type='button' href='{{route("department.ekonomic.delete",$contract->id)}}'>Удалить</button>
													</td>
												@endif
											</tr>
											<menu type="context" id="my-right-click-menu-{{$contract->id}}">
												<menuitem label="Граф" class='btn-href' href="{{route('tree_map.show_contract',$contract->id)}}"></menuitem>
											</menu>
										@endforeach
									</tbody>
								</table>
							@endif
						</div>
						<div class="col-md-12" style="text-align: center;">
							@include('layouts.paginate')
						</div>
					</div>
				</div>
				<!-- Модальное окно отчетов -->
				<div class="modal fade" id="print" tabindex="-1" role="dialog" aria-labelledby="printModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="printModalLabel">Печать отчетов</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class='form-group row'>
									<div id='printList'>
										<div class="col-md-12" style='text-align: center;'>
											<label>СПРАВКИ</label>
										</div>
										<div class="col-md-12">
											<div class='row'>
												<div class="col-md-6">
													<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('проекты на закуп за период');">Справка: проекты Договоров/Контрактов на закуп за период</button>
												</div>
												<div class="col-md-6">
													<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentPeriod' onclick="$('#departmentPeriod input[name=real_name_table]').val('отчет по подразделению по исполнителю');">Отчет о Договорах/Контрактов по подразделению по Исполнителю</button>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-6">
													<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('проекты на сбыт за период');">Справка: проекты Договоров/Контрактов на сбыт за период</button>
												</div>
												<div class="col-md-6">
													<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('просроченое по подразделению');">Просроченные проекты Договоров/Контрактов по подразделению</button>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-6">
													<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('заключенные на закуп за период');">Справка по подразделению на закуп: заключенные Договора/Контракты за период</button>
												</div>
												<div class="col-md-6">
													<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('отказы за период');">Отказы по Договорам/Контрактам, зарегистрированным ОУД за период</button>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-6">
													<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('заключенные на сбыт за период');">Справка по подразделению на сбыт: заключенные Договора/Контракты за период</button>
												</div>
												<div class="col-md-6">
													<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('пролонгированные за период');">Отчет по пролонгированным Договорам/Контрактам, зарегистированным ОУД</button>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-6">
													<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('не заключенные на закуп за период');">Справка по подразделению на закуп: не заключенные Договора/Контракты за период</button>
												</div>
												<div class="col-md-6">
													<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('отчет по иным за период');">Отчет по иным Договорам/Контрактам за период</button>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-6">
													<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('не заключенные на сбыт за период');">Справка по подразделению на сбыт: не заключенные Договора/Контракты за период</button>
												</div>
												<div class="col-md-6">
													<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('сводная таблица поставщиков');">Сводная таблица Поставщиков по инвестициям</button>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-6">
													<button type="button" class="btn btn-secondary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#counterpartiePeriod' onclick="$('#counterpartiePeriod input[name=real_name_table]').val('справка о контрагенте за период');">Справка о Договорах/Контрактов по Контрагенту (на один)</button>
												</div>
												<div class="col-md-6">
													<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('сводная таблица заказчиков');">Сводная таблица Заказчиков (ФКП "НТИИМ" Участник во всех данных закупках)</button>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-6">
													<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('справка о контрагентах за период');">Справка о Договорах/Контрактов по Контрагенту за период</button>
												</div>
												<div class="col-md-6">
													<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('список заявок на сатадии согласования');">Список заявок, зарегистрированных в Реестре - есть проект Договора (стадия согласования)</button>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-6">
													<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentPeriod' onclick="$('#departmentPeriod input[name=real_name_table]').val('отчет по подразделению за период');">Отчет о Договорах/Контрактов по подразделению за период</button>
												</div>
												<div class="col-md-6">
													<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('список заявок без проекта');">Список заявок, зарегистрированных в Реестре - проектов нет</button>
												</div>
											</div>
										</div>
									</div>
									<div id='completePeriod' class="col-md-6 col-md-offset-3" style='display: none;'>
										<form method='GET' action='{{route("department.reestr.print_reestr")}}'>
											<div class='row'>
												<div class="col-md-12">
													<label>Период</label>
													<label>с</label>
													<input class='datepicker form-control' type='text' name='date_begin' value="01.01.{{date('Y', time())}}" required/>
													<label>по</label>
													<input class='datepicker form-control' type='text' name='date_end' value="{{date('d.m.Y', time())}}" required/>
												</div>
											</div>
											<input name='real_name_table' value='' style='display: none;'/>
											<div class='row'>
												<div class="col-md-6">
													<button type="submit" class="btn btn-primary">Сформировать</button>
												</div>
												<div class="col-md-6">
													<button type="button" class="btn btn-secondary steps" first_step='#completePeriod' second_step='#printList' style='float: right;'>Назад</button>
												</div>
											</div>
										</form>
									</div>
									<div id='departmentPeriod' class="col-md-6 col-md-offset-3" style='display: none;'>
										<form method='GET' action='{{route("department.reestr.print_reestr")}}'>
											<div class='row'>
												<div class="col-md-12">
													<div class="form-group">
														<label>Выберите подразделение</label>
														<select class="form-control" name='department' required>
															<option></option>
															@foreach($departments as $department)
																<option value='{{$department->id}}'>{{$department->index_department}} {{$department->name_department}}</option>
															@endforeach
														</select>
													</div>
												</div>
												<div class="col-md-12">
													<label>Период с</label>
													<input class='datepicker form-control' type='text' name='date_begin' value="01.01.{{date('Y', time())}}"/>
													<label>по</label>
													<input class='datepicker form-control' type='text' name='date_end' value="{{date('d.m.Y', time())}}"/>
												</div>
											</div>
											<input name='real_name_table' value='' style='display: none;'/>
											<div class='row'>
												<div class="col-md-6">
													<button type="submit" class="btn btn-primary">Сформировать</button>
												</div>
												<div class="col-md-6">
													<button type="button" class="btn btn-secondary steps" first_step='#departmentPeriod' second_step='#printList' style='float: right;'>Назад</button>
												</div>
											</div>
										</form>
									</div>
									<div id='departmentNoneRequiredPeriod' class="col-md-6 col-md-offset-3" style='display: none;'>
										<form method='GET' action='{{route("department.reestr.print_reestr")}}'>
											<div class='row'>
												<div class="col-md-12">
													<div class="form-group">
														<label>Выберите подразделение</label>
														<select class="form-control" name='department'>
															<option>Все подразделения</option>
															@foreach($departments as $department)
																<option value='{{$department->id}}'>{{$department->index_department}} {{$department->name_department}}</option>
															@endforeach
														</select>
													</div>
												</div>
												<div class="col-md-12">
													<label>Период с</label>
													<input class='datepicker form-control' type='text' name='date_begin' value="01.01.{{date('Y', time())}}"/>
													<label>по</label>
													<input class='datepicker form-control' type='text' name='date_end' value="{{date('d.m.Y', time())}}"/>
												</div>
											</div>
											<input name='real_name_table' value='' style='display: none;'/>
											<div class='row'>
												<div class="col-md-6">
													<button type="submit" class="btn btn-primary">Сформировать</button>
												</div>
												<div class="col-md-6">
													<button type="button" class="btn btn-secondary steps" first_step='#departmentNoneRequiredPeriod' second_step='#printList' style='float: right;'>Назад</button>
												</div>
											</div>
										</form>
									</div>
									<div id='counterpartiePeriod' class="col-md-12" style='display: none;'>
										<form method='GET' action='{{route("department.reestr.print_reestr")}}'>
											<div class='row'>
												<div class="col-md-12">
													<div class="form-group">
														<label>Выберите контрагента</label>
														<select class="form-control" name='counterpartie' required>
															<option></option>
															@foreach($counterparties as $counterpartie)
																<option value='{{$counterpartie->id}}'>{{$counterpartie->name}}</option>
															@endforeach
														</select>
													</div>
												</div>
												<div class="col-md-12">
													<label>Период с</label>
													<input class='datepicker form-control' type='text' name='date_begin' value="01.01.{{date('Y', time())}}"/>
													<label>по</label>
													<input class='datepicker form-control' type='text' name='date_end' value="{{date('d.m.Y', time())}}"/>
												</div>
											</div>
											<input name='real_name_table' value='' style='display: none;'/>
											<div class='row'>
												<div class="col-md-6">
													<button type="submit" class="btn btn-primary">Сформировать</button>
												</div>
												<div class="col-md-6">
													<button type="button" class="btn btn-secondary steps" first_step='#counterpartiePeriod' second_step='#printList' style='float: right;'>Назад</button>
												</div>
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
																<td><button type='button' class='btn btn-primary chose-counterpartie' type='button' id_counterpartie='{{$counterpartie->id}}'>Выбрать</button></td>
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
