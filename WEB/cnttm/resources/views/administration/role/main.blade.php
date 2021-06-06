@extends('layouts.header')

@section('title')
	Роли
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Роли</h3>
						</div>
						<div class="col-md-12" style='margin-top: 10px;'>
							<button class='btn btn-primary btn-href' type='button' href="{{route('role.create')}}">Добавить роль</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>Название</th>
										<th>Редактировать</th>
										<th>Удалить</th>
									</tr>
								</thead>
								<tbody>
									@foreach($roles as $role)
										<tr class='rowsContract'>
											<td>{{$role->role}}</td>
											<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("role.edit", $role->id)}}'>Редактировать</button></td>
											@if($role->deleted_at)
												<td>Удалено</td>
											@else
												<td><button type='button' class='btn btn-danger btn-href' type='button' href='{{route("role.delete", $role->id)}}'>Удалить</button></td>
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
