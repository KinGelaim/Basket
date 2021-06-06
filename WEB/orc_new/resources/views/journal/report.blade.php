@extends('layouts.header')

@section('title')
	Отчёт по журналу
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор')
				<div class="">
					<div class='row'>
						<div class="col-md-12">
							<button class='btn btn-primary' id='createExcel'>Сформировать Excel</button>
						</div>
					</div>
					<div class='row' style='text-align: center;'>
						<div class="col-md-12">
							@if($type_report == 'auth')
								Отчёт посещения по журналу
							@elseif($type_report == 'action')
								Отчёт действий по журналу
							@endif
						</div>
					</div>
					<div class='row' style='text-align: right;'>
						<div class="col-md-12">
							по состоянию на {{date('d.m.Y', time())}} г.
						</div>
					</div>
					<table id='resultTable' class="table table-bordered tablePrint" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
						@if($type_report == 'auth')
							<thead style='text-align: center;'>
								<tr>
									<th>ФИО</th>
									<th>Дата</th>
								</tr>
							</thead>
							<tbody>
								@foreach($journal as $rep)
									<tr>
										<td>{{$rep->surname}} {{$rep->name}} {{$rep->patronymic}}</td>
										<td>{{date('d.m.Y', strtotime($rep->created_at))}}</td>
									</tr>
								@endforeach
							</tbody>
						@elseif($type_report == 'action')
							<thead style='text-align: center;'>
								<tr>
									<th>ФИО</th>
									<th>Действие</th>
									<th>Описание действие</th>
									<th>Дата</th>
								</tr>
							</thead>
							<tbody>
								@foreach($journal as $rep)
									<tr>
										<td>{{$rep->surname}} {{$rep->name}} {{$rep->patronymic}}</td>
										<td>{{$rep->message_action}}</td>
										<td>{{$rep->message_description}}</td>
										<td>{{date('d.m.Y', strtotime($rep->created_at))}}</td>
									</tr>
								@endforeach
							</tbody>
						@endif
					</table>
				</div>
			@else
				<div class="alert alert-danger">
					Недостаточно прав для просмотра данной страницы!
				</div>
			@endif
		@else
			<div class="alert alert-danger">
				Необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection
