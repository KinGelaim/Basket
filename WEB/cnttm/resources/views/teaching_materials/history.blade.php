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
				<h3 class="bars">История предприятия</h3>
				<p>
					Тут история предприятия. И чтобы лучше её усвоить мы предлагаем посмотреть видео:
				</p>
				<video controls><source src="{{asset('video/malinov.mp4')}}"></video>
				<p>
					А тут дальше история предприятия.
				</p>
				<p>
					А дальше Вы можете найти ряд интересных ссылок на другие источники:
					<ul class="list-group">
						<li class="list-group-item"><a href='http://ntiim.ru'>Офицальная страница ФКП "НТИИМ"</a></li>
						<li class="list-group-item"><a href='wikipedia.org'>Википедия</a></li>
					</ul>
				</p>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
@endsection
