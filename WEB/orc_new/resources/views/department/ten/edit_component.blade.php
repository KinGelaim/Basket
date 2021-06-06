@extends('layouts.header')

@section('title')
	Редактировть комплекацию
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор' OR Auth::User()->hasRole()->role == 'Десятый отдел')
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							Редактирование комплектующего элемента
						</div>
					</div>
					<div class='row'>
						<div class="col-md-3">
							<form method='POST' action='{{route("ten.update_component", $component->id)}}'>
								{{csrf_field()}}
								<div class='row'>
									<div class="col-md-4">
										<label>Элемент</label>
									</div>
									<div class="col-md-8">
										<select class="form-control" name='element' required>
											<option value=""></option>
											@if($elements)
												@foreach($elements as $element)
													@if($element->id == $component->id_element_component)
														<option value='{{$element->id}}' selected>{{ $element->name_component }}</option>
													@else
														<option value='{{$element->id}}'>{{ $element->name_component }}</option>
													@endif
												@endforeach
											@endif
										</select>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										<label>Необходимое количество</label>
									</div>
									<div class="col-md-8">
										<input class='form-control' type='text' value="{{ old('need_count') ? old('need_count') : $component->need_count }}" name='need_count'/>
									</div>
								</div>
								<div class='row'>
									<div class="col-md-4">
										
									</div>
									<div class="col-md-8">
										<button type="submit" class="btn btn-primary" style="float: right;">Сохранить</button>
									</div>
								</div>
							</form>
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
