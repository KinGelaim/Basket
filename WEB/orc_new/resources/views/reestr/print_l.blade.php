@extends('layouts.header')

@section('title')
	Печать Итоги по виду договора
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div>
				<div class='row'>
					<div class="col-md-12">
						<button class='btn btn-primary' id='createExcel' real_name_table='Итоги по виду договора'>Сформировать Excel</button>
					</div>
				</div>
				<div class='row' style='text-align: center;'>
					<div class="col-md-12">
						Итоги по виду договора <b>{{$view_contract->name_view_contract}}</b>
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
									<th style='text-align: center;'>Год</th>
									<th style='text-align: center;'>Кол-во договоров</th>
								</tr>
							</thead>
							<tbody>
								@foreach($result as $key=>$value)
									<tr>
										<td>{{$key}}</td>
										<td>{{$value}}</td>
									</tr>
								@endforeach
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
