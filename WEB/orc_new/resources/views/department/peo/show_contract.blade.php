@extends('layouts.header')

@section('title')
	Редактирование дог. (контр.)
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if(Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Десятый отдел' OR Auth::User()->hasRole()->role == 'Администрация' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
				@if(Route::has('login'))
					<div class="top-right links">
						
					</div>
				@endif
				<?php 
					$is_disabled = '';
					if(Auth::User()->hasRole()->role == 'Десятый отдел'){
						$is_disabled = 'disabled';
						if(count(explode("‐",$contract->number_contract))>1)
							if(explode("‐",$contract->number_contract)[1] == '23')
								$is_disabled = '';
					}
					if(Auth::User()->surname == 'Бастрыкова' || Auth::User()->surname == 'Гуринова')
						$is_disabled = 'disabled';
				?>
				<div class="content">
					<form method='POST' action="{{route('department.peo.update_contract', $contract->id)}}" file='true' enctype='multipart/form-data'>
						{{csrf_field()}}
						<div class="row">
							<div class="col-md-2   ">
								<label>Контрагент</label>
								<div class="form-group">
									<select id="sel4" class='form-control select_counterpartie_reestr {{$errors->has("id_counterpartie_contract") ? print("inputError ") : print("")}}' name='id_counterpartie_contract' {{$is_disabled}} required>
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
													@if($contract->id_counterpartie_contract == $counterpartie->id)
														<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}' selected>{{ $counterpartie->name }}</option>
													@else
														<option value='{{$counterpartie->id}}' full_name='{{$counterpartie->name_full}}' inn='{{$counterpartie->inn}}'>{{ $counterpartie->name }}</option>												
													@endif
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
									<button type='button' class="btn btn-primary" data-toggle="modal" data-target="#chose_counterpartie" style='margin-top: 27px;' {{$is_disabled}}>Выбрать</button>
								</div>
							</div>
							<div class="col-md-2  ">
								<div class="form-group">
									<label>Внимание!</label>
									<input class='form-control' style='color:red; text-align:center;' type='text' value='<?php 
											if($reestr->date_registration_project_reestr)
												if(!$reestr->date_signing_contract_reestr){
													if(time() - strtotime($reestr->date_registration_project_reestr) > 2592000)
														echo 'Не подписан более 30 дней!';
												}else
													if(strtotime($reestr->date_signing_contract_reestr) - strtotime($reestr->date_registration_project_reestr) > 2592000)
														echo 'Не был подписан более 30 дней!';
										?>' readonly />
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for='numberContract'>Номер договора</label>
									<input id='numberContract' class='form-control {{$errors->has("number_contract") ? print("inputError ") : print("")}}' name='number_contract' type='text' value='{{old("number_contract") ? old("number_contract") : $contract->number_contract}}' readonly />
									@if($errors->has('number_contract'))
										<label class='msgError'>{{$errors->first('number_contract')}}</label>
									@endif
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for='executor_contract_reestr' class='small-text'>Исполнитель по Дог./Контр.</label>
									<select class='form-control' name='executor_contract_reestr' {{$is_disabled}}>
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
												@if($reestr->executor_contract_reestr == $in_curators->id)
													<option value='{{$in_curators->id}}' selected>{{$in_curators->FIO}}</option>
												@else
													<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
												@endif
											@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-1">
								<div class='row'>
									<div class="col-md-12" style='padding: 0px;'>
										<div class='form-check'>
											@if($contract->name_works_goz == "ГОЗ")
												<input id='gozCheck' class='form-check-input' name='goz_contract' type="checkbox" onclick="$(this).prop('checked', true); $('#exportCheck').prop('checked', false); $('#otherCheck').prop('checked', false);" checked {{$is_disabled}}/>
											@else
												<input id='gozCheck' class='form-check-input' name='goz_contract' type="checkbox" onclick="$(this).prop('checked', true); $('#exportCheck').prop('checked', false); $('#otherCheck').prop('checked', false);" {{$is_disabled}}/>
											@endif
											<label class='form-check-label' for='gozCheck'>ГОЗ</label>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-12" style='padding: 0px;'>
										<div class='form-check'>
											@if($contract->name_works_goz == "Экспорт")
												<input id='exportCheck' class='form-check-input' name='export_contract' type="checkbox" onclick="$(this).prop('checked', true); $('#gozCheck').prop('checked', false); $('#otherCheck').prop('checked', false);" checked {{$is_disabled}}/>
											@else
												<input id='exportCheck' class='form-check-input' name='export_contract' type="checkbox" onclick="$(this).prop('checked', true); $('#gozCheck').prop('checked', false); $('#otherCheck').prop('checked', false);" {{$is_disabled}}/>
											@endif
											<label class='form-check-label' for='exportCheck'>Экспорт</label>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-12" style='padding: 0px;'>
										<div class='form-check'>
											@if($contract->name_works_goz == "Межзаводские")
												<input id='otherCheck' class='form-check-input' name='interfactory_contract' type="checkbox" onclick="$(this).prop('checked', true); $('#exportCheck').prop('checked', false); $('#gozCheck').prop('checked', false);" checked {{$is_disabled}}/>
											@else
												<input id='otherCheck' class='form-check-input' name='interfactory_contract' type="checkbox" onclick="$(this).prop('checked', true); $('#exportCheck').prop('checked', false); $('#gozCheck').prop('checked', false);" {{$is_disabled}}/>
											@endif
											<label class='form-check-label' for='otherCheck'>Межзавод.</label>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-1">
								<div class="form-group">
									<label class='form-check-label' for='break'>ОТКАЗ</label>
									@if(old('renouncement_contract'))
										<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" checked {{$is_disabled}}/>
									@else
										@if($contract->renouncement_contract == 1)
											<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" checked {{$is_disabled}}/>
										@else
											<input id='break' class='form-check-input' name='renouncement_contract' type="checkbox" {{$is_disabled}}/>
										@endif
									@endif
								</div>
								<div class="form-group">
									<label class='form-check-label' for='archive_contract'>АРХИВ</label>
									@if(old('archive_contract'))
										<input id='archive' class='form-check-input' type="checkbox" checked disabled />
									@else
										@if($contract->archive_contract == 1)
											<input id='archive' class='form-check-input' type="checkbox" checked disabled />
										@else
											<input id='archive' class='form-check-input' type="checkbox" disabled />
										@endif
									@endif
								</div>
							</div>
							<div class="col-md-1">
								<div class='form-group'>
									<label>Год:</label>
									<input id='year_contract' class='form-control check-year' type='text' name='year_contract' value="{{ old('year_contract') ? old('year_contract') : $contract->year_contract}}" {{$is_disabled}} required />
								</div>
							</div>
						</div>
						@if(Auth::User()->hasRole()->role != 'Планово-экономический отдел')
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for='miniCounterpartie'>Полное наименование контрагента</label>
										<input id='miniCounterpartie' class='form-control {{$errors->has("number_contract") ? print("inputError ") : print("")}}' type='text' value='{{ $contract->full_name_counterpartie_contract }}' readonly />
									</div>
								</div>
								<div class="col-md-3">
									<label>ИНН</label>
									<input id='inn_counterpartie' class='form-control' value='{{$contract->inn_counterpartie_contract}}' readonly />
								</div>
							</div>
						@endif
						<div class="row">
							<div class="col-md-12">
								<div class='row'>
									<div class="col-md-6">
										<div class="form-group">
											<label for='number_counterpartie_contract_reestr'>№ дог. контрагента</label>
											<input id='number_counterpartie_contract_reestr' class='form-control {{$errors->has("number_counterpartie_contract_reestr") ? print("inputError ") : print("")}}' name='number_counterpartie_contract_reestr' value='{{old("number_counterpartie_contract_reestr") ? old("number_counterpartie_contract_reestr") : $reestr->number_counterpartie_contract_reestr}}' {{$is_disabled}}/>
											@if($errors->has('number_counterpartie_contract_reestr'))
												<label class='msgError'>{{$errors->first('number_counterpartie_contract_reestr')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for='igk_reestr'>ИГК</label>
											<input id='igk_reestr' class='form-control {{$errors->has("igk_reestr") ? print("inputError ") : print("")}}' name='igk_reestr' value='{{ old("igk_reestr") ? old("igk_reestr") : $reestr->igk_reestr }}' {{$is_disabled}}/>
										</div>
									</div>
									<div class="col-md-3">
										<label>Дата Дог./Контр. на 1 л.</label>
										<input id='date_contract_on_first_reestr' class='datepicker form-control {{$errors->has("date_contract_on_first_reestr") ? print("inputError ") : print("")}}' name='date_contract_on_first_reestr' value='{{old("date_contract_on_first_reestr") ? old("date_contract_on_first_reestr") : $reestr->date_contract_on_first_reestr}}' {{$is_disabled}}/>
									</div>
								</div>
							</div>
						</div>
						<div class='row'>
							<div class="col-md-2">
								<div class="form-group">
									<div class='row'>
										<div class="col-md-12">
											<label for="sel3">Вид работ</span></label>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-12">
											<select id="sel3" class='form-control {{$errors->has("id_view_contract") ? print("inputError ") : print("")}}' name='id_view_contract' {{$is_disabled}} required>
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
															@if($reestr->id_view_contract == $viewContract->id)
																<option value='{{$viewContract->id}}' selected>{{ $viewContract->name_view_contract }}</option>
															@else
																<option value='{{$viewContract->id}}'>{{ $viewContract->name_view_contract }}</option>
															@endif
														@endif
													@endforeach
												@endif
											</select>
											@if($errors->has('id_view_contract'))
												<label class='msgError'>{{$errors->first('id_view_contract')}}</label>
											@endif
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<div class='row'>
										<div class="col-md-12">
											<label for='app_outgoing_number_reestr'>Заявка исх. №</label>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-12">
											<!-- $reestr->app_outgoing_number_reestr -->
											@if(null == null)
												<input id='app_outgoing_number_reestr' class='form-control {{$errors->has("app_outgoing_number_reestr") ? print("inputError ") : print("")}}' name='app_outgoing_number_reestr' value='{{old("app_outgoing_number_reestr") ? old("app_outgoing_number_reestr") : $reestr->app_outgoing_number_reestr}}' {{$is_disabled}}/>
											@else
												<input id='app_outgoing_number_reestr' class='form-control {{$errors->has("app_outgoing_number_reestr") ? print("inputError ") : print("")}}' name='app_outgoing_number_reestr' value='{{$reestr->app_outgoing_number_reestr}}' disabled />
											@endif
											@if($errors->has('app_outgoing_number_reestr'))
												<label class='msgError'>{{$errors->first('app_outgoing_number_reestr')}}</label>
											@endif
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<div class='row'>
										<div class="col-md-12">
											<label for='app_incoming_number_reestr'>Вх. №</label>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-12">
											<!-- $reestr->app_incoming_number_reestr -->
											@if(null == null)
												<input id='app_incoming_number_reestr' class='form-control {{$errors->has("app_incoming_number_reestr") ? print("inputError ") : print("")}}' name='app_incoming_number_reestr' value='{{old("app_incoming_number_reestr") ? old("app_incoming_number_reestr") : $reestr->app_incoming_number_reestr}}' {{$is_disabled}}/>
											@else
												<input id='app_incoming_number_reestr' class='form-control {{$errors->has("app_incoming_number_reestr") ? print("inputError ") : print("")}}' name='app_incoming_number_reestr' value='{{$reestr->app_incoming_number_reestr}}' disabled />
											@endif
											@if($errors->has('app_incoming_number_reestr'))
												<label class='msgError'>{{$errors->first('app_incoming_number_reestr')}}</label>
											@endif
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<div class='row'>
										<div class="col-md-12">
											<label for='date_maturity_reestr'>Срок исполнения обязательств</label>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-10">
											<input id='date_maturity_reestr' class='form-control {{$errors->has("date_maturity_reestr") ? print("inputError ") : print("")}}' value='{{ old("date_maturity_reestr") ? old("date_maturity_reestr") : $reestr->date_maturity_reestr }}' readonly />
										</div>
										<div class="col-md-2">
											<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modal_date_maturity_reestr' title='Форма срока исполнения обязательств'><!--☼--><span class="ui-icon ui-icon-1-1" style='background-size: 355px; background-position-x: -49px; background-position-y: -179px;'></span></button>
										</div>
									</div>
								</div>
							</div>
							<div class='col-md-3'>
								<div class="form-group">
									@if(Auth::User()->hasRole()->role == 'Администратор')
										<!--<button class='btn btn-primary' data-toggle="modal" data-target="#scan" type='button' style='float: right; width: 184px;'>Сканы</button>-->
									@endif
									@if($contract->id_counterpartie_contract)
										<button class='btn btn-primary btn-href' type='button' style='float: right; margin-top: 24px;' href="{{route('counterpartie.edit', $contract->id_counterpartie_contract)}}">Информация о контрагенте</button>
									@endif
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class='form-group'>
									<label for='nameWork'>Наименование работ</label>
									<textarea id='nameWork' class='form-control {{$errors->has("item_contract") ? print("inputError ") : print("")}}' name='item_contract' type="text" style="width: 100%;" rows='4' {{$is_disabled}} required>{{ old('item_contract') ? old('item_contract') : $contract->item_contract }}</textarea>
									@if($errors->has('item_contract'))
										<label class='msgError'>{{$errors->first('item_contract')}}</label>
									@endif
								</div>
							</div>
							<div class="col-md-5">
								<div class='row'>
									<div class="col-md-6">
										<div class='form-group'>
											<div class='row'>
												<div class="col-md-12">
													<label>Сумма Д/К, руб. с НДС</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-9">
													<input id='amount_reestr' class='form-control check-number' type='text' value="{{old('amount_reestr') ? old('amount_reestr') : $reestr->amount_reestr}}" readonly />
												</div>
												<div class="col-md-3">
													<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modal_amount_reestr' title='Форма срока исполнения обязательств'><!--☼--><span class="ui-icon ui-icon-1-1" style='background-size: 355px; background-position-x: -49px; background-position-y: -179px;'></span></button>
												</div>
											</div>
										</div>
									</div>
									<div class='col-md-6'>
										<div class='form-group'>
											<div class='row'>
												<div class="col-md-12">
													<label style='font-size: 11px;'>Сумма начальная на тек.год, руб. с НДС</label>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													<input id='amount_year_reestr' name='amount_year_reestr' class='form-control check-number' type='text' value="{{old('amount_year_reestr') ? old('amount_year_reestr') : $reestr->amount_year_reestr}}" {{$is_disabled}}/>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-6">
										<div class='form-group'>
											<label style='font-size: 11px;'>Сумма по актам вып.раб с НДС, руб</label>
											<input id='amount_contract_reestr' class='form-control check-number' type='text' value="{{$reestr->amount_invoice_contract_reestr}}" readonly />
										</div>
									</div>
									<div class="col-md-6">
										<div class='form-group'>
											<label style='font-size: 11px;'>Сумма по актам вып.раб на тек.год с НДС, руб</label>
											<input id='amount_contract_year_reestr' class='form-control check-number' type='text' value="{{$reestr->year_amount_invoice_contract_reestr}}" readonly />
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class='form-group'>
									<label for="sel3">НДС</span></label>
									<select id="sel3" class='form-control {{$errors->has("vat_reestr") ? print("inputError ") : print("")}}' name='vat_reestr' {{$is_disabled}}>
										<option value='1' @if($reestr->vat_reestr) selected @endif>с НДС</option>
										<option value='0' @if(!$reestr->vat_reestr) selected @endif>НДС не облаг.</option>
									</select>
								</div>
								<div class='form-group'>
									@if(old('prepayment_reestr'))
										<input id='prepayment_reestr' class='form-check-input' type="checkbox" checked {{$is_disabled}}/>
									@else
										@if($reestr->prepayment_reestr || $reestr->percent_prepayment_reestr)
											<input id='prepayment_reestr' class='form-check-input' type="checkbox" checked {{$is_disabled}}/>
										@else
											<input id='prepayment_reestr' class='form-check-input' type="checkbox" {{$is_disabled}}/>
										@endif
									@endif
									<label for='prepayment_reestr'>Аванс предусмотрен</label>
									<div class='row'>
										<div class='col-md-6'>
											<div id='input_prepayment_contract' class='input_prepayment_contract' style='@if($reestr->prepayment_reestr || $reestr->percent_prepayment_reestr) display:block; @else display:none; @endif' {{$is_disabled}}>
												<label>%</label>
												@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел')
													<input name='percent_prepayment_reestr' class='form-control check-number' type='text' value="{{old('percent_prepayment_reestr') ? old('percent_prepayment_reestr') : $reestr->percent_prepayment_reestr}}" {{$is_disabled}}/>
												@else
													<input name='percent_prepayment_reestr' class='form-control' type='text' value="{{old('percent_prepayment_reestr') ? old('percent_prepayment_reestr') : $reestr->percent_prepayment_reestr}}" disabled />
												@endif
											</div>
										</div>
										<div class='col-md-6'>
											<div id='input_prepayment_contract' class='input_prepayment_contract' style='@if($reestr->prepayment_reestr || $reestr->percent_prepayment_reestr) display:block; @else display:none; @endif' {{$is_disabled}}>
												<label>Сумма служ. ПЭО</label>
												@if(Auth::User()->hasRole()->role != 'Отдел управления договорами' && Auth::User()->hasRole()->role != 'Десятый отдел')
													<input name='prepayment_reestr' class='form-control check-number' type='text' value="{{old('prepayment_reestr') ? old('prepayment_reestr') : $reestr->prepayment_reestr}}" {{$is_disabled}}/>
												@else
													<input name='prepayment_reestr' class='form-control' type='text' value="{{old('prepayment_reestr') ? old('prepayment_reestr') : $reestr->prepayment_reestr}}" disabled />
												@endif
											</div>
										</div>
									</div>
								</div>
								<div class='form-group'>
									@if(old('big_deal_reestr'))
										<input id='big_deal_reestr' name='big_deal_reestr' class='form-check-input' type="checkbox" checked {{$is_disabled}}/>
									@else
										@if($reestr->big_deal_reestr)
											<input id='big_deal_reestr' name='big_deal_reestr' class='form-check-input' type="checkbox" checked {{$is_disabled}}/>
										@else
											<input id='big_deal_reestr' name='big_deal_reestr' class='form-check-input' type="checkbox" {{$is_disabled}}/>
										@endif
									@endif
									<label for='big_deal_reestr'>Крупная сделка</label>
								</div>
							</div>
							<div class="col-md-2">
								<!--<button class='btn btn-primary btn-href' type='button' style='width: 154px;' href='{{ route("ten.show_contract", $contract->id) }}'>Комплектующие</button>-->
								<button class='btn btn-primary btn-href' style='float: right; width: 154px;' type='button' href="{{route('department.peo.show_additional_documents', $contract->id)}}">Догов. мат</button>
								@if(Auth::User()->hasRole()->role != 'Десятый отдел')
									<button class='btn btn-primary btn-href' type='button' style='float: right; width: 154px; margin-top: 5px;' href='{{route("department.contract_second.show", $contract->id)}}'>Наряды / Акты</button>
								@endif
								<button class='btn btn-primary' data-toggle="modal" data-target="#invoice" type='button' style='float: right; width: 154px; margin-top: 5px;'>Расчёты по Д/К</button>
								<button type='button' class="btn btn-primary" style="float: right; width: 154px; margin-top: 5px;" data-toggle="modal" data-target="#work_states">Выполнение работ</button>
								@if(Auth::User()->hasRole()->role != 'Планово-экономический отдел')
									<button type='button' class="btn btn-primary btn-href" style="float: right; width: 154px; margin-top: 5px;" href="{{route('tree_map.show_contract', $contract->id)}}">Граф договора</button>
								@endif
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
									<button type='submit' class="btn btn-primary" style="float: right;" {{$is_disabled}}>Сохранить договор</button>
								@endif
							</div>
						</div>
						<div class="row">
						</div>
					</form>
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
											@if(Auth::User()->hasRole()->role != 'Администрация')
												<button id='btn_add_state' class='btn btn-secondary' type='button' clear_date='{{date("d.m.Y", time())}}' action_state='{{ route("department.ekonomic.new_state",$contract->id)}}' style='margin-top: 10px;' {{$is_disabled}}>Добавить стадию выполнения</button>
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
											@if(Auth::User()->hasRole()->role != 'Администрация')
												<button id='add_new_resolution' type='button' class='btn btn-secondary' {{$is_disabled}}>Добавить скан</button>
											@endif
										</div>
										<div class="col-md-5">
											<button type='button' class='btn btn-secondary steps' first_step='#form_all_application' second_step='#form_delete_all_resolution' style='float: right;' {{$is_disabled}}>Удаление сканов</button>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-12">
											<select id='resolution_list' name='resolution_list' class='form-control {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
												@if(count($resolutions) > 0)
													@foreach($resolutions as $resolution)
														@if($resolution->deleted_at == null)
															@if($resolution->type_resolution == 1)
																<option value='http://{{$resolution->path_resolution}}' download_href='resolution_download/{{$resolution->id}}' delete_href='{{route("resolution_delete",$resolution->id)}}' style='color: rgb(239,19,198);'>{{$resolution->real_name_resolution}}</option>
															@else
																<option value='http://{{$resolution->path_resolution}}' download_href='resolution_download/{{$resolution->id}}' delete_href='{{route("resolution_delete",$resolution->id)}}'>{{$resolution->real_name_resolution}}</option>
															@endif
														@endif
													@endforeach
												@else
													<option></option>
												@endif
											</select>
										</div>
									</div>
									<div class='form-group row'>
										<div class="col-md-3">
											<button id='open_resolution' type='button' class='btn btn-primary' style='width: 122px;'>Открыть скан</button>
										</div>
										<div class="col-md-3">
											<!--<button id='download_resolution' type='button' class='btn btn-secondary' style='width: 122px;'>Скачать скан</button>-->
										</div>
										<div class="col-md-3">
											@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->surname == 'Бастрыкова')
												<button id='delete_resolution' type='button' class='btn btn-danger' style='width: 122px;'>Удалить скан</button>
											@endif
										</div>
										<div class="col-md-3">
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
								</div>
							</form>
							<form id='form_new_application' method='POST' file='true' enctype='multipart/form-data' action='{{route("resolution_store", $contract->id)}}' style='display: none;'>
								{{csrf_field()}}
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
									<div class='row'>
										<div class='col-md-12'>
											<label>Тип документа</label>
											<select id='type_resolution' type='text' name='type_resolution' class='form-control'>
												<option></option>
												@foreach($type_resolutions as $type_resolution)
													<option value='{{$type_resolution->id}}'>{{$type_resolution->name_type_resolution}}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
									<button id='btn_close_new_application' type="button" class="btn btn-secondary">Закрыть</button>
								</div>
							</form>
							<form id='form_delete_all_resolution' method='POST' action="{{route('resolution_update')}}" style='display: none;'>
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="allApplicationModalLabel">Удаление резолюций</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class='modal-body'>
									<div class='form-group row'>
										<div class="col-md-12">
											<table class="table" style='margin: 0 auto;'>
												<thead>
													<tr>
														<th>Удаление</th>
														<th>Название резолюции</th>
													</tr>
												</thead>
												<tbody>
													<?php $count_check_message = 0; $count_old_delete = 0; ?>
													@foreach($resolutions as $resolution)
														@if($resolution->deleted_at != null)
															<tr class='rowsContract cursorPointer rowsMessage marked' id_application='{{$resolution->id}}' for_check='check_message{{$count_check_message}}' for_input='select_message{{$count_check_message}}'>
																<td>
																	<input id='check_message{{$count_check_message}}' class='form-check-input' type="checkbox" checked />
																</td>
																<td>{{$resolution->real_name_resolution}}</td>
																<input id='select_message{{$count_check_message}}' name='select_message[{{$count_check_message}}]' value='{{$resolution->id}}' style='display: none'/>
																<input name='select_old_delete[{{$count_old_delete}}]' value='{{$resolution->id}}' style='display: none'/>
															</tr>
														@else
															<tr class='rowsContract cursorPointer rowsMessage' id_application='{{$resolution->id}}' for_check='check_message{{$count_check_message}}'>
																<td>
																	<input id='check_message{{$count_check_message}}' class='form-check-input' type="checkbox"/>
																</td>
																<td>{{$resolution->real_name_resolution}}</td>
															</tr>
														@endif
														<?php $count_check_message++; $count_old_delete++; ?>
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' type='button'>Удалить/Восстановить</button>
									<button type="button" class="btn btn-secondary steps" first_step='#form_delete_all_resolution' second_step='#form_all_application'>Закрыть</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Счета -->
				<div class="modal fade" id="invoice" tabindex="-1" role="dialog" aria-labelledby="invoiceModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="invoiceModalLabel">Счета</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-md-12">
										<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
											<thead>
												<tr>
													<th rowspan='7' style='text-align: center; vertical-align: middle; max-width: 94px;'>Оплата и исполнение договора</th>
												</tr>
												<tr>
													<th  colspan='2'>Выполнение</th>
													<th>{{$amount_invoices}} р.</th>
												</tr>
												<tr>
													<th  colspan='2'>Аванс</th>
													<th>{{$amount_prepayments}} р.</th>
												</tr>
												<tr>
													<th  colspan='2'>Окончательный расчет</th>
													<th>{{$amount_payments}} р.</th>
												</tr>
												<tr>
													<th rowspan='2' style='vertical-align: middle;'>Задолженность</th>
													<th>Дебет</th>
													<th>{{number_format($debet, 2, ',', ' ')}} р.</th>
												</tr>
												<tr>
													<th>Кредит</th>
													<th>{{number_format($kredit, 2, ',', ' ')}} р.</th>
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
				<!-- Модальное окно срок исполнения обязательств -->
				<div class="modal fade" id="modal_date_maturity_reestr" tabindex="-1" role="dialog" aria-labelledby="dateMaturityModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="dateMaturityModalLabel">Срок исполнения обязательств</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div id='table_date_maturity_reestr' class='row'>
									@if(count($all_reest_date_maturity) > 0)
										<div class='col-md-12'>
											<table class="table" style='margin: 0 auto;'>
												<thead>
													<tr>
														<th>Наименование</th>
														<th>Срок действия Д/К</th>
														<th>До</th>
														<th>Изменить</th>
														<th>Удалить</th>
													</tr>
												</thead>
												<tbody>
													@foreach($all_reest_date_maturity as $reestr_date_maturity)
														<tr class='rowsContract'>
															<td>{{$reestr_date_maturity->name_date_maturity}}</td>
															<td>{{$reestr_date_maturity->term_date_maturity}}</td>
															<td>{{$reestr_date_maturity->end_date_maturity}}</td>
															<td><button class='btn btn-primary btn-update-date-maturity steps' type='button' reestr_date_maturity='{{$reestr_date_maturity}}' action_update='{{ route("reestr.date_maturity.update", $reestr_date_maturity->id)}}' first_step='#table_date_maturity_reestr' second_step='#update_date_maturity_reestr'>Изменить</button></td>
															<td><button class='btn btn-danger btn-href' type='button' href='{{route("reestr.date_maturity.destroy", $reestr_date_maturity->id)}}'>Удалить</button></td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									@endif
									<div class='col-md-12'>
										@if(Auth::User()->hasRole()->role != 'Администрация')
											<button class='btn btn-primary steps' type='button' style='margin-top: 10px;' first_step='#table_date_maturity_reestr' second_step='#new_date_maturity_reestr'>Добавить срок исполнения обязательств</button>
										@endif
									</div>
								</div>
								<div id='new_date_maturity_reestr' class='row' style='display: none;'>
									<form method='POST' action='{{route("reestr.date_maturity.store", $contract->id)}}'>
										{{csrf_field()}}
										<div class='col-md-12 form-group'>
											<label for='name_date_maturity' class='col-md-3 col-form-label'>Наименование</label>
											<div class='col-md-9'>
												<input id='name_date_maturity' class='form-control {{$errors->has("name_date_maturity") ? print("inputError ") : print("")}}' type='text' name='name_date_maturity' maxlength='70' required />
												@if($errors->has('name_date_maturity'))
													<label class='msgError'>{{$errors->first('name_date_maturity')}}</label>
												@endif
											</div>
										</div>
										<div class='col-md-12 form-group'>
											<label for='term_date_maturity' class='col-md-3 col-form-label'>Срок исполнения</label>
											<div class='col-md-9'>
												<input id='term_date_maturity' class='form-control {{$errors->has("term_date_maturity") ? print("inputError ") : print("")}}' type='text' name='term_date_maturity' required />
												@if($errors->has('term_date_maturity'))
													<label class='msgError'>{{$errors->first('term_date_maturity')}}</label>
												@endif
											</div>
										</div>
										<div class='col-md-12 form-group'>
											<label for='end_date_maturity' class='col-md-3 col-form-label'>До</label>
											<div class='col-md-9'>
												<input id='end_date_maturity' class='datepicker form-control {{$errors->has("end_date_maturity") ? print("inputError ") : print("")}}' type='text' name='end_date_maturity'/>
												@if($errors->has('end_date_maturity'))
													<label class='msgError'>{{$errors->first('end_date_maturity')}}</label>
												@endif
											</div>
										</div>
										<div class='col-md-12 form-group'>
											<div class='row'>
												<div class='col-md-6'>
												</div>
												<div class='col-md-3'>
													<button type="submit" class="btn btn-primary">Добавить</button>
												</div>
												<div class='col-md-3'>
													<button type="button" class="btn btn-secondary steps" first_step='#new_date_maturity_reestr' second_step='#table_date_maturity_reestr'>Назад</button>
												</div>
											</div>
										</div>
									</form>
								</div>
								<div id='update_date_maturity_reestr' class='row' style='display: none;'>
									<form id='form_update_date_maturity_reestr' method='POST' action=''>
										{{csrf_field()}}
										<div class='col-md-12 form-group'>
											<label for='update_name_date_maturity' class='col-md-3 col-form-label'>Наименование</label>
											<div class='col-md-9'>
												<input id='update_name_date_maturity' class='form-control {{$errors->has("name_date_maturity") ? print("inputError ") : print("")}}' type='text' name='name_date_maturity' maxlength='70' required />
												@if($errors->has('name_date_maturity'))
													<label class='msgError'>{{$errors->first('name_date_maturity')}}</label>
												@endif
											</div>
										</div>
										<div class='col-md-12 form-group'>
											<label for='update_term_date_maturity' class='col-md-3 col-form-label'>Срок исполнения</label>
											<div class='col-md-9'>
												<input id='update_term_date_maturity' class='form-control {{$errors->has("term_date_maturity") ? print("inputError ") : print("")}}' type='text' name='term_date_maturity' required />
												@if($errors->has('term_date_maturity'))
													<label class='msgError'>{{$errors->first('term_date_maturity')}}</label>
												@endif
											</div>
										</div>
										<div class='col-md-12 form-group'>
											<label for='update_end_date_maturity' class='col-md-3 col-form-label'>До</label>
											<div class='col-md-9'>
												<input id='update_end_date_maturity' class='datepicker form-control {{$errors->has("end_date_maturity") ? print("inputError ") : print("")}}' type='text' name='end_date_maturity'/>
												@if($errors->has('end_date_maturity'))
													<label class='msgError'>{{$errors->first('end_date_maturity')}}</label>
												@endif
											</div>
										</div>
										<div class='col-md-12 form-group'>
											<div class='row'>
												<div class='col-md-6'>
												</div>
												<div class='col-md-3'>
													<button type="submit" class="btn btn-primary">Сохранить</button>
												</div>
												<div class='col-md-3'>
													<button type="button" class="btn btn-secondary steps" first_step='#update_date_maturity_reestr' second_step='#table_date_maturity_reestr'>Назад</button>
												</div>
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
				<!-- Модальное окно сумм -->
				<div class="modal fade" id="modal_amount_reestr" tabindex="-1" role="dialog" aria-labelledby="amountModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="amountModalLabel">Сумма Д/К</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div id='table_amount_reestr' class='row'>
									@if(count($all_reestr_amount) > 0)
										<div class='col-md-12'>
											<table class="table" style='margin: 0 auto;'>
												<thead>
													<tr>
														<th>Наименование</th>
														<th>Сумма</th>
														<th>Валюта</th>
														<th>Параметры</th>
														<th>Изменить</th>
														<th>Удалить</th>
													</tr>
												</thead>
												<tbody>
													@foreach($all_reestr_amount as $reestr_amount)
														<tr class='rowsContract'>
															<td>{{$reestr_amount->name_amount}}</td>
															<td>{{$reestr_amount->value_amount}}</td>
															<td>{{$reestr_amount->name_unit}}</td>
															<td>
																<?php
																	if ($reestr_amount->vat_amount)
																		echo 'НДС<br/>';
																	if ($reestr_amount->approximate_amount)
																		echo 'Ориентировочная<br/>';
																	if ($reestr_amount->fixed_amount)
																		echo 'Фиксированная<br/>';
																?>
															</td>
															<td><button class='btn btn-primary btn-update-amount steps' type='button' reestr_amount='{{$reestr_amount}}' action_update='{{ route("reestr.amount.update", $reestr_amount->id)}}' first_step='#table_amount_reestr' second_step='#update_amount_reestr'>Изменить</button></td>
															<td><button class='btn btn-danger btn-href' type='button' href='{{route("reestr.amount.destroy", $reestr_amount->id)}}'>Удалить</button></td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									@endif
									<div class='col-md-12'>
										@if(Auth::User()->hasRole()->role != 'Администрация')
											<button class='btn btn-primary steps' type='button' style='margin-top: 10px;' first_step='#table_amount_reestr' second_step='#new_amount_reestr'>Добавить сумму</button>
										@endif
									</div>
								</div>
								<div id='new_amount_reestr' class='row' style='display: none;'>
									<form method='POST' action='{{route("reestr.amount.store", $contract->id)}}'>
										{{csrf_field()}}
										<div class='col-md-12 form-group'>
											<label for='name_amount' class='col-md-3 col-form-label'>Наименование</label>
											<div class='col-md-9'>
												<input id='name_amount' class='form-control {{$errors->has("name_amount") ? print("inputError ") : print("")}}' type='text' name='name_amount' maxlength='40' required />
												@if($errors->has('name_amount'))
													<label class='msgError'>{{$errors->first('name_amount')}}</label>
												@endif
											</div>
										</div>
										<div class='col-md-12 form-group'>
											<label for='value_amount' class='col-md-3 col-form-label'>Сумма</label>
											<div class='col-md-9'>
												<input id='value_amount' class='check-number form-control {{$errors->has("value_amount") ? print("inputError ") : print("")}}' type='text' name='value_amount' required />
												@if($errors->has('value_amount'))
													<label class='msgError'>{{$errors->first('value_amount')}}</label>
												@endif
											</div>
										</div>
										<div class='col-md-12 form-group'>
											<label for='unit_amount' class='col-md-3 col-form-label'>Валюта</label>
											<div class='col-md-9'>
												<select class='form-control {{$errors->has("unit_amount") ? print("inputError ") : print("")}}' name='unit_amount'>
													<option></option>
													@foreach($units as $unit)
														<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class='col-md-3 form-group'>
										</div>
										<div class='col-md-3 form-group'>
											<label for='vat_amount'>НДС</label>
											<input id='vat_amount' class='form-check-input' name='vat_amount' type="checkbox"/>
										</div>
										<div class='col-md-3 form-group'>
											<label for='approximate_amount'>Ориентировочная</label>
											<input id='approximate_amount' class='form-check-input' name='approximate_amount' type="checkbox"/>
										</div>
										<div class='col-md-3 form-group'>
											<label for='fixed_amount'>Фиксированная</label>
											<input id='fixed_amount' class='form-check-input' name='fixed_amount' type="checkbox"/>
										</div>
										<div class='col-md-12 form-group'>
											<div class='row'>
												<div class='col-md-6'>
												</div>
												<div class='col-md-3'>
													<button type="submit" class="btn btn-primary">Добавить</button>
												</div>
												<div class='col-md-3'>
													<button type="button" class="btn btn-secondary steps" first_step='#new_amount_reestr' second_step='#table_amount_reestr'>Назад</button>
												</div>
											</div>
										</div>
									</form>
								</div>
								<div id='update_amount_reestr' class='row' style='display: none;'>
									<form id='form_update_amount_reestr' method='POST' action=''>
										{{csrf_field()}}
										<div class='col-md-12 form-group'>
											<label for='update_name_amount' class='col-md-3 col-form-label'>Наименование</label>
											<div class='col-md-9'>
												<input id='update_name_amount' class='form-control {{$errors->has("name_amount") ? print("inputError ") : print("")}}' type='text' name='name_amount' maxlength='40' required />
												@if($errors->has('name_amount'))
													<label class='msgError'>{{$errors->first('name_amount')}}</label>
												@endif
											</div>
										</div>
										<div class='col-md-12 form-group'>
											<label for='update_value_amount' class='col-md-3 col-form-label'>Сумма</label>
											<div class='col-md-9'>
												<input id='update_value_amount' class='check-number form-control {{$errors->has("value_amount") ? print("inputError ") : print("")}}' type='text' name='value_amount' required />
												@if($errors->has('value_amount'))
													<label class='msgError'>{{$errors->first('value_amount')}}</label>
												@endif
											</div>
										</div>
										<div class='col-md-12 form-group'>
											<label for='update_unit_amount' class='col-md-3 col-form-label'>Валюта</label>
											<div class='col-md-9'>
												<select id='update_unit_amount' class='form-control {{$errors->has("unit_amount") ? print("inputError ") : print("")}}' name='unit_amount'>
													<option></option>
													@foreach($units as $unit)
														<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class='col-md-3 form-group'>
										</div>
										<div class='col-md-3 form-group'>
											<label for='update_vat_amount'>НДС</label>
											<input id='update_vat_amount' class='form-check-input' name='vat_amount' type="checkbox"/>
										</div>
										<div class='col-md-3 form-group'>
											<label for='update_approximate_amount'>Ориентировочная</label>
											<input id='update_approximate_amount' class='form-check-input' name='approximate_amount' type="checkbox"/>
										</div>
										<div class='col-md-3 form-group'>
											<label for='update_fixed_amount'>Фиксированная</label>
											<input id='update_fixed_amount' class='form-check-input' name='fixed_amount' type="checkbox"/>
										</div>
										<div class='col-md-12 form-group'>
											<div class='row'>
												<div class='col-md-6'>
												</div>
												<div class='col-md-3'>
													<button type="submit" class="btn btn-primary">Сохранить</button>
												</div>
												<div class='col-md-3'>
													<button type="button" class="btn btn-secondary steps" first_step='#update_amount_reestr' second_step='#table_amount_reestr'>Назад</button>
												</div>
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
			@endif
		@else
			<div class="alert alert-danger">
				Необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection