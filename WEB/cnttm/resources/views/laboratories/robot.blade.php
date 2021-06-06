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
				<h3>Компьютерные технологии и робототехника</h3>
				<p>
					<h4 class="bars">Основное содержание деятельности</h4>
					<ul class="list-group">
						<li class="list-group-item">Работа с комплектами конструкторов Arduino</li>
						<li class="list-group-item">Ознакомление со средой программирования Arduino IDE</li>
						<li class="list-group-item">Обучение прикладному программированию с использованием микроконтроллеров</li>
						<li class="list-group-item">Создание собственных роботизированных устройств на основе перспективных технологий</li>
						<li class="list-group-item">Изучение принципов работы с датчиками и двигателями</li>
					</ul>
				</p>
				<div class="clearfix"></div>
				<h4 class="bars">Профориентационная направленность на профессии сборочно-испытательного производства:</h4>
				<div class="pra-lft">
					<ul class="list-group">
						<li class="list-group-item">Инженер-испытатель</li>
						<li class="list-group-item">Инженер по обслуживанию испытаний</li>
						<li class="list-group-item">Инженер-исследователь</li>
						<li class="list-group-item">Испытатель вооружения</li>
						<li class="list-group-item">Наблюдатель-приемщик стрельб</li>
					</ul>
				</div>
				<div class="pra-rgt">
					<img src="{{asset('images/robot.jpg')}}" alt=" " class="img-responsive">
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
@endsection
