@extends('layouts.header')

@section('title')
	Документ (согласование)
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class="container">
				<form id='post_send' method='POST' action='{{route("reconciliation.application.store", $application->appID)}}'>
					{{csrf_field()}}
					@if($application->is_protocol == 1)
						@if($is_new_app)
							<div class='row'>
								<div class="col-md-2" style='display: none;'>
									
								</div>
								<div class="col-md-2">
									@if($application->is_additional_agreement != 1)
										<label>Название протокола:</label>
									@else
										<label>Название доп.согл:</label>
									@endif
								</div>
								<div class="col-md-8">
									<input class='form-control ' type='text' value="{{ $application->name_protocol }}" name='name_protocol' required/>
								</div>
							</div>
							<div class='row'>
								<div class="col-md-2" style='display: none;'>
									
								</div>
								<div class="col-md-2">
									@if($application->is_additional_agreement != 1)
										<label>Комментарий к протоколу:</label>
									@else
										<label>Комментарий к доп.согл:</label>
									@endif
								</div>
								<div class="col-md-8">
									<textarea class='form-control' type="text" style="width: 100%;" rows='4' name='comment_application'>{{$application->comment_application}}</textarea>
								</div>
							</div>
						@else
							<div class='row'>
								<div class="col-md-2" style='display: none;'>
									
								</div>
								<div class="col-md-2">
									<label>Название протокола:</label>
								</div>
								<div class="col-md-8">
									<input class='form-control ' type='text' value="{{ $application->name_protocol }}" readonly />
								</div>
							</div>
							<div class='row'>
								<div class="col-md-2" style='display: none;'>
									
								</div>
								<div class="col-md-2">
									<label>Комментарий к протоколу:</label>
								</div>
								<div class="col-md-8">
									<textarea class='form-control' type="text" style="width: 100%;" rows='4' readonly >{{$application->comment_application}}</textarea>
								</div>
							</div>
						@endif
					@endif
					<div class='row'>
						<div class="col-md-2">
							<label>№ записи:</label>
						</div>
						<div class="col-md-4">
							<input class='form-control' type='text' value='{{$application->number_application}}' readonly />
						</div>
					</div>
					<div class='row'>
						<div class="col-md-2">
							<label>Контрагент:</label>
						</div>
						<div class="col-md-8">
							<input class='form-control' type='text' value='{{$application->counterpartie_name}}' readonly />
						</div>
					</div>
					<div class='row'>
						<div class="col-md-2">
							<label>№ исх.:</label>
						</div>
						<div class="col-md-4">
							<input class='form-control' type='text' value='{{$application->number_outgoing}}' readonly />
						</div>
						<div class="col-md-4">
							<input class='form-control' type='text' value="{{ $application->date_outgoing ? date('d.m.Y', strtotime($application->date_outgoing)) : '' }}" readonly />
						</div>
					</div>
					<div class='row'>
						<div class="col-md-2">
							<label>№ вх.:</label>
						</div>
						<div class="col-md-4">
							<input class='form-control' type='text' value='{{$application->number_incoming}}' readonly />
						</div>
						<div class="col-md-4">
							<input class='form-control ' type='text' value="{{ $application->date_incoming ? date('d.m.Y', strtotime($application->date_incoming)) : '' }}" readonly />
						</div>
					</div>
					<div class='row'>
						<div class="col-md-2">
							<label>Тема:</label>
						</div>
						<div class="col-md-8">
							<textarea class='form-control' type="text" style="width: 100%;" rows='4' readonly >{{$application->theme_application}}</textarea>
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
											<option value='http://{{$res->path_resolution}}' download_href='resolution_download/{{$res->id}}'>{{$res->real_name_resolution}}</option>
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
								<button type='button' data-toggle="modal" data-target="#new_resolution"  class='btn btn-primary' style='width: 122px;'>Добавить скан</button>
							@endif
						</div>
					</div>
					<!-- Старое начало протокола -->
					<div class='row'>
						<div class="col-md-2" style='display: none;'>
							<input type='text' value='{{$application->recID}}' name='id_reconciliation'/>
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
							@if($is_new_app)
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
									<div class="col-md-4"></div>
									<div class="col-md-8" style='margin-top: 15px;'>
										<!--<button id='add_direction' type='button' class='btn btn-primary' style='width: 122px;'>Добавить</button>-->
									</div>
								</div>
							@endif
						</div>
						<div class="col-md-4">
							<button type='button' class='btn btn-primary' style='width: 122px; margin-top: 4px;'  data-toggle="modal" data-target="#comments">Коментарии</button>
						</div>
					</div>
					@if($is_new_app)
						<div class='row'>
							<div class="col-md-8" style='text-align: right;'>
								<button type='submit' class='btn btn-secondary'>Выход без согласования</button>
							</div>
							<div class="col-md-2" style='text-align: right;'>
								<button type='submit' class='btn btn-primary' style='width: 122px;' name='btn_reconciliation' value='1'>Согласовать</button>
							</div>
						</div>
					@endif
				</form>
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
						<form method='POST' file='true' enctype='multipart/form-data' action='{{route("resolution_store",$application->appID)}}'>
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
											<input type='text' value='id_application_resolution' name='real_name_document'/>
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
