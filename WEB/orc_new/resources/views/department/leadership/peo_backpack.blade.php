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
							<button class='btn btn-primary' id='createExcel' real_name_table='ПЭО'>Сформировать Excel</button>
						</div>
					</div>
					<div class='row' style='text-align: center;'>
						<div class="col-md-12">
							<b>ПОРТФЕЛЬ КОНТРАКТОВ по ФИЛИАЛ "НТИИМ" ФКП "НИО "ГБИП России" на {{date('d.m.Y', time())}} г.</b>
						</div>
					</div>
					<table id='resultTable' class="table table-bordered tablePrint" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
						<thead style='text-align: center;'>
							<tr>
								<th>№ п/п</th>
								<th>Исполнитель ( указать название филиала), если исполнитель Головной офис указать- Геодезия</th>
								<th>Наимен. компании (Заказчик)</th>
								<th>№ договора</th>
								<th>Дата заключения контракта</th>
								<th>Дата окончания контракта</th>
								<th>Продукт/услуга поставки</th>
								<th>Общая стоимость по контракту, тыс. руб. </th>
								<th>Объем выполненых работ, тыс. руб.</th>
								<th>Остаток работ к выполнению, тыс. руб.</th>
								<th>Оплачено заказчиком, тыс. руб.</th>
								<th>Затраты по проекту, тыс.руб.</th>
								<th>Остаток финансирования, тыс. руб.</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$count = 1;
								$all_amount = 0;
								$all_invoices = 0;
							?>
							@foreach($contracts as $contract)
								<tr>
									<td>{{$count++}}</td>
									<td>
										<?php
											if ($contract->marketing_reestr == 1)
												echo 'Филиал "НТИИМ" ФКП "НИО "ГБИП России"';
											else if ($contract->procurement_reestr == 1)
												echo $contract->name_counterpartie_contract;
										?>
									</td>
									<td>
										<?php
											if ($contract->marketing_reestr == 1)
												echo $contract->name_counterpartie_contract;
											else if ($contract->procurement_reestr == 1)
												echo 'Филиал "НТИИМ" ФКП "НИО "ГБИП России"';
										?>
									</td>
									<td>
										<?php
											if ($contract->number_counterpartie_contract_reestr)
												echo $contract->number_counterpartie_contract_reestr . ' / ';
											echo $contract->number_contract;
										?>
									</td>
									<td>
										{{ $contract->date_entry_into_force_reestr ? $contract->date_entry_into_force_reestr : $contract->date_contract_on_first_reestr}}
									</td>
									<td>
										{{ $contract->date_e_contract_reestr ? $contract->date_e_contract_reestr : 'До полного исполнения контракта'}}
									</td>
									<td>
										{{ $contract->item_contract }}
									</td>
									<td>
										<!-- &nbsp; -->
										{{ is_numeric($contract->amount_reestr) ? number_format($contract->amount_reestr/1000, 2, ',', '') : '' }}
									</td>
									<td>
										{{ is_numeric($contract->amount_invoices) ? number_format($contract->amount_invoices/1000, 2, ',', '') : '' }}
									</td>
									<td>
										{{ is_numeric($contract->amount_reestr) && is_numeric($contract->amount_invoices) ? number_format(($contract->amount_reestr - $contract->amount_invoices) / 1000, 2, ',', '') : '' }}
									</td>
									<!-- Оплачено заказчиком -->
									<td>
										{{ number_format(($contract->amount_prepayments + $contract->amount_payments) / 1000, 2, ',', '') }}
									</td>
									<!-- Затраты по проекту -->
									<td>
									</td>
									<td>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				@endif
			</div>
		@else
			<div class="alert alert-danger">
				Необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection