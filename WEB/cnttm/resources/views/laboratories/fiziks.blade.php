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
				<h3>Физические основы высоких технологий</h3>
				<p>
					<h4 class="bars">Основное содержание деятельности</h4>
					<ul class="list-group">
						<li class="list-group-item">Ознакомление учащихся с современными достижениями физической науки и техники</li>
						<li class="list-group-item">Овладение умениями применять знания по физике для решения задач, объяснения явлений природы, свойств веществ, принципа работы технических устройств</li>
						<li class="list-group-item">Овладение учащимися умениями воспринимать информацию физического содержания, предлагаемую в форме графиков зависимостей физических величин, диаграмм, таблиц</li>
					</ul>
				</p>
				<div class="clearfix"></div>
				<div class="pra-lft">
					<img src="{{asset('images/fizik.png')}}" alt=" " class="img-responsive">
				</div>
				<div class="pra-rgt">
					<p>Основное внимание на теоретических занятиях уделено анализу и решению разнообразных физических явлений и процессов, что способствует пониманию их сущности, развитию логического мышления, творческих способностей. На практических занятиях больше внимание уделено решению задач.</p>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
@endsection
