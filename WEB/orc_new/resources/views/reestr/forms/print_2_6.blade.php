@extends('layouts.header')

@section('title')
	Печать {{$text}} {{$fz}}
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-12">
						<button class='btn btn-primary' id='createExcel' real_name_table='{{$text}} {{$fz}}'>Сформировать Excel</button>
					</div>
				</div>
				<div class='row' style='text-align: center;'>
					<div class="col-md-12">
						{{$text}} {{$fz}}
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
							<th>Номер Договора, дата заключения, ответственные: исполнитель подразделения и ОУД</th>
							<th>Наименование контрагента, принадлежность к СМСП</th>
							<th>Предмет Договора</th>
							<th>НЦМД, руб.</th>
							<th>Сумма Договора, руб.</th>
							<th>Экономия, руб.</th>
							<th>Срок действия Договора</th>
							<th>Срок поставки</th>
							<th>Порядок оплаты</th>
							<th>Обеспечение Договора</th>
							<th>Страна происхождения товара</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($contracts))
							<?php $count=1; $all_nmcd = 0; $all_amount = 0; $all_economy = 0; ?>
							@foreach($contracts as $contract)
								<tr>
									<td>{{$count++}}</td>
									<td>{{$contract->number_contract}}</td>
									<td>{{$contract->counterpartie_name}}</td>
									<td>{{$contract->item_contract}}</td>
									<td>{{$contract->nmcd_reestr}}</td>
									<td>{{$contract->amount_contract_reestr}}</td>
									<td>{{$contract->economy_reestr}}</td>
									<td>{{$contract->date_contract_reestr}}</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<?php
									if (is_numeric($contract->nmcd_reestr))
										$all_nmcd += $contract->nmcd_reestr;
									if (is_numeric($contract->amount_contract_reestr))
										$all_amount += $contract->amount_contract_reestr;
									if (is_numeric($contract->economy_reestr))
										$all_economy += $contract->economy_reestr;
								?>
							@endforeach
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td style='text-align: right;'><b>Итого:</b></td>
								<td style='text-align: center;'><b>{{$all_nmcd}}</b></td>
								<td style='text-align: center;'><b>{{$all_amount}}</b></td>
								<td style='text-align: center;'><b>{{$all_economy}}</b></td>
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
