@extends('layouts.header')

@section('title')
	Печать Сводная таблица Поставщиков по инвестициям
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-12">
						<button class='btn btn-primary' id='createExcel' real_name_table='Сводная таблица Поставщиков по инвестициям'>Сформировать Excel</button>
					</div>
				</div>
				<div class='row' style='text-align: center;'>
					<div class="col-md-12">
						Сводная таблица Поставщиков по инвестициям
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
							<th>№</th>
							<th>Предмет, Исолнитель ОУД</th>
							<th>Кол-во, компл.</th>
							<th>Ответственные за ТЗ, расчет НМЦК</th>
							<th>№ Государственного контракта, наш номер, дата, НЦМК/сумма экономия, аванс</th>
							<th>Банковская гарантия</th>
							<th>Доп. соглашение, номер, дата</th>
							<th>Поставщик, город, контакты, e-mail, номер телефона</th>
							<th>Исполнение: номер тов. накл., Акта приема-передачи</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($contracts))
							<?php $k=1; ?>
							@foreach($contracts as $contract)
								<tr>
									<td>{{$k++}}</td>
									<td>{{$contract->item_contract}}<br/><br/>{{$contract->executor}}</td>
									<td></td>
									<td></td>
									<td>{{$contract->number_contract}}<br/>{{$contract->number_counterpartie_contract_reestr}} {{$contract->date_contract_on_first_reestr ? 'от ' . $contract->date_contract_on_first_reestr : ''}}<br/>{{$contract->economy_reestr ? 'Экономия ' . $contract->economy_reestr : ''}}</td>
									<td>{{$contract->bank_reestr}} {{$contract->amount_bank_reestr}}</td>
									<td>
										@foreach($contract->protocols as $protocol)
											{{$protocol->name_protocol}} от {{$protocol->date_on_first_protocol}}<br/>
										@endforeach
										<br/>
										@foreach($contract->add_agreements as $add_agreement)
											{{$add_agreement->name_protocol}} от {{$add_agreement->date_on_first_protocol}}<br/>
										@endforeach
									</td>
									<td>{{$contract->counterpartie_name}}</td>
									<td></td>
								</tr>
							@endforeach
						@endif
					</tbody>
				</table>
			</div>
		@else
			<div class="alert alert-danger">
				Для просмотра данной страницы необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection
