<div class='form-group row'>
	<div class="col-md-12">
		<label>Выберите отчет:</label>
		<select class='form-control openDivSelect' required>
			<option></option>
			<option value='svPrintContract'>Сводный отчет по договорам</option>
			<option value='incomingPeriod'>Поступление за период</option>
			<option value='completePeriod'>Выполнение за период</option>
			<option value='completePeriodIsp'>Выполнение за период (вид испытания)</option>
			<!--<option value='completePeriodSb'>Выполнение за период (cборка)</option>
			<option value='completePeriodUs'>Выполнение за период (услуги)</option>-->
			<option value='paymentPeriod'>Оплата за период</option>
			<option value='counterpartieYearMini'>Предприятия за год (кратко)</option>
			<option value='counterpartieYear'>Предприятия за год</option>
			<option value='svPrintPayment'>Сводный отчет по оплате</option>
			<option value='completeContractPeriod'>Выполнение договоров</option>
			<option value='testPeriod'>Тестовый режим</option>
			<option value='ispForPeriod'>Испытано за период</option>
			<option value='nomenForPeriod'>Номенклатура за период</option>
		</select>
	</div>
	<div id='svPrintContract' class="col-md-12 closeDivSelect" style='display: none;'>
		<form method='POST' action='{{route("department.second.print_report")}}'>
			{{csrf_field()}}
			<div class='row'>
				<div class="col-md-12">
					<label>Выберите вид работ</label>
					<select class="form-control" name='view_work'>
						<option>Все виды работ</option>
						@if($viewContracts)
							@foreach($viewContracts as $viewContract)
								<option>{{ $viewContract->name_view_contract }}</option>
							@endforeach
						@endif
					</select>
				</div>
			</div>
			<div class='row'>
				<div class="col-md-12">
					<label>Введите год</label>
					<input class='form-control' type='text' name='year'/>
				</div>
			</div>
			<input name='real_name_table' value='Сводный отчет по договорам' style='display: none;'/>
			<div class='row'>
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
			</div>
		</form>
	</div>
	<div id='incomingPeriod' class="col-md-12 closeDivSelect" style='display: none;'>
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
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
			</div>
		</form>
	</div>
	<div id='completePeriod' class="col-md-12 closeDivSelect" style='display: none;'>
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
			@else
				@if(isset($counterparties))
				<div class='row'>
					<div class="col-md-12">
						<label>Контрагент</label>
						<select class='form-control' name='counterpartie'>
							<option value=''>Все контрагенты</option>
							@foreach($counterparties as $counter)
								<option value='{{$counter->id}}'>{{$counter->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				@endif
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
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
			</div>
		</form>
	</div>
	<div id='completePeriodIsp' class="col-md-12 closeDivSelect" style='display: none;'>
		<form method='POST' action='{{route("department.second.print_report")}}'>
			{{csrf_field()}}
			<div class='row'>
				<div class="col-md-12">
					<label>Выберите вид испытания</label>
					<select class="form-control" name='view_isp'>
						<option value='0'>Все виды испытаний</option>
						@if(isset($viewWorkElements))
							@if($viewWorkElements)
								@foreach($viewWorkElements as $viewElement)
									<option value='{{$viewElement->id}}'>{{ $viewElement->name_view_work_elements }}</option>
								@endforeach
							@endif
						@endif
					</select>
				</div>
			</div>
			<div class='row'>
				<div class="col-md-12">
					<label>Выполнение за период</label>
					<label>с</label>
					<input class='datepicker form-control' type='text' name='date_begin'/>
					<label>по</label>
					<input class='datepicker form-control' type='text' name='date_end'/>
				</div>
			</div>
			<input name='real_name_table' value='Выполнение за период (испытания)' style='display: none;'/>
			<div class='row'>
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
			</div>
		</form>
	</div>
	<div id='completePeriodSb' class="col-md-12 closeDivSelect" style='display: none;'>
		<form method='POST' action='{{route("department.second.print_report")}}'>
			{{csrf_field()}}
			<div class='row'>
				<div class="col-md-12">
					<label>Выполнение за период</label>
					<label>с</label>
					<input class='datepicker form-control' type='text' name='date_begin'/>
					<label>по</label>
					<input class='datepicker form-control' type='text' name='date_end'/>
				</div>
			</div>
			<input name='real_name_table' value='Выполнение за период (сборка)' style='display: none;'/>
			<div class='row'>
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
			</div>
		</form>
	</div>
	<div id='completePeriodUs' class="col-md-12 closeDivSelect" style='display: none;'>
		<form method='POST' action='{{route("department.second.print_report")}}'>
			{{csrf_field()}}
			<div class='row'>
				<div class="col-md-12">
					<label>Выполнение за период</label>
					<label>с</label>
					<input class='datepicker form-control' type='text' name='date_begin'/>
					<label>по</label>
					<input class='datepicker form-control' type='text' name='date_end'/>
				</div>
			</div>
			<input name='real_name_table' value='Выполнение за период (услуги)' style='display: none;'/>
			<div class='row'>
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
			</div>
		</form>
	</div>
	<div id='paymentPeriod' class="col-md-12 closeDivSelect" style='display: none;'>
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
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
			</div>
		</form>
	</div>
	<div id='counterpartieYearMini' class="col-md-12 closeDivSelect" style='display: none;'>
		<form method='POST' action='{{route("department.second.print_report")}}'>
			{{csrf_field()}}
			<div class='row'>
				<div class="col-md-12">
					<label>Введите год</label>
					<input class='form-control' type='text' name='year'/>
				</div>
			</div>
			<input name='real_name_table' value='Предприятия за год к' style='display: none;'/>
			<div class='row'>
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
			</div>
		</form>
	</div>
	<div id='counterpartieYear' class="col-md-12 closeDivSelect" style='display: none;'>
		<form method='POST' action='{{route("department.second.print_report")}}'>
			{{csrf_field()}}
			<div class='row'>
				<div class="col-md-12">
					<label>Введите год</label>
					<input class='form-control' type='text' name='year'/>
				</div>
			</div>
			<input name='real_name_table' value='Предприятия за год' style='display: none;'/>
			<div class='row'>
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
			</div>
		</form>
	</div>
	<div id='svPrintPayment' class="col-md-12 closeDivSelect" style='display: none;'>
		<form method='POST' action='{{route("department.second.print_report")}}'>
			{{csrf_field()}}
			<div class='row'>
				<div class="col-md-12">
					<label for='full_report'>Полный отчет</label>
					<input id='full_report' class='form-check-input' name='full_report' type="checkbox" />
				</div>
				<div class="col-md-12">
					<label>Выберите вид работ</label>
					<select class="form-control" name='view_work'>
						<option>Все виды работ</option>
						@if($viewContracts)
							@foreach($viewContracts as $viewContract)
								<option>{{ $viewContract->name_view_contract }}</option>
							@endforeach
						@endif
					</select>
				</div>
			</div>
			<input name='real_name_table' value='Сводный отчет по оплате' style='display: none;'/>
			<div class='row'>
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
			</div>
		</form>
	</div>
	<div id='completeContractPeriod' class="col-md-12 closeDivSelect" style='display: none;'>
		<form method='POST' action='{{route("department.second.print_report")}}'>
			{{csrf_field()}}
			<div class='row'>
				<div class="col-md-12">
					<label>Выберите вид работ</label>
					<select class="form-control" name='view_work'>
						<option>Все виды работ</option>
						@if($viewContracts)
							@foreach($viewContracts as $viewContract)
								<option>{{ $viewContract->name_view_contract }}</option>
							@endforeach
						@endif
					</select>
				</div>
			</div>
			<div class='row'>
				<div class="col-md-12">
					<label>Введите год</label>
					<input class='form-control' type='text' name='year'/>
				</div>
			</div>
			<input name='real_name_table' value='Выполнение работ по договорам' style='display: none;'/>
			<div class='row'>
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
			</div>
		</form>
	</div>
	<div id='testPeriod' class="col-md-12 closeDivSelect" style='display: none;'>
		<form method='POST' action='{{route("department.second.print_report")}}'>
			{{csrf_field()}}
			<div class='row'>
				<div class="col-md-12">
					<label>Период</label>
					<label>с</label>
					<input class='datepicker form-control' type='text' name='date_begin'/>
					<label>по</label>
					<input class='datepicker form-control' type='text' name='date_end'/>
				</div>
			</div>
			<input name='real_name_table' value='Тестовый режим' style='display: none;'/>
			<div class='row'>
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
			</div>
		</form>
	</div>
	<div id='ispForPeriod' class="col-md-12 closeDivSelect" style='display: none;'>
		<form method='POST' action='{{route("department.second.print_report")}}'>
			{{csrf_field()}}
			<div class='row'>
				<div class="col-md-12">
					<label>Период</label>
					<label>с</label>
					<input class='datepicker form-control' type='text' name='date_begin'/>
					<label>по</label>
					<input class='datepicker form-control' type='text' name='date_end'/>
				</div>
			</div>
			<input name='real_name_table' value='Испытано за период' style='display: none;'/>
			<div class='row'>
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
			</div>
		</form>
	</div>
	<div id='nomenForPeriod' class="col-md-12 closeDivSelect" style='display: none;'>
		<form method='POST' action='{{route("department.second.print_report")}}'>
			{{csrf_field()}}
			<div class='row'>
				<div class="col-md-12">
					<label>Период</label>
					<label>с</label>
					<input class='datepicker form-control' type='text' name='date_begin'/>
					<label>по</label>
					<input class='datepicker form-control' type='text' name='date_end'/>
				</div>
			</div>
			<input name='real_name_table' value='Номенклатура за период' style='display: none;'/>
			<div class='row'>
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
			</div>
		</form>
	</div>
</div>