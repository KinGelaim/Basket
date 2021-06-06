@extends('layouts.header')

@section('title')
	Новый тип документа
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Канцелярия')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Новый тип документа</h3>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<form class="form-horizontal" method="POST" action="{{ route('type_document.save') }}">
								{{ csrf_field() }}
								
								<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
									<label for="name" class="col-md-4 control-label">Наименование</label>

									<div class="col-md-6">
										<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>

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
												@foreach($counterparties as $couterparties)
													@if(old('id_counterpartie') == $couterparties->id)
														<option value='{{$couterparties->id}}'>{{$couterparties->name}}</option>
													@else
														<option value='{{$couterparties->id}}' selected>{{$couterparties->name}}</option>
													@endif
												@endforeach
											@else
												@foreach($counterparties as $couterparties)
													<option value='{{$couterparties->id}}'>{{$couterparties->name}}</option>
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
											Добавить тип документа
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
