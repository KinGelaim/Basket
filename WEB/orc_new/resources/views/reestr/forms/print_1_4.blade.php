@extends('layouts.header')

@section('title')
	Печать Банковские гарантии на сбыт на {{date('d.m.Y', time())}} г.
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-12">
						<button class='btn btn-primary' id='createExcel' real_name_table='Банковские гарантии на сбыт на {{date("d.m.Y", time())}} г.'>Сформировать Excel</button>
					</div>
				</div>
				<div class='row' style='text-align: center;'>
					<div class="col-md-12">
						Банковские гарантии на сбыт на {{date('d.m.Y', time())}} г.
					</div>
				</div>
				<div class='row' style='text-align: center;'>
					<div class="col-md-12">
						за период: {{$period1}} г. - {{$period2}} г.
					</div>
				</div>
				<table id='resultTable' class="table table-bordered tablePrint" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
					<thead style='text-align: center;'>
						<tr>
							<th>№ п/п</th>
							<th>№ Дог./Контр. ФКП "НТИИМ"</th>
							<th>Сумма гарантии</th>
							<th>Срок гарантии</th>
							<th>Контрагент (сокращ. наимен.)</th>
							<th>Срок действия Дог./Контр.</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($contracts))
							<?php $k=1; $all_amount_bank = 0; ?>
							@foreach($contracts as $contract)
								<tr>
									<td>{{$k++}}</td>
									<td>{{$contract->number_contract}}</td>
									<td>{{$contract->amount_bank_reestr}}</td>
									<td>{{$contract->date_bank_reestr ? 'До ' . $contract->date_bank_reestr : ''}}</td>
									<td>{{$contract->counterpartie_name}}</td>
									<td>{{$contract->date_contract_reestr}}</td>
								</tr>
								<?php
									if (is_numeric($contract->amount_bank_reestr))
										$all_amount_bank += $contract->amount_bank_reestr;
								?>
							@endforeach
							<tr>
								<td></td>
								<td style='text-align: right;'><b>Итого:</b></td>
								<td style='text-align: center;'><b>{{$all_amount_bank}}</b></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						@endif
					</tbody>
				</table>
				<div class='row'>
					<div class="col-md-8 col-md-offset-2">
						
					</div>
				</div>
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
