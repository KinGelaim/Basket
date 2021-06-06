@extends('layouts.header')

@section('title')
	Редактирование теста
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Преподаватель')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Тест</h3>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<form class="form-horizontal" method="POST" action="{{ route('test.update', $test->id) }}">
								{{ csrf_field() }}
														
								<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
									<label for="name" class="col-md-4 control-label">Название</label>

									<div class="col-md-6">
										<input id="name" type="text" class="form-control" name="name" value="{{ old('name') ? old('name') : $test->name }}" required>

										@if ($errors->has('name'))
											<span class="help-block">
												<strong>{{ $errors->first('name') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('name_first_part') ? ' has-error' : '' }}">
									<label for="name_first_part" class="col-md-4 control-label">Название первой части</label>

									<div class="col-md-6">
										<input id="name_first_part" type="text" class="form-control" name="name_first_part" value="{{ old('name_first_part') ? old('name_first_part') : $test->name_first_part }}" required>

										@if ($errors->has('name_first_part'))
											<span class="help-block">
												<strong>{{ $errors->first('name_first_part') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('name_second_part') ? ' has-error' : '' }}">
									<label for="name_second_part" class="col-md-4 control-label">Название второй части</label>

									<div class="col-md-6">
										<input id="name_second_part" type="text" class="form-control" name="name_second_part" value="{{ old('name_second_part') ? old('name_second_part') : $test->name_second_part }}" required>

										@if ($errors->has('name_second_part'))
											<span class="help-block">
												<strong>{{ $errors->first('name_second_part') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('name_third_part') ? ' has-error' : '' }}">
									<label for="name_third_part" class="col-md-4 control-label">Название третей части</label>

									<div class="col-md-6">
										<input id="name_third_part" type="text" class="form-control" name="name_third_part" value="{{ old('name_third_part') ? old('name_third_part') : $test->name_third_part }}" required>

										@if ($errors->has('name_third_part'))
											<span class="help-block">
												<strong>{{ $errors->first('name_third_part') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('name_fourth_part') ? ' has-error' : '' }}">
									<label for="name_fourth_part" class="col-md-4 control-label">Название четвертой части</label>

									<div class="col-md-6">
										<input id="name_fourth_part" type="text" class="form-control" name="name_fourth_part" value="{{ old('name_fourth_part') ? old('name_fourth_part') : $test->name_fourth_part }}" required>

										@if ($errors->has('name_fourth_part'))
											<span class="help-block">
												<strong>{{ $errors->first('name_fourth_part') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											Изменить тест
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
