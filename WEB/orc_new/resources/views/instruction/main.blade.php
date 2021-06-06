@extends('layouts.header')

@section('title')
	Инструкция
@endsection

@section('content')
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			<div id='content' class="content">
				<div class='row'>
					<div class='col-md-2'>
						<ul class="nav">
							<li class="nav-header">Главная</li>
							<li class="active"><a href="#">Регистрация</a></li>
							<li><a href="#">Авторизация</a></li>
							<li class="nav-header">Согласование</li>
							<li><a href="#">Главная страница</a></li>
							<li><a href="#">Страница согласования</a></li>
							<li class="nav-divider"></li>
							<li><a href="#">Канцелярия</a></li>
						</ul>
					</div>
					<div class='col-md-10'>
						
					</div>
				</div>
			</div>
		@else
			<div class="alert alert-danger">
				Необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection
