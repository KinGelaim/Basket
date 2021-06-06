@extends('layouts.header')

@section('title')
	Новый пользователь
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Преподаватель')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Новый пользователь</h3>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<form class="form-horizontal" method="POST" action="{{ route('user.save') }}">
								{{ csrf_field() }}
								
								<div class="form-group{{ $errors->has('surname') ? ' has-error' : '' }}">
									<label for="surname" class="col-md-4 control-label">Фамилия</label>

									<div class="col-md-6">
										<input id="surname" type="text" class="form-control" name="surname" value="{{ old('surname') }}" required autofocus>

										@if ($errors->has('surname'))
											<span class="help-block">
												<strong>{{ $errors->first('surname') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
									<label for="name" class="col-md-4 control-label">Имя</label>

									<div class="col-md-6">
										<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>

										@if ($errors->has('name'))
											<span class="help-block">
												<strong>{{ $errors->first('name') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('patronymic') ? ' has-error' : '' }}">
									<label for="patronymic" class="col-md-4 control-label">Отчество</label>

									<div class="col-md-6">
										<input id="patronymic" type="text" class="form-control" name="patronymic" value="{{ old('patronymic') }}" required>

										@if ($errors->has('patronymic'))
											<span class="help-block">
												<strong>{{ $errors->first('patronymic') }}</strong>
											</span>
										@endif
									</div>
								</div>

								<div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
									<label for="role" class="col-md-4 control-label">Доступ</label>

									<div class="col-md-6">
										<select id="role" type="text" class="form-control" name="role" required>
											<option></option>
											@if(old('role'))
												@foreach($roles as $role)
													@if(old('role') == $role->id)
														<option value='{{$role->id}}'>{{$role->role}}</option>
													@else
														<option value='{{$role->id}}' selected>{{$role->role}}</option>
													@endif
												@endforeach
											@else
												@foreach($roles as $role)
													<option value='{{$role->id}}'>{{$role->role}}</option>
												@endforeach
											@endif
										</select>
										@if ($errors->has('role'))
											<span class="help-block">
												<strong>{{ $errors->first('role') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('position_department') ? ' has-error' : '' }}">
									<label for="position_department" class="col-md-4 control-label">Должность</label>

									<div class="col-md-6">
										<input id="position_department" type="position_department" class="form-control" name="position_department" value="{{ old('position_department') }}">

										@if ($errors->has('position_department'))
											<span class="help-block">
												<strong>{{ $errors->first('position_department') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('telephone') ? ' has-error' : '' }}">
									<label for="telephone" class="col-md-4 control-label">Телефон</label>

									<div class="col-md-6">
										<input id="telephone" type="telephone" class="form-control" name="telephone" value="{{ old('telephone') }}">

										@if ($errors->has('telephone'))
											<span class="help-block">
												<strong>{{ $errors->first('telephone') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('login') ? ' has-error' : '' }}">
									<label for="login" class="col-md-4 control-label">Логин</label>

									<div class="col-md-6">
										<input id="login" type="login" class="form-control" name="login" value="{{ old('login') }}" required>

										@if ($errors->has('login'))
											<span class="help-block">
												<strong>{{ $errors->first('login') }}</strong>
											</span>
										@endif
									</div>
								</div>

								<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
									<label for="password" class="col-md-4 control-label">Пароль</label>

									<div class="col-md-6">
										<input id="password" type="password" class="form-control" name="password" required>

										@if ($errors->has('password'))
											<span class="help-block">
												<strong>{{ $errors->first('password') }}</strong>
											</span>
										@endif
									</div>
								</div>

								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											Добавить пользователя
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
