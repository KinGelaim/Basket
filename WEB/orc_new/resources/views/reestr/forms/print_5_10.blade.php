@extends('layouts.header')

@section('title')
	Печать Сведения о количестве Договоров (Контрактов)
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-12">
						<button class='btn btn-primary' id='createExcel' real_name_table='Сведения о количестве Договоров (Контрактов)'>Сформировать Excel</button>
					</div>
				</div>
				<div class='row' style='text-align: center;'>
					<div class="col-md-12">
						Сведения о количестве Договоров (Контрактов)
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
					<div class="col-md-7">
					</div>
					<div class="col-md-5">
						<table class='table table-bordered' style='text-align: center;'>
							<thead>
								<tr>
									<th colspan='4' style='text-align: center;'>По текущему году</th>
									<th colspan='4' style='text-align: center;'>За предыдущие года</th>
								</tr>
								<tr>
									<th style='text-align: center;'>Всего</th>
									<th style='text-align: center;'>Проект</th>
									<th style='text-align: center;'>Регистр.</th>
									<th style='text-align: center;'>Отказы</th>
									<th style='text-align: center;'>Всего</th>
									<th style='text-align: center;'>Проект</th>
									<th style='text-align: center;'>Регистр.</th>
									<th style='text-align: center;'>Отказы</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>{{$proekt_t + $registr_t + $break_t}}</td>
									<td>{{$proekt_t}}</td>
									<td>{{$registr_t}}</td>
									<td>{{$break_t}}</td>
									<td>{{$proekt_p + $registr_p + $break_p}}</td>
									<td>{{$proekt_p}}</td>
									<td>{{$registr_p}}</td>
									<td>{{$break_p}}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<table id='resultTable' class="table table-bordered tablePrint" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
					<thead style='text-align: center;'>
						<tr>
							<th>Индекс</th>
							<th>Подразделение</th>
							<th>Дог_Т</th>
							<th>Пр_Т</th>
							<th>Рег_Т</th>
							<th>Отк_Т</th>
							<th>Дог_П</th>
							<th>Пр_П</th>
							<th>Рег_П</th>
							<th>Отк_П</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($departments))
							@foreach($departments as $department)
								<tr>
									<td>{{$department->index_department}}</td>
									<td>{{$department->name_department}}</td>
									<td>{{$department->t_all_count_contracts != 0 ? $department->t_all_count_contracts : ''}}</td>
									<td>{{$department->t_proekt_count_contracts != 0 ? $department->t_proekt_count_contracts : ''}}</td>
									<td>{{$department->t_registr_count_contracts != 0 ? $department->t_registr_count_contracts : ''}}</td>
									<td>{{$department->t_break_count_contracts != 0 ? $department->t_break_count_contracts : ''}}</td>
									<td>{{$department->p_all_count_contracts != 0 ? $department->p_all_count_contracts : ''}}</td>
									<td>{{$department->p_proekt_count_contracts != 0 ? $department->p_proekt_count_contracts : ''}}</td>
									<td>{{$department->p_registr_count_contracts != 0 ? $department->p_registr_count_contracts : ''}}</td>
									<td>{{$department->p_break_count_contracts != 0 ? $department->p_break_count_contracts : ''}}</td>
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
