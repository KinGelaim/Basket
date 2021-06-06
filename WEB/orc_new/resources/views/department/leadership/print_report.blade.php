@extends('layouts.header')

@section('title')
	Печать
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if(Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Администрация' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел')
				<div class="content">
					@if($result)
						<div class='row'>
							<div class="col-md-12">
								<button class='btn btn-primary' id='createExcel' real_name_table='Отчет по договорам'>Сформировать Excel</button>
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
									@foreach($headers as $select)
										@foreach($select as $key=>$value)
											<th>{{$key}}</th>
										@endforeach
									@endforeach
								</tr>
							</thead>
							<tbody>
								@foreach($result as $res)
									<tr>
									@foreach($headers as $select)
										@foreach($select as $key=>$value)
											@if($value == 'comment_implementation_contract')
												<th>
													<?php 
														if($res->$value === null)
															//echo 'Выполнен';
															echo '';
														else
															echo $res->$value;
													?>
												</th>
											@else
												<th>{{$res->$value}}</th>
											@endif
										@endforeach
									@endforeach
									</tr>
								@endforeach
							</tbody>
						</table>
					@endif
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
