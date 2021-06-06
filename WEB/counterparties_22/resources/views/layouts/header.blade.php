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
	<link href="{{ asset('css/loader.css') }}" rel="stylesheet" />
	   
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
			@yield('content')
			@include('layouts.loader')
		</div>
    </div>
</body>
</html>
