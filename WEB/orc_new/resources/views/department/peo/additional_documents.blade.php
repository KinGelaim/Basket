@extends('layouts.header')

@section('title')
	Договорные материалы
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if(Auth::User())
			<?php 
				$is_disabled = '';
				if(Auth::User()->hasRole()->role != 'Администратор' AND Auth::User()->hasRole()->role != 'Планово-экономический отдел')
					$is_disabled = 'disabled';
				if(Auth::User()->hasRole()->role == 'Десятый отдел')
					if(count(explode("‐",$contract->number_contract))>1)
						if(explode("‐",$contract->number_contract)[1] == '23')
							$is_disabled = '';
			?>
			<div class="">
				<table class="table" style='margin: 0 auto; margin-bottom: 20px;'>
					<thead>
						<tr>
							<th style='text-align: center;'>Заявка</th>
							<th style='text-align: center;'>Дог. мат.</th>
							<th style='text-align: center;'>Наименование работ</th>
							<th style='text-align: center;'>Сроки проведения</th>
							<th colspan='2' style='text-align: center;'>Сумма начальная с НДС, руб.</th>
							<th style='text-align: center;'>Ред</th>
							<th style='text-align: center;'>Согласование</th>
							<th style='text-align: center;'>Лист согласования</th>
							<th style='text-align: center;'>Состояние</th>
						</tr>
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th style='text-align: center;'>всего</th>
							<th style='text-align: center;'>в т.ч на тек. год</th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr class="rowsContract" style='text-align: center;'>
							<td>{{$contract->app_outgoing_number_reestr}}</td>
							<td>{{$contract->number_contract}}<br/><button class='btn btn-primary' type='button' data-toggle='modal' data-target='#editResolutionContract' style='width: 154px;'>Сканы</button></td>
							<td>{{$contract->item_contract}}</td>
							<td>{{$contract->date_maturity_reestr}}</td>
							<td>{{$contract->amount_reestr}}</td>
							<td>{{$contract->amount_year_reestr}}</td>
							<td></td>
							<td><button class='btn btn-primary btn-href' type='button' style='width: 154px;' href='{{ route("reconciliation.contract.show", $contract->id) }}' {{$is_disabled}}>Согласование</button></td>
							<td><button type='button' class='btn btn-primary btn-href' href='{{route("department.reconciliation.print_reconciliation",$contract->id)}}' title='Напечатать лист согласования'><!--☼--><span class="ui-icon ui-icon-1-1" style='background-size: 355px; background-position-x: -226px; background-position-y: -136px;'></span></button></td>
							<td>
								@if(count($states) > 0)
									{{$states[count($states)-1]->name_state}}<br/>
									{{$states[count($states)-1]->comment_state}}<br/>
								@endif
								<button class="btn btn-primary" data-toggle="modal" data-target="#history_states" type='button'>История состояний</button>
							</td>
						</tr>
						@foreach($additional_documents as $additional_document)
							<tr class="rowsContract" style='text-align: center;'>
								<td>{{$additional_document->application_protocol}}</td>
								<td>{{$additional_document->name_protocol}}<br/><button class='btn btn-primary rowsAdditionalDocumentResolution' type='button' style='width: 154px;' additional_document='{{$additional_document}}' href_add_resolution="{{route('resolution_store',$additional_document->id)}}">Сканы</button></td>
								<td>{{$additional_document->name_work_protocol}}</td>
								<td>{{$additional_document->date_protocol}}</td>
								<td>{{$additional_document->amount_protocol}}</td>
								<td>{{$additional_document->amount_year_protocol}}</td>
								<td><button type='button' class='btn btn-primary rowsAdditionalDocument' title='Редактировать договорной материал' additional_document='{{$additional_document}}' href_edit_additional_document="{{route('department.reestr.update_protocol', $additional_document->id)}}" href_add_resolution="{{route('resolution_store',$additional_document->id)}}"><span class="ui-icon ui-icon-1-1" style='background-size: 355px; background-position-x: -91px; background-position-y: -158px;'></span></button></td>
								<td><button class='btn btn-primary btn-href' type='button' style='width: 154px;' href='{{ route("reconciliation.additional_document.show", $additional_document->id) }}' {{$is_disabled}}>Согласование</button></td>
								<td><button type='button' class='btn btn-primary btn-href' href='{{route("reconciliation.additional_document.print_reconciliation",$additional_document->id)}}' title='Напечатать лист согласования'><span class="ui-icon ui-icon-1-1" style='background-size: 355px; background-position-x: -226px; background-position-y: -136px;'></span></button></td>
								<td>
									@if(count($additional_document->states) > 0)
										{{$additional_document->states[count($additional_document->states)-1]->name_state}}<br/>
										{{$additional_document->states[count($additional_document->states)-1]->comment_state}}<br/>
									@endif
									<button class="btn btn-primary btn_all_history_protocol_states" data-toggle="modal" data-target="#history_protocol_states" type='button' states='{{$additional_document->states}}' href_add_states="{{ route('department.ekonomic.new_additional_document_state',$additional_document->id)}}">История состояний</button>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				@if(Auth::User()->hasRole()->role != 'Администрация')
					<button type='button' data-toggle="modal" data-target="#new_protocol"  class='btn btn-primary' {{$is_disabled}}>Добавить протокол</button>
					<button type='button' data-toggle="modal" data-target="#new_additional_agreement"  class='btn btn-primary' {{$is_disabled}}>Добавить дополнительное соглашение</button>
				@endif
			</div>
			<!-- Модальное окно история состояний -->
			<div class="modal fade" id="history_states" tabindex="-1" role="dialog" aria-labelledby="historyStatesModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<form method='POST' action="{{ route('department.ekonomic.new_state',$contract->id)}}">
							{{csrf_field()}}
							<div class="modal-header">
								<h5 class="modal-title" id="historyStatesModalLabel">История состояний договора</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class='row'>
									@if(count($states) > 0)
										<div id='table_history_states' class='col-md-12'>
											<table class="table" style='margin: 0 auto;'>
												<thead>
													<tr>
														<th>Наименование</th>
														<th>Дата</th>
														<th>Автор</th>
													</tr>
												</thead>
												<tbody>
													@foreach($states as $state)
														<tr class='rowsContract' id_state='{{$state->id}}' 
																											name_state='{{$state->name_state}}' 
																											date_state='{{$state->date_state}}' 
																											action_state='{{ route("department.ekonomic.update_state",$contract->id)}}'
																											destroy_state='{{ route("department.ekonomic.destroy_state",$state->id)}}'>
															<td>{{$state->name_state}}<br/>{{$state->comment_state}}</td>
															<td>{{$state->date_state}}</td>
															<td>{{$state->surname . ' ' . mb_substr($state->name, 0, 1) . '.' . mb_substr($state->patronymic, 0, 1) . '.'}}</td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									@endif
									<div id='add_history_states' class='col-md-12' style='display: none;'>
										<div class='form-group row col-md-12'>
											<input id='id_state' class='form-control' type='text' name='id_state' style='display: none;'/>
										</div>
										<div class='form-group row col-md-12'>
											<div class='col-md-12'>
												<label for='type_state' class='col-form-label'>Наименование</label>
												<select id='type_state' class='form-control {{$errors->has("unit_reestr") ? print("inputError ") : print("")}}' name='type_state' required>
													<option></option>
													<option>На согласовании</option>
													<option>Отправлен заказчику</option>
													<option>Заключён</option>
													<option>Другое</option>
												</select>
											</div>
										</div>
										<div class='form-group row col-md-12'>
											<div class='col-md-12'>
												<input id='new_name_state' class='form-control {{$errors->has("new_name_state") ? print("inputError ") : print("")}}' type='text' name='new_name_state' style='display: none;'/>
												@if($errors->has('new_name_state'))
													<label class='msgError'>{{$errors->first('new_name_state')}}</label>
												@endif
											</div>
										</div>
										<div class='form-group row col-md-12'>
											<label for='comment_state' class='col-md-3 col-form-label'>Примечение</label>
											<div class='col-md-9'>
												<input id='comment_state' class='form-control {{$errors->has("comment_state") ? print("inputError ") : print("")}}' type='text' name='comment_state'/>
												@if($errors->has('comment_state'))
													<label class='msgError'>{{$errors->first('comment_state')}}</label>
												@endif
											</div>
										</div>
										<div class='form-group row col-md-12'>
											<label for='date_state' class='col-md-3 col-form-label'>Дата</label>
											<div class='col-md-9'>
												<input id='date_state' class='datepicker form-control {{$errors->has("date_state") ? print("inputError ") : print("")}}' name='date_state' value='{{date("d.m.Y", time())}}' />
												@if($errors->has('date_state'))
													<label class='msgError'>{{$errors->first('date_state')}}</label>
												@endif
											</div>
										</div>
									</div>
									<div class='col-md-12'>
										@if(Auth::User()->hasRole()->role != 'Администрация')
											<button id='btn_add_state' class='btn btn-secondary' type='button' clear_date='{{date("d.m.Y", time())}}' action_state='{{ route("department.ekonomic.new_state",$contract->id)}}' style='margin-top: 10px;' {{$is_disabled}}>Добавить состояние</button>
										@endif
									</div>
								</div>									
							</div>
							<div class="modal-footer">
								<button id='btn_add_new_history' type="submit" class="btn btn-primary" style='display: none;'>Добавить</button>
								<button id='btn_destroy_state' type="submit" class="btn btn-danger" style='display: none;'>Удалить</button>
								<button id='btn_close_new_history_states' type="button" class="btn btn-secondary" style='display: none;'>Закрыть</button>
								<button id='btn_close_new_history' type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- Модальное окно истории состояний протокола -->
			<div class="modal fade" id="history_protocol_states" tabindex="-1" role="dialog" aria-labelledby="historyProtocolStatesModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<form id='protocol_state_form' method='POST' action=''>
							{{csrf_field()}}
							<div class="modal-header">
								<h5 class="modal-title" id="historyProtocolStatesModalLabel">История состояний протокола</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class='row'>
									@if(count($states) > 0)
										<div id='table_history_protocol_states' class='col-md-12 first_step_protocol_div'>
											<table class="table" style='margin: 0 auto;'>
												<thead>
													<tr>
														<th>Наименование</th>
														<th>Дата</th>
														<th>Автор</th>
													</tr>
												</thead>
												<tbody id='tbody_history_protocol_states'>
													
												</tbody>
											</table>
										</div>
									@endif
									<div id='add_history_protocol_states' class='col-md-12 add_step_protocol_div' style='display: none;'>
										<div class='form-group row col-md-12'>
											<input id='id_state' class='form-control' type='text' name='id_state' style='display: none;'/>
										</div>
										<div class='form-group row col-md-12'>
											<div class='col-md-12'>
												<label for='type_state_protocol' class='col-form-label'>Наименование</label>
												<select id='type_state_protocol' class='form-control {{$errors->has("unit_reestr") ? print("inputError ") : print("")}}' name='type_state' required>
													<option></option>
													<option>На согласовании</option>
													<option>Отправлен заказчику</option>
													<option>Заключён</option>
													<option>Другое</option>
												</select>
											</div>
										</div>
										<div class='form-group row col-md-12'>
											<div class='col-md-12'>
												<input id='new_name_protocol_state' class='form-control {{$errors->has("new_name_state") ? print("inputError ") : print("")}}' type='text' name='new_name_state' style='display: none;'/>
												@if($errors->has('new_name_state'))
													<label class='msgError'>{{$errors->first('new_name_state')}}</label>
												@endif
											</div>
										</div>
										<div class='form-group row col-md-12'>
											<label for='date_state' class='col-md-3 col-form-label'>Примечение</label>
											<div class='col-md-9'>
												<input class='form-control {{$errors->has("comment_state") ? print("inputError ") : print("")}}' type='text' name='comment_state'/>
												@if($errors->has('comment_state'))
													<label class='msgError'>{{$errors->first('comment_state')}}</label>
												@endif
											</div>
										</div>
										<div class='form-group row col-md-12'>
											<label for='date_state' class='col-md-3 col-form-label'>Дата</label>
											<div class='col-md-9'>
												<input id='date_state' class='datepicker form-control {{$errors->has("date_state") ? print("inputError ") : print("")}}' name='date_state' value='{{date("d.m.Y", time())}}'/>
												@if($errors->has('date_state'))
													<label class='msgError'>{{$errors->first('date_state')}}</label>
												@endif
											</div>
										</div>
									</div>
									<div class='col-md-12 first_step_protocol_div'>
										@if(Auth::User()->hasRole()->role != 'Администрация')
											<button id='btn_add_state_protocol' class='btn btn-secondary steps' first_step='.first_step_protocol_div' second_step='.add_step_protocol_div' type='button' style='margin-top: 10px;' clear_date='{{date("d.m.Y", time())}}' action_state='' onclick="$('#protocol_state_form').attr('action',$(this).attr('action_state'))" {{$is_disabled}}>Добавить состояние</button>
										@endif
									</div>
								</div>									
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary first_step_protocol_div" data-dismiss="modal" style='float: right;'>Закрыть</button>
								<button type="button" class="btn btn-secondary add_step_protocol_div steps" first_step='.add_step_protocol_div' second_step='.first_step_protocol_div' style='display: none; float: right;'>Закрыть</button>
								<button type="submit" class="btn btn-primary add_step_protocol_div" style='display: none; float: right;'>Добавить</button>
								<button id='btn_destroy_state' type="submit" class="btn btn-danger" style='display: none;'>Удалить</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- Модальное окно нового протокола -->
			<div class="modal fade" id="new_protocol" tabindex="-1" role="dialog" aria-labelledby="newProtocolModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<form method='POST' action='{{route("department.reestr.store_protocol",$id_contract)}}'>
							{{csrf_field()}}
							<div class="modal-header">
								<h5 class="modal-title" id="newProtocolModalLabel">Добавление протокола</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class='modal-body'>
								<div class='row'>
									<div class="col-md-4">
										<label>Заявка:</label>
									</div>
									<div class="col-md-8">
										<input class='form-control' name='application_protocol' type='text' value=''/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Наименование протокола:</label>
									</div>
									<div class="col-md-8">
										<input class='form-control' name='name_protocol' type='text' value='' required/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Наименование работ:</label>
									</div>
									<div class="col-md-8">
										<input class='form-control' name='name_work_protocol' type='text' value=''/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Сроки проведения:</label>
									</div>
									<div class="col-md-8">
										<input class='form-control' name='date_protocol' type='text' value=''/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата протокола на 1 л.:</label>
									</div>
									<div class="col-md-8">
										<input class='datepicker form-control' name='date_on_first_protocol' type='text' value=''/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата регистрации:</label>
									</div>
									<div class="col-md-8">
										<input class='datepicker form-control' name='date_registration_protocol' type='text' value='' required/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата подписания ФКП "НТИИМ"</label>
									</div>
									<div class="col-md-8">
										<input class='datepicker form-control' name='date_signing_protocol' type='text' value=''/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата подписания контрагентом</label>
									</div>
									<div class="col-md-8">
										<input class='datepicker form-control' name='date_signing_counterpartie_protocol' type='text' value=''/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-2">
										<label>Дата сдачи на хранение</label>
									</div>
									<div class="col-md-3">
										<div class='row'>
											<div class="col-md-12">
												<label for='is_oud'>ОУД</label>
												<input id='is_oud' class='form-check-input' name='is_oud' type="checkbox"/>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<label for='is_dep'>Отдел №31</label>
												<input id='is_dep' class='form-check-input' name='is_dep' type="checkbox"/>
											</div>
										</div>
									</div>
									<div class="col-md-7">
										<div class='row'>
											<div class="col-md-12">
												<input class='datepicker form-control' name='date_oud_protocol' type='text' value=''/>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<input class='datepicker form-control' name='date_dep_protocol' type='text' value=''/>
											</div>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-2">
										<label>Сумма начальная с НДС, руб.</label>
									</div>
									<div class="col-md-3">
										<div class='row'>
											<div class="col-md-12">
												<label>Всего</label>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<label>в т.ч на тек. год</label>
											</div>
										</div>
									</div>
									<div class="col-md-7">
										<div class='row'>
											<div class="col-md-12">
												<input class='form-control check-number' name='amount_protocol' type='text' value=''/>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<input class='form-control check-number' name='amount_year_protocol' type='text' value=''/>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type='submit' class='btn btn-primary' type='button' {{$is_disabled}}>Добавить</button>
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- Модальное окно редактирования протокола -->
			<div class="modal fade" id="edit_protocol" tabindex="-1" role="dialog" aria-labelledby="editProtocolModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<form id='formToUpdateProtocol' method='POST' action=''>
							{{csrf_field()}}
							<div class="modal-header">
								<h5 class="modal-title" id="editProtocolModalLabel">Редактирование протокола</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class='modal-body'>
								<div class='row'>
									<div class="col-md-4">
										<label>Заявка:</label>
									</div>
									<div class="col-md-8">
										<input id='update_application_protocol' class='form-control' name='application_protocol' type='text' value='' {{$is_disabled}}/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Наименование протокола:</label>
									</div>
									<div class="col-md-8">
										<input id='update_name_protocol' class='form-control' name='name_protocol' type='text' value='' required {{$is_disabled}}/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Наименование работ:</label>
									</div>
									<div class="col-md-8">
										<input id='update_name_work_protocol' class='form-control' name='name_work_protocol' type='text' value='' {{$is_disabled}}/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Сроки проведения:</label>
									</div>
									<div class="col-md-8">
										<input id='update_date_protocol' class='form-control' name='date_protocol' type='text' value='' {{$is_disabled}}/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата протокола на 1 л.:</label>
									</div>
									<div class="col-md-8">
										<input id='update_date_on_first_protocol' class='datepicker form-control' name='date_on_first_protocol' type='text' value='' {{$is_disabled}}/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата регистрации:</label>
									</div>
									<div class="col-md-8">
										<input id='update_date_registration_protocol' class='datepicker form-control' name='date_registration_protocol' type='text' value='' required {{$is_disabled}}/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата подписания ФКП "НТИИМ"</label>
									</div>
									<div class="col-md-8">
										<input id='update_date_signing_protocol' class='datepicker form-control' name='date_signing_protocol' type='text' value='' {{$is_disabled}}/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата подписания контрагентом</label>
									</div>
									<div class="col-md-8">
										<input id='update_date_signing_counterpartie_protocol' class='datepicker form-control' name='date_signing_counterpartie_protocol' type='text' value='' {{$is_disabled}}/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-2">
										<label>Дата сдачи на хранение</label>
									</div>
									<div class="col-md-3">
										<div class='row'>
											<div class="col-md-12">
												<label for='update_is_oud'>ОУД</label>
												<input id='update_is_oud' class='form-check-input' name='is_oud' type="checkbox" {{$is_disabled}}/>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<label for='update_is_dep'>Отдел №31</label>
												<input id='update_is_dep' class='form-check-input' name='is_dep' type="checkbox" {{$is_disabled}}/>
											</div>
										</div>
									</div>
									<div class="col-md-7">
										<div class='row'>
											<div class="col-md-12">
												<input id='update_date_oud_protocol' class='datepicker form-control' name='date_oud_protocol' type='text' value='' {{$is_disabled}}/>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<input id='update_date_dep_protocol' class='datepicker form-control' name='date_dep_protocol' type='text' value='' {{$is_disabled}}/>
											</div>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-2">
										<label>Сумма начальная с НДС, руб.</label>
									</div>
									<div class="col-md-3">
										<div class='row'>
											<div class="col-md-12">
												<label>Всего</label>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<label>в т.ч на тек. год</label>
											</div>
										</div>
									</div>
									<div class="col-md-7">
										<div class='row'>
											<div class="col-md-12">
												<input id='update_amount_protocol' class='form-control check-number' name='amount_protocol' type='text' value='' {{$is_disabled}}/>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<input id='update_amount_year_protocol' class='form-control check-number' name='amount_year_protocol' type='text' value='' {{$is_disabled}}/>
											</div>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-3">
										<label>Резолюция:</label>
									</div>
									<div class="col-md-4">
										@if(Auth::User()->hasRole()->role != 'Администрация')
											<button type='button' class='btn btn-secondary steps' first_step='#edit_protocol #formToUpdateProtocol' second_step='#edit_protocol #formNewResolution' {{$is_disabled}}>Добавить скан</button>
										@endif
									</div>
									<div class="col-md-4">
									</div>
								</div>
								<div class='row'>
									<div class="col-md-3">
									</div>
									<div class="col-md-6">
										<select id='resolution_list' name='resolution_list' class='form-control {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
											@if(Session::has("new_scan"))
												@foreach(Session("all_scan") as $res)
													@if(Session("new_scan")->id == $res->id)
														<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}' selected>{{$res->real_name_resolution}}</option>
													@else
														<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}'>{{$res->real_name_resolution}}</option>
													@endif
												@endforeach
											@else
												<option></option>
											@endif
										</select>
									</div>
									<div class="col-md-3">
										<button type='button' class='btn btn-primary open_resolution' resolution_block='formToUpdateProtocol #resolution_list' style='width: 122px;'>Открыть скан</button>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type='submit' class='btn btn-primary' type='button' {{$is_disabled}}>Изменить</button>
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
							</div>
						</form>
						<form id='formNewResolution' method='POST' file='true' enctype='multipart/form-data' action='' style='display: none;'>
							{{csrf_field()}}
							<div class="modal-header">
								<h5 class="modal-title" id="updateApplicationModalLabel">Добавление резолюции</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class='modal-body'>
								<div class='row'>
									<div class='col-md-6' style='display: none;'>
										<input type='text' value='id_protocol_resolution' name='real_name_document'/>
									</div>
									<div class='col-md-6'>
										<input id='new_file_resolution' type='file' name='new_file_resolution'/>
									</div>
									<div class='col-md-6'>
										<input id='date_resolution' name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
									</div>
								</div>
								<div class='row'>
									<div class='col-md-12'>
										<label>Наименование документа</label>
										<input id='real_name_resolution' type='text' name='real_name_resolution' class='form-control'/>
									</div>
								</div>
								<div class='row'>
									<div class='col-md-12'>
										<label>Тип документа</label>
										<select id='type_resolution' type='text' name='type_resolution' class='form-control'>
											<option></option>
											@foreach($type_resolutions as $type_resolution)
												<option value='{{$type_resolution->id}}'>{{$type_resolution->name_type_resolution}}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
								<button type="button" class="btn btn-secondary steps" first_step='#edit_protocol #formNewResolution' second_step='#edit_protocol #formToUpdateProtocol'>Закрыть</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- Модальное окно нового дополнительного соглашения -->
			<div class="modal fade" id="new_additional_agreement" tabindex="-1" role="dialog" aria-labelledby="newadditional_agreementModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<form method='POST' action='{{route("department.reestr.store_additional_agreement",$id_contract)}}'>
							{{csrf_field()}}
							<div class="modal-header">
								<h5 class="modal-title" id="newadditional_agreementModalLabel">Добавление дополнительного соглашения</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class='modal-body'>
								<div class='row'>
									<div class="col-md-4">
										<label>Заявка:</label>
									</div>
									<div class="col-md-8">
										<input class='form-control' name='application_protocol' type='text' value=''/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дополнительное соглашение:</label>
									</div>
									<div class="col-md-8">
										<input class='form-control' name='name_protocol' type='text' value='' required/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Наименование работ:</label>
									</div>
									<div class="col-md-8">
										<input class='form-control' name='name_work_protocol' type='text' value=''/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Сроки проведения:</label>
									</div>
									<div class="col-md-8">
										<input class='form-control' name='date_protocol' type='text' value=''/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата протокола на 1 л.:</label>
									</div>
									<div class="col-md-8">
										<input class='datepicker form-control' name='date_on_first_protocol' type='text' value=''/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата регистрации:</label>
									</div>
									<div class="col-md-8">
										<input class='datepicker form-control' name='date_registration_protocol' type='text' value='' required/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата подписания ФКП "НТИИМ"</label>
									</div>
									<div class="col-md-8">
										<input class='datepicker form-control' name='date_signing_protocol' type='text' value=''/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата подписания контрагентом</label>
									</div>
									<div class="col-md-8">
										<input class='datepicker form-control' name='date_signing_counterpartie_protocol' type='text' value=''/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата вступления в силу</label>
									</div>
									<div class="col-md-8">
										<input class='datepicker form-control' name='date_entry_ento_force_additional_agreement' type='text' value=''/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-2">
										<label>Дата сдачи на хранение</label>
									</div>
									<div class="col-md-3">
										<div class='row'>
											<div class="col-md-12">
												<label for='is_oud'>ОУД</label>
												<input id='is_oud' class='form-check-input' name='is_oud' type="checkbox"/>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<label for='is_dep'>Отдел №31</label>
												<input id='is_dep' class='form-check-input' name='is_dep' type="checkbox"/>
											</div>
										</div>
									</div>
									<div class="col-md-7">
										<div class='row'>
											<div class="col-md-12">
												<input class='datepicker form-control' name='date_oud_protocol' type='text' value=''/>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<input class='datepicker form-control' name='date_dep_protocol' type='text' value=''/>
											</div>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-2">
										<label>Сумма начальная с НДС, руб.</label>
									</div>
									<div class="col-md-3">
										<div class='row'>
											<div class="col-md-12">
												<label>Всего</label>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<label>в т.ч на тек. год</label>
											</div>
										</div>
									</div>
									<div class="col-md-7">
										<div class='row'>
											<div class="col-md-12">
												<input class='form-control check-number' name='amount_protocol' type='text' value=''/>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<input class='form-control check-number' name='amount_year_protocol' type='text' value=''/>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type='submit' class='btn btn-primary' type='button' {{$is_disabled}}>Добавить</button>
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- Модальное окно редактирования дополнительного соглашения -->
			<div class="modal fade" id="edit_additional_agreement" tabindex="-1" role="dialog" aria-labelledby="editadditional_agreementModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<form id='formToUpdateProtocol' method='POST' action=''>
							{{csrf_field()}}
							<div class="modal-header">
								<h5 class="modal-title" id="editadditional_agreementModalLabel">Редактирование дополнительного соглашения</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class='modal-body'>
								<div class='row'>
									<div class="col-md-4">
										<label>Заявка:</label>
									</div>
									<div class="col-md-8">
										<input id='update_application_protocol' class='form-control' name='application_protocol' type='text' value='' {{$is_disabled}}/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дополнительное соглашение:</label>
									</div>
									<div class="col-md-8">
										<input id='update_name_protocol' class='form-control' name='name_protocol' type='text' value='' required {{$is_disabled}}/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Наименование работ:</label>
									</div>
									<div class="col-md-8">
										<input id='update_name_work_protocol' class='form-control' name='name_work_protocol' type='text' value='' {{$is_disabled}}/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Сроки проведения:</label>
									</div>
									<div class="col-md-8">
										<input id='update_date_protocol' class='form-control' name='date_protocol' type='text' value='' {{$is_disabled}}/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата ДС на 1 л.:</label>
									</div>
									<div class="col-md-8">
										<input id='update_date_on_first_protocol' class='datepicker form-control' name='date_on_first_protocol' type='text' value='' {{$is_disabled}}/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата регистрации:</label>
									</div>
									<div class="col-md-8">
										<input id='update_date_registration_protocol' class='datepicker form-control' name='date_registration_protocol' type='text' value='' required {{$is_disabled}}/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата подписания ФКП "НТИИМ"</label>
									</div>
									<div class="col-md-8">
										<input id='update_date_signing_protocol' class='datepicker form-control' name='date_signing_protocol' type='text' value='' {{$is_disabled}}/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата подписания контрагентом</label>
									</div>
									<div class="col-md-8">
										<input id='update_date_signing_counterpartie_protocol' class='datepicker form-control' name='date_signing_counterpartie_protocol' type='text' value='' {{$is_disabled}}/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Дата вступления в силу</label>
									</div>
									<div class="col-md-8">
										<input id='update_date_entry_ento_force_additional_agreement' class='datepicker form-control' name='date_entry_ento_force_additional_agreement' type='text' value='' {{$is_disabled}}/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-2">
										<label>Дата сдачи на хранение</label>
									</div>
									<div class="col-md-3">
										<div class='row'>
											<div class="col-md-12">
												<label for='update_is_oud'>ОУД</label>
												<input id='update_is_oud' class='form-check-input' name='is_oud' type="checkbox" {{$is_disabled}}/>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<label for='update_is_dep'>Отдел №31</label>
												<input id='update_is_dep' class='form-check-input' name='is_dep' type="checkbox" {{$is_disabled}}/>
											</div>
										</div>
									</div>
									<div class="col-md-7">
										<div class='row'>
											<div class="col-md-12">
												<input id='update_date_oud_protocol' class='datepicker form-control' name='date_oud_protocol' type='text' value='' {{$is_disabled}}/>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<input id='update_date_dep_protocol' class='datepicker form-control' name='date_dep_protocol' type='text' value='' {{$is_disabled}}/>
											</div>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-2">
										<label>Сумма начальная с НДС, руб.</label>
									</div>
									<div class="col-md-3">
										<div class='row'>
											<div class="col-md-12">
												<label>Всего</label>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<label>в т.ч на тек. год</label>
											</div>
										</div>
									</div>
									<div class="col-md-7">
										<div class='row'>
											<div class="col-md-12">
												<input id='update_amount_protocol' class='form-control check-number' name='amount_protocol' type='text' value='' {{$is_disabled}}/>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<input id='update_amount_year_protocol' class='form-control check-number' name='amount_year_protocol' type='text' value='' {{$is_disabled}}/>
											</div>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-3">
										<label>Резолюция:</label>
									</div>
									<div class="col-md-4">
										@if(Auth::User()->hasRole()->role != 'Администрация')
											<button id='add_new_resolution' type='button' class='btn btn-secondary steps' first_step='#edit_additional_agreement #formToUpdateProtocol' second_step='#edit_additional_agreement #formNewResolution' {{$is_disabled}}>Добавить скан</button>
										@endif
									</div>
									<div class="col-md-4">
									</div>
								</div>
								<div class='row'>
									<div class="col-md-3">
									</div>
									<div class="col-md-6">
										<select id='resolution_list_edit_dop' name='resolution_list' class='form-control {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
											@if(Session::has("new_scan"))
												@foreach(Session("all_scan") as $res)
													@if(Session("new_scan")->id == $res->id)
														<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}' selected>{{$res->real_name_resolution}}</option>
													@else
														<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}'>{{$res->real_name_resolution}}</option>
													@endif
												@endforeach
											@else
												<option></option>
											@endif
										</select>
									</div>
									<div class="col-md-3">
										<button type='button' class='btn btn-primary open_resolution' resolution_block='formToUpdateProtocol #resolution_list' style='width: 122px;'>Открыть скан</button>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type='submit' class='btn btn-primary' type='button' {{$is_disabled}}>Изменить</button>
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
							</div>
						</form>
						<form id='formNewResolution' method='POST' file='true' enctype='multipart/form-data' action='' style='display: none;'>
							{{csrf_field()}}
							<div class="modal-header">
								<h5 class="modal-title" id="updateApplicationModalLabel">Добавление резолюции</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class='modal-body'>
								<div class='row'>
									<div class='col-md-6' style='display: none;'>
										<input type='text' value='id_protocol_resolution' name='real_name_document'/>
									</div>
									<div class='col-md-6'>
										<input id='new_file_resolution' type='file' name='new_file_resolution'/>
									</div>
									<div class='col-md-6'>
										<input id='date_resolution' name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
									</div>
								</div>
								<div class='row'>
									<div class='col-md-12'>
										<label>Наименование документа</label>
										<input id='real_name_resolution' type='text' name='real_name_resolution' class='form-control'/>
									</div>
								</div>
								<div class='row'>
									<div class='col-md-12'>
										<label>Тип документа</label>
										<select id='type_resolution' type='text' name='type_resolution' class='form-control'>
											<option></option>
											@foreach($type_resolutions as $type_resolution)
												<option value='{{$type_resolution->id}}'>{{$type_resolution->name_type_resolution}}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
								<button type="button" class="btn btn-secondary steps" first_step='#edit_additional_agreement #formNewResolution' second_step='#edit_additional_agreement #formToUpdateProtocol'>Закрыть</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- Окно просмотра сканов для договора -->
			<div class="modal fade" id="editResolutionContract" tabindex="-1" role="dialog" aria-labelledby="showResolutionContractModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div id='showInRowEditResolution'>
							<div class="modal-header">
								<h5 class="modal-title" id="showResolutionContractModalLabel">Сканы договора</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class='modal-body'>
								<div class='row'>
									<div class="col-md-3">
										<label>Резолюция:</label>
									</div>
									<div class="col-md-5">
										@if(Auth::User()->hasRole()->role != 'Администрация')
											<button id='add_new_resolution' type='button' class='btn btn-secondary steps' first_step='#editResolutionContract #showInRowEditResolution' second_step='#editResolutionContract #formInShowNewResolution' {{$is_disabled}}>Добавить скан</button>
										@endif
									</div>
									<div class="col-md-4" style='text-align: right;'>
										<button type='button' class='btn btn-secondary steps' first_step='#editResolutionContract #showInRowEditResolution' second_step='#editResolutionContract #updateInShowNewResolution' {{$is_disabled}}>Управление сканами</button>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-3">
									</div>
									<div class="col-md-6">
										<select id='resolution_list_only_contract' name='resolution_list' class='form-control {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
											@if(count($resolutions) > 0)
												@foreach($resolutions as $resolution)
													@if($resolution->deleted_at == null)
														@if($resolution->type_resolution == 1)
															<option value='http://{{$resolution->path_resolution}}' download_href='resolution_download/{{$resolution->id}}' delete_href='{{route("resolution_delete",$resolution->id)}}' style='color: rgb(239,19,198);'>{{$resolution->real_name_resolution}}</option>
														@else
															<option value='http://{{$resolution->path_resolution}}' download_href='resolution_download/{{$resolution->id}}' delete_href='{{route("resolution_delete",$resolution->id)}}'>{{$resolution->real_name_resolution}}</option>
														@endif
													@endif
												@endforeach
											@else
												<option></option>
											@endif
										</select>
									</div>
									<div class="col-md-3">
										<button type='button' class='btn btn-primary open_resolution' resolution_block='resolution_list_only_contract' style='width: 122px;'>Открыть скан</button>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
							</div>
						</div>
						<form id='formInShowNewResolution' method='POST' file='true' enctype='multipart/form-data' action='{{route("resolution_store", $contract->id)}}' style='display: none;'>
							{{csrf_field()}}
							<div class="modal-header">
								<h5 class="modal-title" id="updateApplicationModalLabel">Добавление резолюции</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class='modal-body'>
								<div class='row'>
									<div class='col-md-6' style='display: none;'>
										<input type='text' value='id_contract_resolution' name='real_name_document'/>
									</div>
									<div class='col-md-6'>
										<input id='new_file_resolution' type='file' name='new_file_resolution'/>
									</div>
									<div class='col-md-6'>
										<input id='date_resolution' name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
									</div>
								</div>
								<div class='row'>
									<div class='col-md-12'>
										<label>Наименование документа</label>
										<input id='real_name_resolution' type='text' name='real_name_resolution' class='form-control'/>
									</div>
								</div>
								<div class='row'>
									<div class='col-md-12'>
										<label>Тип документа</label>
										<select id='type_resolution' type='text' name='type_resolution' class='form-control'>
											<option></option>
											@foreach($type_resolutions as $type_resolution)
												<option value='{{$type_resolution->id}}'>{{$type_resolution->name_type_resolution}}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
								<button type="button" class="btn btn-secondary steps" first_step='#editResolutionContract #formInShowNewResolution' second_step='#editResolutionContract #showInRowEditResolution'>Закрыть</button>
							</div>
						</form>
						<div id='updateInShowNewResolution' style='display: none;'>
							<div class="modal-header">
								<h5 class="modal-title" id="updateApplicationModalLabel">Управление резолюциями</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class='modal-body'>
								<div class='row'>
									<div class='col-md-12'>
										<div id='divMessageContract'>
										</div>
										<table class="table" style='margin: 0 auto;'>
											<thead>
												<tr>
													<th>Название резолюции</th>
													<th>Удаление</th>
												</tr>
											</thead>
											<tbody>
												@foreach($resolutions as $resolution)
													@if($resolution->deleted_at == null)
														<tr class='rowsContract'>
															<td>{{$resolution->real_name_resolution}}</td>
															<td><button class='btn btn-danger ajax-send-on-delete-href' ajax-href="{{route('resolution_contract_delete_ajax', $resolution->id)}}" ajax-method='GET' div-message='#divMessageContract'>Удалить</button></td>
														</tr>
													@endif
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary steps" first_step='#editResolutionContract #updateInShowNewResolution' second_step='#editResolutionContract #showInRowEditResolution'>Закрыть</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Окно просмотра сканов для договорных материалов -->
			<div class="modal fade" id="edit_resolutions" tabindex="-1" role="dialog" aria-labelledby="showResolutionModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div id='showInRowEditResolution'>
							<div class="modal-header">
								<h5 class="modal-title" id="showResolutionModalLabel">Сканы договорного материала</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class='modal-body'>
								<div class='row'>
									<div class="col-md-3">
										<label>Резолюция:</label>
									</div>
									<div class="col-md-5">
										@if(Auth::User()->hasRole()->role != 'Администрация')
											<button id='add_new_resolution' type='button' class='btn btn-secondary steps' first_step='#edit_resolutions #showInRowEditResolution' second_step='#edit_resolutions #formInShowNewResolution' {{$is_disabled}}>Добавить скан</button>
										@endif
									</div>
									<div class="col-md-4" style='text-align: right;'>
										<button type='button' class='btn btn-secondary steps' first_step='#edit_resolutions #showInRowEditResolution' second_step='#edit_resolutions #updateInShowNewResolution' {{$is_disabled}}>Управление сканами</button>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-3">
									</div>
									<div class="col-md-6">
										<select id='resolution_list' name='resolution_list' class='form-control {{$errors->has("resolution_list") ? print("inputError ") : print("")}}'>
											@if(Session::has("new_scan"))
												@foreach(Session("all_scan") as $res)
													@if(Session("new_scan")->id == $res->id)
														<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}' selected>{{$res->real_name_resolution}}</option>
													@else
														<option value='{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}'>{{$res->real_name_resolution}}</option>
													@endif
												@endforeach
											@else
												<option></option>
											@endif
										</select>
									</div>
									<div class="col-md-3">
										<button type='button' class='btn btn-primary open_resolution' resolution_block='showInRowEditResolution #resolution_list' style='width: 122px;'>Открыть скан</button>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
							</div>
						</div>
						<form id='formInShowNewResolution' method='POST' file='true' enctype='multipart/form-data' action='' style='display: none;'>
							{{csrf_field()}}
							<div class="modal-header">
								<h5 class="modal-title" id="updateApplicationModalLabel">Добавление резолюции</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class='modal-body'>
								<div class='row'>
									<div class='col-md-6' style='display: none;'>
										<input type='text' value='id_protocol_resolution' name='real_name_document'/>
									</div>
									<div class='col-md-6'>
										<input id='new_file_resolution' type='file' name='new_file_resolution'/>
									</div>
									<div class='col-md-6'>
										<input id='date_resolution' name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
									</div>
								</div>
								<div class='row'>
									<div class='col-md-12'>
										<label>Наименование документа</label>
										<input id='real_name_resolution' type='text' name='real_name_resolution' class='form-control'/>
									</div>
								</div>
								<div class='row'>
									<div class='col-md-12'>
										<label>Тип документа</label>
										<select id='type_resolution' type='text' name='type_resolution' class='form-control'>
											<option></option>
											@foreach($type_resolutions as $type_resolution)
												<option value='{{$type_resolution->id}}'>{{$type_resolution->name_type_resolution}}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
								<button type="button" class="btn btn-secondary steps" first_step='#edit_resolutions #formInShowNewResolution' second_step='#edit_resolutions #showInRowEditResolution'>Закрыть</button>
							</div>
						</form>
						<div id='updateInShowNewResolution' style='display: none;'>
							<div class="modal-header">
								<h5 class="modal-title" id="updateApplicationModalLabel">Управление резолюциями</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class='modal-body'>
								<div class='row'>
									<div class='col-md-12'>
										<div id='divMessagePrDs'>
										</div>
										<table class="table" style='margin: 0 auto;'>
											<thead>
												<tr>
													<th>Название резолюции</th>
													<th>Удаление</th>
												</tr>
											</thead>
											<tbody id='resolution_table_tbody'>
												
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary steps" first_step='#edit_resolutions #updateInShowNewResolution' second_step='#edit_resolutions #showInRowEditResolution'>Закрыть</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Модальное окно новой резолюции -->
			<div class="modal fade" id="new_resolution" tabindex="-1" role="dialog" aria-labelledby="newResolutionModalLabel" aria-hidden="true" attr-open-modal='{{$errors->has("number_application_update") || $errors->has("number_outgoing_update") || $errors->has("date_outgoing_update") || $errors->has("number_incoming_update") || $errors->has("date_incoming_update") || $errors->has("directed_application_update") || $errors->has("date_directed_update") || Session::has("new_scan") ? print("open") : print("")}}'>
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<form id='formToStoreNewResolution' method='POST' file='true' enctype='multipart/form-data' action=''>
							{{csrf_field()}}
							<div class="modal-header">
								<h5 class="modal-title" id="newResolutionModalLabel">Добавление резолюции</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class='modal-body'>
									<div class='row'>
										<div class='col-md-6' style='display: none;'>
											<input type='text' value='id_protocol_resolution' name='real_name_document'/>
										</div>
										<div class='col-md-6'>
											<input type='file' name='new_file_resolution'/>
										</div>
										<div class='col-md-6'>
											<input id='date_resolution' name='date_resolution' class='datepicker form-control {{$errors->has("date_resolution") ? print("inputError ") : print("")}}' type='text' value="{{old('date_resolution') ? old('date_resolution') : date('d.m.Y', time())}}"/>
										</div>
									</div>
							</div>
							<div class="modal-footer">
								<button type='submit' class='btn btn-primary' type='button'>Сохранить</button>
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		@else
			<div class="alert alert-danger">
				Для просмотра данной страницы необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection
