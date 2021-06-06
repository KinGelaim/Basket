@extends('layouts.header')

@section('title')
	Прелоадеры
@endsection

@section('content')
    <link href="{{ asset('css/loader1.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/loader2.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/loader3.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/loader4.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/loader5.css') }}" rel="stylesheet" />
	
	<div class="flex-center position-ref full-height">
		@if (Auth::User())
			@if(Auth::User()->hasRole()->role == 'Администратор')
				<div class='row' style='height: 100px;'>
					<div class='col-md-12'>
						<div id='circularG' style='margin-top: 10px;'>
							<div id='circularG_1' class='circularG'>
							</div>
							<div id='circularG_2' class='circularG'>
							</div>
							<div id='circularG_3' class='circularG'>
							</div>
							<div id='circularG_4' class='circularG'>
							</div>
							<div id='circularG_5' class='circularG'>
							</div>
							<div id='circularG_6' class='circularG'>
							</div>
							<div id='circularG_7' class='circularG'>
							</div>
							<div id='circularG_8' class='circularG'>
							</div>
						</div>
					</div>
				</div>
				<div class='row'>
					<div class='col-md-12'>
						<div class="container2">
							<div class="cube"></div>
							<div class="cube"></div>
							<div class="cube"></div>
							<div class="cube"></div>
							<div class="cube"></div>
						</div>
					</div>
				</div>
				<div class='row'>
					<div id='div_loader' class='col-md-12' style='height: 200px;'>
						<div class="loader">
							<div class="l_main">
								<div class="l_square"><span></span><span></span><span></span></div>
								<div class="l_square"><span></span><span></span><span></span></div>
								<div class="l_square"><span></span><span></span><span></span></div>
								<div class="l_square"><span></span><span></span><span></span></div>
							</div>
						</div>
					</div>
				</div>
				<div class='row'>
					<div style='col-md-12'>
						<div class="box">
							<div class="cat">
								<div class="cat__body"></div>
								<div class="cat__body"></div>
								<div class="cat__tail"></div>
								<div class="cat__head"></div>
							</div>
						</div>
					</div>
				</div>
				<div class='row'>
					<div style='col-md-12'>
						<div class="box" style='background-color: white;'>
							<div class="mouse" style='background-color: white;'>
								<div class="mouse__body" style='clip: rect(115px 260px 175px 170px);'></div>
								<div class="mouse__body" style='clip: rect(175px 260px 260px 105px);'></div>
								<div class="mouse__tail" style='clip: rect(85px 117px 260px 0px);'></div>
								<div class="mouse__head" style='clip: rect(0px 260px 145px 90px);'></div>
							</div>
						</div>
					</div>
				</div>
			@endif
		@else
			<div class="alert alert-danger">
				Для просмотра данной страницы необходимо авторизоваться!
			</div>
		@endif
	</div>
@endsection