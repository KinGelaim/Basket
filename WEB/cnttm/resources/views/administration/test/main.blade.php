@extends('layouts.header')

@section('title')
	Тесты
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Преподаватель')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Тесты</h3>
						</div>
						<div class="col-md-12" style='margin-top: 10px;'>
							<button class='btn btn-primary btn-href' type='button' href="{{route('test.create')}}">Добавить тест</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>Название</th>
										<th>Название первой части</th>
										<th>Название второй части</th>
										<th>Название третий части</th>
										<th>Название четвертой части</th>
										<th>Просмотреть</th>
										<th>Редактировать</th>
										<th>Удалить</th>
									</tr>
								</thead>
								<tbody>
									@foreach($tests as $test)
										<tr class='rowsContract'>
											<td>{{$test->name}}</td>
											<td>{{$test->name_first_part}}</td>
											<td>{{$test->name_second_part}}</td>
											<td>{{$test->name_third_part}}</td>
											<td>{{$test->name_fourth_part}}</td>
											<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("test.show", $test->id)}}'>Просмотреть</button></td>
											<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("test.edit", $test->id)}}'>Редактировать</button></td>
											<td><button type='button' class='btn btn-danger btn-href' type='button' href='{{route("test.delete", $test->id)}}'>Удалить</button></td>
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
