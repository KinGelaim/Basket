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
	<!--<link href="{{ asset('css/loader1.css') }}" rel="stylesheet" />
	<link href="{{ asset('css/loader5.css') }}" rel="stylesheet" />-->
	<link href="{{ asset('css/loader.css') }}" rel="stylesheet" />
	<link href="{{ asset('css/loader6.css') }}" rel="stylesheet" />
	   
	<!-- Scripts -->
	<script src="{{ asset('js/jquery-3.0.0.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
	<script src="{{ asset('js/mainJs.js') }}"></script>
	<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
	<script src="{{ asset('js/loaderCSS.js') }}"></script>
	
	<!-- Icon -->
	<link rel="shortcut icon" href="{{ asset('css/images/favicon.png') }}" type="image/png" />
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
		    <!-- Branding Image -->
			@if(isset($back_url))
				<a class="navbar-brand" href="{{ url(' . $back_url . ') }}" title='Вернуться на прошлую страницу древа'>
					Оперативный учет договоров
				</a>
			@else
				@if(Route::current()->uri() == 'home' || Route::current()->uri() == 'chancery' || Route::current()->uri() == 'ekonomic' || Route::current()->uri() == 'ekonomic_sip' || Route::current()->uri() == 'reconciliation' || Route::current()->uri() == 'management_contracts' || Route::current()->uri() == 'invoice' || Route::current()->uri() == 'second' || Route::current()->uri() == 'leadership' || Route::current()->uri() == 'login' || Route::current()->uri() == 'register' || Route::current()->uri() == 'ten')
					<a class="navbar-brand" href="{{ url('/') }}" title='Нажмите, чтобы перейти к домашней странице'>
						Оперативный учет договоров
					</a>
				@else
					<a class="navbar-brand cursorPointer" title='Нажмите, чтобы вернуться' onclick='history.back();'>
						Оперативный учет договоров
					</a>
				@endif
			@endif
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
						<li><a href="{{ route('department.chancery') }}">Канцелярия</a></li>
						<li><a>Заявки</a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
								Отделы <span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								@if(Auth::User())
									@if(Auth::User()->hasRole()->role == 'Администратор')
										<li><a href="{{ route('department.reconciliation') }}">ПЭО старое</a></li>
									@endif
								@endif
								<li><a href="{{ route('department.peo') }}?view=02&sorting=cast_number_pp&sort_p=desc">Планово-экономический отдел</a></li>
								<!--<li><a href="{{ route('department.peo') }}?view=02&sorting=cast_number_pp&sort_p=desc">ПЭО 2</a></li>-->
								<li class="menu-item dropdown dropdown-submenu">
									<a href="{{ route('department.management.contracts') }}" class="dropdown-toggle">
										Отдел управления договорами
									</a>
									<ul class="dropdown-menu">
										<li><a href="{{ route('department.ekonomic.sip') }}">Договоры СИП</a></li>
										<li><a href="{{ route('department.ekonomic') }}">Реестр</a></li>
									</ul>
								</li>
								<li><a href="{{ route('department.invoice') }}">Финансовый отдел</a></li>
								<li><a href="{{ route('department.second') }}?sorting=cast_number_pp&sort_p=desc">Второй отдел</a></li>
								<li><a href="{{ route('department.ten') }}">Десятый отдел</a></li>
								<li><a href="{{ route('department.ten.new') }}">Десятый отдел (новое)</a></li>
							</ul>
						</li>
						<li><a href="{{route('reestr.show')}}">Реестр</a></li>
						<li><a href="{{ route('department.leadership') }}">Руководство</a></li>
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
										$count_message = App\ReconciliationUser::select('reconciliation_users.id')->join('applications','id_application', 'applications.id')->where('id_user',Auth::user()->id)->where('check_reconciliation', 0)->where('applications.deleted_at', null)->get()->count() + App\ReconciliationUser::select('reconciliation_users.id')->join('contracts','id_contract', 'contracts.id')->where('id_user',Auth::user()->id)->where('check_reconciliation', 0)->where('contracts.deleted_at', null)->get()->count()+App\ReconciliationProtocol::select('reconciliation_protocols.id')->where('id_user',Auth::user()->id)->where('check_reconciliation', null)->get()->count();
										if($count_message)
											echo "<span class='badge badge-pill badge-danger'>" . $count_message . "</span>";
									?>
									<span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
										@if(Auth::User())
											@if(Auth::User()->hasRole()->role == 'Администратор')
												<a href="{{ route('signature.main') }}">
													Подпись
												</a>
											@endif
										@endif
										<a href="{{ route('reports.download_orc_reports')}}">
											Отчеты
										</a>
										<a href="{{ route('download_helper')}}">
											Помощник
										</a>
										<a href="{{ route('home') }}">
                                            Согласование 
											<?php
												if($count_message)
													if($count_message > 0)
														echo "<span class='badge badge-pill badge-danger'>" . $count_message . "</span>";
											?>
                                        </a>
										<a href="{{ route('administrator.main') }}">
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
				<div class="row">
					<div class="col-md-12 alert alert-success">
						{!! \Session::get('success') !!}
						<button type="button" class="close" onclick="$(this).parent().parent().empty();">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				</div>
			@endif
			@if(\Session::has('error'))
				<div class="row">
					<div class="col-md-12 alert alert-danger">
						{!! \Session::get('error') !!}
						<button type="button" class="close" onclick="$(this).parent().parent().empty();">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				</div>
			@endif
			@if(\Session::has('pls_back'))
				<?php 
					//session_start();
					//$_SESSION['pls_refresh'] = 'ds';
				?>
				<script>
					history.back();
					//window.location.reload();
					/*setTimeout(function()
					{
						alert('ds');
					}, 3000);*/
					//window.location.reload();
					//window.location.replace(document.referrer);
					//window.location = document.referrer;
					//window.location.reload(history.back());
				</script>
			@endif
			<!-- Скрипт для повторного обеновления страницы -->
			<script>
				//Перенесено в отдельное JS (history.js)
			</script>
			@if(\Session::has('pls_refresh'))
				<?php
					/*session_start();
					dd($_SESSION['pls_refresh']);
					unset($_SESSION['pls_refresh']);
					$_SESSION = [];
					session_destroy();*/
				?>
			@endif
			<!-- Скрипт для отображения инструкции -->
			<script>
				$(document).ready(function(){
					$(document).keyup(function(e){
						if(e.which == 112)
						{
							e.preventDefault();
							window.open("{{route('instruction')}}", '_blank');
						}
					});
				});
			</script>
			@yield('content')
			@include('layouts.loader')
		</div>
    </div>
</body>
</html>
