@extends('layouts.header')

@section('title')
	Договор
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел')
				@if (Route::has('login'))
					<div class="top-right links">
						
					</div>
				@endif
				
				@if($contract)
					
				@else
					<div class="content">
						<form method='POST' action="{{route('department.ekonomic.store', $number_document)}}">
							{{csrf_field()}}
							<!--<div class="row">
								<div class="col-md-1">
									<div class="form-group">
										<label for='number_pp'>№ п/п</label>
										<input id='number_pp' class='change_contract_number form-control {{$errors->has("number_pp") ? print("inputError ") : print("")}}' name='number_pp' type='text' value='{{old("number_pp")}}' required/>
										@if($errors->has('number_pp'))
											<label class='msgError'>{{$errors->first('number_pp')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-1">
									<div class="form-group">
										<label for='index_dep'>Индекс подразд.</label>
										<select id='index_dep' class='change_contract_number form-control {{$errors->has("index_dep") ? print("inputError ") : print("")}}' name='index_dep' type='text' value='{{old("index_dep")}}' required>
											@if(old('index_dep'))
												<option>{{old('index_dep')}}</option>
											@endif
											<option></option>
											<option>01</option>
											<option>02</option>
											<option>03</option>
										</select>
										@if($errors->has('index_dep'))
											<label class='msgError'>{{$errors->first('index_dep')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-1">
									<div class="form-group">
										<label for='year_contract'>Год</label>
										<input id='year_contract' class='change_contract_number form-control {{$errors->has("year_contract") ? print("inputError ") : print("")}}' name='year_contract' type='text' value='{{old("year_contract") ? old("year_contract") : date("Y", time())}}' required/>
										@if($errors->has('year_contract'))
											<label class='msgError'>{{$errors->first('year_contract')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for='numberContract'>Номер договора</label>
										<input id='numberContract' class='form-control {{$errors->has("number_contract") ? print("inputError ") : print("")}}' name='number_contract' type='text' value='{{old("number_contract")}}' readonly />
										@if($errors->has('number_contract'))
											<label class='msgError'>{{$errors->first('number_contract')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-1">
									<div class='form-check'>
										<label class='form-check-label' for='gozCheck2'>ГОЗ</label>
										<input id='gozCheck2' class='form-check-input' name='goz_contract' type="checkbox" {{old("goz_contract") ? print("checked ") : print("")}}/>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="sel3">Вид работ</span></label>
										<select id="sel3" class='form-control {{$errors->has("id_view_work_contract") ? print("inputError ") : print("")}}' name='id_view_work_contract'>
											<option></option>
											@if($viewWorks)
												@foreach($viewWorks as $viewWork)
													@if(old('id_view_work_contract'))
														@if(old('id_view_work_contract') == $viewWork->id)
															<option value='{{$viewWork->id}}' selected>{{ $viewWork->name_view_work }}</option>
														@else
															<option value='{{$viewWork->id}}'>{{ $viewWork->name_view_work }}</option>
														@endif
													@else
														<option value='{{$viewWork->id}}'>{{ $viewWork->name_view_work }}</option>
													@endif
												@endforeach
											@endif
										</select>
										@if($errors->has('id_view_work_contract'))
											<label class='msgError'>{{$errors->first('id_view_work_contract')}}</label>
										@endif
									</div>
								</div>
								<div class="col-md-1">
									
								</div>
								<div class="col-md-3">
									
								</div>
							</div>-->
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
										<input class='form-control {{$errors->has("id_counterpartie_contract") ? print("inputError ") : print("")}}' name='id_counterpartie_contract' type='text' value='{{old("id_counterpartie_contract") ? old("id_counterpartie_contract") : $id_counterpartie}}' readonly style='display: none;'/>
										<select id="sel4" class='form-control {{$errors->has("id_counterpartie_contract") ? print("inputError ") : print("")}}' name='id_counterpartie_contract' disabled>
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
														@if($id_counterpartie)
															@if($id_counterpartie == $counterpartie->id)
																<option value='{{$counterpartie->id}}' selected>{{ $counterpartie->name }}</option>
															@else
																<option value='{{$counterpartie->id}}'>{{ $counterpartie->name }}</option>
															@endif
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
												<label for="sel3">Вид работ</label>
												<select id="sel3" class='form-control {{$errors->has("id_view_contract") ? print("inputError ") : print("")}}' name='id_view_contract'>
													<option></option>
													@if($viewContracts)
														@foreach($viewContracts as $viewContract)
															@if(old('id_view_contract'))
																@if(old('id_view_contract') == $viewContract->id)
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
												@if($errors->has('id_view_contract'))
													<label class='msgError'>{{$errors->first('id_view_contract')}}</label>
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
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label>Детализация</label>
												<div id='detailing_list' class='row' style='min-height: 185px; max-height: 185px; overflow-y: auto;'>
													@if(old('name_elements'))
														<?php $number_element = 0; ?>
														@foreach(old('name_elements') as $key=>$value)
															<div class='col-md-4 count-old-element' style='margin-top: 5px;'>
																<select name='name_elements[{{$number_element}}]' class='form-control'>
																	<option></option>
																	@foreach($elements as $element)
																		@if($value == $element->id)
																			<option value="{{$element->id}}" selected>{{$element->name_element}}</option>
																		@else
																			<option value="{{$element->id}}">{{$element->name_element}}</option>
																		@endif
																	@endforeach
																</select>
															</div>
															<div class='col-md-5' style='margin-top: 5px;'>
																<select name='name_view_work[{{$number_element}}]' class='form-control'>
																	<option></option>
																	@foreach($view_work_elements as $view)
																		@if(old('name_view_work')[$key] == $view->id)
																			<option value="{{$view->id}}" selected>{{$view->name_view_work_elements}}</option>
																		@else
																			<option value="{{$view->id}}">{{$view->name_view_work_elements}}</option>
																		@endif
																	@endforeach
																</select>
															</div>
															<div class='col-md-3' style='margin-top: 5px;'>
																<input name='count_elements[{{$number_element}}]' class='form-control' type='text' value="{{old('count_elements')[$key]}}"/>
															</div>
															<?php $number_element++; ?>
														@endforeach
													@endif
												</div>
												<button id='btn_add_element' class='btn btn-secondary' type='button' style='margin-top: 7px;' 
																select_elemet='@foreach($elements as $element)
																	<option value="{{$element->id}}">{{$element->name_element}}</option>
																@endforeach' 
																select_view_work_elements='@foreach($view_work_elements as $view)
																	<option value="{{$view->id}}">{{$view->name_view_work_elements}}</option>
																@endforeach'>Добавить изделие</button>
											</div>
										</div>
									</div>
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
												<label>Ответственный исполнитель</label>
												<select class='form-control' name='select_curator'>
													<option></option>
													@if(old('select_curator'))
														@foreach($curators as $in_curators)
															@if(old('select_curator') == $in_curators->id)
																<option value='{{$in_curators->id}}' selected>{{$in_curators->FIO}}</option>
															@else
																<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
															@endif
														@endforeach
													@else
														@foreach($curators as $in_curators)
															@if(count($curator) > 0)
																@if($curator[0]->id == $in_curators->id)
																	<option value='{{$in_curators->id}}' selected>{{$in_curators->FIO}}</option>
																@else
																	<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
																@endif
															@else
																<option value='{{$in_curators->id}}'>{{$in_curators->FIO}}</option>
															@endif
														@endforeach
													@endif
												</select>
												<label>Сумма (начальная)</label>
												<input name='amount' class='form-control check-number' type='text' value="{{old('amount')}}"/>
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
								<div class="col-md-6">
									<!--<button class="btn btn-secondary" style="float: right;">Дополнительные соглашения</button>-->
								</div>
								<div class="col-md-3">
									<!--<button class="btn btn-secondary" style="float: left;">Протокол разногласий</button>-->
								</div>
								<div class="col-md-3">
									<button type='submit' class="btn btn-primary" style="float: right;">Сохранить договор</button>
								</div>
							</div>
						</form>
					</div>
				@endif
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
