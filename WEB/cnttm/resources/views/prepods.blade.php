@extends('layouts.app')

@section('title')
	Преподавательский состав
@endsection

@section('content')
	<div class="banner-1">
		
	</div>
	<div class="clients">
		<div class="container">
			<?php $count = 0; ?>
			@foreach($educators as $educator)
				@if($count == 0)
					<div class="cli-top">
					<?php $count=3; ?>
				@endif
				@if($educator->deleted_at == null)
					<div class="col-md-4 clients-left">
						<img src="images/{{$educator->photo[0]}}" alt=" " class="img-responsive" />
						<h5><a>{{$educator->surname . ' ' . mb_substr($educator->name, 0, 1) . '.' . mb_substr($educator->patronymic, 0, 1) . '.'}}</a></h5>
						<p>{{$educator->short_information}}</p>
						<a class="hvr-shutter-in-horizontal myModal pointer-cursor" modal='#showPrepod' prepod='{{$educator}}'>Подробнее</a>
					</div>
					<?php $count--; ?>
				@endif
				@if($count == 0)
					<div class="clearfix"></div>
					</div>
				@endif
			@endforeach
			@if($count != 0)
				<div class="clearfix"></div>
				</div>
			@endif
		</div>
	</div>
	<!-- Модалка -->
	<div class='modal fade' id='showPrepod' tabindex='-1' role='dialog' aria-labelledby='showPrepodLabel' aria-hidden='true'>
		<div class='modal-dialog' role='document'>
			<div class='modal-content'>
				<div class='modal-header'>
					<h4 class='modal-title' id='showPrepodLabel'>Препод</h4>
					<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
						<span aria-hidden='true'>&times;</span>
					</button>
				</div>
				<div class='modal-body'>
					
				</div>
				<div class='modal-footer'>
					<button type='button' class='btn btn-secondary' data-dismiss='modal'>Закрыть</button>
				</div>
			</div>
		</div>
	</div>
@endsection
