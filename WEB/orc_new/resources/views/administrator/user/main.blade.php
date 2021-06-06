@extends('layouts.header')

@section('title')
	Пользователи
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Пользователи</h3>
						</div>
						<div class="col-md-12" style='margin-top: 10px;'>
							<button class='btn btn-primary btn-href' type='button' href="{{route('user.create')}}">Добавить пользователя</button>
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
										<th>Доступ</th>
										<th>Должность</th>
										<th>Редактировать</th>
										<th>Удалить</th>
									</tr>
								</thead>
								<tbody>
									@foreach($users as $user)
										<tr class='rowsContract'>
											<td>{{$user->surname}}</td>
											<td>{{$user->name}}</td>
											<td>{{$user->patronymic}}</td>
											<td>{{$user->role}}</td>
											<td>{{$user->position_department}}</td>
											@if($user->id != '1')
												<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("user.edit", $user->id)}}'>Редактировать</button></td>
												@if($user->deleted_at)
													<td>Удалено</td>
												@else
													<td><button type='button' class='btn btn-danger btn-href' type='button' href='{{route("user.delete", $user->id)}}'>Удалить</button></td>
												@endif
											@else
												<td></td>
												<td></td>
											@endif
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
