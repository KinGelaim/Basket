@extends('layouts.header')

@section('title')
	Карточка комплектации документа
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Десятый отдел')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							Информация о комплектации: <b>{{$application->name_counterpartie_contract}}</b>
						</div>
					</div>
					@if(count($reconciliations) > 0)
						<div class="row">
							<div class="col-md-10">
								<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
									<thead>
										<tr>
											<th>Контракты</th>
											<th>Элемент</th>
											<th>Необходимое количество</th>
											<th>Количество на складе</th>
											<th>Номер партии</th>
											<th>Редактирование</th>
										</tr>
									</thead>
									<tbody>
										@foreach($components as $component)
											<tr class="rowsContract">
												<td>
													@if($component->isChoseContract)
														@foreach($component->number_contract as $number)
															{{ $number }} <br/>
														@endforeach
													@else
														@if(Auth::User()->hasRole()->role == 'Планово-экономический отдел')
															<button class="btn btn-primary" type="button" disabled>Прикрепить</button>
														@else
															<button class="btn btn-primary btn-href" type="button" href="{{route('ten.chose_all_contract', $component->id)}}">Прикрепить</button>
														@endif
													@endif
												</td>
												<td>{{$component->name_component}}</td>
												<td>{{$component->need_count}}</td>
												<td>{{$component->count_element}}</td>
												<td>{{$component->name_party}}</td>
												<td>
													@if($component->name_party == null)
														@if(Auth::User()->hasRole()->role == 'Планово-экономический отдел')
															<button class="btn btn-primary" type="button" disabled>Редактировать</button>
														@else
															<button class="btn btn-primary btn-href" type="button" href="{{route('ten.edit_component_pack', $component->id_pack)}}">Редактировать</button>
														@endif
													@endif
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
							<div class="col-md-2">
								<div class='row'>
									<div class="col-md-12">
										<button class="btn btn-primary btn-href" type="button" style="margin-top: 26px; float: right; margin-right: 10px; width: 227px;" href="{{route('department.reconciliation.document', $application->number_application)}}">Просмотр карточки заявки</button>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-12">
										<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#newReconciliation"  style="margin-top: 26px; float: right; margin-right: 10px; width: 227px;">Согласование</button>
									</div>
								</div>
								@if(Auth::User()->hasRole()->role != 'Планово-экономический отдел')
									<div class='row'>
										<div class="col-md-12">
											<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#newComponent" style="margin-top: 26px; float: right; margin-right: 10px; width: 227px;">Добавить новый элемент</button>
										</div>
									</div>
									<div class='row'>
										<div class="col-md-12">
											<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#oldComponent" style="margin-top: 26px; float: right; margin-right: 10px; width: 227px;">Добавить элемент со склада</button>
										</div>
									</div>
								@endif
								<!--<div class='row'>
									<div class="col-md-12">
										<button class="btn btn-primary btn-href" type="button" style="margin-top: 26px; float: right; margin-right: 10px; width: 227px;" href="{{route('ten.chose_all_component', $document->id)}}">Прикрепить комплектацию</button>
									</div>
								</div>-->
							</div>
						</div>
						<!-- Модальное окно новой комплектации -->
						<div class="modal fade" id="newComponent" tabindex="-1" role="dialog" aria-labelledby="newComponentModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<form method='POST' action='{{route("ten.store_new_component", $document->id)}}'>
										{{csrf_field()}}
										<div class="modal-header">
											<h5 class="modal-title" id="newComponentModalLabel">Новая комплектация</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body">
											<div class='row'>
												<div class="col-md-3">
													<label>Элемент</label>
												</div>
												<div class="col-md-9">
													<select class="form-control" name='element' required>
														<option value=""></option>
														@if($elements)
															@foreach($elements as $element)
																	<option value='{{$element->id}}'>{{ $element->name_component }}</option>
															@endforeach
														@endif
													</select>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-3">
													<label>Необходимое количество</label>
												</div>
												<div class="col-md-9">
													<input class='form-control' type='text' value='' name='need_count' required/>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="submit" class="btn btn-primary">Сохранить</button>
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
										</div>
									</form>
								</div>
							</div>
						</div>
						<!-- Модальное окно комплектации со склада-->
						<div class="modal fade" id="oldComponent" tabindex="-1" role="dialog" aria-labelledby="oldComponentModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<form id='formStoreOldComponent' method='POST' action='{{route("ten.store_old_component", $document->id)}}'>
										{{csrf_field()}}
										<div class="modal-header">
											<h5 class="modal-title" id="oldComponentModalLabel">Комплектация со склада</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body">
											<div class='row'>
												<div class="col-md-3">
													<label>Элемент</label>
												</div>
												<div class="col-md-9">
													<select id='selectOldComponent' class="form-control" name='element' required>
														<option value=""></option>
														@if($old_elements)
															@foreach($old_elements as $element)
																	<option value='{{$element->id}}' count_party='{{$element->count_party}}' name_party='{{$element->name_party}}' date_party='{{$element->date_party}}'>{{ $element->name_component }}</option>
															@endforeach
														@endif
													</select>
												</div>
											</div>
											<div class='row'>
												<div class="col-md-3">
													<label>Номер партии</label>
												</div>
												<div class="col-md-9">
													<input id='name_party' class='form-control' type='text' value='' readonly />
												</div>
											</div>
											<div class='row'>
												<div class="col-md-3">
													<label>Дата партии партии</label>
												</div>
												<div class="col-md-9">
													<input id='date_party' class='form-control' type='text' value='' readonly />
												</div>
											</div>
											<div class='row'>
												<div class="col-md-3">
													<label>Количество на складе</label>
												</div>
												<div class="col-md-9">
													<input id='count_party' class='form-control' type='text' value='' readonly />
												</div>
											</div>
											<div class='row'>
												<div class="col-md-3">
													<label>Необходимое количество</label>
												</div>
												<div class="col-md-9">
													<input id='need_count' class='form-control' type='text' value='' name='need_count' required/>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="submit" class="btn btn-primary">Сохранить</button>
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					@else
						<div class="alert alert-danger">
							Процесс согласования еще не запущен!
						</div>
						<div class='row'>
							<div class="col-md-12">
								<button class="btn btn-primary" data-toggle="modal" data-target="#newReconciliation" type="button">Запустить процесс согласования</button>
							</div>
						</div>
					@endif
					<!-- Модальное окно согласования -->
					<div class="modal fade" id="newReconciliation" tabindex="-1" role="dialog" aria-labelledby="newReconciliationModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<form id='post_send' method='POST' action='{{route("reconciliation.document.store", $application->id)}}'>
									{{csrf_field()}}
									<div class="modal-header">
										<h5 class="modal-title" id="newReconciliationModalLabel">Согласование</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<div class='row'>
											<div class="col-md-12">
												<label>Направлено:</label>
											</div>
											<div id='directed_list' class="col-md-7" style='max-height: 135px; overflow-y: auto;'>
												@foreach($directed_list as $user)
													<?php
														/*if($user->check_reconciliation == 1)
															echo '<label>' . $user->surname . ' ' . $user->name . ' ' . $user->patronymic . '<span class="closebtn btn-ajax-delete-directed" ajax-href="' . route('reconciliation.document.delete_direction') . '" ajax-data="id_application=' . $application->id . '&id_user=' . $user->userID .'">&times;</span></label><br/>';
														else
															echo '<label style="color: red;">' . $user->surname . ' ' . $user->name . ' ' . $user->patronymic . '<span class="closebtn btn-ajax-delete-directed" ajax-href="' . route('reconciliation.document.delete_direction') . '" ajax-data="id_application=' . $application->id . '&id_user=' . $user->userID .'">&times;</span></label><br/>';
														*/
														if($user->check_reconciliation == 1)
															echo '<label>' . $user->surname . ' ' . $user->name . ' ' . $user->patronymic . '</label><br/>';
														else
															echo '<label style="color: red;">' . $user->surname . ' ' . $user->name . ' ' . $user->patronymic . '</label><br/>';
													?>
												@endforeach
											</div>
											<div class="col-md-12">
												<div class='row'>
													<div class="col-md-8">
														<select id='directed_select' class='form-control'>
															<option></option>
															@foreach($all_users as $user)
																<option value='{{$user->id}}' real_name='{{ $user->surname . ' ' . $user->name . ' ' . $user->patronymic }}'>{{ $user->surname . ' ' . $user->name . ' ' . $user->patronymic }}</option>
															@endforeach
														</select>
													</div>
													<div class="col-md-8" style='margin-top: 15px;'>
														<!--<button id='add_direction' type='button' class='btn btn-primary' style='width: 122px;'>Добавить</button>-->
													</div>
												</div>
											</div>
										</div>
										<div class='row'>
											<div class="col-md-12">
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
											<div class="col-md-12">
												<button type='button' class='btn btn-primary' style='width: 122px; margin-top: 4px;'  data-toggle="modal" data-target="#comments">Коментарии</button>
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
