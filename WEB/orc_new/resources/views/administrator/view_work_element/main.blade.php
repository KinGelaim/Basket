@extends('layouts.header')

@section('title')
	Виды испытаний
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Второй отдел')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Виды испытаний</h3>
						</div>
						<div class="col-md-12" style='margin-top: 10px;'>
							<button class='btn btn-primary btn-href' type='button' href="{{route('view_work.element.create')}}">Добавить вид испытания</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>Название элементы</th>
										<th>Редактировать</th>
										@if(Auth::User()->hasRole()->role == 'Администратор')
											<th>Удалить</th>
										@endif
									</tr>
								</thead>
								<tbody>
									@foreach($views as $view)
										<tr class='rowsContract'>
											<td>{{$view->name_view_work_elements}}</td>
											<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("view_work.element.edit", $view->id)}}'>Редактировать</button></td>
											@if(Auth::User()->hasRole()->role == 'Администратор')
												<td><button type='button' class='btn btn-danger btn-href' type='button' href='{{route("view_work.element.delete", $view->id)}}'>Удалить</button></td>
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
