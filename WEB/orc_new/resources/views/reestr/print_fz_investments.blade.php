@extends('layouts.header')

@section('title')
	Печать Список контрактов по инвестициям 44-ФЗ
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-2">
						<button class='btn btn-primary' id='createExcel' real_name_table='Список контрактов по инвестициям 44-ФЗ'>Сформировать Excel</button>
					</div>
				</div>
				<div id='copyTarget'>
					<div class='row' style='text-align: center;'>
						<div class="col-md-12">
							Список контрактов по инвестициям 44-ФЗ
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
								<th style='text-align: center; vertical-align: middle;'>№</th>
								<th style='text-align: center; vertical-align: middle;'>Наименование</th>
								<th style='text-align: center; vertical-align: middle;'>Статус</th>
								<th style='text-align: center; vertical-align: middle;'>НМЦЗ, руб</th>
								<th style='text-align: center; vertical-align: middle;'>Дата публикации извещения</th>
								<th style='text-align: center; vertical-align: middle;'>Окончание срока рассмотрения 1 частей заявок</th>
								<th style='text-align: center; vertical-align: middle;'>Дата проведения ЭА</th>
								<th style='text-align: center; vertical-align: middle;'>Окончание срока рассмотрения 2 частей заявок</th>
								<th style='text-align: center; vertical-align: middle;'>Ориентировочная дата подписания контракта</th>
								<th style='text-align: center; vertical-align: middle;'>Контрагент</th>
								<th style='text-align: center; vertical-align: middle;'>Сумма ГК, руб</th>
								<th style='text-align: center; vertical-align: middle;'>Экономия, руб</th>
							</tr>
						</thead>
						<tbody>
							@if(isset($contracts))
								<?php $k=1; $full_amount=0; $full_economy=0; ?>
								@foreach($contracts as $contract)
									<tr>
										<td>{{$k++}}</td>
										<td>{{$contract->item_contract}}</td>
										<td>{{$contract->number_counterpartie_contract_reestr}}/{{$contract->number_contract}} от {{$contract->date_contract_on_first_reestr}}</td>
										<td>{{is_numeric($contract->nmcd_reestr) ? number_format($contract->nmcd_reestr, 2, ',', ' ') : $contract->nmcd_reestr}}</td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td>{{$contract->date_control_signing_contract_reestr}}</td>
										<td>{{$contract->counterpartie_name}}</td>
										<td>
											<?php
												if(is_numeric($contract->amount_contract_reestr))
													echo number_format($contract->amount_contract_reestr, 2, ',', ' ');
												else if($contract->amount_contract_reestr)
													echo $contract->amount_contract_reestr;
												else if(is_numeric($contract->amount_reestr))
													echo number_format($contract->amount_reestr, 2, ',', ' ');
												else
													echo $contract->amount_reestr;
											?>
										</td>
										<td>{{is_numeric($contract->economy_reestr) ? number_format($contract->economy_reestr, 2, ',', ' ') : $contract->economy_reestr}}</td>
									</tr>
									<?php 
										if($contract->amount_contract_reestr != null && $contract->amount_contract_reestr != 'NULL')
											$full_amount += str_replace(' ','',str_replace(',','.',$contract->amount_contract_reestr));
										else if($contract->amount_reestr != null && $contract->amount_reestr != 'NULL')
											$full_amount += str_replace(' ','',str_replace(',','.',$contract->amount_reestr));
											
										if($contract->economy_reestr != null && $contract->economy_reestr != 'NULL')
											$full_economy += str_replace(' ','',str_replace(',','.',$contract->economy_reestr));
									?>
								@endforeach
							@endif
						</tbody>
					</table>
					<div class='row' style='height: 20px;'>
					</div>
					<div class='row'>
						<div class="col-md-8 col-md-offset-2">
							<div class='row'>
								<div class="col-md-7">
									Общая сумма: {{str_replace('.',',',number_format($full_amount, 2, ',', ' '))}}
								</div>
								<div class="col-md-5">
									Начальник отдела управления договорами<span style='float: right;'>{{$lider}}</span>
								</div>
							</div>
							<div class='row'>
								<div class="col-md-7">
									Общая экономия: {{str_replace('.',',',number_format($full_economy, 2, ',', ' '))}}
								</div>
								<div class="col-md-5">
									Секретарь ЕК<span style='float: right;'>Едемская Н.Н.</span>
								</div>
							</div>
						</div>
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
