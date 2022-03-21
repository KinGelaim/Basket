@extends('layouts.header')

@section('title')
	Страница примеров
@endsection

@section('content')	
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div class='row'>
				<div class='col-md-3'>
					<div class='form-group'>
						<button id='tezis-open' class='btn btn-primary'>Тезис</button>
					</div>
					<div id='block-for-tezis' style='display: none; padding: 10px; border: 1px solid black; border-radius: 10px; background-color: white;'>
						<a href='http://tezis.ntiim.local:8080/app/o?u=75277' target='_blank'>СТП 75 19308-46-2007</a>
						<br/>
						<a href='http://tezis.ntiim.local:8080/app/o?u=75280' target='_blank'>СТП 75 19308-59-2009</a>
					</div>
				</div>
			</div>
			<div class='row'>
				<div class='col-md-3'>
					<label>{{mt_rand()}}</label>
				</div>
				<div class='col-md-3'>
					<label>{{mt_rand()}}</label>
				</div>
				<div class='col-md-3'>
					<label>{{uniqid(mt_rand(), true)}}</label>
				</div>
				<div class='col-md-3'>
					<label>{{uniqid(mt_rand(), true)}}</label>
				</div>
			</div>
			<div class='row'>
				<div class='col-md-3'>
					<label>{{mt_rand()}}</label>
				</div>
				<div class='col-md-3'>
					<label>{{mt_rand()}}</label>
				</div>
				<div class='col-md-3'>
					<label>{{uniqid(mt_rand(), false)}}</label>
				</div>
				<div class='col-md-3'>
					<label>{{uniqid(mt_rand(), false)}}</label>
				</div>
			</div>
			<script>
				var k = true;
				$('#tezis-open').on('click', function(){
					if(k)
						$('#block-for-tezis').css('display', 'block');
					else
						$('#block-for-tezis').css('display', 'none');
					k = !k;
				});
			</script>
		@else
			<div class="alert alert-danger">
				Для просмотра данной страницы необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection