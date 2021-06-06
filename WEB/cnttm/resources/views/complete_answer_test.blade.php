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
				@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Преподаватель')
					<div class="content">
						<div class="row">
							<div class="col-md-12">
								<h3>Тест: {{$test->name}}</h3>
							</div>
						</div>
					</div>
					<div class="grid_3 grid_5">
						<h3 class="bars">Итоговая аттестация</h3>
						<div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
							<ul id="myTab" class="nav nav-tabs" role="tablist">
								<li role="presentation" class="active"><a href="#part1" id="part1-tab" role="tab" data-toggle="tab" aria-controls="part1" aria-expanded="true">{{$test->name_first_part}}</a></li>
								<li role="presentation"><a href="#part2" role="tab" id="part2-tab" data-toggle="tab" aria-controls="part2">{{$test->name_second_part}}</a></li>
								<li role="presentation"><a href="#part3" role="tab" id="part3-tab" data-toggle="tab" aria-controls="part3">{{$test->name_third_part}}</a></li>
								<li role="presentation"><a href="#part4" role="tab" id="part4-tab" data-toggle="tab" aria-controls="part4">{{$test->name_fourth_part}}</a></li>
							</ul>
							<div id="myTabContent" class="tab-content">
								<div role="tabpanel" class="tab-pane fade in active" id="part1" aria-labelledby="part1-tab">
									<!-- Выводим тест -->
									<?php 
										$numberOfQuestion = 1;
										$numberOfRadio = 1;
										$all_question = 0;
										$access_question = 0;
									?>
									<form class='form-horizontal' method='POST' action="{{route('administration.answer.save', ['id_schoolchildren'=>$id_schoolchildren,'id_test'=>$test->id])}}">
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
															
														</div>
													</div>
												@elseif($question->name == 'input')
													<div class="row">
														<div class="col-md-12">
															
														</div>
													</div>
												@elseif($question->name == 'radio')
													<div class="row">
														<div class="col-md-12">
															<p><input type="radio"><label>{{$question->first_answer}}</label></p>
															<p><input type="radio"><label>{{$question->second_answer}}</label></p>
															<p><input type="radio"><label>{{$question->third_answer}}</label></p>
															<p><input type="radio"><label>{{$question->fourth_answer}}</label></p>
														</div>
													</div>
												@elseif($question->name == 'checkbox')
													<div class="row">
														<div class="col-md-12">
															<p><input type="checkbox"><label>{{$question->first_answer}}</label></p>
															<p><input type="checkbox"><label>{{$question->second_answer}}</label></p>
															<p><input type="checkbox"><label>{{$question->third_answer}}</label></p>
															<p><input type="checkbox"><label>{{$question->fourth_answer}}</label></p>
														</div>
													</div>
												@endif
												@if(!is_null($question->answers))
													<div class="row">
														<div class='col-md-6'>
															<h5 style='color: red;'>Ответ пользователя: <pre>{{$question->answers->answer}}</pre></h5>
														</div>
														<div class='col-md-6'>
															<h5 style='color: green;'>Правильный ответ: <pre>{{$question->finally_answer}}</pre></h5>
														</div>
													</div>
													<div class="row">
														<div class="col-md-12">
															<p><input id='part{{$question->part}}radio{{$numberOfRadio}}' class='answerYesSelect' for_question='accessQuestion1' type="radio" name='answer{{$question->answers->id}}' value='1' required <?php if($question->answers->check_question == 1) {echo 'checked';$access_question++;} ?>><label for='part{{$question->part}}radio{{$numberOfRadio++}}' style='color:green;'>Верно</label></p>
															<p><input id='part{{$question->part}}radio{{$numberOfRadio}}' class='answerNoneSelect' for_question='accessQuestion1' type="radio" name='answer{{$question->answers->id}}' value='0' required <?php if($question->answers->check_question == 0 && !is_null($question->answers->check_question)) echo 'checked'; ?>><label for='part{{$question->part}}radio{{$numberOfRadio++}}' style='color:red;'>Неверно</label></p>
														</div>
													</div>
												@else
													<h5 style='color: red;'>Ответ ещё не получен!</h5>
												@endif
												<?php $all_question++; ?>
											@endif
										@endforeach
										<label>Результат по вопросам: <span id='accessQuestion1' value='{{$access_question}}'>{{$access_question}}</span> из {{$all_question}}</label><br/><br/>
										<label>Введите общий бал (<0, если не сдал без оценки; >5, если сдал без оценки; от 1 до 5, если сдал на текущую оценку):</label>
										<input class='form-control' name='complete_ball' required value='{{$schoolchildrenTest->first_part}}'></input>
										<br/>
										<button class='btn btn-primary form-control' type='submit'>Проверено</button>
									</form>
								</div>
								<div role="tabpanel" class="tab-pane fade" id="part2" aria-labelledby="part2-tab">
									<!-- Выводим тест -->
									<?php 
										$numberOfQuestion = 1;	//Номер вопроса
										$numberOfRadio = 1;		//Порядковый номер радио баттон
										$all_question = 0;
										$access_question = 0;
									?>
									<form class='form-horizontal' method='POST' action="{{route('administration.answer.save', ['id_schoolchildren'=>$id_schoolchildren,'id_test'=>$test->id])}}">
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
															
														</div>
													</div>
												@elseif($question->name == 'input')
													<div class="row">
														<div class="col-md-12">
															
														</div>
													</div>
												@elseif($question->name == 'radio')
													<div class="row">
														<div class="col-md-12">
															<p><input type="radio"><label>{{$question->first_answer}}</label></p>
															<p><input type="radio"><label>{{$question->second_answer}}</label></p>
															<p><input type="radio"><label>{{$question->third_answer}}</label></p>
															<p><input type="radio"><label>{{$question->fourth_answer}}</label></p>
														</div>
													</div>
												@elseif($question->name == 'checkbox')
													<div class="row">
														<div class="col-md-12">
															<p><input type="checkbox"><label>{{$question->first_answer}}</label></p>
															<p><input type="checkbox"><label>{{$question->second_answer}}</label></p>
															<p><input type="checkbox"><label>{{$question->third_answer}}</label></p>
															<p><input type="checkbox"><label>{{$question->fourth_answer}}</label></p>
														</div>
													</div>
												@endif
												@if(!is_null($question->answers))
													<div class="row">
														<div class='col-md-6'>
															<h5 style='color: red;'>Ответ пользователя: <pre>{{$question->answers->answer}}</pre></h5>
														</div>
														<div class='col-md-6'>
															<h5 style='color: green;'>Правильный ответ: <pre>{{$question->finally_answer}}</pre></h5>
														</div>
													</div>
													<div class="row">
														<div class="col-md-12">
															<p><input id='part{{$question->part}}radio{{$numberOfRadio}}' class='answerYesSelect' for_question='accessQuestion2' type="radio" name='answer{{$question->answers->id}}' value='1' required <?php if($question->answers->check_question == 1) {echo 'checked';$access_question++;} ?>><label for='part{{$question->part}}radio{{$numberOfRadio++}}' style='color:green;'>Верно</label></p>
															<p><input id='part{{$question->part}}radio{{$numberOfRadio}}' class='answerNoneSelect' for_question='accessQuestion2' type="radio" name='answer{{$question->answers->id}}' value='0' required <?php if($question->answers->check_question == 0 && !is_null($question->answers->check_question)) echo 'checked'; ?>><label for='part{{$question->part}}radio{{$numberOfRadio++}}' style='color:red;'>Неверно</label></p>
														</div>
													</div>
												@else
													<h5 style='color: red;'>Ответ ещё не получен!</h5>
												@endif
												<?php $all_question++; ?>
											@endif
										@endforeach
										<label>Результат по вопросам: <span id='accessQuestion2' value='{{$access_question}}'>{{$access_question}}</span> из {{$all_question}}</label><br/><br/>
										<label>Введите общий бал (<0, если не сдал без оценки; >5, если сдал без оценки; от 1 до 5, если сдал на текущую оценку):</label>
										<input class='form-control' name='complete_ball' required value='{{$schoolchildrenTest->second_part}}'></input>
										<br/>
										<button class='btn btn-primary form-control' type='submit'>Проверено</button>
									</form>
								</div>
								<div role="tabpanel" class="tab-pane fade" id="part3" aria-labelledby="part3-tab">
									<!-- Выводим тест -->
									<?php 
										$numberOfQuestion = 1;	//Номер вопроса
										$numberOfRadio = 1;		//Порядковый номер радио баттон
										$all_question = 0;
										$access_question = 0;
									?>
									<form class='form-horizontal' method='POST' action="{{route('administration.answer.save', ['id_schoolchildren'=>$id_schoolchildren,'id_test'=>$test->id])}}">
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
															
														</div>
													</div>
												@elseif($question->name == 'input')
													<div class="row">
														<div class="col-md-12">
															
														</div>
													</div>
												@elseif($question->name == 'radio')
													<div class="row">
														<div class="col-md-12">
															<p><input type="radio"><label>{{$question->first_answer}}</label></p>
															<p><input type="radio"><label>{{$question->second_answer}}</label></p>
															<p><input type="radio"><label>{{$question->third_answer}}</label></p>
															<p><input type="radio"><label>{{$question->fourth_answer}}</label></p>
														</div>
													</div>
												@elseif($question->name == 'checkbox')
													<div class="row">
														<div class="col-md-12">
															<p><input type="checkbox"><label>{{$question->first_answer}}</label></p>
															<p><input type="checkbox"><label>{{$question->second_answer}}</label></p>
															<p><input type="checkbox"><label>{{$question->third_answer}}</label></p>
															<p><input type="checkbox"><label>{{$question->fourth_answer}}</label></p>
														</div>
													</div>
												@endif
												@if(!is_null($question->answers))
													<div class="row">
														<div class='col-md-6'>
															<h5 style='color: red;'>Ответ пользователя: <pre>{{$question->answers->answer}}</pre></h5>
														</div>
														<div class='col-md-6'>
															<h5 style='color: green;'>Правильный ответ: <pre>{{$question->finally_answer}}</pre></h5>
														</div>
													</div>
													<div class="row">
														<div class="col-md-12">
															<p><input id='part{{$question->part}}radio{{$numberOfRadio}}' class='answerYesSelect' for_question='accessQuestion3' type="radio" name='answer{{$question->answers->id}}' value='1' required <?php if($question->answers->check_question == 1) {echo 'checked';$access_question++;} ?>><label for='part{{$question->part}}radio{{$numberOfRadio++}}' style='color:green;'>Верно</label></p>
															<p><input id='part{{$question->part}}radio{{$numberOfRadio}}' class='answerNoneSelect' for_question='accessQuestion3' type="radio" name='answer{{$question->answers->id}}' value='0' required <?php if($question->answers->check_question == 0 && !is_null($question->answers->check_question)) echo 'checked'; ?>><label for='part{{$question->part}}radio{{$numberOfRadio++}}' style='color:red;'>Неверно</label></p>
														</div>
													</div>
												@else
													<h5 style='color: red;'>Ответ ещё не получен!</h5>
												@endif
												<?php $all_question++; ?>
											@endif
										@endforeach
										<label>Результат по вопросам: <span id='accessQuestion3' value='{{$access_question}}'>{{$access_question}}</span> из {{$all_question}}</label><br/><br/>
										<label>Введите общий бал (<0, если не сдал без оценки; >5, если сдал без оценки; от 1 до 5, если сдал на текущую оценку):</label>
										<input class='form-control' name='complete_ball' required value='{{$schoolchildrenTest->third_part}}'></input>
										<br/>
										<button class='btn btn-primary form-control' type='submit'>Проверено</button>
									</form>
								</div>
								<div role="tabpanel" class="tab-pane fade" id="part4" aria-labelledby="part4-tab">
									<!-- Выводим тест -->
									<?php 
										$numberOfQuestion = 1;	//Номер вопроса
										$numberOfRadio = 1;		//Порядковый номер радио баттон
										$all_question = 0;
										$access_question = 0;
									?>
									<form class='form-horizontal' method='POST' action="{{route('administration.answer.save', ['id_schoolchildren'=>$id_schoolchildren,'id_test'=>$test->id])}}">
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
															
														</div>
													</div>
												@elseif($question->name == 'input')
													<div class="row">
														<div class="col-md-12">
															
														</div>
													</div>
												@elseif($question->name == 'radio')
													<div class="row">
														<div class="col-md-12">
															<p><input type="radio"><label>{{$question->first_answer}}</label></p>
															<p><input type="radio"><label>{{$question->second_answer}}</label></p>
															<p><input type="radio"><label>{{$question->third_answer}}</label></p>
															<p><input type="radio"><label>{{$question->fourth_answer}}</label></p>
														</div>
													</div>
												@elseif($question->name == 'checkbox')
													<div class="row">
														<div class="col-md-12">
															<p><input type="checkbox"><label>{{$question->first_answer}}</label></p>
															<p><input type="checkbox"><label>{{$question->second_answer}}</label></p>
															<p><input type="checkbox"><label>{{$question->third_answer}}</label></p>
															<p><input type="checkbox"><label>{{$question->fourth_answer}}</label></p>
														</div>
													</div>
												@endif
												@if(!is_null($question->answers))
													<div class="row">
														<div class='col-md-6'>
															<h5 style='color: red;'>Ответ пользователя: <pre>{{$question->answers->answer}}</pre></h5>
														</div>
														<div class='col-md-6'>
															<h5 style='color: green;'>Правильный ответ: <pre>{{$question->finally_answer}}</pre></h5>
														</div>
													</div>
													<div class="row">
														<div class="col-md-12">
															<p><input id='part{{$question->part}}radio{{$numberOfRadio}}' class='answerYesSelect' for_question='accessQuestion4' type="radio" name='answer{{$question->answers->id}}' value='1' required <?php if($question->answers->check_question == 1) {echo 'checked';$access_question++;} ?>><label for='part{{$question->part}}radio{{$numberOfRadio++}}' style='color:green;'>Верно</label></p>
															<p><input id='part{{$question->part}}radio{{$numberOfRadio}}' class='answerNoneSelect' for_question='accessQuestion4' type="radio" name='answer{{$question->answers->id}}' value='0' required <?php if($question->answers->check_question == 0 && !is_null($question->answers->check_question)) echo 'checked'; ?>><label for='part{{$question->part}}radio{{$numberOfRadio++}}' style='color:red;'>Неверно</label></p>
														</div>
													</div>
												@else
													<h5 style='color: red;'>Ответ ещё не получен!</h5>
												@endif
												<?php $all_question++; ?>
											@endif
										@endforeach
										<label>Результат по вопросам: <span id='accessQuestion4' value='{{$access_question}}'>{{$access_question}}</span> из {{$all_question}}</label><br/><br/>
										<label>Введите общий бал (<0, если не сдал без оценки; >5, если сдал без оценки; от 1 до 5, если сдал на текущую оценку):</label>
										<input class='form-control' name='complete_ball' required value='{{$schoolchildrenTest->fourth_part}}'></input>
										<br/>
										<button class='btn btn-primary form-control' type='submit'>Проверено</button>
									</form>
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
