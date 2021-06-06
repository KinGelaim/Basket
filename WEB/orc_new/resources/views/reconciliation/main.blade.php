@extends('layouts.header')

@section('title')
	Документы согласования
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Администрация')
				<div class="content">
					<form>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="select_counterpartie">Выберите контрагента</label>
									<select class="form-control change_select" id="select_counterpartie" href="{{ route('department.reconciliation') }}" name='counterpartie'>
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
							<div class="col-md-3">
								<div class="form-group">
									<label for="select_view">Выберите вид работ</label>
									<select class="form-control change_select" id="select_view" href="{{ route('department.reconciliation') }}" name='view'>
										<option value="">Все виды работ</option>
										@if($viewContract)
											@if($viewContracts)
												@foreach($viewContracts as $in_viewContracts)
													@if($viewContract == $in_viewContracts->name_view_contract)
														<option selected>{{ $in_viewContracts->name_view_contract }}</option>
													@else
														<option>{{ $in_viewContracts->name_view_contract }}</option>
													@endif
												@endforeach
											@endif
										@else
											@if($viewContracts)
												@foreach($viewContracts as $viewContract)
													<option>{{ $viewContract->name_view_contract }}</option>
												@endforeach
											@endif
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="selSearch">Выберите поле для поиска</label>
									<select class="form-control" id="selSearch" name='search_name'>
										<option></option>
										<option value='number_outgoing' <?php if($search_name == 'number_outgoing') echo 'selected'; ?>>Исх. №</option>
										<option value='date_outgoing' <?php if($search_name == 'date_outgoing') echo 'selected'; ?>>Дата (исх)</option>
										<option value='number_incoming' <?php if($search_name == 'number_incoming') echo 'selected'; ?>>Вх. №</option>
										<option value='date_incoming' <?php if($search_name == 'date_incoming') echo 'selected'; ?>>Дата (вх)</option>
										<option value='theme_application' <?php if($search_name == 'theme_application') echo 'selected'; ?>>Тема</option>
										<option value='number_contract' <?php if($search_name == 'number_contract') echo 'selected'; ?>>Номер договора</option>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<label >Поиск</label>
								<input class='form-control' type='text' value='{{$search_value}}' name='search_value'/>
							</div>
							<div class="col-md-1">
								<button class="btn btn-primary" type="submit" href="" style="margin-top: 26px;">Поиск</button>
							</div>
							<div class="col-md-2" style='text-align: right;'>
								<button class="btn btn-primary btn-href-select" type="button" href="{{ route('department.reconciliation.incoming') }}" style="margin-top: 26px;">Входящие</button>
							</div>
							<div class="col-md-1" style='text-align: left;'>
								<button class="btn btn-primary btn-href-select" type="button" href="{{ route('department.ekonomic.sip') }}?view=02&sorting=cast_number_pp&sort_p=desc" style="margin-top: 26px;">Договоры</button>
							</div>
						</div>
					</form>
					<div class="row">
						<div class="col-md-12">
							Список заявок
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>Исх. №</th>
										<th>Дата</th>
										<th>Вх. №</th>
										<th>Дата</th>
										<th>Тема</th>
										<th>Контрагент</th>
										<th>№ заявки</th>
										<th>Дата</th>
										<th>Номер договора НТИИМ</th>
										<th>Вид договора</th>
										@if(Auth::User()->hasRole()->role != 'Администрация')
											<th>Удаление</th>
										@endif
									</tr>
								</thead>
								<tbody>
									@foreach($documents as $key=>$value)
										<tr class='rowsContract rowsContractClick cursorPointer' number_document='{{$key}}' href="{{ route('department.reconciliation.document', $key)}}">
											<td>
												{{ $value[0]->number_outgoing }}
											</td>
											<td>
												{{ $value[0]->date_outgoing ? date('d.m.Y', strtotime($value[0]->date_outgoing)) : '' }}
											</td>
											<td>
												{{ $value[0]->number_incoming }}
											</td>
											<td>
												{{ $value[0]->date_incoming ? date('d.m.Y', strtotime($value[0]->date_incoming)) : '' }}
											</td>
											<td>
												{{ $value[0]->theme_application }}
											</td>
											<td>
												{{ $value[0]->name_counterpartie_contract }}
											</td>
											<td>
												{{ $value[0]->number_application }}
											</td>
											<td>
												{{ $value[0]->date_document }}
											</td>
											<td>
												@foreach($value[0]->contracts as $key2=>$value2)
													{{ $value2->number_contract }} <br/>
												@endforeach
											</td>
											<td>
												@foreach($value[0]->contracts as $key2=>$value2)
													{{ $value2->name_view_contract }} <br/>
												@endforeach
											</td>
											@if(Auth::User()->hasRole()->role != 'Администрация')
												<td class='table_coll_btn'><button type='button' class='btn btn-danger btn-href' type='button' href="{{route('department.reconciliation.delete', $key)}}">Удалить</button></td>
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
