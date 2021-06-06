@extends('layouts.header')

@section('title')
	Документ (согласование)
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="container">
				@if(Auth::User()->hasRole()->role != 'Планово-экономический отдел')
					<div class='row'>
						<div class="col-md-2">
							
						</div>
						<div class="col-md-4">
							<button type='button' class='btn btn-primary btn-href' href="{{route('tree_map.show_contract', $contract->contractID)}}">Граф договора</button>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-2">
							<label>№ записи:</label>
						</div>
						<div class="col-md-4">
							<input class='form-control' type='text' value='{{$contract->number_contract}}' readonly />
						</div>
					</div>
					<div class='row'>
						<div class="col-md-2">
							<label>Контрагент:</label>
						</div>
						<div class="col-md-8">
							<input class='form-control' type='text' value='{{$contract->counterpartie_name}}' readonly />
						</div>
					</div>
					<div class='row'>
						<div class="col-md-2">
							<label>Предмет договора:</label>
						</div>
						<div class="col-md-8">
							<textarea class='form-control' type="text" style="width: 100%;" rows='4' readonly >{{$contract->item_contract}}</textarea>
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
							<button type='button' data-toggle="modal" data-target="#new_resolution"  class='btn btn-primary' style='width: 122px;'>Добавить скан</button>
						</div>
					</div>
				@endif
				<form id='post_send' method='POST' action='{{route("reconciliation.contract.store", $contract->contractID)}}'>
					{{csrf_field()}}
					<div class='row'>
						<div class="col-md-2" style='display: none;'>
							<input type='text' value='{{$contract->recID}}' name='id_reconciliation'/>
						</div>
						<div class="col-md-2">
							<label>Направлено:</label>
						</div>
						<div id='directed_list' class="col-md-4" style='max-height: 135px; overflow-y: auto;'>
							@foreach($directed_list as $user)
								<!-- <div class='chip'>{{ $user->surname . ' ' . $user->name . ' ' . $user->patronymic }}<span class='closebtn' onclick="this.parentElement.style.display='none'">&times;</span></div>-->
								<?php
									if(Auth::User()->hasRole()->role == 'Администратор')
										if($user->check_reconciliation == 1)
											echo '<label>' . $user->surname . ' ' . $user->name . ' ' . $user->patronymic . '<span class="closebtn btn-ajax-delete-directed" ajax-href="' . route('reconciliation.contract.delete_direction') . '" ajax-data="id_contract=' . $contract->contractID . '&id_user=' . $user->userID .'">&times;</span></label><br/>';
										else
											echo '<label style="color: red;">' . $user->surname . ' ' . $user->name . ' ' . $user->patronymic . '<span class="closebtn btn-ajax-delete-directed" ajax-href="' . route('reconciliation.contract.delete_direction') . '" ajax-data="id_contract=' . $contract->contractID . '&id_user=' . $user->userID .'">&times;</span></label><br/>';
									else
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
					@if($is_new_app)
						<div class='row'>
							<div class="col-md-8" style='text-align: right;'>
								<button type='submit' class='btn btn-secondary' name='btn_reconciliation' value='2'>Выход без согласования</button>
							</div>
							<div class="col-md-2" style='text-align: right;'>
								<button type='submit' class='btn btn-primary' style='width: 122px;' name='btn_reconciliation' value='1'>Согласовать</button>
							</div>
						</div>
					@else
						<div class='row'>
							<div class="col-md-8" style='text-align: right;'>
								<button type='button' class='btn btn-secondary' onclick='history.back();' style='width: 122px;'>Закрыть</button>
							</div>
							<div class="col-md-2" style='text-align: right;'>
								<button type='submit' class='btn btn-primary' style='width: 122px;'>Направить</button>
							</div>
						</div>
					@endif
				</form>
			</div>
			<!-- Модальное окно согласующих -->
			<div class="modal fade" id="directed_modal_list" tabindex="-1" role="dialog" aria-labelledby="directedListModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document" style="width: 80%; height: 80%">
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
						<form method='POST' file='true' enctype='multipart/form-data' action='{{route("resolution_store",$contract->contractID)}}'>
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
											<input type='text' value='id_contract_resolution' name='real_name_document'/>
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
