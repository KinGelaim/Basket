@extends('layouts.header')

@section('title')
	Протокол/Доп.соглашение (согласование)
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="container">
				<form id='post_send' method='POST' action="{{route('reconciliation.additional_document.store', $additional_document->id)}}">
					{{csrf_field()}}
					@if(Auth::User()->hasRole()->role != 'Планово-экономический отдел')
						<div class='row'>
							<div class="col-md-3">
								
							</div>
							<div class="col-md-4">
								<button type='button' class='btn btn-primary btn-href' href="{{route('tree_map.show_contract', $additional_document->id_contract)}}">Граф договора</button>
							</div>
						</div>
						<div class='row'>
							<div class="col-md-3">
								<label>Заявка:</label>
							</div>
							<div class="col-md-7">
								<input class='form-control' type='text' value='{{$additional_document->application_protocol}}' readonly />
							</div>
						</div>
						<div class='row'>
							<div class="col-md-3">
								@if($additional_document->is_protocol)
									<label>Наименование протокола:</label>
								@else
									<label>Дополнительное соглашение:</label>
								@endif
							</div>
							<div class="col-md-7">
								<input class='form-control' type='text' value='{{$additional_document->name_protocol}}' readonly />
							</div>
						</div>
						<div class='row'>
							<div class="col-md-3">
								<label>Наименование работ:</label>
							</div>
							<div class="col-md-7">
								<input class='form-control' type='text' value='{{$additional_document->name_work_protocol}}' readonly />
							</div>
						</div>
						<div class='row'>
							<div class="col-md-3">
								<label>Сроки проведения:</label>
							</div>
							<div class="col-md-7">
								<input class='form-control' type='text' value='{{$additional_document->date_protocol}}' readonly />
							</div>
						</div>
						<div class='row'>
							<div class="col-md-3">
								<label>Дата протокола на 1 л.:</label>
							</div>
							<div class="col-md-7">
								<input class='form-control' type='text' value='{{$additional_document->date_on_first_protocol}}' readonly />
							</div>
						</div>
						<div class='row'>
							<div class="col-md-3">
								<label>Дата регистрации:</label>
							</div>
							<div class="col-md-7">
								<input class='form-control' type='text' value='{{$additional_document->date_registration_protocol}}' readonly />
							</div>
						</div>
						<div class='row'>
							<div class="col-md-3">
								<label>Дата подписания ФКП "НТИИМ"</label>
							</div>
							<div class="col-md-7">
								<input class='form-control' type='text' value='{{$additional_document->date_signing_protocol}}' readonly />
							</div>
						</div>
						<div class='row'>
							<div class="col-md-3">
								<label>Дата подписания контрагентом</label>
							</div>
							<div class="col-md-7">
								<input class='form-control' type='text' value='{{$additional_document->date_signing_counterpartie_protocol}}' readonly />
							</div>
						</div>
						<div class='row'>
							<div class="col-md-3">
								<label>Дата вступления в силу</label>
							</div>
							<div class="col-md-7">
								<input class='form-control' type='text' value='{{$additional_document->date_entry_ento_force_additional_agreement}}' readonly />
							</div>
						</div>
						<div class='row'>
							<div class="col-md-10">
								<div class='row'>
									<div class="col-md-2">
										<label>Дата сдачи на хранение в ОУД</label>
									</div>
									<div class="col-md-4">
										<div class='row'>
											<div class="col-md-12">
												<label for='is_oud_el'>скан (эл. вариант)</label>
												@if($additional_document->is_oud_el)
													<input id='is_oud_el' class='form-check-input' type="checkbox" disabled checked />
												@else
													<input id='is_oud_el' class='form-check-input' type="checkbox" disabled />
												@endif
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<label for='is_oud'>оригинал</label>
												@if($additional_document->is_oud)
													<input id='is_oud' class='form-check-input' type="checkbox" disabled checked />
												@else
													<input id='is_oud' class='form-check-input' type="checkbox" disabled />
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class='row'>
											<div class="col-md-12">
												<input class='form-control' name='date_oud_el_protocol' type='text' value='{{$additional_document->date_oud_el_protocol}}' readonly />
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<input class='form-control' name='date_oud_protocol' type='text' value='{{$additional_document->date_oud_protocol}}' readonly />
											</div>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-2">
										<label>Дата сдачи на хранение в отдел №31</label>
									</div>
									<div class="col-md-4">
										<div class='row'>
											<div class="col-md-12">
												<label for='is_dep_el'>скан (эл. вариант)</label>
												@if($additional_document->is_dep_el)
													<input id='is_dep_el' class='form-check-input' type="checkbox" disabled checked />
												@else
													<input id='is_dep_el' class='form-check-input' type="checkbox" disabled />
												@endif
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<label for='is_dep'>оригинал</label>
												@if($additional_document->is_dep)
													<input id='is_dep' class='form-check-input' type="checkbox" disabled checked />
												@else
													<input id='is_dep' class='form-check-input' type="checkbox" disabled />
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class='row'>
											<div class="col-md-12">
												<input class='form-control' name='date_dep_el_protocol' type='text' value='{{$additional_document->date_dep_el_protocol}}' readonly />
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
												<input class='form-control' name='date_dep_protocol' type='text' value='{{$additional_document->date_dep_protocol}}' readonly />
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class='row'>
							<div class="col-md-1">
								<label>Сумма начальная с НДС, руб.</label>
							</div>
							<div class="col-md-2">
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
										<input class='form-control' type='text' value='{{$additional_document->amount_protocol}}' readonly />
									</div>
								</div>
								<div class='row'>
									<div class="col-md-12">
										<input class='form-control' type='text' value='{{$additional_document->amount_year_protocol}}' readonly />
									</div>
								</div>
							</div>
						</div>
						<div class='row'>
							<div class="col-md-2">
								<label>Сканы:</label>
							</div>
							<div class="col-md-4">
								<select id='resolution_list' class='form-control'>
									@if(count($resolutions) > 0)
										@foreach($resolutions as $res)
											@if(Session::has('new_scan'))
												@if(Session('new_scan')->id == $res->id)
													<option value='http://{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}' selected>{{$res->real_name_resolution}}</option>
												@else
													<option value='http://{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}'>{{$res->real_name_resolution}}</option>
												@endif
											@else
												@if($res->type_resolution == 1)
													<option value='http://{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}' style='color: rgb(239,19,198);'>{{$res->real_name_resolution}}</option>
												@else
													<option value='http://{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}'>{{$res->real_name_resolution}}</option>
												@endif
											@endif
										@endforeach
									@else
										<option></option>
									@endif
								</select>
							</div>
							<div class="col-md-4">
								<button id='open_resolution' type='button' class='btn btn-primary' style='width: 122px;'>Открыть скан</button>
							</div>
						</div>
						<div class='row'>
							<div class="col-md-2">
							</div>
							<div class="col-md-4">
								@if(Auth::User()->hasRole()->role != 'Администрация')
									<button type='button' data-toggle="modal" data-target="#new_resolution"  class='btn btn-primary' style='width: 122px;' onclick="$('#formToStoreNewResolution').attr('action', $(this).attr('href_resolution'));" href_resolution='{{route("resolution_store",$additional_document->id)}}'>Добавить скан</button>
								@endif
							</div>
						</div>
					@endif
					<div class='row'>
						<div class="col-md-2" style='display: none;'>
							<input type='text' value='' name=''/>
						</div>
						<div class="col-md-2">
							<label>Направлено:</label>
						</div>
						<div id='directed_list' class="col-md-4" style='max-height: 135px; overflow-y: auto;'>
							@foreach($directed_list as $user)
								<!-- <div class='chip'>{{ $user->surname . ' ' . $user->name . ' ' . $user->patronymic }}<span class='closebtn' onclick="this.parentElement.style.display='none'">&times;</span></div> -->
								<?php
									/*if($user->check_reconciliation == 1)
										echo '<label>' . $user->surname . ' ' . $user->name . ' ' . $user->patronymic . '<span class="closebtn btn-ajax-delete-directed" ajax-href="' . route('reconciliation.application.delete_direction') . '" ajax-data="id_application=' . $application->appID . '&id_user=' . $user->userID .'">&times;</span></label><br/>';
									else
										echo '<label style="color: red;">' . $user->surname . ' ' . $user->name . ' ' . $user->patronymic . '<span class="closebtn btn-ajax-delete-directed" ajax-href="' . route('reconciliation.application.delete_direction') . '" ajax-data="id_application=' . $application->appID . '&id_user=' . $user->userID .'">&times;</span></label><br/>';
									*/
									if($user->check_reconciliation == 1)
										echo '<label>' . $user->surname . ' ' . $user->name . ' ' . $user->patronymic . '</label><br/>';
									else
										echo '<label style="color: red;">' . $user->surname . ' ' . $user->name . ' ' . $user->patronymic . '</label><br/>';
								?>
							@endforeach
						</div>
						<div class="col-md-4">
							<div class='row'>
								<div class="col-md-12">
									<input id='comment_label' name='check_comment' class='form-check-input' type="checkbox" />
									<label for='comment_label'>Комментарий</label>
								</div>
								<div class="col-md-12">
									<textarea id='textarea_for_comment' name='new_comment' class='form-control' type="text" style="width: 100%;" rows='4' readonly ></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							<div class='row'>
								<div class="col-md-4">
									<label>Выберите согласующего:</label>
								</div>
								<div class="col-md-8">
									<select id='directed_select' class='form-control'>
										<option></option>
										@foreach($all_users as $user)
											<option value='{{$user->id}}' real_name='{{ $user->surname . ' ' . $user->name . ' ' . $user->patronymic }}'>{{ $user->surname . ' ' . $user->name . ' ' . $user->patronymic }}</option>
										@endforeach
									</select>
								</div>
								<div class="col-md-4"><button class='btn btn-primary' style='margin-top: 5px;' data-toggle="modal" data-target="#directed_modal_list" type='button'>Выбрать согласующих</button></div>
								<div class="col-md-8" style='margin-top: 15px;'>
									<!--<button id='add_direction' type='button' class='btn btn-primary' style='width: 122px;'>Добавить</button>-->
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<button type='button' class='btn btn-primary' style='width: 122px; margin-top: 4px;'  data-toggle="modal" data-target="#comments">Коментарии</button>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-8" style='text-align: right;'>
							<button type='submit' class='btn btn-secondary'>Выход без согласования</button>
						</div>
						<div class="col-md-2" style='text-align: right;'>
							<button type='submit' class='btn btn-primary' style='width: 122px;' name='btn_reconciliation' value='1'>Согласовать</button>
						</div>
					</div>
				</form>
			</div>
			<!-- Модальное окно согласующих -->
			<div class="modal fade" id="directed_modal_list" tabindex="-1" role="dialog" aria-labelledby="directedListModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document" style='width: 80%; height: 80%;'>
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="directedListModalLabel">Согласующие</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body" style='height: 78%; overflow-y: auto;'>
							<div class='row'>
								<?php $count_check_directed = 0; ?>
									@foreach($check_all_users_list as $users_list)
									<div class='col-md-4'>
										<table class="table" style='margin: 0 auto;'>
											<thead>
												<tr>
													<th></th>
													<th>ФИО</th>
												</tr>
											</thead>
											<tbody>
												@foreach($users_list as $user)
													<tr class='rowsContract cursorPointer rowsMessage' for_check='check_message{{$count_check_directed}}'>
														<td>
															<input id='check_message{{$count_check_directed}}' class='form-check-input check_directed_list' type="checkbox" value='{{$user->id}}' real_name='{{ $user->surname . ' ' . $user->name . ' ' . $user->patronymic }}'/>
														</td>
														<td>
															{{ $user->surname . ' ' . $user->name . ' ' . $user->patronymic }}
														</td>
													</tr>
													<?php $count_check_directed++; ?>
												@endforeach
											</tbody>
										</table>
									</div>
								@endforeach
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
							<button id='add_modal_direction' type="button" class="btn btn-primary">Добавить</button>
						</div>
					</div>
				</div>
			</div>
			<!-- Модальное окно комментариев -->
			<div class="modal fade" id="comments" tabindex="-1" role="dialog" aria-labelledby="commentsModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="commentsModalLabel">Комментарии</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							@foreach($comments as $comment)
								<div class='row'>
									<div class="col-md-12">
										<label>{{$comment->created_at . ' ' . $comment->surname . ' ' . $comment->name . ' ' . $comment->patronymic}}</label>
									</div>
									<div class="col-md-12">
										<textarea class='form-control' type="text" style="width: 100%;" rows='3' readonly >{{$comment->message}}</textarea>
									</div>
								</div>
							@endforeach
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
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
