@extends('layouts.header')

@section('title')
	Карточка комплектации контракта
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Планово-экономический отдел' OR Auth::User()->hasRole()->role == 'Десятый отдел')
				<div class="content">
					<div class="row">
						<div class="col-md-6">
							Информация о комплектации контракта
						</div>
						<div class="col-md-6">
							Информация о предварительной комплектации
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>Элемент</th>
										<th>Необходимое количество</th>
										<th>Количество на складе</th>
										<th>Удалить</th>
									</tr>
								</thead>
								<tbody>
									@foreach($components as $component)
										<tr class="rowsContract">
											<td>{{$component->name_component}}</td>
											<td>{{$component->count_components}}</td>
											<td>{{$component->count_element}}</td>
											<td>
												<button type='button' class='btn btn-danger btn-href' href="{{route('ten.delete_component_contract', $component->componentsID)}}">Удалить</button>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						<div class="col-md-6">
							<table class="table" style='margin: 0 auto; margin-top:20px; margin-bottom: 10px;'>
								<thead>
									<tr>
										<th>Элемент</th>
										<th>Количество доступное для прикрепления</th>
										<th>Количество для прикрепления</th>
										<th>Прикрепить</th>
									</tr>
								</thead>
								<tbody>
									@foreach($all_components as $component)
										<form class='formChoseComponentForContract' method='POST' action="{{route('ten.chose_contract', $component->id)}}" check-count='{{$component->need_count}}' href-to-change-components="{{route('ten.document_components', $contract->id_document_contract)}}">
											{{csrf_field()}}
											<tr class="rowsContract">
												<td style='display: none;'><input name='id_contract[0]' value='{{$contract->id}}'/></td>
												<td>{{$component->name_component}}</td>
												<td>{{$component->need_count}}</td>
												<td>
													<input class='form-control count_element' type='text' value="" name='count_element[0]' required />
												</td>
												<td>
													<button type='submit' class='btn btn-primary'>Прикрепить</button>
												</td>
											</tr>
										</form>
									@endforeach
								</tbody>
							</table>
						</div>
						<!--<div class="col-md-2">
							<div class='row'>
								<div class="col-md-12">
									
								</div>
							</div>
							<div class='row'>
								<div class="col-md-12">
									<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#newReconciliation"  style="margin-top: 26px; float: right; margin-right: 10px; width: 227px;">Согласование</button>
								</div>
							</div>
						</div>-->
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
