@extends('layouts.header')

@section('title')
	Печать Список заявок, зарегистрированных в Реестре договоров - проектов нет
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-12">
						<button class='btn btn-primary' id='createExcel' real_name_table='Список заявок, зарегистрированных в Реестре договоров - проектов нет'>Сформировать Excel</button>
					</div>
				</div>
				<div class='row' style='text-align: center;'>
					<div class="col-md-12">
						СПИСОК заявок, зарегистрированных в Реестре - есть проект договора (стадия согласования)
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
							<th>№ договора</th>
							<th>Контрагент</th>
							<th>Предмет</th>
							<th>Сумма</th>
							<th>Исполнитель</th>
							<th>Дата, № входящего (исходящего) проекта Договора</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($contracts))
							@foreach($contracts as $contract)
								<tr>
									<td>{{$contract->number_contract}} от {{$contract->date_registration_project_reestr}}</td>
									<td>{{$contract->counterpartie_name}}</td>
									<td>{{$contract->item_contract}}<br/>{{$contract->app_outgoing_number_reestr}}<br/>{{$contract->app_incoming_number_reestr}}</td>
									<td>{{$contract->amount_contract_reestr}}</td>
									<td>{{$contract->executor_contract_reestr}}</td>
									<td></td>
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
