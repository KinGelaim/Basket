@extends('layouts.header')

@section('title')
	Печать {{$mini_title}}
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-2">
						<button class='btn btn-primary' id='createExcel' real_name_table='{{$mini_title}}'>Сформировать Excel</button>
					</div>
					<div class="col-md-2">
						<button class='btn btn-secondary' id='createWord' real_name_table='{{$mini_title}}'>Сформировать Word</button>
					</div>
					<div class="col-md-2">
						<button class='btn btn-primary' id='copyInBuffer' copy_target='copyTarget'>Скопировать в буфер</button>
					</div>
					<div class="col-md-2">
						<button class='btn btn-secondary' id='createCSharp' data-toggle="modal" data-target="#printCSharp" type='button'>Сформировать отчет в хранилищи</button>
					</div>
				</div>
				<div id='copyTarget'>
					<div class='row' style='text-align: center;'>
						<div class="col-md-12">
							{{$title}}
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
								<th style='text-align: center; vertical-align: middle;'>Дата и номер обращения о согласовании сделки (указывается входящий номер Минпромторга России)</th>
								<th style='text-align: center; vertical-align: middle;'>Предмет сделки</th>
								<th style='text-align: center; vertical-align: middle;'>Сумма (в руб., долларах США или иной валюте)</th>
								<th style='text-align: center; vertical-align: middle;'>Дата и номер договора по сделке</th>
								<th style='text-align: center; vertical-align: middle;'>Срок действия согласуемой сделки</th>
								<th style='text-align: center; vertical-align: middle;'>Номер и дата письма Минпромторга России, согласовывающего сделку</th>
								<th style='text-align: center; vertical-align: middle;'>Дата получения (кредита, векселя, кредитной линии, изменения условий сделки и др.)</th>
								<th style='text-align: center; vertical-align: middle;'>Дата исполнения обязательств по сделки</th>
							</tr>
						</thead>
						<tbody>
							@if(isset($contracts))
								<?php $full_amount=0; ?>
								@foreach($contracts as $contract)
									<tr>
										<td>{{$contract->number_inquiry_reestr}}<br/>{{$contract->date_inquiry_reestr}}</td>
										<td>{{$contract->item_contract}}</td>
										<td>{{is_numeric($contract->amount_contract_reestr) ? number_format($contract->amount_contract_reestr, 2, '.', '&nbsp;') : ($contract->amount_contract_reestr ? $contract->amount_contract_reestr : (is_numeric($contract->amount_reestr) ? number_format($contract->amount_reestr, 2, '.', '&nbsp;') : $contract->amount_reestr))}}</td>
										<td>{{$contract->number_contract}}</td>
										<td>{{$contract->date_contract_reestr}}</td>
										<td>{{$contract->number_answer_reestr}}<br/>{{$contract->date_answer_reestr}}</td>
										<td></td>
										<td>{{$contract->date_maturity_reestr}}</td>
									</tr>
									<?php
										if($contract->amount_contract_reestr != null && $contract->amount_contract_reestr != 'NULL')
											$full_amount += str_replace(' ','',str_replace(',','.',$contract->amount_contract_reestr));
										else if($contract->amount_reestr != null && $contract->amount_reestr != 'NULL')
											$full_amount += str_replace(' ','',str_replace(',','.',$contract->amount_reestr));
									?>
								@endforeach
								<tr>
									<td colspan='8' style='text-align: center;'><b>Итого: {{str_replace('.',',',number_format($full_amount, 2, ',', '&nbsp;'))}}</b></td>
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
			<!-- Модальное окно формирования отчета -->
			<div class="modal fade" id="printCSharp" tabindex="-1" role="dialog" aria-labelledby="printCSharpModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<form method='POST' action="{{route('sharp.create')}}">
							{{csrf_field()}}
							<div class="modal-header">
								<h5 class="modal-title" id="printCSharpModalLabel">Формирование отчета</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div id='all_aplication' class="modal-body">
								<div class='form-group row'>
									<div class="col-md-4">
										<label>Введите название:</label>
									</div>
									<div class="col-md-8">
										<input type='text' name='real_name_report' class='form-control' required />
									</div>
								</div>
								<div class='form-group row' style='display: none;'>
									<div class="col-md-8">
										<input type='text' name='real_name_table' class='form-control' value='справка о крупных сделках'/>
									</div>
								</div>
								<div class='form-group row' style='display: none;'>
									<div class="col-md-8">
										<input type='text' name='query' class='form-control' value='{{$query}}'/>
									</div>
								</div>
								<div class='form-group row'>
									<div class="col-md-4">
										<label>Выберите тип:</label>
									</div>
									<div class="col-md-8">
										<select name='type_report' class='form-control' required >
											<option></option>
											<option>Word</option>
											<option>Excel</option>
										</select>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-primary">Сформировать</button>
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
							</div>
						</form>
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
