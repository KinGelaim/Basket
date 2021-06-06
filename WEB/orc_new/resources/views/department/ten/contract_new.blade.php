@extends('layouts.header')

@section('title')
	Новый договор
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Десятый отдел')
				@if (Route::has('login'))
					<div class="top-right links">
						
					</div>
				@endif
				<div class="content">
					<form method='POST' action="{{route('ten.save_contract', $pack->id)}}">
						{{csrf_field()}}
						<div class="row">
							<div class="col-md-3">
								<label>Название предприятия</label>
							</div>
							<div class="col-md-3">
								
							</div>
							<div class="col-md-3">
								
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<input class='form-control {{$errors->has("id_counterpartie_contract") ? print("inputError ") : print("")}}' name='id_counterpartie_contract' type='text' value='{{old("id_counterpartie_contract")}}' readonly style='display: none;'/>
									<select id="sel4" class='form-control {{$errors->has("id_counterpartie_contract") ? print("inputError ") : print("")}}' name='id_counterpartie_contract'>
										<option></option>
										@if($counterparties)
											@foreach($counterparties as $counterpartie)
												@if(old('id_counterpartie_contract'))
													@if(old('id_counterpartie_contract') == $counterpartie->id)
														<option value='{{$counterpartie->id}}' selected>{{ $counterpartie->name }}</option>
													@else
														<option value='{{$counterpartie->id}}'>{{ $counterpartie->name }}</option>
													@endif
												@else
													@if($pack->id_counterpartie == $counterpartie->id)
														<option value='{{$counterpartie->id}}' selected>{{ $counterpartie->name }}</option>
													@else
														<option value='{{$counterpartie->id}}'>{{ $counterpartie->name }}</option>
													@endif
												@endif
											@endforeach
										@endif
									</select>
									@if($errors->has('id_counterpartie_contract'))
										<label class='msgError'>{{$errors->first('id_counterpartie_contract')}}</label>
									@endif
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="sel3">Вид договора</label>
											<select id="sel3" class='form-control {{$errors->has("id_view_contract") ? print("inputError ") : print("")}}' name='id_view_contract'>
												<option></option>
												@if($viewContracts)
													@foreach($viewContracts as $viewContract)
														@if(old('id_view_contract_contract'))
															@if(old('id_view_contract_contract') == $viewContract->id)
																<option value='{{$viewContract->id}}' selected>{{ $viewContract->name_view_contract }}</option>
															@else
																<option value='{{$viewContract->id}}'>{{ $viewContract->name_view_contract }}</option>
															@endif
														@else
															<option value='{{$viewContract->id}}'>{{ $viewContract->name_view_contract }}</option>
														@endif
													@endforeach
												@endif
											</select>
											@if($errors->has('id_view_contract_contract'))
												<label class='msgError'>{{$errors->first('id_view_contract_contract')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-12">
												<div class='form-group'>
													<label for='nameWork'>Наименование работ</label>
													<textarea id='nameWork' class='form-control {{$errors->has("name_work_contract") ? print("inputError ") : print("")}}' name='name_work_contract' type="text" style="width: 100%;" rows='4'>{{old('name_work_contract')}}</textarea>
													@if($errors->has('name_work_contract'))
														<label class='msgError'>{{$errors->first('name_work_contract')}}</label>
													@endif
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								
							</div>
							<div class="col-md-2">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for='check_checkpoint'>Контрольные точки</label>
											<div id='checkpoint_list'style='min-height: 185px; max-height: 185px; overflow-y: auto;'>
												@if(old('checkpoint_date'))
													<?php $number_checkpoint = 0; ?>
													@foreach(old('checkpoint_date') as $key=>$value)
														<div class='col-md-6 count-old-checkpoint' style='margin-top: 5px;'>
															<input name='checkpoint_date[{{$number_checkpoint}}]' class='form-control datepicker' type='text' value='{{$value}}'/>
														</div>
														<div class='col-md-6' style='margin-top: 5px;'>
															<input name='checkpoint_comment[{{$number_checkpoint}}]' class='form-control' type='text' value="{{old('checkpoint_comment')[$key]}}"/>
														</div>
														<?php $number_checkpoint++; ?>
													@endforeach
												@endif
											</div>
											<button id='btn_add_checkpoint' class='btn btn-secondary' type='button' style='margin-top: 7px;'>Добавить контрольную точку</button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="row">
									<div class="col-md-10">
										<div class="form-group">
											<label>Срок исполнения</label>
											<input name='date_test_date' class='datepicker form-control' type='text' value="{{old('date_test_date')}}"/>
											@if(old('date_test'))
												<input id='date_test' name='date_test' class='form-check-input' type="checkbox" checked />
											@else
												<input id='date_test' name='date_test' class='form-check-input' type="checkbox" />
											@endif
											<label for='date_test'>Не определен</label>
											@if(old('date_test'))
												<textarea id='date_textarea' name='date_textarea' class='form-control' type="text" style="width: 100%;" rows='5'>{{old('date_textarea')}}</textarea>
											@else
												<textarea id='date_textarea' name='date_textarea' class='form-control' type="text" style="width: 100%;" rows='5' readonly>{{old('date_textarea')}}</textarea>
											@endif
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="row">
									<div class="col-md-10">
										<div class="form-group">
											<label for='year_contract'>Год</label>
											<input id='year_contract' class='change_contract_number form-control {{$errors->has("year_contract") ? print("inputError ") : print("")}}' name='year_contract' type='text' value='{{old("year_contract") ? old("year_contract") : date("Y", time())}}' required/>
											@if($errors->has('year_contract'))
												<label class='msgError'>{{$errors->first('year_contract')}}</label>
											@endif
										</div>
									</div>
									<div class="col-md-10">
										<div class="form-group">
											<label>Сумма (начальная)</label>
											<input name='amount' class='form-control' type='text' value="{{old('amount')}}"/>
											@if(old('fix_amount'))
												<input id='fix_amount' name='fix_amount' class='form-check-input' type="checkbox" checked />
											@else
												<input id='fix_amount' name='fix_amount' class='form-check-input' type="checkbox" />
											@endif
											<label for='fix_amount'>Фиксированная сумма</labeL>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<button type='submit' class="btn btn-primary" style="float: right;">Сохранить договор</button>
							</div>
						</div>
					</form>
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
