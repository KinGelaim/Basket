@extends('layouts.header')

@section('title')
	Печать Статистический отчет по количеству Договоров/Контрактов
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-12">
						<button class='btn btn-primary' id='createExcel' real_name_table='Статистический отчет по количеству Договоров/Контрактов'>Сформировать Excel</button>
					</div>
				</div>
				<div class='row' style='text-align: center;'>
					<div class="col-md-12">
						Статистический отчет по количеству действующих Договоров (Контрактов) по Подразделению
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
							<th>Закуп</th>
							<th>Сбыт</th>
							<th>Закуп/Сбыт</th>
							<th>Другое</th>
							<th>Отказ</th>
							<th>Всего</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($departments))
							<?php $all_procurement_count=0; $all_marketing_count=0; $all_procurement_marketing_count=0; $all_other_count=0; $all_break_count=0; $all_all_count=0; ?>
							@foreach($departments as $department)
								<tr>
									<td>{{$department->index_department}}</td>
									<td>{{$department->name_department}}</td>
									<td>{{$department->procurement_count}}</td>
									<td>{{$department->marketing_count}}</td>
									<td>{{$department->procurement_marketing_count}}</td>
									<td>{{$department->other_count}}</td>
									<td>{{$department->break_count}}</td>
									<td>{{$department->all_count}}</td>
								</tr>
								<?php $all_procurement_count+=$department->procurement_count; $all_marketing_count+=$department->marketing_count; $all_procurement_marketing_count+=$department->procurement_marketing_count; $all_other_count+=$department->other_count; $all_break_count+=$department->break_count; $all_all_count+=$department->all_count; ?>
							@endforeach
							<tr>
								<td></td>
								<td></td>
								<td><b>{{$all_procurement_count}}</b></td>
								<td><b>{{$all_marketing_count}}</b></td>
								<td><b>{{$all_procurement_marketing_count}}</b></td>
								<td><b>{{$all_other_count}}</b></td>
								<td><b>{{$all_break_count}}</b></td>
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
