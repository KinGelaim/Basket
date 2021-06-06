@extends('layouts.header')

@section('title')
	Печать
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Финансовый отдел' OR Auth::User()->hasRole()->role == 'Администрация')
				<div class="content">
					@if($invoices)
						<div class='row'>
							<div class="col-md-12">
								<button class='btn btn-primary' id='createExcel' real_name_table='Счета и счета-фактуры'>Сформировать Excel</button>
							</div>
						</div>
						<div class='row' style='text-align: center;'>
							<div class="col-md-12">
								Счета и счета-фактуры
							</div>
						</div>
						<div class='row' style='text-align: right;'>
							<div class="col-md-12">
								по состоянию на {{date('d.m.Y', time())}} г.
							</div>
						</div>
						<table id='resultTable' class="table table-bordered tablePrint" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
							<thead style='text-align: center;'>
								<tr>
									<th colspan='11'>Счета и счета-фактуры на {{date('d.m.Y', time())}}г.</th>
								</tr>
								<tr>
									<th>№ сч</th>
									<th>Дата</th>
									<th>Сумма П</th>
									<th>Название предприятия</th>
									<th>с/фактура дата</th>
									<th>сумма с/ф</th>
									<th>дата оплаты по БАНКУ</th>
									<th>сумма оплаты по БАНКУ</th>
									<th>ДОЛГ перед ФКП "НТИИМ"</th>
									<th colspan='2'>Договор</th>
								</tr>
							</thead>
							<tbody>
								@foreach($invoices as $key=>$value)
									<tr>
										<td colspan='11' style='text-align: center;'>{{$key}}</td>
									</tr>
									@foreach($value as $key2=>$value2)
										<tr>
											<td colspan='11'>{{$key2}}</td>
										</tr>
										@foreach($value2 as $invoice)
											<tr>
												<td><b>{{$invoice->number_invoice}}</b></td>
												<td>{{$invoice->date_invoice}}</td>
												<td>{{$invoice->amount_p_invoice}}</td>
												<td>{{$invoice->name_view_contract}}</td>
												<td>{{$invoice->name == 'Счет-фактура' ? $invoice->date_invoice : '' }}</td>
												<td>{{$invoice->name == 'Счет-фактура' ? $invoice->amount_p_invoice : '' }}</td>
												<td>{{$invoice->name == 'Оплата' ? $invoice->date_invoice : '' }}</td>
												<td>{{$invoice->name == 'Оплата' ? $invoice->amount_p_invoice : '' }}</td>
												<td></td>
												<td></td>
												<td>{{$invoice->number_contract}}</td>
											</tr>
										@endforeach
									@endforeach
								@endforeach
							</tbody>
						</table>
					@endif
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
