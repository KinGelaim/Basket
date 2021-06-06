@extends('layouts.header')

@section('title')
	Редактирование педагога
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Преподаватель')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Педагог</h3>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<form class="form-horizontal" method="POST" action="{{ route('educator.update', $educator->id) }}">
								{{ csrf_field() }}
								
								<div class="form-group{{ $errors->has('id_user') ? ' has-error' : '' }}">
									<label for="id_user" class="col-md-4 control-label">Пользователь</label>

									<div class="col-md-6">
										<select id="id_user" type="text" class="form-control" name="id_user" required>
											<option></option>
											@if(old('id_user'))
												@foreach($users as $user)
													@if(old('id_user') == $user->id)
														<option value='{{$user->id}}' selected>{{$user->surname}} {{$user->name}} {{$user->patronymic}}</option>
													@else
														<option value='{{$user->id}}'>{{$user->surname}} {{$user->name}} {{$user->patronymic}}</option>
													@endif
												@endforeach
											@else
												@foreach($users as $user)
													@if($educator->id_user == $user->id)
														<option value='{{$user->id}}' selected>{{$user->surname}} {{$user->name}} {{$user->patronymic}}</option>
													@else
														<option value='{{$user->id}}'>{{$user->surname}} {{$user->name}} {{$user->patronymic}}</option>
													@endif
												@endforeach
											@endif
										</select>
										@if ($errors->has('id_user'))
											<span class="help-block">
												<strong>{{ $errors->first('id_user') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('position') ? ' has-error' : '' }}">
									<label for="position" class="col-md-4 control-label">Позиция</label>

									<div class="col-md-6">
										<input id="position" type="text" class="form-control" name="position" value="{{ old('position') ? old('position') : $educator->position }}" required>

										@if ($errors->has('position'))
											<span class="help-block">
												<strong>{{ $errors->first('position') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('photo') ? ' has-error' : '' }}">
									<label for="photo" class="col-md-4 control-label">Фотография</label>

									<div class="col-md-6">
										<input id="photo" type="text" class="form-control" name="photo" value="{{ old('photo') ? old('photo') : $educator->photo }}" required>

										@if ($errors->has('photo'))
											<span class="help-block">
												<strong>{{ $errors->first('photo') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('short_information') ? ' has-error' : '' }}">
									<label for="short_information" class="col-md-4 control-label">Краткая информация</label>

									<div class="col-md-6">
										<input id="short_information" type="text" class="form-control" name="short_information" value="{{ old('short_information') ? old('short_information') : $educator->short_information }}" required>

										@if ($errors->has('short_information'))
											<span class="help-block">
												<strong>{{ $errors->first('short_information') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('full_information') ? ' has-error' : '' }}">
									<label for="full_information" class="col-md-4 control-label">Полная информация</label>

									<div class="col-md-6">
										<textarea id="full_information" type="text" class="form-control" name="full_information" rows=10 required >{{ old('full_information') ? old('full_information') : $educator->full_information }}</textarea>

										@if ($errors->has('full_information'))
											<span class="help-block">
												<strong>{{ $errors->first('full_information') }}</strong>
											</span>
										@endif
									</div>
								</div>

								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											Изменить пользователя
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
