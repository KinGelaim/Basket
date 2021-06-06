@extends('layouts.header')

@section('title')
	Журнал событий
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор')
				<form>
					<div class='row'>
						<div class='col-md-2'>
							@include('layouts.filters.users', ['users'=>$users])
						</div>
						<div class='col-md-2'>
							@include('layouts.filters.roles', ['roles'=>$roles])
						</div>
						<div class='col-md-1'>
							@include('layouts.filters.dates')
						</div>
						<div class='col-md-4'>
							@includeif('layouts.search', ['search_arr_value'=>['message'=>'Действие']])
						</div>
						<div class='col-md-2'>
						</div>
						<div class='col-md-1'>
							<button class='btn btn-primary' data-toggle="modal" data-target="#report" type='button'>Отчёты</button>
						</div>
					</div>
				</form>
				<div class='row'>
					<div class='col-md-8 col-md-offset-2'>
						<table class="table" style='margin: 0 auto;'>
							<thead>
								<tr>
									<th>Подразделение</th>
									<th>Пользователь</th>
									<th>Действие</th>
									<th>Дата</th>
								</tr>
							</thead>
							<tbody>
								<?php $count_rows = 1; ?>
								@foreach($journals as $journal)
									@if(!isset($journal->comment))
										<tr class='rowsContract'>
											<td>{{$journal->role}}</td>
											<td>{{$journal->surname . ' ' . $journal->name . ' ' . $journal->patronymic}}</td>
											<td>{{$journal->message}}</td>
											<td>{{$journal->created_at}}</td>
										</tr>
									@else
										<tr class='rowsContract cursorPointer' data-toggle='collapse' data-target='#group-of-rows-{{$count_rows}}' aria-expanded='false' aria-controls='group-of-rows-{{$count_rows}}'>
											<td>{{$journal->role}}</td>
											<td>{{$journal->surname . ' ' . $journal->name . ' ' . $journal->patronymic}}</td>
											<td>{{$journal->message}}</td>
											<td>{{$journal->created_at}}</td>
										</tr>
										<tr id='group-of-rows-{{$count_rows}}' class='collapse' style='background-color: #E7E7E7;'>
											<td colspan='4'>
												<p>
													@foreach($journal->comment as $key=>$value)
														<b>{{$key}}:</b>{{$value}}<br/>
													@endforeach
												</p>
											</td>
										</tr>
									@endif
									<?php $count_rows++; ?>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12" style="text-align: center;">
						@include('layouts.paginate')
					</div>
				</div>
				<!-- Модальное окно отчётов -->
				<div class="modal fade" id="report" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="reportModalLabel">Отчёты по журналу</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div id='chose_reports' class="modal-body">
								<div class='form-group row'>
									<div class="col-md-3">
										<button type='button' first_step='#chose_reports' second_step='#chose_auth' class='btn btn-primary steps'>Посещаемость</button>
									</div>
									<div class="col-md-3">
										<button type='button' first_step='#chose_reports' second_step='#chose_action' class='btn btn-primary steps'>Действия</button>
									</div>
								</div>
							</div>
							<form id='chose_auth' method='GET' action='{{route("journal.report")}}' style='display: none;'>
								<div class="modal-body">
									<input name='type_report' value='auth' style='display:none;'/>
									<div class='row'>
										<div class='col-md-12'>
											<label>Подразделение</label>
											<div class="form-group">
												<select class='form-control' name='role'>
													<option></option>
													@foreach($roles as $role)
														<option value='{{$role->id}}'>{{$role->role}}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-12">
											<label>Начальная дата</label>
										</div>
										<div class="col-md-12">
											<input class='form-control datepicker' name='date_begin' required />
										</div>
									</div>
									<div class='row'>
										<div class="col-md-12">
											<label>Конечная дата</label>
										</div>
										<div class="col-md-12">
											<input class='form-control datepicker' name='date_end' required />
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-9">
											<button type='submit' class='btn btn-primary' style='float: right;'>Отчёт</button>
										</div>
										<div class="col-md-3">
											<button type='button' class='btn btn-secondary steps' first_step='#chose_auth' second_step='#chose_reports' style='float: right;'>Назад</button>
										</div>
									</div>
								</div>
							</form>
							<form id='chose_action' method='GET' action='{{route("journal.report")}}' style='display: none;'>
								<div class="modal-body">
									<input name='type_report' value='action' style='display:none;'/>
									<div class='row'>
										<div class='col-md-12'>
											<label>Подразделение</label>
											<div class="form-group">
												<select class='form-control' name='role'>
													<option></option>
													@foreach($roles as $role)
														<option value='{{$role->id}}'>{{$role->role}}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-12">
											<label>Начальная дата</label>
										</div>
										<div class="col-md-12">
											<input class='form-control datepicker' name='date_begin' required />
										</div>
									</div>
									<div class='row'>
										<div class="col-md-12">
											<label>Конечная дата</label>
										</div>
										<div class="col-md-12">
											<input class='form-control datepicker' name='date_end' required />
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-9">
											<button type='submit' class='btn btn-primary' style='float: right;'>Отчёт</button>
										</div>
										<div class="col-md-3">
											<button type='button' class='btn btn-secondary steps' first_step='#chose_action' second_step='#chose_reports' style='float: right;'>Назад</button>
										</div>
									</div>
								</div>
							</form>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
							</div>
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
