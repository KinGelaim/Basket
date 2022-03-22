@extends('layouts.header')

@section('title')
	Печать Список заявок
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-12">
						<button class='btn btn-primary' id='createExcel' real_name_table='Список заявок'>Сформировать Excel</button>
					</div>
				</div>
				<div class='row' style='text-align: center;'>
					<div class="col-md-12">
						Список заявок
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
							<th>№ п/п</th>
							<th>Дата регистрации заявки</th>
							<th>Заявка Исх. №</th>
							<th>н/Вх.</th>
							<th>Ответ по Заявке Исх. №</th>
							<th>Исполнитель</th>
							<th>Предмет (содержание заявки)</th>
							<th>Цель</th>
							<th>Срок исполнения</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($new_applications))
							<?php
								$count = 0;
							?>
							@foreach($new_applications as $application)
								<tr>
									<td>{{++$count}}</td>
									<td>{{$application->date_registration_new_application}}</td>
									<td>{{$application->number_outgoing_new_application}}</td>
									<td>{{$application->number_incoming_new_application}}</td>
									<td>{{$application->result_outgoing_new_application}}</td>
									<td>{{$application->executor_new_application}}</td>
									<td>{{$application->item_new_application}}</td>
									<td>{{$application->name_work_new_application}}</td>
									<td>{{$application->term_maturity_new_application}}</td>
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
