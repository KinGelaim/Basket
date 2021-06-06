<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<title>@yield('title')</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<!-- Styles -->
	<link href="{{ asset('css/bootstrap.css') }}" rel='stylesheet' type='text/css' />
	<link href="{{ asset('css/style.css') }}" rel='stylesheet' type='text/css' />
	<link href="{{ asset('css/flexslider.css') }}" rel="stylesheet"  type="text/css" media="screen" />
	<link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet" />
	
	<!-- Scripts -->
	<script src="{{ asset('js/jquery.min.js') }}" type="text/javascript" ></script>
	<script src="{{ asset('js/bootstrap.js') }}" type="text/javascript" ></script>
	<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
	<script src="{{ asset('js/myjs.js') }}"></script>
	
	<!-- Icon -->
	<link rel="shortcut icon" href="{{ asset('images/favicon.jpg') }}" type="image/jpg" />
</head>
<body>
    <div id="app">
		<div class="header-top">
			<div class="container">
				<div class="head-main">
					<h1><a href="{{route('home')}}">ЦНТТМ</a></h1>
				</div>
				<div class="hea-rgt">
					@guest
						<a href="{{ route('login') }}">Авторизация</a>
					@else
						<ul class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
								{{ Auth::user()->login }} 
								<span class="caret"></span>
							</a>

							<ul class="dropdown-menu">
								<li>
									@if(Auth::User()->hasRole()->role == 'Администратор' || Auth::User()->hasRole()->role == 'Преподаватель')
										<a href="{{route('administration.main')}}">
											Администрирование
										</a>
									@endif
									<a href="{{ route('logout') }}"
										onclick="event.preventDefault();
												 document.getElementById('logout-form').submit();">
										Выход
									</a>

									<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
										{{ csrf_field() }}
									</form>
								</li>
							</ul>
						</ul>
					@endguest
				</div>
				<div class="navigation">
					<nav class="navbar navbar-default" role="navigation">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
							<ul class="nav navbar-nav">
								<li class="<?php $k=explode('/', $_SERVER['REQUEST_URI']); if($k[count($k)-1]=='home') echo 'active'; ?>"><a href="{{route('home')}}">Главная</a></li>
								<li class="<?php $k=explode('/', $_SERVER['REQUEST_URI']); if($k[count($k)-1]=='centr') echo 'active'; ?>"><a href="{{route('centr')}}">Структура</a></li>
								<li class="<?php $k=explode('/', $_SERVER['REQUEST_URI']); if($k[count($k)-1]=='prepods') echo 'active'; ?>"><a href="{{route('prepods')}}">Педагоги</a></li>
								<li class="<?php $k=explode('/', $_SERVER['REQUEST_URI']); if($k[count($k)-1]=='control') echo 'active'; ?>"><a href="{{route('control')}}">Аттестация</a></li>
								<li class="<?php $k=explode('/', $_SERVER['REQUEST_URI']); if($k[count($k)-1]=='contact') echo 'active'; ?>"><a href="{{route('contact')}}">Контакты</a></li>
							</ul>
						</div>
					</nav>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div id="maincontainer">
			@if(\Session::has('success'))
				<div class="container">
					<div class="col-md-12 alert alert-success">
						{!! \Session::get('success') !!}
						<button type="button" class="close" onclick="$(this).parent().parent().empty();">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				</div>
			@endif
			@if(\Session::has('error'))
				<div class="container">
					<div class="col-md-12 alert alert-danger">
						{!! \Session::get('error') !!}
						<button type="button" class="close" onclick="$(this).parent().parent().empty();">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				</div>
			@endif
			@yield('content')
		</div>
		<div class="footer">
			<div class="container">
				<div class="col-md-4 contact-left">
				<h4>Адрес</h4>
					<div class="cont-tp">
						<span class="glyphicon glyphicon-map-marker" aria-hidden="true">
					</span></div>
					<address>
						Микрорайон Старатель<br>
						ул. Здесенко, 22<br>
						<abbr title="Телефон">Т:</abbr> +7 (3435) 29-15-17
					</address>
				</div>
				<div class="col-md-4 contact-left">
				<h4>Телефон/Почта</h4>
					<div class="cont-tp">
						<span class="glyphicon glyphicon-phone" aria-hidden="true">
					</span></div>
					<p>Телефон: +7 (3435) 29-15-17</p>
					<p>Телефон: +7 (3435) 47-53-05</p>
					<p>Почта: cnttm@yandex.ru</p>
				</div>
				<div class="col-md-4 contact-left">
				<h4>Рассылка</h4>
					<div class="cont-tp">
						<span class="glyphicon glyphicon-envelope" aria-hidden="true">
					</span></div>
					<!--<form>-->
						<input type="text" value="Введите Вашу почту" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Введите Вашу почту';}">
						<input type="submit" value="Подписаться">
					<!--</form>-->
				</div>
				<div class="clearfix"></div>
				<div class="footer-text">
					<p>© 2020 ЦНТТМ. Все права защищены | Офицальный сайт <a href="http://ntiim.ru/" target="_blank">НТИИМ</a> </p>
				</div>
			</div>
		</div>
    </div>
</body>
</html>
