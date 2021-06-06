@extends('layouts.app')

@section('title')
	{{$test->name}}
@endsection

@section('content')
	<div class="banner-1">
		
	</div>
	<div class="resource">
		<div class="container">
			@if(Auth::User())
				@if(Auth::User()->hasRole()->role == 'Ученик')
					<div class="content">
						<div class="row">
							<div class="col-md-12">
								<h3>Тест: {{$test->name}}</h3>
							</div>
							<div class="col-md-12">
								<p>Время прохождения теста не ограничено</p>
								<p>Для перехода к последующей части теста, необходимо сдать предыдущую часть</p>
								<p><b>После нажатия кнопки сдать тест, тест отправляется на проверку и изменению не подлежит!!!</b></p>
								<p>Для прохождения теста, рекомедуется ознакомиться с методическим материалом: <button class='btn btn-primary btn-new-href' href="{{route('teaching_materials')}}">Материалы</button></p>
								<p>При длительном бездействии, срабатывает защита и возникает ошибка <u>Page Expired</u>. В таком случае, необходимо нажать кнопку назад и обновить страницу</p>
							</div>
						</div>
					</div>
					<div class="grid_3 grid_5">
						<!--<h3 class="bars">Итоговая аттестация</h3>-->
						<div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
							<ul id="myTab" class="nav nav-tabs" role="tablist">
								<li role="presentation" class="active"><a href="#part1" id="part1-tab" role="tab" data-toggle="tab" aria-controls="part1" aria-expanded="true">{{$test->name_first_part}}</a></li>
								<li role="presentation"><a href="#part2" role="tab" id="part2-tab" data-toggle="tab" aria-controls="part2">{{$test->name_second_part}}</a></li>
								<li role="presentation"><a href="#part3" role="tab" id="part3-tab" data-toggle="tab" aria-controls="part3">{{$test->name_third_part}}</a></li>
								<li role="presentation"><a href="#part4" role="tab" id="part4-tab" data-toggle="tab" aria-controls="part4">{{$test->name_fourth_part}}</a></li>
							</ul>
							<div id="myTabContent" class="tab-content">
								<div role="tabpanel" class="tab-pane fade in active" id="part1" aria-labelledby="part1-tab">
									@if(is_null($schoolchildrenTest->first_part))
										<!-- Выводим тест -->
										<?php 
											$numberOfQuestion = 1;	//Номер вопроса
											$numberOfRadio = 1;		//Порядковый номер радио баттон
											$numberOfCheckbox = 1;	//Порядковый номер для чекбоксиков
										?>
										<form class='form-horizontal' method='POST' action="{{route('answer.save', ['id_schoolchildren'=>$schoolchildrenTest->id_schoolchildren,'id_test'=>$test->id])}}">
											{{ csrf_field() }}
											<input name='part' value='1' style='display: none;'/>
											@foreach($questions as $question)
												@if($question->part == 1)
													<h5>{{$numberOfQuestion++}}) {{$question->question}}</h5>
													@if(!is_null($question->description))
														<?php
															$check = 0;
															$arr = explode('~',$question->description);
															if($arr[0] == 'text')
																$check = 1;
															else if($arr[0] == 'img')
																$check = 2;
														?>
														@if($check == 1)
															<pre>{{$arr[1]}}</pre>
														@elseif($check == 2)
															<img src="{{asset('description/'.$arr[1].'')}}"/>
														@endif
													@endif
													@if($question->name == 'textarea')
														<div class="row">
															<div class="col-md-12">
																<textarea style='width: 100%;' rows=7 name='question{{$question->id}}' required></textarea>
															</div>
														</div>
													@elseif($question->name == 'input')
														<div class="row">
															<div class="col-md-12">
																<input type="text" class="form-control" name='question{{$question->id}}' required></input>
															</div>
														</div>
													@elseif($question->name == 'radio')
														<div class="row">
															<div class="col-md-12">
																@if(!is_null($question->first_answer))
																	<div class='row'>
																		<div class='col-xs-1 text-align-right'>
																			<input id='part{{$question->part}}Radio{{$numberOfRadio}}' type="radio" name='question{{$question->id}}' value='{{$question->first_answer}}' required>
																		</div>
																		<div class='col-xs-11'>
																			<label for='part{{$question->part}}Radio{{$numberOfRadio++}}'>{{$question->first_answer}}</label>
																		</div>
																	</div>
																@endif
																@if(!is_null($question->second_answer))
																	<div class='row'>
																		<div class='col-xs-1 text-align-right'>
																			<input id='part{{$question->part}}Radio{{$numberOfRadio}}' type="radio" name='question{{$question->id}}' value='{{$question->second_answer}}' required>
																		</div>
																		<div class='col-xs-11'>
																			<label for='part{{$question->part}}Radio{{$numberOfRadio++}}'>{{$question->second_answer}}</label>
																		</div>
																	</div>
																@endif
																@if(!is_null($question->third_answer))
																	<div class='row'>
																		<div class='col-xs-1 text-align-right'>
																			<input id='part{{$question->part}}Radio{{$numberOfRadio}}' type="radio" name='question{{$question->id}}' value='{{$question->third_answer}}' required>
																		</div>
																		<div class='col-xs-11'>
																			<label for='part{{$question->part}}Radio{{$numberOfRadio++}}'>{{$question->third_answer}}</label>
																		</div>
																	</div>
																@endif
																@if(!is_null($question->fourth_answer))
																	<div class='row'>
																		<div class='col-xs-1 text-align-right'>
																			<input id='part{{$question->part}}Radio{{$numberOfRadio}}' type="radio" name='question{{$question->id}}' value='{{$question->fourth_answer}}' required>
																		</div>
																		<div class='col-xs-11'>
																			<label for='part{{$question->part}}Radio{{$numberOfRadio++}}'>{{$question->fourth_answer}}</label>
																		</div>
																	</div>
																@endif
															</div>
														</div>
													@elseif($question->name == 'checkbox')
														<div class="row">
															<div class="col-md-12">
																@if(!is_null($question->first_answer))
																	<div class='row'>
																		<div class='col-xs-1 text-align-right'>
																			<input id='part{{$question->part}}Checkbox{{$numberOfCheckbox}}' type="checkbox" name='question{{$question->id}}[{{$numberOfCheckbox}}]' value='{{$question->first_answer}}'>
																		</div>
																		<div class='col-xs-11'>
																			<label for='part{{$question->part}}Checkbox{{$numberOfCheckbox++}}'>{{$question->first_answer}}</label>
																		</div>
																	</div>
																@endif
																@if(!is_null($question->second_answer))
																	<div class='row'>
																		<div class='col-xs-1 text-align-right'>
																			<input id='part{{$question->part}}Checkbox{{$numberOfCheckbox}}' type="checkbox" name='question{{$question->id}}[{{$numberOfCheckbox}}]' value='{{$question->second_answer}}'>
																		</div>
																		<div class='col-xs-11'>
																			<label for='part{{$question->part}}Checkbox{{$numberOfCheckbox++}}'>{{$question->second_answer}}</label>
																		</div>
																	</div>
																@endif
																@if(!is_null($question->third_answer))
																	<div class='row'>
																		<div class='col-xs-1 text-align-right'>
																			<input id='part{{$question->part}}Checkbox{{$numberOfCheckbox}}' type="checkbox" name='question{{$question->id}}[{{$numberOfCheckbox}}]' value='{{$question->third_answer}}'>
																		</div>
																		<div class='col-xs-11'>
																			<label for='part{{$question->part}}Checkbox{{$numberOfCheckbox++}}'>{{$question->third_answer}}</label>
																		</div>
																	</div>
																@endif
																@if(!is_null($question->fourth_answer))
																	<div class='row'>
																		<div class='col-xs-1 text-align-right'>
																			<input id='part{{$question->part}}Checkbox{{$numberOfCheckbox}}' type="checkbox" name='question{{$question->id}}[{{$numberOfCheckbox}}]' value='{{$question->fourth_answer}}'>
																		</div>
																		<div class='col-xs-11'>
																			<label for='part{{$question->part}}Checkbox{{$numberOfCheckbox++}}'>{{$question->fourth_answer}}</label>
																		</div>
																	</div>
																@endif
															</div>
														</div>
													@endif
												@endif
											@endforeach
											<br/>
											<button class='btn btn-primary form-control' type='submit'>Cдать тест</button>
										</form>
									@else
										@if($schoolchildrenTest->first_part == 0)
											<div class="alert alert-info">
												Тест отправлен на проверку!
											</div>
										@else
											@if($schoolchildrenTest->first_part < 0)
												<div class="alert alert-danger">
													Вы не сдали!
												</div>
											@elseif($schoolchildrenTest->first_part > 5)
												<div class="alert alert-success">
													Вы сдали!
												</div>
											@else
												<div class="alert alert-success">
													Вы сдали на {{$schoolchildrenTest->first_part}}!
												</div>
											@endif
										@endif
									@endif
								</div>
								<div role="tabpanel" class="tab-pane fade" id="part2" aria-labelledby="part2-tab">
									@if(is_null($schoolchildrenTest->first_part))
										<div class="alert alert-danger">
											Необходимо пройти первую часть!
										</div>
									@else
										@if(is_null($schoolchildrenTest->second_part))
											<!-- Выводим тест -->
											<?php 
												$numberOfQuestion = 1;	//Номер вопроса
												$numberOfRadio = 1;		//Порядковый номер радио баттон
												$numberOfCheckbox = 1;	//Порядковый номер для чекбоксиков
											?>
											<form class='form-horizontal' method='POST' action="{{route('answer.save', ['id_schoolchildren'=>$schoolchildrenTest->id_schoolchildren,'id_test'=>$test->id])}}">
												{{ csrf_field() }}
												<input name='part' value='2' style='display: none;'/>
												@foreach($questions as $question)
													@if($question->part == 2)
														<h5>{{$numberOfQuestion++}}) {{$question->question}}</h5>
														@if(!is_null($question->description))
															<?php
																$check = 0;
																$arr = explode('~',$question->description);
																if($arr[0] == 'text')
																	$check = 1;
																else if($arr[0] == 'img')
																	$check = 2;
															?>
															@if($check == 1)
																<pre>{{$arr[1]}}</pre>
															@elseif($check == 2)
																<img src="{{asset('description/'.$arr[1].'')}}"/>
															@endif
														@endif
														@if($question->name == 'textarea')
															<div class="row">
																<div class="col-md-12">
																	<textarea style='width: 100%;' rows=7 name='question{{$question->id}}' required></textarea>
																</div>
															</div>
														@elseif($question->name == 'input')
															<div class="row">
																<div class="col-md-12">
																	<input type="text" class="form-control" name='question{{$question->id}}' required></input>
																</div>
															</div>
														@elseif($question->name == 'radio')
															<div class="row">
																<div class="col-md-12">
																	@if(!is_null($question->first_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Radio{{$numberOfRadio}}' type="radio" name='question{{$question->id}}' value='{{$question->first_answer}}' required>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Radio{{$numberOfRadio++}}'>{{$question->first_answer}}</label>
																			</div>
																		</div>
																	@endif
																	@if(!is_null($question->second_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Radio{{$numberOfRadio}}' type="radio" name='question{{$question->id}}' value='{{$question->second_answer}}' required>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Radio{{$numberOfRadio++}}'>{{$question->second_answer}}</label>
																			</div>
																		</div>
																	@endif
																	@if(!is_null($question->third_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Radio{{$numberOfRadio}}' type="radio" name='question{{$question->id}}' value='{{$question->third_answer}}' required>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Radio{{$numberOfRadio++}}'>{{$question->third_answer}}</label>
																			</div>
																		</div>
																	@endif
																	@if(!is_null($question->fourth_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Radio{{$numberOfRadio}}' type="radio" name='question{{$question->id}}' value='{{$question->fourth_answer}}' required>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Radio{{$numberOfRadio++}}'>{{$question->fourth_answer}}</label>
																			</div>
																		</div>
																	@endif
																</div>
															</div>
														@elseif($question->name == 'checkbox')
															<div class="row">
																<div class="col-md-12">
																	@if(!is_null($question->first_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Checkbox{{$numberOfCheckbox}}' type="checkbox" name='question{{$question->id}}[{{$numberOfCheckbox}}]' value='{{$question->first_answer}}'>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Checkbox{{$numberOfCheckbox++}}'>{{$question->first_answer}}</label>
																			</div>
																		</div>
																	@endif
																	@if(!is_null($question->second_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Checkbox{{$numberOfCheckbox}}' type="checkbox" name='question{{$question->id}}[{{$numberOfCheckbox}}]' value='{{$question->second_answer}}'>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Checkbox{{$numberOfCheckbox++}}'>{{$question->second_answer}}</label>
																			</div>
																		</div>
																	@endif
																	@if(!is_null($question->third_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Checkbox{{$numberOfCheckbox}}' type="checkbox" name='question{{$question->id}}[{{$numberOfCheckbox}}]' value='{{$question->third_answer}}'>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Checkbox{{$numberOfCheckbox++}}'>{{$question->third_answer}}</label>
																			</div>
																		</div>
																	@endif
																	@if(!is_null($question->fourth_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Checkbox{{$numberOfCheckbox}}' type="checkbox" name='question{{$question->id}}[{{$numberOfCheckbox}}]' value='{{$question->fourth_answer}}'>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Checkbox{{$numberOfCheckbox++}}'>{{$question->fourth_answer}}</label>
																			</div>
																		</div>
																	@endif
																</div>
															</div>
														@endif
													@endif
												@endforeach
												<br/>
												<button class='btn btn-primary form-control' type='submit'>Cдать тест</button>
											</form>
										@else
											@if($schoolchildrenTest->second_part == 0)
												<div class="alert alert-info">
													Тест отправлен на проверку!
												</div>
											@else
												<div class="alert alert-success">
													Вы сдали!
												</div>
											@endif
										@endif
									@endif
								</div>
								<div role="tabpanel" class="tab-pane fade" id="part3" aria-labelledby="part3-tab">
									@if(is_null($schoolchildrenTest->second_part))
										<div class="alert alert-danger">
											Необходимо пройти вторую часть!
										</div>
									@else
										@if(is_null($schoolchildrenTest->third_part))
											<!-- Выводим тест -->
											<?php 
												$numberOfQuestion = 1;	//Номер вопроса
												$numberOfRadio = 1;		//Порядковый номер радио баттон
												$numberOfCheckbox = 1;	//Порядковый номер для чекбоксиков
											?>
											<form class='form-horizontal' method='POST' action="{{route('answer.save', ['id_schoolchildren'=>$schoolchildrenTest->id_schoolchildren,'id_test'=>$test->id])}}">
												{{ csrf_field() }}
												<input name='part' value='3' style='display: none;'/>
												@foreach($questions as $question)
													@if($question->part == 3)
														<h5>{{$numberOfQuestion++}}) {{$question->question}}</h5>
														@if(!is_null($question->description))
															<?php
																$check = 0;
																$arr = explode('~',$question->description);
																if($arr[0] == 'text')
																	$check = 1;
																else if($arr[0] == 'img')
																	$check = 2;
															?>
															@if($check == 1)
																<pre>{{$arr[1]}}</pre>
															@elseif($check == 2)
																<img src="{{asset('description/'.$arr[1].'')}}"/>
															@endif
														@endif
														@if($question->name == 'textarea')
															<div class="row">
																<div class="col-md-12">
																	<textarea style='width: 100%;' rows=7 name='question{{$question->id}}' required></textarea>
																</div>
															</div>
														@elseif($question->name == 'input')
															<div class="row">
																<div class="col-md-12">
																	<input type="text" class="form-control" name='question{{$question->id}}' required></input>
																</div>
															</div>
														@elseif($question->name == 'radio')
															<div class="row">
																<div class="col-md-12">
																	@if(!is_null($question->first_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Radio{{$numberOfRadio}}' type="radio" name='question{{$question->id}}' value='{{$question->first_answer}}' required>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Radio{{$numberOfRadio++}}'>{{$question->first_answer}}</label>
																			</div>
																		</div>
																	@endif
																	@if(!is_null($question->second_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Radio{{$numberOfRadio}}' type="radio" name='question{{$question->id}}' value='{{$question->second_answer}}' required>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Radio{{$numberOfRadio++}}'>{{$question->second_answer}}</label>
																			</div>
																		</div>
																	@endif
																	@if(!is_null($question->third_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Radio{{$numberOfRadio}}' type="radio" name='question{{$question->id}}' value='{{$question->third_answer}}' required>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Radio{{$numberOfRadio++}}'>{{$question->third_answer}}</label>
																			</div>
																		</div>
																	@endif
																	@if(!is_null($question->fourth_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Radio{{$numberOfRadio}}' type="radio" name='question{{$question->id}}' value='{{$question->fourth_answer}}' required>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Radio{{$numberOfRadio++}}'>{{$question->fourth_answer}}</label>
																			</div>
																		</div>
																	@endif
																</div>
															</div>
														@elseif($question->name == 'checkbox')
															<div class="row">
																<div class="col-md-12">
																	@if(!is_null($question->first_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Checkbox{{$numberOfCheckbox}}' type="checkbox" name='question{{$question->id}}[{{$numberOfCheckbox}}]' value='{{$question->first_answer}}'>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Checkbox{{$numberOfCheckbox++}}'>{{$question->first_answer}}</label>
																			</div>
																		</div>
																	@endif
																	@if(!is_null($question->second_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Checkbox{{$numberOfCheckbox}}' type="checkbox" name='question{{$question->id}}[{{$numberOfCheckbox}}]' value='{{$question->second_answer}}'>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Checkbox{{$numberOfCheckbox++}}'>{{$question->second_answer}}</label>
																			</div>
																		</div>
																	@endif
																	@if(!is_null($question->third_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Checkbox{{$numberOfCheckbox}}' type="checkbox" name='question{{$question->id}}[{{$numberOfCheckbox}}]' value='{{$question->third_answer}}'>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Checkbox{{$numberOfCheckbox++}}'>{{$question->third_answer}}</label>
																			</div>
																		</div>
																	@endif
																	@if(!is_null($question->fourth_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Checkbox{{$numberOfCheckbox}}' type="checkbox" name='question{{$question->id}}[{{$numberOfCheckbox}}]' value='{{$question->fourth_answer}}'>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Checkbox{{$numberOfCheckbox++}}'>{{$question->fourth_answer}}</label>
																			</div>
																		</div>
																	@endif
																</div>
															</div>
														@endif
													@endif
												@endforeach
												<br/>
												<button class='btn btn-primary form-control' type='submit'>Cдать тест</button>
											</form>
										@else
											@if($schoolchildrenTest->third_part == 0)
												<div class="alert alert-info">
													Тест отправлен на проверку!
												</div>
											@else
												<div class="alert alert-success">
													Вы сдали!
												</div>
											@endif
										@endif
									@endif
								</div>
								<div role="tabpanel" class="tab-pane fade" id="part4" aria-labelledby="part4-tab">
									@if(is_null($schoolchildrenTest->third_part))
										<div class="alert alert-danger">
											Необходимо пройти третью часть!
										</div>
									@else
										@if(is_null($schoolchildrenTest->fourth_part))
											<!-- Выводим тест -->
											<?php 
												$numberOfQuestion = 1;	//Номер вопроса
												$numberOfRadio = 1;		//Порядковый номер радио баттон
												$numberOfCheckbox = 1;	//Порядковый номер для чекбоксиков
											?>
											<form class='form-horizontal' method='POST' action="{{route('answer.save', ['id_schoolchildren'=>$schoolchildrenTest->id_schoolchildren,'id_test'=>$test->id])}}">
												{{ csrf_field() }}
												<input name='part' value='4' style='display: none;'/>
												@foreach($questions as $question)
													@if($question->part == 4)
														<h5>{{$numberOfQuestion++}}) {{$question->question}}</h5>
														@if(!is_null($question->description))
															<?php
																$check = 0;
																$arr = explode('~',$question->description);
																if($arr[0] == 'text')
																	$check = 1;
																else if($arr[0] == 'img')
																	$check = 2;
															?>
															@if($check == 1)
																<pre>{{$arr[1]}}</pre>
															@elseif($check == 2)
																<img src="{{asset('description/'.$arr[1].'')}}"/>
															@endif
														@endif
														@if($question->name == 'textarea')
															<div class="row">
																<div class="col-md-12">
																	<textarea style='width: 100%;' rows=7 name='question{{$question->id}}' required></textarea>
																</div>
															</div>
														@elseif($question->name == 'input')
															<div class="row">
																<div class="col-md-12">
																	<input type="text" class="form-control" name='question{{$question->id}}' required></input>
																</div>
															</div>
														@elseif($question->name == 'radio')
															<div class="row">
																<div class="col-md-12">
																	@if(!is_null($question->first_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Radio{{$numberOfRadio}}' type="radio" name='question{{$question->id}}' value='{{$question->first_answer}}' required>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Radio{{$numberOfRadio++}}'>{{$question->first_answer}}</label>
																			</div>
																		</div>
																	@endif
																	@if(!is_null($question->second_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Radio{{$numberOfRadio}}' type="radio" name='question{{$question->id}}' value='{{$question->second_answer}}' required>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Radio{{$numberOfRadio++}}'>{{$question->second_answer}}</label>
																			</div>
																		</div>
																	@endif
																	@if(!is_null($question->third_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Radio{{$numberOfRadio}}' type="radio" name='question{{$question->id}}' value='{{$question->third_answer}}' required>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Radio{{$numberOfRadio++}}'>{{$question->third_answer}}</label>
																			</div>
																		</div>
																	@endif
																	@if(!is_null($question->fourth_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Radio{{$numberOfRadio}}' type="radio" name='question{{$question->id}}' value='{{$question->fourth_answer}}' required>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Radio{{$numberOfRadio++}}'>{{$question->fourth_answer}}</label>
																			</div>
																		</div>
																	@endif
																</div>
															</div>
														@elseif($question->name == 'checkbox')
															<div class="row">
																<div class="col-md-12">
																	@if(!is_null($question->first_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Checkbox{{$numberOfCheckbox}}' type="checkbox" name='question{{$question->id}}[{{$numberOfCheckbox}}]' value='{{$question->first_answer}}'>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Checkbox{{$numberOfCheckbox++}}'>{{$question->first_answer}}</label>
																			</div>
																		</div>
																	@endif
																	@if(!is_null($question->second_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Checkbox{{$numberOfCheckbox}}' type="checkbox" name='question{{$question->id}}[{{$numberOfCheckbox}}]' value='{{$question->second_answer}}'>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Checkbox{{$numberOfCheckbox++}}'>{{$question->second_answer}}</label>
																			</div>
																		</div>
																	@endif
																	@if(!is_null($question->third_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Checkbox{{$numberOfCheckbox}}' type="checkbox" name='question{{$question->id}}[{{$numberOfCheckbox}}]' value='{{$question->third_answer}}'>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Checkbox{{$numberOfCheckbox++}}'>{{$question->third_answer}}</label>
																			</div>
																		</div>
																	@endif
																	@if(!is_null($question->fourth_answer))
																		<div class='row'>
																			<div class='col-xs-1 text-align-right'>
																				<input id='part{{$question->part}}Checkbox{{$numberOfCheckbox}}' type="checkbox" name='question{{$question->id}}[{{$numberOfCheckbox}}]' value='{{$question->fourth_answer}}'>
																			</div>
																			<div class='col-xs-11'>
																				<label for='part{{$question->part}}Checkbox{{$numberOfCheckbox++}}'>{{$question->fourth_answer}}</label>
																			</div>
																		</div>
																	@endif
																</div>
															</div>
														@endif
													@endif
												@endforeach
												<br/>
												<button class='btn btn-primary form-control' type='submit'>Cдать тест</button>
											</form>
										@else
											@if($schoolchildrenTest->fourth_part == 0)
												<div class="alert alert-info">
													Тест отправлен на проверку!
												</div>
											@else
												<div class="alert alert-success">
													Вы сдали!
												</div>
											@endif
										@endif
									@endif
								</div>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
				@else
					<div class="alert alert-danger">
						Страница нужна ученикам!
					</div>
				@endif
			@else
				<div class="alert alert-danger">
					Необходимо авторизоваться!
				</div>
			@endif
		</diV>
	</div>
@endsection
