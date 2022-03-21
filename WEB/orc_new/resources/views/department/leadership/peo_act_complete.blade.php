@extends('layouts.header')

@section('title')
	Печать
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="content">
				@if($contracts)
					@if(count($contracts) > 0)
						<div class='row'>
							<div class="col-md-12">
								<button class='btn btn-primary' id='createExcelNoneTable' real_name_table='Акт сдачи-приёмки выполненных работ'>Сформировать Excel</button>
							</div>
						</div>
						<table id='resultTable' style='margin: 0 auto; margin-top:20px; margin-bottom: 10px; width: 590px;'>
							<tbody>
								@foreach($contracts as $contract)
									@foreach($contract->results as $results)
										@foreach($results as $result)
											<tr>
												<td style='width: 240px;'>
													Федеральное казенное предприятие<br/>
													"Национальное испытательное объединение<br/>
													"Государственные боеприпасные испытательные полигоны России"<br/>
													филиал "Нижнетагильский институт испытания металлов"<br/>
													ул. Гагарина, 29<br/>
													г. Нижний Тагил Свердловской обл., 622015<br/>
													ИНН 5023002050
												</td>
												<td style='width: 110px;'></td>
												<td style='width: 240px; text-align: center; vertical-align: top;'>
													{{$contract->full_name_counterpartie_contract}}<br/>
													_____________________________
												</td>
											</tr>
											<tr>
												<td colspan='3' style='text-align: center;'>
													<b>АКТ № {{$result->number_act}}<br/>
													сдачи-приемки выполненных работ</b><br/>
													от {{$result->date_act}} года
												</td>
											</tr>
											<tr>
											</tr>
											<tr>
												<td colspan='3' style='text-indent: 25px;'>
													    Мы, нижеподписавшиеся, представитель Исполнителя Директор филиала Смирнов<br/>
													Николай Павлович с одной стороны, и представитель Заказчика __________________________<br/>
													__________________________________________________________________ с другой стороны,<br/>
													составили настоящий акт о том, что выполненные работы удовлетворяют условиям контракта и<br/>
													в надлежащем порядке оформлены
												</td>
											</tr>
											<tr>
												<td colspan='3' style='text-indent: 25px;'>
													Краткое описание выполненных работ: проведение испытаний по контракту №<br/>
													{{$contract->number_counterpartie_contract_reestr}}/{{$contract->number_contract}} от {{$contract->date_contract_on_first_reestr}}. <u> по наряду № {{$results->number_duty}} (исх. № {{$results->number_telegram}} от {{$results->date_telegram}} года.)
												</td>
											</tr>
											@if($contract->fixed_amount_reestr == 1)
												<tr>
													<td colspan='3' style='text-indent: 25px;'>
														Договорная (фиксированная) цена составляет {{$contract->amount_reestr}} руб. ({{$contract->amount_reestr_text}}) <?php if($contract->vat_reestr) echo ', в т.ч. НДС (20%) - ' . $contract->vat . ' руб. (' . $contract->vat_text . ').'; ?>
													</td>
												</tr>
												<tr>
													<td colspan='3' style='text-indent: 25px;'>
														Общая сумма аванса, перечисленная за выполненнные работы (услуги), составила
													</td>
												</tr>
												<tr>
													<td colspan='3' style='text-align: center'>
														{{$contract->prepayment}} руб.
													</td>
												</tr>
												<tr>
													<td colspan='3'>
														Следует к перечислению {{$contract->remainder}} руб. ({{$contract->remainder_text}}) <?php if($contract->vat_reestr) echo ', в т.ч. НДС (20%) - ' . $contract->vat_remainder . ' руб. (' . $contract->vat_remainder_text . ').'; ?>
													</td>
												</tr>
											@endif
											@if($contract->approximate_amount_reestr == 1)
												<tr>
													<td colspan='3' style='text-indent: 25px;'>
														Договорная (ориентировочная) цена составляет {{$contract->amount_reestr}} руб. ({{$contract->amount_reestr_text}}) <?php if($contract->vat_reestr) echo ', в т.ч. НДС (20%) - ' . $contract->vat . ' руб. (' . $contract->vat_text . ').'; ?>
													</td>
												</tr>
												<tr>
													<td colspan='3'>
														Ориентировочная цена подлежит переводу в фиксированную цену на основании Протокола цены единицы продукции и выбора вида цены с учетом заключения отдела 624 ВП Минобороны России.<br/>
														Настоящий акт не является основание для окончательного расчета по контракту.
													</td>
												</tr>
											@endif
											<tr>
												<td style='width: 240px;'>
													Работу сдал:<br/>
													<br/>
													Директор<br/>
													филиала "НТИИМ"<br/>
													ФКП "НИО "ГБИП Росссии"<br/>
													<br/>
													_______________ Н.П. Смирнов<br/>
													{{date('"d" M Y г.', time())}}<br/>
													М.П.
												</td>
												<td></td>
												<td style='width: 240px;'>
													Работу принял:<br/>
													<br/>
													<br/>
													<br/>
													<br/>
													<br/>
													________________<br/>
													"  " ___________ {{date('Y г.', time())}}<br/>
													М.П.
												</td>
											</tr>
											<tr></tr>
											<tr></tr>
											<tr></tr>
										@endforeach
									@endforeach
								@endforeach
							</tbody>
						</table>
					@else
						<div class="alert alert-danger">
							Не найдены договоры (контракты) с введённым номером! {{isset($_GET['number_contract']) ? '(' . $_GET['number_contract'] . ')' : ''}}
						</div>
					@endif
				@endif
			</div>
		@else
			<div class="alert alert-danger">
				Необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection