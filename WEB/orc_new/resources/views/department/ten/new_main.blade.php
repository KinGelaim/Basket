@extends('layouts.header')

@section('title')
	Десятый отдел
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Десятый отдел' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									Информация о комплектациях
								</div>
								<div class="panel-body" style='max-height: 700px; overflow-y: auto;'>
									<ul class='nav nav-tabs'>
										<li class='<?php if(isset($_COOKIE["TenCookie"])) if($_COOKIE["TenCookie"]=='contracts') echo 'active';?>'>
											<a data-toggle='tab' href='#contracts' ajax-href="{{ route('ajax.set_cookie') }}">Договоры</a>
										</li>
										<li class='<?php if(isset($_COOKIE["TenCookie"])) if($_COOKIE["TenCookie"]=='contracts_sip') echo 'active';?>'>
											<a data-toggle='tab' href='#contracts_sip' ajax-href="{{ route('ajax.set_cookie') }}">Договоры ПЭО</a>
										</li>
										<li class='<?php if(isset($_COOKIE["TenCookie"])) if($_COOKIE["TenCookie"]=='contracts_components') echo 'active';?>'>
											<a data-toggle='tab' href='#contracts_components' ajax-href="{{ route('ajax.set_cookie') }}">Договоры с комплектацией</a>
										</li>
										<li class='<?php if(isset($_COOKIE["TenCookie"])) if($_COOKIE["TenCookie"]=='all_components') echo 'active';?>'>
											<a data-toggle='tab' href='#all_components' ajax-href="{{ route('ajax.set_cookie') }}">Внутренняя комплектация</a>
										</li>
										<li class='<?php if(isset($_COOKIE["TenCookie"])) if($_COOKIE["TenCookie"]=='components') echo 'active';?>'>
											<a data-toggle='tab' href='#components' ajax-href="{{ route('ajax.set_cookie') }}">Внешняя комплектация</a>
										</li>
										<li class='<?php if(isset($_COOKIE["TenCookie"])) if($_COOKIE["TenCookie"]=='guids') echo 'active';?>'>
											<a data-toggle='tab' href='#guids' ajax-href="{{ route('ajax.set_cookie') }}">Справочники</a>
										</li>
									</ul>
									<div class='tab-content'>
										<div id='contracts' class='tab-pane fade <?php if(isset($_COOKIE["TenCookie"])) if($_COOKIE["TenCookie"]=='contracts') echo 'in active';?>'>
											<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
												<thead>
													<tr>
														<th>№ договора НТИИМ</th>
														<th>№ договора (контрагент)</th>
														<th>Вид работ</th>
														<th>Предмет договора</th>
														<th>Контрагент</th>
														<th>Начальная сумма</th>
														<th>Окончательная сумма</th>
														<th>Срок исполнения</th>
													</tr>
												</thead>
												<tbody>
													@foreach($contracts as $contract)
														<tr class='rowsContract cursorPointer btn-href' href="{{ route('department.peo.show_contract', $contract->id)}}">
															<td>
																{{ $contract->number_contract }}
															</td>
															<td>
																{{ $contract->number_counterpartie_contract_reestr }}
															</td>
															<td>
																{{ $contract->name_view_work }}
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
										<div id='contracts_sip' class='tab-pane fade <?php if(isset($_COOKIE["TenCookie"])) if($_COOKIE["TenCookie"]=='contracts_sip') echo 'in active';?>'>
											<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
												<thead>
													<tr>
														<th>№ договора НТИИМ</th>
														<th>№ договора (контрагент)</th>
														<th>Вид работ</th>
														<th>Предмет договора</th>
														<th>Контрагент</th>
														<th>Начальная сумма</th>
														<th>Окончательная сумма</th>
														<th>Срок исполнения</th>
													</tr>
												</thead>
												<tbody>
													@foreach($contracts_sip as $contract)
														<tr class='rowsContract cursorPointer btn-href' href="{{ route('department.ekonomic.contract_new_reestr', $contract->id)}}">
															<td>
																{{ $contract->number_contract }}
															</td>
															<td>
																{{ $contract->number_counterpartie_contract_reestr }}
															</td>
															<td>
																{{ $contract->name_view_work }}
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
										<div id='contracts_components' class='tab-pane fade <?php if(isset($_COOKIE["TenCookie"])) if($_COOKIE["TenCookie"]=='contracts_components') echo 'in active';?>'>
											<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
												<thead>
													<tr>
														<th>№ договора НТИИМ</th>
														<th>№ договора (контрагент)</th>
														<th>Вид работ</th>
														<th>Предмет договора</th>
														<th>Контрагент</th>
														<th>Начальная сумма</th>
														<th>Окончательная сумма</th>
														<th>Срок исполнения</th>
													</tr>
												</thead>
												<tbody>
													@foreach($contracts_components as $contract)
														<tr class='rowsContract cursorPointer btn-href' href="{{ route('department.ekonomic.contract_new_reestr', $contract->id)}}">
															<td>
																{{ $contract->number_contract }}
															</td>
															<td>
																{{ $contract->number_counterpartie_contract_reestr }}
															</td>
															<td>
																{{ $contract->name_view_work }}
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
										<div id='all_components' class='tab-pane fade <?php if(isset($_COOKIE["TenCookie"])) if($_COOKIE["TenCookie"]=='all_components') echo 'in active';?>'>
											<div class='row'>
												<!--<div class="col-md-12">
													<button type='button' class='btn btn-primary btn-href' type='button' href="{{route('ten.create_component')}}">Добавить комплектацию</button>
												</div>-->
											</div>
											<table class="table" style='margin: 0 auto; margin-bottom: 10px;'>
												<thead>
													<tr>
														<th>Элемент</th>
														<th>Необходимое количество</th>
														<th>Граф</th>
														<th>Контаркт</th>
														<th>Редактирование</th>
														@if(Auth::User()->hasRole()->role == 'Администратор')
															<th>Удаление</th>
														@endif
													</tr>
												</thead>
													<?php $count_rows = 1; ?>
													@foreach($results as $key=>$value)
														<tbody>
															<tr class="rowsContract clickable" data-toggle='collapse' data-target='#group-of-rows-{{$count_rows}}' aria-expanded='false' aria-controls='group-of-rows-{{$count_rows}}'>
																<td>{{$key}}</td>
																<td>{{$results_count[$key]}}</td>
																<td>
																	<button type='button' class='btn btn-secondary btn-href' type='button' href="{{route('tree_map.show_component', $value[0]->id_element_component)}}" disabled>Граф</button>
																</td>
																<td></td>
																<td>
																	
																</td>
																@if(Auth::User()->hasRole()->role == 'Администратор')
																	<td>
																		
																	</td>
																@endif
															</tr>
														</tbody>
														<tbody id='group-of-rows-{{$count_rows}}' class='collapse' style='background-color: #E7E7E7;'>
															@foreach($value as $component)
																<tr>
																	<td>{{$component->name_component}}</td>
																	<td>{{$component->need_count}}</td>
																	<td>
																		
																	</td>
																	<td>
																		<!-- Контракты -->
																		@foreach($component->contracts_id as $contract_id)
																			<a href="{{route('department.reconciliation.show', $contract_id->id_contract)}}">{{$contract_id->number_contract}}</a><br/>
																		@endforeach
																	</td>
																	<td>
																		@if($component->id_party == null)
																			<button type='button' class='btn btn-primary btn-href' type='button' href="{{route('ten.edit_small_component', $component->id)}}">Редактировать</button>
																		@endif
																	</td>
																	@if(Auth::User()->hasRole()->role == 'Администратор')
																		<td>
																			<button type='button' class='btn btn-danger btn-href' type='button' href='{{route("ten.delete_component",$component->id)}}'>Удалить</button>
																		</td>
																	@endif
																</tr>
															@endforeach
														</tbody>
														<?php $count_rows++; ?>
													@endforeach
											</table>
										</div>
										<div id='components' class='tab-pane fade <?php if(isset($_COOKIE["TenCookie"])) if($_COOKIE["TenCookie"]=='components') echo 'in active';?>'>
											<table class="table" style='margin: 0 auto; margin-bottom: 10px;'>
												<thead>
													<tr>
														<th>Элемент</th>
														<th>Необходимое количество</th>
														<th>Количество на складе</th>
														<th>Количество необходимое для заказа</th>
														<th>Процесс</th>
														<th>Редактирование</th>
														@if(Auth::User()->hasRole()->role == 'Администратор')
															<th>Удаление</th>
														@endif
													</tr>
												</thead>
												<tbody>
													@foreach($packs as $component)
														<tr class="rowsContract">
															<td>{{$component->name_component}}</td>
															<td>{{$component->need_count}}</td>
															<td>{{$component->count_element}}</td>
															<td>{{$component->need_count_element}}</td>
															<td><?php if($component->check_complete == 0) echo 'Запущен'; else echo 'Завершен'; ?></td>
															<td>
																<button type='button' class='btn btn-primary btn-href' type='button' href="{{route('ten.edit_component_pack', $component->id)}}">Редактировать</button>
															</td>
															@if(Auth::User()->hasRole()->role == 'Администратор')
																<td>
																	<button type='button' class='btn btn-danger btn-href' type='button' href='{{route("ten.delete_pack",$component->id)}}'>Удалить</button>
																</td>
															@endif
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
										<div id='guids' class='tab-pane fade <?php if(isset($_COOKIE["TenCookie"])) if($_COOKIE["TenCookie"]=='guids') echo 'in active';?>'>
											<div class='row'>
												<div class="col-md-12">
													<button class='btn btn-primary btn-href' type='button' href="{{ route('counterpartie.main') }}" style='width: 195px;'>Страница контрагентов</button>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													<button class='btn btn-primary btn-href' type='button' href="{{ route('ten.element_main') }}" style='width: 195px;'>Страница комплектующих</button>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													<button class='btn btn-primary btn-href' type='button' href="{{ route('ten.party_element_main') }}" style='width: 195px;'>Страница склада</button>
												</div>
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
