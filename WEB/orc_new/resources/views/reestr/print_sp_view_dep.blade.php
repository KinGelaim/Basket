@extends('layouts.header')

@section('title')
	Печать Справка по виду Договоров (Контрактов) по подразделению
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-12">
						<button class='btn btn-primary' id='createExcel' real_name_table='Справка по виду Договоров (Контрактов) по подразделению'>Сформировать Excel</button>
					</div>
				</div>
				<div class='row' style='text-align: center;'>
					<div class="col-md-12">
						Справка по виду Договоров (Контрактов) по подразделению
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
							<th>Номер Дог./Контр. по Реестру,<br/>Исполнитель</th>
							<th>Контрагент (сокращенное наименование)</th>
							<th>Вид Дог./Контр.</th>
							<th>Предмет Дог./Контр</th>
							<th>Срок исполнения</th>
							<th>Сумма Дог./Контр.</th>
							<th>Срок действия</th>
							<th>ПР, ПСР, ПУР и др., ДС</th>
							<th>Дата вступления в силу</th>
						</tr>
					</thead>
					<tbody>
						@foreach($contracts as $contract)
							<tr>
								<td>{{$contract->number_contract}}<br/>{{$contract->executor_contract_reestr}}</td>
								<td>{{$contract->name_counterpartie_contract}}</td>
								<td>{{$contract->name_view_contract}}</td>
								<td>{{$contract->item_contract}}</td>
								<td>{{$contract->date_maturity_reestr ? $contract->date_maturity_reestr : $contract->date_maturity_date_reestr}}</td>
								<td>{{is_numeric($contract->amount_contract_reestr) ? number_format($contract->amount_contract_reestr, 2, '.', '&nbsp;') : ($contract->amount_contract_reestr ? $contract->amount_contract_reestr : (is_numeric($contract->amount_reestr) ? number_format($contract->amount_reestr, 2, '.', '&nbsp;') : $contract->amount_reestr))}}</td>
								<td>{{$contract->date_contract_reestr}}</td>
								<td>
									@foreach($contract->protocols as $protocol)
										{{$protocol->name_protocol}} от {{$protocol->date_on_first_protocol}}<br/>
									@endforeach
									@foreach($contract->add_agreements as $add_agreement)
										{{$add_agreement->name_protocol}} от {{$add_agreement->date_on_first_protocol}}<br/>
									@endforeach
								</td>
								<td>{{$contract->date_entry_into_force_reestr}}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		@else
			<div class="alert alert-danger">
				Для просмотра данной страницы необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection
