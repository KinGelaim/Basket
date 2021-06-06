@extends('layouts.app')

@section('title')
	Методические материалы
@endsection

@section('content')
	<div class="banner-1">
		
	</div>
	<div class="practice">
		<div class="container">
			<!--<div class="col-md-4 practice-left">
				<h3>Материалы</h3>
				<ul>
					<li><a href="{{route('teaching_materials.history')}}"><span></span>История предприятия</a></li>
					<li><a href="{{route('teaching_materials.tb')}}"><span></span>Техника безопасности</a></li>
					<li><a href="{{route('teaching_materials.radio')}}"><span></span>Компьютерные технологии и радиоэлектроника</a></li>
				</ul>
			</div>-->
			<div class="col-md-8 practice-right">
				<h3 class="bars">Методические материалы</h3>
				<p>
					<a href='http://ntiim.ru/' target='_blank'>Офицальная страница ФКП "НТИИМ"</a><br/>
					<a href='http://www.microanswers.ru/article/storija-poligona-staratel.html' target='_blank'>История полигона «Старатель»</a><br/>
					<a href='http://ntiim.ru/ipress.php?x=kadry/nttm/10' target='_blank'>Сведения об образовательной организации</a><br/>
					<a href='http://www.rcdesign.ru/articles/avia/wings_profile' target='_blank'>Авиамоделирование</a><br/>
				</p>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
@endsection
