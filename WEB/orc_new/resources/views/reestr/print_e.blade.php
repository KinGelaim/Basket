@extends('layouts.header')

@section('title')
	Печать Сводная таблица Заказчиков
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-12">
						<button class='btn btn-primary' id='createExcel' real_name_table='Сводная таблица Заказчиков'>Сформировать Excel</button>
					</div>
				</div>
				<div class='row' style='text-align: center;'>
					<div class="col-md-12">
						Сводная таблица Заказчиков (ФКП "НТИИМ" Участник во всех данных закупках)
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
				<table id='resultTable' class="table table-bordered tablePrint" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
					<thead style='text-align: center;'>
						<tr>
							<th>№ п/п</th>
							<th>Предмет поставки</th>
							<th>№ закупки<br/>Форма процедуры<br/>Срок подачи заявки - исх.<br/>Обеспечение заявки<br/><br/>Протокол<br/>Услуги ЭТП</th>
							<th>Осн. условия: срок поставки, срок действия договора, НМЦК, расчеты<br/>Обеспечение исполнения договора (контр)</th>
							<th>№ договора (контракта) Срок подписания<br/><br/>Д.С.</th>
							<th>Обеспечение испол.дог. Б.Г. - Ден. Ср-ва</th>
							<th>Наименование отдела, ответственные сотрудники: Подразделения, ОУД</th>
							<th>Заказчик, контакты, E-mail, телефоны</th>
							<th>Исполнение: аванс, плат. поручение, расчет, товарная накладная</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($contracts))
							<?php $k=1; ?>
							@foreach($contracts as $contract)
								<tr>
									<td>{{$k++}}</td>
									<td>{{$contract->item_contract}}</td>
									<td>
										<br/>
										
										<br/>
										@foreach($contract->protocols as $protocol)
											{{$protocol->name_protocol}} от {{$protocol->date_on_first_protocol}}<br/>
										@endforeach
									</td>
									<td>{{$contract->date_maturity_reestr}}<br/>{{$contract->date_contract_reestr}}<br/>НМЦК:{{$contract->nmcd_reestr}}<br/>{{$contract->prepayment_order_reestr}}<br/>{{$contract->score_order_reestr}}</td>
									<td>
										{{$contract->number_contract}}
										<br/>
										{{$contract->number_counterpartie_contract_reestr}} {{$contract->date_contract_on_first_reestr ? 'от ' . $contract->date_contract_on_first_reestr : ''}}
										<br/><br/>
										@foreach($contract->add_agreements as $add_agreement)
											{{$add_agreement->name_protocol}} от {{$add_agreement->date_on_first_protocol}}<br/>
										@endforeach
									</td>
									<td></td>
									<td></td>
									<td>{{$contract->counterpartie_name}}</td>
									<td></td>
								</tr>
							@endforeach
						@endif
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
