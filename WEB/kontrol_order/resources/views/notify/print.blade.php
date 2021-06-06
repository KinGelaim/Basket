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
						<button class='btn btn-primary btnProtocolOpen' href_protocol='kontrolorderreports://print?{{$query}}'>Сформировать единый Word</button>
					</div>
				</div>
				@foreach($orders as $order)
					<div class='row'>
						<div class="col-md-2">
							<button class='btn btn-secondary createExcel' href_table='resultTable{{$order->id}}' real_name_table='Уведомление'>Сформировать Excel {{$order->number_document}}</button>
						</div>
					</div>
					<div id='resultTable{{$order->id}}'>
						<table class="table tablePrint">
							<tbody>
								<tr>
									<td colspan='2' style='text-align: center; border: none;'><b>Карточка учета и исполнения директивного документа</b></td>
									<td style='border: none;'></td>
									<td style='border: none;'></td>
									<td style='border: none;'>{{date('d.m.Y',time())}} г.</td>
								</tr>
							</tbody>
						</table>
						<table class="table table-bordered tablePrint" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
							<tbody>
								<tr>
									<td>{{$order->number_document}}</td>
									<td><b>Документ</b></td>
									<td>{{$order->type_document}}</td>
									<td><b>Дата</b></td>
									<td>{{$order->date_order}}</td>
									<td><b>Номер</b></td>
									<td>{{$order->number_order}}</td>
								</tr>
								<tr>
									<td colspan='2'><b>Организация</b></td>
									<td colspan='5'>{{$order->counterpartie}}</td>
								</tr>
								<tr>
									<td colspan='2'><b>Содержание документа</b></td>
									<td colspan='5'>{{$order->short_maintenance}}</td>
								</tr>
								<tr>
									<td colspan='2'><b>Содержание поручения</b></td>
									<td colspan='5'>{{$order->maintenance_order}}</td>
								</tr>
								<tr>
									<td colspan='2'><b>Отв. за контроль</b></td>
									<td colspan='5'>{{$order->surname}} {{substr($order->name,0,2)}}.{{substr($order->patronymic,0,2)}}.</td>
								</tr>
								<tr>
									<td colspan='2'><b>Соисполнители</b></td>
									<td colspan='5'>{{$order->second_executor}}</td>
								</tr>
								<tr>
									<td colspan='2'><b>Срок исполнения</b></td>
									<td>{{$order->date_maturity_format}}</td>
									<td><b>Дата переноса</b></td>
									<td>{{$order->last_postponement_format}}</td>
									<td><b>Осталось дней</b></td>
									<td>{{$order->out_days_format}}</td>
								</tr>
								<tr>
									<td colspan='2'><b>Уведомление от</b></td>
									<td colspan='5'>{{$order->notifycation_list}}</td>
								</tr>
								<tr>
									<td colspan='2'><b>Периодичность</b></td>
									<td colspan='5'>{{$order->period_kontrol}}</td>
								</tr>
							</tbody>
						</table>
						<table class="table tablePrint">
							<tbody>
								<tr>
									<td colspan='2' style='text-align: center; border: none;'><b>Информация об итогах исполнения (заполняется исполнителем)</b></td>
									<td style='border: none;'></td>
									<td style='border: none;'></td>
									<td style='border: none;'></td>
								</tr>
							</tbody>
						</table>
						<table class="table table-bordered tablePrint" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
							<tbody>
								<tr>
									<td><b>Мероприятия,<br/>проведенные в<br/>ходе исполнения<br/>поручения</b></td>
									<td colspan='2'>{{$order->events}}</td>
								</tr>
								<tr>
									<td><b>Исполнено, № док.</b></td>
									<td>Дата</td>
									<td>Подпись</td>
								</tr>
							</tbody>
						</table>
						<table class="table tablePrint">
							<tbody>
								<tr>
									<td colspan='2' style='text-align: center; border: none;'><b>Резолюция директора</b></td>
									<td style='border: none;'></td>
									<td style='border: none;'></td>
									<td style='border: none;'></td>
								</tr>
							</tbody>
						</table>
						<table class="table table-bordered tablePrint" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
							<tbody>
								<tr>
									<td rowspan='2'><b>Снять с контроля</b></td>
									<td rowspan='2'><b>Продлить</b></td>
									<td rowspan='2'><b>Срок</b></td>
									<td colspan='3'><b>Установить периодический контроль</b></td>
								</tr>
								<tr>
									<td><b>Раз в неделю</b></td>
									<td><b>Раз в месяц</b></td>
									<td><b>Раз в квартал</b></td>
								</tr>
								<tr style='height: 100px;'>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>
				@endforeach
			</div>
		@else
			<div class="alert alert-danger">
				Для просмотра данной страницы необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection
