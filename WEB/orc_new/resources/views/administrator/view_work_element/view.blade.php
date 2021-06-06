@extends('layouts.header')

@section('title')
	@if($view)
		Редактирование вида испытания
	@else
		Новый вид испытания
	@endif
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Второй отдел')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							@if($view)
								<h3>Редактирование вида испытания</h3>
							@else
								<h3>Новый вид испытания</h3>
							@endif
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							@if($view)
								<form class="form-horizontal" method="POST" action="{{ route('view_work.element.update', $view->id) }}">
									{{ csrf_field() }}
									
									<div class="form-group{{ $errors->has('name_view_work_elements') ? ' has-error' : '' }}">
										<label for="name_view_work_elements" class="col-md-4 control-label">Наименование вида испытания</label>

										<div class="col-md-3">
											<input id="name_view_work_elements" type="text" class="form-control" name="name_view_work_elements" value="{{ old('name_view_work_elements') ? old('name_view_work_elements') : $view->name_view_work_elements }}" required>

											@if ($errors->has('name_view_work_elements'))
												<span class="help-block">
													<strong>{{ $errors->first('name_view_work_elements') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Изменить вид испытания
											</button>
										</div>
									</div>
								</form>
							@else
								<form class="form-horizontal" method="POST" action="{{ route('view_work.element.save') }}">
									{{ csrf_field() }}
									
									<div class="form-group{{ $errors->has('name_view_work_elements') ? ' has-error' : '' }}">
										<label for="name_view_work_elements" class="col-md-4 control-label">Наименование вида испытания</label>

										<div class="col-md-3">
											<input id="name_view_work_elements" type="text" class="form-control" name="name_view_work_elements" value="{{ old('name_view_work_elements') }}" required>

											@if ($errors->has('name_view_work_elements'))
												<span class="help-block">
													<strong>{{ $errors->first('name_view_work_elements') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Добавить вид испытания
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
