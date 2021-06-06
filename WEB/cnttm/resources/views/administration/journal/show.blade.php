@extends('layouts.header')

@section('title')
	Журнал
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Преподаватель')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>{{$journal->name}}</h3>
						</div>
						<div class="col-md-6" style='margin-top: 10px;'>
							<button class='btn btn-primary' type='button' data-toggle="modal" data-target="#newVisit">Добавить учебный день</button>
						</div>
						<div class="col-md-6" style='margin-top: 10px;'>
							<button id='createExcel' real_name_table='{{$journal->name}}' class='btn btn-primary' type='button' style='float: right;'>Вывести в EXCEL</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table id='resultTable' class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>ФИО</th>
										@foreach($result as $key=>$value)
											<th>{{$key}}</th>
										@endforeach
									</tr>
								</thead>
								<tbody>
									@foreach($result as $key=>$value)
										@foreach($value as $idUser=>$visit)
											<tr class='rowsContract'>
												<td>{{$visit->surname}} {{$visit->name}} {{$visit->patronymic}}</td>
												@foreach($result as $key3=>$value3)
													<td>{{$value3[$idUser]['state']}}</td>
												@endforeach
											</tr>
										@endforeach
										@break
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- Модальное окно нового посещения -->
				<div class="modal fade" id="newVisit" tabindex="-1" role="dialog" aria-labelledby="newVisitModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="contract">
						<div class="modal-content">
							<form method='POST' action='{{route("journal.visit.add", $journal->id)}}'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="newVisitModalLabel">Новое посещение</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='row'>
										<div class="col-md-12">
											<label>Дата</label>
										</div>
										<div class="col-md-12">
											<input id='datepicker' class='form-control' name='date' required />
										</div>
									</div>
									@foreach($schoolchildrens as $schoolchildren)
										<div class='row'>
											<div class="col-md-12">
												<label>{{$schoolchildren->surname}} {{$schoolchildren->name}} {{$schoolchildren->patronymic}}</label>
											</div>
											<div class="col-md-12">
												<select class="form-control" name='state[{{$schoolchildren->id}}]'>
													<option value=""></option>
													@foreach($states as $state)
														<option value='{{$state->id}}'>{{ $state->name }}</option>
													@endforeach
												</select>
											</div>
										</div>
									@endforeach
								</div>
								<div class="modal-footer">
									<button type="submit" class="btn btn-primary">Добавить</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<script>
					$('#datepicker').datepicker();
					$('#datepicker').datepicker("option", "dateFormat", "dd.mm.yy");
				</script>
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
