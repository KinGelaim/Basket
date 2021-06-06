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
				<h3>Компьютерные технологии и программы</h3>
				<p>
					<h4 class="bars">Основное содержание деятельности</h4>
					<ul class="list-group pra-lft">
						<li class="list-group-item">Основы программирования</li>
						<li class="list-group-item">Оконный интерфейс</li>
						<li class="list-group-item">Web-приложения</li>
						<li class="list-group-item">BigData - обработка и анализ данных</li>
						<li class="list-group-item">Web 2.0 - интеллектульный поиск в интернете</li>
					</ul>
					<ul class="list-group pra-rgt">
						<li class="list-group-item">параллельное программирование</li>
						<li class="list-group-item">3D-проектирование</li>
						<li class="list-group-item">3D-анимация и графика</li>
						<li class="list-group-item">Сетевые технологии</li>
						<li class="list-group-item">Веб дизайн</li>
					</ul>
				</p>
				<div class="clearfix"></div>
				<div class="pra-lft">
					<img src="{{asset('images/prog.jpg')}}" alt=" " class="img-responsive">
				</div>
				<div class="pra-rgt">
					<p>В процессе обучения учащиеся получают основные сведения по инженерной графике и основам работы на современных CAD платформах (COMPASS), которые широко применяются в современных отраслях науки, техники и производства.</p>
				</div>
				<div class="clearfix"></div>
				<p>
					<h4 class="bars">Профориентационная направленность на профессии сборочно-испытательного производства:</h4>
					<ul class="list-group">
						<li class="list-group-item">Инженер-программист</li>
						<li class="list-group-item">Инженер-конструктор</li>
						<li class="list-group-item">Инженер-проектировщик</li>
						<li class="list-group-item">Оператор ЭВМ</li>
						<li class="list-group-item">Оператор станков с ЧПУ</li>
						<li class="list-group-item">Мастер по обработке цифровой информации</li>
					</ul>
				</p>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
@endsection
