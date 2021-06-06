@extends('layouts.header')

@section('title')
	Добавить дог. (контр.)
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if(Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел')
				@if(Route::has('login'))
					<div class="top-right links">
						
					</div>
				@endif
				<div class="content">
					<form method='POST' action="{{route('department.peo.store_contract')}}" file='true' enctype='multipart/form-data'>
						{{csrf_field()}}
						<div class="row">
							<div class="col-md-2   ">
								<label>Контрагент</label>
								<div class="form-group">
									<select id="sel4" class='form-control select_counterpartie_reestr {{$errors->has("id_counterpartie_contract") ? print("inputError ") : print("")}}' name='id_counterpartie_contract' required >
										<option></option>
										@if($counterparties)
											@foreach($counterparties as $counterpartie)
												@if(old('id_counterpartie_contract'))
													@if(old('id_counterpartie_contract') == $counterpartie->id)
														<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}' selected>{{ $counterpartie->name }}</option>
													@else
														<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>												
													@endif
												@else
													<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>
												@endif
											@endforeach
										@endif
									</select>
									@if($errors->has('id_counterpartie_contract'))
										<label class='msgError'>{{$errors->first('id_counterpartie_contract')}}</label>
									@endif
								</div>
							</div>
							<div class="col-md-1  ">
								<div class="form-group">
									<button type='button' class="btn btn-primary" data-toggle="modal" data-target="#chose_counterpartie" style='margin-top: 27px;'>Выбрать</button>
								</div>
							</div>
							<div class="col-md-2  ">
								<div class="form-group">
									<label>Внимание!</label>
									<input class='form-control' style='color:red; text-align:center;' type='text' value='' readonly />
								</div>
							</div>
							<div class="col-md-1">
								<div class="form-group">
									<label for='numberContract'>Номер договора</label>
									<input id='numberContract' class='form-control {{$errors->has("number_contract") ? print("inputError ") : print("")}}' name='number_contract' type='text' value='{{old("number_contract")}}' readonly />
									@if($errors->has('number_contract'))
										<label class='msgError'>{{$errors->first('number_contract')}}</label>
									@endif
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for='executor_contract_reestr'>Исполнитель по Дог./Контр.</label>
									<select class='form-control' name='executor_contract_reestr'>
										<option></option>
										@if(old('executor_reestr'))
											@foreach($curators_sip as $in_curators)
												@if(old('executor_contract_reestr') == $in_curators->id)
													<option value='{{$in_curators->id}}' selected>{{$in_curators->FIO}}</option>
												@else
													<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
												@endif
											@endforeach
										@else
											@foreach($curators_sip as $in_curators)
												<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
											@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class='row'>
									<div class="col-md-12">
										<div class='form-check'>
											<input id='gozCheck' class='form-check-input' name='goz_contract' type="checkbox" onclick="$(this).prop('checked', true); $('#exportCheck').prop('checked', false); $('#otherCheck').prop('checked', false);" />
											<label class='form-check-label' for='gozCheck'>ГОЗ</label>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-12">
										<div class='form-check'>
											<input id='exportCheck' class='form-check-input' name='export_contract' type="checkbox" onclick="$(this).prop('checked', true); $('#gozCheck').prop('checked', false); $('#otherCheck').prop('checked', false);" />
											<label class='form-check-label' for='exportCheck'>Экспорт</label>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-12">
										<div class='form-check'>
											<input id='otherCheck' class='form-check-input' name='interfactory_contract' type="checkbox" onclick="$(this).prop('checked', true); $('#exportCheck').prop('checked', false); $('#gozCheck').prop('checked', false);" />
											<label class='form-check-label' for='otherCheck'>Межзаводские</label>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-1">
								<div class="form-group">
									<label class='form-check-label' for='break'>ОТКАЗ</label>
									@if(old('renouncement_contract'))
										<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" checked />
									@else
										<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox"/>
									@endif
								</div>
								<div class="form-group">
									<label class='form-check-label' for='archive_contract'>АРХИВ</label>
									@if(old('archive_contract'))
										<input id='archive_contract' class='form-check-input' name='archive_contract' type="checkbox" checked />
									@else
										<input id='archive_contract' class='form-check-input' name='archive_contract' type="checkbox"/>
									@endif
								</div>
							</div>
							<div class="col-md-1">
								<div class='form-group'>
									<label>Год:</label>
									<input id='year_contract' class='form-control' type='text' name='year_contract' value="{{ old('year_contract') ? old('year_contract') : date('Y', time())}}"/>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for='miniCounterpartie'>Полное наименование контрагента</label>
									<input id='miniCounterpartie' class='form-control {{$errors->has("number_contract") ? print("inputError ") : print("")}}' type='text' value='' readonly />
								</div>
							</div>
							<div class="col-md-3">
								<label>ИНН</label>
								<input class='form-control' value='' readonly />
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class='row'>
									<div class="col-md-6">
										<div class="form-group">
											<label for='number_counterpartie_contract_reestr'>№ дог. контрагента</label>
											<input id='number_counterpartie_contract_reestr' class='form-control {{$errors->has("number_counterpartie_contract_reestr") ? print("inputError ") : print("")}}' name='number_counterpartie_contract_reestr' value='{{old("number_counterpartie_contract_reestr")}}'/>
											@if($errors->has('number_counterpartie_contract_reestr'))
												<label class='msgError'>{{$errors->first('number_counterpartie_contract_reestr')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for='igk_reestr'>ИГК</label>
											<input id='igk_reestr' class='form-control {{$errors->has("igk_reestr") ? print("inputError ") : print("")}}' name='igk_reestr' value='{{ old("igk_reestr") }}'/>
										</div>
									</div>
									<div class="col-md-3">
										<label>Дата Дог./Контр. на 1 л.</label>
										<input name='date_contract_on_first_reestr' class='datepicker form-control' type='text' value="{{old('date_contract_on_first_reestr') ? old('date_contract_on_first_reestr') : $reestr->date_contract_on_first_reestr}}"/>
									</div>
								</div>
							</div>
						</div>
						<div class='row'>
							<div class="col-md-2">
								<div class="form-group">
									<label for="sel3">Вид работ</span></label>
									<select id="sel3" class='form-control {{$errors->has("id_view_contract") ? print("inputError ") : print("")}}' name='id_view_contract' required>
										<option></option>
										@if($viewContracts)
											@foreach($viewContracts as $viewContract)
												@if(old('id_view_contract'))
													@if(old('id_view_contract') == $viewContract->id)
														<option value='{{$viewContract->id}}' selected>{{ $viewContract->name_view_contract }}</option>
													@else
														<option value='{{$viewContract->id}}'>{{ $viewContract->name_view_contract }}</option>
													@endif
												@else
													<option value='{{$viewContract->id}}'>{{ $viewContract->name_view_contract }}</option>
												@endif
											@endforeach
										@endif
									</select>
									@if($errors->has('id_view_contract'))
										<label class='msgError'>{{$errors->first('id_view_contract')}}</label>
									@endif
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for='app_outgoing_number_reestr'>Заявка исх. №</label>
									<input id='app_outgoing_number_reestr' class='form-control {{$errors->has("app_outgoing_number_reestr") ? print("inputError ") : print("")}}' name='app_outgoing_number_reestr' value='{{old("app_outgoing_number_reestr")}}'/>
									@if($errors->has('app_outgoing_number_reestr'))
										<label class='msgError'>{{$errors->first('app_outgoing_number_reestr')}}</label>
									@endif
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for='app_incoming_number_reestr'>Вх. №</label>
									<input id='app_incoming_number_reestr' class='form-control {{$errors->has("app_incoming_number_reestr") ? print("inputError ") : print("")}}' name='app_incoming_number_reestr' value='{{old("app_incoming_number_reestr")}}'/>
									@if($errors->has('app_incoming_number_reestr'))
										<label class='msgError'>{{$errors->first('app_incoming_number_reestr')}}</label>
									@endif
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for='date_maturity_reestr'>Срок проведения работ</label>
									<input id='date_maturity_reestr' class='form-control {{$errors->has("date_maturity_reestr") ? print("inputError ") : print("")}}' name='date_maturity_reestr' value='{{ old("date_maturity_reestr") }}'/>
								</div>
							</div>
							<div class='col-md-3'>
								<div class="form-group">
									<button class='btn btn-primary' data-toggle="modal" data-target="#scan" type='button' style='float: right; width: 184px;'>Скан</button>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class='form-group'>
									<label for='nameWork'>Наименование работ</label>
									<textarea id='nameWork' class='form-control {{$errors->has("item_contract") ? print("inputError ") : print("")}}' name='item_contract' type="text" style="width: 100%;" rows='4' required>{{ old('item_contract') }}</textarea>
									@if($errors->has('item_contract'))
										<label class='msgError'>{{$errors->first('item_contract')}}</label>
									@endif
								</div>
							</div>
							<div class="col-md-2">
								<div class='form-group'>
									<label>Сумма начальная, руб. с НДС</label>
									<input id='amount_reestr' name='amount_reestr' class='form-control check-number' type='text' value="{{old('amount_reestr') ? old('amount_reestr') : $reestr->amount_reestr}}"/>
								</div>
								<div class='form-group'>
									<label>Сумма по актам вып.раб с НДС, руб</label>
									<input id='amount_contract_reestr' name='amount_contract_reestr' class='form-control check-number' type='text' value="{{old('amount_contract_reestr') ? old('amount_contract_reestr') : $reestr->amount_contract_reestr}}" readonly />
								</div>
							</div>
							<div class="col-md-3">
								<div class='form-group'>
									<label>Сумма начальная на тек.год, руб. с НДС</label>
									<input id='amount_year_reestr' name='amount_year_reestr' class='form-control check-number' type='text' value="{{old('amount_year_reestr') ? old('amount_year_reestr') : $reestr->amount_year_reestr}}"/>
								</div>
								<div class='form-group'>
									<label>Сумма по актам вып.раб на тек.год с НДС, руб</label>
									<input id='amount_contract_year_reestr' name='amount_contract_reestr' class='form-control check-number' type='text' value="{{old('amount_contract_reestr') ? old('amount_contract_reestr') : $reestr->amount_contract_reestr}}" readonly />
								</div>
							</div>
							<div class="col-md-2">
								<div class='form-group'>
									<label for="sel3">НДС</span></label>
									<select id="sel3" class='form-control {{$errors->has("vat_reestr") ? print("inputError ") : print("")}}' name='vat_reestr'>
										<option value='1' @if($reestr->vat_reestr) selected @endif>с НДС</option>
										<option value='0' @if(!$reestr->vat_reestr) selected @endif>НДС не облаг.</option>
									</select>
								</div>
								<div class='form-group'>
									@if(old('prepayment_reestr'))
										<input id='prepayment_reestr' class='form-check-input' type="checkbox" checked />
									@else
										@if($reestr->prepayment_reestr || $reestr->percent_prepayment_reestr)
											<input id='prepayment_reestr' class='form-check-input' type="checkbox" checked />
										@else
											<input id='prepayment_reestr' class='form-check-input' type="checkbox" />
										@endif
									@endif
									<label for='prepayment_reestr'>Аванс предусмотрен</label>
									<div class='row'>
										<div class='col-md-6'>
											<div id='input_prepayment_contract' class='input_prepayment_contract' style='@if($reestr->prepayment_reestr || $reestr->percent_prepayment_reestr) display:block; @else display:none; @endif'>
												<label>%</label>
												@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел')
													<input name='percent_prepayment_reestr' class='form-control check-number' type='text' value="{{old('percent_prepayment_reestr') ? old('percent_prepayment_reestr') : $reestr->percent_prepayment_reestr}}"/>
												@else
													<input name='percent_prepayment_reestr' class='form-control' type='text' value="{{old('percent_prepayment_reestr') ? old('percent_prepayment_reestr') : $reestr->percent_prepayment_reestr}}" disabled />
												@endif
											</div>
										</div>
										<div class='col-md-6'>
											<div id='input_prepayment_contract' class='input_prepayment_contract' style='@if($reestr->prepayment_reestr || $reestr->percent_prepayment_reestr) display:block; @else display:none; @endif'>
												<label>Сумма</label>
												@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел')
													<input name='prepayment_reestr' class='form-control check-number' type='text' value="{{old('prepayment_reestr') ? old('prepayment_reestr') : $reestr->prepayment_reestr}}"/>
												@else
													<input name='prepayment_reestr' class='form-control' type='text' value="{{old('prepayment_reestr') ? old('prepayment_reestr') : $reestr->prepayment_reestr}}" disabled />
												@endif
											</div>
										</div>
									</div>
								</div>
								<div class='form-group'>
									@if(old('big_deal_reestr'))
										<input id='big_deal_reestr' name='big_deal_reestr' class='form-check-input' type="checkbox" checked />
									@else
										@if($reestr->big_deal_reestr)
											<input id='big_deal_reestr' name='big_deal_reestr' class='form-check-input' type="checkbox" checked />
										@else
											<input id='big_deal_reestr' name='big_deal_reestr' class='form-check-input' type="checkbox" />
										@endif
									@endif
									<label for='big_deal_reestr'>Крупная сделка</label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								
							</div>
							<div class="col-md-2">
								
							</div>
							<div class="col-md-2">
								
							</div>
							<div class="col-md-3">
								
							</div>
							<div class="col-md-3">
								@if(Auth::User()->hasRole()->role != 'Администрация')
									<button type='submit' class="btn btn-primary" style="float: right;">Сохранить договор</button>
								@endif
							</div>
						</div>
						<div class="row">
						</div>
						<!-- Модальное окно резолюции -->
						<div class="modal fade" id="scan" tabindex="-1" role="dialog" aria-labelledby="scanModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="updateApplicationModalLabel">Добавление резолюции</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class='modal-body'>
										<div class='row'>
											<div class='col-md-6' style='display: none;'>
												<input type='text' value='id_contract_resolution' name='real_name_document'/>
											</div>
											<div class='col-md-6'>
												<input id='new_file_resolution' type='file' name='new_file_resolution' value='D:\'/>
											</div>
											<div class='col-md-6'>
												<input id='date_resolution' name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
											</div>
										</div>
										<div class='row'>
											<div class='col-md-12'>
												<label>Наименование документа</label>
												<input id='real_name_resolution' type='text' name='real_name_resolution' class='form-control'/>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-primary" data-dismiss="modal">Принять</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
				<!-- Модальное окно выбора контрагента -->
				<div class="modal fade" id="chose_counterpartie" tabindex="-1" role="dialog" aria-labelledby="choseCounterpartieModalLabel" aria-hidden="true" attr-open-modal="@if(\Session::has('search_counterparties')){{print('open')}}@endif">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<form method='POST' action="{{route('department.reestr.search_counterpartie')}}">
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="choseCounterpartieModalLabel">Выбор контрагента</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									@includeif('layouts.search', ['search_arr_value'=>['name'=>'Контрагент','name_full'=>'Полное наименование','inn'=>'ИНН']])
									@if(\Session::has('search_counterparties'))
										<div class="row">
											<div class="col-md-12">
												<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
													<thead>
														<tr>
															<th>Контрагент</th>
															<th>Полное наименование</th>
															<th>Выбрать</th>
														</tr>
													</thead>
													<tbody>
														@foreach(\Session::get('search_counterparties') as $counterpartie)
															<tr class='rowsContract'>
																<td>{{$counterpartie->name}}</td>
																<td>{{$counterpartie->name_full}}</td>
																<td><button type='button' class='btn btn-primary chose-counterpartie' type='button' id_counterpartie='{{$counterpartie->id}}'>Выбрать</button></td>
															</tr>
														@endforeach
													</tbody>
												</table>
											</div>
										</div>
									@endif
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<script>
					if($('#chose_counterpartie').attr('attr-open-modal') == 'open1')
						$('#chose_counterpartie').modal('show');
				</script>
			@endif
		@else
			<div class="alert alert-danger">
				Необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection