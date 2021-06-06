@extends('layouts.header')

@section('title')
	@if($second_department_sb_tours)
		Редактирование наряда сборки
	@else
		Новый наряд на сборку
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
									@if($second_department_sb_tours)
										Редактирование наряда сборки
									@else
										Новый наряд на сборку
									@endif
								</div>
								<div class="panel-body">
									@if(Session::has('message'))
										<div class='col-md-12 alert alert-danger'>
											{{Session('message')}}
										</div>
									@endif
									@if($second_department_sb_tours)
										<form method='POST' action="{{route('department.second.update_tour_of_duty_sb', $second_department_sb_tours->id)}}">
											{{csrf_field()}}
											<div class='row'>
												<div class="col-md-4">
													<label>Номер наряда</label>
													<input class='form-control' name='number_duty' type='text' value="{{old('number_duty') ? old('number_duty') : $second_department_sb_tours->number_duty}}" required onchange="$('input[name=number_report]').val($(this).val());" />
												</div>
												<div class="col-md-4" style='display: none;'>
													<label>Дата наряда</label>
													<input class='form-control' name='date_duty' type='text' value="{{old('date_duty') ? old('date_duty') : $second_department_sb_tours->date_duty}}" required readonly />
												</div>
												<div class='col-md-4'>
													<div class="form-group">
														<label for="selView">Выберите вид работ</label>
														<select class='form-control {{$errors->has("id_view_work_elements") ? print("inputError ") : print("")}}' id="selView" name='id_view_work_elements' required >
															<option></option>
															@foreach($view_work_elements as $view)
																@if($view->name_view_work_elements == 'сборка' || $view->name_view_work_elements == 'заливка' || $view->name_view_work_elements == 'изготовление')
																	@if(old('id_view_work_elements'))
																		@if(old('id_view_work_elements') == $view->id)
																			<option value='{{$view->id}}' selected>{{$view->name_view_work_elements}}</option>
																		@else
																			<option value='{{$view->id}}'>{{$view->name_view_work_elements}}</option>
																		@endif
																	@else
																		@if($second_department_sb_tours->id_view_work_elements == $view->id)
																			<option value='{{$view->id}}' selected>{{$view->name_view_work_elements}}</option>
																		@else
																			<option value='{{$view->id}}'>{{$view->name_view_work_elements}}</option>
																		@endif
																	@endif
																@endif
															@endforeach
														</select>
														@if($errors->has('id_view_work_elements'))
															<label class='msgError'>{{$errors->first('id_view_work_elements')}}</label>
														@endif
													</div>
												</div>
												<div class='col-md-4'>
													<div class="form-group">
														<button type='button' class='btn btn-danger btn-href' style='margin-top: 26px; float: right;' href='{{route("department.second.delete_sb", $second_department_sb_tours->id)}}'>Удалить</button>
													</div>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-4">
													<div class="form-group">
														<label for="selElement">Выберите изделие</label>
														<select class='form-control {{$errors->has("id_element") ? print("inputError ") : print("")}}' id="selElement" name='id_element' required>
															@foreach($elements as $element)
																@if(old('id_element'))
																	@if(old('id_element') == $element->id)
																		<option value='{{$element->id}}' selected>{{$element->name_element}}</option>
																	@else
																		<option value='{{$element->id}}'>{{$element->name_element}}</option>
																	@endif
																@else
																	@if($second_department_sb_tours->id_element == $element->id)
																		<option value='{{$element->id}}' selected>{{$element->name_element}}</option>
																	@else
																		<option value='{{$element->id}}'>{{$element->name_element}}</option>
																	@endif
																@endif
															@endforeach
														</select>
														@if($errors->has('id_element'))
															<label class='msgError'>{{$errors->first('id_element')}}</label>
														@endif
													</div>
												</div>
												<div class='col-md-2'>
													<label>Тип</label>
													<select class='form-control {{$errors->has("id_caliber") ? print("inputError ") : print("")}}' id="selView" name='id_caliber' required >
														<option></option>
														@foreach($calibers as $caliber)
															@if(old('id_caliber'))
																@if(old('id_caliber') == $caliber->id)
																	<option value='{{$caliber->id}}' selected>{{$caliber->name_caliber}}</option>
																@else
																	<option value='{{$caliber->id}}'>{{$unit->name_caliber}}</option>
																@endif
															@else
																@if($second_department_sb_tours->id_caliber == $caliber->id)
																	<option value='{{$caliber->id}}' selected>{{$caliber->name_caliber}}</option>
																@else
																	<option value='{{$caliber->id}}'>{{$caliber->name_caliber}}</option>
																@endif
															@endif
														@endforeach
													</select>
													@if($errors->has('id_caliber'))
														<label class='msgError'>{{$errors->first('id_caliber')}}</label>
													@endif
												</div>
											</div>
											<div class='row'>
												<div class="col-md-2">
													<label>Партия</label>
													<input class='form-control' name='number_party' type='text' value="{{old('number_party') ? old('number_party') : $second_department_sb_tours->number_party}}" />
												</div>
												<div class='col-md-2'>
													<label>Количество</label>
													<input class='form-control {{$errors->has("count_elements") ? print("inputError ") : print("")}}' name='count_elements' type='text' value="{{old('count_elements') ? old('count_elements') : $second_department_sb_tours->count_elements}}" required />
													@if($errors->has('count_elements'))
														<label class='msgError'>{{$errors->first('count_elements')}}</label>
													@endif
												</div>
												<div class='col-md-2'>
													<label>Доп. количество</label>
													<input class='form-control {{$errors->has("addition_count_elements") ? print("inputError ") : print("")}}' name='addition_count_elements' type='text' value="{{old('addition_count_elements') ? old('addition_count_elements') : $second_department_sb_tours->addition_count_elements}}"/>
													@if($errors->has('addition_count_elements'))
														<label class='msgError'>{{$errors->first('addition_count_elements')}}</label>
													@endif
												</div>
												<div class='col-md-2'>
													<label>Ед. изм.</label>
													<select class='form-control {{$errors->has("id_unit") ? print("inputError ") : print("")}}' id="selView" name='id_unit' required >
														<option></option>
														@foreach($second_department_units as $unit)
															@if(old('id_unit'))
																@if(old('id_unit') == $unit->id)
																	<option value='{{$unit->id}}' selected>{{$unit->name_unit}}</option>
																@else
																	<option value='{{$unit->id}}'>{{$unit->name_unit}}</option>
																@endif
															@else
																@if($second_department_sb_tours->id_unit == $unit->id)
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
													<label>Дата сдачи</label>
													<input class='form-control datepicker' name='date_worked' type='text' value="{{old('date_worked') ? old('date_worked') : $second_department_sb_tours->date_worked ? date('d.m.Y', strtotime($second_department_sb_tours->date_worked)) : '' }}"/>
												</div>
												<div class="col-md-2">
													<label>№ формуляра</label>
													<input class='form-control' name='number_logbook' type='text' value="{{old('number_logbook') ? old('number_logbook') : $second_department_sb_tours->number_logbook}}"/>
												</div>
												<div class='col-md-2'>
													<label>Дата формуляра</label>
													<input class='form-control datepicker' name='date_logbook' type='text' value="{{old('date_logbook') ? old('date_logbook') : $second_department_sb_tours->date_logbook}}"/>
												</div>
												<div class="col-md-2">
													<label>№ уведомления</label>
													<input class='form-control' name='number_notification' type='text' value="{{old('number_notification') ? old('number_notification') : $second_department_sb_tours->number_notification}}"/>
												</div>
												<div class='col-md-2'>
													<label>Дата уведомления</label>
													<input class='form-control datepicker' name='date_notification' type='text' value="{{old('date_notification') ? old('date_notification') : $second_department_sb_tours->date_notification}}"/>
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
										<form method='POST' action="{{route('department.second.store_tour_of_duty_sb', $contractID)}}">
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
												<div class='col-md-4'>
													<div class="form-group">
														<label for="selView">Выберите вид работ</label>
														<select class='form-control {{$errors->has("id_view_work_elements") ? print("inputError ") : print("")}}' id="selView" name='id_view_work_elements' required >
															<option></option>
															@foreach($view_work_elements as $view)
																@if($view->name_view_work_elements == 'сборка' || $view->name_view_work_elements == 'заливка' || $view->name_view_work_elements == 'изготовление' || $view->name_view_work_elements == 'сборка, заливка')
																	@if(old('id_view_work_elements'))
																		@if(old('id_view_work_elements') == $view->id)
																			<option value='{{$view->id}}' selected>{{$view->name_view_work_elements}}</option>
																		@else
																			<option value='{{$view->id}}'>{{$view->name_view_work_elements}}</option>
																		@endif
																	@else
																		<option value='{{$view->id}}'>{{$view->name_view_work_elements}}</option>
																	@endif
																@endif
															@endforeach
														</select>
														@if($errors->has('id_view_work_elements'))
															<label class='msgError'>{{$errors->first('id_view_work_elements')}}</label>
														@endif
													</div>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-4">
													<div class="form-group">
														<label for="selElement">Введите изделие</label>
														<input id='selElement' class='form-control autocomplete {{$errors->has("full_name_element") ? print("inputError ") : print("")}}' name='full_name_element' src_autocomplete='@foreach($elements as $element){{$element->name_element}};@endforeach' value="{{old('full_name_element')}}" required/>
														@if($errors->has('id_element'))
															<label class='msgError'>{{$errors->first('id_element')}}</label>
														@endif
													</div>
												</div>
												<div class="col-md-2">
													<div class="form-group">
														<label for="selCaliber">Тип</label>
														<select class='form-control {{$errors->has("id_caliber") ? print("inputError ") : print("")}}' id="selCaliber" name='id_caliber' required >
															<option></option>
															@foreach($calibers as $caliber)
																@if(old('id_caliber'))
																	@if(old('id_caliber') == $caliber->id)
																		<option value='{{$caliber->id}}' selected>{{$caliber->name_caliber}}</option>
																	@else
																		<option value='{{$caliber->id}}'>{{$caliber->name_caliber}}</option>
																	@endif
																@else
																	<option value='{{$caliber->id}}'>{{$caliber->name_caliber}}</option>
																@endif
															@endforeach
														</select>
														@if($errors->has('id_caliber'))
															<label class='msgError'>{{$errors->first('id_caliber')}}</label>
														@endif
													</div>
												</div>
												<div class="col-md-2">
													<label>Партия</label>
													<input class='form-control' name='number_party' type='text' value="{{old('number_party')}}"/>
												</div>
											</div>
											<div class='row'>
												<div class='col-md-2'>
													<label>Количество</label>
													<input class='form-control {{$errors->has("count_elements") ? print("inputError ") : print("")}}' name='count_elements' type='text' value="{{old('count_elements')}}" required />
													@if($errors->has('count_elements'))
														<label class='msgError'>{{$errors->first('count_elements')}}</label>
													@endif
												</div>
												<div class='col-md-2'>
													<label>Доп. количество</label>
													<input class='form-control {{$errors->has("addition_count_elements") ? print("inputError ") : print("")}}' name='addition_count_elements' type='text' value="{{old('addition_count_elements')}}"/>
													@if($errors->has('addition_count_elements'))
														<label class='msgError'>{{$errors->first('addition_count_elements')}}</label>
													@endif
												</div>
												<div class='col-md-2'>
													<label>Ед. изм.</label>
													<select class='form-control {{$errors->has("id_unit") ? print("inputError ") : print("")}}' id="selView" name='id_unit' required >
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