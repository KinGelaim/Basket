@extends('layouts.header')

@section('title')
	Акты испытания
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Акты испытания</h3>
						</div>
						<div class="col-md-12" style='margin-top: 10px;'>
							<button class='btn btn-primary btn-href' type='button' href="{{route('department.second.create_act_sb', $id_second_tour)}}">Добавить акт</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8 col-md-offset-2">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>Номер акта</th>
										<th>Дата акта</th>
										<th>Сумма с НДС, руб.</th>
										<th>Редактировать</th>
										@if(Auth::User()->hasRole()->role == 'Администратор')
											<th>Удалить</th>
										@endif
									</tr>
								</thead>
								<tbody>
									<?php $amount_acts = 0; ?>
									@foreach($acts as $act)
										<tr class='rowsContract'>
											<td>{{$act->number_act}}</td>
											<td>{{$act->date_act}}</td>
											<td>{{is_numeric($act->amount_act) ? number_format($act->amount_act, 2, ',', ' ') : $act->amount_act}}</td>
											<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("department.second.edit_act", $act->id)}}'>Редактировать</button></td>
											@if(Auth::User()->hasRole()->role == 'Администратор')
												<td><button type='button' class='btn btn-danger btn-href' type='button' href='{{route("department.second.delete_act", $act->id)}}'>Удалить</button></td>
											@endif
										</tr>
										<?php $amount_acts += $act->amount_act; ?>
									@endforeach
									<tr class='rowsContract'>
										<td></td>
										<td><b>Сумма:</b></td>
										<td><b>{{is_numeric($amount_acts) ? number_format($amount_acts, 2, ',', ' ') : $amount_acts}}</b></td>
										<td></td>
										@if(Auth::User()->hasRole()->role == 'Администратор')
											<td></td>
										@endif
									</tr>
								</tbody>
							</table>
						</div>
					</div>
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
