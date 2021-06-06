@extends('layouts.header')

@section('title')
	Печать Статистический отчет по закупкам (по сумме Договоров/Контрактов)
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-12">
						<button class='btn btn-primary' id='createExcel' real_name_table='Статистический отчет по закупкам (по сумме Договоров/Контрактов)'>Сформировать Excel</button>
					</div>
				</div>
				<div class='row' style='text-align: center;'>
					<div class="col-md-12">
						Статистический отчет по закупкам (по сумме Договоров/Контрактов)
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
							<th>Индекс</th>
							<th>Наименование</th>
							<th>До 100 т.р.</th>
							<th>100-500 т.р.</th>
							<th>500 т. - 1 млн.р.</th>
							<th>Свыше 1 млн.р.</th>
							<th>Без конкрет. суммы</th>
							<th>Всего</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($departments))
							<?php $all_count_100=0; $all_count_300=0; $all_count_700=0; $all_count_lyam=0; $all_count_null=0; $all_all_count=0; ?>
							@foreach($departments as $department)
								<tr>
									<td>{{$department->index_department}}</td>
									<td>{{$department->name_department}}</td>
									<td>{{$department->count_100}}</td>
									<td>{{$department->count_300}}</td>
									<td>{{$department->count_700}}</td>
									<td>{{$department->count_lyam}}</td>
									<td>{{$department->count_null}}</td>
									<td>{{$department->all_count}}</td>
								</tr>
								<?php $all_count_100+=$department->count_100; $all_count_300+=$department->count_300; $all_count_700+=$department->count_700; $all_count_lyam+=$department->count_lyam; $all_count_null+=$department->count_null; $all_all_count+=$department->all_count; ?>
							@endforeach
							<tr>
								<td></td>
								<td></td>
								<td><b>{{$all_count_100}}</b></td>
								<td><b>{{$all_count_300}}</b></td>
								<td><b>{{$all_count_700}}</b></td>
								<td><b>{{$all_count_lyam}}</b></td>
								<td><b>{{$all_count_null}}</b></td>
								<td><b>{{$all_all_count}}</b></td>
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
