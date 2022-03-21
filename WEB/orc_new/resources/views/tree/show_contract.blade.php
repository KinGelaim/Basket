@extends('layouts.header')

@section('title')
	Граф договора
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div id='content' class="content">
				<div class='row'>
					<div class="col-md-12">
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
						</div>
						<div class="row">
							<div class="col-md-2">
								<label>Дата Дог./Контр. на 1 л.</label>
							</div>
							<div class="col-md-10">
								<label for='sel2'>Название предприятия</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<input name='date_contract_on_first_reestr' class='form-control' type='text' value="{{$contract->date_contract_on_first_reestr}}" readonly />
							</div>
							<div class="col-md-8">
								<div class="form-group">
									<select class="form-control" id="sel2" disabled>
										<option>{{ $contract->name_counterpartie_contract }}</option>
									</select>
								</div>
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
												<label for='date_b_contract_reestr'>Срок действия Д/К с</label>
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
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class='row'>
					<div class='col-sm-3'>
						<div class="row">
							<div class='col-sm-12'>
								<label>Текущий договор</label>
							</div>
						</div>
						<div class="row">
							<div class='col-sm-6' style='text-align: center;'>
								@if(count($states) > 0)
									@if($states[count($states) - 1]->name_state == "Заключен" OR $states[count($states) - 1]->name_state == "Заключён")
										<img id='main_contract' class='cursorPointer btn-href' src="{{ asset('css/images/bd_sbrowse_complete.png') }}" style='width: 32px;' href="{{route('department.ekonomic.contract_new_reestr', $contract->id)}}"/>
									@else
										<img id='main_contract' class='cursorPointer btn-href' src="{{ asset('css/images/bd_sbrowse.png') }}" style='width: 32px;' href="{{route('department.ekonomic.contract_new_reestr', $contract->id)}}"/>
									@endif
								@else
									<img id='main_contract' class='cursorPointer btn-href' src="{{ asset('css/images/bd_sbrowse.png') }}" style='width: 32px;' href="{{route('department.ekonomic.contract_new_reestr', $contract->id)}}"/>
								@endif
							</div>
						</div>
						<div class='row'>
							<div class='col-sm-12'>
								<p>№{{$contract->number_contract}}</p>
							</div>
						</div>
						<div class='row'>
							<div class='col-sm-12'>
								<button class='btn btn-primary' data-toggle="modal" data-target="#scan" type='button' style='width: 184px;'>Оригиналы</button>
							</div>
						</div>
					</div>
					<div class='col-sm-1'>
						
					</div>
					<div class='col-sm-6'>
						@if(isset($protocols))
							@if(count($protocols) > 0)
								<div class="row">
									<div class='col-sm-12'>
										<label>Протоколы</label>
									</div>
								</div>
								<div id='protocols' class="row" style='border: solid 1.5px; padding: 5px;'>
									<div class='col-sm-12'>
										<div class="row">
											@foreach($protocols as $protocol)
												<div class='col-sm-2'>
													<div class='row'>
														<div class='col-sm-12'>	
															@if($protocol->complete == 1)
																<img class='cursorPointer btn-href' src="{{ asset('css/images/bd_sbrowse_complete.png') }}" style='width: 32px;' href="{{route('department.reestr.show_protocols', $contract->id)}}"/>
															@else
																<img class='cursorPointer btn-href' src="{{ asset('css/images/bd_sbrowse.png') }}" style='width: 32px;' href="{{route('department.reestr.show_protocols', $contract->id)}}"/>
															@endif
														</div>
													</div>
													<div class='row'>
														<div class='col-sm-12'>
															<p>{{$protocol->name_protocol}}</p>
														</div>
													</div>
												</div>
											@endforeach
										</div>
									</div>
								</div>
							@endif
						@endif
						<!--<hr style='border-top: 1px solid #030303;height: 10px;-webkit-transform: rotate(-11deg);position: absolute;left: -495px;top: 85px;line-height: 1px;width: 487px;'/>
						<hr style='border-top: 1px solid #030303;height: 10px;position: absolute;left: -495px;top: 147px;line-height: 1px;width: 487px;'/>-->
						@if(isset($additional_agreements))
							@if(count($additional_agreements) > 0)
								<div class="row">
									<div class='col-sm-12'>
										<label>Дополнительные соглашения</label>
									</div>
								</div>
								<div id='additional_agreements' class="row" style='border: solid 1.5px; padding: 5px;'>
									<div class='col-sm-12'>
										<div class="row">
											@foreach($additional_agreements as $additional_agreement)
												<div class='col-sm-2'>
													<div class='row'>
														<div class='col-sm-12'>
															@if($additional_agreement->complete == 1)
																<img class='cursorPointer btn-href' src="{{ asset('css/images/bd_sbrowse_complete.png') }}" style='width: 32px;' href="{{route('department.reestr.show_additional_agreements', $contract->id)}}"/>
															@else
																<img class='cursorPointer btn-href' src="{{ asset('css/images/bd_sbrowse.png') }}" style='width: 32px;' href="{{route('department.reestr.show_additional_agreements', $contract->id)}}"/>
															@endif
														</div>
													</div>
													<div class='row'>
														<div class='col-sm-12'>
															<p>{{$additional_agreement->name_protocol}}</p>
														</div>
													</div>
												</div>
											@endforeach
										</div>
									</div>
								</div>
							@endif
						@endif
						@if(isset($tours))
							@if(count($tours) > 0)
								<div class="row">
									<div class='col-sm-12'>
										<label>Наряды</label>
									</div>
								</div>
								<div id='tours' class="row" style='border: solid 1.5px; padding: 5px;'>
									<div class='col-sm-12'>
										<div class="row">
											@foreach($tours as $tour)
												<div class='col-sm-2'>
													<div class='row'>
														<div class='col-sm-12'>
															<img class='cursorPointer btn-href' src="{{ asset('css/images/bd_sbrowse.png') }}" style='width: 32px;' href="{{route('department.contract_second.show', $contract->id)}}"/>
														</div>
													</div>
													<div class='row'>
														<div class='col-sm-12'>
															<p>№{{$tour->number_duty}}</p>
														</div>
													</div>
												</div>
											@endforeach
										</div>
									</div>
								</div>
							@endif
						@endif
					</div>
				</div>
				<div class='row'>
					<div class='col-md-8 col-md-offset-2'>
						<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
							<thead>
								<tr>
									<th rowspan='8' style='text-align: center; vertical-align: middle; max-width: 94px;'>Оплата и исполнение договора</th>
								</tr>
								<tr>
									<th  colspan='2'>Счёт на оплату</th>
									<th>{{$contract->amount_scores}} р.</th>
								</tr>
								<tr>
									<th  colspan='2'>Аванс</th>
									<th>{{$contract->amount_prepayments}} р.</th>
								</tr>
								<tr>
									<th  colspan='2'>Оказано услуг</th>
									<th>{{$contract->amount_invoices}} р.</th>
								</tr>
								<tr>
									<th  colspan='2'>Окончательный расчет</th>
									<th>{{$contract->amount_payments}} р.</th>
								</tr>
								<tr>
									<th  colspan='2'>Возврат</th>
									<th>{{$contract->amount_returns}} р.</th>
								</tr>
								<tr>
									<th rowspan='2' style='vertical-align: middle;'>Задолженность</th>
									<th>Дебет</th>
									<th>{{($contract->amount_invoices - ($contract->amount_prepayments + $contract->amount_payments) + $contract->amount_returns) > 0 ? $contract->amount_invoices - ($contract->amount_prepayments + $contract->amount_payments) + $contract->amount_returns : 0}} р.</th>
								</tr>
								<tr>
									<th>Кредит</th>
									<th>{{(($contract->amount_prepayments + $contract->amount_payments) - $contract->amount_invoices - $contract->amount_returns) > 0 ? ($contract->amount_prepayments + $contract->amount_payments) - $contract->amount_invoices - $contract->amount_returns : 0}} р.</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
			<!-- Модальное окно резолюции -->
			<div class="modal fade" id="scan" tabindex="-1" role="dialog" aria-labelledby="scanModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
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
							</div>
							<div class='form-group row'>
								<div class="col-md-12">
									<select id='resolution_list' name='resolution_list' class='form-control {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
										@if(count($resolutions) > 0)
											@foreach($resolutions as $key=>$value)
												@foreach($value as $resolution)
													@if($resolution->deleted_at == null)
														@if($resolution->type_resolution == 1)
															<option value='http://{{$resolution->path_resolution}}' download_href='resolution_download/{{$resolution->id}}' delete_href='{{route("resolution_delete",$resolution->id)}}' style='color: rgb(239,19,198);'>{{$resolution->real_name_resolution}}</option>
														@else
															<option value='http://{{$resolution->path_resolution}}' download_href='resolution_download/{{$resolution->id}}' delete_href='{{route("resolution_delete",$resolution->id)}}'>{{$resolution->real_name_resolution}}</option>
														@endif
													@endif
												@endforeach
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
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
						</div>
					</div>
				</div>
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
												</tr>
											</thead>
											<tbody>
												@if(isset($work_states))
													@foreach($work_states as $state)
														<tr class='rowsContract'>
															<td>{{$state->name_state}}<br/>{{$state->comment_state}}</td>
															<td>{{$state->date_state}}</td>
															<td>{{$state->surname . ' ' . mb_substr($state->name, 0, 1) . '.' . mb_substr($state->patronymic, 0, 1) . '.'}}</td>
														</tr>
													@endforeach
												@endif
											</tbody>
										</table>
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
			<script>
				function createTree(){
					var beginOffsetTop = 27;
					if($('#protocols').is('#protocols')){
						var xBegin = $('#main_contract').offset().left + $('#main_contract').width() + 4;
						var yBegin = $('#main_contract').offset().top - beginOffsetTop;
						var xEnd = $('#protocols').offset().left - 4;
						var yEnd = $('#protocols').offset().top - 7;
						var length = Math.sqrt(((xEnd - xBegin) * (xEnd - xBegin)) + ((yEnd - yBegin) * (yEnd - yBegin)));
						var cx = ((xBegin + xEnd) / 2) - (length / 2); 
						var cy = ((yBegin + yEnd) / 2) - 2;
						var angle = Math.atan2((yBegin - yEnd), (xBegin - xEnd))*(180/Math.PI);
						var htmlLine = "<hr class='hrLine' style='height: 3px; position: absolute; left:" + cx + "px; top:" + cy + "px; width:" + length + "px; -webkit-transform:rotate(" + angle + "deg);border-top: 1px solid black;' />";
						$('#content').append(htmlLine);
						beginOffsetTop -= 12;
					}
					if($('#additional_agreements').is('#additional_agreements')){
						xBegin = $('#main_contract').offset().left + $('#main_contract').width() + 4;
						yBegin = $('#main_contract').offset().top - beginOffsetTop;
						xEnd = $('#additional_agreements').offset().left - 4;
						yEnd = $('#additional_agreements').offset().top - 7;
						length = Math.sqrt(((xEnd - xBegin) * (xEnd - xBegin)) + ((yEnd - yBegin) * (yEnd - yBegin)));
						cx = ((xBegin + xEnd) / 2) - (length / 2); 
						cy = ((yBegin + yEnd) / 2) - 2;
						angle = Math.atan2((yBegin - yEnd), (xBegin - xEnd))*(180/Math.PI);
						htmlLine = "<hr class='hrLine' style='height: 3px; position: absolute; left:" + cx + "px; top:" + cy + "px; width:" + length + "px; -webkit-transform:rotate(" + angle + "deg);border-top: 1px solid black;' />";
						$('#content').append(htmlLine);
						beginOffsetTop -= 12;
					}
					if($('#tours').is('#tours')){
						xBegin = $('#main_contract').offset().left + $('#main_contract').width() + 4;
						yBegin = $('#main_contract').offset().top - beginOffsetTop;
						xEnd = $('#tours').offset().left - 4;
						yEnd = $('#tours').offset().top - 7;
						length = Math.sqrt(((xEnd - xBegin) * (xEnd - xBegin)) + ((yEnd - yBegin) * (yEnd - yBegin)));
						cx = ((xBegin + xEnd) / 2) - (length / 2); 
						cy = ((yBegin + yEnd) / 2) - 2;
						angle = Math.atan2((yBegin - yEnd), (xBegin - xEnd))*(180/Math.PI);
						htmlLine = "<hr class='hrLine' style='height: 3px; position: absolute; left:" + cx + "px; top:" + cy + "px; width:" + length + "px; -webkit-transform:rotate(" + angle + "deg);border-top: 1px solid black;' />";
						$('#content').append(htmlLine);
						beginOffsetTop -= 12;
					}
					//alert(xBegin);
				};
				createTree();
				$(window).resize(function(){
					$('.hrLine').each(function(){
						$(this).remove();
					});
					createTree();
				});
			</script>
		@else
			<div class="alert alert-danger">
				Необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection
