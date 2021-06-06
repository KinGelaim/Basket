@extends('layouts.header')

@section('title')
	Типы документов
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Канцелярия')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Типы документов</h3>
						</div>
						<div class="col-md-12" style='margin-top: 10px;'>
							<button class='btn btn-primary btn-href' type='button' href="{{route('type_document.create')}}">Добавить тип документа</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>Наименование</th>
										<th>Организация</th>
										<th>Редактировать</th>
										<th>Удалить</th>
									</tr>
								</thead>
								<tbody>
									@foreach($type_documents as $type_document)
										<tr class='rowsContract'>
											<td>{{$type_document->name}}</td>
											<td>{{$type_document->counterpartie}}</td>
											<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("type_document.edit", $type_document->id)}}'>Редактировать</button></td>
											<td><button type='button' class='btn btn-danger btn-href' type='button' href='{{route("type_document.delete", $type_document->id)}}'>Удалить</button></td>
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
