@extends('layouts.header')

@section('title')
	Печать
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Администрация' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел')
				<div class="content">
					<form method='POST' action="{{route('department.leadership.print_report')}}">
						{{csrf_field()}}
						<div class='row'>
							<div class='col-md-12'>
								<label>Фильтр договоров</label>
							</div>
							<div class='col-md-1'>
								<label>Контрагент:</label>
							</div>
							<div class='col-md-11'>
								<div class="form-group">
									<select class="form-control" name='counterpartie'>
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
							<div class='col-md-1'>
								<label>Вид договора:</label>
							</div>
							<div class='col-md-11'>
								<div class="form-group">
									<select class="form-control" name='view'>
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
							<div class='col-md-1'>
								<label>Год:</label>
							</div>
							<div class='col-md-11'>
								<div class="form-group">
									<select class="form-control" name='year'>
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
						</div>
						<div class='row'>
							<div class='col-md-12'>
								<label>Формирование отчета</label>
							</div>
						</div>
						<div id='divForColumn' class='row'>
							
						</div>
						<div class='row'>
							<div class='col-md-5'>
								<button id='btnAddColumn' class="btn btn-primary" type="button">Добавить поле</button>
							</div>
							<div class='col-md-5'>
								<button class="btn btn-primary" type="submit" style='float: right;'>Сформировать отчет</button>
							</div>
						</div>
					</form>
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
