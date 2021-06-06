@extends('layouts.header')

@section('title')
	Печать уведомления
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="print_area">
				<div class='row'>
					<div class="col-md-2">
						<button class='btn btn-secondary createExcel' href_table='resultTable' real_name_table='Отчёт'>Сформировать Excel</button>
					</div>
					<div class="col-md-2">
						<button class='btn btn-primary btnProtocolOpen' href_protocol='kontrolorderreports://print?{{$query}}'>Сформировать Word</button>
					</div>
					<div class="col-md-2">
						<button class='btn btn-primary btn-href' href="{{route('print_report_pdf')}}?{{$query}}">Сформировать PDF</button>
					</div>
				</div>
				<div id='resultTable'>
					<table class="table">
						<tbody>
							<tr>
								<td colspan='2' style='text-align: center; border: none;'><b>{{$title}}</b></td>
								<td style='border: none;'></td>
								<td style='border: none;'></td>
								<td style='border: none;'>{{date('d.m.Y',time())}} г.</td>
							</tr>
						</tbody>
					</table>
					<table class="table table-bordered tablePrint" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
						<thead>
							<tr>
								<th>№ п/п<br/>Документ,<br/>№,дата</th>
								<th>Организация</th>
								<th>Содержание документа</th>
								<th>Содержание поручения</th>
								<th>Выполнение поручения</th>
								@if(!isset($use))
									<th>Срок исп., /<br/>перенос срока /<br/>просрочено</th>
								@else
									<th>Срок исп.,<br/>перенесено,<br/>исполнено,<br/>хар-ка</th>
								@endif
							</tr>
						</thead>
						<tbody>
							@foreach($result as $key=>$value)
								<tr>
									<td><b>{{$key}}</b></td>
									<td><b>({{count($value)}})</b></td>
								</tr>
								@foreach($value as $order)
									<tr>
										<td>{{$order->number_document}}<br/>{{$order->type_document}}<br/> {{$order->number_order}}<br/> {{$order->date_order}}</td>
										<td>{{$order->period_kontrol}}<br/>{{$order->counterpartie}}</td>
										<td>{{$order->short_maintenance}}</td>
										<td>{{$order->maintenance_order}}</td>
										<td>{{$order->events}}</td>
										@if(!isset($use))
											<td>{{$order->date_maturity_formating}}<br/>{{$order->last_postponement_formating}}<br/>{{$order->proskor_formating}}</td>
										@else
											<td>{{$order->date_maturity_formating}}<br/>{{$order->last_postponement_formating}}<br/>{{$order->date_complete_executor_formating}}<br/>{{$order->character_formating}}</td>
										@endif
									</tr>
									<tr>
										<td colspan='6'><u>Уведомления от:</u></td>
									</tr>
									<tr>
										<td colspan='6'>
											<pre>@foreach($order->notifycations as $notify){{$notify->created_at_formating}}	@endforeach</pre>
										</td>
									</tr>
								@endforeach
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		@else
			<div class="alert alert-danger">
				Для просмотра данной страницы необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection
