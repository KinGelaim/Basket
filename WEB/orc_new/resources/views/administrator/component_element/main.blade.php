@extends('layouts.header')

@section('title')
	Комплектующие
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Десятый отдел')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Комплектующие</h3>
						</div>
						<div class="col-md-12" style='margin-top: 10px;'>
							<button class='btn btn-primary btn-href' type='button' href="{{route('ten.element_create')}}">Добавить комплектующий</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>Название элемента</th>
										<th>Поставщик</th>
										<th>Количество на складе</th>
										<th>Нужная потребность для заказа</th>
										<th>Редактировать</th>
										<th>Удалить</th>
									</tr>
								</thead>
								<tbody>
									@foreach($elements as $element)
										<tr class='rowsContract'>
											<td>{{$element->name_component}}</td>
											<td>{{$element->name_counterpartie}}</td>
											<td>{{$element->count_element}}</td>
											<td>{{$element->need_count_element}}</td>
											<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("ten.element_edit", $element->id)}}'>Редактировать</button></td>
											<td><button type='button' class='btn btn-danger btn-href' type='button' href='{{route("ten.element_delete", $element->id)}}'>Удалить</button></td>
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
