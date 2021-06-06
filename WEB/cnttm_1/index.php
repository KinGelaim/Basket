<!DOCTYPE html>
<html>
	<head>
		<title>ЦНТТМ</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
		<link href="css/style.css" rel='stylesheet' type='text/css' />
		<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />
		<script src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script>
		
		<link rel="shortcut icon" href="images/favicon.jpg" type="image/png" />
	</head>
	<body>
		<?php include('header.html'); ?>
		<script>
			$("span.menu").click(function(){
				$(" ul.navig").slideToggle("slow" , function(){
				});
			});
		</script>
		<div class="banner">
			<div class="container">
				<div class="banner-top">
					<section class="slider">
						<div class="flexslider">
							<ul class="slides">
								<li>	
									<div class="banner-text">
										<h2>“МЫ ЗАБОТИМСЯ О ВАШЕМ БУДУЩЕМ!”</h2>
										<p>Центр научно-технического творчества молодежи ФКП "НТИИМ" предоставляет современные знания и готовит к востребовательным профессиям.</p>
										<a class="hvr-shutter-in-horizontal" href="centr.php">Подробнее</a>
									</div>
								</li>
								<li>	
									<div class="banner-text">
										<h2>“ЛУЧШЕЕ ОБОРУДОВАНИЕ В КАЖДОМ КАБИНЕТЕ!”</h2>
										<p>Центр оборудован по последнему слову науки и техники. Каждое направление обучения обладает своим кабинетом, который всё необходимое для освоение программы в полной мере.</p>
										<a class="hvr-shutter-in-horizontal" href="centr.php">Подробнее</a>
									</div>
								<li>	
									<div class="banner-text">
										<h2>“НАДЁЖНЫЕ ПРЕПОДАВАТЕЛИ!”</h2>
										<p>Преподавателями в центре являются реальные инженеры и специалисты ФКП "НТИИМ", которые каждый день с утра и до вечера впитывают информацию на заводе, чтобы отдать её детям.</p>
										<a class="hvr-shutter-in-horizontal" href="prepods.php">Подробнее</a>
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
						<p>Многие дети ещё в раннем возрасте задумаются о своём будущем, но возможности получить образование, необходимое предприятиям нет.</p>
						<p>Но есть ЦНТТМ, который спешит на помощь!</p>
						<a class="hvr-shutter-in-horizontal" href="centr.php">Подробнее</a>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="col-md-4 welcome-right">
					<h3>Предметы</h3>
					<ul>
						<li><a href="#"><span></span>Компьютерные технологии и программы</a></li>
						<li><a href="#"><span></span>Компьютерные технологии и робототехника</a></li>
						<li><a href="#"><span></span>Компьютерные технологии и радиоэлектроника</a></li>
						<li><a href="#"><span></span>Компьютерные технологии и техническое моделирование</a></li>
						<li><a href="#"><span></span>Физические основы высоких технологий</a></li>
						<li><a href="#"><span></span>Математика для будущих инженеров</a></li>
						<li><a href="#"><span></span>Информатика ЕГЭ</a></li>
					</ul>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="cases">
			<div class="container">
				<div class="col-md-4 cases-left">
					<h3>Оборудование</h3>
					<img src="images/proc.jpg" alt=" " class="img-responsive">
					<p>Центр оборудован совремейнешим оборудованием, которое помогает развиваться умственно и творчески!</p>
					<a class="hvr-shutter-in-horizontal" href="#">Подробнее</a>
				</div>
				<div class="col-md-4 cases-left">
					<h3>Преподаватели</h3>
					<img src="images/sobr.jpg" alt=" " class="img-responsive">
					<p>У нас работают реальные специалисты НТИИМа! Которые донесут до Вас знания, необходимые для заводского рабства.</p>
					<a class="hvr-shutter-in-horizontal" href="#">Подробнее</a>
				</div>
				<div class="col-md-4 cases-left">
					<h3>Будущее</h3>
					<img src="images/prog.jpg" alt=" " class="img-responsive">
					<p>После прохождения обучения Вы получаете сертификат, который можно использовать, как подставку под горячее.</p>
					<a class="hvr-shutter-in-horizontal" href="#">Подробнее</a>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="news">
			<div class="container">
				<h3>Новости & События</h3>
				<div class="col-md-6 news-left">
					<div class="col-md-6 new-lft">
						<img src="images/code.jpg" alt=" " class="img-responsive">
					</div>
					<div class="col-md-6 new-rgt">
						<h5> [27-04-2020]</h5>
						<p>Теперь у нас есть свой собственный веб-сервис!</p>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="col-md-6 news-left">
					<div class="col-md-6 new-lft">
						<img src="images/hamster.jpg" alt=" " class="img-responsive">
					</div>
					<div class="col-md-6 new-rgt">
						<h5> [24-04-2020]</h5>
						<p>Пятница!.</P>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<?php include('footer.html'); ?>
	</body>
</html>