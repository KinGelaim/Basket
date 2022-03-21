@extends('layouts.header')

@section('title')
	Печать Список исполненных договоров
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-12">
						<button class='btn btn-primary' id='createExcel' real_name_table='Список исполненных договоров'>Сформировать Excel</button>
					</div>
				</div>
				<div class='row' style='text-align: center;'>
					<div class="col-md-12">
						Список исполненных договоров
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
							<th colspan='2'>В проекте договора (контракта)</th>
							<th rowspan='2'>ГОЗ, межзаводские, экспорт, услуги и иные</th>
							<th rowspan='2'>Срок исполнения</th>
							<th rowspan='2'>Дата, № входящего (исходящего) проекта Договора</th>
							<th rowspan='2'>Дата вступления в силу (дата урегулирования разногасий)</th>
							<th rowspan='2'>Исполнение Договора/Контракта</th>
							<th rowspan='2'>Дата исполнения Дог./Контр.</th>
						</tr>
						<tr>
							<th>Предмет</th>
							<th>Сумма</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($result))
							<?php $count = 0; $all_amount = 0; ?>
							@foreach($result as $key=>$value)
								<tr>
									<td colspan='7' style='text-align: center;'>
										<b>{{$key}}</b>
									</td>
								</tr>
								@foreach($value as $contract)
									<tr>
										<td>{{$contract->number_contract}} от<br/>{{$contract->date_contract_on_first_reestr}}<br/><br/>{{$contract->executor_contract_reestr}}</td>
										<td>{{$contract->item_contract}}</td>
										<td>{{is_numeric($contract->amount_contract_reestr) ? number_format($contract->amount_contract_reestr, 2, '.', '&nbsp;') : (is_numeric($contract->amount_reestr) ? number_format($contract->amount_reestr, 2, '.', '&nbsp;') : $contract->amount_reestr)}}</td>
										<td>{{$contract->name_works_goz}}</td>
										<td>{{$contract->date_maturity_reestr}}</td>
										<td>Договор подписан - <b>{{strtotime($contract->date_signing_contract_reestr) > strtotime($contract->date_signing_contract_counterpartie_reestr) ? $contract->date_signing_contract_reestr : $contract->date_signing_contract_counterpartie_reestr}}<b/></td>
										<td>Дата вступления в силу - <b>{{$contract->date_entry_into_force_reestr}}</b><br/>Дата сдачи Договора на хранение - <b>{{$contract->date_save_contract_reestr}}</b></td>
										<td><b>Исполнено:</b> {{$contract->amount_invoices}}<br/><b>Оплата:</b> {{$contract->amount_payments + $contract->amount_prepayments}}<br/><b>Задолженность: </b>{{$contract->amount_invoices - ($contract->amount_payments + $contract->amount_prepayments) > 0 ? ($contract->amount_invoices - ($contract->amount_payments + $contract->amount_prepayments)) : 0}}</td>
										<td></td>
									</tr>
									<?php
										$count++;
										if (is_numeric($contract->amount_contract_reestr))
											$all_amount += $contract->amount_contract_reestr;
										else if (is_numeric($contract->amount_reestr))
											$all_amount += $contract->amount_reestr;
									?>
								@endforeach
							@endforeach
							<tr>
								<td style='text-align: right;'><b>Итого:</b></td>
								<td style='text-align: center;'><b>{{$count}}</b></td>
								<td style='text-align: center;'><b>{{number_format($all_amount, 2, '.', '&nbsp;')}}</b></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
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
