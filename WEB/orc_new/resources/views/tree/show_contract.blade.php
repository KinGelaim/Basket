@extends('layouts.header')

@section('title')
	Граф договора
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div id='content' class="content">
				<div class='row'>
					<div class='col-sm-3'>
						<div class="row">
							<div class='col-sm-12'>
								<label>Текущий договор</label>
							</div>
						</div>
						<div class="row">
							<div class='col-sm-6' style='text-align: center;'>
								<img id='main_contract' class='cursorPointer btn-href' src="{{ asset('css/images/bd_sbrowse.png') }}" style='width: 32px;' href="{{route('department.ekonomic.contract_new_reestr', $contract->id)}}"/>
							</div>
						</div>
						<div class='row'>
							<div class='col-sm-12'>
								<p>№{{$contract->number_contract}}</p>
							</div>
						</div>
						<div class='row'>
							<div class='col-sm-12'>
								<button class='btn btn-primary' data-toggle="modal" data-target="#scan" type='button' style='width: 184px;'>Оригиналы</button>
							</div>
						</div>
					</div>
					<div class='col-sm-1'>
						
					</div>
					<div class='col-sm-6'>
						@if(isset($protocols))
							@if(count($protocols) > 0)
								<div class="row">
									<div class='col-sm-12'>
										<label>Протоколы</label>
									</div>
								</div>
								<div id='protocols' class="row" style='border: solid 1.5px; padding: 5px;'>
									<div class='col-sm-12'>
										<div class="row">
											@foreach($protocols as $protocol)
												<div class='col-sm-2'>
													<div class='row'>
														<div class='col-sm-12'>
															<img class='cursorPointer btn-href' src="{{ asset('css/images/bd_sbrowse.png') }}" style='width: 32px;' href="{{route('department.reestr.show_protocols', $contract->id)}}"/>
														</div>
													</div>
													<div class='row'>
														<div class='col-sm-12'>
															<p>{{$protocol->name_protocol}}</p>
														</div>
													</div>
												</div>
											@endforeach
										</div>
									</div>
								</div>
							@endif
						@endif
						<!--<hr style='border-top: 1px solid #030303;height: 10px;-webkit-transform: rotate(-11deg);position: absolute;left: -495px;top: 85px;line-height: 1px;width: 487px;'/>
						<hr style='border-top: 1px solid #030303;height: 10px;position: absolute;left: -495px;top: 147px;line-height: 1px;width: 487px;'/>-->
						@if(isset($additional_agreements))
							@if(count($additional_agreements) > 0)
								<div class="row">
									<div class='col-sm-12'>
										<label>Дополнительные соглашения</label>
									</div>
								</div>
								<div id='additional_agreements' class="row" style='border: solid 1.5px; padding: 5px;'>
									<div class='col-sm-12'>
										<div class="row">
											@foreach($additional_agreements as $additional_agreement)
												<div class='col-sm-2'>
													<div class='row'>
														<div class='col-sm-12'>
															<img class='cursorPointer btn-href' src="{{ asset('css/images/bd_sbrowse.png') }}" style='width: 32px;' href="{{route('department.reestr.show_additional_agreements', $contract->id)}}"/>
														</div>
													</div>
													<div class='row'>
														<div class='col-sm-12'>
															<p>{{$additional_agreement->name_protocol}}</p>
														</div>
													</div>
												</div>
											@endforeach
										</div>
									</div>
								</div>
							@endif
						@endif
						@if(isset($tours))
							@if(count($tours) > 0)
								<div class="row">
									<div class='col-sm-12'>
										<label>Наряды</label>
									</div>
								</div>
								<div id='tours' class="row" style='border: solid 1.5px; padding: 5px;'>
									<div class='col-sm-12'>
										<div class="row">
											@foreach($tours as $tour)
												<div class='col-sm-2'>
													<div class='row'>
														<div class='col-sm-12'>
															<img class='cursorPointer btn-href' src="{{ asset('css/images/bd_sbrowse.png') }}" style='width: 32px;' href="{{route('department.contract_second.show', $contract->id)}}"/>
														</div>
													</div>
													<div class='row'>
														<div class='col-sm-12'>
															<p>№{{$tour->number_duty}}</p>
														</div>
													</div>
												</div>
											@endforeach
										</div>
									</div>
								</div>
							@endif
						@endif
					</div>
				</div>
				<div class='row'>
					<div class='col-md-8 col-md-offset-2'>
						<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
							<thead>
								<tr>
									<th rowspan='8' style='text-align: center; vertical-align: middle; max-width: 94px;'>Оплата и исполнение договора</th>
								</tr>
								<tr>
									<th  colspan='2'>Счёт на оплату</th>
									<th>{{$contract->amount_scores}} р.</th>
								</tr>
								<tr>
									<th  colspan='2'>Аванс</th>
									<th>{{$contract->amount_prepayments}} р.</th>
								</tr>
								<tr>
									<th  colspan='2'>Оказано услуг</th>
									<th>{{$contract->amount_invoices}} р.</th>
								</tr>
								<tr>
									<th  colspan='2'>Окончательный расчет</th>
									<th>{{$contract->amount_payments}} р.</th>
								</tr>
								<tr>
									<th  colspan='2'>Возврат</th>
									<th>{{$contract->amount_returns}} р.</th>
								</tr>
								<tr>
									<th rowspan='2' style='vertical-align: middle;'>Задолженность</th>
									<th>Дебет</th>
									<th>{{($contract->amount_invoices - ($contract->amount_prepayments + $contract->amount_payments) + $contract->amount_returns) > 0 ? $contract->amount_invoices - ($contract->amount_prepayments + $contract->amount_payments) + $contract->amount_returns : 0}} р.</th>
								</tr>
								<tr>
									<th>Кредит</th>
									<th>{{(($contract->amount_prepayments + $contract->amount_payments) - $contract->amount_invoices - $contract->amount_returns) > 0 ? ($contract->amount_prepayments + $contract->amount_payments) - $contract->amount_invoices - $contract->amount_returns : 0}} р.</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
			<!-- Модальное окно резолюции -->
			<div class="modal fade" id="scan" tabindex="-1" role="dialog" aria-labelledby="scanModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="scanModalLabel">Скан договора</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div id='all_aplication' class="modal-body">
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Резолюция:</label>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-12">
									<select id='resolution_list' name='resolution_list' class='form-control {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
										@if(count($resolutions) > 0)
											@foreach($resolutions as $key=>$value)
												@foreach($value as $resolution)
													@if($resolution->deleted_at == null)
														@if($resolution->type_resolution == 1)
															<option value='http://{{$resolution->path_resolution}}' download_href='resolution_download/{{$resolution->id}}' delete_href='{{route("resolution_delete",$resolution->id)}}' style='color: rgb(239,19,198);'>{{$resolution->real_name_resolution}}</option>
														@else
															<option value='http://{{$resolution->path_resolution}}' download_href='resolution_download/{{$resolution->id}}' delete_href='{{route("resolution_delete",$resolution->id)}}'>{{$resolution->real_name_resolution}}</option>
														@endif
													@endif
												@endforeach
											@endforeach
										@else
											<option></option>
										@endif
									</select>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<button id='open_resolution' type='button' class='btn btn-secondary' style='width: 122px;'>Открыть скан</button>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
						</div>
					</div>
				</div>
			</div>
			<script>
				function createTree(){
					var beginOffsetTop = 27;
					if($('#protocols').is('#protocols')){
						var xBegin = $('#main_contract').offset().left + $('#main_contract').width() + 4;
						var yBegin = $('#main_contract').offset().top - beginOffsetTop;
						var xEnd = $('#protocols').offset().left - 4;
						var yEnd = $('#protocols').offset().top - 7;
						var length = Math.sqrt(((xEnd - xBegin) * (xEnd - xBegin)) + ((yEnd - yBegin) * (yEnd - yBegin)));
						var cx = ((xBegin + xEnd) / 2) - (length / 2); 
						var cy = ((yBegin + yEnd) / 2) - 2;
						var angle = Math.atan2((yBegin - yEnd), (xBegin - xEnd))*(180/Math.PI);
						var htmlLine = "<hr class='hrLine' style='height: 3px; position: absolute; left:" + cx + "px; top:" + cy + "px; width:" + length + "px; -webkit-transform:rotate(" + angle + "deg);border-top: 1px solid black;' />";
						$('#content').append(htmlLine);
						beginOffsetTop -= 12;
					}
					if($('#additional_agreements').is('#additional_agreements')){
						xBegin = $('#main_contract').offset().left + $('#main_contract').width() + 4;
						yBegin = $('#main_contract').offset().top - beginOffsetTop;
						xEnd = $('#additional_agreements').offset().left - 4;
						yEnd = $('#additional_agreements').offset().top - 7;
						length = Math.sqrt(((xEnd - xBegin) * (xEnd - xBegin)) + ((yEnd - yBegin) * (yEnd - yBegin)));
						cx = ((xBegin + xEnd) / 2) - (length / 2); 
						cy = ((yBegin + yEnd) / 2) - 2;
						angle = Math.atan2((yBegin - yEnd), (xBegin - xEnd))*(180/Math.PI);
						htmlLine = "<hr class='hrLine' style='height: 3px; position: absolute; left:" + cx + "px; top:" + cy + "px; width:" + length + "px; -webkit-transform:rotate(" + angle + "deg);border-top: 1px solid black;' />";
						$('#content').append(htmlLine);
						beginOffsetTop -= 12;
					}
					if($('#tours').is('#tours')){
						xBegin = $('#main_contract').offset().left + $('#main_contract').width() + 4;
						yBegin = $('#main_contract').offset().top - beginOffsetTop;
						xEnd = $('#tours').offset().left - 4;
						yEnd = $('#tours').offset().top - 7;
						length = Math.sqrt(((xEnd - xBegin) * (xEnd - xBegin)) + ((yEnd - yBegin) * (yEnd - yBegin)));
						cx = ((xBegin + xEnd) / 2) - (length / 2); 
						cy = ((yBegin + yEnd) / 2) - 2;
						angle = Math.atan2((yBegin - yEnd), (xBegin - xEnd))*(180/Math.PI);
						htmlLine = "<hr class='hrLine' style='height: 3px; position: absolute; left:" + cx + "px; top:" + cy + "px; width:" + length + "px; -webkit-transform:rotate(" + angle + "deg);border-top: 1px solid black;' />";
						$('#content').append(htmlLine);
						beginOffsetTop -= 12;
					}
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
				Необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection
