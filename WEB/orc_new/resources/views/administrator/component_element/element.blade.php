@extends('layouts.header')

@section('title')
	@if($element)
		Редактирование комплектующего
	@else
		Новое комплектующее
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
								<h3>Редактирование комплектующего</h3>
							@else
								<h3>Новое комплектующее</h3>
							@endif
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							@if($element)
								<form class="form-horizontal" method="POST" action="{{ route('ten.element_update', $element->id) }}">
									{{ csrf_field() }}
									
									<div class="form-group{{ $errors->has('id_counterpartie') ? ' has-error' : '' }}">
										<label for="id_counterpartie" class="col-md-4 control-label">Наименование поставщика</label>
										<div class="col-md-5">
											<select class="form-control" name='id_counterpartie' required>
												<option value=""></option>
												@if($counterparties)
													@foreach($counterparties as $in_counterparties)
														@if($in_counterparties->id == $element->id_counterpartie)
															<option value='{{$in_counterparties->id}}' selected>{{ $in_counterparties->name }}</option>
														@else
															<option value='{{$in_counterparties->id}}'>{{ $in_counterparties->name }}</option>
														@endif
													@endforeach
												@endif
											</select>
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('name_component') ? ' has-error' : '' }}">
										<label for="name_component" class="col-md-4 control-label">Наименование комплектующего</label>

										<div class="col-md-5">
											<input id="name_component" type="text" class="form-control" name="name_component" value="{{ old('name_component') ? old('name_component') : $element->name_component }}" required>

											@if ($errors->has('name_component'))
												<span class="help-block">
													<strong>{{ $errors->first('name_component') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<!--<div class="form-group{{ $errors->has('count_element') ? ' has-error' : '' }}">
										<label for="count_element" class="col-md-4 control-label">Количество элементов на складе</label>

										<div class="col-md-5">
											<input id="count_element" type="text" class="form-control" name="count_element" value="{{ old('count_element') ? old('count_element') : $element->count_element }}">

											@if ($errors->has('count_element'))
												<span class="help-block">
													<strong>{{ $errors->first('count_element') }}</strong>
												</span>
											@endif
										</div>
									</div>-->
									
									<div class="form-group{{ $errors->has('need_count_element') ? ' has-error' : '' }}">
										<label for="need_count_element" class="col-md-4 control-label">Необходимое количество для заказа с завода</label>

										<div class="col-md-5">
											<input id="need_count_element" type="text" class="form-control" name="need_count_element" value="{{ old('need_count_element') ? old('need_count_element') : $element->need_count_element }}">

											@if ($errors->has('need_count_element'))
												<span class="help-block">
													<strong>{{ $errors->first('need_count_element') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Изменить комплектующий
											</button>
										</div>
									</div>
								</form>
							@else
								<form class="form-horizontal" method="POST" action="{{ route('ten.element_save') }}">
									{{ csrf_field() }}
									
									<div class="form-group{{ $errors->has('id_counterpartie') ? ' has-error' : '' }}">
										<label for="id_counterpartie" class="col-md-4 control-label">Наименование поставщика</label>
										<div class="col-md-5">
											<select class="form-control" name='id_counterpartie' required>
												<option value=""></option>
												@if($counterparties)
													@foreach($counterparties as $in_counterparties)
														<option value='{{$in_counterparties->id}}'>{{ $in_counterparties->name }}</option>
													@endforeach
												@endif
											</select>
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('name_component') ? ' has-error' : '' }}">
										<label for="name_component" class="col-md-4 control-label">Наименование комплектующего</label>
										<div class="col-md-5">
											<input id="name_component" type="text" class="form-control" name="name_component" value="{{ old('name_component') }}" required>

											@if ($errors->has('name_component'))
												<span class="help-block">
													<strong>{{ $errors->first('name_component') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<!--<div class="form-group{{ $errors->has('count_element') ? ' has-error' : '' }}">
										<label for="count_element" class="col-md-4 control-label">Количество элементов на складе</label>

										<div class="col-md-5">
											<input id="count_element" type="text" class="form-control" name="count_element" value="{{ old('count_element') }}">

											@if ($errors->has('count_element'))
												<span class="help-block">
													<strong>{{ $errors->first('count_element') }}</strong>
												</span>
											@endif
										</div>
									</div>-->
									
									<div class="form-group{{ $errors->has('need_count_element') ? ' has-error' : '' }}">
										<label for="need_count_element" class="col-md-4 control-label">Необходимое количество для заказа с завода</label>

										<div class="col-md-5">
											<input id="need_count_element" type="text" class="form-control" name="need_count_element" value="{{ old('need_count_element') }}">

											@if ($errors->has('need_count_element'))
												<span class="help-block">
													<strong>{{ $errors->first('need_count_element') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Добавить комплектующий
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
