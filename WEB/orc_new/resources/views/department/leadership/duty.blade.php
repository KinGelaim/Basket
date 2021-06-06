@extends('layouts.header')

@section('title')
	Печать
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Администрация' OR Auth::User()->hasRole()->role == 'Финансовый отдел')
				<div class="content">
					@if($contracts)
						<div class='row'>
							<div class="col-md-12">
								<button class='btn btn-primary' id='createExcel' real_name_table='Задолженность по авансам и выполненным работам перед ФКП "НТИИМ"'>Сформировать Excel</button>
							</div>
						</div>
						<!--<div class='row' style='text-align: center;'>
							<div class="col-md-12">
								Задолженность по авансам и выполненным работам перед ФКП "НТИИМ"
							</div>
						</div>-->
						<div class='row' style='text-align: right;'>
							<div class="col-md-12">
								по состоянию на {{date('d.m.Y', time())}} г.
							</div>
						</div>
						<table id='resultTable' class="table table-bordered tablePrint table-fixed" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
							<thead style='text-align: center;'>
								<tr>
									<th colspan='13'>Задолженность по авансам и выполненным работам перед ФКП "НТИИМ"</th>
								</tr>
								<tr>
									<th>Предприятие, № договора</th>
									<th>Сумма договора</th>
									<th>Сумма аванса</th>
									<th>% аванса</th>
									<th>№ сч.</th>
									<th>Дата</th>
									<th>№ акта</th>
									<th style='min-width: 162px;'>сч-фактура,дата</th>
									<th>Сумма сч-фактуры</th>
									<th>Дата оплаты</th>
									<th>Сумма оплаты</th>
									<th>Задолженность по авансам</th>
									<th>Задолженность по вып.работы</th>
								</tr>
							</thead>
							<tbody>
								<?php $full_duty_prepayments = 0; $full_duty_payments = 0; ?>
								@foreach($contracts as $key=>$value)
									<tr>
										<td colspan='13'><b>{{$key}}</b></td>
									</tr>
									<?php $counterpartie_duty_prepayments = 0; $counterpartie_duty_payments = 0; ?>
									@foreach($value as $key2=>$value2)
										<tr>
											<td colspan='13'>{{$key2}}</td>
										</tr>
										@foreach($value2 as $contract)
											<tr>
												<td>{{$contract->number_counterpartie_contract_reestr}}/{{$contract->number_contract}} от {{$contract->date_contract_on_first_reestr}}</td>
												<td>{{is_numeric(str_replace(',','.',$contract->amount_reestr)) ? number_format(str_replace(',','.',$contract->amount_reestr), 2, ',', '&nbsp;') : ''}}</td>
												<td>{{number_format($contract->full_prepayments, 2, ',', '&nbsp;')}}</td>
												<td><?php if(is_numeric($contract->amount_reestr) && is_numeric($contract->full_prepayments)) if($contract->amount_reestr > 0) echo round($contract->full_prepayments*100/$contract->amount_reestr, 2) . '%'; ?></td>
												<td>@foreach($contract->prepayments as $prepayment) {{$prepayment->number_invoice}}<br/> @endforeach</td>
												<td>@foreach($contract->prepayments as $prepayment) {{ $prepayment->date_invoice ? date('d.m.Y', strtotime($prepayment->date_invoice)) : '' }}<br/> @endforeach</td>
												<td>@foreach($contract->invoices as $invoice) {{$invoice->number_deed_invoice}}<br/> @endforeach</td>
												<td>@foreach($contract->invoices as $invoice) {{$invoice->number_invoice}} от {{ $invoice->date_invoice ? date('d.m.Y', strtotime($invoice->date_invoice)) : '' }}<br/> @endforeach</td>
												<td>@foreach($contract->invoices as $invoice) {{number_format($invoice->amount_p_invoice, 2, ',', '&nbsp;')}}<br/> @endforeach</td>
												<td>@foreach($contract->payments as $payment) {{$payment->number_invoice}} от {{ $payment->date_invoice ? date('d.m.Y', strtotime($payment->date_invoice)) : '' }}<br/> @endforeach</td>
												<td>@foreach($contract->payments as $payment) {{number_format($payment->amount_p_invoice, 2, ',', '&nbsp;')}}<br/> @endforeach</td>
												<td>{{number_format($contract->duty_prepayments, 2, ',', '&nbsp;')}}</td>
												<td>{{number_format($contract->duty_payments, 2, ',', '&nbsp;')}}</td>
											</tr>
											<?php
												$full_duty_prepayments += $contract->duty_prepayments;
												$full_duty_payments += $contract->duty_payments;
												$counterpartie_duty_prepayments += $contract->duty_prepayments;
												$counterpartie_duty_payments += $contract->duty_payments;
											?>
										@endforeach
									@endforeach
									@if(count($contracts) > 1)
										<tr>
											<td colspan='9'></td>
											<td><b>Итого</b></td>
											<td></td>
											<td><b>{{number_format($counterpartie_duty_prepayments, 2, ',', '&nbsp;')}}</b></td>
											<td><b>{{number_format($counterpartie_duty_payments, 2, ',', '&nbsp;')}}</b></td>
										</tr>
									@endif
									<?php $counterpartie_duty_prepayments = 0; $counterpartie_duty_payments = 0; ?>
								@endforeach
								<tr>
									<td colspan='9'></td>
									<td colspan='2'><b>Итого по предприятиям</b></td>
									<td><b>{{number_format($full_duty_prepayments, 2, ',', '&nbsp;')}}</b></td>
									<td><b>{{number_format($full_duty_payments, 2, ',', '&nbsp;')}}</b></td>
								</tr>
							</tbody>
						</table>
					@else
						<div class="alert alert-danger">
							Контрактов не обнаружено!
						</div>
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
