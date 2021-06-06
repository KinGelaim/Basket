@extends('layouts.header')

@section('title')
	Контрагенты
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Отдел управления договорами' OR Auth::User()->hasRole()->role == 'Десятый отдел' OR Auth::User()->hasRole()->role == 'Администрация' OR Auth::User()->hasRole()->role == 'Отдел 22' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Контрагенты</h3>
						</div>
						<div class="col-md-6">
							@includeif('layouts.search', ['search_arr_value'=>['name'=>'Контрагент','name_full'=>'Полное наименование','inn'=>'ИНН']])
						</div>
						<div class="col-md-6" style='margin-top: 10px; text-align: right;'>
							@if(Auth::User()->hasRole()->role != 'Администрация')
								<button class='btn btn-primary btn-href' type='button' href="{{route('counterpartie.create')}}">Добавить контрагента</button>
							@endif
						</div>
					</div>
					<div class="row">
						<div class="col-md-12" style="text-align: center;">
							<nav aria-label="counterpartie_navigation">
							  <ul class="pagination justify-content-center">
								@foreach(range(0,9) as $letter)
									@if($letter_value == iconv('CP1251','UTF-8',$letter))
										<li class="page-item active"><a class="page-link" href="{{'?page=1'  . $link . '&letter=' . $letter}}">{{$letter}}</a></li>
									@else
										<li class="page-item"><a class="page-link" href="{{'?page=1'  . $link . '&letter=' . $letter}}">{{$letter}}</a></li>
									@endif
								@endforeach
								@foreach(range(chr(0xC0),chr(0xDF)) as $letter)
									@if($letter_value == iconv('CP1251','UTF-8',$letter))
										<li class="page-item active"><a class="page-link" href="{{'?page=1'  . $link . '&letter=' . iconv('CP1251','UTF-8',$letter)}}">{{iconv('CP1251','UTF-8',$letter)}}</a></li>
									@else
										<li class="page-item"><a class="page-link" href="{{'?page=1'  . $link . '&letter=' . iconv('CP1251','UTF-8',$letter)}}">{{iconv('CP1251','UTF-8',$letter)}}</a></li>
									@endif
								@endforeach
								</ul>
							</nav>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12" style="text-align: center;">
							@include('layouts.paginate')
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>Контрагент</th>
										<th>Полное наименование</th>
										@if(Auth::User()->hasRole()->role == 'Администратор')
											<th>Куратор ПЭО</th>
										@endif
										<th>Редактировать</th>
										@if(Auth::User()->hasRole()->role == 'Администратор')
											<th>Удалить</th>
										@endif
									</tr>
								</thead>
								<tbody>
									@foreach($counterparties as $counterpartie)
										@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Администрация' OR Auth::User()->hasRole()->role == 'Отдел 22')
											<tr class='rowsContract'>
												<td>{{$counterpartie->name}}</td>
												<td>{{$counterpartie->name_full}}</td>
												@if(Auth::User()->hasRole()->role == 'Администратор')
													<td>{{$counterpartie->FIO}}</td>
												@endif
												<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("counterpartie.edit", $counterpartie->id)}}'>Редактировать</button></td>
												@if(Auth::User()->hasRole()->role == 'Администратор')
													@if($counterpartie->deleted_at)
														<td>Удалено</td>
													@else
														<td><button type='button' class='btn btn-danger btn-href' type='button' href='{{route("counterpartie.delete", $counterpartie->id)}}'>Удалить</button></td>
													@endif
												@endif
											</tr>
										@else
											<tr class='rowsContract'>
												<td>{{$counterpartie->name}}</td>
												<td>{{$counterpartie->name_full}}</td>
												<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("counterpartie.edit", $counterpartie->id)}}'>Редактировать</button></td>
											</tr>
										@endif
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12" style="text-align: center;">
							@include('layouts.paginate')
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
