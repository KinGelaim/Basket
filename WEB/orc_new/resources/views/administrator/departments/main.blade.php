@extends('layouts.header')

@section('title')
	Подразделения
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Подразделения</h3>
						</div>
						<div class="col-md-12" style='margin-top: 10px;'>
							<button class='btn btn-primary btn-href' type='button' href="{{route('departments.create')}}">Добавить подразделение</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>Индек подразделения</th>
										<th>Название подразделения</th>
										<th>Руководитель подразделения</th>
										<th>Редактировать</th>
										<th>Удалить</th>
									</tr>
								</thead>
								<tbody>
									@foreach($departments as $department)
										<tr class='rowsContract'>
											<td>{{$department->index_department}}</td>
											<td>{{$department->name_department}}</td>
											<td>{{$department->position_department}}<br/>{{$department->surname . ' ' . mb_substr($department->name, 0, 1) . '.' . mb_substr($department->patronymic, 0, 1) . '.'}}</td>
											<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("departments.edit", $department->id)}}'>Редактировать</button></td>
											<td><button type='button' class='btn btn-danger btn-href' type='button' href='{{route("departments.delete", $department->id)}}'>Удалить</button></td>
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
