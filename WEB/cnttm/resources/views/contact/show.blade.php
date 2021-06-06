@extends('layouts.header')

@section('title')
	Сообщения
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Преподаватель')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Сообщения</h3>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>Фамилия</th>
										<th>Имя</th>
										<th>Почта</th>
										<th>Сообщение</th>
										<th>Дата</th>
									</tr>
								</thead>
								<tbody>
									@foreach($messages as $message)
										<tr class='rowsContract'>
											<td>{{$message->last_name}}</td>
											<td>{{$message->first_name}}</td>
											<td>{{$message->email}}</td>
											<td><pre>{{$message->message}}</pre></td>
											<td>{{$message->created_at}}</td>
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
