@extends('layouts.header')

@section('title')
	@if($department)
		Редактирование подразделения
	@else
		Новое подразделение
	@endif
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							@if($department)
								<h3>Редактирование подразделения</h3>
							@else
								<h3>Новое подразделение</h3>
							@endif
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							@if($department)
								<form class="form-horizontal" method="POST" action="{{ route('departments.update', $department->id) }}">
									{{ csrf_field() }}
									
									<div class="form-group{{ $errors->has('index_department') ? ' has-error' : '' }}">
										<label for="index_department" class="col-md-4 control-label">Индекс подразделения</label>

										<div class="col-md-3">
											<input id="index_department" type="text" class="form-control" name="index_department" value="{{ old('index_department') ? old('index_department') : $department->index_department }}" required>

											@if ($errors->has('index_department'))
												<span class="help-block">
													<strong>{{ $errors->first('index_department') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('name_department') ? ' has-error' : '' }}">
										<label for="name_department" class="col-md-4 control-label">Название подразделения</label>

										<div class="col-md-3">
											<input id="name_department" type="text" class="form-control" name="name_department" value="{{ old('name_department') ? old('name_department') : $department->name_department }}" required>

											@if ($errors->has('name_department'))
												<span class="help-block">
													<strong>{{ $errors->first('name_department') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('lider_department') ? ' has-error' : '' }}">
										<label for="lider_department" class="col-md-4 control-label">Руководитель подразделения</label>
										<div class="col-md-3">
											<select class='form-control' name='lider_department'>
												<option></option>
												@if(old('lider_department'))
													@foreach($users as $user)
														@if(old('lider_department') == $user->id)
															<option value='{{$user->id}}' selected>{{$user->surname . ' ' . $user->name . ' ' . $user->patronymic}}</option>
														@else
															<option value='{{$user->id}}'>{{$user->surname . ' ' . $user->name . ' ' . $user->patronymic}}</option>
														@endif
													@endforeach
												@else
													@foreach($users as $user)
														@if($department->lider_department == $user->id)
															<option value='{{$user->id}}' selected>{{$user->surname . ' ' . $user->name . ' ' . $user->patronymic}}</option>
														@else
															<option value='{{$user->id}}'>{{$user->surname . ' ' . $user->name . ' ' . $user->patronymic}}</option>
														@endif
													@endforeach
												@endif
											</select>

											@if ($errors->has('lider_department'))
												<span class="help-block">
													<strong>{{ $errors->first('lider_department') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Изменить подразделение
											</button>
										</div>
									</div>
								</form>
							@else
								<form class="form-horizontal" method="POST" action="{{ route('departments.save') }}">
									{{ csrf_field() }}
									
									<div class="form-group{{ $errors->has('index_department') ? ' has-error' : '' }}">
										<label for="index_department" class="col-md-4 control-label">Индекс подразделения</label>

										<div class="col-md-3">
											<input id="index_department" type="text" class="form-control" name="index_department" value="{{ old('index_department') }}" required>

											@if ($errors->has('index_department'))
												<span class="help-block">
													<strong>{{ $errors->first('index_department') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('name_department') ? ' has-error' : '' }}">
										<label for="name_department" class="col-md-4 control-label">Название подразделения</label>

										<div class="col-md-3">
											<input id="name_department" type="text" class="form-control" name="name_department" value="{{ old('name_department') }}" required>

											@if ($errors->has('name_department'))
												<span class="help-block">
													<strong>{{ $errors->first('name_department') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('lider_department') ? ' has-error' : '' }}">
										<label for="lider_department" class="col-md-4 control-label">Руководитель подразделения</label>
										<div class="col-md-3">
											<select class='form-control' name='lider_department'>
												<option></option>
												@if(old('lider_department'))
													@foreach($users as $user)
														@if(old('lider_department') == $user->id)
															<option value='{{$user->id}}' selected>{{$user->surname . ' ' . $user->name . ' ' . $user->patronymic}}</option>
														@else
															<option value='{{$user->id}}'>{{$user->surname . ' ' . $user->name . ' ' . $user->patronymic}}</option>
														@endif
													@endforeach
												@else
													@foreach($users as $user)
														<option value='{{$user->id}}'>{{$user->surname . ' ' . $user->name . ' ' . $user->patronymic}}</option>
													@endforeach
												@endif
											</select>

											@if ($errors->has('lider_department'))
												<span class="help-block">
													<strong>{{ $errors->first('lider_department') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Добавить подразделение
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
