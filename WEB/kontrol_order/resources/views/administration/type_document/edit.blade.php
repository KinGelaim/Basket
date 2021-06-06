@extends('layouts.header')

@section('title')
	Редактирование типа документа
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Канцелярия')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Тип документа</h3>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<form class="form-horizontal" method="POST" action="{{ route('type_document.update', $type_document->id) }}">
								{{ csrf_field() }}
								
								<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
									<label for="name" class="col-md-4 control-label">Наименование</label>

									<div class="col-md-6">
										<input id="name" type="text" class="form-control" name="name" value="{{ old('name') ? old('name') : $type_document->name }}" required>

										@if ($errors->has('name'))
											<span class="help-block">
												<strong>{{ $errors->first('name') }}</strong>
											</span>
										@endif
									</div>
								</div>

								<div class="form-group{{ $errors->has('id_counterpartie') ? ' has-error' : '' }}">
									<label for="id_counterpartie" class="col-md-4 control-label">Организация</label>

									<div class="col-md-6">
										<select id="id_counterpartie" type="text" class="form-control" name="id_counterpartie" required>
											<option></option>
											@if(old('id_counterpartie'))
												@foreach($counterparties as $counterpartie)
													@if(old('id_counterpartie') == $counterpartie->id)
														<option value='{{$counterpartie->id}}' selected>{{$counterpartie->name}}</option>
													@else
														<option value='{{$counterpartie->id}}'>{{$counterpartie->name}}</option>
													@endif
												@endforeach
											@else
												@foreach($counterparties as $counterpartie)
													@if($type_document->id_counterpartie == $counterpartie->id)
														<option value='{{$counterpartie->id}}' selected>{{$counterpartie->name}}</option>
													@else
														<option value='{{$counterpartie->id}}'>{{$counterpartie->name}}</option>
													@endif
												@endforeach
											@endif
										</select>
										@if ($errors->has('id_counterpartie'))
											<span class="help-block">
												<strong>{{ $errors->first('id_counterpartie') }}</strong>
											</span>
										@endif
									</div>
								</div>

								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											Изменить тип документа
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
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
