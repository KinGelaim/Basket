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
						Список заявок, зарегистрированных в Реестре договоров - проектов нет
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
							<th rowspan='2'>№ договора</th>
							<th rowspan='2'>Предмет, заявка</th>
							<th colspan='2'>В реестре</th>
							<th rowspan='2'>Примечание</th>
							<th rowspan='2'>Исполнитель</th>
						</tr>
						<tr>
							<th>Сумма</th>
							<th>Срок испытаний, сборки</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($result))
							@foreach($result as $key=>$value)
								<tr>
									<td colspan='6' style='text-align: center;'>
										<b>{{$key}}</b>
									</td>
								</tr>
								@foreach($value as $contract)
									<tr>
										<td>{{$contract->number_contract}} от<br/>{{$contract->date_contract_on_first_reestr}}</td>
										<td>{{$contract->item_contract}}<br/>{{$contract->app_outgoing_number_reestr}}<br/>{{$contract->app_incoming_number_reestr}}</td>
										<td>{{is_numeric($contract->amount_contract_reestr) ? number_format($contract->amount_contract_reestr, 2, '.', '&nbsp;') : (is_numeric($contract->amount_reestr) ? number_format($contract->amount_reestr, 2, '.', '&nbsp;') : $contract->amount_reestr)}}</td>
										<td>{{$contract->date_maturity_reestr}}</td>
										<td></td>
										<td>{{$contract->executor_contract_reestr}}</td>
									</tr>
								@endforeach
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
