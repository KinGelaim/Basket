@extends('layouts.header')

@section('title')
	@if($element)
		Редактирование изделия
	@else
		Новое изделие
	@endif
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Второй отдел')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							@if($element)
								<h3>Редактирование изделия</h3>
							@else
								<h3>Новое изделие</h3>
							@endif
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							@if($element)
								<form class="form-horizontal" method="POST" action="{{ route('element.update', $element->id) }}">
									{{ csrf_field() }}
									
									<div class="form-group{{ $errors->has('name_element') ? ' has-error' : '' }}">
										<label for="name_element" class="col-md-4 control-label">Наименование изделия</label>

										<div class="col-md-6">
											<input id="name_element" type="text" class="form-control" name="name_element" value="{{ old('name_element') ? old('name_element') : $element->name_element }}" required>

											@if ($errors->has('name_element'))
												<span class="help-block">
													<strong>{{ $errors->first('name_element') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Изменить изделие
											</button>
										</div>
									</div>
								</form>
							@else
								<form class="form-horizontal" method="POST" action="{{ route('element.save') }}">
									{{ csrf_field() }}
									
									<div class="form-group{{ $errors->has('name_element') ? ' has-error' : '' }}">
										<label for="name_element" class="col-md-4 control-label">Наименование изделия</label>

										<div class="col-md-3">
											<input id="name_element" type="text" class="form-control" name="name_element" value="{{ old('name_element') }}" required>

											@if ($errors->has('name_element'))
												<span class="help-block">
													<strong>{{ $errors->first('name_element') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Добавить изделие
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
