<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/main.css') }}" rel="stylesheet" />
	<link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet" />
	   
	<!-- Scripts -->
	<script src="{{ asset('js/jquery-3.0.0.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
	<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
	<script src="{{ asset('js/myjs.js') }}"></script>
	
	<!-- Icon -->
	<link rel="shortcut icon" href="{{ asset('css/images/favicon.png') }}" type="image/png" />
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
		    <!-- Branding Image -->
			<a class="navbar-brand cursorPointer btn-href" href='{{route("welcome")}}' title='Нажмите, чтобы попасть на главную'>
				Контроль приказов
			</a>
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>


                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
						<li><a href="{{ route('orders.main') }}">Приказы</a></li>
						<li><a href="{{ route('archive.main') }}">Архив</a></li>
						@if(Auth::User())
							@if(Auth::User()->hasRole()->role == 'Администратор')
								<li><a href="{{ route('journal.main') }}">Журнал</a></li>
							@endif
						@endif
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">Авторизация</a></li>
                            <li><a href="{{ route('register') }}">Регистрация</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                                    {{ Auth::user()->login }} 
									<?php
										$count_message = 0;
										if($count_message)
											echo "<span class='badge badge-pill badge-danger'>" . $count_message . "</span>";
									?>
									<span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
										<a href="{{ route('download_kontrol_period_reports') }}">
                                            Отчёты
                                        </a>
										<a href="{{ route('administration.main') }}">
                                            Администрирование
                                        </a>
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
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
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
    </div>
</body>
</html>
