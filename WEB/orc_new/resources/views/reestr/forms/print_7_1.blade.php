@extends('layouts.header')

@section('title')
	Печать {{$text}}
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-12">
						<button class='btn btn-primary' id='createExcel' real_name_table='{{$text}}'>Сформировать Excel</button>
					</div>
				</div>
				<div class='row' style='text-align: center;'>
					<div class="col-md-12">
						{{$text}}
					</div>
				</div>
				@if(isset($department_name))
					<div class='row' style='text-align: center;'>
						<div class="col-md-12">
							{{$department_name}}
						</div>
					</div>
				@endif
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
							<th>Закупки по 223-ФЗ, 44-ФЗ (аукцион, запрос котировок, конкурс и др.)</th>
							<th>Предмет</th>
							<th>Сумма Дог./Контр.</th>
							<th>Конечный срок оплаты по Договору (Контракту)</th>
							<th>Дата вступления в силу</th>
						</tr>
					</thead>
					<tbody>
						<?php $result_amount = 0; ?>
						@if(isset($contracts))
							@foreach($contracts as $contract)
								<tr>
									<td>{{$contract->number_contract}}<br/>{{$contract->date_contract_on_first_reestr ? 'от ' . $contract->date_contract_on_first_reestr : ''}}</td>
									<td>{{$contract->counterpartie_name}}</td>
									<td></td>
									<td>{{$contract->item_contract}}</td>
									<td>{{is_numeric($contract->amount_contract_reestr) ? number_format($contract->amount_contract_reestr, 2, '.', '&nbsp;') : ($contract->amount_contract_reestr ? $contract->amount_contract_reestr : (is_numeric($contract->amount_reestr) ? number_format($contract->amount_reestr, 2, '.', '&nbsp;') : $contract->amount_reestr))}}</td>
									<td>{{$contract->end_term_repayment_reestr}}</td>
									<td>{{$contract->date_entry_into_force_reestr}}</td>
								</tr>
								<?php if($contract->amount_contract_reestr != null) $result_amount += str_replace(' ','',str_replace(',','.',$contract->amount_contract_reestr)); else if($contract->amount_reestr != null) $result_amount += str_replace(' ','',str_replace(',','.',$contract->amount_reestr))?>
							@endforeach
						@endif
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td style='text-align: right;'><b>Итого</b></td>
							<td><b>{{is_numeric($result_amount) ? number_format($result_amount, 2, '.', '&nbsp;') : str_replace('.',',',$result_amount)}}</b></td>
							<td></td>
							<td></td>
						</tr>
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
