@extends('layouts.header')

@section('title')
	Отдел управления договорами
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<label>Список договоров СИП, которым необходимо присвоить номер</label>
						</div>
					</div>
					<div class="row">
						<div id="allcontracts" class="col-md-12">
							<div class="row">
								<div id="allcontracts" class="col-md-12">
									<button class="btn btn-primary btn-href" type="button" href="{{ route('department.ekonomic.sip') }}" style="width: 167px;float: right;">Договоры СИП</button>
								</div>
							</div>
							<div class="row">
								<div id="allcontracts" class="col-md-12">
									<button class="btn btn-primary btn-href" type="button" href="{{ route('department.ekonomic') }}" style="width: 167px;float: right;">Реестр</button>
								</div>
							</div>
							@if($contracts)
								<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
									<thead>
										<tr>
											<th>Номер договора</th>
											<th>Вид договора</th>
											<th>Наименование работ</th>
											<th>Контрагент</th>
											@if(Auth::User()->hasRole()->role == 'Администратор')
												<th>Удаление</th>
											@endif
										</tr>
									</thead>
									<tbody>
										@foreach($contracts as $contract)
											<tr class='rowsContract cursorPointer btn-href' id_contact='{{$contract->id}}' href='{{route("department.reconciliation.show", $contract->id)}}'>
												<td>
													{{ $contract->number_contract }}
												</td>
												<td>
													{{ $contract->name_view_contract }}
												</td>
												<td>
													{{ $contract->name_work_contract }}
												</td>
												<td>
													{{ $contract->name_counterpartie_contract }}
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
						<div class="col-md-12" style="text-align: center;">
							@if($count_paginate)
								<nav aria-label="Page navigation example">
								  <ul class="pagination justify-content-center">
									@if($prev_page)
										<li class="page-item">
										  <a class="page-link" href="?page={{$prev_page}}{{$link}}" tabindex="-1">Предыдущая</a>
										</li>
									@else
										<li class="page-item disabled">
										  <a class="page-link" href="" tabindex="-1">Предыдущая</a>
										</li>
									@endif
									@for($i = 1; $i < $count_paginate+1; $i++)
										@if($i == $page)
											<li class="page-item active"><a class="page-link" href="?page={{$i}}{{$link}}">{{$i}}</a></li>
										@else
											<li class="page-item"><a class="page-link" href="?page={{$i}}{{$link}}">{{$i}}</a></li>
										@endif
									@endfor
									@if($next_page)
										<li class="page-item">
										  <a class="page-link" href="?page={{$next_page}}{{$link}}">Следующая</a>
										</li>
									@else
										<li class="page-item disabled">
										  <a class="page-link" href="">Следующая</a>
										</li>
									@endif
								  </ul>
								</nav>
							@endif
						</div>
					</div>
				</div>
				<script>
					if($('#selectContract').attr('attr-open-modal') == 'open1')
						$('#selectContract').modal('show');
				</script>
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
