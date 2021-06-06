@extends('layouts.app')

@section('title')
	Методические материалы
@endsection

@section('content')
	<div class="banner-1">
		
	</div>
	<div class="practice">
		<div class="container">
		<div class="col-md-4 practice-left">
			<h3>Материалы</h3>
				<ul>
					<li><a href="{{route('teaching_materials.history')}}"><span></span>История предприятия</a></li>
					<li><a href="{{route('teaching_materials.tb')}}"><span></span>Техника безопасности</a></li>
					<li><a href="{{route('teaching_materials.radio')}}"><span></span>Компьютерные технологии и радиоэлектроника</a></li>
				</ul>
			</div>
			<div class="col-md-8 practice-right">
				<h3 class="bars">Техника безопасности</h3>
				<p>
					Спички детям не игрушка! Покупайте зажигалки!
				</p>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
@endsection
