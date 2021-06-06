@extends('layouts.header')

@section('title')
	Направление теста группам учеников
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Преподаватель')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Направление теста группам учеников</h3>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>Название группы</th>
										<th>Направить</th>
									</tr>
								</thead>
								<tbody>
									@foreach($groups as $group)
										<tr class='rowsContract'>
											<td>{{$group->name}}</td>
											<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("schoolchildren.redirect_group", $group->id)}}?id_test={{$id_test}}'>Направить</button></td>
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
