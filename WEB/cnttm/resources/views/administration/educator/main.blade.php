@extends('layouts.header')

@section('title')
	Педагоги
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Преподаватель')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Педагоги</h3>
						</div>
						<div class="col-md-12" style='margin-top: 10px;'>
							<button class='btn btn-primary btn-href' type='button' href="{{route('educator.create')}}">Добавить педагога</button>
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
										<th>Фотография</th>
										<th>Краткая информация</th>
										<th>Редактировать</th>
										<th>Удалить</th>
									</tr>
								</thead>
								<tbody>
									@foreach($educators as $educator)
										<tr class='rowsContract'>
											<td>{{$educator->surname}}</td>
											<td>{{$educator->name}}</td>
											<td>{{$educator->patronymic}}</td>
											<td>{{$educator->photo}}</td>
											<td>{{$educator->short_information}}</td>
											<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("educator.edit", $educator->id)}}'>Редактировать</button></td>
											<td><button type='button' class='btn btn-danger btn-href' type='button' href='{{route("educator.delete", $educator->id)}}'>Удалить</button></td>
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
