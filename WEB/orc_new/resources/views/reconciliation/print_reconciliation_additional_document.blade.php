@extends('layouts.header')

@section('title')
	Печать листа согласования
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="container">
				<div class='row'>
					<div class="col-md-2">
						<button class='btn btn-primary' id='createExcel' real_name_table='Лист согласования по контракту {{$number_contract->number_contract}}'>Отправить в Excel</button>
					</div>
					<div class="col-md-4">
						<input id='comment_checkbox' class='form-check-input' type='checkbox' onclick="if($(this).prop('checked') == true) $('.comment').css('display', 'table-cell'); else $('.comment').css('display', 'none');"/>
						<label for='comment_checkbox'>Добавить комментарии</label>
					</div>
				</div>
				<div class='row' style='text-align: center;'>
					<div class="col-md-12">
						@if($addional_document->is_protocol)
							Лист согласования по протоколу для контракта {{$number_contract->number_contract}}
						@else
							Лист согласования по доп. соглашению для контракта {{$number_contract->number_contract}}
						@endif
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
							<th>ФИО</th>
							<th>Дата отправки на согласование</th>
							<th>Дата согласования</th>
							<th class='comment' style='display: none;'>Комментарии</th>
						</tr>
					</thead>
					<tbody>
						@foreach($directed_list as $directed)
							<tr>
								<td>{{$directed->surname . ' ' . $directed->name . ' ' . $directed->patronymic}}</td>
								<td>{{$directed->date_outgoing}}</td>
								<td>{{$directed->date_check_agree_reconciliation}}</td>
								<td class='comment' style='display: none;'>
									@if(count($directed->comments) > 0)
										@foreach($directed->comments as $comment)
											{{$comment->message}}<br/>
										@endforeach
									@endif
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		@else
			<div class="alert alert-danger">
				Для просмотра данной страницы необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection
