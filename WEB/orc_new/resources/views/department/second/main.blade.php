@extends('layouts.header')

@section('title')
	Второй отдел
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Второй отдел' OR Auth::User()->hasRole()->role == 'Администрация' OR Auth::User()->surname == 'Бастрыкова' OR Auth::User()->surname == 'Гуринова')
				<div class="content">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="sel1">Выберите год</label>
								<select class="form-control" id="sel1">
									<option value=''>Все года</option>
									@if($years)
										@foreach($years as $in_years)
											@if($year == $in_years->year_contract)
												<option selected>{{ $in_years->year_contract }}</option>
											@else
												<option>{{ $in_years->year_contract }}</option>
											@endif
										@endforeach
									@endif
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="sel2">Выберите вид договора</label>
								<select class="form-control" id="sel2">
									<option value=''>Все виды договоров</option>
									@if($viewContracts)
										@foreach($viewContracts as $in_viewContracts)
											@if($viewContract == $in_viewContracts->name_view_contract)
												<option selected>{{ $in_viewContracts->name_view_contract }}</option>
											@else
												<option>{{ $in_viewContracts->name_view_contract }}</option>
											@endif
										@endforeach
									@endif
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<button id='refreshContract' class="btn btn-primary" type="button" href="{{ route('department.second') }}" style="margin-top: 26px;">Обновить список</button>
						</div>
						<div class="col-md-4">
							<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#guids" style="margin-top: 26px; float: right; margin-left: 25px;">Справочники</button>
							<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#print" style="margin-top: 26px; float: right;">Отчеты</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<div class="row">
									<div class="col-md-12">
										<label for="sel3">Выберите контрагента</label>
										<select class="form-control" id="sel3">
											<option value=''>Все контрагенты</option>
											@if($counterparties)
												@foreach($counterparties as $in_counterparties)
													@if($counterpartie == $in_counterparties->name)
														<option selected>{{ $in_counterparties->name }}</option>
													@else
														<option>{{ $in_counterparties->name }}</option>
													@endif
												@endforeach
											@endif
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class='col-md-6'>
							<div class="form-group">
								@includeif('layouts.search', ['search_arr_value'=>['number_contract'=>'Номер договора','item_contract'=>'Наименование работ']])
							</div>
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
								<table class="table" style='margin: 0 auto; margin-bottom: 10px;'>
									<thead>
										<tr>
											<th class='cursorPointer btn-href' href='{{ route("department.second") }}?sorting=cast_number_pp&sort_p={{$re_sort}}&{{$link}}'>Номер договора<span>{{ $sort == 'cast_number_pp' ? $sort_span : ''}}</th>
											<th>Вид договора</th>
											<th>Наименование работ</th>
											<th>Контрагент</th>
										</tr>
									</thead>
									<tbody>
										@foreach($contracts as $contract)
											<tr class="rowsContract rowsContractClick cursorPointer" id_contact='{{$contract->id}}' href="{{ route('department.contract_second.show',$contract->id) }}">
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
													{{ $contract->name_counterpartie_contract }}
												</td>
											</tr>
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
								<h5 class="modal-title" id="printModalLabel">Формирование отчетов</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								@include('layouts.reports.main_panel', ['sip_counterparties'=>$counterparties])
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
							</div>
						</div>
					</div>
				</div>
				<!-- Модальное окно справочников -->
				<div class="modal fade" id="guids" tabindex="-1" role="dialog" aria-labelledby="guidsModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="guidsModalLabel">Справочники</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class='form-group row'>
									<div class="col-md-12" style='text-align: center;'>
										<button class="btn btn-primary btn-href" type="button" href="{{ route('element.main') }}">Справочник изделий</button>
										<button class="btn btn-primary btn-href" type="button" href="{{ route('view_work.element.main') }}">Виды испытаний</button>
										<button class="btn btn-primary btn-href" type="button" href="{{ route('second_department_unit.main') }}">Справочник ед. изм.</button>
									</div>
								</div>
								<div class='form-group row'>
									<div class="col-md-12" style='text-align: center;'>
										<button class="btn btn-primary btn-href" type="button" href="{{ route('second_department_caliber.main') }}">Типы</button>
										<button class="btn btn-primary btn-href" type="button" href="{{ route('second_department_name_element.main') }}">Наименования</button>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
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
