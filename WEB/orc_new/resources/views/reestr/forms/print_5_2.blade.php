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
							<th>Номер Дог./Контр. по Реестру, номер Контрагента, отв. Исполнитель</th>
							<th>Контрагент</th>
							<th>Предмет Дог./Контр.</th>
							<th>Общая сумма Дог./Контр.</th>
							<th>Срок действия</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php $result_amount = 0; ?>
						@if(isset($contracts))
							@foreach($contracts as $contract)
								<tr>
									<td>{{$contract->number_contract}}<br/>{{$contract->number_counterpartie_contract_reestr}} {{$contract->date_contract_on_first_reestr ? 'от ' . $contract->date_contract_on_first_reestr : ''}}</td>
									<td>{{$contract->counterpartie_name}}</td>
									<td>{{$contract->item_contract}}</td>
									<td>{{$contract->amount_contract_reestr}}</td>
									<td>{{$contract->date_contract_reestr}}</td>
									<td></td>
								</tr>
								<?php if($contract->amount_contract_reestr != null) $result_amount += str_replace(' ','',str_replace(',','.',$contract->amount_contract_reestr)); ?>
							@endforeach
						@elseif(isset($result))
							@foreach($result as $key=>$value)
								<tr>
									<td colspan='8' style='text-align: center;'>{{$key}}</td>
									@foreach($value as $contract)
										<tr>
											<td>{{$contract->number_contract}}<br/>{{$contract->number_counterpartie_contract_reestr}} {{$contract->date_contract_on_first_reestr ? 'от ' . $contract->date_contract_on_first_reestr : ''}}</td>
											<td>{{$contract->counterpartie_name}}</td>
											<td>{{$contract->item_contract}}</td>
											<td>{{$contract->amount_contract_reestr}}</td>
											<td>{{$contract->date_contract_reestr}}</td>
											<td></td>
										</tr>
										<?php if($contract->amount_contract_reestr != null) $result_amount += str_replace(' ','',str_replace(',','.',$contract->amount_contract_reestr)); ?>
									@endforeach
								</tr>
							@endforeach
						@endif
						<tr>
							<td></td>
							<td></td>
							<td style='text-align: right;'><b>Итого</b></td>
							<td><b>{{str_replace('.',',',$result_amount)}}</b></td>
							<td></td>
							<td></td>
						</tr>
					</tbody>
				</table>
				<div class='row'>
					<div class="col-md-8 col-md-offset-2">
						@if(isset($contracts))
							Всего проектов за период: {{count($contracts)}}
						@elseif(isset($result))
							Всего зарегистрировано за период: {{$count_contracts}}
						@endif
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
