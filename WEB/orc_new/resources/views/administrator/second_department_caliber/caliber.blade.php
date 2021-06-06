@extends('layouts.header')

@section('title')
	@if($caliber)
		Редактирование типа для второго отдела
	@else
		Новый тип для второго отдела
	@endif
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Второй отдел')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							@if($caliber)
								<h3>Редактирование типа для второго отдела</h3>
							@else
								<h3>Новый тип для второго отдела</h3>
							@endif
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							@if($caliber)
								<form class="form-horizontal" method="POST" action="{{ route('second_department_caliber.update', $caliber->id) }}">
									{{ csrf_field() }}
									
									<div class="form-group{{ $errors->has('name_caliber') ? ' has-error' : '' }}">
										<label for="name_caliber" class="col-md-4 control-label">Наименование типа</label>

										<div class="col-md-3">
											<input id="name_caliber" type="text" class="form-control" name="name_caliber" value="{{ old('name_caliber') ? old('name_caliber') : $caliber->name_caliber }}" required>

											@if ($errors->has('name_caliber'))
												<span class="help-block">
													<strong>{{ $errors->first('name_caliber') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Изменить тип
											</button>
										</div>
									</div>
								</form>
							@else
								<form class="form-horizontal" method="POST" action="{{ route('second_department_caliber.save') }}">
									{{ csrf_field() }}
									
									<div class="form-group{{ $errors->has('name_caliber') ? ' has-error' : '' }}">
										<label for="name_caliber" class="col-md-4 control-label">Наименование типа</label>

										<div class="col-md-3">
											<input id="name_caliber" type="text" class="form-control" name="name_caliber" value="{{ old('name_caliber') }}" required>

											@if ($errors->has('name_caliber'))
												<span class="help-block">
													<strong>{{ $errors->first('name_caliber') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Добавить тип
											</button>
										</div>
									</div>
								</form>
							@endif
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
