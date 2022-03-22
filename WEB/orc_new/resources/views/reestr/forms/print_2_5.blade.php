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
							<th rowspan=2>№</th>
							<th rowspan=2>Номер Договора, дата заключения, ответственные: исполнитель подразделения и ОУД</th>
							<th rowspan=2>Наименование контрагента</th>
							<th rowspan=2>Предмет Договора (Контракта)</th>
							<th rowspan=2>Сумма Договора (Контракта), руб.</th>
							<th colspan=2>Поставка ТРУ</th>
							<th rowspan=2>Срок действия Договора</th>
							<th rowspan=2>Просрочка поставки, дни</th>
							<th colspan=2>Оплата</th>
							<th rowspan=2>Просрочка оплаты, дни</th>
						</tr>
						<tr>
							<th>Дата</th>
							<th>Сумма</th>
							<th>Дата</th>
							<th>Сумма</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($contracts))
							<?php $count=1; $all_amount = 0; ?>
							@foreach($contracts as $contract)
								<tr>
									<td>{{$count++}}</td>
									<td>{{$contract->number_contract}}</td>
									<td>{{$contract->counterpartie_name}}</td>
									<td>{{$contract->item_contract}}</td>
									<td>{{$contract->amount_contract_reestr}}</td>
									<td></td>
									<td></td>
									<td>{{$contract->date_contract_reestr}}</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<?php
									if (is_numeric($contract->amount_contract_reestr))
										$all_amount += $contract->amount_contract_reestr;
								?>
							@endforeach
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td style='text-align: right;'><b>Итого:</b></td>
								<td style='text-align: center;'><b>{{$all_amount}}</b></td>
								<td></td>
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
