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
										<form method='POST' action="{{route('department.second.update_tour_of_duty', $second_department_tours->id)}}">
											{{csrf_field()}}
											<div class='row'>
												<div class="col-md-2">
													<label>Номер наряда</label>
													<input class='form-control' name='number_duty' type='text' value="{{old('number_duty') ? old('number_duty') : $second_department_tours->number_duty}}" required onchange="$('input[name=number_report]').val($(this).val());" />
												</div>
												<div class="col-md-4" style='display: none;'>
													<label>Дата наряда</label>
													<input class='form-control' name='date_duty' type='text' value="{{old('date_duty') ? old('date_duty') : $second_department_tours->date_duty}}" required readonly />
												</div>
												<div class='col-md-4'>
													<div class="form-group">
														<label for="selView">Выберите вид испытания</label>
														<select class='form-control {{$errors->has("id_view_work_elements") ? print("inputError ") : print("")}}' id="selView" name='id_view_work_elements' required >
															@foreach($view_work_elements as $view)
																@if(old('id_view_work_elements'))
																	@if(old('id_view_work_elements') == $view->id)
																		<option value='{{$view->id}}' selected>{{$view->name_view_work_elements}}</option>
																	@else
																		<option value='{{$view->id}}'>{{$view->name_view_work_elements}}</option>
																	@endif
																@else
																	@if($second_department_tours->id_view_work_elements == $view->id)
																		<option value='{{$view->id}}' selected>{{$view->name_view_work_elements}}</option>
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
												<div class="col-md-2">
													<div class="form-group">
														<label for="selCaliber">Тип</label>
														<select class='form-control {{$errors->has("id_caliber") ? print("inputError ") : print("")}}' id="selCaliber" name='id_caliber' >
															<option></option>
															@foreach($calibers as $caliber)
																@if(old('id_caliber'))
																	@if(old('id_caliber') == $caliber->id)
																		<option value='{{$caliber->id}}' selected>{{$caliber->name_caliber}}</option>
																	@else
																		<option value='{{$caliber->id}}'>{{$caliber->name_caliber}}</option>
																	@endif
																@else
																	@if($second_department_tours->id_caliber == $caliber->id)
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
												<div class="col-md-3">
													<div class="form-group">
														<label for="selNameElement">Наименование</label>
														<select class='form-control {{$errors->has("id_name_element") ? print("inputError ") : print("")}}' id="selNameElement" name='id_name_element' >
															<option></option>
															@foreach($name_elements as $name_element)
																@if(old('id_name_element'))
																	@if(old('id_name_element') == $name_element->id)
																		<option value='{{$name_element->id}}' selected>{{$name_element->name_element}}</option>
																	@else
																		<option value='{{$name_element->id}}'>{{$name_element->name_element}}</option>
																	@endif
																@else
																	@if($second_department_tours->id_name_element == $name_element->id)
																		<option value='{{$name_element->id}}' selected>{{$name_element->name_element}}</option>
																	@else
																		<option value='{{$name_element->id}}'>{{$name_element->name_element}}</option>
																	@endif
																@endif
															@endforeach
														</select>
														@if($errors->has('id_name_element'))
															<label class='msgError'>{{$errors->first('id_name_element')}}</label>
														@endif
													</div>
												</div>
												<div class='col-md-1'>
													<div class="form-group">
														<button type='button' class='btn btn-danger btn-href' style='margin-top: 26px; float: right;' href='{{route("department.second.delete", $second_department_tours->id)}}'>Удалить</button>
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
																	@if($second_department_tours->id_element == $element->id)
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
												<div class="col-md-4">
													<label>Дата поступления</label>
													<input class='form-control datepicker' name='date_incoming' type='text' value="{{old('date_incoming') ? old('date_incoming') : $second_department_tours->date_incoming}}"/>
												</div>
												<div class='col-md-2'>
													<label>Количество (шт.)</label>
													<input class='form-control {{$errors->has("count_elements") ? print("inputError ") : print("")}}' name='count_elements' type='text' value="{{old('count_elements') ? old('count_elements') : $second_department_tours->count_elements}}" required />
													@if($errors->has('count_elements'))
														<label class='msgError'>{{$errors->first('count_elements')}}</label>
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
													<label>Дата отработки</label>
													<input class='form-control datepicker' name='date_worked' type='text' value="{{old('date_worked') ? old('date_worked') : $second_department_tours->date_worked ? date('d.m.Y', strtotime($second_department_tours->date_worked)) : '' }}"/>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-4">
													<div class="form-group">
														<label for="selResult">Выберите результат</label>
														<select class='form-control {{$errors->has("id_result") ? print("inputError ") : print("")}}' id="selResult" name='id_result'>
															<option></option>
															@foreach($results as $result)
																@if(old('id_result'))
																	@if(old('id_result') == $result->id)
																		<option value='{{$result->id}}' selected>{{$result->name_result}}</option>
																	@else
																		<option value='{{$result->id}}'>{{$result->name_result}}</option>
																	@endif
																@else
																	@if($second_department_tours->id_result == $result->id)
																		<option value='{{$result->id}}' selected>{{$result->name_result}}</option>
																	@else
																		<option value='{{$result->id}}'>{{$result->name_result}}</option>
																	@endif
																@endif
															@endforeach
														</select>
														@if($errors->has('id_result'))
															<label class='msgError'>{{$errors->first('id_result')}}</label>
														@endif
													</div>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-2">
													<label>Исх.№ телеграммы</label>
													<input class='form-control' name='number_telegram' type='text' value="{{old('number_telegram') ? old('number_telegram') : ($second_department_tours->number_telegram ? $second_department_tours->number_telegram : '22-')}}"/>
												</div>
												<div class='col-md-2'>
													<label>Дата телеграммы</label>
													<input class='form-control datepicker' name='date_telegram' type='text' value="{{old('date_telegram') ? old('date_telegram') : $second_department_tours->date_telegram}}"/>
												</div>
												<div class="col-md-2">
													<label>Номер отчета</label>
													<input class='form-control' name='number_report' type='text' value="{{old('number_report') ? old('number_report') : $second_department_tours->number_report}}"/>
												</div>
												<div class='col-md-2'>
													<label>Дата отп. отчета</label>
													<input class='form-control datepicker' name='date_report' type='text' value="{{old('date_report') ? old('date_report') : $second_department_tours->date_report}}"/>
												</div>
												<div class="col-md-2" style='display: none;'>
													<label>№ акта вып.работ</label>
													<input class='form-control' name='number_act' type='text' value="{{old('number_act') ? old('number_act') : $second_department_tours->number_act}}"/>
												</div>
												<div class="col-md-2" style='display: none;'>
													<label>Дата акта</label>
													<input class='form-control datepicker' name='date_act' type='text' value="{{old('date_act') ? old('date_act') : $second_department_tours->date_act}}"/>
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
										<form method='POST' action="{{route('department.second.store_tour_of_duty', $contractID)}}">
											{{csrf_field()}}
											<div class='row'>
												<div class="col-md-3">
													<label>Номер наряда</label>
													<input class='form-control' name='number_duty' type='text' value="{{old('number_duty')}}" required />
												</div>
												<div class="col-md-4" style='display: none;'>
													<label>Дата наряда</label>
													<input class='form-control' name='date_duty' type='text' value="{{old('date_duty') ? old('date_duty') : date('d.m.Y', time())}}" required readonly />
												</div>
												<div class='col-md-4'>
													<div class="form-group">
														<label for="selView">Выберите вид испытания</label>
														<select class='form-control {{$errors->has("id_view_work_elements") ? print("inputError ") : print("")}}' id="selView" name='id_view_work_elements' required >
															@foreach($view_work_elements as $view)
																@if(old('id_view_work_elements'))
																	@if(old('id_view_work_elements') == $view->id)
																		<option value='{{$view->id}}' selected>{{$view->name_view_work_elements}}</option>
																	@else
																		<option value='{{$view->id}}'>{{$view->name_view_work_elements}}</option>
																	@endif
																@else
																	<option value='{{$view->id}}'>{{$view->name_view_work_elements}}</option>
																@endif
															@endforeach
														</select>
														@if($errors->has('id_view_work_elements'))
															<label class='msgError'>{{$errors->first('id_view_work_elements')}}</label>
														@endif
													</div>
												</div>
												<div class="col-md-2">
													<div class="form-group">
														<label for="selCaliber">Тип</label>
														<select class='form-control {{$errors->has("id_caliber") ? print("inputError ") : print("")}}' id="selCaliber" name='id_caliber' >
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
												<div class="col-md-3">
													<div class="form-group">
														<label for="selNameElement">Наименование</label>
														<select class='form-control {{$errors->has("id_name_element") ? print("inputError ") : print("")}}' id="selNameElement" name='id_name_element' >
															<option></option>
															@foreach($name_elements as $name_element)
																@if(old('id_name_element'))
																	@if(old('id_name_element') == $name_element->id)
																		<option value='{{$name_element->id}}' selected>{{$name_element->name_element}}</option>
																	@else
																		<option value='{{$name_element->id}}'>{{$name_element->name_element}}</option>
																	@endif
																@else
																	<option value='{{$name_element->id}}'>{{$name_element->name_element}}</option>
																@endif
															@endforeach
														</select>
														@if($errors->has('id_name_element'))
															<label class='msgError'>{{$errors->first('id_name_element')}}</label>
														@endif
													</div>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-4">
													<div class="form-group">
														<label for="selElement">Введите изделие</label>
														<input id='selElement' class='form-control autocomplete {{$errors->has("full_name_element") ? print("inputError ") : print("")}}' name='full_name_element' src_autocomplete='@foreach($elements as $element){{$element->name_element}};@endforeach' value="{{old('full_name_element')}}" required/>
														<!--<select class='form-control {{$errors->has("id_element") ? print("inputError ") : print("")}}' id="selElement" name='id_element' required>
															@foreach($elements as $element)
																@if(old('id_element'))
																	@if(old('id_element') == $element->id)
																		<option value='{{$element->id}}' selected>{{$element->name_element}}</option>
																	@else
																		<option value='{{$element->id}}'>{{$element->name_element}}</option>
																	@endif
																@else
																	<option value='{{$element->id}}'>{{$element->name_element}}</option>
																@endif
															@endforeach
														</select>-->
														@if($errors->has('id_element'))
															<label class='msgError'>{{$errors->first('id_element')}}</label>
														@endif
													</div>
												</div>
												<div class="col-md-4">
													<label>Дата поступления</label>
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