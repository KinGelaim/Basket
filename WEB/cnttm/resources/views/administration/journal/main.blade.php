@extends('layouts.header')

@section('title')
	Журналы
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Преподаватель')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Журналы</h3>
						</div>
						<div class="col-md-12" style='margin-top: 10px;'>
							<button class='btn btn-primary btn-href' type='button' href="{{route('journal.create', $id_group)}}">Добавить журнал</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>Название</th>
										<th>Просмотреть</th>
										<th>Удалить</th>
									</tr>
								</thead>
								<tbody>
									@foreach($journals as $journal)
										<tr class='rowsContract'>
											<td>{{$journal->name}}</td>
											<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("journal.show", $journal->id)}}'>Просмотр</button></td>
											<td><button type='button' class='btn btn-danger btn-href' type='button' href='{{route("journal.delete", $journal->id)}}'>Удалить</button></td>
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
