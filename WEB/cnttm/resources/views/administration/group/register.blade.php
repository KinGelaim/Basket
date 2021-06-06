@extends('layouts.header')

@section('title')
	Новая группа
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Преподаватель')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Новая группа</h3>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<form class="form-horizontal" method="POST" action="{{ route('group.save') }}">
								{{ csrf_field() }}
								
								<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
									<label for="name" class="col-md-4 control-label">Название</label>

									<div class="col-md-6">
										<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>

										@if ($errors->has('name'))
											<span class="help-block">
												<strong>{{ $errors->first('name') }}</strong>
											</span>
										@endif
									</div>
								</div>
								

								<div class="form-group{{ $errors->has('id_laba') ? ' has-error' : '' }}">
									<label for="id_laba" class="col-md-4 control-label">Лаборатория</label>

									<div class="col-md-6">
										<select id="id_laba" type="text" class="form-control" name="id_laba" required>
											<option></option>
											@if(old('id_laba'))
												@foreach($laboratories as $laboratory)
													@if(old('id_laba') == $laboratory->id)
														<option value='{{$laboratory->id}}'>{{$laboratory->name}}</option>
													@else
														<option value='{{$laboratory->id}}' selected>{{$laboratory->name}}</option>
													@endif
												@endforeach
											@else
												@foreach($laboratories as $laboratory)
													<option value='{{$laboratory->id}}'>{{$laboratory->name}}</option>
												@endforeach
											@endif
										</select>
										@if ($errors->has('id_laba'))
											<span class="help-block">
												<strong>{{ $errors->first('id_laba') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											Добавить группу
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
