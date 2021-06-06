@extends('layouts.header')

@section('title')
	Договоры контрагента
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role != 'Пользователь')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							{{$counterpartie->name_full}}
						</div>
					</div>
					<div class="row">
						<div id="allcontracts" class="col-md-12">
							@if($contracts)
								<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
									<thead>
										<tr>
											<th>Номер договора</th>
											<th>Дата рег.</th>
											<th>Срок действия</th>
											<th>Сумма</th>
											<th>Предмет договора</th>
											<th>№ дог.контрагента</th>
											@if(Auth::User()->hasRole()->role == 'Администратор')
												<th>Удаление</th>
											@endif
										</tr>
									</thead>
									<tbody>
										@foreach($contracts as $contract)
											<tr class='rowsContract cursorPointer btn-href' id_contact='{{$contract->id}}' href='{{ route("department.ekonomic.contract_new_reestr",$contract->id) }}'>
												<td>
													{{ $contract->number_contract }}
												</td>
												<td>
													{{ $contract->date_registration_project_reestr }}
												</td>
												<td>
													{{ $contract->date_contract_reestr }}
												</td>
												<td>
													{{ $contract->amount_reestr }}
												</td>
												<td>
													{{ $contract->item_contract }}
												</td>
												<td>
													{{ $contract->number_counterpartie_contract_reestr }}
												</td>
												@if(Auth::User()->hasRole()->role == 'Администратор')
													<td>
														<button type='button' class='btn btn-danger btn-href' type='button' href='{{route("department.ekonomic.delete",$contract->id)}}'>Удалить</button>
													</td>
												@endif
											</tr>
										@endforeach
									</tbody>
								</table>
							@endif
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
