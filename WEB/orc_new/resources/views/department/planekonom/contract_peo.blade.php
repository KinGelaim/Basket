@extends('layouts.header')

@section('title')
	Договор
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Отдел управления договорами' OR Auth::User()->hasRole()->role == 'Администрация')
				@if (Route::has('login'))
					<div class="top-right links">
						
					</div>
				@endif
				
				@if($contract)
					<div class="content">
						<form method='POST' action='{{route("department.ekonomic.update_peo", $contract->id)}}'>
							{{csrf_field()}}
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for='numberContract'>Номер договора</label>
										<input id='numberContract' class='form-control {{$errors->has("number_contract") ? print("inputError ") : print("")}}' name='number_contract' type='text' value='{{old("number_contract") ? old("number_contract") : $contract->number_contract}}'/>
										@if($errors->has('number_contract'))
											<label class='msgError'>{{$errors->first('number_contract')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-1">
									<div class='form-check'>
										<label class='form-check-label' for='gozCheck'>ГОЗ</label>
										@if(old('goz_contract'))
											<input id='gozCheck' class='form-check-input' name='goz_contract' type="checkbox" onclick="$('#exportCheck').prop('checked', !$('#exportCheck').prop('checked'))" checked />
										@else
											@if($contract->id_goz_contract == 1)
												<input id='gozCheck' class='form-check-input' name='goz_contract' type="checkbox" onclick="$('#exportCheck').prop('checked', !$('#exportCheck').prop('checked'))" checked />
											@else
												<input id='gozCheck' class='form-check-input' name='goz_contract' type="checkbox" onclick="$('#exportCheck').prop('checked', !$('#exportCheck').prop('checked'))"/>
											@endif
										@endif
									</div>
									<div class='form-check'>
										<label class='form-check-label' for='exportCheck'>Экспорт</label>
										@if(old('goz_contract'))
											<input id='exportCheck' class='form-check-input' type="checkbox" onclick="$('#gozCheck').prop('checked', !$('#gozCheck').prop('checked'))" />
										@else
											@if($contract->id_goz_contract != 1)
												<input id='exportCheck' class='form-check-input' type="checkbox" onclick="$('#gozCheck').prop('checked', !$('#gozCheck').prop('checked'))" checked />
											@else
												<input id='exportCheck' class='form-check-input' type="checkbox" onclick="$('#gozCheck').prop('checked', !$('#gozCheck').prop('checked'))"/>
											@endif
										@endif
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="sel3">Вид работ</span></label>
										<select id="sel3" class='form-control {{$errors->has("id_view_work_contract") ? print("inputError ") : print("")}}' name='id_view_work_contract'>
											<option></option>
											@if($viewWorks)
												@foreach($viewWorks as $viewWork)
													@if(old('id_view_work_contract'))
														@if(old('id_view_work_contract') == $viewWork->id)
															<option value='{{$viewWork->id}}' selected>{{ $viewWork->name_view_work }}</option>
														@else
															<option value='{{$viewWork->id}}'>{{ $viewWork->name_view_work }}</option>
														@endif
													@else
														@if($contract->id_view_work_contract == $viewWork->id)
															<option value='{{$viewWork->id}}' selected>{{ $viewWork->name_view_work }}</option>
														@else
															<option value='{{$viewWork->id}}'>{{ $viewWork->name_view_work }}</option>
														@endif
													@endif
												@endforeach
											@endif
										</select>
										@if($errors->has('id_view_work_contract'))
											<label class='msgError'>{{$errors->first('id_view_work_contract')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-1">
									<label class='form-check-label' for='dateCheck2'>ОТ</label>
									@if(old('ot_date_contact'))
										<input id='dateCheck' class='form-check-input dateCheck' type="checkbox" name='ot_date_contact' checked />
									@else
										@if($contract->date_contact)
											<input id='dateCheck' class='form-check-input dateCheck' type="checkbox" name='ot_date_contact' checked />
										@else
											<input id='dateCheck' class='form-check-input dateCheck' type="checkbox" name='ot_date_contact'/>
										@endif
									@endif
								</div>
								<div class="col-md-3">
									<div class='form-group'>
										@if(old('date_contact'))
											<span class='dateInput'>
												<label>Дата</label>
												<input class='datepicker form-control {{$errors->has("date_contact") ? print("inputError ") : print("")}}' name='date_contact' value='{{old("date_contact") ? old("date_contact") : $contract->date_contact}}'/>
												@if($errors->has('date_contact'))
													<label class='msgError'>{{$errors->first('date_contact')}}</label>
												@endif
											</span>
										@else
											@if($contract->date_contact)
												<span class='dateInput'>
													<label>Дата</label>
													<input class='datepicker form-control {{$errors->has("date_contact") ? print("inputError ") : print("")}}' name='date_contact' value='{{old("date_contact") ? old("date_contact") : $contract->date_contact}}'/>
													@if($errors->has('date_contact'))
														<label class='msgError'>{{$errors->first('date_contact')}}</label>
													@endif
												</span>
											@else
												<span class='dateInput' style='display: none;'>
													<label>Дата</label>
													<input class='datepicker form-control {{$errors->has("date_contact") ? print("inputError ") : print("")}}' name='date_contact' value='{{old("date_contact") ? old("date_contact") : date("d.m.Y", time())}}'/>
													@if($errors->has('date_contact'))
														<label class='msgError'>{{$errors->first('date_contact')}}</label>
													@endif
												</span>
											@endif
										@endif
									</div>
								</div>
								<div class="col-md-1">
									<div class="form-group">
										<label for='year_contract'>Год</label>
										<input id='year_contract' class='form-control {{$errors->has("year_contract") ? print("inputError ") : print("")}}' name='year_contract' type='text' value='{{old("year_contract") ? old("year_contract") : $contract->year_contract}}'/>
										@if($errors->has('year_contract'))
											<label class='msgError'>{{$errors->first('year_contract')}}</label>
										@endif
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<label>Название предприятия</label>
								</div>
								<div class="col-md-3">
									<div class='form-group row'>
										<label for='countContract' class='col-sm-5 col-form-label'>Всего договоров</label>
										<div class='col-sm-7'>
											<input id='countContract' class='form-control {{$errors->has("all_count_contract") ? print("inputError ") : print("")}}' type='number' value='{{old("all_count_contract") ? old("all_count_contract") : $contract->all_count_contract}}' name='all_count_contract'/>
											@if($errors->has('all_count_contract'))
												<label class='msgError'>{{$errors->first('all_count_contract')}}</label>
											@endif
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class='form-group row'>
										<label for='bigContract2' class='col-sm-5 col-form-label'>Крупных сделок</label>
										<div class='col-sm-7'>
											<input id='bigContract2' class='form-control {{$errors->has("big_deal_contract") ? print("inputError ") : print("")}}' type='number' value='{{old("big_deal_contract") ? old("big_deal_contract") : $contract->big_deal_contract}}' name='big_deal_contract'/>
											@if($errors->has('big_deal_contract'))
												<label class='msgError'>{{$errors->first('big_deal_contract')}}</label>
											@endif
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<select id="sel4" class='form-control {{$errors->has("id_counterpartie_contract") ? print("inputError ") : print("")}}' name='id_counterpartie_contract'>
											<option></option>
											@if($counterparties)
												@foreach($counterparties as $counterpartie)
													@if(old('id_counterpartie_contract'))
														@if(old('id_counterpartie_contract') == $counterpartie->id)
															<option value='{{$counterpartie->id}}' selected>{{ $counterpartie->name }}</option>
														@else
															<option value='{{$counterpartie->id}}'>{{ $counterpartie->name }}</option>
														@endif
													@else
														@if($contract->id_counterpartie_contract == $counterpartie->id)
															<option value='{{$counterpartie->id}}' selected>{{ $counterpartie->name }}</option>
														@else
															<option value='{{$counterpartie->id}}'>{{ $counterpartie->name }}</option>
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
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="row">
										<div class="col-md-12">
											<div class='form-group'>
												<label for='nameWork'>Наименование работ</label>
												<textarea id='nameWork' class='form-control {{$errors->has("name_work_contract") ? print("inputError ") : print("")}}' name='name_work_contract' type="text" style="width: 100%;" rows='4'>{{ old('name_work_contract') ? old('name_work_contract') : $contract->name_work_contract }}</textarea>
												@if($errors->has('name_work_contract'))
													<label class='msgError'>{{$errors->first('name_work_contract')}}</label>
												@endif
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<label for='lastCompleteContract'>Состояние заключения договора</label>
										</div>								
									</div>
									<div class="row">
										<div class="col-md-12">
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
										<div class="col-md-12">
											<div class='form-group'>
												@if(count($states) > 0)
													@if($states[count($states) - 1]->name_state == "Заключен" OR $states[count($states) - 1]->name_state == "Заключён")
														<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%; display: none;" rows='3' disabled></textarea>
													@else
														<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%;" rows='3' disabled>{{$states[count($states) - 1]->name_state}}</textarea>
													@endif
												@else
													<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%;" rows='3' disabled></textarea>
												@endif
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class="btn btn-primary" data-toggle="modal" data-target="#history_states" type='button'>История состояний</button>
										</div>
									</div>
								</div>
								<div class="col-md-9">
									<div class="row">
										<div class="col-md-4">
											<div class='form-group row'>
												<label for='concludedContract' class='col-md-6 col-form-label'>Заключено</label>
												<div class='col-md-6'>
													<input id='concludedContract' class='form-control {{$errors->has("concluded_count_contract") ? print("inputError ") : print("")}}' type='number' value='{{ old("concluded_count_contract") ? old("concluded_count_contract") : $contract->concluded_count_contract }}' name='concluded_count_contract'/>
													@if($errors->has('concluded_count_contract'))
														<label class='msgError'>{{$errors->first('concluded_count_contract')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class='form-group row'>
												<label for='amountConcludedContract' class='col-md-6 col-form-label'>Сумма с НДС, т.р.</label>
												<div class='col-md-6'>
													<input id='amountConcludedContract' class='form-control {{$errors->has("amount_concluded_contract") ? print("inputError ") : print("")}}' type='text' name='amount_concluded_contract' value='{{ old("amount_concluded_contract") ? old("amount_concluded_contract") : $contract->amount_concluded_contract }}'/>
													@if($errors->has('amount_concluded_contract'))
														<label class='msgError'>{{$errors->first('amount_concluded_contract')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class='form-group row'>
												<div class='col-md-12'>
													<button class='btn btn-primary' data-toggle="modal" data-target="#scan" type='button'>Резолюция</button>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">
											<div class='form-group row'>
												<label for='formalizationContract' class='col-md-6 col-form-label'>На оформлении</label>
												<div class='col-md-6'>
													<input id='formalizationContract' class='form-control {{$errors->has("formalization_count_contract") ? print("inputError ") : print("")}}' type='number' value='{{ old("formalization_count_contract") ? old("formalization_count_contract") : $contract->formalization_count_contract }}' name='formalization_count_contract'/>
													@if($errors->has('formalization_count_contract'))
														<label class='msgError'>{{$errors->first('formalization_count_contract')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class='form-group row'>
												<label for='amountFormalizationContract' class='col-md-6 col-form-label'>Сумма с НДС, т.р.</label>
												<div class='col-md-6'>
													<input id='amountFormalizationContract' class='form-control {{$errors->has("amount_formalization_contract") ? print("inputError ") : print("")}}' type='text' name='amount_formalization_contract' value='{{ old("amount_formalization_contract") ? old("amount_formalization_contract") : $contract->amount_formalization_contract }}'/>
													@if($errors->has('amount_formalization_contract'))
														<label class='msgError'>{{$errors->first('amount_formalization_contract')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class="col-md-4">
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">
											<label for='implementationInput2'>Выполнение</label>
										</div>
										<div class="col-md-4">
											<div class='form-group row'>
												<label for='amountImplementationContract' class='col-md-6 col-form-label'>Сумма с НДС, т.р.</label>
												<div class='col-md-6'>
													<input id='amountImplementationContract' class='form-control {{$errors->has("amoun_implementation_contract") ? print("inputError ") : print("")}}' type='text' name='amoun_implementation_contract' value='{{ old("amoun_implementation_contract") ? old("amoun_implementation_contract") : $contract->amoun_implementation_contract }}'/>
													@if($errors->has('amoun_implementation_contract'))
														<label class='msgError'>{{$errors->first('amoun_implementation_contract')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class='form-check'>
												@if(old("comment_implementation_contract"))
													<input id='implementationСontract' class='form-check-input implementationCheck' type="checkbox" name='check_implementation_contract'/>
												@else
													@if($contract->comment_implementation_contract !== null)
														<input id='implementationСontract' class='form-check-input implementationCheck' type="checkbox" name='check_implementation_contract'/>
													@else
														<input id='implementationСontract' class='form-check-input implementationCheck' type="checkbox" name='check_implementation_contract' checked />
													@endif
												@endif
												<label class='form-check-label' for='implementationСontract'>Выполнен</label>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											@if(old("comment_implementation_contract"))
												<input id='implementationInput' class='form-control implementationInput' type='text' name='comment_implementation_contract' value='{{old("comment_implementation_contract") ? old("comment_implementation_contract") : $contract->comment_implementation_contract}}'/>
											@else
												@if($contract->comment_implementation_contract !== null)
													<input id='implementationInput' class='form-control implementationInput' type='text' name='comment_implementation_contract' value='{{old("comment_implementation_contract") ? old("comment_implementation_contract") : $contract->comment_implementation_contract}}'/>
												@else
													<input id='implementationInput' class='form-control implementationInput' type='text' name='comment_implementation_contract' value='{{old("comment_implementation_contract") ? old("comment_implementation_contract") : $contract->comment_implementation_contract}}' style='display: none;'/>
												@endif
											@endif
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-12">
													<label>Выставление счетов с НДС, тыс. руб.</label>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class='form-group row'>
														<label for='prepaymentScoreContract' class='col-md-6 col-form-label'>Аванс</label>
														<div class='col-md-6'>
															<input id='prepaymentScoreContract' class='form-control {{$errors->has("prepayment_score_contract") ? print("inputError ") : print("")}}' type='text' name='prepayment_score_contract' value='{{ old("prepayment_score_contract") ? old("prepayment_score_contract") : $contract->prepayment_score_contract}}'/>
															@if($errors->has('prepayment_score_contract'))
																<label class='msgError'>{{$errors->first('prepayment_score_contract')}}</label>
															@endif
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class='form-group row'>
														<label for='invoiceScoreContract' class='col-md-6 col-form-label'>Счет-фактура</label>
														<div class='col-md-6'>
															<input id='invoiceScoreContract' class='form-control {{$errors->has("invoice_score_contract") ? print("inputError ") : print("")}}' type='text' name='invoice_score_contract' value='{{ old("invoice_score_contract") ? old("invoice_score_contract") : $contract->invoice_score_contract}}'/>
															@if($errors->has('invoice_score_contract'))
																<label class='msgError'>{{$errors->first('invoice_score_contract')}}</label>
															@endif
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-12">
													<label>Оплата с НДС, тыс. руб.</label>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class='form-group row'>
														<label for='prepaymentPaymentContract' class='col-md-6 col-form-label'>Аванс</label>
														<div class='col-md-6'>
															<input id='prepaymentPaymentContract' class='form-control {{$errors->has("prepayment_payment_contract") ? print("inputError ") : print("")}}' type='text' name='prepayment_payment_contract' value='{{ old("prepayment_payment_contract") ? old("prepayment_payment_contract") : $contract->prepayment_payment_contract}}'/>
															@if($errors->has('prepayment_payment_contract'))
																<label class='msgError'>{{$errors->first('prepayment_payment_contract')}}</label>
															@endif
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class='form-group row'>
														<label for='amountPaymentContract' class='col-md-6 col-form-label'>Окончательный расчет</label>
														<div class='col-md-6'>
															<input id='amountPaymentContract' class='form-control {{$errors->has("amount_payment_contract") ? print("inputError ") : print("")}}' type='text' name='amount_payment_contract' value='{{ old("amount_payment_contract") ? old("amount_payment_contract") : $contract->amount_payment_contract}}'/>
															@if($errors->has('amount_payment_contract'))
																<label class='msgError'>{{$errors->first('amount_payment_contract')}}</label>
															@endif
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class='form-group row'>
														<label for='allPaymentContract' class='col-md-6 col-form-label'>Всего</label>
														<div class='col-md-6'>
															<input id='allPaymentContract' class='form-control' type='text' name='all_payment_contract' disabled value='{{old("all_payment_contract") ? old("all_payment_contract") : $contract->prepayment_payment_contract + $contract->amount_payment_contract}}'/>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<button class="btn btn-secondary" style="float: right;" type='button'>Дополнительные соглашения</button>
								</div>
								<div class="col-md-3">
									<button class="btn btn-secondary" style="float: left;" type='button'>Протокол разногласий / согласования</button>
								</div>
								<div class="col-md-3">
									@if(Auth::User()->hasRole()->role != 'Администрация')
										<button type='submit' class="btn btn-primary" style="float: right;">Сохранить договор</button>
									@endif
								</div>
							</div>
						</form>
					</div>
				@else
					<div class="content">
						<form method='POST' action="{{route('department.ekonomic.store')}}">
							{{csrf_field()}}
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for='numberContract2'>Номер договора</label>
										<input id='numberContract2' class='form-control {{$errors->has("number_contract") ? print("inputError ") : print("")}}' name='number_contract' type='text' value='{{old("number_contract")}}'/>
										@if($errors->has('number_contract'))
											<label class='msgError'>{{$errors->first('number_contract')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-1">
									<div class='form-check'>
										<label class='form-check-label' for='gozCheck2'>ГОЗ</label>
										<input id='gozCheck2' class='form-check-input' name='goz_contract' type="checkbox" {{old("goz_contract") ? print("checked ") : print("")}}/>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="sel3">Вид работ</span></label>
										<select id="sel3" class='form-control {{$errors->has("id_view_work_contract") ? print("inputError ") : print("")}}' name='id_view_work_contract'>
											<option></option>
											@if($viewWorks)
												@foreach($viewWorks as $viewWork)
													<option value='{{$viewWork->id}}'>{{ $viewWork->name_view_work }}</option>
												@endforeach
											@endif
										</select>
										@if($errors->has('id_view_work_contract'))
											<label class='msgError'>{{$errors->first('id_view_work_contract')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-1">
									<label class='form-check-label' for='dateCheck2'>ОТ</label>
									<input id='dateCheck2' class='form-check-input dateCheck' type="checkbox" name='ot_date_contact' {{old("ot_date_contact") ? print("checked ") : print("")}}/>
								</div>
								<div class="col-md-3">
									<div class='form-group'>
										<span class='dateInput' {{old("ot_date_contact") ? print("") : print("style='display: none;' ")}}>
											<label>Дата</label>
											<input class='datepicker form-control {{$errors->has("date_contact") ? print("inputError ") : print("")}}' name='date_contact' value='{{old("date_contact") ? old("date_contact") : date("d.m.Y", time())}}'/>
											@if($errors->has('date_contact'))
												<label class='msgError'>{{$errors->first('date_contact')}}</label>
											@endif
										</span>
									</div>
								</div>
								<div class="col-md-1">
									<div class="form-group">
										<label for='year_contract'>Год</label>
										<input id='year_contract' class='form-control {{$errors->has("year_contract") ? print("inputError ") : print("")}}' name='year_contract' type='text' value='{{old("year_contract")}}'/>
										@if($errors->has('year_contract'))
											<label class='msgError'>{{$errors->first('year_contract')}}</label>
										@endif
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<label>Название предприятия</label>
								</div>
								<div class="col-md-3">
									<div class='form-group row'>
										<label for='countContract2' class='col-sm-5 col-form-label'>Всего договоров</label>
										<div class='col-sm-7'>
											<input id='countContract2' class='form-control {{$errors->has("all_count_contract") ? print("inputError ") : print("")}}' type='number' value='1' name='all_count_contract'/>
											@if($errors->has('all_count_contract'))
												<label class='msgError'>{{$errors->first('all_count_contract')}}</label>
											@endif
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class='form-group row'>
										<label for='bigContract2' class='col-sm-5 col-form-label'>Крупных сделок</label>
										<div class='col-sm-7'>
											<input id='bigContract2' class='form-control {{$errors->has("big_deal_contract") ? print("inputError ") : print("")}}' type='number' value='0' name='big_deal_contract'/>
											@if($errors->has('big_deal_contract'))
												<label class='msgError'>{{$errors->first('big_deal_contract')}}</label>
											@endif
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<select id="sel4" class='form-control {{$errors->has("id_counterpartie_contract") ? print("inputError ") : print("")}}' name='id_counterpartie_contract'>
											<option></option>
											@if($counterparties)
												@foreach($counterparties as $counterpartie)
													<option value='{{$counterpartie->id}}'>{{ $counterpartie->name }}</option>
												@endforeach
											@endif
										</select>
										@if($errors->has('id_counterpartie_contract'))
											<label class='msgError'>{{$errors->first('id_counterpartie_contract')}}</label>
										@endif
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="row">
										<div class="col-md-12">
											<div class='form-group'>
												<label for='nameWork2'>Наименование работ</label>
												<textarea id='nameWork2' class='form-control {{$errors->has("name_work_contract") ? print("inputError ") : print("")}}' name='name_work_contract' type="text" style="width: 100%;" rows='4'></textarea>
												@if($errors->has('name_work_contract'))
													<label class='msgError'>{{$errors->first('name_work_contract')}}</label>
												@endif
											</div>
										</div>
									</div>
									<!--<div class="row">
										<div class="col-md-12">
											<label for='lastCompleteContract'>Состояние заключения договора</label>
										</div>								
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class='form-check'>
												<input id='completeContract2' class='form-check-input completeCheck' type="checkbox"/>
												<label class='form-check-label' for='completeContract2'>Заключен</label>
											</div>
										</div>								
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class='form-group'>
												<textarea id='lastCompleteContract' class='form-control lastCompleteInput' type="text" style="width: 100%;" rows='3' disabled></textarea>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class="btn btn-primary">История состояний</button>
										</div>
									</div>-->
								</div>
								<div class="col-md-9">
									<div class="row">
										<div class="col-md-4">
											<div class='form-group row'>
												<label for='concludedContract2' class='col-md-6 col-form-label'>Заключено</label>
												<div class='col-md-6'>
													<input id='concludedContract2' class='form-control {{$errors->has("concluded_count_contract") ? print("inputError ") : print("")}}' type='number' value='0' name='concluded_count_contract'/>
													@if($errors->has('concluded_count_contract'))
														<label class='msgError'>{{$errors->first('concluded_count_contract')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class='form-group row'>
												<label for='amountConcludedContract2' class='col-md-6 col-form-label'>Сумма с НДС, т.р.</label>
												<div class='col-md-6'>
													<input id='amountConcludedContract2' class='form-control {{$errors->has("amount_concluded_contract") ? print("inputError ") : print("")}}' type='text' name='amount_concluded_contract'/>
													@if($errors->has('amount_concluded_contract'))
														<label class='msgError'>{{$errors->first('amount_concluded_contract')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class='form-group row'>
												<div class='col-md-12'>
													<!--<button class='btn btn-primary' data-toggle="modal" data-target="#scan">Резолюция</button>-->
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">
											<div class='form-group row'>
												<label for='formalizationContract2' class='col-md-6 col-form-label'>На оформлении</label>
												<div class='col-md-6'>
													<input id='formalizationContract2' class='form-control {{$errors->has("formalization_count_contract") ? print("inputError ") : print("")}}' type='number' value='0' name='formalization_count_contract'/>
													@if($errors->has('formalization_count_contract'))
														<label class='msgError'>{{$errors->first('formalization_count_contract')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class='form-group row'>
												<label for='amountFormalizationContract2' class='col-md-6 col-form-label'>Сумма с НДС, т.р.</label>
												<div class='col-md-6'>
													<input id='amountFormalizationContract2' class='form-control {{$errors->has("amount_formalization_contract") ? print("inputError ") : print("")}}' type='text' name='amount_formalization_contract'/>
													@if($errors->has('amount_formalization_contract'))
														<label class='msgError'>{{$errors->first('amount_formalization_contract')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class="col-md-4">
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">
											<label for='implementationInput2'>Выполнение</label>
										</div>
										<div class="col-md-4">
											<div class='form-group row'>
												<label for='amountImplementationContract2' class='col-md-6 col-form-label'>Сумма с НДС, т.р.</label>
												<div class='col-md-6'>
													<input id='amountImplementationContract2' class='form-control {{$errors->has("amoun_implementation_contract") ? print("inputError ") : print("")}}' type='text' name='amoun_implementation_contract'/>
													@if($errors->has('amoun_implementation_contract'))
														<label class='msgError'>{{$errors->first('amoun_implementation_contract')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class='form-check'>
												<input id='implementationСontract2' class='form-check-input implementationCheck' type="checkbox" name='check_implementation_contract'/>
												<label class='form-check-label' for='implementationСontract2'>Выполнен</label>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<input id='implementationInput2' class='form-control implementationInput' type='text' name='comment_implementation_contract'/>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-12">
													<label>Выставление счетов с НДС, тыс. руб.</label>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class='form-group row'>
														<label for='prepaymentScoreContract2' class='col-md-6 col-form-label'>Аванс</label>
														<div class='col-md-6'>
															<input id='prepaymentScoreContract2' class='form-control {{$errors->has("prepayment_score_contract") ? print("inputError ") : print("")}}' type='text' name='prepayment_score_contract'/>
															@if($errors->has('prepayment_score_contract'))
																<label class='msgError'>{{$errors->first('prepayment_score_contract')}}</label>
															@endif
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class='form-group row'>
														<label for='invoiceScoreContract2' class='col-md-6 col-form-label'>Счет-фактура</label>
														<div class='col-md-6'>
															<input id='invoiceScoreContract2' class='form-control {{$errors->has("invoice_score_contract") ? print("inputError ") : print("")}}' type='text' name='invoice_score_contract'/>
															@if($errors->has('invoice_score_contract'))
																<label class='msgError'>{{$errors->first('invoice_score_contract')}}</label>
															@endif
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-12">
													<label>Оплата с НДС, тыс. руб.</label>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class='form-group row'>
														<label for='prepaymentPaymentContract2' class='col-md-6 col-form-label'>Аванс</label>
														<div class='col-md-6'>
															<input id='prepaymentPaymentContract2' class='form-control {{$errors->has("prepayment_payment_contract") ? print("inputError ") : print("")}}' type='text' name='prepayment_payment_contract'/>
															@if($errors->has('prepayment_payment_contract'))
																<label class='msgError'>{{$errors->first('prepayment_payment_contract')}}</label>
															@endif
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class='form-group row'>
														<label for='amountPaymentContract2' class='col-md-6 col-form-label'>Окончательный расчет</label>
														<div class='col-md-6'>
															<input id='amountPaymentContract2' class='form-control {{$errors->has("amount_payment_contract") ? print("inputError ") : print("")}}' type='text' name='amount_payment_contract'/>
															@if($errors->has('amount_payment_contract'))
																<label class='msgError'>{{$errors->first('amount_payment_contract')}}</label>
															@endif
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class='form-group row'>
														<label for='allPaymentContract2' class='col-md-6 col-form-label'>Всего</label>
														<div class='col-md-6'>
															<input id='allPaymentContract2' class='form-control' type='text' disabled />
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<!--<button class="btn btn-secondary" style="float: right;">Дополнительные соглашения</button>-->
								</div>
								<div class="col-md-3">
									<!--<button class="btn btn-secondary" style="float: left;">Протокол разногласий</button>-->
								</div>
								<div class="col-md-3">
									@if(Auth::User()->hasRole()->role != 'Администрация')
										<button type='submit' class="btn btn-primary" style="float: right;">Сохранить договор</button>
									@endif
								</div>
							</div>
						</form>
					</div>
				@endif
				<!-- Модальное окно история состояний -->
				<div class="modal fade" id="history_states" tabindex="-1" role="dialog" aria-labelledby="historyStatesModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form method='POST' action="{{ route('department.ekonomic.new_state',$contract->id)}}">
								{{csrf_field()}}
								<div class="modal-header">
									<h5 class="modal-title" id="historyStatesModalLabel">История состояний</h5>
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
														</tr>
													</thead>
													<tbody>
														@foreach($states as $state)
															<tr class='rowsContract cursorPointer updateState' id_state='{{$state->id}}' 
																												name_state='{{$state->name_state}}' 
																												date_state='{{$state->date_state}}' 
																												action_state='{{ route("department.ekonomic.update_state",$contract->id)}}'
																												destroy_state='{{ route("department.ekonomic.destroy_state",$state->id)}}'>
																<td>{{$state->name_state}}</td>
																<td>{{$state->date_state}}</td>
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
													<input id='date_state' class='datepicker form-control {{$errors->has("date_state") ? print("inputError ") : print("")}}' name='date_state' value='{{date("d.m.Y", time())}}'/>
													@if($errors->has('date_state'))
														<label class='msgError'>{{$errors->first('date_state')}}</label>
													@endif
												</div>
											</div>
										</div>
										<div class='col-md-12'>
											@if(Auth::User()->hasRole()->role != 'Администрация')
												<button id='btn_add_state' class='btn btn-secondary' type='button' clear_date='{{date("d.m.Y", time())}}' action_state='{{ route("department.ekonomic.new_state",$contract->id)}}'>Добавить состояние</button>
											@endif
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
											@if(Auth::User()->hasRole()->role != 'Администрация')
												<button id='add_new_resolution' type='button' class='btn btn-secondary'>Добавить скан</button>
											@endif
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
											<input type='file' name='new_file_resolution'/>
										</div>
										<div class='col-md-6'>
											<input id='date_resolution' name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
									<button id='btn_close_new_application' type="button" class="btn btn-secondary">Закрыть</button>
								</div>
							</form>
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
