@extends('layouts.header')

@section('title')
	Внешняя комплектация
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Десятый отдел')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							Информация о внешней комплектации
						</div>
					</div>
					<div id='divMessage' class="row">
					</div>
					<div class='row'>
						<div class="col-md-3">
							<div class='row'>
								<div class="col-md-4">
									
								</div>
								<div class="col-md-8">
									@if($pack->check_complete == 0)
										<input id='check_complete' name='check_complete' class='form-check-input' type="checkbox" ajax-href="{{ route('ten.change_complete', $pack->id)}}"/>
									@else
										<input id='check_complete' name='check_complete' class='form-check-input' type="checkbox" ajax-href="{{ route('ten.change_complete', $pack->id)}}" checked />
									@endif
									<label for='check_complete'>Процесс завершен</label>
								</div>
							</div>
							<div class='row'>
								<div class="col-md-4">
									<label>Элемент</label>
								</div>
								<div class="col-md-8">
									<select class="form-control" name='element' required>
										<option value=""></option>
										@if($elements)
											@foreach($elements as $in_elements)
												@if($in_elements->id == $pack->id_element)
													<option value='{{$in_elements->id}}' selected>{{ $in_elements->name_component }}</option>
												@else
													<option value='{{$in_elements->id}}'>{{ $in_elements->name_component }}</option>
												@endif
											@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class='row'>
								<div class="col-md-4">
									<label>Необходимое количество</label>
								</div>
								<div class="col-md-8">
									<input class='form-control' type='text' value='{{$need_count_element}}'/>
								</div>
							</div>
							<div class='row'>
								<div class="col-md-4">
									<label>Количество на складе</label>
								</div>
								<div class="col-md-8">
									<input class='form-control' type='text' value='{{$element->count_element}}'/>
								</div>
							</div>
							<div class='row'>
								<div class="col-md-4">
									<label>Необходимое количество для заказа</label>
								</div>
								<div class="col-md-8">
									<input class='form-control' type='text' value='{{$element->need_count_element}}'/>
								</div>
							</div>
						</div>
						<div class="col-md-9">
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-primary btn-href" type="button" href="{{ route('ten.create_contract', $pack->id) }}" style="float: right; margin-right: 10px;">Добавить договор</button>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="panel-body" style='max-height: 700px; overflow-y: auto;'>
										<ul class='nav nav-tabs'>
											<li class='active'>
												<a data-toggle='tab' href='#contracts'>Договоры</a>
											</li>
											<li>
												<a data-toggle='tab' href='#all_components'>Внутренняя комплектации</a>
											</li>
										</ul>
										<div class='tab-content'>
											<div id='contracts' class='tab-pane fade in active'>
												<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
													<thead>
														<tr>
															<th>№ договора НТИИМ</th>
															<th>№ договора (контрагент)</th>
															<th>Вид договора</th>
															<th>Предмет договора</th>
															<th>Контрагент</th>
															<th>Начальная сумма</th>
															<th>Окончательная сумма</th>
															<th>Срок исполнения</th>
														</tr>
													</thead>
													<tbody>
														@foreach($contracts as $contract)
															<tr class='rowsContract cursorPointer btn-href' href="{{ route('department.reconciliation.show', $contract->id)}}">
																<td>
																	{{ $contract->number_contract }}
																</td>
																<td>
																	{{ $contract->number_counterpartie_contract_reestr }}
																</td>
																<td>
																	{{ $contract->name_view_contract }}
																</td>
																<td>
																	{{ $contract->name_work_contract }}
																</td>
																<td>
																	{{ $contract->name_counterpartie_contract }}
																</td>
																<td>
																	{{ $contract->amount_reestr }} <br/>
																</td>
																<td>
																	{{ $contract->amount_contract_reestr }} <br/>
																</td>
																<td>
																	{{ $contract->date_maturity_date_reestr ? $contract->date_maturity_date_reestr : $contract->date_maturity_reestr }} <br/>
																</td>
															</tr>
														@endforeach
													</tbody>
												</table>
											</div>
											<div id='all_components' class='tab-pane fade'>
												<table class="table" style='margin: 0 auto; margin-bottom: 10px;'>
													<thead>
														<tr>
															<th>Элемент</th>
															<th>Необходимое количество</th>
															<th>Количество на складе</th>
															<th>Редактирование</th>
															@if(Auth::User()->hasRole()->role == 'Администратор')
																<th>Удаление</th>
															@endif
														</tr>
													</thead>
													<tbody>
														@foreach($components as $component)
															<tr class="rowsContract">
																<td>{{$component->name_component}}</td>
																<td>{{$component->need_count}}</td>
																<td>{{$component->count_element}}</td>
																<td>
																	<button type='button' class='btn btn-primary btn-href' type='button' href="{{route('ten.edit_small_component', $component->id)}}">Редактировать</button>
																</td>
																@if(Auth::User()->hasRole()->role == 'Администратор')
																	<td>
																		<button type='button' class='btn btn-danger btn-href' type='button' href='{{route("ten.delete_component",$component->id)}}'>Удалить</button>
																	</td>
																@endif
															</tr>
														@endforeach
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
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
