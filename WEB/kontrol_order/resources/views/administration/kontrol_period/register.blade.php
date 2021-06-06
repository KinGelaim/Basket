@extends('layouts.header')

@section('title')
	Новый период контроля
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Канцелярия')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Новый период контроля</h3>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<form class="form-horizontal" method="POST" action="{{ route('kontrol_period.save') }}">
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
								
								<div class="form-group{{ $errors->has('count_day') ? ' has-error' : '' }}">
									<label for="count_day" class="col-md-4 control-label">Количество дней</label>

									<div class="col-md-6">
										<input id="count_day" type="text" class="form-control" name="count_day" value="{{ old('count_day') }}" required>

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
											Добавить новый период контроля
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
