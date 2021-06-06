@extends('layouts.header')

@section('title')
	Ученики
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Преподаватель')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Ученики</h3>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>Фамилия</th>
										<th>Имя</th>
										<th>Отчество</th>
										<th>Группа</th>
										<th>Лаборатория</th>
										<th>Просмотреть</th>
									</tr>
								</thead>
								<tbody>
									@foreach($schoolchildrens as $schoolchildren)
										<tr class='rowsContract'>
											<td>{{$schoolchildren->surname}}</td>
											<td>{{$schoolchildren->name}}</td>
											<td>{{$schoolchildren->patronymic}}</td>
											<td>{{$schoolchildren->nameGroup}}</td>
											<td>{{$schoolchildren->nameLaba}}</td>
											<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("test.show_test_schoolchildren", $schoolchildren->id)}}'>Просмотреть</button></td>
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
