@extends('layouts.header')

@section('title')
	Второй отдел
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Второй отдел' OR Auth::User()->hasRole()->role == 'Второй отдел (просмотр)' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Администрация' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
				@if (Route::has('login'))
					<div class="top-right links">
						
					</div>
				@endif
				
				@if($contract)
					<div class="content">
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<label for='numberContract1' style='font-size: 11px;'>Номер Д/К ф-л "НТИИМ"(ФКП "НТИИМ")</label>
									<input id='numberContract1' class='form-control' type='text' value='{{old("number_contract") ? old("number_contract") : $contract->number_contract}}' readonly />
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for='numberContract2'>Номер Д/К Контрагента</label>
									<input id='numberContract2' class='form-control' type='text' value='{{old("number_counterpartie_contract_reestr") ? old("number_counterpartie_contract_reestr") : $contract->number_counterpartie_contract_reestr}}' readonly />
								</div>
							</div>
							@if(Auth::User()->hasRole()->role != 'Планово-экономический отдел')
								<div class="col-md-2">
									<div class='row'>
										<div class="col-md-6" style='padding: 0px;'>
											<div class='form-check'>
												@if($contract->name_works_goz == "ГОЗ")
													<input class='form-check-input' type="checkbox" checked disabled />
												@else
													<input class='form-check-input' type="checkbox" disabled />
												@endif
												<label class='form-check-label' for='gozCheck'>ГОЗ</label>
											</div>
										</div>
										<div class="col-md-6" style='padding: 0px;'>
											<div class='form-check'>
												@if($contract->name_works_goz == "Экспорт")
													<input class='form-check-input' type="checkbox" checked disabled />
												@else
													<input class='form-check-input' type="checkbox" disabled />
												@endif
												<label class='form-check-label' for='exportCheck'>Экспорт</label>
											</div>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-6" style='padding: 0px;'>
											<div class='form-check'>
												@if($contract->name_works_goz == "Межзаводские")
													<input class='form-check-input' type="checkbox" checked disabled />
												@else
													<input class='form-check-input' type="checkbox" disabled />
												@endif
												<label class='form-check-label' for='interfactoryCheck'>Межзавод.</label>
											</div>
										</div>
										<div class="col-md-6" style='padding: 0px;'>
											<div class='form-check'>
												@if($contract->name_works_goz == "Иные")
													<input class='form-check-input' type="checkbox" checked disabled />
												@else
													<input class='form-check-input' type="checkbox" disabled />
												@endif
												<label class='form-check-label' for='otherCheck'>Иные</label>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="sel1">Вид договора</span></label>
										<select class="form-control" id="sel1" disabled>
											<option>{{$contract->name_view_contract}}</option>
										</select>
									</div>
								</div>
								<div class="col-md-2">
									<label class='small-text'>Ответственный исполнитель</span></label>
									<input class='form-control' type='text' value='{{$contract->name_executor ? $contract->name_executor : $contract->executor_contract_reestr}}' disabled />
								</div>
								<div class="col-md-1" style='text-align: right; margin-top: 25px;'>
									<button class='btn btn-primary' data-toggle="modal" data-target="#scan" type='button'>Сканы</button>
								</div>
							@endif
						</div>
						<div class="row">
							<div class="col-md-2">
								<label>Дата Дог./Контр. на 1 л.</label>
							</div>
							<div class="col-md-5">
								<label for='sel2'>Название предприятия</label>
							</div>
							<div class="col-md-3">
								<label>ИГК</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<input name='date_contract_on_first_reestr' class='form-control' type='text' value="{{$contract->date_contract_on_first_reestr}}" readonly />
							</div>
							<div class="col-md-5">
								<div class="form-group">
									<select class="form-control" id="sel2" disabled>
										<option>{{ $contract->name_counterpartie_contract }}</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<input name='igk_reestr' class='form-control' type='text' value="{{$contract->igk_reestr}}" readonly />
							</div>
							<div class="col-md-2">
								<div class='form-check'>
									@if(count($states) > 0)
										@if($states[count($states) - 1]->name_state == "Заключен" OR $states[count($states) - 1]->name_state == "Заключён")
											<input id='completeContract' class='form-check-input completeCheck' type="checkbox" checked disabled />
										@else
											<input id='completeContract' class='form-check-input completeCheck' type="checkbox" disabled />
										@endif
									@else
										<input id='completeContract' class='form-check-input completeCheck' type="checkbox" disabled />
									@endif
									<label class='form-check-label' for='completeContract'>Заключен</label>
								</div>
							</div>
						</div>
						@if(Auth::User()->hasRole()->role != 'Планово-экономический отдел')
							<div class="row">
								<div class="col-md-10">
									<div class="row">
										<div class="col-md-2">
											<div class="row">
												<div class="col-md-12">
													<div class='form-group'>
														<label for='nameWork1'>Предмет</label>
														<textarea id='nameWork1' class='form-control' type="text" style="width: 100%;" rows='5' disabled>{{$contract->item_contract}}</textarea>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-2">
											<div class="row">
												<div class="col-md-12">
													<div class='form-group'>
														<label for='nameWork1'>Цель</label>
														<textarea id='nameWork1' class='form-control' type="text" style="width: 100%;" rows='5' disabled>{{$contract->name_work_contract}}</textarea>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<label>Срок исполнения</label>
														<input class='form-control' type='text' value="{{$contract->date_maturity_reestr}}" readonly />
														<label for='date_test'>До</label>
														<input class='form-control' type='text' value="{{$contract->date_e_maturity_reestr}}" readonly />
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-5">
											<div class="row">
												<div class="col-md-6">
													<label class='small-text'>Цена при заключении Д/К</label>
													<input class='form-control' type='text' value="{{is_numeric($contract->amount_begin_reestr) ? number_format($contract->amount_begin_reestr, 2, '.', ' ') : $contract->amount_begin_reestr}}" readonly />
												</div>
												<div class="col-md-6">
													<div class="row">
														<div class="col-md-12">
															@if($contract->approximate_amount_begin_reestr)
																<input id='approximate_amount_begin_reestr' class='form-check-input' type="checkbox" checked disabled />
																<label for='approximate_amount_begin_reestr'>Ориентировочная</label>
															@elseif($contract->fixed_amount_begin_reestr)
																<input id='fixed_amount_begin_reestr' class='form-check-input' type="checkbox" checked disabled />
																<label for='fixed_amount_begin_reestr'>Фиксированная</label>
															@endif
														</div>
													</div>
													<div class="row">
														<div class="col-md-12">
															@if($contract->vat_begin_reestr)
																<input id='vat_begin_reestr' class='form-check-input' type="checkbox" checked disabled />
															@else
																<input id='vat_begin_reestr' class='form-check-input' type="checkbox" disabled />
															@endif
															<label for='vat_begin_reestr'>НДС</label>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<label>Сумма Д/К</label>
													<input class='form-control' type='text' value="{{is_numeric($contract->amount_reestr) ? number_format($contract->amount_reestr, 2, '.', ' ') : $contract->amount_reestr}}" readonly />
												</div>
												<div class="col-md-6">
													<div class="row">
														<div class="col-md-12">
															@if($contract->approximate_amount_reestr)
																<input id='approximate_amount_reestr' class='form-check-input' type="checkbox" checked disabled />
																<label for='approximate_amount_reestr'>Ориентировочная</label>
															@elseif($contract->fixed_amount_reestr)
																<input id='fixed_amount_reestr' class='form-check-input' type="checkbox" checked disabled />
																<label for='fixed_amount_reestr'>Фиксированная</label>
															@endif
														</div>
													</div>
													<div class="row">
														<div class="col-md-12">
															@if($contract->vat_reestr)
																<input id='vat_reestr' class='form-check-input' type="checkbox" checked disabled />
															@else
																<input id='vat_reestr' class='form-check-input' type="checkbox" disabled />
															@endif
															<label for='vat_reestr'>НДС</label>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-3">
													<label for='date_b_contract_reestr'>Срок действия ДК с</label>
												</div>
												<div class="col-md-3">
													<label for='date_e_contract_reestr'>по</label>
												</div>
											</div>
											<div class="row">
												<div class="col-md-3">
													<input class='form-control' type='text' value="{{$contract->date_b_contract_reestr}}" readonly />
												</div>
												<div class="col-md-3">
													<input class='form-control' type='text' value="{{$contract->date_e_contract_reestr}}" readonly />
												</div>
												<div class="col-md-6">
													<input class='form-control' type='text' value="{{$contract->date_contract_reestr}}" readonly />
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<div class="row">
										<div class="col-md-12" style='text-align: right;'>
											<button class='btn btn-primary' data-toggle="modal" data-target="#history_states" type='button' style='width: 150px; float: right;'>История Д/К</button>
										</div>
										<div class="col-md-12" style='text-align: right;'>
											<button class='btn btn-primary' data-toggle="modal" data-target="#invoice" type='button' style='width: 150px; float: right; margin-top: 5px;'>Расчёты по Д/К</button>
										</div>
										<div class="col-md-12" style='text-align: right;'>
											<button type='button' class="btn btn-primary" style="float: right; width: 150px; margin-top: 5px;" data-toggle="modal" data-target="#work_states">Выполнение работ</button>
										</div>
										<div class="col-md-12" style='text-align: right;'>
											<button type='button' class="btn btn-primary btn-href" style="float: right; width: 150px; margin-top: 5px;" href="{{route('department.peo.show_additional_documents', $contract->id)}}">Догов. мат</button>
										</div>
										@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел')
											@if($contract->name_view_contract == 'Услуги ГН' || $contract->name_view_contract == 'Услуги ВН')
												<div class="col-md-12" style='text-align: right;'>
													<button type='button' class="btn btn-primary" data-toggle="modal" data-target="#modalContractActs"  style="float: right; width: 150px; margin-top: 5px;">Акты выполнения</button>
												</div>
											@endif
										@endif
									</div>
								</div>
							</div>
						@endif
						<div class="row">
							<div class="col-md-12" style="text-align: center;">
								<ul class='nav nav-tabs'>
									<!--<li class='active'>
										<a data-toggle='tab' href='#planirovka'>Планирование</a>
									</li>-->
									<li class='active'>
										<a data-toggle='tab' href='#second_naryad'>Наряды</a>
									</li>
								</ul>
								<div class='tab-content'>
									<!--<div id='planirovka' class='tab-pane fade in active'>
										<div class='row' style="text-align: left; margin-top: 10px;">
											<div class="col-md-12">
												@if($contract->name_view_work == 'Контрольные испытания')
													<button class='btn btn-primary' data-toggle="modal" data-target="#newIsp">Добавить испытание</button>
												@elseif($contract->name_view_work == 'Опытные испытания')
													<button class='btn btn-primary' >Добавить ОКР</button>
												@elseif($contract->name_view_work == 'Сборка')
													<button class='btn btn-primary' data-toggle="modal" data-target="#newSb">Добавить сборку</button>
												@endif
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												@if($secondDepartments)
													@if($contract->name_view_work == 'Контрольные испытания')
														<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
															<thead>
																<tr>
																	<th>Изделие</th>
																	<th>Вид испытыния</th>
																	<th>Январь</th>
																	<th>Февраль</th>
																	<th>Март</th>
																	<th>Апрель</th>
																	<th>Май</th>
																	<th>Июнь</th>
																	<th>Июль</th>
																	<th>Август</th>
																	<th>Сентябрь</th>
																	<th>Октябрь</th>
																	<th>Ноябрь</th>
																	<th>Декабрь</th>
																	<th>ГОД</th>
																	<th>Выполнение</th>
																	<th>Удаление</th>
																</tr>
															</thead>
															<tbody>
																@foreach($secondDepartments as $isp)
																	<tr class="rowsContract cursorPointer rowsUpdateIsp" id_invoice='{{$isp->id}}' href="{{route('department.second_isp.update', $isp->id)}}" comment_isp="{{route('department.second.new_comment', $isp->id)}}" data-isp='{{$isp}}'>
																		<td>
																			{{ $isp->name_element }}
																		</td>
																		<td>
																			{{ $isp->name_view_work_elements }}
																		</td>
																		<td>
																			{{ $isp->january }}
																		</td>
																		<td>
																			{{ $isp->february }}
																		</td>
																		<td>
																			{{ $isp->march }}
																		</td>
																		<td>
																			{{ $isp->april }}
																		</td>
																		<td>
																			{{ $isp->may }}
																		</td>
																		<td>
																			{{ $isp->june }}
																		</td>
																		<td>
																			{{ $isp->july }}
																		</td>
																		<td>
																			{{ $isp->august }}
																		</td>
																		<td>
																			{{ $isp->september }}
																		</td>
																		<td>
																			{{ $isp->october }}
																		</td>
																		<td>
																			{{ $isp->november }}
																		</td>
																		<td>
																			{{ $isp->december }}
																		</td>
																		<td>
																			{{ $isp->year }}
																		</td>
																		<td>
																			@if(($isp->january != null && $isp->january_check == 1 || $isp->january == null) && ($isp->february != null && $isp->february_check == 1 || $isp->february == null)
																					&& ($isp->march != null && $isp->march_check == 1 || $isp->march == null)
																					&& ($isp->april != null && $isp->april_check == 1 || $isp->april == null)
																					&& ($isp->may != null && $isp->may_check == 1 || $isp->may == null)
																					&& ($isp->june != null && $isp->june_check == 1 || $isp->june == null)
																					&& ($isp->july != null && $isp->july_check == 1 || $isp->july == null)
																					&& ($isp->august != null && $isp->august_check == 1 || $isp->august == null)
																					&& ($isp->september != null && $isp->september_check == 1 || $isp->september == null)
																					&& ($isp->october != null && $isp->october_check == 1 || $isp->october == null)
																					&& ($isp->november != null && $isp->november_check == 1 || $isp->november == null)
																					&& ($isp->december != null && $isp->december_check == 1 || $isp->december == null))
																				Выполнено
																			@else
																				Не выполнено
																			@endif
																		</td>
																		<td><button type='button' class='btn btn-danger btn-href' type='button' href='{{route("department.second.delete",$isp->id)}}'>Удалить</button></td>
																	</tr>
																@endforeach
															</tbody>
														</table>
													@elseif($contract->name_view_work == 'Опытные испытания')
														<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
															<thead>
																<tr>
																	<th>Изделие</th>
																	<th>Программа</th>
																	<th>ГОД</th>
																	<th>Выполнение</th>
																</tr>
															</thead>
															<tbody>
																@foreach($secondDepartments as $exp)
																	<tr class="rowsContract cursorPointer">
																		
																	</tr>
																@endforeach
															</tbody>
														</table>
													@elseif($contract->name_view_work == 'Сборка')
														<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
															<thead>
																<tr>
																	<th>Изделие</th>
																	<th>Количество</th>
																	<th>ГОД</th>
																	<th>Выполнение</th>
																	<th>Удаление</th>
																</tr>
															</thead>
															<tbody>
																@foreach($secondDepartments as $sb)
																	<tr class="rowsContract cursorPointer rowsUpdateIsp" id_invoice='{{$sb->id}}' href="{{route('department.second_sb.update', $sb->id)}}" data-isp='{{$sb}}'>
																		<td>
																			{{ $sb->name_element }}
																		</td>
																		<td>
																			{{ $sb->january + $sb->february + $sb->march + $sb->april + $sb->may + $sb->june + $sb->july + $sb->august + $sb->september + $sb->october + $sb->november + $sb->december }}
																		</td>
																		<td>
																			{{ $sb->year }}
																		</td>
																		<td>
																			@if(($sb->january != null && $sb->january_check == 1 || $sb->january == null) && ($sb->february != null && $sb->february_check == 1 || $sb->february == null)
																					&& ($sb->march != null && $sb->march_check == 1 || $sb->march == null)
																					&& ($sb->april != null && $sb->april_check == 1 || $sb->april == null)
																					&& ($sb->may != null && $sb->may_check == 1 || $sb->may == null)
																					&& ($sb->june != null && $sb->june_check == 1 || $sb->june == null)
																					&& ($sb->july != null && $sb->july_check == 1 || $sb->july == null)
																					&& ($sb->august != null && $sb->august_check == 1 || $sb->august == null)
																					&& ($sb->september != null && $sb->september_check == 1 || $sb->september == null)
																					&& ($sb->october != null && $sb->october_check == 1 || $sb->october == null)
																					&& ($sb->november != null && $sb->november_check == 1 || $sb->november == null)
																					&& ($sb->december != null && $sb->december_check == 1 || $sb->december == null))
																				Выполнено
																			@else
																				Не выполнено
																			@endif
																		</td>
																		<td><button type='button' class='btn btn-danger btn-href' type='button' href='{{route("department.second.delete",$sb->id)}}'>Удалить</button></td>
																	</tr>
																@endforeach
															</tbody>
														</table>
													@endif
												@endif
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
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
																<li class="page-item active"><a class="page-link" href="?page={{$i}}">{{$i}}</a></li>
															@else
																<li class="page-item"><a class="page-link" href="?page={{$i}}">{{$i}}</a></li>
															@endif
														@endfor
														@if($next_page)
															<li class="page-item">
															  <a class="page-link" href="?page={{$next_page}}">Следующая</a>
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
									</div>-->
									<div id='second_naryad' class='tab-pane fade in active'>
										@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Второй отдел')
											<div class='row' style="text-align: left; margin-top: 10px;">
												@if(trim($contract->name_view_contract) == 'Испытания опытные')
													<div class="col-md-2">
														<button class='btn btn-primary btn-href' type='button' href='{{route("department.second.new_tour_of_duty_exp", $contract->id)}}'>Добавить опытный наряд</button>
													</div>
												@elseif(trim($contract->name_view_contract) == 'Сборка')
													<div class="col-md-2">
														<button class='btn btn-primary btn-href' type='button' href='{{route("department.second.new_tour_of_duty_sb", $contract->id)}}'>Добавить наряд на сборку</button>
													</div>
												@elseif(trim($contract->name_view_contract) != 'Услуги ВН' AND trim($contract->name_view_contract) != 'Услуги ГН')
													<div class="col-md-2">
														<button class='btn btn-primary btn-href' type='button' href='{{route("department.second.new_tour_of_duty", $contract->id)}}'>Добавить наряд на испытание</button>
													</div>
												@endif
												<div class="col-md-2">
													<button class='btn btn-primary btn-href' type='button' href='{{route("department.second.new_tour_of_duty_us", $contract->id)}}'>Добавить наряд на услугу</button>
												</div>
											</div>
										@endif
										<div class='row'>
											<div class="col-md-12">
												@if(trim($contract->name_view_contract) != 'Сборка' AND trim($contract->name_view_contract) != 'Услуги ГН' AND trim($contract->name_view_contract) != 'Услуги ВН' AND trim($contract->name_view_contract) != 'Испытания опытные')
													@if($secondDepartmentTours)
														<table class='table' style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
															<thead>
																<tr>
																	<th rowspan='2'>№ наряда</th>
																	<!--<th rowspan='2'>Дата наряда</th>-->
																	<th rowspan='2'>Изделие</th>
																	<th rowspan='2'>Поступление (дата)</th>
																	<th rowspan='2'>Кол.</th>
																	<th rowspan='2'>Вид испытаний</th>
																	<th colspan='5' style='text-align: center;'>Отработано</th>
																	<th rowspan='2'>Дата отработки</th>
																	<th rowspan='2'>Результат</th>
																	<th rowspan='2'>Доп. инф.</th>
																	<th colspan='2' style='text-align: center;'>Телеграмма</th>
																	<th colspan='2' style='text-align: center;'>Отчет</th>
																	<th colspan='3' style='text-align: center;'>Акт</th>
																</tr>
																<tr>
																	<th>Сч.</th>
																	<th>Пристр.</th>
																	<th>Прогр.</th>
																	<th>Несч.</th>
																	<th>Отказ</th>
																	<th style='text-align: center;'>исх.№</th>
																	<th style='text-align: center;'>Дата</th>
																	<th style='text-align: center;'>№</th>
																	<th style='text-align: center;'>Дата</th>
																	<th style='text-align: center;'>№</th>
																	<th style='text-align: center;'>Дата</th>
																	<th style='text-align: center;'>Сумма</th>
																</tr>
															</thead>
															<tbody>
																@foreach($secondDepartmentTours as $isp)
																	@if(Auth::User()->hasRole()->role == 'Администратор')
																		@if($contract->name_view_contract == 'Испытания опытные')
																			<tr class="rowsContract cursorPointer" data-toggle="modal" data-target="#modalChose" onclick="$('#updateNaryad').attr('href','{{route('department.second.edit_tour_of_duty_exp',$isp->id)}}');$('#acts').attr('href','{{route('department.second.show_all_acts',$isp->id)}}');$('#btnModalActs').attr('acts','{{$isp->acts}}');$('#btnModalActs').attr('amount_acts','{{$isp->amount_acts}}');$('#btnModalActs').attr('new_act_href','{{ route('department.second.store_act', $isp->id) }}');">
																		@else
																			<tr class="rowsContract cursorPointer" data-toggle="modal" data-target="#modalChose" onclick="$('#updateNaryad').attr('href','{{route('department.second.edit_tour_of_duty',$isp->id)}}');$('#acts').attr('href','{{route('department.second.show_all_acts',$isp->id)}}');$('#btnModalActs').attr('acts','{{$isp->acts}}');$('#btnModalActs').attr('amount_acts','{{$isp->amount_acts}}');$('#btnModalActs').attr('new_act_href','{{ route('department.second.store_act', $isp->id) }}');">
																		@endif
																	@elseif(Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
																		<!--<tr class="rowsContract cursorPointer btn-href" href="{{route('department.second.show_all_acts',$isp->id)}}">-->
																		<tr class="rowsContract cursorPointer modalActsBTN" acts="{{$isp->acts}}" amount_acts="{{$isp->amount_acts}}" new_act_href="{{ route('department.second.store_act', $isp->id) }}">
																	@elseif(Auth::User()->hasRole()->role == 'Второй отдел')
																		@if($contract->name_view_contract == 'Испытания опытные')
																			<tr class="rowsContract cursorPointer rowsUpdateIsp btn-href" href="{{route('department.second.edit_tour_of_duty_exp', $isp->id)}}">
																		@else
																			<tr class="rowsContract cursorPointer rowsUpdateIsp btn-href" href="{{route('department.second.edit_tour_of_duty', $isp->id)}}">
																		@endif
																	@endif
																		<td>
																			{{ $isp->number_duty }}
																		</td>
																		<!--<td>
																			{{ $isp->date_duty }}
																		</td>-->
																		<td>
																			{{ $isp->name_element }}
																		</td>
																		<td>
																			{{ $isp->date_incoming }}
																		</td>
																		<td>
																			{{ $isp->count_elements == '0' ? '' : $isp->count_elements . ' ' . $isp->name_unit }}
																		</td>
																		<td>
																			{{ $isp->name_view_work_elements }}
																		</td>
																		<td>
																			{{ $isp->countable }}
																		</td>
																		<td>
																			{{ $isp->targeting }}
																		</td>
																		<td>
																			{{ $isp->warm }}
																		</td>
																		<td>
																			{{ $isp->uncountable }}
																		</td>
																		<td>
																			{{ $isp->renouncement }}
																		</td>
																		<td>
																			{{ $isp->date_worked ? date('d.m.Y', strtotime($isp->date_worked)) : '' }}
																		</td>
																		<td>
																			{{ $isp->name_result }}
																		</td>
																		<td>
																			{{ $isp->add_information }}
																		</td>
																		<td>
																			{{ $isp->number_telegram }}
																		</td>
																		<td>
																			{{ $isp->date_telegram }}
																		</td>
																		<td>
																			{{ $isp->number_report }}
																		</td>
																		<td>
																			{{ $isp->date_report }}
																		</td>
																		<td>
																			{{ $isp->act['number_act'] }}
																		</td>
																		<td>
																			{{ $isp->act['date_act'] }}
																		</td>
																		<td>
																			{{ is_numeric($isp->amount_acts) ? number_format($isp->amount_acts, 2, ',', ' ') : $isp->amount_acts }}
																		</td>
																	</tr>
																@endforeach
															</tbody>
														</table>
													@endif
												@elseif(trim($contract->name_view_contract) == 'Сборка')
													@if($secondDepartmentSbTours)
														<table class='table' style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
															<thead>
																<tr>
																	<th rowspan='2'>№ наряда</th>
																	<th rowspan='2'>Изделие</th>
																	<th rowspan='2'>Тип</th>
																	<th rowspan='2'>Партия</th>
																	<th rowspan='2'>Кол.</th>
																	<th rowspan='2'>Вид работ</th>
																	<th rowspan='2'>Дата сдачи</th>
																	<th rowspan='2'>№ формуляра</th>
																	<th rowspan='2'>Дата формуляра</th>
																	<th rowspan='2'>№ уведомления</th>
																	<th rowspan='2'>Дата уведомления</th>
																	<th colspan='3' style='text-align: center;'>Акт</th>
																</tr>
																<tr>
																	<th style='text-align: center;'>№</th>
																	<th style='text-align: center;'>Дата</th>
																	<th style='text-align: center;'>Сумма</th>
																</tr>
															</thead>
															<tbody>
																@foreach($secondDepartmentSbTours as $isp)
																	@if(Auth::User()->hasRole()->role == 'Администратор')
																		<tr class="rowsContract cursorPointer" data-toggle="modal" data-target="#modalChose" onclick="$('#updateNaryad').attr('href','{{route('department.second.edit_tour_of_duty_sb',$isp->id)}}');$('#acts').attr('href','{{route('department.second.show_all_acts_sb',$isp->id)}}');$('#btnModalActs').attr('acts','{{$isp->acts_sb}}');$('#btnModalActs').attr('amount_acts','{{$isp->amount_acts_sb}}');$('#btnModalActs').attr('new_act_href','{{ route('department.second.store_act_sb', $isp->id) }}');">
																	@elseif(Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
																		<tr class="rowsContract cursorPointer modalActsBTN" acts="{{$isp->acts_sb}}" amount_acts="{{$isp->amount_acts_sb}}" new_act_href="{{ route('department.second.store_act_sb', $isp->id) }}">
																	@elseif(Auth::User()->hasRole()->role == 'Второй отдел')
																		<tr class="rowsContract cursorPointer rowsUpdateIsp btn-href" href="{{route('department.second.edit_tour_of_duty_sb', $isp->id)}}">
																	@endif
																		<td>
																			{{ $isp->number_duty }}
																		</td>
																		<td>
																			{{ $isp->name_element }}
																		</td>
																		<td>
																			{{ $isp->name_caliber }}
																		</td>
																		<td>
																			{{ $isp->number_party }}
																		</td>
																		<td>
																			{{ $isp->count_elements }} {{ $isp->addition_count_elements ? "+ " . $isp->addition_count_elements : "" }} {{$isp->name_unit}}
																		</td>
																		<td>
																			{{ $isp->name_view_work_elements }}
																		</td>
																		<td>
																			{{ $isp->date_worked ? date('d.m.Y', strtotime($isp->date_worked)) : '' }}
																		</td>
																		<td>
																			{{ $isp->number_logbook }}
																		</td>
																		<td>
																			{{ $isp->date_logbook }}
																		</td>
																		<td>
																			{{ $isp->number_notification }}
																		</td>
																		<td>
																			{{ $isp->date_notification }}
																		</td>
																		<td>
																			{{ $isp->act_sb['number_act'] }}
																		</td>
																		<td>
																			{{ $isp->act_sb['date_act'] }}
																		</td>
																		<td>
																			{{ is_numeric($isp->amount_acts_sb) ? number_format($isp->amount_acts_sb, 2, ',', ' ') : $isp->amount_acts_sb }}
																		</td>
																	</tr>
																@endforeach
															</tbody>
														</table>
													@endif
												@elseif(trim($contract->name_view_contract) == 'Испытания опытные')
													@if($secondDepartmentTours)
														<table class='table' style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
															<thead>
																<tr>
																	<th rowspan='2'>№ наряда</th>
																	<th rowspan='2'>Изделие (тема)</th>
																	<th rowspan='2'>Дата начала работы</th>
																	<th rowspan='2'>Количество</th>
																	<th colspan='5' style='text-align: center;'>Отработано</th>
																	<th rowspan='2'>Дата отработки</th>
																	<th rowspan='2'>Документ о выполнении работ</th>
																	<th colspan='3' style='text-align: center;'>Акт</th>
																</tr>
																<tr>
																	<th>Сч.</th>
																	<th>Пристр.</th>
																	<th>Прогр.</th>
																	<th>Несч.</th>
																	<th>Отказ</th>
																	<th style='text-align: center;'>№</th>
																	<th style='text-align: center;'>Дата</th>
																	<th style='text-align: center;'>Сумма</th>
																</tr>
															</thead>
															<tbody>
																@foreach($secondDepartmentTours as $isp)
																	@if(Auth::User()->hasRole()->role == 'Администратор')
																		<tr class="rowsContract cursorPointer" data-toggle="modal" data-target="#modalChose" onclick="$('#updateNaryad').attr('href','{{route('department.second.edit_tour_of_duty_exp',$isp->id)}}');$('#acts').attr('href','{{route('department.second.show_all_acts',$isp->id)}}');$('#btnModalActs').attr('acts','{{$isp->acts}}');$('#btnModalActs').attr('amount_acts','{{$isp->amount_acts}}');$('#btnModalActs').attr('new_act_href','{{ route('department.second.store_act', $isp->id) }}');">
																	@elseif(Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
																		<tr class="rowsContract cursorPointer modalActsBTN" acts="{{$isp->acts}}" amount_acts="{{$isp->amount_acts}}" new_act_href="{{ route('department.second.store_act', $isp->id) }}">
																	@elseif(Auth::User()->hasRole()->role == 'Второй отдел')
																		<tr class="rowsContract cursorPointer rowsUpdateIsp btn-href" href="{{route('department.second.edit_tour_of_duty_exp', $isp->id)}}">
																	@endif
																		<td>
																			{{ $isp->number_duty }}
																		</td>
																		<td>
																			{{ $isp->theme_exp }}
																		</td>
																		<td>
																			{{ $isp->date_incoming }}
																		</td>
																		<td>
																			{{ $isp->count_elements == '0' ? '' : $isp->count_elements . ' ' . $isp->name_unit }}
																		</td>
																		<td>
																			{{ $isp->countable }}
																		</td>
																		<td>
																			{{ $isp->targeting }}
																		</td>
																		<td>
																			{{ $isp->warm }}
																		</td>
																		<td>
																			{{ $isp->uncountable }}
																		</td>
																		<td>
																			{{ $isp->renouncement }}
																		</td>
																		<td>
																			{{ $isp->date_worked ? date('d.m.Y', strtotime($isp->date_worked)) : '' }}
																		</td>
																		<td>
																			{{ $isp->result_document_exp }}
																		</td>
																		<td>
																			{{ $isp->act['number_act'] }}
																		</td>
																		<td>
																			{{ $isp->act['date_act'] }}
																		</td>
																		<td>
																			{{ is_numeric($isp->amount_acts) ? number_format($isp->amount_acts, 2, ',', ' ') : $isp->amount_acts }}
																		</td>
																	</tr>
																@endforeach
															</tbody>
														</table>
													@endif
												@endif
												@if($secondDepartmentUsTours)
													@if(count($secondDepartmentUsTours) > 0)
														<table class='table' style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
															<thead>
																<tr>
																	<th rowspan='2'>№ наряда</th>
																	<th rowspan='2'>Дата отработки</th>
																	<th rowspan='2'>№ отчёта-справки</th>
																	<th rowspan='2'>Дата отчёта-справки</th>
																	<th colspan='3' style='text-align: center;'>Акт</th>
																</tr>
																<tr>
																	<th style='text-align: center;'>№</th>
																	<th style='text-align: center;'>Дата</th>
																	<th style='text-align: center;'>Сумма</th>
																</tr>
															</thead>
															<tbody>
																@foreach($secondDepartmentUsTours as $isp)
																	@if(Auth::User()->hasRole()->role == 'Администратор')
																		<tr class="rowsContract cursorPointer" data-toggle="modal" data-target="#modalChose" onclick="$('#updateNaryad').attr('href','{{route('department.second.edit_tour_of_duty_us',$isp->id)}}');$('#acts').attr('href','{{route('department.second.show_all_acts_us',$isp->id)}}');$('#btnModalActs').attr('acts','{{$isp->acts_us}}');$('#btnModalActs').attr('amount_acts','{{$isp->amount_acts_us}}');$('#btnModalActs').attr('new_act_href','{{ route('department.second.store_act_us', $isp->id) }}');">
																	@elseif(Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
																		<tr class="rowsContract cursorPointer modalActsBTN" acts="{{$isp->acts_us}}" amount_acts="{{$isp->amount_acts_us}}" new_act_href="{{ route('department.second.store_act_us', $isp->id) }}">
																	@elseif(Auth::User()->hasRole()->role == 'Второй отдел')
																		<tr class="rowsContract cursorPointer rowsUpdateIsp btn-href" href="{{route('department.second.edit_tour_of_duty_us', $isp->id)}}">
																	@endif
																		<td>
																			{{ $isp->number_duty }}
																		</td>
																		<td>
																			{{ $isp->date_worked ? date('d.m.Y', strtotime($isp->date_worked)) : '' }}
																		</td>
																		<td>
																			{{ $isp->number_help_report }}
																		</td>
																		<td>
																			{{ $isp->date_help_report }}
																		</td>
																		<td>
																			{{ $isp->act_us['number_act'] }}
																		</td>
																		<td>
																			{{ $isp->act_us['date_act'] }}
																		</td>
																		<td>
																			{{ is_numeric($isp->amount_acts_us) ? number_format($isp->amount_acts_us, 2, ',', ' ') : $isp->amount_acts_us }}
																		</td>
																	</tr>
																@endforeach
															</tbody>
														</table>
													@endif
												@endif
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						@if($contract->name_view_work == 'Контрольные испытания')
							<!-- Модальное окно нового испытания -->
							<div class="modal fade" id="newIsp" tabindex="-1" role="dialog" aria-labelledby="newIspModalLabel" aria-hidden="true" attr-open-modal="{{$errors->has('id_element') || $errors->has('id_view_work_elements') || $errors->has('year') || $errors->has('january') || $errors->has('february') || $errors->has('march') || $errors->has('april') || $errors->has('may') || $errors->has('june') || $errors->has('july') || $errors->has('august') || $errors->has('september') || $errors->has('october') || $errors->has('november') || $errors->has('december') ? print('open') : print('')}}">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<form method='POST' action="{{route('department.second_isp.create', $contract->id)}}">
											{{csrf_field()}}
											<div class="modal-header">
												<h5 class="modal-title" id="newIspModalLabel">Новое испытание</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<div class='row'>
													<div class='col-md-12'>
														<div class="form-group">
															<label for="selElement">Выберите изделие</label>
															<select class='form-control {{$errors->has("id_element") ? print("inputError ") : print("")}}' id="selElement" name='id_element'>
																@foreach($elements as $element)
																	@if(old('id_element'))
																		@if(old('id_element') == $element->id)
																			<option value='{{$element->id}}' selected>{{$element->name_element}}</option>
																		@else
																			<option value='{{$element->id}}'>{{$element->name_element}}</option>
																		@endif
																	@else
																		<option value='{{$element->id}}'>{{$element->name_element}}</option>
																	@endif
																@endforeach
															</select>
															@if($errors->has('id_element'))
																<label class='msgError'>{{$errors->first('id_element')}}</label>
															@endif
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='col-md-12'>
														<div class="form-group">
															<label for="selView">Выберите вид испытания</label>
															<select class='form-control {{$errors->has("id_view_work_elements") ? print("inputError ") : print("")}}' id="selView" name='id_view_work_elements'>
																@foreach($view_work_elements as $view)
																	@if(old('id_view_work_elements'))
																		@if(old('id_view_work_elements') == $view->id)
																			<option value='{{$view->id}}' selected>{{$view->name_view_work_elements}}</option>
																		@else
																			<option value='{{$view->id}}'>{{$view->name_view_work_elements}}</option>
																		@endif
																	@else
																		<option value='{{$view->id}}'>{{$view->name_view_work_elements}}</option>
																	@endif
																@endforeach
															</select>
															@if($errors->has('id_view_work_elements'))
																<label class='msgError'>{{$errors->first('id_view_work_elements')}}</label>
															@endif
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='col-md-12'>
														<div class="form-group">
															<label for="year_isp">Введите год</label>
															<input id='year_isp' class='form-control {{$errors->has("year") ? print("inputError ") : print("")}}' name='year' type='number' value="{{old('year') ? old('year') : date('Y',time())}}"/>
															@if($errors->has('year'))
																<label class='msgError'>{{$errors->first('year')}}</label>
															@endif
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='col-md-2'>
														<label for='' class='form-label'>Январь</label>
														<input id='' class='form-control {{$errors->has("january") ? print("inputError ") : print("")}}' type='number' value="{{old('january') ? old('january') : ''}}" name='january'/>
														@if($errors->has('january'))
															<label class='msgError'>{{$errors->first('january')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='january_check' style='margin-top: 37px;' {{old('january_check') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Февраль</label>
														<input id='' class='form-control {{$errors->has("february") ? print("inputError ") : print("")}}' type='number' value="{{old('february') ? old('february') : ''}}" name='february'/>
														@if($errors->has('february'))
															<label class='msgError'>{{$errors->first('february')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='february_check' style='margin-top: 37px;' {{old('february_check') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Март</label>
														<input id='' class='form-control {{$errors->has("march") ? print("inputError ") : print("")}}' type='number' value="{{old('march') ? old('march') : ''}}" name='march'/>
														@if($errors->has('march'))
															<label class='msgError'>{{$errors->first('march')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='march_check' style='margin-top: 37px;' {{old('march_check') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Апрель</label>
														<input id='' class='form-control {{$errors->has("april") ? print("inputError ") : print("")}}' type='number' value="{{old('april') ? old('april') : ''}}" name='april'/>
														@if($errors->has('april'))
															<label class='msgError'>{{$errors->first('april')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='april_check' style='margin-top: 37px;' {{old('april_check') ? print("checked ") : print("") }}/>
													</div>
												</div>
												<div class='row'>
													<div class='col-md-2'>
														<label for='' class='form-label'>Май</label>
														<input id='' class='form-control {{$errors->has("may") ? print("inputError ") : print("")}}' type='number' value="{{old('may') ? old('may') : ''}}" name='may'/>
														@if($errors->has('may'))
															<label class='msgError'>{{$errors->first('may')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='may_check' style='margin-top: 37px;' {{old('may_check') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Июнь</label>
														<input id='' class='form-control {{$errors->has("june") ? print("inputError ") : print("")}}' type='number' value="{{old('june') ? old('june') : ''}}" name='june'/>
														@if($errors->has('june'))
															<label class='msgError'>{{$errors->first('june')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='june_check' style='margin-top: 37px;' {{old('june_check') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Июль</label>
														<input id='' class='form-control {{$errors->has("july") ? print("inputError ") : print("")}}' type='number' value="{{old('july') ? old('july') : ''}}" name='july'/>
														@if($errors->has('july'))
															<label class='msgError'>{{$errors->first('july')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='july_check' style='margin-top: 37px;' {{old('july_check') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Август</label>
														<input id='' class='form-control {{$errors->has("august") ? print("inputError ") : print("")}}' type='number' value="{{old('august') ? old('august') : ''}}" name='august'/>
														@if($errors->has('august'))
															<label class='msgError'>{{$errors->first('august')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='august_check' style='margin-top: 37px;' {{old('august_check') ? print("checked ") : print("") }}/>
													</div>
												</div>
												<div class='row'>
													<div class='col-md-2'>
														<label for='' class='form-label'>Сентябрь</label>
														<input id='' class='form-control {{$errors->has("september") ? print("inputError ") : print("")}}' type='number' value="{{old('september') ? old('september') : ''}}" name='september'/>
														@if($errors->has('september'))
															<label class='msgError'>{{$errors->first('september')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='september_check' style='margin-top: 37px;' {{old('september_check') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Октябрь</label>
														<input id='' class='form-control {{$errors->has("october") ? print("inputError ") : print("")}}' type='number' value="{{old('october') ? old('october') : ''}}" name='october'/>
														@if($errors->has('october'))
															<label class='msgError'>{{$errors->first('october')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='october_check' style='margin-top: 37px;' {{old('october_check') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Ноябрь</label>
														<input id='' class='form-control {{$errors->has("november") ? print("inputError ") : print("")}}' type='number' value="{{old('november') ? old('november') : ''}}" name='november'/>
														@if($errors->has('november'))
															<label class='msgError'>{{$errors->first('november')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='november_check' style='margin-top: 37px;' {{old('november_check') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Декабрь</label>
														<input id='' class='form-control {{$errors->has("december") ? print("inputError ") : print("")}}' type='number' value="{{old('december') ? old('december') : ''}}" name='december'/>
														@if($errors->has('december'))
															<label class='msgError'>{{$errors->first('december')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='december_check' style='margin-top: 37px;' {{old('december_check') ? print("checked ") : print("") }}/>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button type='submit' class='btn btn-primary' type='button'>Добавить</button>
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
											</div>
										</form>
									</div>
								</div>
							</div>
							<!-- Модальное окно редактирования испытания -->
							<div class="modal fade" id="updateIsp" tabindex="-1" role="dialog" aria-labelledby="updateIspModalLabel" aria-hidden="true" attr-open-modal="{{$errors->has('id_element_update') || $errors->has('id_view_work_elements_update') || $errors->has('year_update') || $errors->has('january_update') || $errors->has('february_update') || $errors->has('march_update') || $errors->has('april_update') || $errors->has('may_update') || $errors->has('june_update') || $errors->has('july_update') || $errors->has('august_update') || $errors->has('september_update') || $errors->has('october_update') || $errors->has('november_update') || $errors->has('december_update') ? print('open') : print('')}}">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<form id='form_update_isp'  method='POST' action="{{old('action')}}">
											{{csrf_field()}}
											<div class="modal-header">
												<h5 class="modal-title" id="updateIspModalLabel">Редактирование испытание</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<div class='row'>
													<div class='col-md-12' style='display: none;'>
														<label>Ссылка</label>
														<input id='action_update_isp' name='action_update_isp' class='form-control' value="{{old('action_update_isp')}}">
													</div>
												</div>
												<div class='row'>
													<div class='col-md-12'>
														<div class="form-group">
															<label for="selElement_update">Выберите изделие</label>
															<select class='form-control {{$errors->has("id_element_update") ? print("inputError ") : print("")}}' id="selElement_update" name='id_element_update'>
																@foreach($elements as $element)
																	@if(old('id_element_update'))
																		@if(old('id_element_update') == $element->id)
																			<option value='{{$element->id}}' selected>{{$element->name_element}}</option>
																		@else
																			<option value='{{$element->id}}'>{{$element->name_element}}</option>
																		@endif
																	@else
																		<option value='{{$element->id}}'>{{$element->name_element}}</option>
																	@endif
																@endforeach
															</select>
															@if($errors->has('id_element_update'))
																<label class='msgError'>{{$errors->first('id_element_update')}}</label>
															@endif
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='col-md-12'>
														<div class="form-group">
															<label for="selView_update">Выберите вид испытания</label>
															<select class='form-control {{$errors->has("id_view_work_elements_update") ? print("inputError ") : print("")}}' id="selView_update" name='id_view_work_elements_update'>
																@foreach($view_work_elements as $view)
																	@if(old('id_view_work_elements_update'))
																		@if(old('id_view_work_elements_update') == $view->id)
																			<option value='{{$view->id}}' selected>{{$view->name_view_work_elements}}</option>
																		@else
																			<option value='{{$view->id}}'>{{$view->name_view_work_elements}}</option>
																		@endif
																	@else
																		<option value='{{$view->id}}'>{{$view->name_view_work_elements}}</option>
																	@endif
																@endforeach
															</select>
															@if($errors->has('id_view_work_elements_update'))
																<label class='msgError'>{{$errors->first('id_view_work_elements_update')}}</label>
															@endif
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='col-md-12'>
														<div class="form-group">
															<label for="year_isp">Введите год</label>
															<input id='year_isp' class='form-control {{$errors->has("year_update") ? print("inputError ") : print("")}}' name='year_update' type='number' value="{{old('year_update') ? old('year_update') : date('Y',time())}}"/>
															@if($errors->has('year_update'))
																<label class='msgError'>{{$errors->first('year_update')}}</label>
															@endif
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='col-md-2'>
														<label for='' class='form-label'>Январь</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("january_update") ? print("inputError ") : print("")}}' type='number' value="{{old('january_update') ? old('january_update') : ''}}" name='january_update'/>
														@if($errors->has('january_update'))
															<label class='msgError'>{{$errors->first('january_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='january_check_update' style='margin-top: 37px;' {{old('january_check_update') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Февраль</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("february_update") ? print("inputError ") : print("")}}' type='number' value="{{old('february_update') ? old('february_update') : ''}}" name='february_update'/>
														@if($errors->has('february_update'))
															<label class='msgError'>{{$errors->first('february_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='february_check_update' style='margin-top: 37px;' {{old('february_check_update') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Март</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("march_update") ? print("inputError ") : print("")}}' type='number' value="{{old('march_update') ? old('march_update') : ''}}" name='march_update'/>
														@if($errors->has('march_update'))
															<label class='msgError'>{{$errors->first('march_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='march_check_update' style='margin-top: 37px;' {{old('march_check_update') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Апрель</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("april_update") ? print("inputError ") : print("")}}' type='number' value="{{old('april_update') ? old('april_update') : ''}}" name='april_update'/>
														@if($errors->has('april_update'))
															<label class='msgError'>{{$errors->first('april_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='april_check_update' style='margin-top: 37px;' {{old('april_check_update') ? print("checked ") : print("") }}/>
													</div>
												</div>
												<div class='row'>
													<div class='col-md-2'>
														<label for='' class='form-label'>Май</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("may_update") ? print("inputError ") : print("")}}' type='number' value="{{old('may_update') ? old('may_update') : ''}}" name='may_update'/>
														@if($errors->has('may_update'))
															<label class='msgError'>{{$errors->first('may_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='may_check_update' style='margin-top: 37px;' {{old('may_check_update') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Июнь</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("june_update") ? print("inputError ") : print("")}}' type='number' value="{{old('june_update') ? old('june_update') : ''}}" name='june_update'/>
														@if($errors->has('june_update'))
															<label class='msgError'>{{$errors->first('june_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='june_check_update' style='margin-top: 37px;' {{old('june_check_update') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Июль</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("july_update") ? print("inputError ") : print("")}}' type='number' value="{{old('july_update') ? old('july_update') : ''}}" name='july_update'/>
														@if($errors->has('july_update'))
															<label class='msgError'>{{$errors->first('july_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='july_check_update' style='margin-top: 37px;' {{old('july_check_update') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Август</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("august_update") ? print("inputError ") : print("")}}' type='number' value="{{old('august_update') ? old('august_update') : ''}}" name='august_update'/>
														@if($errors->has('august_update'))
															<label class='msgError'>{{$errors->first('august_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='august_check_update' style='margin-top: 37px;' {{old('august_check_update') ? print("checked ") : print("") }}/>
													</div>
												</div>
												<div class='row'>
													<div class='col-md-2'>
														<label for='' class='form-label'>Сентябрь</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("september_update") ? print("inputError ") : print("")}}' type='number' value="{{old('september_update') ? old('september_update') : ''}}" name='september_update'/>
														@if($errors->has('september_update'))
															<label class='msgError'>{{$errors->first('september_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='september_check_update' style='margin-top: 37px;' {{old('september_check_update') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Октябрь</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("october_update") ? print("inputError ") : print("")}}' type='number' value="{{old('october_update') ? old('october_update') : ''}}" name='october_update'/>
														@if($errors->has('october_update'))
															<label class='msgError'>{{$errors->first('october_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='october_check_update' style='margin-top: 37px;' {{old('october_check_update') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Ноябрь</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("november_update") ? print("inputError ") : print("")}}' type='number' value="{{old('november_update') ? old('november_update') : ''}}" name='november_update'/>
														@if($errors->has('november_update'))
															<label class='msgError'>{{$errors->first('november_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='november_check_update' style='margin-top: 37px;' {{old('november_check_update') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Декабрь</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("december_update") ? print("inputError ") : print("")}}' type='number' value="{{old('december_update') ? old('december_update') : ''}}" name='december_update'/>
														@if($errors->has('december_update'))
															<label class='msgError'>{{$errors->first('december_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='december_check_update' style='margin-top: 37px;' {{old('december_check_update') ? print("checked ") : print("") }}/>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button type='submit' class='btn btn-primary' type='button'>Редактировать</button>
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
											</div>
										</form>
										<form id='comment_isp' method='POST' action="" style='display: none;'>
											{{csrf_field()}}
											<div class="modal-header">
												<h5 class="modal-title">Комментарии</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<div class='row'>
													<div id='list_message'>
														<div class='col-md-12'>
															<label>Комментарии:</label>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='col-md-12'>
														<button id='add_new_comment' type='button' class='btn btn-primary' type='button'>Добавить комментарий</button>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button id='close_comment' type="button" class="btn btn-secondary">Закрыть комментарии</button>
											</div>
										</form>
										<form id='new_comment_isp' method='POST' action="{{old('action_new_comment')}}" style='display: none;'>
											{{csrf_field()}}
											<div class="modal-header">
												<h5 class="modal-title">Новый комментарий</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<div class='row' style='display: none;'>
													<div class='col-md-12'>
														<label>Ссылка</label>
														<input id='action_new_comment' name='action_new_comment' class='form-control' value="{{old('action_new_comment')}}">
													</div>
													<div class='col-md-12'>
														<label>Месяц</label>
														<input id='month_new_comment' name='month_new_comment' class='form-control' value="{{old('month_new_comment')}}">
													</div>
												</div>
												<div class='row'>
													<div class='col-md-12'>
														<input class='form-control' type='text' value='' name='new_comment'/>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button type='submit' class='btn btn-primary' type='button'>Добавить комментарий</button>
												<button id='close_new_comment' type="button" class="btn btn-secondary">Закрыть</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						@elseif(trim($contract->name_view_work) == 'Сборка')
							<!-- Модальное окно новой сборки -->
							<div class="modal fade" id="newSb" tabindex="-1" role="dialog" aria-labelledby="newSbModalLabel" aria-hidden="true" attr-open-modal="{{$errors->has('id_element') || $errors->has('id_view_work_elements') || $errors->has('year') || $errors->has('january') || $errors->has('february') || $errors->has('march') || $errors->has('april') || $errors->has('may') || $errors->has('june') || $errors->has('july') || $errors->has('august') || $errors->has('september') || $errors->has('october') || $errors->has('november') || $errors->has('december') ? print('open') : print('')}}">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<form method='POST' action="{{route('department.second_sb.create', $contract->id)}}">
											{{csrf_field()}}
											<div class="modal-header">
												<h5 class="modal-title" id="newIspModalLabel">Новая сборка</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<div class='row'>
													<div class='col-md-12'>
														<div class="form-group">
															<label for="selElement">Выберите изделие</label>
															<select class='form-control {{$errors->has("id_element") ? print("inputError ") : print("")}}' id="selElement" name='id_element'>
																@foreach($elements as $element)
																	@if(old('id_element'))
																		@if(old('id_element') == $element->id)
																			<option value='{{$element->id}}' selected>{{$element->name_element}}</option>
																		@else
																			<option value='{{$element->id}}'>{{$element->name_element}}</option>
																		@endif
																	@else
																		<option value='{{$element->id}}'>{{$element->name_element}}</option>
																	@endif
																@endforeach
															</select>
															@if($errors->has('id_element'))
																<label class='msgError'>{{$errors->first('id_element')}}</label>
															@endif
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='col-md-12'>
														<div class="form-group">
															<label for="year_isp">Введите год</label>
															<input id='year_isp' class='form-control {{$errors->has("year") ? print("inputError ") : print("")}}' name='year' type='number' value="{{old('year') ? old('year') : date('Y',time())}}"/>
															@if($errors->has('year'))
																<label class='msgError'>{{$errors->first('year')}}</label>
															@endif
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='col-md-2'>
														<label for='' class='form-label'>Январь</label>
														<input id='' class='form-control {{$errors->has("january") ? print("inputError ") : print("")}}' type='number' value="{{old('january') ? old('january') : ''}}" name='january'/>
														@if($errors->has('january'))
															<label class='msgError'>{{$errors->first('january')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='january_check' style='margin-top: 37px;' {{old('january_check') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Февраль</label>
														<input id='' class='form-control {{$errors->has("february") ? print("inputError ") : print("")}}' type='number' value="{{old('february') ? old('february') : ''}}" name='february'/>
														@if($errors->has('february'))
															<label class='msgError'>{{$errors->first('february')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='february_check' style='margin-top: 37px;' {{old('february_check') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Март</label>
														<input id='' class='form-control {{$errors->has("march") ? print("inputError ") : print("")}}' type='number' value="{{old('march') ? old('march') : ''}}" name='march'/>
														@if($errors->has('march'))
															<label class='msgError'>{{$errors->first('march')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='march_check' style='margin-top: 37px;' {{old('march_check') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Апрель</label>
														<input id='' class='form-control {{$errors->has("april") ? print("inputError ") : print("")}}' type='number' value="{{old('april') ? old('april') : ''}}" name='april'/>
														@if($errors->has('april'))
															<label class='msgError'>{{$errors->first('april')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='april_check' style='margin-top: 37px;' {{old('april_check') ? print("checked ") : print("") }}/>
													</div>
												</div>
												<div class='row'>
													<div class='col-md-2'>
														<label for='' class='form-label'>Май</label>
														<input id='' class='form-control {{$errors->has("may") ? print("inputError ") : print("")}}' type='number' value="{{old('may') ? old('may') : ''}}" name='may'/>
														@if($errors->has('may'))
															<label class='msgError'>{{$errors->first('may')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='may_check' style='margin-top: 37px;' {{old('may_check') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Июнь</label>
														<input id='' class='form-control {{$errors->has("june") ? print("inputError ") : print("")}}' type='number' value="{{old('june') ? old('june') : ''}}" name='june'/>
														@if($errors->has('june'))
															<label class='msgError'>{{$errors->first('june')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='june_check' style='margin-top: 37px;' {{old('june_check') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Июль</label>
														<input id='' class='form-control {{$errors->has("july") ? print("inputError ") : print("")}}' type='number' value="{{old('july') ? old('july') : ''}}" name='july'/>
														@if($errors->has('july'))
															<label class='msgError'>{{$errors->first('july')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='july_check' style='margin-top: 37px;' {{old('july_check') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Август</label>
														<input id='' class='form-control {{$errors->has("august") ? print("inputError ") : print("")}}' type='number' value="{{old('august') ? old('august') : ''}}" name='august'/>
														@if($errors->has('august'))
															<label class='msgError'>{{$errors->first('august')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='august_check' style='margin-top: 37px;' {{old('august_check') ? print("checked ") : print("") }}/>
													</div>
												</div>
												<div class='row'>
													<div class='col-md-2'>
														<label for='' class='form-label'>Сентябрь</label>
														<input id='' class='form-control {{$errors->has("september") ? print("inputError ") : print("")}}' type='number' value="{{old('september') ? old('september') : ''}}" name='september'/>
														@if($errors->has('september'))
															<label class='msgError'>{{$errors->first('september')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='september_check' style='margin-top: 37px;' {{old('september_check') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Октябрь</label>
														<input id='' class='form-control {{$errors->has("october") ? print("inputError ") : print("")}}' type='number' value="{{old('october') ? old('october') : ''}}" name='october'/>
														@if($errors->has('october'))
															<label class='msgError'>{{$errors->first('october')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='october_check' style='margin-top: 37px;' {{old('october_check') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Ноябрь</label>
														<input id='' class='form-control {{$errors->has("november") ? print("inputError ") : print("")}}' type='number' value="{{old('november') ? old('november') : ''}}" name='november'/>
														@if($errors->has('november'))
															<label class='msgError'>{{$errors->first('november')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='november_check' style='margin-top: 37px;' {{old('november_check') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Декабрь</label>
														<input id='' class='form-control {{$errors->has("december") ? print("inputError ") : print("")}}' type='number' value="{{old('december') ? old('december') : ''}}" name='december'/>
														@if($errors->has('december'))
															<label class='msgError'>{{$errors->first('december')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='december_check' style='margin-top: 37px;' {{old('december_check') ? print("checked ") : print("") }}/>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button type='submit' class='btn btn-primary' type='button'>Добавить</button>
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
											</div>
										</form>
									</div>
								</div>
							</div>
							<!-- Модальное окно редактирования испытания -->
							<div class="modal fade" id="updateIsp" tabindex="-1" role="dialog" aria-labelledby="updateIspModalLabel" aria-hidden="true" attr-open-modal="{{$errors->has('id_element_update') || $errors->has('id_view_work_elements_update') || $errors->has('year_update') || $errors->has('january_update') || $errors->has('february_update') || $errors->has('march_update') || $errors->has('april_update') || $errors->has('may_update') || $errors->has('june_update') || $errors->has('july_update') || $errors->has('august_update') || $errors->has('september_update') || $errors->has('october_update') || $errors->has('november_update') || $errors->has('december_update') ? print('open') : print('')}}">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<form method='POST' action="{{old('action')}}">
											{{csrf_field()}}
											<div class="modal-header">
												<h5 class="modal-title" id="newIspModalLabel">Редактирование сборки</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<div class='row' style='display: none;'>
													<div class='col-md-12'>
														<label>Ссылка</label>
														<input id='action' name='action' class='form-control' value="{{old('action')}}">
													</div>
												</div>
												<div class='row'>
													<div class='col-md-12'>
														<div class="form-group">
															<label for="selElement_update">Выберите изделие</label>
															<select class='form-control {{$errors->has("id_element_update") ? print("inputError ") : print("")}}' id="selElement_update" name='id_element_update'>
																@foreach($elements as $element)
																	@if(old('id_element_update'))
																		@if(old('id_element_update') == $element->id)
																			<option value='{{$element->id}}' selected>{{$element->name_element}}</option>
																		@else
																			<option value='{{$element->id}}'>{{$element->name_element}}</option>
																		@endif
																	@else
																		<option value='{{$element->id}}'>{{$element->name_element}}</option>
																	@endif
																@endforeach
															</select>
															@if($errors->has('id_element_update'))
																<label class='msgError'>{{$errors->first('id_element_update')}}</label>
															@endif
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='col-md-12'>
														<div class="form-group">
															<label for="year_isp">Введите год</label>
															<input id='year_isp' class='form-control {{$errors->has("year_update") ? print("inputError ") : print("")}}' name='year_update' type='number' value="{{old('year_update') ? old('year_update') : date('Y',time())}}"/>
															@if($errors->has('year_update'))
																<label class='msgError'>{{$errors->first('year_update')}}</label>
															@endif
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='col-md-2'>
														<label for='' class='form-label'>Январь</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("january_update") ? print("inputError ") : print("")}}' type='number' value="{{old('january_update') ? old('january_update') : ''}}" name='january_update'/>
														@if($errors->has('january_update'))
															<label class='msgError'>{{$errors->first('january_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='january_check_update' style='margin-top: 37px;' {{old('january_check_update') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Февраль</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("february_update") ? print("inputError ") : print("")}}' type='number' value="{{old('february_update') ? old('february_update') : ''}}" name='february_update'/>
														@if($errors->has('february_update'))
															<label class='msgError'>{{$errors->first('february_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='february_check_update' style='margin-top: 37px;' {{old('february_check_update') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Март</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("march_update") ? print("inputError ") : print("")}}' type='number' value="{{old('march_update') ? old('march_update') : ''}}" name='march_update'/>
														@if($errors->has('march_update'))
															<label class='msgError'>{{$errors->first('march_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='march_check_update' style='margin-top: 37px;' {{old('march_check_update') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Апрель</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("april_update") ? print("inputError ") : print("")}}' type='number' value="{{old('april_update') ? old('april_update') : ''}}" name='april_update'/>
														@if($errors->has('april_update'))
															<label class='msgError'>{{$errors->first('april_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='april_check_update' style='margin-top: 37px;' {{old('april_check_update') ? print("checked ") : print("") }}/>
													</div>
												</div>
												<div class='row'>
													<div class='col-md-2'>
														<label for='' class='form-label'>Май</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("may_update") ? print("inputError ") : print("")}}' type='number' value="{{old('may_update') ? old('may_update') : ''}}" name='may_update'/>
														@if($errors->has('may_update'))
															<label class='msgError'>{{$errors->first('may_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='may_check_update' style='margin-top: 37px;' {{old('may_check_update') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Июнь</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("june_update") ? print("inputError ") : print("")}}' type='number' value="{{old('june_update') ? old('june_update') : ''}}" name='june_update'/>
														@if($errors->has('june_update'))
															<label class='msgError'>{{$errors->first('june_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='june_check_update' style='margin-top: 37px;' {{old('june_check_update') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Июль</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("july_update") ? print("inputError ") : print("")}}' type='number' value="{{old('july_update') ? old('july_update') : ''}}" name='july_update'/>
														@if($errors->has('july_update'))
															<label class='msgError'>{{$errors->first('july_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='july_check_update' style='margin-top: 37px;' {{old('july_check_update') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Август</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("august_update") ? print("inputError ") : print("")}}' type='number' value="{{old('august_update') ? old('august_update') : ''}}" name='august_update'/>
														@if($errors->has('august_update'))
															<label class='msgError'>{{$errors->first('august_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='august_check_update' style='margin-top: 37px;' {{old('august_check_update') ? print("checked ") : print("") }}/>
													</div>
												</div>
												<div class='row'>
													<div class='col-md-2'>
														<label for='' class='form-label'>Сентябрь</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("september_update") ? print("inputError ") : print("")}}' type='number' value="{{old('september_update') ? old('september_update') : ''}}" name='september_update'/>
														@if($errors->has('september_update'))
															<label class='msgError'>{{$errors->first('september_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='september_check_update' style='margin-top: 37px;' {{old('september_check_update') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Октябрь</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("october_update") ? print("inputError ") : print("")}}' type='number' value="{{old('october_update') ? old('october_update') : ''}}" name='october_update'/>
														@if($errors->has('october_update'))
															<label class='msgError'>{{$errors->first('october_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='october_check_update' style='margin-top: 37px;' {{old('october_check_update') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Ноябрь</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("november_update") ? print("inputError ") : print("")}}' type='number' value="{{old('november_update') ? old('november_update') : ''}}" name='november_update'/>
														@if($errors->has('november_update'))
															<label class='msgError'>{{$errors->first('november_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='november_check_update' style='margin-top: 37px;' {{old('november_check_update') ? print("checked ") : print("") }}/>
													</div>
													<div class='col-md-2'>
														<label for='' class='form-label'>Декабрь</label>
														<input id='' class='comment_for_naryad form-control {{$errors->has("december_update") ? print("inputError ") : print("")}}' type='number' value="{{old('december_update') ? old('december_update') : ''}}" name='december_update'/>
														@if($errors->has('december_update'))
															<label class='msgError'>{{$errors->first('december_update')}}</label>
														@endif
													</div>
													<div class='col-md-1'>
														<input id='' class='form-check-input' type="checkbox" name='december_check_update' style='margin-top: 37px;' {{old('december_check_update') ? print("checked ") : print("") }}/>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button type='submit' class='btn btn-primary' type='button'>Редактировать</button>
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						@endif
					</div>
					<!-- Модальное окно выполнение работ -->
					<div class="modal fade" id="work_states" tabindex="-1" role="dialog" aria-labelledby="workStatesModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form method='POST' action="{{ route('department.ekonomic.new_state',$contract->id)}}">
									{{csrf_field()}}
									<div class="modal-header">
										<h5 class="modal-title" id="workStatesModalLabel">Выполнение работ</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<div class='row'>
											<div id='table_history_states' class='col-md-12'>
												<table class="table" style='margin: 0 auto;'>
													<thead>
														<tr>
															<th>Наименование</th>
															<th>Дата</th>
															<th>Автор</th>
															<th>Удалить</th>
														</tr>
													</thead>
													<tbody>
														@if(isset($work_states))
															@foreach($work_states as $state)
																<tr class='rowsContract'>
																	<td>{{$state->name_state}}<br/>{{$state->comment_state}}</td>
																	<td>{{$state->date_state}}</td>
																	<td>{{$state->surname . ' ' . mb_substr($state->name, 0, 1) . '.' . mb_substr($state->patronymic, 0, 1) . '.'}}</td>
																	<td>
																		@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->surname == $state->surname)
																			<button type='button' class='btn btn-danger btn-href' type='button' href="{{route('department.ekonomic.destroy_state', $state->id)}}">Удалить</button>
																		@else
																			<button type='button' class='btn btn-danger' type='button' disabled>Удалить</button>
																		@endif
																	</td>
																</tr>
															@endforeach
														@endif
													</tbody>
												</table>
											</div>
											<div id='add_history_states' class='col-md-12' style='display: none;'>
												<div class='form-group row col-md-12'>
													<input id='id_state' class='form-control' type='text' name='id_state' style='display: none;'/>
												</div>
												<div class='form-group row col-md-12'>
													<div class='col-md-12'>
														<label for='type_state' class='col-form-label'>Наименование</label>
														<select id='type_state' class='form-control {{$errors->has("unit_reestr") ? print("inputError ") : print("")}}' name='type_state' required>
															<option></option>
															<option>Изделие не поступило на испытание</option>
															<option>В стадии выполнения</option>
															<option>Выполнен</option>
															<option>Другое</option>
														</select>
													</div>
												</div>
												<div class='form-group row col-md-12'>
													<div class='col-md-12'>
														<input id='new_name_state' class='form-control {{$errors->has("new_name_state") ? print("inputError ") : print("")}}' type='text' name='new_name_state' style='display: none;'/>
														@if($errors->has('new_name_state'))
															<label class='msgError'>{{$errors->first('new_name_state')}}</label>
														@endif
													</div>
												</div>
												<div class='form-group row col-md-12' style='display: none;'>
													<div class='col-md-12'>
														<input class='form-control' type='text' name='is_work_state' value='1'/>
													</div>
												</div>
												<div class='form-group row col-md-12'>
													<label class='col-md-3 col-form-label'>Примечение</label>
													<div class='col-md-9'>
														<input class='form-control {{$errors->has("comment_state") ? print("inputError ") : print("")}}' type='text' name='comment_state'/>
														@if($errors->has('comment_state'))
															<label class='msgError'>{{$errors->first('comment_state')}}</label>
														@endif
													</div>
												</div>
												<div class='form-group row col-md-12'>
													<label for='date_state' class='col-md-3 col-form-label'>Дата</label>
													<div class='col-md-9'>
														<input id='date_state' class='datepicker form-control {{$errors->has("date_state") ? print("inputError ") : print("")}}' name='date_state' value='{{date("d.m.Y", time())}}' />
														@if($errors->has('date_state'))
															<label class='msgError'>{{$errors->first('date_state')}}</label>
														@endif
													</div>
												</div>
											</div>
											<div class='col-md-12'>
												@if(Auth::User()->hasRole()->role != 'Администрация' AND Auth::User()->surname != 'Бастрыкова' AND Auth::User()->surname != 'Гуринова' AND Auth::User()->hasRole()->role != 'Второй отдел (просмотр)')
													<button id='btn_add_state' class='btn btn-secondary' type='button' clear_date='{{date("d.m.Y", time())}}' action_state='{{ route("department.ekonomic.new_state",$contract->id)}}' style='margin-top: 10px;'>Добавить стадию выполнения</button>
												@endif
											</div>
										</div>									
									</div>
									<div class="modal-footer">
										<button id='btn_add_new_history' type="submit" class="btn btn-primary" style='display: none;'>Добавить</button>
										<button id='btn_destroy_state' type="submit" class="btn btn-danger" style='display: none;'>Удалить</button>
										<button id='btn_close_new_history_states' type="button" class="btn btn-secondary" style='display: none;'>Закрыть</button>
										<button id='btn_close_new_history' type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<!-- Модальное окно история состояний -->
					<div class="modal fade" id="history_states" tabindex="-1" role="dialog" aria-labelledby="historyStatesModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form method='POST' action="{{ route('department.ekonomic.new_state',$contract->id)}}">
									{{csrf_field()}}
									<div class="modal-header">
										<h5 class="modal-title" id="historyStatesModalLabel">История состояний договора</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<div class='row'>
											@if(count($states) > 0)
												<div id='table_history_states' class='col-md-12'>
													<table class="table" style='margin: 0 auto;'>
														<thead>
															<tr>
																<th>Наименование</th>
																<th>Дата</th>
																<th>Автор</th>
															</tr>
														</thead>
														<tbody>
															@foreach($states as $state)
																<tr class='rowsContract' id_state='{{$state->id}}' 
																													name_state='{{$state->name_state}}' 
																													date_state='{{$state->date_state}}' 
																													action_state='{{ route("department.ekonomic.update_state",$contract->id)}}'
																													destroy_state='{{ route("department.ekonomic.destroy_state",$state->id)}}'>
																	<td>{{$state->name_state}}<br/>{{$state->comment_state}}</td>
																	<td>{{$state->date_state}}</td>
																	<td>{{$state->surname . ' ' . mb_substr($state->name, 0, 1) . '.' . mb_substr($state->patronymic, 0, 1) . '.'}}</td>
																</tr>
															@endforeach
														</tbody>
													</table>
												</div>
											@endif
											<div id='add_history_states' class='col-md-12' style='display: none;'>
												<div class='form-group row col-md-12'>
													<input id='id_state' class='form-control' type='text' name='id_state' style='display: none;'/>
												</div>
												<div class='form-group row col-md-12'>
													<label for='new_name_state' class='col-md-3 col-form-label'>Наименование</label>
													<div class='col-md-9'>
														<input id='new_name_state' class='form-control {{$errors->has("new_name_state") ? print("inputError ") : print("")}}' type='text' name='new_name_state'/>
														@if($errors->has('new_name_state'))
															<label class='msgError'>{{$errors->first('new_name_state')}}</label>
														@endif
													</div>
												</div>
												<div class='form-group row col-md-12'>
													<label for='date_state' class='col-md-3 col-form-label'>Дата</label>
													<div class='col-md-9'>
														<input id='date_state' class='form-control {{$errors->has("date_state") ? print("inputError ") : print("")}}' name='date_state' value='{{date("d.m.Y", time())}}' readonly />
														@if($errors->has('date_state'))
															<label class='msgError'>{{$errors->first('date_state')}}</label>
														@endif
													</div>
												</div>
											</div>
											<div class='col-md-12'>
												<!--<button id='btn_add_state' class='btn btn-secondary' type='button' clear_date='{{date("d.m.Y", time())}}' action_state='{{ route("department.ekonomic.new_state",$contract->id)}}'>Добавить состояние</button>-->
											</div>
										</div>									
									</div>
									<div class="modal-footer">
										<button id='btn_add_new_history' type="submit" class="btn btn-primary" style='display: none;'>Добавить</button>
										<button id='btn_destroy_state' type="submit" class="btn btn-primary" style='display: none;'>Удалить</button>
										<button id='btn_close_new_history_states' type="button" class="btn btn-secondary" style='display: none;'>Закрыть</button>
										<button id='btn_close_new_history' type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<!-- Модальное окно резолюции -->
					<div class="modal fade" id="scan" tabindex="-1" role="dialog" aria-labelledby="scanModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form id='form_all_application' method='POST' action=''>
									{{csrf_field()}}
									<div class="modal-header">
										<h5 class="modal-title" id="scanModalLabel">Скан договора</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div id='all_aplication' class="modal-body">
										<div class='form-group row'>
											<div class="col-md-3">
												<label>Резолюция:</label>
											</div>
											<div class="col-md-4">
												
											</div>
											<div class="col-md-4">
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-12">
												<select id='resolution_list' name='resolution_list' class='form-control {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
													@if(count($resolutions) > 0)
														@foreach($resolutions as $resolution)
															<option value='http://{{$resolution->path_resolution}}' download_href='resolution_download/{{$resolution->id}}'>{{$resolution->real_name_resolution}}</option>
														@endforeach
													@else
														<option></option>
													@endif
												</select>
											</div>
										</div>
										<div class='form-group row'>
											<div class="col-md-3">
												<button id='open_resolution' type='button' class='btn btn-secondary' style='width: 122px;'>Открыть скан</button>
											</div>
											<div class="col-md-3">
												<!--<button id='download_resolution' type='button' class='btn btn-secondary' style='width: 122px;'>Скачать скан</button>-->
											</div>
											<div class="col-md-6">
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<!-- Счета -->
					<div class="modal fade" id="invoice" tabindex="-1" role="dialog" aria-labelledby="invoiceModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="invoiceModalLabel">Счета</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='row'>
										<div class="col-md-12">
											<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
												<thead>
													<tr>
														<th rowspan='7' style='text-align: center; vertical-align: middle; max-width: 94px;'>По предприятию</th>
													</tr>
													<tr>
														<th  colspan='2'>Всего оказано услуг</th>
														<th>{{number_format($amount_invoices_all, 2, ',', ' ')}} р.</th>
													</tr>
													<tr>
														<th  colspan='2'>Всего оплачено</th>
														<th>{{number_format($amount_payments_all, 2, ',', ' ')}} р.</th>
													</tr>
													<tr>
														<th rowspan='2' style='vertical-align: middle;'>Задолженность</th>
														<th>Дебет</th>
														<th>{{number_format((($amount_invoices_all - $amount_payments_all + $amount_returns_all) > 0 ? $amount_invoices_all - $amount_payments_all + $amount_returns_all : 0), 2, ',', ' ')}} р.</th>
													</tr>
													<tr>
														<th>Кредит</th>
														<th>{{number_format((($amount_payments_all - $amount_invoices_all - $amount_returns_all) > 0 ? $amount_payments_all - $amount_invoices_all - $amount_returns_all : 0), 2, ',', ' ')}} р.</th>
													</tr>
												</thead>
											</table>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
												<thead>
													<tr>
														<th rowspan='7' style='text-align: center; vertical-align: middle; max-width: 94px;'>Оплата и исполнение договора</th>
													</tr>
													<tr>
														<th  colspan='2'>Выполнение</th>
														<th>{{number_format($amount_invoices, 2, ',', ' ')}} р.</th>
													</tr>
													<tr>
														<th  colspan='2'>Аванс</th>
														<th>{{number_format($amount_prepayments, 2, ',', ' ')}} р.</th>
													</tr>
													<tr>
														<th  colspan='2'>Окончательный расчет</th>
														<th>{{number_format($amount_payments, 2, ',', ' ')}} р.</th>
													</tr>
													<tr>
														<th rowspan='2' style='vertical-align: middle;'>Задолженность</th>
														<th>Дебет</th>
														<th>{{number_format((($amount_invoices - ($amount_prepayments + $amount_payments) + $amount_returns) > 0 ? $amount_invoices - ($amount_prepayments + $amount_payments) + $amount_returns : 0), 2, ',', ' ')}} р.</th>
													</tr>
													<tr>
														<th>Кредит</th>
														<th>{{number_format(((($amount_prepayments + $amount_payments) - $amount_invoices - $amount_returns) > 0 ? ($amount_prepayments + $amount_payments) - $amount_invoices - $amount_returns : 0), 2, ',', ' ')}} р.</th>
													</tr>
												</thead>
											</table>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-6">
											@if($prepayments)
												<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
													<thead>
														<tr>
															<th colspan='3' style='text-align: center;'>СЧЕТ НА АВАНС</th>
														</tr>
														<tr>
															<th>№ сч</th>
															<th>Дата</th>
															<th>Сумма</th>
														</tr>
													</thead>
													<tbody>
														<?php $pr_amount = 0; ?>
														@foreach($prepayments as $prepayment)
															<tr class="rowsContract">
																<td>
																	{{ $prepayment->number_invoice }}
																</td>
																<td>
																	{{ $prepayment->date_invoice ? date('d.m.Y', strtotime($prepayment->date_invoice)) : '' }}
																</td>
																<td>
																	{{ $prepayment->amount_p_invoice }}
																</td>
															</tr>
															<?php $pr_amount += $prepayment->amount_p_invoice; ?>
														@endforeach
														<tr>
															<td>
															<td><b>Итого:</b></td>
															<td>{{$pr_amount}}</td>
														</tr>
													</tbody>
												</table>
											@endif
										</div>
										<div class="col-md-6">
											@if($payments)
												<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
													<thead>
														<tr>
															<th colspan='3' style='text-align: center;'>ОПЛАТА АВАНСА</th>
														</tr>
														<tr>
															<th>№ п/п</th>
															<th>Дата</th>
															<th>Сумма</th>
														</tr>
													</thead>
													<tbody>
														<?php $pr_amount = 0; ?>
														@foreach($payments as $payment)
															@if($payment->is_prepayment_invoice)
																<tr class="rowsContract">
																	<td>
																		{{ $payment->number_invoice }}
																	</td>
																	<td>
																		{{ $payment->date_invoice ? date('d.m.Y', strtotime($payment->date_invoice)) : '' }}
																	</td>
																	<td>
																		{{ $payment->amount_p_invoice }}
																	</td>
																</tr>
																<?php $pr_amount += $payment->amount_p_invoice; ?>
															@endif
														@endforeach
														<tr>
															<td>
															<td><b>Итого:</b></td>
															<td>{{$pr_amount}}</td>
														</tr>
													</tbody>
												</table>
											@endif
										</div>
									</div>
									<div class='row'>
										<div class="col-md-6">
											@if($scores)
												<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
													<thead>
														<tr>
															<th colspan='3' style='text-align: center;'>СЧЕТ НА ОПЛАТУ</th>
														</tr>
														<tr>
															<th>№ сч</th>
															<th>Дата</th>
															<th>Сумма</th>
														</tr>
													</thead>
													<tbody>
														<?php $pr_amount = 0; ?>
														@foreach($scores as $score)
															<tr class="rowsContract">
																<td>
																	{{ $score->number_invoice }}
																</td>
																<td>
																	{{ $score->date_invoice ? date('d.m.Y', strtotime($score->date_invoice)) : '' }}
																</td>
																<td>
																	{{ $score->amount_p_invoice }}
																</td>
															</tr>
															<?php $pr_amount += $score->amount_p_invoice; ?>
														@endforeach
														<tr>
															<td>
															<td><b>Итого:</b></td>
															<td>{{$pr_amount}}</td>
														</tr>
													</tbody>
												</table>
											@endif
										</div>
										<div class="col-md-6">
											@if($payments)
												<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
													<thead>
														<tr>
															<th colspan='3' style='text-align: center;'>ОПЛАТА ТОВАРА, РАБОТ, УСЛУГ</th>
														</tr>
														<tr>
															<th>№ п/п</th>
															<th>Дата</th>
															<th>Сумма</th>
														</tr>
													</thead>
													<tbody>
														<?php $pr_amount = 0; ?>
														@foreach($payments as $payment)
															@if(!$payment->is_prepayment_invoice)
																<tr class="rowsContract">
																	<td>
																		{{ $payment->number_invoice }}
																	</td>
																	<td>
																		{{ $payment->date_invoice ? date('d.m.Y', strtotime($payment->date_invoice)) : '' }}
																	</td>
																	<td>
																		{{ $payment->amount_p_invoice }}
																	</td>
																</tr>
																<?php $pr_amount += $payment->amount_p_invoice; ?>
															@endif
														@endforeach
														<tr>
															<td>
															<td><b>Итого:</b></td>
															<td>{{$pr_amount}}</td>
														</tr>
													</tbody>
												</table>
											@endif
										</div>
									</div>
									<div class='row'>
										<div class="col-md-6">
											@if($invoices)
												<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
													<thead>
														<tr>
															<th colspan='3' style='text-align: center;'>ОКАЗАНО УСЛУГ (Счет-фактура)</th>
														</tr>
														<tr>
															<th>№ п/п</th>
															<th>Дата</th>
															<th>Сумма</th>
														</tr>
													</thead>
													<tbody>
														<?php $pr_amount = 0; ?>
														@foreach($invoices as $invoice)
															<tr class="rowsContract">
																<td>
																	{{ $invoice->number_invoice }}
																</td>
																<td>
																	{{ $invoice->date_invoice ? date('d.m.Y', strtotime($invoice->date_invoice)) : '' }}
																</td>
																<td>
																	{{ $invoice->amount_p_invoice }}
																</td>
															</tr>
															<?php $pr_amount += $invoice->amount_p_invoice; ?>
														@endforeach
														<tr>
															<td>
															<td><b>Итого:</b></td>
															<td>{{$pr_amount}}</td>
														</tr>
													</tbody>
												</table>
											@endif
										</div>
										<div class="col-md-6">
											@if($returns)
												<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
													<thead>
														<tr>
															<th colspan='3' style='text-align: center;'>ВОЗВРАТ</th>
														</tr>
														<tr>
															<th>№ п/п</th>
															<th>Дата</th>
															<th>Сумма</th>
														</tr>
													</thead>
													<tbody>
														<?php $pr_amount = 0; ?>
														@foreach($returns as $return)
															<tr class="rowsContract">
																<td>
																	{{ $return->number_invoice }}
																</td>
																<td>
																	{{ $return->date_invoice ? date('d.m.Y', strtotime($return->date_invoice)) : '' }}
																</td>
																<td>
																	{{ $return->amount_p_invoice }}
																</td>
															</tr>
															<?php $pr_amount += $return->amount_p_invoice; ?>
														@endforeach
														<tr>
															<td>
															<td><b>Итого:</b></td>
															<td>{{$pr_amount}}</td>
														</tr>
													</tbody>
												</table>
											@endif
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</div>
						</div>
					</div>
					<!-- Модальное окно актов для нарядов -->
					<div class="modal fade" id="modalActs" tabindex="-1" role="dialog" aria-labelledby="modalActsModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="modalActsModalLabel">Акты по наряду</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div id='allActs' class='row'>
										<div class='col-md-12'>
											<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
												<thead>
													<tr>
														<th>Номер акта</th>
														<th>Дата акта</th>
														<th>Исх. № акта</th>
														<th>Дата исх.</th>
														<th>Вх. № акта</th>
														<th>Дата вх.</th>
														<th>Сумма с НДС, руб.</th>
														<th>Редактировать</th>
													</tr>
												</thead>
												<tbody id='tbodyForModalActs'>
													
												</tbody>
											</table>
											<button class='btn btn-primary steps' type='button' first_step='#allActs' second_step='#newAct'>Новый акт</button>
										</div>
									</div>
									<div id='newAct' class='row' style='display: none;'>
										<form id='formNewAct' method='POST' action=''>
											{{csrf_field()}}
											<div class='col-md-8 col-md-offset-2'>
												<div class="form-group">
													<label>Номер акта</label>
													<input class='form-control' type='text' value='' name='number_act' />
												</div>
												<div class="form-group">
													<label>Дата акта</label>
													<input class='datepicker form-control' type='text' value='' name='date_act' />
												</div>
												<div class="form-group">
													<label>Исх. № акта</label>
													<input class='form-control' type='text' value='' name='number_outgoing_act' />
												</div>
												<div class="form-group">
													<label>Дата исх.</label>
													<input class='datepicker form-control' type='text' value='' name='date_outgoing_act' />
												</div>
												<div class="form-group">
													<label>Вх. № акта</label>
													<input class='form-control' type='text' value='' name='number_incoming_act' />
												</div>
												<div class="form-group">
													<label>Дата вх.</label>
													<input class='datepicker form-control' type='text' value='' name='date_incoming_act' />
												</div>
												<div class="form-group">
													<label>Сумма акта</label>
													<input class='form-control check-number' type='text' value='' name='amount_act' required />
												</div>
											</div>
											<div class='col-md-8 col-md-offset-2'>
												<div class='col-md-6'>
													<button type="button" class="btn btn-secondary steps" first_step='#newAct' second_step='#allActs'>Назад</button>
												</div>
												<div class='col-md-6'>
													@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел')
														<button type="submit" class="btn btn-primary" style='float: right;'>Сохранить акт</button>
													@endif
												</div>
											</div>
										</form>
									</div>
									<div id='editAct' class='row' style='display: none;'>
										<form id='formEditAct' method='POST' action=''>
											{{csrf_field()}}
											<div class='col-md-8 col-md-offset-2'>
												<div class="form-group">
													<label>Номер акта</label>
													<input id='edit_number_act' class='form-control' type='text' value='' name='number_act' />
												</div>
												<div class="form-group">
													<label>Дата акта</label>
													<input id='edit_date_act' class='datepicker form-control' type='text' value='' name='date_act' />
												</div>
												<div class="form-group">
													<label>Исх. № акта</label>
													<input id='edit_number_outgoing_act' class='form-control' type='text' value='' name='number_outgoing_act' />
												</div>
												<div class="form-group">
													<label>Дата исх.</label>
													<input id='edit_date_outgoing_act' class='datepicker form-control' type='text' value='' name='date_outgoing_act' />
												</div>
												<div class="form-group">
													<label>Вх. № акта</label>
													<input id='edit_number_incoming_act' class='form-control' type='text' value='' name='number_incoming_act' />
												</div>
												<div class="form-group">
													<label>Дата вх.</label>
													<input id='edit_date_incoming_act' class='datepicker form-control' type='text' value='' name='date_incoming_act' />
												</div>
												<div class="form-group">
													<label>Сумма акта</label>
													<input id='edit_amount_act' class='form-control check-number' type='text' value='' name='amount_act' readonly required />
												</div>
											</div>
											<div class='col-md-8 col-md-offset-2'>
												<div class='col-md-6'>
													<button type="button" class="btn btn-secondary steps" first_step='#editAct' second_step='#allActs'>Назад</button>
												</div>
												<div class='col-md-6'>
													@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел')
														<button type="submit" class="btn btn-primary" style='float: right;'>Сохранить акт</button>
													@endif
												</div>
											</div>
										</form>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</div>
						</div>
					</div>
					<!-- Модальное окно актов для контракта (Улсуги ГН и услуги ВН) -->
					<div class="modal fade" id="modalContractActs" tabindex="-1" role="dialog" aria-labelledby="modalContractActsModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="modalContractActsModalLabel">Акты по контракту</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div id='allContractActs' class='row'>
										<div class='col-md-12'>
											<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
												<thead>
													<tr>
														<th>Номер акта</th>
														<th>Дата акта</th>
														<th>Сумма с НДС, руб.</th>
														<th>Редактировать</th>
													</tr>
												</thead>
												<tbody>
													<?php $amount_contract_acts = 0; ?>
													@foreach($contract->acts as $act)
														<tr>
															<td>{{$act->number_act}}</td>
															<td>{{$act->date_act}}</td>
															<td>{{$act->amount_act}}</td>
															<td><button type='button' class='btn btn-primary editContractActBTN' type='button' edit_act_href="{{route('department.second.update_act', $act->id)}}" number_act='{{$act->number_act}}' date_act='{{$act->date_act}}' amount_act='{{$act->amount_act}}'>Редактировать</button></td></td>
														</tr>
														<?php if(is_numeric($act->amount_act)) $amount_contract_acts += $act->amount_act; ?>
													@endforeach
													<tr class='rowsContract'>
														<td></td>
														<td><b>Сумма:</b></td>
														<td><b>{{$amount_contract_acts}}</b>
														</td>
														<td></td>
													</tr>
												</tbody>
											</table>
											<button class='btn btn-primary steps' type='button' first_step='#allContractActs' second_step='#newContractAct'>Новый акт</button>
										</div>
									</div>
									<div id='newContractAct' class='row' style='display: none;'>
										<form method='POST' action="{{ route('department.second.store_contract_act', $contract->id) }}">
											{{csrf_field()}}
											<div class='col-md-8 col-md-offset-2'>
												<div class="form-group">
													<label>Номер акта</label>
													<input class='form-control' type='text' value='' name='number_act' />
												</div>
												<div class="form-group">
													<label>Дата акта</label>
													<input class='datepicker form-control' type='text' value='' name='date_act' />
												</div>
												<div class="form-group">
													<label>Сумма акта</label>
													<input class='form-control check-number' type='text' value='' name='amount_act' required />
												</div>
											</div>
											<div class='col-md-8 col-md-offset-2'>
												<div class='col-md-6'>
													<button type="button" class="btn btn-primary steps" first_step='#newContractAct' second_step='#allContractActs'>Назад</button>
												</div>
												<div class='col-md-6'>
													@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел')
														<button type="submit" class="btn btn-primary" style='float: right;'>Сохранить акт</button>
													@endif
												</div>
											</div>
										</form>
									</div>
									<div id='editContractAct' class='row' style='display: none;'>
										<form id='formEditContractAct' method='POST' action=''>
											{{csrf_field()}}
											<div class='col-md-8 col-md-offset-2'>
												<div class="form-group">
													<label>Номер акта</label>
													<input id='edit_contract_number_act' class='form-control' type='text' value='' name='number_act' />
												</div>
												<div class="form-group">
													<label>Дата акта</label>
													<input id='edit_contract_date_act' class='datepicker form-control' type='text' value='' name='date_act' />
												</div>
												<div class="form-group">
													<label>Сумма акта</label>
													<input id='edit_contract_amount_act' class='form-control check-number' type='text' value='' name='amount_act' readonly required />
												</div>
											</div>
											<div class='col-md-8 col-md-offset-2'>
												<div class='col-md-6'>
													<button type="button" class="btn btn-primary steps" first_step='#editContractAct' second_step='#allContractActs'>Назад</button>
												</div>
												<div class='col-md-6'>
													@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел')
														<button type="submit" class="btn btn-primary" style='float: right;'>Сохранить акт</button>
													@endif
												</div>
											</div>
										</form>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</div>
						</div>
					</div>
					<!-- Модальное окно выбора действия -->
					<div class="modal fade" id="modalChose" tabindex="-1" role="dialog" aria-labelledby="modalChoseModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="modalChoseModalLabel">Выбор действия</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class='row'>
										<div class="col-md-12">
											<button id='updateNaryad' class="btn btn-primary btn-href" type="button" href="" style='width: 200px;'>Наряд</button>
										</div>
										<div class="col-md-12" style='margin-top: 5px;'>
											<button id='acts' class="btn btn-primary btn-href" type="button" href="" style='width: 200px;'>Выполнение</button>
										</div>
										<div class="col-md-12" style='margin-top: 5px;'>
											<button id='btnModalActs' class="btn btn-primary modalActsBTN" type="button" style='200px;' data-dismiss="modal">Выполнение (модальное)</button>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</div>
						</div>
					</div>
					<script>
						if($('#newIsp').attr('attr-open-modal') == 'open1')
							$('#newIsp').modal('show');
						if($('#updateIsp').attr('attr-open-modal') == 'open1')
							$('#updateIsp').modal('show');
					</script>
				@else
					
				@endif
				<script src="{{ asset('js/history.js') }}"></script>
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
