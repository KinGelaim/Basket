@extends('layouts.header')

@section('title')
	Карточка комплектации документа
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Десятый отдел' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							Информация о комплектации: {{$contract->number_contract}}</b>
						</div>
					</div>
					<div class="row">
						<div class="col-md-10">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>Элемент</th>
										<th>Необходимое количество</th>
										<th>Номер партии</th>
										<th>Редактирование</th>
									</tr>
								</thead>
								<tbody>
									@foreach($components as $component)
										<tr class="rowsContract">
											<td>{{$component->name_component}}</td>
											<td>{{$component->need_count}}</td>
											<td>{{$component->name_party}}</td>
											<td>
												@if($component->name_party == null)
													@if(Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Отдел управления договорами')
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
									<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#newReconciliation"  style="margin-top: 26px; float: right; margin-right: 10px; width: 227px;" disabled>Согласование</button>
								</div>
							</div>
							@if(Auth::User()->hasRole()->role != 'Планово-экономический отдел' AND Auth::User()->hasRole()->role != 'Отдел управления договорами')
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
						</div>
					</div>
					<!-- Модальное окно новой комплектации -->
					<div class="modal fade" id="newComponent" tabindex="-1" role="dialog" aria-labelledby="newComponentModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="contract">
							<div class="modal-content">
								<form method='POST' action='{{route("ten.store_new_component", $contract->id)}}'>
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
						<div class="modal-dialog" role="contract">
							<div class="modal-content">
								<form id='formStoreOldComponent' method='POST' action='{{route("ten.store_old_component", $contract->id)}}'>
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
