@extends('layouts.header')

@section('title')
	Печать {{$text}}
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="">
				<div class='row'>
					<div class="col-md-12">
						<button class='btn btn-primary' id='createExcel' real_name_table='{{$text}}'>Сформировать Excel</button>
					</div>
				</div>
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
				<table id='resultTable' class="table table-bordered tablePrint" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
					<thead style='text-align: center;'>
						<tr>
							<th>№ п/п</th>
							<th>№ Дог./Контр. ФКП "НТИИМ"</th>
							<th>№ Дог./Контр. Контрагента</th>
							<th>Контрагент (сокращ. наимен.)</th>
							<th>Предмет</th>
							<th>Дата регистр. проекта</th>
							<th>Дата заключения Дог./Контр.</th>
							<th>Дата сдачи Дог./Контр.</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($result))
							<?php $k=1; ?>
							@foreach($result as $key=>$value)
								<tr>
									<td colspan='8' style='text-align: center;'>{{$key}}</td>
								</tr>
								@foreach($value as $contract)
									<tr>
										<td>{{$k++}}</td>
										<td>{{$contract->number_contract}}</td>
										<td>{{$contract->number_counterpartie_contract_reestr}}</td>
										<td>{{$contract->counterpartie_name}}</td>
										<td>{{$contract->item_contract}}</td>
										<td>{{$contract->date_registration_project_reestr}}</td>
										<td>{{$contract->date_entry_into_force_reestr}}</td>
										<td>{{$contract->date_save_contract_reestr}}</td>
									</tr>
								@endforeach
							@endforeach
						@endif
					</tbody>
				</table>
				<div class='row'>
					<div class="col-md-8 col-md-offset-2">
						@if(isset($result))
							Всего зарегистрировано за период: {{$count_contracts}}
						@endif
					</div>
				</div>
				<div class='row'>
					<div class="col-md-8 col-md-offset-2">
						Начальник отдела управления договорами<span style='float: right;'>{{$lider}}</span>
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
