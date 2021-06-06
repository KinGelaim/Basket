@extends('layouts.header')

@section('title')
	Новый вопрос
@endsection

@section('content')
	<div class="flex-center position-ref full-height container">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Преподаватель')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<h3>Новый вопрос</h3>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<form class="form-horizontal" method="POST" action="{{ route('question.save', $id_test) }}">
								{{ csrf_field() }}
								
								<div class="form-group{{ $errors->has('part') ? ' has-error' : '' }}">
									<label for="part" class="col-md-4 control-label">Часть</label>

									<div class="col-md-6">
										<select id="part" type="text" class="form-control" name="part" required>
											<option>1</option>
											<option>2</option>
											<option>3</option>
											<option>4</option>
										</select>

										@if ($errors->has('part'))
											<span class="help-block">
												<strong>{{ $errors->first('part') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('position') ? ' has-error' : '' }}">
									<label for="position" class="col-md-4 control-label">Позиция в сортировки</label>

									<div class="col-md-6">
										<input id="position" type="text" class="form-control" name="position" value="{{ old('position') }}" required>

										@if ($errors->has('position'))
											<span class="help-block">
												<strong>{{ $errors->first('position') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('question') ? ' has-error' : '' }}">
									<label for="question" class="col-md-4 control-label">Вопрос</label>

									<div class="col-md-6">
										<input id="question" type="text" class="form-control" name="question" value="{{ old('question') }}" required>

										@if ($errors->has('question'))
											<span class="help-block">
												<strong>{{ $errors->first('question') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
									<label for="description" class="col-md-4 control-label">Дополнительный материал (text,img)</label>

									<div class="col-md-6">
										<textarea id="description" type="text" class="form-control" name="description" rows=3>{{ old('description') }}</textarea>

										@if ($errors->has('description'))
											<span class="help-block">
												<strong>{{ $errors->first('description') }}</strong>
											</span>
										@endif
									</div>
								</div>

								<div class="form-group{{ $errors->has('id_view') ? ' has-error' : '' }}">
									<label for="id_view" class="col-md-4 control-label">Внешний вид ответа</label>

									<div class="col-md-6">
										<select id="id_view" type="text" class="form-control" name="id_view" required>
											<option></option>
											@if(old('id_view'))
												@foreach($views as $view)
													@if(old('id_view') == $view->id)
														<option value='{{$view->id}}'>{{$view->real_name}}</option>
													@else
														<option value='{{$view->id}}' selected>{{$view->real_name}}</option>
													@endif
												@endforeach
											@else
												@foreach($views as $view)
													<option value='{{$view->id}}'>{{$view->real_name}}</option>
												@endforeach
											@endif
										</select>
										@if ($errors->has('id_view'))
											<span class="help-block">
												<strong>{{ $errors->first('id_view') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('first_answer') ? ' has-error' : '' }}">
									<label for="first_answer" class="col-md-4 control-label">Первый вариант ответа</label>

									<div class="col-md-6">
										<input id="first_answer" type="first_answer" class="form-control" name="first_answer" value="{{ old('first_answer') }}">

										@if ($errors->has('first_answer'))
											<span class="help-block">
												<strong>{{ $errors->first('first_answer') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('second_answer') ? ' has-error' : '' }}">
									<label for="second_answer" class="col-md-4 control-label">Второй вариант ответа</label>

									<div class="col-md-6">
										<input id="second_answer" type="second_answer" class="form-control" name="second_answer" value="{{ old('second_answer') }}">

										@if ($errors->has('second_answer'))
											<span class="help-block">
												<strong>{{ $errors->first('second_answer') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('third_answer') ? ' has-error' : '' }}">
									<label for="third_answer" class="col-md-4 control-label">Третий вариант ответа</label>

									<div class="col-md-6">
										<input id="third_answer" type="third_answer" class="form-control" name="third_answer" value="{{ old('third_answer') }}">

										@if ($errors->has('third_answer'))
											<span class="help-block">
												<strong>{{ $errors->first('third_answer') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('fourth_answer') ? ' has-error' : '' }}">
									<label for="fourth_answer" class="col-md-4 control-label">Четвертый вариант ответа</label>

									<div class="col-md-6">
										<input id="fourth_answer" type="fourth_answer" class="form-control" name="fourth_answer" value="{{ old('fourth_answer') }}">

										@if ($errors->has('fourth_answer'))
											<span class="help-block">
												<strong>{{ $errors->first('fourth_answer') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('finally_answer') ? ' has-error' : '' }}">
									<label for="finally_answer" class="col-md-4 control-label">Правильный ответа</label>

									<div class="col-md-6">
										<input id="finally_answer" type="finally_answer" class="form-control" name="finally_answer" value="{{ old('finally_answer') }}">

										@if ($errors->has('finally_answer'))
											<span class="help-block">
												<strong>{{ $errors->first('finally_answer') }}</strong>
											</span>
										@endif
									</div>
								</div>

								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											Добавить вопрос
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
