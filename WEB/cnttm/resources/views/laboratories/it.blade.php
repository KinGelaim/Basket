@extends('layouts.app')

@section('title')
	Информация о центре
@endsection

@section('content')
	<div class="banner-1">
		
	</div>
	<div class="practice">
		<div class="container">
		<div class="col-md-4 practice-left">
			<h3>Направления подготовки</h3>
				<ul>
					<li><a href="{{route('laboratories.modeling_programming')}}"><span></span>Компьютерные технологии и программы</a></li>
					<li><a href="{{route('laboratories.robot')}}"><span></span>Компьютерные технологии и робототехника</a></li>
					<li><a href="{{route('laboratories.radio')}}"><span></span>Компьютерные технологии и радиоэлектроника</a></li>
					<li><a href="{{route('laboratories.modeling')}}"><span></span>Компьютерные технологии и техническое моделирование</a></li>
					<li><a href="{{route('laboratories.fiziks')}}"><span></span>Физические основы высоких технологий</a></li>
					<li><a href="{{route('laboratories.math')}}"><span></span>Математика для будущих инженеров</a></li>
					<li><a href="{{route('laboratories.it')}}"><span></span>Информационные технологии в производственной деятельности</a></li>
				</ul>
			</div>
			<div class="col-md-8 practice-right">
				<h3>Информационные технологии в производственной деятельности</h3>
				<p>
					<h4 class="bars">Основное содержание деятельности</h4>
					<ul class="list-group">
						<li class="list-group-item">Формирование компонентов информационной культуры: навыки работы с различными источниками информации, первичная верификация информации, отбор информации, применение полученных знаний на практике</li>
						<li class="list-group-item">Формирование и развитие информационно-коммуникационной компетенции</li>
						<li class="list-group-item">Основные приемы работы в прикладных информационных системах</li>
						<li class="list-group-item">Решение прикладных производственных задач с использованием информационных технологий</li>
						<li class="list-group-item">Построение алгоритмов при помощи блок-схем</li>
						<li class="list-group-item">Применение основных алгоритмических конструкций на практике в производственной деятельности</li>
					</ul>
				</p>
				<div class="clearfix"></div>
				<div class="pra-lft">
					<img src="{{asset('images/it.jpg')}}" alt=" " class="img-responsive">
				</div>
				<div class="pra-rgt">
					<p>Новизна курсов заключается в инженерной направленности обучения, которая базируется на методах и умозаключениях, находящих свое отражение в любой сфере жизнедеятельности</p>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
@endsection
