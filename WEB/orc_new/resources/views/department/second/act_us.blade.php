@extends('layouts.header')

@section('title')
	@if($act)
		Редактирование акта
	@else
		Новый акт
	@endif
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							@if($act)
								<h3>Редактирование акта</h3>
							@else
								<h3>Новый акт</h3>
							@endif
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							@if($act)
								<form class="form-horizontal" method="POST" action="{{ route('department.second.update_act', $act->id) }}">
									{{ csrf_field() }}
									
									<div class="form-group{{ $errors->has('number_act') ? ' has-error' : '' }}">
										<label for="number_act" class="col-md-4 control-label">Номер акта</label>

										<div class="col-md-3">
											<input id="number_act" type="text" class="form-control" name="number_act" value="{{ old('number_act') ? old('number_act') : $act->number_act }}">

											@if ($errors->has('number_act'))
												<span class="help-block">
													<strong>{{ $errors->first('number_act') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('date_act') ? ' has-error' : '' }}">
										<label for="date_act" class="col-md-4 control-label">Дата акта</label>

										<div class="col-md-3">
											<input id="date_act" type="text" class="form-control datepicker" name="date_act" value="{{ old('date_act') ? old('date_act') : $act->date_act }}">

											@if ($errors->has('date_act'))
												<span class="help-block">
													<strong>{{ $errors->first('date_act') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('number_outgoing_act') ? ' has-error' : '' }}">
										<label for="number_outgoing_act" class="col-md-4 control-label">Исходящий номер акта</label>

										<div class="col-md-3">
											<input id="number_outgoing_act" type="text" class="form-control" name="number_outgoing_act" value="{{ old('number_outgoing_act') ? old('number_outgoing_act') : $act->number_outgoing_act }}">
											@if ($errors->has('number_outgoing_act'))
												<span class="help-block">
													<strong>{{ $errors->first('number_outgoing_act') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('date_outgoing_act') ? ' has-error' : '' }}">
										<label for="date_outgoing_act" class="col-md-4 control-label">Дата исходящего</label>

										<div class="col-md-3">
											<input id="date_outgoing_act" type="text" class="form-control datepicker" name="date_outgoing_act" value="{{ old('date_outgoing_act') ? old('date_outgoing_act') : $act->date_outgoing_act }}">
											@if ($errors->has('date_outgoing_act'))
												<span class="help-block">
													<strong>{{ $errors->first('date_outgoing_act') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('number_incoming_act') ? ' has-error' : '' }}">
										<label for="number_incoming_act" class="col-md-4 control-label">Входящий номер акта</label>

										<div class="col-md-3">
											<input id="number_incoming_act" type="text" class="form-control" name="number_incoming_act" value="{{ old('number_incoming_act') ? old('number_incoming_act') : $act->number_incoming_act }}">
											@if ($errors->has('number_incoming_act'))
												<span class="help-block">
													<strong>{{ $errors->first('number_incoming_act') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('date_incoming_act') ? ' has-error' : '' }}">
										<label for="date_incoming_act" class="col-md-4 control-label">Дата входящего</label>

										<div class="col-md-3">
											<input id="date_incoming_act" type="text" class="form-control datepicker" name="date_incoming_act" value="{{ old('date_incoming_act') ? old('date_incoming_act') : $act->date_incoming_act }}">
											@if ($errors->has('date_incoming_act'))
												<span class="help-block">
													<strong>{{ $errors->first('date_incoming_act') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('amount_act') ? ' has-error' : '' }}">
										<label for="amount_act" class="col-md-4 control-label">Сумма с НДС, руб</label>

										<div class="col-md-3">
											<input id="amount_act" type="text" class="form-control check-number" name="amount_act" value="{{ old('amount_act') ? old('amount_act') : $act->amount_act }}" readonly required>

											@if ($errors->has('amount_act'))
												<span class="help-block">
													<strong>{{ $errors->first('amount_act') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Изменить акт
											</button>
										</div>
									</div>
								</form>
							@else
								<form class="form-horizontal" method="POST" action="{{ route('department.second.store_act_us', $id_second_tour) }}">
									{{ csrf_field() }}
									
									<div class="form-group{{ $errors->has('number_act') ? ' has-error' : '' }}">
										<label for="number_act" class="col-md-4 control-label">Номер акта</label>

										<div class="col-md-3">
											<input id="number_act" type="text" class="form-control" name="number_act" value="{{ old('number_act') }}">

											@if ($errors->has('number_act'))
												<span class="help-block">
													<strong>{{ $errors->first('number_act') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('date_act') ? ' has-error' : '' }}">
										<label for="date_act" class="col-md-4 control-label">Дата акта</label>

										<div class="col-md-3">
											<input id="date_act" type="text" class="form-control datepicker" name="date_act" value="{{ old('date_act') }}">

											@if ($errors->has('date_act'))
												<span class="help-block">
													<strong>{{ $errors->first('date_act') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('number_outgoing_act') ? ' has-error' : '' }}">
										<label for="number_outgoing_act" class="col-md-4 control-label">Исходящий номер акта</label>

										<div class="col-md-3">
											<input id="number_outgoing_act" type="text" class="form-control" name="number_outgoing_act" value="{{ old('number_outgoing_act') }}">
											@if ($errors->has('number_outgoing_act'))
												<span class="help-block">
													<strong>{{ $errors->first('number_outgoing_act') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('date_outgoing_act') ? ' has-error' : '' }}">
										<label for="date_outgoing_act" class="col-md-4 control-label">Дата исходящего</label>

										<div class="col-md-3">
											<input id="date_outgoing_act" type="text" class="form-control datepicker" name="date_outgoing_act" value="{{ old('date_outgoing_act') }}">
											@if ($errors->has('date_outgoing_act'))
												<span class="help-block">
													<strong>{{ $errors->first('date_outgoing_act') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('number_incoming_act') ? ' has-error' : '' }}">
										<label for="number_incoming_act" class="col-md-4 control-label">Входящий номер акта</label>

										<div class="col-md-3">
											<input id="number_incoming_act" type="text" class="form-control" name="number_incoming_act" value="{{ old('number_incoming_act') }}">
											@if ($errors->has('number_incoming_act'))
												<span class="help-block">
													<strong>{{ $errors->first('number_incoming_act') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('date_incoming_act') ? ' has-error' : '' }}">
										<label for="date_incoming_act" class="col-md-4 control-label">Дата входящего</label>

										<div class="col-md-3">
											<input id="date_incoming_act" type="text" class="form-control datepicker" name="date_incoming_act" value="{{ old('date_incoming_act') }}">
											@if ($errors->has('date_incoming_act'))
												<span class="help-block">
													<strong>{{ $errors->first('date_incoming_act') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group{{ $errors->has('amount_act') ? ' has-error' : '' }}">
										<label for="amount_act" class="col-md-4 control-label">Сумма с НДС, руб</label>

										<div class="col-md-3">
											<input id="amount_act" type="text" class="form-control check-number" name="amount_act" value="{{ old('amount_act') }}" required>

											@if ($errors->has('amount_act'))
												<span class="help-block">
													<strong>{{ $errors->first('amount_act') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Добавить акт
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
