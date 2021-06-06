@extends('layouts.app')

@section('title')
	Контроль приказов
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								Приказы
							</div>
							<div class="panel-body">
								<div>
									<table class="table" style='margin: 0 auto; margin-bottom: 10px;'>
										<thead>
											<tr>
												<th style='text-align: center; vertical-align: middle;'>Номер, дата<br/>регистрации<br/>документа</th>										
												<th style='text-align: center; vertical-align: middle;'>Организация</th>
												<th style='text-align: center; vertical-align: middle;'>Тип документа</th>
												<th style='text-align: center; vertical-align: middle;'>Номер, дата<br/>приказа</th>
												<th style='text-align: center; vertical-align: middle;'>Срок исполнения/<br/>Дата рассылки</th>
												<th style='text-align: center; vertical-align: middle;'>Перенос<br/> срока / Дата<br/>служ.записки<br/>о переносе</th>
												<th style='text-align: center; vertical-align: middle;'>Срок действия/<br/>Осталось дн.<br/>Периодичность<br/>контроля</th>
												<th style='text-align: center; vertical-align: middle;'>Ответственный за<br/>контроль /<br/>Соисполнители</th>
											</tr>
										</thead>
										<tbody>
											@foreach($orders as $order)
												<tr>
													<td style='text-align: center; vertical-align: middle; <?php if($order->last_postponement ? ceil((strtotime($order->last_postponement['date_postponement'])-time())/60/60/24) < 0 : ceil((strtotime($order->date_maturity)-time())/60/60/24) < 0) echo "color: red;"?>'>
														{{ $order->number_document }}<br/>{{date('d.m.Y',strtotime($order->created_at))}}
													</td>
													<td>
														{{ $order->counterpartie }}
													</td>
													<td>
														{{ $order->type_document }}
													</td>
													<td style='text-align: center; vertical-align: middle;'>
														{{ $order->number_order }}<br/>{{$order->date_order}}
													</td>
													<td style='text-align: center; vertical-align: middle;'>
														{{ date('d.m.Y',strtotime($order->date_maturity)) }}<br/>
													</td>
													<td style='text-align: center; vertical-align: middle;'>
														{{$order->last_postponement ? date('d.m.Y', strtotime($order->last_postponement['date_postponement'])) : ''}}<br/>{{$order->last_postponement['date_service'] ? date('d.m.Y', strtotime($order->last_postponement['date_service'])) : ''}}
													</td>
													<td style='text-align: center; vertical-align: middle;'>
														{{$order->last_postponement ? ((strtotime($order->last_postponement['date_postponement'])-strtotime(date("d.m.Y",strtotime($order->created_at))))/60/60/24) : ((strtotime($order->date_maturity)-strtotime(date("d.m.Y",strtotime($order->created_at))))/60/60/24)}} / {{$order->last_postponement ? ceil((strtotime($order->last_postponement['date_postponement'])-time())/60/60/24) : ceil((strtotime($order->date_maturity)-time())/60/60/24)}}<br/>{{$order->period_kontrol}}
													</td>
													<td>
														{{$order->surname}} {{substr($order->name,0,2)}}.{{substr($order->patronymic,0,2)}}.<br/>{{$order->second_executor}}
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
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
