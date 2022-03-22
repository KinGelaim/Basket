@extends('layouts.header')

@section('title')
	Печать {{$text}}
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-12">
						<button class='btn btn-primary' id='createExcel' real_name_table='{{$text}}'>Сформировать Excel</button>
					</div>
				</div>
				<div class='row' style='text-align: center;'>
					<div class="col-md-12">
						<b>{{$text}} {{$period1}} г. - {{$period2}} г.</b>
					</div>
				</div>
				<div class='row' style='text-align: right;'>
					<div class="col-md-12">
						<b>По состоянию на {{date('d.m.Y', time())}} г.</b>
					</div>
				</div>
				<table id='resultTable' class="table table-bordered tablePrint" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
					<thead style='text-align: center;'>
						<tr>
							<th>Номер Дог./Контр. по Реестру, номер Контрагента, Отв. Исполнитель</th>
							<th>Контрагент (сокращенное наименование)</th>
							<th>Предмет Дог./Контр.</th>
							<th>Срок исполнения</th>
							<th>Сумма Дог./Контр.</th>
							<th>Порядок расчетов</th>
							<th>Срок действия</th>
							<th>ПР, ПСР, ПУР и др., ДС</th>
							<th>Дата вступления в силу Дог./Контр.</th>
						</tr>
					</thead>
					<tbody>
						<?php $result_amount = 0; $count=0; ?>
						@if(isset($contracts))
							@foreach($contracts as $contract)
								<tr>
									<td>{{$contract->number_contract}}<br/>{{$contract->number_counterpartie_contract_reestr}} {{$contract->date_contract_on_first_reestr ? 'от ' . $contract->date_contract_on_first_reestr : ''}}<br/>{{$contract->executor_contract_reestr}}</td>
									<td>{{$contract->counterpartie_name}}</td>
									<td>{{$contract->item_contract}}</td>
									<td>{{$contract->date_maturity_reestr}}</td>
									<td>{{is_numeric($contract->amount_contract_reestr) ? number_format($contract->amount_contract_reestr, 2, '.', '&nbsp;') : ($contract->amount_contract_reestr ? $contract->amount_contract_reestr : (is_numeric($contract->amount_reestr) ? number_format($contract->amount_reestr, 2, '.', '&nbsp;') : $contract->amount_reestr))}}</td>
									<td>{{$contract->prepayment_order_reestr}} {{$contract->score_order_reestr}} {{$contract->payment_order_reestr}}</td>
									<td>{{$contract->date_contract_reestr}}</td>
									<td>
										@foreach($contract->protocols as $protocol)
											{{$protocol->name_protocol}} от {{$protocol->date_on_first_protocol}}<br/>
										@endforeach
										<br/>
										@foreach($contract->add_agreements as $add_agreement)
											{{$add_agreement->name_protocol}} от {{$add_agreement->date_on_first_protocol}}<br/>
										@endforeach
									</td>
									<td>{{$contract->date_entry_into_force_reestr}}</td>
								</tr>
								<?php
									if($contract->amount_contract_reestr != null) $result_amount += str_replace(' ','',str_replace(',','.',$contract->amount_contract_reestr));
									else if($contract->amount_reestr != null) $result_amount += str_replace(' ','',str_replace(',','.',$contract->amount_reestr));
									$count++;
								?>
							@endforeach
						@elseif(isset($result))
							@foreach($result as $key=>$value)
								<tr>
									<td colspan='8' style='text-align: center;'><?php if($text == 'Отчет о Договорах/Контрактов по подразделению по Исполнителю') echo $value[0]->executor_contract_reestr; else echo $key; ?></td>
									@foreach($value as $contract)
										<tr>
											<td>{{$contract->number_contract}}<br/>{{$contract->number_counterpartie_contract_reestr}}<br/>{{$contract->executor_contract_reestr}}</td>
											<td>{{$contract->counterpartie_name}}</td>
											<td>{{$contract->item_contract}}</td>
											<td>{{$contract->date_maturity_reestr}}</td>
											<td>{{is_numeric($contract->amount_contract_reestr) ? number_format($contract->amount_contract_reestr, 2, '.', '&nbsp;') : ($contract->amount_contract_reestr ? $contract->amount_contract_reestr : (is_numeric($contract->amount_reestr) ? number_format($contract->amount_reestr, 2, '.', '&nbsp;') : $contract->amount_reestr))}}</td>
											<td>{{$contract->prepayment_order_reestr}} {{$contract->score_order_reestr}} {{$contract->payment_order_reestr}}</td>
											<td>{{$contract->date_contract_reestr}}</td>
											<td>
												@foreach($contract->protocols as $protocol)
													{{$protocol->name_protocol}} от {{$protocol->date_on_first_protocol}}<br/>
												@endforeach
												<br/>
												@foreach($contract->add_agreements as $add_agreement)
													{{$add_agreement->name_protocol}} от {{$add_agreement->date_on_first_protocol}}<br/>
												@endforeach
											</td>
											<td>{{$contract->date_entry_into_force_reestr}}</td>
										</tr>
										<?php
											if($contract->amount_contract_reestr != null) $result_amount += str_replace(' ','',str_replace(',','.',$contract->amount_contract_reestr));
											else if($contract->amount_reestr != null) $result_amount += str_replace(' ','',str_replace(',','.',$contract->amount_reestr));
											$count++;
										?>
									@endforeach
								</tr>
							@endforeach
						@endif
						<tr>
							<td></td>
							<td></td>
							<td style='text-align: right;'><b>Итого</b></td>
							<td style='text-align: center;'><b>{{$count}}</b></td>
							<td style='text-align: center;'><b>{{is_numeric($result_amount) ? number_format($result_amount, 2, '.', '&nbsp;') : str_replace('.',',',$result_amount)}}</b></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</tbody>
				</table>
				<div class='row'>
					<div class="col-md-8 col-md-offset-2">
						<b>Начальник отдела управления договорами<span style='float: right;'>{{$lider}}</span></b>
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
