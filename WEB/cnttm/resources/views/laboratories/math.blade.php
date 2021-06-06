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
				<h3>Математика для будущих инженеров</h3>
				<p>
					<h4 class="bars">Основное содержание деятельности</h4>
					<ul class="list-group">
						<li class="list-group-item">Расширение и углубление знаний по основным разделам математики, необходимых будущему инженеру</li>
						<li class="list-group-item">Систематизация и обобщение знаний, развитие инженерного мышления</li>
						<li class="list-group-item">Решение практико-ориентированных задач</li>
					</ul>
				</p>
				<div class="clearfix"></div>
				<p>Новизна курса заключается в инженерной направленности обучения, которая базируется на математических методах и умозаключениях, находящих свое отражение в любой сфере жизнедеятельности.</p>
				<div class="pra-lft">
					<img src="{{asset('images/math.jpg')}}" alt=" " class="img-responsive">
				</div>
				<div class="pra-rgt">
					<p>Содержание учебных занятий не дублирует школьные уроки. Предлагаеиые курсы обеспечивают значительный объем новой для учащихся информации, связанной с использованием достижений математики и физики в современной технике и технологиях.</p>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
@endsection
