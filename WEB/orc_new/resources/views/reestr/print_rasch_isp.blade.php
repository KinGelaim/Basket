@extends('layouts.header')

@section('title')
	Печать Список договор, заключенных в рамках 223-ФЗ, 44-ФЗ для контроля их исполнения
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-2">
						<button class='btn btn-primary' id='createExcel' real_name_table='Список договор, заключенных в рамках 223-ФЗ, 44-ФЗ для контроля их исполнения'>Сформировать Excel</button>
					</div>
				</div>
				<div id='copyTarget'>
					<div class='row' style='text-align: center;'>
						<div class="col-md-12">
							Список договор, заключенных в рамках 223-ФЗ, 44-ФЗ для контроля их исполнения
						</div>
					</div>
					<div class='row' style='text-align: center;'>
						<div class="col-md-12">
							за период: {{$period1}} г. - {{$period2}} г.
						</div>
					</div>
					<div class='row' style='text-align: right;'>
						<div class="col-md-12">
							по состоянию на {{date('d.m.Y', time())}} г.
						</div>
					</div>
					<table id='resultTable' class="table table-bordered" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
						<thead style='text-align: center;'>
							<tr>
								<th style='text-align: center; vertical-align: middle;' colspan=2>Договор</th>
								<th style='text-align: center; vertical-align: middle;' rowspan=2>Контрагент</th>
								<th style='text-align: center; vertical-align: middle;' rowspan=2>Предмет договора</th>
								<th style='text-align: center; vertical-align: middle;' rowspan=2>Цена без НДС, руб.</th>
								<th style='text-align: center; vertical-align: middle;' rowspan=2>Срок поставки товара, выполнения работ, оказания услуг</th>
								<th style='text-align: center; vertical-align: middle;' rowspan=2>Срок и порядок оплаты</th>
								<th style='text-align: center; vertical-align: middle;' rowspan=2>Срок действия договора</th>
							</tr>
							<tr>
								<th style='text-align: center; vertical-align: middle;'>№</th>
								<th style='text-align: center; vertical-align: middle;'>Дата</th>
							</tr>
						</thead>
						<tbody>
							@if(isset($contracts))
								@foreach($contracts as $contract)
									<tr>
										<td>{{$contract->number_contract}}</td>
										<td>{{$contract->date_registration_project_reestr}}</td>
										<td>{{$contract->counterpartie_name}}</td>
										<td>{{$contract->item_contract}}</td>
										<td>{{is_numeric($contract->amount_contract_reestr) ? number_format($contract->amount_contract_reestr, 2, '.', '&nbsp;') : ($contract->amount_contract_reestr ? $contract->amount_contract_reestr : (is_numeric($contract->amount_reestr) ? number_format($contract->amount_reestr, 2, '.', '&nbsp;') : $contract->amount_reestr))}}</td>
										<td>{{$contract->date_maturity_reestr}}</td>
										<td>{{$contract->prepayment_order_reestr}}<br/>{{$contract->score_order_reestr}}<br/>{{$contract->payment_order_reestr}}</td>
										<td>{{$contract->date_e_contract_reestr}}</td>
									</tr>
								@endforeach
							@endif
						</tbody>
					</table>
					<div class='row' style='height: 20px;'>
					</div>
				</div>
			</div>
		@else
			<div class="alert alert-danger">
				Для просмотра данной страницы необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection
