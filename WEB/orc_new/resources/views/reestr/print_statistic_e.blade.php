@extends('layouts.header')

@section('title')
	Печать Отказы по Договорам/Контрактам за год (суммы)
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-12">
						<button class='btn btn-primary' id='createExcel' real_name_table='Отказы по Договорам/Контрактам за год (суммы)'>Сформировать Excel</button>
					</div>
				</div>
				<div class='row' style='text-align: center;'>
					<div class="col-md-12">
						Отказы по Договорам/Контрактам за год (суммы)
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
							<th>Закуп</th>
							<th>Сбыт</th>
							<th>Иное</th>
							<th>До 100 т.р.</th>
							<th>100-500 т.р.</th>
							<th>500 т. - 1 млн.р.</th>
							<th>Свыше 1 млн.р.</th>
							<th>Без конкрет. суммы</th>
							<th>Всего</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>+</td>
							<td>+</td>
							<td></td>
							<td><b>{{$m_p_count_100}}</b></td>
							<td><b>{{$m_p_count_300}}</b></td>
							<td><b>{{$m_p_count_700}}</b></td>
							<td><b>{{$m_p_count_lyam}}</b></td>
							<td><b>{{$m_p_count_null}}</b></td>
							<td><b>{{$m_p_count_count}}</b></td>
						</tr>
						<tr>
							<td>+</td>
							<td></td>
							<td></td>
							<td><b>{{$m_count_100}}</b></td>
							<td><b>{{$m_count_300}}</b></td>
							<td><b>{{$m_count_700}}</b></td>
							<td><b>{{$m_count_lyam}}</b></td>
							<td><b>{{$m_count_null}}</b></td>
							<td><b>{{$m_count_count}}</b></td>
						</tr>
						<tr>
							<td></td>
							<td>+</td>
							<td></td>
							<td><b>{{$p_count_100}}</b></td>
							<td><b>{{$p_count_300}}</b></td>
							<td><b>{{$p_count_700}}</b></td>
							<td><b>{{$p_count_lyam}}</b></td>
							<td><b>{{$p_count_null}}</b></td>
							<td><b>{{$p_count_count}}</b></td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td>+</td>
							<td><b>{{$o_count_100}}</b></td>
							<td><b>{{$o_count_300}}</b></td>
							<td><b>{{$o_count_700}}</b></td>
							<td><b>{{$o_count_lyam}}</b></td>
							<td><b>{{$o_count_null}}</b></td>
							<td><b>{{$o_count_count}}</b></td>
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
