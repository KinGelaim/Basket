@extends('layouts.header')

@section('title')
	{{$test->name}}
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Преподаватель')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Тест: {{$test->name}}</h3>
						</div>
						<div class="col-md-3" style='margin-top: 10px;'>
							<button class='btn btn-primary btn-href' type='button' href="{{route('question.create', $test->id)}}">Добавить вопрос</button>
						</div>
						<div class="col-md-3" style='margin-top: 10px;'>
							<button class='btn btn-primary btn-href' type='button' href="{{route('test.redirect_group', $test->id)}}">Направить группе</button>
						</div>
						<div class="col-md-3" style='margin-top: 10px;'>
							<button class='btn btn-primary btn-href' type='button' href="{{route('test.redirect_schoolchildren', $test->id)}}">Направить ученику</button>
						</div>
						<div class="col-md-3" style='margin-top: 10px;'>
							<button class='btn btn-primary btn-href' type='button' href="{{route('test.all_show_test', $test->id)}}">Полное отображение</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>Часть</th>
										<th>Позиция</th>
										<th>Вопрос</th>
										<th>Вид вопроса</th>
										<th>Редактировать</th>
										<th>Удалить</th>
									</tr>
								</thead>
								<tbody>
									@foreach($questions as $question)
										<tr class='rowsContract'>
											<td>{{$question->part}}</td>
											<td>{{$question->position}}</td>
											<td>{{$question->question}}</td>
											<td>{{$question->real_name}}</td>
											<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("question.edit", $question->id)}}'>Редактировать</button></td>
											<td><button type='button' class='btn btn-danger btn-href' type='button' href='{{route("question.delete", $question->id)}}'>Удалить</button></td>
										</tr>
									@endforeach
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
