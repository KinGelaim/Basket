<div class='form-group row'>
	<div id='printListFin'>
		<div class="col-md-12">
			<button type="button" class="btn btn-primary steps" first_step='#printListFin' second_step='#printFin'>Счета и счета-фактуры</button>
		</div>
		<div class="col-md-12" style='margin-top: 5px;'>
			<button type="button" class="btn btn-primary steps" first_step='#printListFin' second_step='#counterpartiePeriodFin'>Задолженность по авансам и выполненным работам перед ФКП "НТИИМ"</button>
		</div>
	</div>
	<div id='printFin' class="col-md-8 col-md-offset-2" style='display: none;'>
		<form method='GET' action='{{route("department.leadership.invoice")}}'>
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
					<button type="button" class="btn btn-secondary steps" first_step='#printFin' second_step='#printListFin' style='float: right;'>Назад</button>
				</div>
			</div>
		</form>
	</div>
	<div id='counterpartiePeriodFin' class="col-md-12" style='display: none;'>
		<form method='GET' action="{{route('department.leadership.duty')}}">
			<div class='row'>
				<div class="col-md-12">
					<label for='full_report'>Полный отчет</label>
					<input id='full_report' class='form-check-input' name='full_report' type="checkbox" />
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Выберите контрагента</label>
						<select class="form-control" name='counterpartie'>
							<option value=''>Все контрагенты</option>
							@if(isset($sip_counterparties))
								@foreach($sip_counterparties as $counterpartie)
									<option>{{$counterpartie->name}}</option>
								@endforeach
							@else
								@foreach($counterparties as $counterpartie)
									<option>{{$counterpartie->name}}</option>
								@endforeach
							@endif
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
			<div class='row'>
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
				<div class="col-md-6">
					<button type="button" class="btn btn-secondary steps" first_step='#counterpartiePeriodFin' second_step='#printListFin' style='float: right;'>Назад</button>
				</div>
			</div>
		</form>
	</div>
</div>