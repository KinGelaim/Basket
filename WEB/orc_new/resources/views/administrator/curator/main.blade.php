@extends('layouts.header')

@section('title')
	Кураторы
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Кураторы</h3>
						</div>
						<div class="col-md-12" style='margin-top: 10px;'>
							<button class='btn btn-primary btn-href' type='button' href="{{route('curator.create')}}">Добавить куратора</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>ФИО</th>
										<th>Телефон</th>
										<th>Редактировать</th>
										<th>Удалить</th>
									</tr>
								</thead>
								<tbody>
									@foreach($curators as $curator)
										<tr class='rowsContract'>
											<td>{{$curator->FIO}}</td>
											<td>{{$curator->telephone}}</td>
											<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("curator.edit", $curator->id)}}'>Редактировать</button></td>
											@if($curator->deleted_at)
												<td>Удалено</td>
											@else
												<td><button type='button' class='btn btn-danger btn-href' type='button' href='{{route("curator.delete", $curator->id)}}'>Удалить</button></td>
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
