@extends('layouts.header')

@section('title')
	Договоры СИП
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Десятый отдел' OR Auth::User()->hasRole()->role == 'Администрация' OR Auth::User()->surname == 'Бастрыкова' OR Auth::User()->surname == 'Гуринова')
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
							<?php $pr_id_department_for_curators = null; ?>
							<div class="col-md-3">
								<div class="form-group">
									<label for="sel2">Выберите подразделение</label>
									<select class="form-control select_for_department" id="sel2" name='view'>
										<option value="">Все подразделения</option>
										@if($viewDepartment)
											@if($viewDepartments)
												@foreach($viewDepartments as $department)
													@if($viewDepartment == $department->index_department)
														<option value='{{$department->index_department}}' id_department='{{$department->id}}' selected>{{ $department->name_department }}</option>
														<?php $pr_id_department_for_curators = $department->id; ?>
													@else
														<option value='{{$department->index_department}}' id_department='{{$department->id}}'>{{ $department->name_department }}</option>
													@endif
												@endforeach
											@endif
										@else
											@if($viewDepartments)
												@foreach($viewDepartments as $viewDepartment)
													<option value='{{$viewDepartment->index_department}}' id_department='{{$viewDepartment->id}}'>{{ $viewDepartment->name_department }}</option>
												@endforeach
											@endif
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<button id='refreshContract' class="btn btn-primary" type="submit" style="margin-top: 26px;">Обновить список</button>
							</div>
							<div class="col-md-4">
								<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#print" style="margin-top: 26px; float: right;">Отчеты</button>
								<button class='btn btn-primary btn-href' href='{{route("counterpartie.main")}}' type='button' style="margin-top: 26px; float: right; margin-right: 5px;">Контрагенты</button>
								<button class="btn btn-primary btn-href" type="button" href="{{ route('department.peo.new_contract') }}" style="margin-top: 26px; float: right; margin-right: 10px;">Создать договор</button>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="sel2">Выберите вид работ</label>
									<select class="form-control" id="sel2" name='view_work'>
										<option value="">Все виды работ</option>
										@if($view_work)
											@if($viewContracts)
												@foreach($viewContracts as $in_viewWorks)
													@if($view_work == $in_viewWorks->id)
														<option value='{{$in_viewWorks->id}}' selected>{{ $in_viewWorks->name_view_contract }}</option>
													@else
														<option value='{{$in_viewWorks->id}}'>{{ $in_viewWorks->name_view_contract }}</option>
													@endif
												@endforeach
											@endif
										@else
											@if($viewContracts)
												@foreach($viewContracts as $in_viewWorks)
													<option value='{{$in_viewWorks->id}}'>{{ $in_viewWorks->name_view_contract }}</option>
												@endforeach
											@endif
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="sel2">Ответственный исполнитель</label>
									<select class="form-control select_curator" id="sel2" name='curator'>
										<option value="">Все исполнители</option>
										@if($curator)
											@if($curators)
												@foreach($curators as $in_curators)
													@if($curator == $in_curators->id)
														@if($pr_id_department_for_curators != null)
															@if($pr_id_department_for_curators == $in_curators->id_department)
																<option value='{{ $in_curators->id }}' id_department='{{$in_curators->id_department}}' selected>{{ $in_curators->FIO }}</option>
															@else
																<option value='{{ $in_curators->id }}' id_department='{{$in_curators->id_department}}' selected style='display: none;'>{{ $in_curators->FIO }}</option>
															@endif
														@else
															<option value='{{ $in_curators->id }}' id_department='{{$in_curators->id_department}}' selected>{{ $in_curators->FIO }}</option>
														@endif
													@else
														@if($pr_id_department_for_curators != null)
															@if($pr_id_department_for_curators == $in_curators->id_department)
																<option value='{{ $in_curators->id }}' id_department='{{$in_curators->id_department}}'>{{ $in_curators->FIO }}</option>
															@else
																<option value='{{ $in_curators->id }}' id_department='{{$in_curators->id_department}}' style='display: none;'>{{ $in_curators->FIO }}</option>
															@endif
														@else
															<option value='{{ $in_curators->id }}' id_department='{{$in_curators->id_department}}'>{{ $in_curators->FIO }}</option>
														@endif
													@endif
												@endforeach
											@endif
										@else
											@if($curators)
												@foreach($curators as $in_curators)
													@if($pr_id_department_for_curators != null)
														@if($pr_id_department_for_curators == $in_curators->id_department)
															<option value='{{ $in_curators->id }}' id_department='{{$in_curators->id_department}}'>{{ $in_curators->FIO }}</option>
														@else
															<option value='{{ $in_curators->id }}' id_department='{{$in_curators->id_department}}' style='display: none;'>{{ $in_curators->FIO }}</option>
														@endif
													@else
														<option value='{{ $in_curators->id }}' id_department='{{$in_curators->id_department}}'>{{ $in_curators->FIO }}</option>
													@endif
												@endforeach
											@endif
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group">
									<label for="sel3">Выберите контрагента</label>
									<select class="form-control" id="sel3" name='counterpartie'>
										<option value="">Все контрагенты</option>
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
									</select>
								</div>
							</div>
							<div class="col-md-1">
								<div class="form-group">
									<button type='button' data-toggle="modal" data-target="#chose_counterpartie" class="btn btn-primary" style='margin-top: 27px;'>Выбрать</button>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2" style='display: none;'>
								<div class="form-group">
									<label for="selSearch">Выберите поле для поиска</label>
									<select class="form-control" id="selSearch" name='search_name'>
										<option value='number_contract' selected>№ договора</option>
										<!--<option value='name_view_contract' <?php if($search_name == 'name_view_contract') echo 'selected'; ?>>Вид договора</option>
										<option value='name_work_contract' <?php if($search_name == 'name_work_contract') echo 'selected'; ?>>Наименование работ</option>
										<option value='item_contract' <?php if($search_name == 'item_contract') echo 'selected'; ?>>Предмет договора</option>
										<option value='app_outgoing_number_reestr' <?php if($search_name == 'app_outgoing_number_reestr') echo 'selected'; ?>>№ исх. Заявки</option>-->
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<label >Поиск по № договора</label>
								<input class='form-control' type='text' value='{{$search_value}}' name='search_value'/>
							</div>
							<div class="col-md-1">
								<button class="btn btn-primary" type="submit" href="" style="margin-top: 26px;">Поиск</button>
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
											<th>Контрагент</th>
											<th class='cursorPointer btn-href' href='{{ route("department.peo") }}?{{$link}}&sorting=cast_number_pp&sort_p={{$re_sort}}'>Номер договора<span>{{ $sort == 'cast_number_pp' ? $sort_span : ''}}</th>										
											<th>№ исх. Заявки</th>
											<th>Вид работ</th>
											<th>Наименование работ</th>
											<th>Ответственный исполнитель</th>
											<th>Начальная сумма с НДС, руб.</th>
											@if(Auth::User()->hasRole()->role == 'Администратор')
												<th>Удаление</th>
											@endif
										</tr>
									</thead>
									<tbody>
										@foreach($contracts as $contract)
											@if(Auth::User()->hasRole()->role == 'Администратор')
												<tr class='rowsContract rowsContractClickPEO cursorPointer' id_contact='{{$contract->id}}' href=""
															number_contract='{{ $contract->number_contract }}'
															contract_peo='{{ route("department.ekonomic.show_peo",$contract->id) }}'
															contract_new_reestr='{{ route("department.ekonomic.contract_new_reestr",$contract->id) }}'
															contract_reestr='{{ route("department.ekonomic.show_reestr",$contract->id) }}'
															contract_reconciliation='{{ route("department.reconciliation.show",$contract->id) }}'
															contract_new_peo='{{ route("department.peo.show_contract",$contract->id) }}'>
													<td>
														{{ $contract->name_counterpartie_contract }}
													</td>
													<td>
														{{ $contract->number_contract }}
													</td>
													<td>
														{{ $contract->app_outgoing_number_reestr }}
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
													<td style='width: 120px;'>
														{{ $contract->amount_reestr }}
													</td>
													@if(Auth::User()->hasRole()->role == 'Администратор')
														<td>
															<button type='button' class='btn btn-danger btn-href' type='button' href='{{route("department.ekonomic.delete",$contract->id)}}'>Удалить</button>
														</td>
													@endif
												</tr>
											@else
												<tr class='rowsContract cursorPointer btn-href' href='{{ route("department.peo.show_contract",$contract->id) }}'>
													<td>
														{{ $contract->name_counterpartie_contract }}
													</td>
													<td>
														{{ $contract->number_contract }}
													</td>
													<td>
														{{ $contract->app_outgoing_number_reestr }}
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
													<td style='width: 120px;'>
														{{ $contract->amount_reestr }}
													</td>
													@if(Auth::User()->hasRole()->role == 'Администратор')
														<td>
															<button type='button' class='btn btn-danger btn-href' type='button' href='{{route("department.ekonomic.delete",$contract->id)}}'>Удалить</button>
														</td>
													@endif
												</tr>

											@endif
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
											<li class="page-item active"><a class="page-link" href="?page={{$i}}{{$link}}">{{$i}}</a></li>
										@else
											<li class="page-item"><a class="page-link" href="?page={{$i}}{{$link}}">{{$i}}</a></li>
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
									@include('layouts.reports.main_panel')
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
