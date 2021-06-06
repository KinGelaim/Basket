@extends('layouts.header')

@section('title')
	Печать Договора/Контракты по подразделению
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-12">
						<button class='btn btn-primary' id='createExcel' real_name_table='Договора/Контракты по подразделению'>Сформировать Excel</button>
					</div>
				</div>
				<div class='row' style='text-align: center;'>
					<div class="col-md-12">
						Договора/Контракты по подразделению
					</div>
				</div>
				<div class='row' style='text-align: center;'>
					<div class="col-md-12">
						за период: {{$period1}} г. - {{$period2}} г.
					</div>
				</div>
				<div class='row' style='text-align: right;'>
					<div class="col-md-12">
						по состоянию на {{date('d.m.Y', time())}} г.
					</div>
				</div>
				<table id='resultTable' class="table table-bordered tablePrint" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
					<thead style='text-align: center;'>
						<tr>
							<th>Номер договора</th>
							<th>Вступил в силу</th>
							<th>Сумма</th>
							<th>Контрагент</th>
							<th>Закуп</th>
							<th>Сбыт</th>
							<th>Инвестирование</th>
							<th>Иное</th>
							<th>Предмет</th>
							<th>Архив</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($contracts))
							@foreach($contracts as $contract)
								<tr>
									<td>{{$contract->number_contract}}</td>
									<td>{{$contract->renouncement_contract ? 'Отказ' : $contract->date_entry_into_force_reestr}}</td>
									<td>{{$contract->amount_contract_reestr}}</td>
									<td>{{$contract->counterpartie_name}}</td>
									<td>{{$contract->procurement_reestr==1 ? 'Закуп' : ''}}</td>
									<td>{{$contract->marketing_reestr==1 ? 'Сбыт' : ''}}</td>
									<td>{{$contract->investments_reestr==1 ? 'Инвестирование' : ''}}</td>
									<td>{{$contract->other_reestr==1 ? 'Иное' : ''}}</td>
									<td>{{$contract->item_contract}}</td>
									<td>{{$contract->archive_contract==1 ? 'Архив' : ''}}</td>
								</tr>
							@endforeach
						@endif
					</tbody>
				</table>
				<div class='row'>
					<div class="col-md-8 col-md-offset-2">
						Начальник отдела управления договорами<span style='float: right;'>{{$lider}}</span>
					</div>
				</div>
			</div>
		@else
			<div class="alert alert-danger">
				Для просмотра данной страницы необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection
