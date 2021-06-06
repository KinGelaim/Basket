@extends('layouts.header')

@section('title')
	{{ $is_sip_contract == 1 ? 'Договоры СИП' : 'Остальные договоры' }}
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Отдел управления договорами' OR Auth::User()->hasRole()->role == 'Администрация')
				<div class="content">
					<form>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="sel1">Выберите год</label>
									<select class="form-control" id="sel1" name='year'>
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
							<!--<div class="col-md-3">
								<div class="form-group">
									<label for="sel2">Выберите вид работ</label>
									<select class="form-control" id="sel2">
										<option value="">Все виды работ</option>
										@if($viewWork)
											@if($viewWorks)
												@foreach($viewWorks as $in_viewWorks)
													@if($viewWork == $in_viewWorks->name_view_work)
														<option selected>{{ $in_viewWorks->name_view_work }}</option>
													@else
														<option>{{ $in_viewWorks->name_view_work }}</option>
													@endif
												@endforeach
											@endif
										@else
											@if($viewWorks)
												@foreach($viewWorks as $viewWork)
													<option>{{ $viewWork->name_view_work }}</option>
												@endforeach
											@endif
										@endif
									</select>
								</div>
							</div>-->
							<div class="col-md-3">
								<div class="form-group">
									<label for="sel2">Выберите подразделение</label>
									<select class="form-control" id="sel2" name='view'>
										<option value="">Все подразделения</option>
										@if($viewDepartment)
											@if($viewDepartments)
												@foreach($viewDepartments as $department)
													@if($viewDepartment == $department->index_department)
														<option value='{{$department->index_department}}' selected>{{ $department->name_department }}</option>
													@else
														<option value='{{$department->index_department}}'>{{ $department->name_department }}</option>
													@endif
												@endforeach
											@endif
										@else
											@if($viewDepartments)
												@foreach($viewDepartments as $viewDepartment)
													<option value='{{$viewDepartment->index_department}}'>{{ $viewDepartment->name_department }}</option>
												@endforeach
											@endif
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<button id='refreshContract' class="btn btn-primary" type="button" href="{{ $is_sip_contract == 1 ? route('department.ekonomic.sip') : route('department.ekonomic') }}" style="margin-top: 26px;">Обновить список</button>
							</div>
							<div class="col-md-4">
								@if($is_sip_contract == 1)
									@if(Auth::User()->hasRole()->role != 'Отдел управления договорами')
										<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#print" style="margin-top: 26px; float: right;">Отчеты</button>
										<button class='btn btn-primary btn-href' href='{{route("counterpartie.main")}}' type='button' style="margin-top: 26px; float: right; margin-right: 5px;">Контрагенты</button>
									@endif
								@else
									<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#print" style="margin-top: 26px; float: right;">Отчеты</button>
									<button class='btn btn-primary btn-href' href='{{route("counterpartie.main")}}' type='button' style="margin-top: 26px; float: right; margin-right: 5px;">Контрагенты</button>
								@endif
								@if($is_sip_contract == 0)
									@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
										<button id='addNewContract' class="btn btn-primary" type="button" href="{{ route('department.ekonomic.new_reestr') }}" style="margin-top: 26px; float: right; margin-right: 10px;">Добавить договор</button>
									@endif
								@endif
								@if($is_sip_contract == 1)
									@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
										<button class="btn btn-primary btn-href" type="button" href="{{ route('department.ekonomic.new_sip_reestr') }}" style="margin-top: 26px; float: right; margin-right: 10px;">Добавить договор СИП</button>
									@endif
									@if(Auth::User()->hasRole()->role != 'Отдел управления договорами')
										<button class="btn btn-primary btn-href" type="button" href="{{ route('department.reconciliation') }}" style="margin-top: 26px; float: right; margin-right: 10px;">На главную</button>
									@else
										<button class="btn btn-primary btn-href" type="button" href="{{ route('department.management.contracts') }}" style="margin-top: 26px; float: right; margin-right: 10px;">На главную</button>
									@endif
								@else
									<button class="btn btn-primary" type="button" onclick="history.back()" style="margin-top: 26px; float: right; margin-right: 10px;">На главную</button>
								@endif
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<label for="selSearch">Выберите поле для поиска</label>
									<select class="form-control" id="selSearch" name='search_name'>
										<option></option>
										<option value='number_contract' <?php if($search_name == 'number_contract') echo 'selected'; ?>>№ договора</option>
										<option value='name_view_contract' <?php if($search_name == 'name_view_contract') echo 'selected'; ?>>Вид договора</option>
										<option value='name_work_contract' <?php if($search_name == 'name_work_contract') echo 'selected'; ?>>Наименование работ</option>
										<option value='item_contract' <?php if($search_name == 'item_contract') echo 'selected'; ?>>Предмет договора</option>
										<option value='app_outgoing_number_reestr' <?php if($search_name == 'app_outgoing_number_reestr') echo 'selected'; ?>>№ исх. Заявки</option>
										<option value='number_counterpartie_contract_reestr' <?php if($search_name == 'number_counterpartie_contract_reestr') echo 'selected'; ?>>№ дог. контрагента</option>
										<option value='igk_reestr' <?php if($search_name == 'igk_reestr') echo 'selected'; ?>>ИГК</option>
										<option value='name_work_contract' <?php if($search_name == 'name_work_contract') echo 'selected'; ?>>Цель</option>
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
						</div>
						<div class="row">
							<div class="col-md-8">
								<div class="form-group">
									<label for="sel3">Выберите контрагента</label>
									<select class="form-control" id="sel3" name='counterpartie'>
										<option value="">Все контрагенты</option>
										@if($is_sip_contract != 1)
											@if($counterpartie)
												@if($counterparties)
													@foreach($counterparties as $in_counterparties)
														@if($counterpartie == $in_counterparties->name)
															<option selected>{{ $in_counterparties->name }}</option>
														@else
															<option>{{ $in_counterparties->name }}</option>
														@endif
													@endforeach
												@endif
											@else
												@if($counterparties)
													@foreach($counterparties as $in_counterparties)
														<option>{{ $in_counterparties->name }}</option>
													@endforeach
												@endif
											@endif
										@else
											@if($counterpartie)
												@if($sip_counterparties)
													@foreach($sip_counterparties as $in_counterparties)
														@if($counterpartie == $in_counterparties->name)
															<option selected>{{ $in_counterparties->name }}</option>
														@else
															<option>{{ $in_counterparties->name }}</option>
														@endif
													@endforeach
												@endif
											@else
												@if($sip_counterparties)
													@foreach($sip_counterparties as $in_counterparties)
														<option>{{ $in_counterparties->name }}</option>
													@endforeach
												@endif
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
						</div>
					</form>
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
											@if($is_sip_contract != 1)
												<th class='cursorPointer btn-href' href='{{ route("department.ekonomic") }}?sorting=cast_number_pp&sort_p={{$re_sort}}'>Номер договора<span>{{ $sort == 'cast_number_pp' ? $sort_span : ''}}</th>
											@else
												<th class='cursorPointer btn-href' href='{{ route("department.ekonomic.sip") }}?sorting=cast_number_pp&sort_p={{$re_sort}}'>Номер договора<span>{{ $sort == 'cast_number_pp' ? $sort_span : ''}}</th>
											@endif											
											<th>ГОЗ,<br/>межзаводские,<br/>экспорт</th>
											<th>№ исх. Заявки</th>
											<th>Вид договора</th>
											<th>Контрагент</th>
											<th>Предмет дог./контр.</th>
											<th>Сумма</th>
											<th style='width: 150px;'>Дата вступления Дог./Контр. в силу</th>
											@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' AND Auth::User()->hasRole()->role != 'Администрация')
												<th>Удаление</th>
											@endif
										</tr>
									</thead>
									<tbody>
										@foreach($contracts as $contract)
											@if(Auth::User()->hasRole()->role != 'Планово-экономический отдел' AND Auth::User()->hasRole()->role != 'Отдел управления договорами' AND Auth::User()->hasRole()->role != 'Администрация')
												<tr class='rowsContract rowsContractClickPEO cursorPointer' id_contact='{{$contract->id}}' href=""
															number_contract='{{ $contract->number_contract }}'
															contract_peo='{{ route("department.ekonomic.show_peo",$contract->id) }}'
															contract_new_reestr='{{ route("department.ekonomic.contract_new_reestr",$contract->id) }}'
															contract_reestr='{{ route("department.ekonomic.show_reestr",$contract->id) }}'
															contract_reconciliation='{{ route("department.reconciliation.show",$contract->id) }}'
															contract_new_peo='{{ route("department.peo.show_contract",$contract->id) }}' contextmenu="my-right-click-menu-{{$contract->id}}">
													<td>
														{{ $contract->number_contract }}
													</td>
													<td>
														{{ $contract->name_works_goz }}
													</td>
													<td>
														{{ $contract->app_outgoing_number_reestr }}
													</td>
													<td>
														{{ $contract->name_view_contract }}
													</td>
													<td>
														{{ $contract->name_counterpartie_contract }}
													</td>
													<td>
														{{ $contract->item_contract }}
													</td>
													<td style='width: 120px;'>
														{{ $contract->amount_reestr }}
													</td>
													<td>
														{{ $contract->renouncement_contract == 1 ? 'Отказ' : $contract->date_entry_into_force_reestr }}
													</td>
													@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' AND Auth::User()->hasRole()->role != 'Администрация' AND Auth::User()->hasRole()->role != 'Планово-экономический отдел')
														<td>
															<button type='button' class='btn btn-danger btn-href' type='button' href='{{route("department.ekonomic.delete",$contract->id)}}'>Удалить</button>
														</td>
													@endif
												</tr>
											@elseif(Auth::User()->hasRole()->role == 'Планово-экономический отдел')
												<tr class='rowsContract cursorPointer btn-href' href="{{route('department.reconciliation.show', $contract->id)}}" contextmenu="my-right-click-menu-{{$contract->id}}">
													<td>
														{{ $contract->number_contract }}
													</td>
													<td>
														{{ $contract->name_works_goz }}
													</td>
													<td>
														{{ $contract->app_outgoing_number_reestr }}
													</td>
													<td>
														{{ $contract->name_view_contract }}
													</td>
													<td>
														{{ $contract->name_counterpartie_contract }}
													</td>
													<td>
														{{ $contract->item_contract }}
													</td>
													<td style='width: 120px;'>
														{{ $contract->amount_reestr }}
													</td>
													<td>
														{{ $contract->renouncement_contract == 1 ? 'Отказ' : $contract->date_entry_into_force_reestr }}
													</td>
													@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' AND Auth::User()->hasRole()->role != 'Администрация' AND Auth::User()->hasRole()->role != 'Планово-экономический отдел')
														<td>
															<button type='button' class='btn btn-danger btn-href' type='button' href='{{route("department.ekonomic.delete",$contract->id)}}'>Удалить</button>
														</td>
													@endif
												</tr>
											@elseif(Auth::User()->hasRole()->role == 'Отдел управления договорами' OR Auth::User()->hasRole()->role == 'Администрация')
												<tr class='rowsContract cursorPointer btn-href' id_contact='{{$contract->id}}' href='{{ route("department.ekonomic.contract_new_reestr",$contract->id) }}' contextmenu="my-right-click-menu-{{$contract->id}}">
													<td>
														{{ $contract->number_contract }}
													</td>
													<td>
														{{ $contract->name_works_goz }}
													</td>
													<td>
														{{ $contract->app_outgoing_number_reestr }}
													</td>
													<td>
														{{ $contract->name_view_contract }}
													</td>
													<td>
														{{ $contract->name_counterpartie_contract }}
													</td>
													<td>
														{{ $contract->item_contract }}
													</td>
													<td style='width: 120px;'>
														{{ $contract->amount_reestr }}
													</td>
													<td>
														{{ $contract->renouncement_contract == 1 ? 'Отказ' : $contract->date_entry_into_force_reestr }}
													</td>
													@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' AND Auth::User()->hasRole()->role != 'Администрация' AND Auth::User()->hasRole()->role != 'Планово-экономический отдел')
														<td>
															<button type='button' class='btn btn-danger btn-href' type='button' href='{{route("department.ekonomic.delete",$contract->id)}}'>Удалить</button>
														</td>
													@endif
												</tr>
											@endif
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
						<!-- Модальное окно выбора договора -->
						<div class="modal fade" id="selectContract" tabindex="-1" role="dialog" aria-labelledby="selectContractModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="selectContractModalLabel">Договор</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<div class='row'>
											<div class="col-md-12">
												<button id='new_reestr' class="btn btn-primary btn-href" type="button" href="" style='width: 150px;'>Новый реестр</button>
											</div>
											<div class="col-md-12" style='margin-top: 5px;'>
												<button id='reestr' class="btn btn-primary btn-href" type="button" href="" style='width: 150px;'>Старый реестр</button>
											</div>
											<div class="col-md-12" style='margin-top: 5px;'>
												<button id='result' class="btn btn-primary btn-href" type="button" href="" style='width: 150px;'>Отчет ПЭО</button>
											</div>
											<div class="col-md-12" style='margin-top: 5px;'>
												<button id='sogl' class="btn btn-primary btn-href" type="button" href="" style='width: 150px;'>Карточка договора</button>
											</div>
											<div class="col-md-12" style='margin-top: 5px;'>
												<button id='modal_btn_peo' class="btn btn-primary btn-href" type="button" href="" style='width: 150px;'>Карточка ПЭО</button>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Модальное окно отчетов -->
					<div class="modal fade" id="print" tabindex="-1" role="dialog" aria-labelledby="printModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg" role="document" style='width: 80%;'>
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="printModalLabel">Формирование отчетов</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									@if(Auth::User()->hasRole()->role == 'Отдел управления договорами')
										@include('layouts.reports.oud')
									@else
										@include('layouts.reports.main_panel')
									@endif
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
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
																<td><button type='button' class='btn btn-primary chose-counterpartie' type='button' id_counterpartie='{{$counterpartie->name}}' id_select='sel3'>Выбрать</button></td>
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
