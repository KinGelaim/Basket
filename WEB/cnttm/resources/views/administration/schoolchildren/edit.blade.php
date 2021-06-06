@extends('layouts.header')

@section('title')
	Редактирование ученика
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Преподаватель')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Ученик</h3>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<form class="form-horizontal" method="POST" action="{{ route('schoolchildren.update', $schoolchildren->id) }}">
								{{ csrf_field() }}
								
								<div class="form-group{{ $errors->has('id_user') ? ' has-error' : '' }}">
									<label for="id_user" class="col-md-4 control-label">Пользователь</label>

									<div class="col-md-6">
										<select id="id_user" type="text" class="form-control" name="id_user" required>
											<option></option>
											@if(old('id_user'))
												@foreach($users as $user)
													@if(old('id_user') == $user->id)
														<option value='{{$user->id}}' selected>{{$user->surname}}</option>
													@else
														<option value='{{$user->id}}'>{{$user->surname}}</option>
													@endif
												@endforeach
											@else
												@foreach($users as $user)
													@if($schoolchildren->id_user == $user->id)
														<option value='{{$user->id}}' selected>{{$user->surname}}</option>
													@else
														<option value='{{$user->id}}'>{{$user->surname}}</option>
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
								
								<div class="form-group{{ $errors->has('id_group') ? ' has-error' : '' }}">
									<label for="id_group" class="col-md-4 control-label">Группа</label>

									<div class="col-md-6">
										<select id="id_group" type="text" class="form-control" name="id_group" required>
											<option></option>
											@if(old('id_group'))
												@foreach($groups as $group)
													@if(old('id_group') == $group->id)
														<option value='{{$group->id}}' selected>{{$group->name}}</option>
													@else
														<option value='{{$group->id}}'>{{$group->name}}</option>
													@endif
												@endforeach
											@else
												@foreach($groups as $group)
													@if($schoolchildren->id_group == $group->id)
														<option value='{{$group->id}}' selected>{{$group->name}}</option>
													@else
														<option value='{{$group->id}}'>{{$group->name}}</option>
													@endif
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
								
								<div class="form-group{{ $errors->has('id_group') ? ' has-error' : '' }}">

									<div class="col-md-4 col-md-offset-4">
										<label for="is_complete" class="control-label">Учёба завершена</label>
										@if($schoolchildren->is_complete == 1)
											<input id='is_complete' type='checkbox' class='' name='is_complete' checked />
										@else
											<input id='is_complete' type='checkbox' class='' name='is_complete' />
										@endif
										@if ($errors->has('is_complete'))
											<span class="help-block">
												<strong>{{ $errors->first('is_complete') }}</strong>
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
