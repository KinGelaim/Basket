@extends('layouts.header')

@section('title')
	Список файлов хранилища
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class='row'>
				<div class='col-md-8 col-md-offset-2'>
					<table class="table" style='margin: 0 auto;'>
						<thead>
							<tr>
								<th>Название файла</th>
								<th>Дата формирования</th>
							</tr>
						</thead>
						<tbody>
							@foreach($files as $file)
								@if($file != '.' && $file != '..')
									<tr class='rowsContract cursorPointer btn-href' href='{{$path.$file}}'>
										<td>{{$file}}</td>
										<td></td>
									</tr>
								@endif
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
			<div class='row'>
				<div class="col-md-12" style="text-align: center;">
					@include('layouts.paginate')
				</div>
			</div>
		@else
			<div class="alert alert-danger">
				Необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection
