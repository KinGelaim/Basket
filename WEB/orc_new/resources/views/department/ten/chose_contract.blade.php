@extends('layouts.header')

@section('title')
	Прикрепление контракта
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Десятый отдел')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							Информация о контрактах
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<form id='formChoseContractForTen' method='POST' action="{{route('ten.chose_contract', $component->id)}}" check-count='{{$component->need_count}}' href-to-change-components="{{route('ten.document_components', $id_document)}}">
								{{csrf_field()}}
								<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
									<thead>
										<tr>
											<th>№ договора НТИИМ</th>
											<th>№ договора (контрагент)</th>
											<th>Вид работ</th>
											<th>Предмет договора</th>
											<th>Контрагент</th>
											<th>Начальная сумма</th>
											<th>Окончательная сумма</th>
											<th>Срок исполнения</th>
											<th>Количество {{$component->name_component}}</th>
										</tr>
									</thead>
									<tbody>
										<?php $count_txt = 0; ?>
										@foreach($contracts as $contract)
											<tr class="rowsContract">
												<td style='display: none;'><input name='id_contract[{{$count_txt}}]' value='{{$contract->id}}'/></td>
												<td>
													{{ $contract->number_contract }}
												</td>
												<td>
													{{ $contract->number_counterpartie_contract_reestr }}
												</td>
												<td>
													{{ $contract->name_view_work }}
												</td>
												<td>
													{{ $contract->name_work_contract }}
												</td>
												<td>
													{{ $contract->name_counterpartie_contract }}
												</td>
												<td>
													{{ $contract->amount_reestr }} <br/>
												</td>
												<td>
													{{ $contract->amount_contract_reestr }} <br/>
												</td>
												<td>
													{{ $contract->date_maturity_date_reestr ? $contract->date_maturity_date_reestr : $contract->date_maturity_reestr }} <br/>
												</td>
												<td>
													<input class='form-control count_element' type='text' value="" name='count_element[{{$count_txt}}]' required />
												</td>
											</tr>
											<?php $count_txt++; ?>
										@endforeach
									</tbody>
								</table>
								@if(count($contracts) > 0)
									<button type='submit' onclick="" class='btn btn-primary' type='button' style='float: right;'>Прикрепить</button>
								@endif
							</form>
						</div>
					</div>
				</div>
			@else
				<div class="alert alert-danger">
					Недостаточно прав для просмотра данной страницы!
				</div>
			@endif
		@else
			<div class="alert alert-danger">
				Необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection
