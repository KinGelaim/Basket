@extends('layouts.header')

@section('title')
	Печать Сведения о количестве и об общей стоимости договоров
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-2">
						<button class='btn btn-primary' id='createExcel' real_name_table='Сведения о количестве и об общей стоимости договоров'>Сформировать Excel</button>
					</div>
				</div>
				<div id='copyTarget'>
					<div class='row' style='text-align: center;'>
						<div class="col-md-12">
							{{$text}}
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
								<th style='text-align: center; vertical-align: middle;' rowspan=2>№ п/п</th>
								<th style='text-align: center; vertical-align: middle;' rowspan=2>№ договора</th>
								<th style='text-align: center; vertical-align: middle;' rowspan=2>Наименование контрагента</th>
								<th style='text-align: center; vertical-align: middle;' rowspan=2>Предмет договора</th>
								<th style='text-align: center; vertical-align: middle;' rowspan=2>ОКПД 2</th>
								<th style='text-align: center; vertical-align: middle;' rowspan=2>Реестровый номер договора/контракта</th>
								<th style='text-align: center; vertical-align: middle;' rowspan=2>Сумма договора/контракта</th>
								<th style='text-align: center; vertical-align: middle;' colspan=2>Исполнение</th>
								<th style='text-align: center; vertical-align: middle;' rowspan=2>Отбор поставщика</th>
								<th style='text-align: center; vertical-align: middle;' rowspan=2>Принадлежность к СМСП</th>
							</tr>
							<tr>
								<th style='text-align: center; vertical-align: middle;'>Стоимостной объем товаров, работ, услуг, руб.</th>
								<th style='text-align: center; vertical-align: middle;'>Оплачено товаров, работ, услуг, руб.</th>
							</tr>
							<tr>
								<th style='text-align: center; vertical-align: middle;'>1</th>
								<th style='text-align: center; vertical-align: middle;'>2</th>
								<th style='text-align: center; vertical-align: middle;'>3</th>
								<th style='text-align: center; vertical-align: middle;'>4</th>
								<th style='text-align: center; vertical-align: middle;'>5</th>
								<th style='text-align: center; vertical-align: middle;'>6</th>
								<th style='text-align: center; vertical-align: middle;'>7</th>
								<th style='text-align: center; vertical-align: middle;'>8</th>
								<th style='text-align: center; vertical-align: middle;'>9</th>
								<th style='text-align: center; vertical-align: middle;'>10</th>
								<th style='text-align: center; vertical-align: middle;'>11</th>
							</tr>
						</thead>
						<tbody>
							@if(isset($contracts))
								<?php $count = 0; $all_amount = 0; $all_amount_st = 0; $all_amount_pp = 0; ?>
								@foreach($contracts as $contract)
									<tr>
										<td style='text-align: center; vertical-align: middle;'>{{++$count}}</td>
										<td>{{$contract->number_contract}}</td>
										<td>{{$contract->counterpartie_name}}</td>
										<td>{{$contract->item_contract}}</td>
										<td style='text-align: center;'>{{$contract->okpd_2_reestr}}</td>
										<td>{{$contract->reestr_number_reestr}}</td>
										<td>{{is_numeric($contract->amount_contract_reestr) ? number_format($contract->amount_contract_reestr, 2, '.', '&nbsp;') : ($contract->amount_contract_reestr ? $contract->amount_contract_reestr : (is_numeric($contract->amount_reestr) ? number_format($contract->amount_reestr, 2, '.', '&nbsp;') : $contract->amount_reestr))}}</td>
										<td>{{number_format($contract->amount_st, 2, '.', '&nbsp;')}}</td>
										<td>{{number_format($contract->amount_pp, 2, '.', '&nbsp;')}}</td>
										<td>{{$contract->name_selection_supplier}}</td>
										<td style='text-align: center; vertical-align: middle;'>{{$contract->purchase_reestr ? '+' : ''}}</td>
									</tr>
									<?php 
										if (is_numeric($contract->amount_contract_reestr))
											$all_amount += $contract->amount_contract_reestr;
										else if (is_numeric($contract->amount_reestr))
											$all_amount += $contract->amount_reestr;
										
										if (is_numeric($contract->amount_st))
											$all_amount_st += $contract->amount_st;
										if (is_numeric($contract->amount_pp))
											$all_amount_pp += $contract->amount_pp;
									?>
								@endforeach
								<tr>
									<td style='text-align: center; vertical-align: middle;'>{{$count}}</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td style='text-align: right;'><b>Всего:<b/></td>
									<td>{{number_format($all_amount, 2, '.', '&nbsp;')}}</td>
									<td>{{number_format($all_amount_st, 2, '.', '&nbsp;')}}</td>
									<td>{{number_format($all_amount_pp, 2, '.', '&nbsp;')}}</td>
									<td></td>
									<td></td>
								</tr>
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
