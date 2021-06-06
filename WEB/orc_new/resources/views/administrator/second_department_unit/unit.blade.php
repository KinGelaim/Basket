@extends('layouts.header')

@section('title')
	@if($unit)
		Редактирование единицы измерения для второго отдела
	@else
		Новая единица измерения для второго отдела
	@endif
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Второй отдел')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							@if($unit)
								<h3>Редактирование единицы измерения для второго отдела</h3>
							@else
								<h3>Новая единица измерения для второго отдела</h3>
							@endif
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							@if($unit)
								<form class="form-horizontal" method="POST" action="{{ route('second_department_unit.update', $unit->id) }}">
									{{ csrf_field() }}
									
									<div class="form-group{{ $errors->has('name_unit') ? ' has-error' : '' }}">
										<label for="name_unit" class="col-md-4 control-label">Наименование единицы измерения</label>

										<div class="col-md-3">
											<input id="name_unit" type="text" class="form-control" name="name_unit" value="{{ old('name_unit') ? old('name_unit') : $unit->name_unit }}" required>

											@if ($errors->has('name_unit'))
												<span class="help-block">
													<strong>{{ $errors->first('name_unit') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Изменить единицу измерения
											</button>
										</div>
									</div>
								</form>
							@else
								<form class="form-horizontal" method="POST" action="{{ route('second_department_unit.save') }}">
									{{ csrf_field() }}
									
									<div class="form-group{{ $errors->has('name_unit') ? ' has-error' : '' }}">
										<label for="name_unit" class="col-md-4 control-label">Наименование единицы измерения</label>

										<div class="col-md-3">
											<input id="name_unit" type="text" class="form-control" name="name_unit" value="{{ old('name_unit') }}" required>

											@if ($errors->has('name_unit'))
												<span class="help-block">
													<strong>{{ $errors->first('name_unit') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Добавить единицу измерения
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
