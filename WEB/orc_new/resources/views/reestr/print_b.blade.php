@extends('layouts.header')

@section('title')
	Печать Просроченных проектов по подразделению
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-12">
						<button class='btn btn-primary' id='createExcel' real_name_table='Просроченные проекты Договоров/Контрактов'>Сформировать Excel</button>
					</div>
				</div>
				<div id='resultTable'>
					@foreach($result as $department)
						<table class="table tablePrint">
							<tbody>
								<tr>
									<td colspan='2' style='text-align: center; border: none;'>Служебная<br/>от {{date('d.m.Y',time())}} г.</td>
									<td style='border: none;'></td>
									<td style='border: none;'></td>
									<td style='border: none;'></td>
									<td colspan='2' style='text-align: center; border: none;'>
										@if(isset($department[0]->lider_department))
											{{$department[0]->lider_department['position_department']}}<br/>{{$department[0]->lider_department['surname'] . ' ' . mb_substr($department[0]->lider_department['name'], 0, 1) . '.' . mb_substr($department[0]->lider_department['patronymic'], 0, 1) . '.'}}
										@endif
									</td>
								</tr>
							</tbody>
						</table>
						<div class='row' style='text-align: center;'>
							<div class="col-md-12">
								<p>
									В соответствии с СТП 75 19308-049-2018 "Анализ Договора(Контракта) на поставку продукции выполнение работ и оказания услуг":
									Прошу сдать оригиналы Договоров/Контрактов в ОУД в срок до {{date('d.m.Y',time()+950400)}} г. Если договора не будут сданы в срок, то необходимо указать причину
									задержки и заполнить графу 7. Информация о Договорах/Контрактах необходима для подготовки отчета об итогах договорной работы за {{date('Y',time())}} г.
								</p>
							</div>
						</div>
						<table class="table table-bordered tablePrint" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
							<thead style='text-align: center;'>
								<tr style='border: solid 1px black;'>
									<th>№ п/п</th>
									<th>Номер Договора (контрагента/наш)<br/>_______________<br/>Исполнитель</th>
									<th>Контрагент</th>
									<th>Предмет</th>
									<th>Сумма</th>
									<th>Исполнитель</th>
									<th>Договорная документация (прот. разногл., прот. согл. разногл., доп. соглашения и др.)</th>
								</tr>
							</thead>
							<tbody>
								<?php $k=1; $result_amount=0; ?>
								@foreach($department as $contract)
									<tr style='border: solid 1px black;'>
										<td>{{$k++}}</td>
										<td>{{$contract->number_counterpartie_contract_reestr ? $contract->number_counterpartie_contract_reestr . '/' : ''}}{{$contract->number_contract}} {{$contract->date_contract_on_first_reestr ? 'от ' . $contract->date_contract_on_first_reestr : ''}}<br/>_______________<br/>{{$contract->executor}}</td>
										<td>{{$contract->counterpartie_name}}</td>
										<td>{{$contract->item_contract}}</td>
										<td>
											{{is_numeric($contract->amount_contract_reestr) ? number_format($contract->amount_contract_reestr, 2, ',', '&nbsp;') : ($contract->amount_contract_reestr ? $contract->amount_contract_reestr : (is_numeric($contract->amount_reestr) ? number_format($contract->amount_reestr, 2, ',', '&nbsp;') : $contract->amount_reestr))}}
										</td>
										<td>{{$contract->executor_contract_reestr}}</td>
										<td>
											@foreach($contract->protocols as $protocol)
												{{$protocol->name_protocol}} от {{$protocol->date_on_first_protocol}}<br/>
											@endforeach
											<br/>
											@foreach($contract->add_agreements as $add_agreement)
												{{$add_agreement->name_protocol}} от {{$add_agreement->date_on_first_protocol}}<br/>
											@endforeach
										</td>
									</tr>
									<?php if($contract->amount_contract_reestr != null) $result_amount += str_replace(' ','',str_replace(',','.',$contract->amount_contract_reestr)); ?>
								@endforeach
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td style='text-align: right;'><b>Итого</b></td>
									<td><b>{{is_numeric($result_amount) ? number_format($result_amount, 2, ',', '&nbsp;') : $result_amount}}</b></td>
									<td></td>
									<td></td>
								</tr>
							</tbody>
						</table>
						<table class="table tablePrint">
							<tbody>
								<tr>
									<td colspan='2' style='text-align: center; border: none;'>Начальник отдела управления договорами</td>
									<td style='border: none;'></td>
									<td style='border: none;'></td>
									<td style='border: none;'></td>
									<td colspan='2' style='text-align: center; border: none;'>{{$lider}}</td>
								</tr>
								<tr></tr>
							</tbody>
						</table>
					@endforeach
				</div>
			</div>
		@else
			<div class="alert alert-danger">
				Для просмотра данной страницы необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection
