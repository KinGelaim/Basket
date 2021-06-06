@extends('layouts.header')

@section('title')
	@if($second_department_us_tours)
		Редактирование наряда услуги
	@else
		Новый наряд на услугу
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
									@if($second_department_us_tours)
										Редактирование наряда услуги
									@else
										Новый наряд на услугу
									@endif
								</div>
								<div class="panel-body">
									@if(Session::has('message'))
										<div class='col-md-12 alert alert-danger'>
											{{Session('message')}}
										</div>
									@endif
									@if($second_department_us_tours)
										<form method='POST' action="{{route('department.second.update_tour_of_duty_us', $second_department_us_tours->id)}}">
											{{csrf_field()}}
											<div class='row'>
												<div class="col-md-4">
													<label>Номер наряда</label>
													<input class='form-control' name='number_duty' type='text' value="{{old('number_duty') ? old('number_duty') : $second_department_us_tours->number_duty}}" required onchange="$('input[name=number_report]').val($(this).val());" />
												</div>
												<div class="col-md-4" style='display: none;'>
													<label>Дата наряда</label>
													<input class='form-control' name='date_duty' type='text' value="{{old('date_duty') ? old('date_duty') : $second_department_us_tours->date_duty}}" required readonly />
												</div>
												<div class='col-md-4'>
													<div class="form-group">
														<button type='button' class='btn btn-danger btn-href' style='margin-top: 26px; float: right;' href='{{route("department.second.delete_us", $second_department_us_tours->id)}}'>Удалить</button>
													</div>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-2">
													<label>Дата отработки</label>
													<input class='form-control datepicker' name='date_worked' type='text' value="{{old('date_worked') ? old('date_worked') : $second_department_us_tours->date_worked ? date('d.m.Y', strtotime($second_department_us_tours->date_worked)) : '' }}"/>
												</div>
												<div class="col-md-2">
													<label>№ отчёта-справки</label>
													<input class='form-control' name='number_help_report' type='text' value="{{old('number_help_report') ? old('number_help_report') : $second_department_us_tours->number_help_report}}"/>
												</div>
												<div class='col-md-4'>
													<label>Дата отчёта-справки</label>
													<input class='form-control datepicker' name='date_help_report' type='text' value="{{old('date_help_report') ? old('date_help_report') : $second_department_us_tours->date_help_report}}"/>
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
										<form method='POST' action="{{route('department.second.store_tour_of_duty_us', $contractID)}}">
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
												<div class="col-md-2">
													<label>Дата отработки</label>
													<input class='form-control datepicker' name='date_worked' type='text' value="{{old('date_worked')}}"/>
												</div>
												<div class="col-md-2">
													<label>№ отчёта-справки</label>
													<input class='form-control' name='number_help_report' type='text' value="{{old('number_help_report')}}"/>
												</div>
												<div class='col-md-4'>
													<label>Дата отчёта-справки</label>
													<input class='form-control datepicker' name='date_help_report' type='text' value="{{old('date_help_report')}}"/>
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