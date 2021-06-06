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
						<div class="col-md-12" style='margin-top: 10px;'>
							<button class='btn btn-primary btn-href' type='button' href="{{route('schoolchildren.create')}}">Добавить ученика</button>
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
										<th>Учёба</th>
										<th>Финансы</th>
										<th>Редактировать</th>
										<th>Удалить</th>
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
											<td>{{$schoolchildren->is_complete}}</td>
											<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("schoolchildren.finance", $schoolchildren->id)}}'>Финансы</button></td>
											<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("schoolchildren.edit", $schoolchildren->id)}}'>Редактировать</button></td>
											<td><button type='button' class='btn btn-danger btn-href' type='button' href='{{route("schoolchildren.delete", $schoolchildren->id)}}'>Удалить</button></td>
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
