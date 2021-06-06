@extends('layouts.header')

@section('title')
	Финансы
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Преподаватель')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3><b>{{$schoolchildren->surname}} {{$schoolchildren->name}} {{$schoolchildren->patronymic}}</b></h3>
						</div>
						<div class="col-md-12">
							<h5>Цена занятия: <b>{{$pricelesson}}</b></h5>
						</div>
						<div class="col-md-12">
							<h5>Всего занятий: <b>{{$allvisits}}</b></h5>
						</div>
						<div class="col-md-12">
							<h5>Пропущенных занятий: <b>{{$missvisits+$amissvisits}}</b></h5>
						</div>
						<div class="col-md-12">
							<h5>Из них по уважительной причине: <b>{{$amissvisits}}</b></h5>
						</div>
						<div class="col-md-12">
							<h5>Оплачено учеником: <b>{{$all_amount}}</b></h5>
						</div>
						<div class="col-md-12">
							<h5>Всего необходимо оплатить: <b>{{$all_need_amount}}</b></h5>
						</div>
						<div class="col-md-12">
							<h5><b>Долг: {{$need_amount}}</b></h5>
						</div>
						<div class="col-md-12" style='margin-top: 10px;'>
							<button class='btn btn-primary' type='button' data-toggle="modal" data-target="#newFinance">Добавить финансы</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>Номер счёта</th>
										<th>Сумма</th>
										<th>Дата счёта</th>
									</tr>
								</thead>
								<tbody>
									@foreach($finances as $finance)
										<tr class='rowsContract'>
											<td>{{$finance->number}}</td>
											<td>{{$finance->amount}}</td>
											<td>{{$finance->date}}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- Модальное окно нового посещения -->
				<div class="modal fade" id="newFinance" tabindex="-1" role="dialog" aria-labelledby="newVisitModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="contract">
						<div class="modal-content">
							<form method='POST' action='{{route("finance.save", $schoolchildren->id)}}'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="newVisitModalLabel">Новые финансы</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='row'>
										<div class="col-md-12">
											<label>Номер счёта</label>
										</div>
										<div class="col-md-12">
											<input id='number' class='form-control' name='number' required />
										</div>
									</div>
									<div class='row'>
										<div class="col-md-12">
											<label>Сумма</label>
										</div>
										<div class="col-md-12">
											<input id='amount' class='form-control' name='amount' required />
										</div>
									</div>
									<div class='row'>
										<div class="col-md-12">
											<label>Дата</label>
										</div>
										<div class="col-md-12">
											<input id='date' class='form-control' name='date' required />
										</div>
									</div>
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
					$('#date').datepicker();
					$('#date').datepicker("option", "dateFormat", "dd.mm.yy");
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
