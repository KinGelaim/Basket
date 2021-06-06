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
												<div class='col-md-4'>
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
												<div class="col-md-4">
													<label>Дата окончания работ</label>
													<input class='form-control datepicker' name='date_worked' type='text' value="{{old('date_worked') ? old('date_worked') : $second_department_tours->date_worked ? date('d.m.Y', strtotime($second_department_tours->date_worked)) : '' }}"/>
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