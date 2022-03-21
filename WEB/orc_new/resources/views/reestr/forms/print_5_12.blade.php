@extends('layouts.header')

@section('title')
	Печать Справка по Договорам (Контрактам) ПЭО по ГОЗ, межзаводские, экспорт, иные
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-12">
						<button class='btn btn-primary' id='createExcel' real_name_table='Справка по Договорам (Контрактам) ПЭО по ГОЗ, межзаводские, экспорт, иные'>Сформировать Excel</button>
					</div>
				</div>
				<div class='row' style='text-align: center;'>
					<div class="col-md-12">
						Справка по Договорам (Контрактам) ПЭО по ГОЗ, межзаводские, экспорт, иные
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
							<th>Номер Договора (Контракта), дата, Исполнитель</th>
							<th>Контрагент</th>
							<th>ГОЗ, межзаводские, экспорт, услуги и иные</th>
							<th>Предмет</th>
							<th>Сумма</th>
							<th>Вступил в силу</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($contracts))
							@foreach($contracts as $contract)
								<tr>
									<td>{{$contract->number_contract}}<br/>{{$contract->date_contract_on_first_reestr ? 'от ' . $contract->date_contract_on_first_reestr : ''}}<br/>{{$contract->executor_contract_reestr}}</td>
									<td>{{$contract->counterpartie_name}}</td>
									<td>{{$contract->name_works_goz}}</td>
									<td>{{$contract->item_contract}}</td>
									<td>{{$contract->amount_contract_reestr}}</td>
									<td>{{$contract->date_entry_into_force_reestr}}</td>
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
