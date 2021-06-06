@extends('layouts.app')

@section('title')
	ЦНТТМ
@endsection

@section('content')
	<div class="banner">
		<div class="container">
			<div class="banner-top">
				<section class="slider">
					<div class="flexslider">
						<ul class="slides">
							<li>	
								<div class="banner-text">
									<h2>“НА ПУТИ К<br/>ПРОФЕССИИ!”</h2>
									<p>Центр предоставляет современные знания и готовит к востребованным профессиям</p><br/>
									<a class="hvr-shutter-in-horizontal" href="{{route('centr')}}">Подробнее</a>
								</div>
							</li>
							<li>	
								<div class="banner-text">
									<h2>“СОВРЕМЕННАЯ ТЕХНОЛОГИЧЕСКАЯ БАЗА!”</h2>
									<p>Лаборатории центра оснащены современным оборудованием реального производства</p><br/>
									<a class="hvr-shutter-in-horizontal" href="{{route('centr')}}">Подробнее</a>
								</div>
							<li>	
								<div class="banner-text">
									<h2>“ЛУЧШИЕ<br/>СПЕЦИАЛИСТЫ”</h2>
									<p>Педагогический состав Центра - ведущие инженеры ФКП "НТИИМ", кандидаты наук</p><br/>
									<a class="hvr-shutter-in-horizontal" href="{{route('prepods')}}">Подробнее</a>
								</div>
							</li>
						</ul>
					</div>
				</section>
				<script defer="" src="js/jquery.flexslider.js"></script>
				<script type="text/javascript">
					$(function(){

					});
					$(window).load(function(){
						$('.flexslider').flexslider({
							animation: "slide",
							start: function(slider){
								$('body').removeClass('loading');
							}
						});
					});
				</script>
			</div>
		</div>
	</div>
	<div class="welcome">
		<div class="container">
			<div class="col-md-8 welcome-left">
				<h3>Добро пожаловать!</h3>
				<div class="col-md-6 wel-lft">
					<img src="images/main.png" alt=" " class="img-responsive">
				</div>
				<div class="col-md-6 wel-rgt">
					<p>Центр научно-технического творчества молодежи, созданный на базе ФКП "Нижнетагильский институт испытания металлов" для учащихся школ города, направлен на совершенствования системы подготовки кадров для предприятия.</p>
					<p>В Центр научно-технического творчества молодежи принимаются учащиеся 8 и 10 классов, проживающие в г.Нижний Тагил.</p>
					<p>Набор в лаборатории Центра научно-технического творчества молодежи осуществляется ежегодно <u>до 10 сентября.</u></p>
					<a class="hvr-shutter-in-horizontal" href="{{route('centr')}}">Подробнее</a>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="col-md-4 welcome-right">
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
			<div class="clearfix"></div>
		</div>
	</div>
	<div class="cases">
		<div class="container">
			<div class="col-md-4 cases-left">
				<h3>Достижения</h3>
				<img src="images/proc.jpg" alt=" " class="img-responsive">
				<p>За время учебы курсанты Центра получают опыт участия в мероприятиях различного уровня и возможность публикации результатов своей работы</p><br/><br/><br/>
				<a class="hvr-shutter-in-horizontal" href="{{route('achievements')}}">Подробнее</a>
			</div>
			<div class="col-md-4 cases-left">
				<h3>Педагоги</h3>
				<img src="images/sobr.jpg" alt=" " class="img-responsive">
				<p>В педагогический коллектив Центра входят ведущие инженеры предприятия – высококвалифицированные специалисты, а также опытные преподаватели – кандидаты наук</p><br/><br/>
				<a class="hvr-shutter-in-horizontal" href="{{route('prepods')}}">Подробнее</a>
			</div>
			<div class="col-md-4 cases-left">
				<h3>Перспективы</h3>
				<img src="images/prog.jpg" alt=" " class="img-responsive">
				<p>По окончании обучения в Центре курсантам выдается свидетельство установленного образца. Для наиболее талантливых и успешных ребят открывается возможность дальнейшего обучения по целевому договору от предприятия</p>
				<a class="hvr-shutter-in-horizontal" href="{{route('perspectives')}}">Подробнее</a>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<div class="news">
		<div class="container">
			<h3>Новости & События</h3>
			<div class="col-md-6 news-left">
				<div class="col-md-6 new-lft">
					<img src="images/nine.jpg" alt=" " class="img-responsive">
				</div>
				<div class="col-md-6 new-rgt">
					<h5> [09-05-2020]</h5>
					<p>Уважаемые курсанты и родители! Администрация ФКП «НТИИМ» и коллектив Центра НТТМ от всего сердца поздравляют Вас с праздником Великой Победы!</p>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="col-md-6 news-left">
				<div class="col-md-6 new-lft">
					<img src="images/hamster.jpg" alt=" " class="img-responsive">
				</div>
				<div class="col-md-6 new-rgt">
					<h5> [07-05-2020]</h5>
					<p>Внимание! <a href="{{route('control')}}">Аттестация!</a></P>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
@endsection
