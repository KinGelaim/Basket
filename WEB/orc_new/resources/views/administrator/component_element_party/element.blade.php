@extends('layouts.header')

@section('title')
	@if($element)
		Редактирование комплектующего на складе
	@else
		Новое комплектующее на складе
	@endif
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Десятый отдел')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							@if($element)
								<h3>Редактирование комплектующего на складе</h3>
							@else
								<h3>Новое комплектующее на складе</h3>
							@endif
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							@if($element)
								<form class="form-horizontal" method="POST" action="{{ route('ten.party_element_update', $element->id) }}">
									{{ csrf_field() }}
									<div class="form-group{{ $errors->has('name_component') ? ' has-error' : '' }}">
										<label for="name_component" class="col-md-4 control-label">Наименование элемента</label>
										<div class="col-md-5">
											<select class="form-control" name='name_component' required>
												<option value=""></option>
												@if($elements)
													@foreach($elements as $in_elements)
														@if($in_elements->id == $element->id_element)
															<option value='{{$in_elements->id}}' selected>{{ $in_elements->name_component }}</option>
														@else
															<option value='{{$in_elements->id}}'>{{ $in_elements->name_component }}</option>
														@endif
													@endforeach
												@endif
											</select>
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('name_party') ? ' has-error' : '' }}">
										<label for="name_party" class="col-md-4 control-label">Идентификатор партии</label>
										<div class="col-md-5">
											<input id="name_party" type="text" class="form-control" name="name_party" value="{{ old('name_party') ? old('name_party') : $element->name_party }}" required>

											@if ($errors->has('name_party'))
												<span class="help-block">
													<strong>{{ $errors->first('name_party') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('date_party') ? ' has-error' : '' }}">
										<label for="date_party" class="col-md-4 control-label">Дата партии</label>

										<div class="col-md-5">
											<input id="date_party" type="text" class="form-control datepicker" name="date_party" value="{{ old('date_party') ? old('date_party') : $element->date_party }}" required>

											@if ($errors->has('date_party'))
												<span class="help-block">
													<strong>{{ $errors->first('date_party') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('count_party') ? ' has-error' : '' }}">
										<label for="count_party" class="col-md-4 control-label">Количество элементов</label>

										<div class="col-md-5">
											<input id="count_party" type="text" class="form-control" name="count_party" value="{{ old('count_party') ? old('count_party') : $element->count_party }}" required>

											@if ($errors->has('count_party'))
												<span class="help-block">
													<strong>{{ $errors->first('count_party') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Изменить комплектующий элемент на складе
											</button>
										</div>
									</div>
								</form>
							@else
								<form class="form-horizontal" method="POST" action="{{ route('ten.party_element_save') }}">
									{{ csrf_field() }}
									
									<div class="form-group{{ $errors->has('name_component') ? ' has-error' : '' }}">
										<label for="name_component" class="col-md-4 control-label">Наименование элемента</label>
										<div class="col-md-5">
											<select class="form-control" name='name_component' required>
												<option value=""></option>
												@if($elements)
													@foreach($elements as $element)
														<option value='{{$element->id}}'>{{ $element->name_component }}</option>
													@endforeach
												@endif
											</select>
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('name_party') ? ' has-error' : '' }}">
										<label for="name_party" class="col-md-4 control-label">Идентификатор партии</label>
										<div class="col-md-5">
											<input id="name_party" type="text" class="form-control" name="name_party" value="{{ old('name_party') }}" required>

											@if ($errors->has('name_party'))
												<span class="help-block">
													<strong>{{ $errors->first('name_party') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('date_party') ? ' has-error' : '' }}">
										<label for="date_party" class="col-md-4 control-label">Дата партии</label>

										<div class="col-md-5">
											<input id="date_party" type="text" class="form-control datepicker" name="date_party" value="{{ old('date_party') }}" required>

											@if ($errors->has('date_party'))
												<span class="help-block">
													<strong>{{ $errors->first('date_party') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('count_party') ? ' has-error' : '' }}">
										<label for="count_party" class="col-md-4 control-label">Количество элементов</label>

										<div class="col-md-5">
											<input id="count_party" type="text" class="form-control" name="count_party" value="{{ old('count_party') }}" required>

											@if ($errors->has('count_party'))
												<span class="help-block">
													<strong>{{ $errors->first('count_party') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Добавить комплектующий элемент на склад
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
