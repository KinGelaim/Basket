@extends('layouts.header')

@section('title')
	Руководство
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Администрация' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
				<div class="content">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="sel1">Выберите год</span></label>
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
								<label for="sel2">Выберите вид договора</span></label>
								<select class="form-control" id="sel2">
									<option value="">Все виды водговоров</option>
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
							<button id='refreshContract' class="btn btn-primary" type="button" href="{{ route('department.leadership') }}" style="margin-top: 26px;">Обновить список</button>
						</div>
						<div class="col-md-3">
							<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#printModal" style="margin-top: 26px; float: right;">Отчеты</button>
						</div>
						<!-- Модальное окно печати -->
						<div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-labelledby="printModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="printModalLabel">Печать отчетов</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<div class='row'>
											<div class='col-md-12'>
												<button type="button" class="btn btn-primary btn-href" href="{{route('department.leadership.create_report',$link)}}">Создать отчет</button>
											</div>
										</div>
										<div class='row'>
										</div>
										@include('layouts.reports.main_panel')
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="sel3">Выберите контрагента</span></label>
								<select class="form-control" id="sel3">
									<option value="">Все контрагенты</option>
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
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							Список договоров для печати
						</div>
					</div>
					<div class="row">
						<div id="allcontracts" class="col-md-12">
							@if($contracts)
								<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
									<thead>
										<tr>
											<th>Номер договора</th>
											<th>Вид договора</th>
											<th>Наименование работ</th>
											<th>Контрагент</th>
										</tr>
									</thead>
									<tbody>
										@foreach($contracts as $contract)
											<tr class="rowsContract cursorPointer btn-href" id_contact='{{$contract->id}}' href="{{route('tree_map.show_contract', $contract->id)}}">
												<td>
													{{ $contract->number_contract }}
												</td>
												<td>
													{{ $contract->name_view_contract }}
												</td>
												<td>
													{{ $contract->name_work_contract }}
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
