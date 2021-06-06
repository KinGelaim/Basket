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
							<th>Предмет договора</th>
							<th>Примечание</th>
							<th>НЦМД, руб.</th>
							<th>Цена договора, руб.</th>
							<th>Договор, контрагент</th>
							<th>Экономия, руб.</th>
							<th>Размещение информации о договоре</th>
							<th>Размещение информации об исполнении договора</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($contracts))
							<?php $count=1; ?>
							@foreach($contracts as $contract)
								<tr>
									<td>{{$count++}}</td>
									<td>{{$contract->item_contract}}</td>
									<td>{{$contract->FIO}}</td>
									<td>{{$contract->nmcd_reestr}}</td>
									<td>{{$contract->amount_contract_reestr}}</td>
									<td>{{$contract->number_contract}}<br/>{{$contract->counterpartie_name}}</td>
									<td>{{$contract->economy_reestr}}</td>
									<td></td>
									<td></td>
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
