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
				<h3>Компьютерные технологии и техническое моделирование</h3>
				<p>
					<h4 class="bars">Основное содержание деятельности</h4>
					<ul class="list-group">
						<li class="list-group-item">Проектирование и создание летательных аппаратов</li>
						<li class="list-group-item">Работа с ручным инструментом</li>
						<li class="list-group-item">Работа на сверлильном, фрезерном, токарном и других станках</li>
						<li class="list-group-item">Участие в соревнованиях по авиамодельному спорту</li>
						<li class="list-group-item">Выполнение спортивных нормативов</li>
					</ul>
				</p>
				<div class="clearfix"></div>
				<h4 class="bars">Профориентационная направленность на профессии летно-испытательно базы:</h4>
				<div class="pra-lft">
					<ul class="list-group">
						<li class="list-group-item">Инженер по эксплуатации летательных аппаратов</li>
						<li class="list-group-item">Инженер по эксплуатации авиационного оборудования</li>
						<li class="list-group-item">Инженер по эксплуатации комплексов с беспилотными летательными аппаратами</li>
						<li class="list-group-item">Авиатехник</li>
						<li class="list-group-item">Авиамеханик</li>
					</ul>
				</div>
				<div class="pra-rgt">
					<img src="{{asset('images/modeling.jpg')}}" alt=" " class="img-responsive">
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
@endsection
