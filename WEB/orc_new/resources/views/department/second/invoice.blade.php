@extends('layouts.header')

@section('title')
	Планово-экономический отдел
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
						<div class='row'>
							<div class="col-md-12">
								Номер договора: {{$invoice->number_contract}}
							</div>
						</div>
						<div class='row'>
							<div class="col-md-12">
								Контрагент: {{$invoice->name_counterpartie_contract}}
							</div>
						</div>
						<div class='row'>
							<div class="col-md-3">
								<div class="form-group">
									<label for='amountP'>№ сч</label>
									<input id='amountP' class='form-control' type='number' value='{{$invoice->number_invoice}}'/>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for='idDatePicker1'>Дата</label>
									<input id='idDatePicker1' class='datepicker form-control' value='{{$invoice->date_invoice}}'/>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for='amountP'>Сумма П</label>
									<input id='amountP' class='form-control' type='text' value='{{$invoice->amount_p_invoice}}'/>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="sel1">Вид деятельности</span></label>
									<select class="form-control" id="sel1">
										<option>{{$invoice->name_view_invoice}}</option>
										<option></option>
										@foreach($view_invoices as $view_invoice)
											<option>{{$view_invoice->name_view_invoice}}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class='row'>
							<div class="col-md-3">
								<div class="form-group">
									<label for='amountP'>Счет фактура</label>
									<input id='amountP' class='form-control' type='text' value='{{$invoice->name_invoice}}'/>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for='idDatePicker2'>от</label>
									<input id='idDatePicker2' class='datepicker form-control' value='{{$invoice->name_date_invoice}}'/>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for='amountInvoice'>Сумма с/ф</label>
									<input id='amountInvoice' class='form-control' type='text' value='{{$invoice->amount_invoice}}'/>
								</div>
							</div>
							<div class="col-md-3">
								
							</div>
						</div>
						<div class='row'>
							<div class="col-md-3">
								<div class="form-group">
									<label for='datePayment'>Дата оплаты по БАНКУ</label>
									<input id='datePayment' class='form-control' type='text' value='{{$invoice->date_payment_invoice}}'/>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for='amountPayment'>Сумма оплаты по БАНКУ</label>
									<input id='amountPayment' class='form-control' type='text' value='{{$invoice->amount_payment_invoice}}'/>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for='debt'>Долг перед ФКП "НТИИМ"</label>
									<input id='debt' class='form-control' type='text' value='{{$invoice->amount_invoice - $invoice->amount_payment_invoice}}'/>
								</div>
							</div>
							<div class="col-md-3">
								
							</div>
						</div>
						<div class='row'>
							<div class="col-md-12">
								<button class='btn btn-primary' style='float: right;'>Редактировать</button>
							</div>
						</div>
					</div>
				@else
					@if($contract)
						<div class="content">
							<div class='row'>
								<div class="col-md-12">
									Номер договора: {{$contract->number_contract}}
								</div>
							</div>
							<div class='row'>
								<div class="col-md-12">
									Контрагент: {{$contract->name_counterpartie_contract}}
								</div>
							</div>
							<div class='row'>
								<div class="col-md-3">
									<div class="form-group">
										<label for='amountP'>№ сч</label>
										<input id='amountP' class='form-control' type='number' value=''/>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for='idDatePicker1'>Дата</label>
										<input id='idDatePicker1' class='datepicker form-control' value=''/>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for='amountP'>Сумма П</label>
										<input id='amountP' class='form-control' type='text' value=''/>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="sel1">Вид деятельности</span></label>
										<select class="form-control" id="sel1">
											<option></option>
											@foreach($view_invoices as $view_invoice)
												<option>{{$view_invoice->name_view_invoice}}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class='row'>
								<div class="col-md-3">
									<div class="form-group">
										<label for='amountP'>Счет фактура</label>
										<input id='amountP' class='form-control' type='text' value=''/>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for='idDatePicker2'>от</label>
										<input id='idDatePicker2' class='datepicker form-control' value=''/>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for='amountInvoice'>Сумма с/ф</label>
										<input id='amountInvoice' class='form-control' type='text' value=''/>
									</div>
								</div>
								<div class="col-md-3">
									
								</div>
							</div>
							<div class='row'>
								<div class="col-md-3">
									<div class="form-group">
										<label for='datePayment'>Дата оплаты по БАНКУ</label>
										<input id='datePayment' class='form-control' type='text' value=''/>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for='amountPayment'>Сумма оплаты по БАНКУ</label>
										<input id='amountPayment' class='form-control' type='text' value=''/>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for='debt'>Долг перед ФКП "НТИИМ"</label>
										<input id='debt' class='form-control' type='text' value=''/>
									</div>
								</div>
								<div class="col-md-3">
									
								</div>
							</div>
							<div class='row'>
								<div class="col-md-12">
									<button class='btn btn-primary' style='float: right;'>Сохранить</button>
								</div>
							</div>
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
