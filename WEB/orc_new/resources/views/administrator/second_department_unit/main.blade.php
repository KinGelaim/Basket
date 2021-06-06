@extends('layouts.header')

@section('title')
	Единицы измерения для второго отдела
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Второй отдел')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Единицы измерения</h3>
						</div>
						<div class="col-md-12" style='margin-top: 10px;'>
							<button class='btn btn-primary btn-href' type='button' href="{{route('second_department_unit.create')}}">Добавить единицу измерения</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>Название единицы измерения</th>
										<th>Редактировать</th>
										@if(Auth::User()->hasRole()->role == 'Администратор')
											<th>Удалить</th>
										@endif
									</tr>
								</thead>
								<tbody>
									@foreach($units as $unit)
										<tr class='rowsContract'>
											<td>{{$unit->name_unit}}</td>
											<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("second_department_unit.edit", $unit->id)}}'>Редактировать</button></td>
											@if(Auth::User()->hasRole()->role == 'Администратор')
												<td><button type='button' class='btn btn-danger btn-href' type='button' href='{{route("second_department_unit.delete", $unit->id)}}'>Удалить</button></td>
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
