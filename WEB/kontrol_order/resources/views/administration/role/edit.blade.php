@extends('layouts.header')

@section('title')
	Редактирование пользователя
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Пользователь</h3>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<form class="form-horizontal" method="POST" action="{{ route('user.update', $user->id) }}">
								{{ csrf_field() }}
								
								<div class="form-group{{ $errors->has('surname') ? ' has-error' : '' }}">
									<label for="surname" class="col-md-4 control-label">Фамилия</label>

									<div class="col-md-6">
										<input id="surname" type="text" class="form-control" name="surname" value="{{ old('surname') ? old('surname') : $user->surname }}" required>

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
										<input id="name" type="text" class="form-control" name="name" value="{{ old('name') ? old('name') : $user->name }}" required>

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
										<input id="patronymic" type="text" class="form-control" name="patronymic" value="{{ old('patronymic') ? old('patronymic') : $user->patronymic }}" required>

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
														<option value='{{$role->id}}' selected>{{$role->role}}</option>
													@else
														<option value='{{$role->id}}'>{{$role->role}}</option>
													@endif
												@endforeach
											@else
												@foreach($roles as $role)
													@if($user->id_role == $role->id)
														<option value='{{$role->id}}' selected>{{$role->role}}</option>
													@else
														<option value='{{$role->id}}'>{{$role->role}}</option>
													@endif
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
								
								<div class="form-group{{ $errors->has('login') ? ' has-error' : '' }}">
									<label for="login" class="col-md-4 control-label">Логин</label>

									<div class="col-md-6">
										<input id="login" type="login" class="form-control" name="login" value="{{ old('login') ? old('login') : $user->login }}" required>

										@if ($errors->has('login'))
											<span class="help-block">
												<strong>{{ $errors->first('login') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
									<label class='form-check-label col-md-4 control-label' for='changePassword'>Изменить пароль</label>
									<div class="col-md-6">
										<input id='changePassword' class='form-check-input' type="checkbox" name='change_password'/>
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
									<label for="password" class="col-md-4 control-label">Пароль</label>

									<div class="col-md-6">
										<input id="password" type="password" class="form-control" name="password">

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
