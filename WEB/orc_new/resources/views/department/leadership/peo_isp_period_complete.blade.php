@extends('layouts.header')

@section('title')
	Печать
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="content">
				@if($contracts)
					<div class='row'>
						<div class="col-md-12">
							<button class='btn btn-primary' id='createExcel' real_name_table='Отчет по контрольным испытаниям'>Сформировать Excel</button>
						</div>
					</div>
					<div class='row' style='text-align: center;'>
						<div class="col-md-12">
							<b>Отчет по контрольным испытаиям с {{$period1}} по {{$period2}} на {{date('d.m.Y', time())}} г.</b>
						</div>
					</div>
					<table id='resultTable' class="table table-bordered tablePrint" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
						<thead style='text-align: center;'>
							<tr>
								<th>№ наряда</th>
								<th>Заказчик</th>
								<th>№ договора</th>
								<th>Наименование работ</th>
								<th>Выполнено, без НДС</th>
								<th>Кол-во испытанных изделий</th>
								<th>ГОЗ/экспорт</th>
							</tr>
						</thead>
						<tbody>
							@foreach($contracts as $contract)
								<tr>
									<td>{{$contract->number_duty}}</td>
									<td>{{$contract->full_name_counterpartie_contract}}</td>
									<td>{{$contract->number_contract}}</td>
									<td>{{$contract->item_contract}}</td>
									<td>{{is_numeric($contract->amount_reestr) ? number_format($contract->amount_reestr, 2, '.', '&nbsp;') : $contract->amount_reestr}}</td>
									<td>{{$contract->count_duty}}</td>
									<td>{{$contract->name_works_goz}}</td>
								</tr>
							@endforeach
							<tr>
								<td colspan='4' style='text-align: right;'><b>Итого:</b></td>
								<td><b>{{is_numeric($all_amount_reestr) ? number_format($all_amount_reestr, 2, '.', '&nbsp;') : $all_amount_reestr}}</b></td>
								<td><b></b></td>
								<td></td>
							</tr>
						</tbody>
					</table>
					<p>
						@foreach ($itogs as $key=>$value)
							{{$key}} (в руб.): <b>{{is_numeric($value) ? number_format($value, 2, '.', '&nbsp;') : $value}}</b><br/>
						@endforeach
					</p>
					<p style='text-align: center;'>
						Главный экономист, начальник ПЭО	Г.А. Мезенцева
					</p>
				@endif
			</div>
		@else
			<div class="alert alert-danger">
				Необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection