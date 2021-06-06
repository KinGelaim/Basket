@extends('layouts.header')

@section('title')
	Редактирование периода контроля
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Канцелярия')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Период контроля</h3>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<form class="form-horizontal" method="POST" action="{{ route('kontrol_period.update', $kontrol_period->id) }}">
								{{ csrf_field() }}
								
								<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
									<label for="name" class="col-md-4 control-label">Имя</label>

									<div class="col-md-6">
										<input id="name" type="text" class="form-control" name="name" value="{{ old('name') ? old('name') : $kontrol_period->name }}" required>

										@if ($errors->has('name'))
											<span class="help-block">
												<strong>{{ $errors->first('name') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('count_day') ? ' has-error' : '' }}">
									<label for="count_day" class="col-md-4 control-label">Отчество</label>

									<div class="col-md-6">
										<input id="count_day" type="text" class="form-control" name="count_day" value="{{ old('count_day') ? old('count_day') : $kontrol_period->count_day }}" required>

										@if ($errors->has('count_day'))
											<span class="help-block">
												<strong>{{ $errors->first('count_day') }}</strong>
											</span>
										@endif
									</div>
								</div>

								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											Изменить период контроля
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
