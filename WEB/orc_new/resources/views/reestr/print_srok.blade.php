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
				<div class='row' style='text-align: right;'>
					<div class="col-md-12">
						по состоянию на {{date('d.m.Y', time())}} г.
					</div>
				</div>
				<table id='resultTable' class="table table-bordered tablePrint" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
					<thead style='text-align: center;'>
						<tr>
							<th>Номер контракта</th>
							<th>Предмет договора</th>
							<th>Срок исполнения обязательств</th>
							<th>Срок действия договора</th>
							<th>Договор действует по</th>
							<th>Сумма Дог./Контр.</th>
							<th>Описание суммы</th>
						</tr>
					</thead>
					<tbody>
						<?php $result_amount = 0; ?>
						@if(isset($result))
							@foreach($result as $key=>$value)
								<tr>
									<td colspan='7' style='text-align: center;'>{{$key}}</td>
									@foreach($value as $contract)
										<tr>
											<td>{{$contract->number_contract}}</td>
											<td>{{$contract->item_contract}}</td>
											<td>{{$contract->date_maturity_reestr}}</td>
											<td>{{$contract->date_contract_reestr}}</td>
											<td>{{$contract->date_e_contract_reestr}}</td>
											<td>{{$contract->amount_reestr}}</td>
											<td>{{$contract->amount_comment_reestr}}</td>
										</tr>
									@endforeach
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
