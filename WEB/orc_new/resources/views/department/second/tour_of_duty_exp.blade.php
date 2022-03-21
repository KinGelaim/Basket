@extends('layouts.header')

@section('title')
	@if($second_department_tours)
		Редактирование наряда
	@else
		Новый наряд
	@endif
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Второй отдел')
				<div class="container">
					<div class="row">
						<div class="col-md-11">
							<div class="panel panel-default">
								<div class="panel-heading">
									@if($second_department_tours)
										Редактирование наряда
									@else
										Новый наряд
									@endif
								</div>
								<div class="panel-body">
									@if(Session::has('message'))
										<div class='col-md-12 alert alert-danger'>
											{{Session('message')}}
										</div>
									@endif
									@if($second_department_tours)
										<form method='POST' action="{{route('department.second.update_tour_of_duty_exp', $second_department_tours->id)}}">
											{{csrf_field()}}
											<div class='row'>
												<div class="col-md-4">
													<label>Номер наряда</label>
													<input class='form-control' name='number_duty' type='text' value="{{old('number_duty') ? old('number_duty') : $second_department_tours->number_duty}}" required onchange="$('input[name=number_report]').val($(this).val());" />
												</div>
												<div class="col-md-4" style='display: none;'>
													<label>Дата наряда</label>
													<input class='form-control' name='date_duty' type='text' value="{{old('date_duty') ? old('date_duty') : $second_department_tours->date_duty}}" required readonly />
												</div>
												<div class="col-md-6">
													<label>Изделие (тема)</label>
													<input class='form-control' name='theme_exp' type='text' value="{{old('theme_exp') ? old('theme_exp') : $second_department_tours->theme_exp}}" required />
												</div>
												<div class='col-md-2'>
													<div class="form-group">
														<button type='button' class='btn btn-danger btn-href' style='margin-top: 26px; float: right;' href='{{route("department.second.delete", $second_department_tours->id)}}'>Удалить</button>
													</div>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-4">
													<label>Дата начала работ</label>
													<input class='form-control datepicker' name='date_incoming' type='text' value="{{old('date_incoming') ? old('date_incoming') : $second_department_tours->date_incoming}}"/>
												</div>
												<div class='col-md-2'>
													<label>Количество</label>
													<input class='form-control {{$errors->has("count_elements") ? print("inputError ") : print("")}}' name='count_elements' type='text' value="{{old('count_elements') ? old('count_elements') : $second_department_tours->count_elements}}" required />
													@if($errors->has('count_elements'))
														<label class='msgError'>{{$errors->first('count_elements')}}</label>
													@endif
												</div>
												<div class='col-md-2'>
													<label>Ед. изм.</label>
													<select class='form-control {{$errors->has("id_unit") ? print("inputError ") : print("")}}' id="selView" name='id_unit' >
														<option></option>
														@foreach($second_department_units as $unit)
															@if(old('id_unit'))
																@if(old('id_unit') == $unit->id)
																	<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
																@else
																	<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
																@endif
															@else
																@if($second_department_tours->id_unit == $unit->id)
																	<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
																@else
																	<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
																@endif
															@endif
														@endforeach
													</select>
													@if($errors->has('id_unit'))
														<label class='msgError'>{{$errors->first('id_unit')}}</label>
													@endif
												</div>
											</div>
											<div class='row'>
												<div class="col-md-2">
													<label>Счетные</label>
													<input class='form-control {{$errors->has("countable") ? print("inputError ") : print("")}}' name='countable' type='text' value="{{old('countable') ? old('countable') : $second_department_tours->countable}}"/>
													@if($errors->has('countable'))
														<label class='msgError'>{{$errors->first('countable')}}</label>
													@endif
												</div>
												<div class='col-md-2'>
													<label>Пристрелочные</label>
													<input class='form-control {{$errors->has("targeting") ? print("inputError ") : print("")}}' name='targeting' type='text' value="{{old('targeting') ? old('targeting') : $second_department_tours->targeting}}"/>
													@if($errors->has('targeting'))
														<label class='msgError'>{{$errors->first('targeting')}}</label>
													@endif
												</div>
												<div class="col-md-2">
													<label>Прогревные</label>
													<input class='form-control {{$errors->has("warm") ? print("inputError ") : print("")}}' name='warm' type='text' value="{{old('warm') ? old('warm') : $second_department_tours->warm}}"/>
													@if($errors->has('warm'))
														<label class='msgError'>{{$errors->first('warm')}}</label>
													@endif
												</div>
												<div class='col-md-2'>
													<label>Несчетные</label>
													<input class='form-control {{$errors->has("uncountable") ? print("inputError ") : print("")}}' name='uncountable' type='text' value="{{old('uncountable') ? old('uncountable') : $second_department_tours->uncountable}}"/>
													@if($errors->has('uncountable'))
														<label class='msgError'>{{$errors->first('uncountable')}}</label>
													@endif
												</div>
												<div class="col-md-2">
													<label>Отказ</label>
													<input class='form-control {{$errors->has("renouncement") ? print("inputError ") : print("")}}' name='renouncement' type='text' value="{{old('renouncement') ? old('renouncement') : $second_department_tours->renouncement}}"/>
													@if($errors->has('renouncement'))
														<label class='msgError'>{{$errors->first('renouncement')}}</label>
													@endif
												</div>
												<div class="col-md-2">
													<label class='small-text'>Дата окончания работ</label>
													<input class='form-control datepicker' name='date_worked' type='text' value="{{old('date_worked') ? old('date_worked') : $second_department_tours->date_worked ? date('d.m.Y', strtotime($second_department_tours->date_worked)) : '' }}"/>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12">
													<label>Документ о выполнении работ</label>
													<input class='form-control' name='result_document_exp' type='text' value="{{old('result_document_exp') ? old('result_document_exp') : $second_department_tours->result_document_exp}}"/>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-6" style='text-align: left;'>
												</div>
												<div class="col-md-2" style='text-align: left;'>
													<button class='btn btn-secondary' type='button' onclick='history.back();'>Выход</button>
												</div>
												<div class="col-md-4" style='text-align: right;'>
													<button type='submit' class='btn btn-primary'>Редактировать наряд</button>
												</div>
											</div>
										</form>
									@else
										<form method='POST' action="{{route('department.second.store_tour_of_duty_exp', $contractID)}}">
											{{csrf_field()}}
											<div class='row'>
												<div class="col-md-4">
													<label>Номер наряда</label>
													<input class='form-control' name='number_duty' type='text' value="{{old('number_duty')}}" required />
												</div>
												<div class="col-md-4" style='display: none;'>
													<label>Дата наряда</label>
													<input class='form-control' name='date_duty' type='text' value="{{old('date_duty') ? old('date_duty') : date('d.m.Y', time())}}" required readonly />
												</div>
												<div class="col-md-4">
													<label>Изделие (тема)</label>
													<input class='form-control' name='theme_exp' type='text' value="{{old('theme_exp')}}" required />
												</div>
											</div>
											<div class='row'>
												<div class="col-md-4">
													<label>Дата начала работ</label>
													<input class='form-control datepicker' name='date_incoming' type='text' value="{{old('date_incoming')}}"/>
												</div>
												<div class='col-md-2'>
													<label>Количество</label>
													<input class='form-control {{$errors->has("count_elements") ? print("inputError ") : print("")}}' name='count_elements' type='text' value="{{old('count_elements')}}" required />
													@if($errors->has('count_elements'))
														<label class='msgError'>{{$errors->first('count_elements')}}</label>
													@endif
												</div>
												<div class='col-md-2'>
													<label>Ед. изм.</label>
													<select class='form-control {{$errors->has("id_unit") ? print("inputError ") : print("")}}' id="selView" name='id_unit' >
														<option></option>
														@foreach($second_department_units as $unit)
															@if(old('id_unit'))
																@if(old('id_unit') == $unit->id)
																	<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
																@else
																	<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
																@endif
															@else
																<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
															@endif
														@endforeach
													</select>
													@if($errors->has('id_unit'))
														<label class='msgError'>{{$errors->first('id_unit')}}</label>
													@endif
												</div>
											</div>
											<div class='row'>
												<div class="col-md-12" style='text-align: right;'>
													<button type='submit' class='btn btn-primary' type='button'>Добавить наряд</button>
												</div>
											</div>
										</form>
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
				<script>
					$(function(){
						$('.autocomplete').each(function(e){
							var k = $(this).attr('src_autocomplete').split(';');
							$(this).autocomplete({
								source: k
							});
						});
					});
				</script>
			@else
				<div class="alert alert-danger">
					Недостаточно прав для просмотра данной страницы!
				</div>
			@endif
		@else
			<div class="alert alert-danger">
				Для просмотра данной страницы необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection