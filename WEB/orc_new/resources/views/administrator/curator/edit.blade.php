@extends('layouts.header')

@section('title')
	Редактирование куратора
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Куратор</h3>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<form class="form-horizontal" method="POST" action="{{ route('curator.update', $curator->id) }}">
								{{ csrf_field() }}
								
								<div class="form-group{{ $errors->has('id_user') ? ' has-error' : '' }}">
									<label for="id_user" class="col-md-4 control-label">Пользователь</label>
									<div class="col-md-6">
										<select class='form-control' name='id_user' required>
											<option></option>
											@if(old('id_user'))
												@foreach($users as $user)
													@if(old('id_user') == $user->id)
														<option value='{{$user->id}}' selected>{{$user->surname . ' ' . $user->name . ' ' . $user->patronymic}}</option>
													@else
														<option value='{{$user->id}}'>{{$user->surname . ' ' . $user->name . ' ' . $user->patronymic}}</option>
													@endif
												@endforeach
											@else
												@foreach($users as $user)
													@if($curator->id_user == $user->id)
														<option value='{{$user->id}}' selected>{{$user->surname . ' ' . $user->name . ' ' . $user->patronymic}}</option>
													@else
														<option value='{{$user->id}}'>{{$user->surname . ' ' . $user->name . ' ' . $user->patronymic}}</option>
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
								
								<div class="form-group{{ $errors->has('FIO') ? ' has-error' : '' }}">
									<label for="FIO" class="col-md-4 control-label">ФИО куратора</label>

									<div class="col-md-6">
										<input id="FIO" type="text" class="form-control" name="FIO" value="{{ old('FIO') ? old('FIO') : $curator->FIO }}" required>

										@if ($errors->has('FIO'))
											<span class="help-block">
												<strong>{{ $errors->first('FIO') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('telephone') ? ' has-error' : '' }}">
									<label for="telephone" class="col-md-4 control-label">Телефон</label>

									<div class="col-md-6">
										<input id="telephone" type="text" class="form-control" name="telephone" value="{{ old('telephone') ? old('telephone') : $curator->telephone }}">

										@if ($errors->has('telephone'))
											<span class="help-block">
												<strong>{{ $errors->first('telephone') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('id_department') ? ' has-error' : '' }}">
									<label for="id_department" class="col-md-4 control-label">Подразделение</label>
									<div class="col-md-6">
										<select class='form-control' name='id_department'>
											<option></option>
											@if(old('id_department'))
												@foreach($departments as $department)
													@if(old('id_department') == $department->id)
														<option value='{{$department->id}}' selected>{{$department->name_department}}</option>
													@else
														<option value='{{$department->id}}'>{{$department->name_department}}</option>
													@endif
												@endforeach
											@else
												@foreach($departments as $department)
													@if($curator->id_department == $department->id)
														<option value='{{$department->id}}' selected>{{$department->name_department}}</option>
													@else
														<option value='{{$department->id}}'>{{$department->name_department}}</option>
													@endif
												@endforeach
											@endif
										</select>

										@if ($errors->has('id_department'))
											<span class="help-block">
												<strong>{{ $errors->first('id_department') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											Изменить куратора
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
