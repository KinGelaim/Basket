@extends('layouts.header')

@section('title')
	Печать Итоги по введенным Договорам/Контрактам
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-12">
						<button class='btn btn-primary' id='createExcel' real_name_table='Итоги по введенным Договорам/Контрактам'>Сформировать Excel</button>
					</div>
				</div>
				<div class='row' style='text-align: center;'>
					<div class="col-md-12">
						Итоги по введенным Договорам/Контрактам
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
				<div class='row'>
					<div class="col-md-8 col-md-offset-2">
						<table id='resultTable' class="table table-bordered tablePrint" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
							<thead style='text-align: center;'>
								<tr>
									<th style='text-align: center;'>Всего</th>
									<th style='text-align: center;'>Проект</th>
									<th style='text-align: center;'>Вступили в силу</th>
									<th style='text-align: center;'>Отказы</th>
									<th style='text-align: center;'>Архив</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>{{$proekt_count_contracts + $registr_count_contracts + $break_count_contracts + $arhive_count_contracts}}</td>
									<td>{{$proekt_count_contracts}}</td>
									<td>{{$registr_count_contracts}}</td>
									<td>{{$break_count_contracts}}</td>
									<td>{{$arhive_count_contracts}}</td>
								</tr>
							</tbody>
						</table>
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
