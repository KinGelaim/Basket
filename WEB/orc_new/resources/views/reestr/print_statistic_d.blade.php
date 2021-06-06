@extends('layouts.header')

@section('title')
	Печать Итоги по действующим договорам
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-12">
						<button class='btn btn-primary' id='createExcel' real_name_table='Итоги по действующим договорам'>Сформировать Excel</button>
					</div>
				</div>
				<div class='row' style='text-align: center;'>
					<div class="col-md-12">
						Итоги по действующим договорам
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
							<th>Подразделение</th>
							<th>Закуп</th>
							<th>Сбыт</th>
							<th>Инвестиции</th>
							<th>Иное</th>
						</tr>
					</thead>
					<tbody>
						@foreach($result as $key=>$value)
							<tr>
								<td>{{$key}}</td>
								<?php
									$all_procurement_reestr = 0;
									$all_marketing_reestr = 0;
									$all_investments_reestr = 0;
									$all_other_reestr = 0;
									foreach($value as $contract){
										if($contract->procurement_reestr == 1){
											if(is_numeric($contract->amount_contract_reestr))
												$all_procurement_reestr += $contract->amount_contract_reestr;
											else if(is_numeric($contract->amount_reestr))
												$all_procurement_reestr += $contract->amount_reestr;
										}else if($contract->marketing_reestr == 1){
											if(is_numeric($contract->amount_contract_reestr))
												$all_marketing_reestr += $contract->amount_contract_reestr;
											else if(is_numeric($contract->amount_reestr))
												$all_marketing_reestr += $contract->amount_reestr;
										}else if($contract->investments_reestr == 1){
											if(is_numeric($contract->amount_contract_reestr))
												$all_investments_reestr += $contract->amount_contract_reestr;
											else if(is_numeric($contract->amount_reestr))
												$all_investments_reestr += $contract->amount_reestr;
										}else if($contract->other_reestr == 1){
											if(is_numeric($contract->amount_contract_reestr))
												$all_other_reestr += $contract->amount_contract_reestr;
											else if(is_numeric($contract->amount_reestr))
												$all_other_reestr += $contract->amount_reestr;
										}
									}
								?>
								<td>{{$all_procurement_reestr}}</td>
								<td>{{$all_marketing_reestr}}</td>
								<td>{{$all_investments_reestr}}</td>
								<td>{{$all_other_reestr}}</td>
							</tr>
						@endforeach
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
