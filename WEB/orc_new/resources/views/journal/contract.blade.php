@extends('layouts.header')

@section('title')
	Журнал Д/К
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class='row'>
				<div class='col-md-8 col-md-offset-2'>
					<table class="table" style='margin: 0 auto;'>
						<thead>
							<tr>
								<th>Подразделение</th>
								<th>Пользователь</th>
								<th>Действие</th>
								<th>Дата</th>
							</tr>
						</thead>
						<tbody>
							<?php $count_rows = 1; ?>
							@foreach($journals as $journal)
								@if(!isset($journal->comment))
									<tr class='rowsContract'>
										<td>{{$journal->role}}</td>
										<td>{{$journal->surname . ' ' . $journal->name . ' ' . $journal->patronymic}}</td>
										<td>{{$journal->message}}</td>
										<td>{{$journal->created_at}}</td>
									</tr>
								@else
									<tr class='rowsContract cursorPointer' data-toggle='collapse' data-target='#group-of-rows-{{$count_rows}}' aria-expanded='false' aria-controls='group-of-rows-{{$count_rows}}'>
										<td>{{$journal->role}}</td>
										<td>{{$journal->surname . ' ' . $journal->name . ' ' . $journal->patronymic}}</td>
										<td>{{$journal->message}}</td>
										<td>{{$journal->created_at}}</td>
									</tr>
									<tr id='group-of-rows-{{$count_rows}}' class='collapse' style='background-color: #E7E7E7;'>
										<td colspan='4'>
											<p>
												@foreach($journal->comment as $key=>$value)
													<b>{{$key}}:</b>{{$value}}<br/>
												@endforeach
											</p>
										</td>
									</tr>
								@endif
								<?php $count_rows++; ?>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		@else
			<div class="alert alert-danger">
				Необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection
