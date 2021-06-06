@extends('layouts.header')

@section('title')
	Граф заявки
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Администрация')
				<div id='content' class="content">
					<div class='row'>
						<div class='col-sm-3'>
							@if($my_parents)
								<div class="row">
									<div class='col-sm-12'>
										<label>Родительские заявки</label>
									</div>
								</div>
								<div class="row">
									<div class='col-sm-12'>
										<div class="row">
											@foreach($my_parents as $parent)
												<div class='col-sm-2' style='text-align: center;'>
													<div class='row'>
														<div class='col-sm-12'>
															<img class='btn-href cursorPointer' src="{{ asset('css/images/bd_sbrowse.png') }}" style='width: 32px;' href='{{ route("department.reconciliation.document", $parent[0]->number_application) }}'/>
														</div>
													</div>
													<div class='row'>
														<div class='col-sm-12'>
															<label>↓</label>
														</div>
													</div>
												</div>
											@endforeach
										</div>
									</div>
								</div>
							@endif
							<div class="row">
								<div class='col-sm-12'>
									<label>Текущая заявка</label>
								</div>
							</div>
							<div class="row">
								<div class='col-sm-6' style='text-align: center;'>
									<img id='main_application' class='btn-href cursorPointer' src="{{ asset('css/images/bd_sbrowse.png') }}" style='width: 32px;' href='{{ route("department.reconciliation.document", $documents[0]->number_application) }}'/>
								</div>
							</div>
							@if($my_childs)
								<div class="row">
									<div class='col-sm-12'>
										<div class="row">
											@foreach($my_childs as $child)
												<div class='col-sm-2' style='text-align: center;'>
													<div class='row'>
														<div class='col-sm-12'>
															<label>↓</label>
														</div>
													</div>
													<div class='row'>
														<div class='col-sm-12'>
															<img class='btn-href cursorPointer' src="{{ asset('css/images/bd_sbrowse.png') }}" style='width: 32px;' href='{{ route("department.reconciliation.document", $child[0]->number_application) }}'/>
														</div>
													</div>
												</div>
											@endforeach
										</div>
									</div>
								</div>
								<div class="row">
									<div class='col-sm-12'>
										<label>Дочерние заявки</label>
									</div>
								</div>
							@endif
						</div>
						<div class='col-sm-1'>
							
						</div>
						<div class='col-sm-6'>
							<div class="row">
								<div class='col-sm-12'>
									<label>Письма</label>
								</div>
							</div>
							<div id='applications' class="row" style='border: solid 1.5px; padding: 5px;'>
								<div class='col-sm-12'>
									<div class="row">
										<div class='col-sm-2'>
											<div class='row'>
												<div class='col-sm-12'>
													<img class='btn-href cursorPointer' src="{{ asset('css/images/bd_sbrowse.png') }}" style='width: 32px;' href='{{route("reconciliation.application.show", $documents[0]->appID)}}'/>
												</div>
											</div>
											<div class='row'>
												<div class='col-sm-12'>
													<p>Исх.{{$documents[0]->number_outgoing}}</p>
												</div>
											</div>
										</div>
										@foreach($my_applications as $application)
											<div class='col-sm-2'>
												<div class='row'>
													<div class='col-sm-12'>
														<img class='btn-href cursorPointer' src="{{ asset('css/images/bd_sbrowse.png') }}" style='width: 32px;' href='{{route("reconciliation.application.show", $application->id)}}'/>
													</div>
												</div>
												<div class='row'>
													<div class='col-sm-12'>
														<p>Исх.{{$application->number_outgoing}}</p>
													</div>
												</div>
											</div>
										@endforeach
									</div>
								</div>
							</div>
							<!--<hr style='border-top: 1px solid #030303;height: 10px;-webkit-transform: rotate(-11deg);position: absolute;left: -495px;top: 85px;line-height: 1px;width: 487px;'/>
							<hr style='border-top: 1px solid #030303;height: 10px;position: absolute;left: -495px;top: 147px;line-height: 1px;width: 487px;'/>-->
							<div class="row">
								<div class='col-sm-12'>
									<label>Договоры</label>
								</div>
							</div>
							<div id='contracts' class="row" style='border: solid 1.5px; padding: 5px;'>
								<div class='col-sm-12'>
									<div class="row">
										@foreach($result as $key=>$value)
											@foreach($value as $key2=>$value2)
												<div class='col-sm-2' style='border: solid 1px; margin-right: 5px; margin-top: 5px; padding-bottom: 5px;'>
													<!-- Договор -->
													<div class='row'>
														<div class='col-sm-12'>
															<label>Договор</label>
															<label>№ {{$value2->number_contract}}</label>
														</div>
														<div class='col-sm-12'>
															<img class='btn-href cursorPointer' src="{{ asset('css/images/bd_sbrowse.png') }}" style='width: 32px;' href="{{ route('department.reconciliation.show', $value2->id)}}"/>
														</div>
													</div>
													<!-- Письма -->
													<div class='row'>
														@if(count($value2->applications_in_contract) > 0)
															<div class='col-sm-12'>
																<label>Письма</label>
															</div>
															@foreach($value2->applications_in_contract as $application_in_contract)
																<div class='col-sm-12'>
																	<img class='btn-href cursorPointer' src="{{ asset('css/images/bd_sbrowse.png') }}" style='width: 32px;' href="{{ route('reconciliation.application.show', $application_in_contract->id)}}"/>
																</div>
																<div class='col-sm-12'>
																	<p>{{$application_in_contract->number_outgoing}}</p>
																</div>
															@endforeach
														@endif
													</div>
													<!-- Протоколы -->
													<div class='row'>
														@if(count($value2->protocols_in_contract) > 0)
															<div class='col-sm-12'>
																<label>Протоколы</label>
															</div>
															@foreach($value2->protocols_in_contract as $protocol_in_contract)
																<div class='col-sm-12'>
																	<img class='btn-href cursorPointer' src="{{ asset('css/images/bd_sbrowse.png') }}" style='width: 32px;' href="{{ route('reconciliation.application.show', $protocol_in_contract->id)}}"/>
																</div>
																<div class='col-sm-12'>
																	<p>{{$protocol_in_contract->number_outgoing}}</p>
																</div>
															@endforeach
														@endif
													</div>
												</div>
											@endforeach
										@endforeach
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<script>
					function createTree(){
						var xBegin = $('#main_application').offset().left + $('#main_application').width() + 4;
						var yBegin = $('#main_application').offset().top - 27;
						var xEnd = $('#applications').offset().left - 4;
						var yEnd = $('#applications').offset().top - 7;
						var length = Math.sqrt(((xEnd - xBegin) * (xEnd - xBegin)) + ((yEnd - yBegin) * (yEnd - yBegin)));
						var cx = ((xBegin + xEnd) / 2) - (length / 2); 
						var cy = ((yBegin + yEnd) / 2) - 2;
						var angle = Math.atan2((yBegin - yEnd), (xBegin - xEnd))*(180/Math.PI);
						var htmlLine = "<hr class='hrLine' style='height: 3px; position: absolute; left:" + cx + "px; top:" + cy + "px; width:" + length + "px; -webkit-transform:rotate(" + angle + "deg);border-top: 1px solid black;' />";
						$('#content').append(htmlLine);
						xBegin = $('#main_application').offset().left + $('#main_application').width() + 4;
						yBegin = $('#main_application').offset().top - 9;
						xEnd = $('#contracts').offset().left - 4;
						yEnd = $('#contracts').offset().top - 7;
						length = Math.sqrt(((xEnd - xBegin) * (xEnd - xBegin)) + ((yEnd - yBegin) * (yEnd - yBegin)));
						cx = ((xBegin + xEnd) / 2) - (length / 2); 
						cy = ((yBegin + yEnd) / 2) - 2;
						angle = Math.atan2((yBegin - yEnd), (xBegin - xEnd))*(180/Math.PI);
						htmlLine = "<hr class='hrLine' style='height: 3px; position: absolute; left:" + cx + "px; top:" + cy + "px; width:" + length + "px; -webkit-transform:rotate(" + angle + "deg);border-top: 1px solid black;' />";
						$('#content').append(htmlLine);
						//alert(xBegin);
					};
					createTree();
					$(window).resize(function(){
						$('.hrLine').each(function(){
							$(this).remove();
						});
						createTree();
					});
				</script>
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
