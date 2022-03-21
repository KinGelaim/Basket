@extends('layouts.header')

@section('title')
	Заявки
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Отдел управления договорами' OR Auth::User()->hasRole()->role == 'Второй отдел')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							@if ($method == 'is_contract_new_application')
								Список заявок на заключение Д/К
							@elseif ($method == 'is_rkm_new_application')
								Список заявок на РКМ, заключение ВП МО РФ, запрос цены и иное
							@else
								Список заявок
							@endif
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							@if ($method == 'is_contract_new_application')
								<button class="btn btn-primary btn-href" type="button" href="{{ route('new_applications.create') }}?method={{$method}}" style="float: right;">Добавить заявку на заключение Д/К</button>
							@elseif ($method == 'is_rkm_new_application')
								<button class="btn btn-primary btn-href" type="button" href="{{ route('new_applications.create') }}?method={{$method}}" style="float: right;">Добавить заявку на РКМ и др.</button>
							@else
								<button class="btn btn-primary btn-href" type="button" href="{{ route('new_applications.create') }}?method={{$method}}" style="float: right;">Добавить заявку на заключение Д/К</button>
								<button class="btn btn-primary btn-href" type="button" href="{{ route('new_applications.create') }}?method={{$method}}" style="float: right;">Добавить заявку на РКМ и др.</button>
							@endif
						</div>
					</div>
					<div class="row">
						<form>
							<div class="col-md-6">
								<div class="form-group">
									<label for="sel3">Выберите контрагента</label>
									<select class="form-control" id="sel3" name='counterpartie'>
										<option value=''>Все контрагенты</option>
										@if($counterparties)
											@foreach($counterparties as $in_counterparties)
												@if($counterpartie == $in_counterparties->id)
													<option selected>{{ $in_counterparties->name }}</option>
												@else
													<option>{{ $in_counterparties->name }}</option>
												@endif
											@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="selSearch">Выберите поле для поиска</label>
									<select class="form-control" id="selSearch" name='search_name'>
										<option></option>
										<option value='number_outgoing_new_application' <?php if($search_name == 'number_outgoing_new_application') echo 'selected'; ?>>исх. №</option>
										<option value='number_incoming_new_application' <?php if($search_name == 'number_incoming_new_application') echo 'selected'; ?>>н/Вх. №</option>
										<option value='item_new_application' <?php if($search_name == 'item_new_application') echo 'selected'; ?>>Предмет</option>
										<option value='name_work_new_application' <?php if($search_name == 'name_work_new_application') echo 'selected'; ?>>ИГК (Цель)</option>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<label >Поиск</label>
								<input class='form-control' type='text' value='{{$search_value}}' name='search_value' />
							</div>
							<div class="col-md-1">
								<button class="btn btn-primary" type="submit" href="" style="margin-top: 26px;">Поиск</button>
							</div>
						</form>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>Дата регистрация заявки</th>
										<th>Контрагент</th>
										<th>Заявка Исх. №</th>
										<th>н/Вх.</th>
										<th>Предмет (содержание заявки)</th>
										@if(Auth::User()->hasRole()->role == 'Администратор')
											<th>Удаление</th>
										@endif
									</tr>
								</thead>
								<tbody>
									@foreach($new_applications as $application)
										<tr class='rowsContract cursorPointer btn-href' href="{{ route('new_applications.show', $application->id)}}">
											<td
												<?php
													if($application->date_registration_new_application)
														if(!$application->result_outgoing_new_application)
															if(time() - strtotime($application->date_registration_new_application) > 518400)
																echo "style='color: red;'";
												?>
											>
												{{ $application->date_registration_new_application }}
											</td>
											<td>
												{{ $application->full_name_counterpartie_contract }}
											</td>
											<td>
												{{ $application->number_outgoing_new_application }}
											</td>
											<td>
												{{ $application->number_incoming_new_application }}
											</td>
											<td>
												{{ $application->item_new_application }}
											</td>
											@if(Auth::User()->hasRole()->role == 'Администратор')
												<td class='table_coll_btn'><button type='button' class='btn btn-danger btn-href' type='button' href="{{route('department.reconciliation.delete', 1)}}">Удалить</button></td>
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
