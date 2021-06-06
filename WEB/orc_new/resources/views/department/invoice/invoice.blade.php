@extends('layouts.header')

@section('title')
	Финансовый отдел
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Финансовый отдел')
				@if (Route::has('login'))
					<div class="top-right links">
						
					</div>
				@endif
				
				@if($invoice)
					<div class="content">
						<form method='POST' action="{{route('department.invoice.update', $invoice->id)}}">
							{{csrf_field()}}
							<div class='row'>
								<div class="col-md-12">
									<label>Номер договора: {{$invoice->number_contract}}</label>
									<input name='id_contract' class='form-control' value='{{$invoice->id_contract}}' style='display: none;'/>
								</div>
							</div>
							<div class='row'>
								<div class="col-md-12">
									<label>Контрагент: {{$invoice->name_counterpartie_contract}}</label>
									<input name='id_counterpartie' class='form-control' value='{{$invoice->id_counterpartie_contract}}' style='display: none;'/>
								</div>
							</div>
							<div class='row'>
								<div class="col-md-1">
									<div class="form-group">
										<label for='amountP'>№ акта</label>
										<input name='number_deed_invoice' id='amountP' class='form-control {{$errors->has("number_deed_invoice") ? print("inputError ") : print("")}}' type='text' value='{{old("number_deed_invoice") ? old("number_deed_invoice") : $invoice->number_deed_invoice}}'/>
										@if($errors->has('number_deed_invoice'))
											<label class='msgError'>{{$errors->first('number_deed_invoice')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for='amountP'>№ сч</label>
										<input name='number_invoice' id='amountP' class='form-control {{$errors->has("number_invoice") ? print("inputError ") : print("")}}' type='text' value='{{old("number_invoice") ? old("number_invoice") : $invoice->number_invoice}}' required/>
										@if($errors->has('number_invoice'))
											<label class='msgError'>{{$errors->first('number_invoice')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for='idDatePicker1'>Дата</label>
										<input name='date_invoice' id='idDatePicker1' class='datepicker form-control {{$errors->has("date_invoice") ? print("inputError ") : print("")}}' value='{{old("date_invoice") ? old("date_invoice") : date("d.m.Y", strtotime($invoice->date_invoice))}}' required/>
										@if($errors->has('date_invoice'))
											<label class='msgError'>{{$errors->first('date_invoice')}}</label>
										@endif
									</div>
								</div>
								@if($invoice->name == 'Оплата')
									<div class="col-md-2">
										<div class="form-group">
											<label for='amountP'>Сумма П</label>
											<input name='amount_p_invoice' id='amountP' class='form-control check-number {{$errors->has("amount_p_invoice") ? print("inputError ") : print("")}}' type='text' value='{{old("amount_p_invoice") ? old("amount_p_invoice") : $invoice->amount_p_invoice}}' required/>
											@if($errors->has('amount_p_invoice'))
												<label class='msgError'>{{$errors->first('amount_p_invoice')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-1">
										<div class="form-group" style='margin-top: 30px;margin-left: 27px;'>
											<label class='form-check-label' for='is_prepayment_invoice'>Аванс</label>
											@if($invoice->is_prepayment_invoice == 1)
												<input id='is_prepayment_invoice' name='is_prepayment_invoice' class='form-check-input' type="checkbox" checked />
											@else
												<input id='is_prepayment_invoice' name='is_prepayment_invoice' class='form-check-input' type="checkbox" />
											@endif
										</div>
									</div>
								@else
									<div class="col-md-3">
										<div class="form-group">
											<label for='amountP'>Сумма П</label>
											<input name='amount_p_invoice' id='amountP' class='form-control check-number {{$errors->has("amount_p_invoice") ? print("inputError ") : print("")}}' type='text' value='{{old("amount_p_invoice") ? old("amount_p_invoice") : $invoice->amount_p_invoice}}' required/>
											@if($errors->has('amount_p_invoice'))
												<label class='msgError'>{{$errors->first('amount_p_invoice')}}</label>
											@endif
										</div>
									</div>
								@endif
								<div class="col-md-3">
									<div class="form-group">
										<label for="sel1">Вид счета</span></label>
										<select name='id_name_invoice' class='form-control {{$errors->has("id_name_invoice") ? print("inputError ") : print("")}}' id="sel1" required>
											<option></option>
											@if(old('id_name_invoice'))
												@foreach($name_invoices as $name_invoice)
													@if(old('id_name_invoice') == $name_invoice->id_name_invoice)
														<option value='{{$name_invoice->id}}' selected>{{$name_invoice->name}}</option>
													@else
														<option value='{{$name_invoice->id}}'>{{$name_invoice->name}}</option>
													@endif
												@endforeach
											@else
												@foreach($name_invoices as $name_invoice)
													@if($invoice->id_name_invoice == $name_invoice->id)
														<option value='{{$name_invoice->id}}' selected>{{$name_invoice->name}}</option>
													@else
														<option value='{{$name_invoice->id}}'>{{$name_invoice->name}}</option>
													@endif
												@endforeach
											@endif
											@if($errors->has('id_name_invoice'))
												<label class='msgError'>{{$errors->first('id_name_invoice')}}</label>
											@endif
										</select>
									</div>
								</div>
								<!--<div class="col-md-3">
									<div class="form-group">
										<label for="sel1">Вид деятельности</span></label>
										<select name='name_view_invoice' class='form-control {{$errors->has("name_view_invoice") ? print("inputError ") : print("")}}' id="sel1">
											<option>{{old('name_view_invoice') ? old('name_view_invoice') : $invoice->name_view_invoice}}</option>
											<option></option>
											@foreach($view_invoices as $view_invoice)
												<option>{{$view_invoice->name_view_invoice}}</option>
											@endforeach
											@if($errors->has('name_view_invoice'))
												<label class='msgError'>{{$errors->first('name_view_invoice')}}</label>
											@endif
										</select>
									</div>
								</div>-->
							</div>
							<!--<div class='row'>
								<div class="col-md-3">
									<div class="form-group">
										<label for='amountP'>Счет фактура</label>
										<input name='name_invoice' id='amountP' class='form-control {{$errors->has("name_invoice") ? print("inputError ") : print("")}}' type='text' value='{{old("name_invoice") ? old("name_invoice") : $invoice->name_invoice}}'/>
										@if($errors->has('name_invoice'))
											<label class='msgError'>{{$errors->first('name_invoice')}}</label>
										@endif										
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for='idDatePicker2'>от</label>
										<input name='name_date_invoice' id='idDatePicker2' class='datepicker form-control {{$errors->has("name_date_invoice") ? print("inputError ") : print("")}}' value='{{old("name_date_invoice") ? old("name_date_invoice") : $invoice->name_date_invoice}}'/>
										@if($errors->has('name_date_invoice'))
											<label class='msgError'>{{$errors->first('name_date_invoice')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for='amountInvoice'>Сумма с/ф</label>
										<input name='amount_invoice' id='amountInvoice' class='form-control {{$errors->has("amount_invoice") ? print("inputError ") : print("")}}' type='text' value='{{old("amount_invoice") ? old("amount_invoice") : $invoice->amount_invoice}}'/>
										@if($errors->has('amount_invoice'))
											<label class='msgError'>{{$errors->first('amount_invoice')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-3">
									
								</div>
							</div>
							<div class='row'>
								<div class="col-md-3">
									<div class="form-group">
										<label for='datePayment'>Дата оплаты по БАНКУ</label>
										<input name='date_payment_invoice' id='datePayment' class='form-control {{$errors->has("date_payment_invoice") ? print("inputError ") : print("")}}' type='text' value='{{old("date_payment_invoice") ? old("date_payment_invoice") : $invoice->date_payment_invoice}}'/>
										@if($errors->has('date_payment_invoice'))
											<label class='msgError'>{{$errors->first('date_payment_invoice')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for='amountPayment'>Сумма оплаты по БАНКУ</label>
										<input name='amount_payment_invoice' id='amountPayment' class='form-control {{$errors->has("amount_payment_invoice") ? print("inputError ") : print("")}}' type='text' value='{{old("amount_payment_invoice") ? old("amount_payment_invoice") : $invoice->amount_payment_invoice}}'/>
										@if($errors->has('amount_payment_invoice'))
											<label class='msgError'>{{$errors->first('amount_payment_invoice')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for='debt'>Долг перед ФКП "НТИИМ"</label>
										<input name='debt' id='debt' class='form-control {{$errors->has("debt") ? print("inputError ") : print("")}}' type='text' value='{{old("debt") ? old("debt") : $invoice->amount_invoice - $invoice->amount_payment_invoice}}' disabled />
										@if($errors->has('debt'))
											<label class='msgError'>{{$errors->first('debt')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-3">
									
								</div>
							</div>-->
							<div class='row'>
								<div class="col-md-12">
									<button type='submit' class='btn btn-primary' style='float: right;'>Редактировать</button>
								</div>
							</div>
						</form>
					</div>
				@else
					@if($contract)
						<div class="content">
							<form method='POST' action="{{route('department.invoice.store')}}">
								{{csrf_field()}}
								<div class='row'>
									<div class="col-md-12">
										<label>Номер договора: {{$contract->number_contract}}</label>
										<input name='id_contract' class='form-control' value='{{$contract->id}}' style='display: none;'/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-12">
										<label>Контрагент: {{$contract->name_counterpartie_contract}}</label>
										<input name='id_counterpartie' class='form-control' value='{{$contract->id_counterpartie_contract}}' style='display: none;'/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-1">
										<div class="form-group">
											<label for='amountP'>№ акта</label>
											<input name='number_deed_invoice' id='amountP' class='form-control {{$errors->has("number_deed_invoice") ? print("inputError ") : print("")}}' type='text' value='{{old("number_deed_invoice")}}'/>
											@if($errors->has('number_deed_invoice'))
												<label class='msgError'>{{$errors->first('number_deed_invoice')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for='number_invoice'>№ сч</label>
											<input name='number_invoice' id='number_invoice' class='form-control {{$errors->has("number_invoice") ? print("inputError ") : print("")}}' type='text' value="{{ old('number_invoice')}}" required/>
											@if($errors->has('number_invoice'))
												<label class='msgError'>{{$errors->first('number_invoice')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for='idDatePicker1'>Дата</label>
											<input name='date_invoice' id='idDatePicker1' class='datepicker form-control {{$errors->has("date_invoice") ? print("inputError ") : print("")}}' value="{{ old('date_invoice')}}" required/>
											@if($errors->has('date_invoice'))
												<label class='msgError'>{{$errors->first('date_invoice')}}</label>
											@endif
										</div>
									</div>
									@if($n_invoice == 'Оплата')
										<div class="col-md-2">
											<div class="form-group">
												<label for='amountP'>Сумма П</label>
												<input name='amount_p_invoice' id='amountP' class='form-control check-number {{$errors->has("amount_p_invoice") ? print("inputError ") : print("")}}' type='text' value="{{ old('amount_p_invoice')}}" required/>
												@if($errors->has('amount_p_invoice'))
													<label class='msgError'>{{$errors->first('amount_p_invoice')}}</label>
												@endif
											</div>
										</div>
										<div class="col-md-1">
											<div class="form-group" style='margin-top: 30px;margin-left: 27px;'>
												<label class='form-check-label' for='is_prepayment_invoice'>Аванс</label>
												<input id='is_prepayment_invoice' name='is_prepayment_invoice' class='form-check-input' type="checkbox" />
											</div>
										</div>
									@else
										<div class="col-md-3">
											<div class="form-group">
												<label for='amountP'>Сумма П</label>
												<input name='amount_p_invoice' id='amountP' class='form-control check-number {{$errors->has("amount_p_invoice") ? print("inputError ") : print("")}}' type='text' value="{{ old('amount_p_invoice')}}" required/>
												@if($errors->has('amount_p_invoice'))
													<label class='msgError'>{{$errors->first('amount_p_invoice')}}</label>
												@endif
											</div>
										</div>
									@endif
									<div class="col-md-3">
										<div class="form-group">
											<label for="sel1">Вид счета</span></label>
											<select name='id_name_invoice' class='form-control {{$errors->has("id_name_invoice") ? print("inputError ") : print("")}}' id="sel1" required>
												<option></option>
												@if(old('id_name_invoice'))
													@foreach($name_invoices as $name_invoice)
														@if(old('id_name_invoice') == $name_invoice->id)
															<option value='{{$name_invoice->id}}' selected>{{$name_invoice->name}}</option>
														@else
															<option value='{{$name_invoice->id}}'>{{$name_invoice->name}}</option>
														@endif
													@endforeach
												@else
													@foreach($name_invoices as $name_invoice)
														@if($n_invoice == $name_invoice->name)
															<option value='{{$name_invoice->id}}' selected>{{$name_invoice->name}}</option>
														@else
															<option value='{{$name_invoice->id}}'>{{$name_invoice->name}}</option>
														@endif
													@endforeach
												@endif
												@if($errors->has('id_name_invoice'))
													<label class='msgError'>{{$errors->first('id_name_invoice')}}</label>
												@endif
											</select>
										</div>
									</div>
									<!--<div class="col-md-3">
										<div class="form-group">
											<label for="sel1">Вид деятельности</span></label>
											<select name='name_view_invoice' class='form-control {{$errors->has("name_view_invoice") ? print("inputError ") : print("")}}' id="sel1">
												@if(old('name_view_invoice'))
													<option>{{old('name_view_invoice')}}</option>
												@endif
												<option></option>
												@foreach($view_invoices as $view_invoice)
													<option>{{$view_invoice->name_view_invoice}}</option>
												@endforeach
											</select>
											@if($errors->has('name_view_invoice'))
												<label class='msgError'>{{$errors->first('name_view_invoice')}}</label>
											@endif
										</div>
									</div>-->
								</div>
								<!--<div class='row'>
									<div class="col-md-3">
										<div class="form-group">
											<label for='amountP'>Счет фактура</label>
											<input name='name_invoice' id='amountP' class='form-control {{$errors->has("name_invoice") ? print("inputError ") : print("")}}' type='text' value="{{ old('name_invoice')}}"/>
											@if($errors->has('name_invoice'))
												<label class='msgError'>{{$errors->first('name_invoice')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for='idDatePicker2'>от</label>
											<input name='name_date_invoice' id='idDatePicker2' class='datepicker form-control {{$errors->has("name_date_invoice") ? print("inputError ") : print("")}}' value="{{ old('name_date_invoice')}}"/>
											@if($errors->has('name_date_invoice'))
												<label class='msgError'>{{$errors->first('name_date_invoice')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for='amountInvoice'>Сумма с/ф</label>
											<input name='amount_invoice' id='amountInvoice' class='form-control {{$errors->has("amount_invoice") ? print("inputError ") : print("")}}' type='text' value="{{ old('amount_invoice')}}"/>
											@if($errors->has('amount_invoice'))
												<label class='msgError'>{{$errors->first('amount_invoice')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-3">
										
									</div>
								</div>
								<div class='row'>
									<div class="col-md-3">
										<div class="form-group">
											<label for='datePayment'>Дата оплаты по БАНКУ</label>
											<input name='date_payment_invoice' id='datePayment' class='form-control {{$errors->has("date_payment_invoice") ? print("inputError ") : print("")}}' type='text' value="{{ old('date_payment_invoice')}}"/>
											@if($errors->has('date_payment_invoice'))
												<label class='msgError'>{{$errors->first('date_payment_invoice')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for='amountPayment'>Сумма оплаты по БАНКУ</label>
											<input name='amount_payment_invoice' id='amountPayment' class='form-control {{$errors->has("amount_payment_invoice") ? print("inputError ") : print("")}}' type='text' value="{{ old('amount_payment_invoice')}}"/>
											@if($errors->has('amount_payment_invoice'))
												<label class='msgError'>{{$errors->first('amount_payment_invoice')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for='debt'>Долг перед ФКП "НТИИМ"</label>
											<input name='debt' id='debt' class='form-control {{$errors->has("debt") ? print("inputError ") : print("")}}' type='text' value="{{ old('debt')}}" disabled />
											@if($errors->has('debt'))
												<label class='msgError'>{{$errors->first('debt')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-3">
										
									</div>
								</div>-->
								<div class='row'>
									<div class="col-md-12">
										<button type='submit' class='btn btn-primary' style='float: right;'>Сохранить</button>
									</div>
								</div>
							</form>
						</div>
					@endif
				@endif
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
