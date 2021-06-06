<div class='form-group'>
	<div id='printListPEO'>
		<div class='row'>
			<div class="col-md-12">
				<button type="button" class="btn btn-primary steps" style='white-space: normal;' first_step='#printListPEO' second_step='#printPEO'>Отчет ПЭО</button>
			</div>
		</div>
		<div class='row'>
			<div class="col-md-12">
				<button type="button" class="btn btn-primary steps" style='white-space: normal;' first_step='#printListPEO' second_step='#printNoExecutePEO'>Отчет ПЭО (в стадии выполнения)</button>
			</div>
		</div>
		<div class='row'>
			<div class="col-md-12">
				<button type="button" class="btn btn-primary steps" style='white-space: normal;' first_step='#printListPEO' second_step='#incomingPeriod'>Поступление за период</button>
			</div>
		</div>
		<div class='row'>
			<div class="col-md-12">
				<button type="button" class="btn btn-primary steps" style='white-space: normal;' first_step='#printListPEO' second_step='#completePeriodPEO'>Выполнение за период</button>
			</div>
		</div>
		<div class='row'>
			<div class="col-md-12">
				<button type="button" class="btn btn-primary steps" style='white-space: normal;' first_step='#printListPEO' second_step='#paymentPeriod'>Оплата за период</button>
			</div>
		</div>
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор')
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-second steps" style='white-space: normal;' first_step='#printListPEO' second_step='#backpackContracts'>Портфель контрактов</button>
					</div>
				</div>
			@endif
		@endif
	</div>
	<div id='printPEO' class="col-md-8 col-md-offset-2" style='display: none;'>
		<form method='GET' action='{{route("department.leadership.peo")}}'>
			<div class='row'>
				<div class="col-md-12">
					<div class="form-group">
						<label>Выберите контрагента</label>
						<select class="form-control" name='counterpartie'>
							<option value=''>Все контрагенты</option>
							@foreach($sip_counterparties as $counterpartie)
								<option>{{$counterpartie->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Выберите вид договора</label>
						<select class="form-control" name='view'>
							<option value=''>Все виды договоров</option>
							@foreach($viewContracts as $viewContract)
								<option>{{ $viewContract->name_view_contract }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Выберите год</label>
						<select class="form-control" name='year'>
							<option value=''>Все года</option>
							@foreach($years as $year)
								<option>{{$year->year_contract}}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
				<div class="col-md-6">
					<button type="button" class="btn btn-secondary steps" first_step='#printPEO' second_step='#printListPEO' style='float: right;'>Назад</button>
				</div>
			</div>
		</form>
	</div>
	<div id='printNoExecutePEO' class="col-md-8 col-md-offset-2" style='display: none;'>
		<form method='GET' action='{{route("department.leadership.peoNoExecute")}}'>
			<div class='row'>
				<div class="col-md-12">
					<div class="form-group">
						<label>Выберите контрагента</label>
						<select class="form-control" name='counterpartie'>
							<option value=''>Все контрагенты</option>
							@foreach($sip_counterparties as $counterpartie)
								<option>{{$counterpartie->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Выберите вид договора (несколько через CTRL)</label>
						<select class="form-control" name='view[]' multiple=true size='7'>
							<option value=''>Все виды договоров</option>
							@foreach($viewContracts as $viewContract)
								<option>{{ $viewContract->name_view_contract }}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<div class='row'>
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
				<div class="col-md-6">
					<button type="button" class="btn btn-secondary steps" first_step='#printNoExecutePEO' second_step='#printListPEO' style='float: right;'>Назад</button>
				</div>
			</div>
		</form>
	</div>
	<div id='incomingPeriod' class="col-md-12" style='display: none;'>
		<form method='POST' action='{{route("department.second.print_report")}}'>
			{{csrf_field()}}
			<div class='row'>
				<div class="col-md-12">
					<label>Поступление за период</label>
					<label>с</label>
					<input class='datepicker form-control' type='text' name='date_begin'/>
					<label>по</label>
					<input class='datepicker form-control' type='text' name='date_end'/>
				</div>
			</div>
			<input name='real_name_table' value='Поступление за период' style='display: none;'/>
			<div class='row'>
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
				<div class="col-md-6">
					<button type="button" class="btn btn-secondary steps" first_step='#incomingPeriod' second_step='#printListPEO' style='float: right;'>Назад</button>
				</div>
			</div>
		</form>
	</div>
	<div id='completePeriodPEO' class="col-md-12" style='display: none;'>
		<form method='POST' action='{{route("department.second.print_report")}}'>
			{{csrf_field()}}
			<div class='row'>
				<div class="col-md-12">
					<label>Вид отчёта</label>
					<select class='form-control' name='view_complete_report' required>
						<option>Общий</option>
						<option selected>Испытания</option>
						<option>Сборка</option>
						<option>Услуги</option>
					</select>
				</div>
			</div>
			@if(isset($sip_counterparties))
			<div class='row'>
				<div class="col-md-12">
					<label>Контрагент</label>
					<select class='form-control' name='counterpartie'>
						<option value=''>Все контрагенты</option>
						@foreach($sip_counterparties as $counter)
							<option value='{{$counter->id}}'>{{$counter->name}}</option>
						@endforeach
					</select>
				</div>
			</div>
			@endif
			<div class='row'>
				<div class="col-md-12">
					<label>Выполнение за период</label>
					<label>с</label>
					<input class='datepicker form-control' type='text' name='date_begin'/>
					<label>по</label>
					<input class='datepicker form-control' type='text' name='date_end'/>
				</div>
			</div>
			<input name='real_name_table' value='Выполнение за период' style='display: none;'/>
			<div class='row'>
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
				<div class="col-md-6">
					<button type="button" class="btn btn-secondary steps" first_step='#completePeriodPEO' second_step='#printListPEO' style='float: right;'>Назад</button>
				</div>
			</div>
		</form>
	</div>
	<div id='paymentPeriod' class="col-md-12" style='display: none;'>
		<form method='POST' action='{{route("department.second.print_report")}}'>
			{{csrf_field()}}
			<div class='row'>
				<div class="col-md-12">
					<label>Оплата за период</label>
					<label>с</label>
					<input class='datepicker form-control' type='text' name='date_begin'/>
					<label>по</label>
					<input class='datepicker form-control' type='text' name='date_end'/>
				</div>
			</div>
			<input name='real_name_table' value='Оплата за период' style='display: none;'/>
			<div class='row'>
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
				<div class="col-md-6">
					<button type="button" class="btn btn-secondary steps" first_step='#paymentPeriod' second_step='#printListPEO' style='float: right;'>Назад</button>
				</div>
			</div>
		</form>
	</div>
	<div id='backpackContracts' class="col-md-8 col-md-offset-2" style='display: none;'>
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор')
				<form method='GET' action='{{route("department.leadership.peoBackpack")}}'>
					<div class='row'>
						<div class="col-md-12">
							<div class="form-group">
								<label>Выберите контрагента</label>
								<select class="form-control" name='counterpartie'>
									<option value=''>Все контрагенты</option>
									@foreach($sip_counterparties as $counterpartie)
										<option>{{$counterpartie->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Выберите вид договора</label>
								<select class="form-control" name='view'>
									<option value=''>Все виды договоров</option>
									@foreach($viewContracts as $viewContract)
										<option>{{ $viewContract->name_view_contract }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Выберите год</label>
								<select class="form-control" name='year'>
									<option value=''>Все года</option>
									@foreach($years as $year)
										<option>{{$year->year_contract}}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							<button type="submit" class="btn btn-primary">Сформировать</button>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-secondary steps" first_step='#backpackContracts' second_step='#printListPEO' style='float: right;'>Назад</button>
						</div>
					</div>
				</form>
			@else
				<div class="alert alert-danger">
					Недостаточно прав для просмотра данного элемента!
				</div>
				<div class='row'>
					<div class="col-md-6">
						<button type="button" class="btn btn-secondary steps" first_step='#backpackContracts' second_step='#printListPEO' style='float: right;'>Назад</button>
					</div>
				</div>
			@endif
		@endif
	</div>
</div>