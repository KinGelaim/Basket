@extends('layouts.header')

@section('title')
	Входящие (куча)
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Отдел управления договорами' OR Auth::User()->hasRole()->role == 'Администрация')
				<div class="content">
					<form>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="select_counterpartie">Выберите контрагента</label>
									<select class="form-control change_select" id="select_counterpartie" href="{{ route('department.reconciliation.incoming') }}" name='counterpartie'>
										<option value="">Все контрагенты</option>
										@if($counterpartie)
											@if($counterparties)
												@foreach($counterparties as $in_counterparties)
													@if($counterpartie == $in_counterparties->id)
														<option selected>{{ $in_counterparties->name }}</option>
													@else
														<option>{{ $in_counterparties->name }}</option>
													@endif
												@endforeach
											@endif
										@else
											@if($counterparties)
												@foreach($counterparties as $in_counterparties)
													<option>{{ $in_counterparties->name }}</option>
												@endforeach
											@endif
										@endif
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<!--<div class="col-md-3">
								<div class="form-group">
									<label for="sel2">Выберите вид работ</label>
									<select class="form-control" id="sel2">
										<option></option>
										@if($viewWork)
											@if($viewWorks)
												@foreach($viewWorks as $in_viewWorks)
													@if($viewWork == $in_viewWorks->name_view_work)
														<option selected>{{ $in_viewWorks->name_view_work }}</option>
													@else
														<option>{{ $in_viewWorks->name_view_work }}</option>
													@endif
												@endforeach
											@endif
										@else
											@if($viewWorks)
												@foreach($viewWorks as $viewWork)
													<option>{{ $viewWork->name_view_work }}</option>
												@endforeach
											@endif
										@endif
									</select>
								</div>
							</div>-->
							<div class="col-md-2">
								<div class="form-group">
									<label for="selSearch">Выберите поле для поиска</label>
									<select class="form-control" id="selSearch" name='search_name'>
										<option></option>
										<option value='number_application' <?php if($search_name == 'number_application') echo 'selected'; ?>>Номер записи</option>
										<option value='number_outgoing' <?php if($search_name == 'number_outgoing') echo 'selected'; ?>>Исх. №</option>
										<option value='date_outgoing' <?php if($search_name == 'date_outgoing') echo 'selected'; ?>>Дата (исх)</option>
										<option value='number_incoming' <?php if($search_name == 'number_incoming') echo 'selected'; ?>>Вх. №</option>
										<option value='date_incoming' <?php if($search_name == 'date_incoming') echo 'selected'; ?>>Дата (вх)</option>
										<option value='theme_application' <?php if($search_name == 'theme_application') echo 'selected'; ?>>Тема</option>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<label >Поиск</label>
								<input class='form-control' type='text' value='{{$search_value}}' name='search_value'/>
							</div>
							<div class="col-md-5">
								<button class="btn btn-primary" type="submit" href="" style="margin-top: 26px;">Поиск</button>
							</div>
						</div>
					</form>
					<div class="row">
						<div class="col-md-12">
							Список писем
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>Номер записи</th>
										<th>Исх. №</th>
										<th>Дата</th>
										<th>Вх. №</th>
										<th>Дата</th>
										<th>Тема</th>
										<th>Просмотреть</th>
										@if(Auth::User()->hasRole()->role != 'Администрация')
											<th>Заявка на заключение Д/К</th>
											<th>Заявка на РКМ и др.</th>
										@endif
									</tr>
								</thead>
								<tbody>
									@foreach($applications as $application)
										<tr class='rowsContract' id_application='{{$application->id}}' >
											<td>
												{{ $application->number_application }}
											</td>
											<td>
												{{ $application->number_outgoing }}
											</td>
											<td>
												{{ $application->date_outgoing ? date('d.m.Y', strtotime($application->date_outgoing)) : '' }}
											</td>
											<td>
												{{ $application->number_incoming }}
											</td>
											<td>
												{{ $application->date_incoming ? date('d.m.Y', strtotime($application->date_incoming)) : '' }}
											</td>
											<td>
												{{ $application->theme_application }}
											</td>
											<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("reconciliation.application.show", $application->id)}}'>Просмотреть</button></td>
											@if(Auth::User()->hasRole()->role != 'Администрация')
												<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("department.reconciliation.create_new_application", $application->id)}}?method=dk' {{ $application->id_contract_application != null || $application->id_document_application != null || $application->id_application_document != null || $application->id_new_application != null  ? 'disabled' : ''}}>Создать</button></td>
												<td><button type='button' class='btn btn-primary btn-href' type='button' href='{{route("department.reconciliation.create_new_application", $application->id)}}?method=rkm' {{ $application->id_contract_application != null || $application->id_document_application != null || $application->id_application_document != null || $application->id_new_application != null  ? 'disabled' : ''}}>Создать</button></td>
											@endif
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						<div class="col-md-12" style="text-align: center;">
							@if($count_paginate)
								<nav aria-label="Page navigation example">
								  <ul class="pagination justify-content-center">
									@if($prev_page)
										<li class="page-item">
										  <a class="page-link" href="?page={{$prev_page}}{{$link}}" tabindex="-1">Предыдущая</a>
										</li>
									@else
										<li class="page-item disabled">
										  <a class="page-link" href="" tabindex="-1">Предыдущая</a>
										</li>
									@endif
									@for($i = 1; $i < $count_paginate+1; $i++)
										@if($i == $page)
											<li class="page-item active"><a class="page-link" href="?page={{$i}}{{$link}}">{{$i}}</a></li>
										@else
											<li class="page-item"><a class="page-link" href="?page={{$i}}{{$link}}">{{$i}}</a></li>
										@endif
									@endfor
									@if($next_page)
										<li class="page-item">
										  <a class="page-link" href="?page={{$next_page}}{{$link}}">Следующая</a>
										</li>
									@else
										<li class="page-item disabled">
										  <a class="page-link" href="">Следующая</a>
										</li>
									@endif
								  </ul>
								</nav>
							@endif
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
