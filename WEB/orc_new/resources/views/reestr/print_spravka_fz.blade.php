@extends('layouts.header')

@section('title')
	Печать Справка о договорах сданных в ОУД
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-2">
						<button class='btn btn-primary' id='createExcel' real_name_table='Справка о договорах сданных в ОУД'>Сформировать Excel</button>
					</div>
				</div>
				<div id='copyTarget'>
					<div class='row' style='text-align: center;'>
						<div class="col-md-12">
							@if($number_fz == 223)
								Справка: Договоры/Контракты, сданные в ОУД на закуп по 223-ФЗ
							@else
								Справка: Договоры/Контракты, сданные в ОУД на закуп по 44-ФЗ
							@endif
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
								<th style='text-align: center; vertical-align: middle;'>Номер Дог./Контр. по Реестру, номер Контрагента</th>
								<th style='text-align: center; vertical-align: middle;'>Сумма</th>
								<th style='text-align: center; vertical-align: middle;'>Дата сдачи Договора/Контракта в ОУД</th>
							</tr>
						</thead>
						<tbody>
							@if(isset($contracts))
								<?php $k=0; $full_amount=0; ?>
								@foreach($contracts as $contract)
									<tr>
										<td>{{$contract->number_contract}}<br/>{{$contract->number_counterpartie_contract_reestr}}</td>
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
										<td>{{$contract->date_save_contract_reestr}}</td>
									</tr>
									<?php 
										$k++; 
										if($contract->amount_contract_reestr != null && $contract->amount_contract_reestr != 'NULL')
											$full_amount += str_replace(' ','',str_replace(',','.',$contract->amount_contract_reestr));
										else if($contract->amount_reestr != null && $contract->amount_reestr != 'NULL')
											$full_amount += str_replace(' ','',str_replace(',','.',$contract->amount_reestr));
									?>
								@endforeach
								<tr>
									<td style='text-align: right;'><b>Итого:</b></td>
									<td><b>{{str_replace('.',',',number_format($full_amount, 2, ',', ' '))}}</b></td>
									<td></td>
								</tr>
							@endif
						</tbody>
					</table>
					<div class='row' style='height: 20px;'>
					</div>
					<div class='row'>
						<div class="col-md-8 col-md-offset-2">
							Начальник отдела управления договорами<span style='float: right;'>{{$lider}}</span>
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
