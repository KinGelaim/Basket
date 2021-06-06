@extends('layouts.header')

@section('title')
	Склад комплектующих
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Десятый отдел')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Склад комплектующих</h3>
						</div>
						<div class="col-md-12" style='margin-top: 10px;'>
							<button class='btn btn-primary btn-href' type='button' href="{{route('ten.party_element_create')}}">Добавить комплектующий на склад</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>Название элемента</th>
										<th>Идентификатор партии</th>
										<th>Дата партии</th>
										<th>Количество элементов изначально</th>
										<th>Текущее количество</th>
										<th>Редактировать</th>
										<th>Удалить</th>
									</tr>
								</thead>
								<tbody>
									@foreach($components as $component)
										<tr class='rowsContract'>
											<td>{{$component->name_component}}</td>
											<td>{{$component->name_party}}</td>
											<td>{{$component->date_party}}</td>
											<td>{{$component->count_party}}</td>
											<td>{{$component->new_count_party}}</td>
											<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("ten.party_element_edit", $component->id)}}'>Редактировать</button></td>
											<td><button type='button' class='btn btn-danger btn-href' type='button' href='{{route("ten.party_element_delete", $component->id)}}'>Удалить</button></td>
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
