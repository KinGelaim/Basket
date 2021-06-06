@extends('layouts.header')

@section('title')
	Новый ученик
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Преподаватель')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Новый ученик</h3>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<form class="form-horizontal" method="POST" action="{{ route('schoolchildren.save') }}">
								{{ csrf_field() }}
								
								<div class="form-group{{ $errors->has('id_user') ? ' has-error' : '' }}">
									<label for="id_user" class="col-md-4 control-label">Пользователь</label>

									<div class="col-md-6">
										<select id="id_user" type="text" class="form-control" name="id_user" required>
											<option></option>
											@if(old('id_user'))
												@foreach($users as $user)
													@if(old('id_user') == $user->id)
														<option value='{{$user->id}}'>{{$user->surname}} {{$user->name}} {{$user->patronymic}}</option>
													@else
														<option value='{{$user->id}}' selected>{{$user->surname}} {{$user->name}} {{$user->patronymic}}</option>
													@endif
												@endforeach
											@else
												@foreach($users as $user)
													<option value='{{$user->id}}'>{{$user->surname}} {{$user->name}} {{$user->patronymic}}</option>
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
								
								<div class="form-group{{ $errors->has('id_group') ? ' has-error' : '' }}">
									<label for="id_group" class="col-md-4 control-label">Группа</label>

									<div class="col-md-6">
										<select id="id_group" type="text" class="form-control" name="id_group" required>
											<option></option>
											@if(old('id_group'))
												@foreach($groups as $group)
													@if(old('id_group') == $group->id)
														<option value='{{$group->id}}'>{{$group->name}}</option>
													@else
														<option value='{{$group->id}}' selected>{{$group->name}}</option>
													@endif
												@endforeach
											@else
												@foreach($groups as $group)
													<option value='{{$group->id}}'>{{$group->name}}</option>
												@endforeach
											@endif
										</select>
										@if ($errors->has('id_group'))
											<span class="help-block">
												<strong>{{ $errors->first('id_group') }}</strong>
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
