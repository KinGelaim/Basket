@extends('layouts.header')

@section('title')
	Печать контрагентов
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		<div class="">
			<div class='row'>
				<div class="col-md-12">
					<button class='btn btn-primary' id='createExcel' real_name_table='Контраенты отдела № 22'>Сформировать Excel</button>
				</div>
			</div>
			<div class='row' style='text-align: center;'>
				<div class="col-md-12">
					<h3>Контрагенты отдела № 22</h3>
				</div>
			</div>
			<div class='row' style='text-align: right;'>
				<div class="col-md-12">
					по состоянию на {{date('d.m.Y', time())}} г.
				</div>
			</div>
			<table id='resultTable' class="table table-bordered tablePrint" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
				<thead style='text-align: center;'>
					<tr>
						<th style='width: 50px;'>№ п/п</th>
						<th style='width: 69px;'>Код</th>
						<th style='width: 150px;'>Полное и сокращенное наименование предприятия</th>
						<th style='width: 210px;'>Адрес предприятия</th>
						<th style='width: 200px;'>Ф.И.О. руководителя предприятия (код, номер телефона)</th>
						<th>Должность, Ф.И.О., телефон сотрудников</th>
					</tr>
				</thead>
				<tbody>
					<?php $k=0; ?>
					@foreach($counterparties as $counterpartie)
						<tr>
							<td>{{++$k}}</td>
							<td>{{$counterpartie->code}}</td>
							<td>{{$counterpartie->name_full}}<br/><b>{{$counterpartie->name}}</b><br/><br/>ИНН: {{$counterpartie->inn}}</td>
							<td>{{$counterpartie->mailing_address}}<br/><br/>{{$counterpartie->email}}</td>
							<td>{{$counterpartie->lider}}<br/><br/>{{$counterpartie->telephone}}</td>
							<td>
								@foreach($counterpartie->employees as $employe)
									<b>{{$employe->post}}</b><br/>
									{{$employe->FIO}}<br/>
									@if($employe->telephone)
										{{$employe->telephone}}<br/>
									@endif
								@endforeach
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection
