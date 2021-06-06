@extends('layouts.header')

@section('title')
	Архив
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор')
				<applet code="{{ asset('applet/Paint/Paint.class') }}" width='400' height='300'>
					ОГО! У вас нет Java плэйера! Нужен JavaPlayer!!!
				</applet>
			@endif
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
				@if(isset($contracts))
					<div class="row">
						<div class="col-md-12">
							Список договоров
						</div>
					</div>
					<div class="row">
						<div id="allcontracts" class="col-md-12">
							@if($contracts)
								<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
									<thead>
										<tr>
											<th>Номер договора</th>											
											<th>№ исх. Заявки</th>
											<th>Вид договора</th>
											<th>Контрагент</th>
											<th>Предмет дог./контр.</th>
											<th>Сумма</th>
											<th style='width: 150px;'>Дата вступления Дог./Контр. в силу</th>
											@if(Auth::User()->hasRole()->role == 'Администратор')
												<th>Удаление</th>
											@endif
										</tr>
									</thead>
									<tbody>
										@foreach($contracts as $contract)
											@if(Auth::User()->hasRole()->role == 'Администратор')
												<tr class='rowsContract rowsContractClickPEO cursorPointer' id_contact='{{$contract->id}}'
															number_contract='{{ $contract->number_contract }}'
															contract_peo='{{ route("department.ekonomic.show_peo",$contract->id) }}'
															contract_new_reestr='{{ route("department.ekonomic.contract_new_reestr",$contract->id) }}'
															contract_reestr='{{ route("department.ekonomic.show_reestr",$contract->id) }}'
															contract_reconciliation='{{ route("department.reconciliation.show",$contract->id) }}'
															contract_new_peo='{{ route("department.peo.show_contract",$contract->id) }}'>
													<td>
														{{ $contract->number_contract }}
													</td>
													<td>
														{{ $contract->app_outgoing_number_reestr }}
													</td>
													<td>
														{{ $contract->name_view_contract }}
													</td>
													<td>
														{{ $contract->name_counterpartie_contract }}
													</td>
													<td>
														{{ $contract->item_contract }}
													</td>
													<td style='width: 120px;'>
														{{ $contract->amount_reestr }}
													</td>
													<td>
														{{ $contract->renouncement_contract == 1 ? 'Отказ' : $contract->date_entry_into_force_reestr }}
													</td>
													@if(Auth::User()->hasRole()->role == 'Администратор')
														<td>
															<button type='button' class='btn btn-danger btn-href' type='button' href='{{route("department.ekonomic.delete",$contract->id)}}'>Удалить</button>
														</td>
													@endif
												</tr>
											@elseif(Auth::User()->hasRole()->role == 'Планово-экономический отдел')
												<tr class='rowsContract cursorPointer btn-href' href='{{ route("department.peo.show_contract",$contract->id) }}'>
													<td>
														{{ $contract->number_contract }}
													</td>
													<td>
														{{ $contract->app_outgoing_number_reestr }}
													</td>
													<td>
														{{ $contract->name_view_contract }}
													</td>
													<td>
														{{ $contract->name_counterpartie_contract }}
													</td>
													<td>
														{{ $contract->item_contract }}
													</td>
													<td style='width: 120px;'>
														{{ $contract->amount_reestr }}
													</td>
													<td>
														{{ $contract->renouncement_contract == 1 ? 'Отказ' : $contract->date_entry_into_force_reestr }}
													</td>
												</tr>
											@else
												<tr class='rowsContract cursorPointer btn-href' href='{{ route("department.ekonomic.contract_new_reestr",$contract->id) }}'>
													<td>
														{{ $contract->number_contract }}
													</td>
													<td>
														{{ $contract->app_outgoing_number_reestr }}
													</td>
													<td>
														{{ $contract->name_view_contract }}
													</td>
													<td>
														{{ $contract->name_counterpartie_contract }}
													</td>
													<td>
														{{ $contract->item_contract }}
													</td>
													<td style='width: 120px;'>
														{{ $contract->amount_reestr }}
													</td>
													<td>
														{{ $contract->renouncement_contract == 1 ? 'Отказ' : $contract->date_entry_into_force_reestr }}
													</td>
												</tr>
											@endif
										@endforeach
									</tbody>
								</table>
							@endif
						</div>
						<!-- Модальное окно выбора договора -->
						<div class="modal fade" id="selectContract" tabindex="-1" role="dialog" aria-labelledby="selectContractModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="selectContractModalLabel">Договор</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<div class='row'>
											<div class="col-md-12">
												<button id='new_reestr' class="btn btn-primary btn-href" type="button" href="" style='width: 150px;'>Новый реестр</button>
											</div>
											<div class="col-md-12" style='margin-top: 5px;'>
												<button id='reestr' class="btn btn-primary btn-href" type="button" href="" style='width: 150px;'>Старый реестр</button>
											</div>
											<div class="col-md-12" style='margin-top: 5px;'>
												<button id='result' class="btn btn-primary btn-href" type="button" href="" style='width: 150px;'>Отчет ПЭО</button>
											</div>
											<div class="col-md-12" style='margin-top: 5px;'>
												<button id='sogl' class="btn btn-primary btn-href" type="button" href="" style='width: 150px;'>Карточка договора</button>
											</div>
											<div class="col-md-12" style='margin-top: 5px;'>
												<button id='modal_btn_peo' class="btn btn-primary btn-href" type="button" href="" style='width: 150px;'>Карточка ПЭО</button>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				@endif
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
