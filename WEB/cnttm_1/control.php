<!DOCTYPE html>
<html>
	<head>
		<title>Аттестация ЦНТТМ</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="keywords" content="Law Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
		Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
		<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
		<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
		<link href="css/style.css" rel='stylesheet' type='text/css' />
		<link href='//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,400,300,600,700|Six+Caps' rel='stylesheet' type='text/css' />
		<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />
		<script src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script>
		
		<link rel="shortcut icon" href="images/favicon.jpg" type="image/png" />
	</head>
	<body>
		<?php include('header.html'); ?>
		<div class="banner-1">
			
		</div>
		<div class="resource">
			<div class="container">
				<div class="grid_3 grid_5">
					<h3 class="bars">Итоговая аттестация</h3>
					<div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
						<ul id="myTab" class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#part1" id="part1-tab" role="tab" data-toggle="tab" aria-controls="part1" aria-expanded="true">Часть 1</a></li>
							<li role="presentation"><a href="#part2" role="tab" id="part2-tab" data-toggle="tab" aria-controls="part2">Часть 2</a></li>
							<li role="presentation"><a href="#part3" role="tab" id="part3-tab" data-toggle="tab" aria-controls="part3">Часть 3</a></li>
							<li role="presentation"><a href="#part4" role="tab" id="part4-tab" data-toggle="tab" aria-controls="part4">Часть 4</a></li>
						</ul>
						<div id="myTabContent" class="tab-content">
							<div role="tabpanel" class="tab-pane fade in active" id="part1" aria-labelledby="part1-tab">
								<h3>Общие сведения о НТИИМе</h3>
								<div class="res-top">
									<div class="col-md-6 re-lft">
										<video controls><source src='video/malinov.mp4'></video>
									</div>
									<div class="col-md-6 re-rgt">
										<h4>Информационное видео</h4>
										<p>Для ответа на последующие вопросы, рекомендуем просмотреть следующее видео</p>
									</div>
									<div class="clearfix"></div>
								</div>
								<h3>Тест</h3>
								<div>
									<h5>1) В ЦНТТМ Вас обучали на профессию:</h5>
									<div>
										<p><input id='test1Povar' type="radio" name='quest1' value='Повар'><label for='test1Povar'>Повар</label></p>
										<p><input id='test1Dvornik' type="radio" name='quest1' value='Дворник'><label for='test1Dvornik'>Дворник</label></p>
										<p><input id='test1Slesar' type="radio" name='quest1' value='Слесарь'><label for='test1Slesar'>Слесарь</label></p>
										<p><input id='test1Injener' type="radio" name='quest1' value='Инженер'><label for='test1Injener'>Инженер</label></p>
									</div>
									<h5>2) Что нельзя делать при пожаре?</h5>
									<div>
										<p><input id='test2Panik' type="checkbox" name='quest2' value='Повар'><label for='test2Panik'>Паниковать</label></p>
										<p><input id='test2Fire' type="checkbox" name='quest2' value='Дворник'><label for='test2Fire'>Раздувать огонь</label></p>
										<p><input id='test2Benz' type="checkbox" name='quest2' value='Слесарь'><label for='test2Benz'>Пить бензин</label></p>
										<p><input id='test2Call' type="checkbox" name='quest2' value='Инженер'><label for='test2Call'>Вызывать пожарных</label></p>
									</div>
									<h5>3) Расскажите о себе:</h5>
									<div>
										<textarea style='width: 100%;' rows=7></textarea>
									</div>
								</div>
								<br/>
								<button class='btn btn-primary form-control'>Cдать часть 1</button>
							</div>
							<div role="tabpanel" class="tab-pane fade" id="part2" aria-labelledby="part2-tab">
								<h3>Вопросы с подвохом</h3>
								<div>
									<h5>1) Зимой и летом одним цветом:</h5>
									<input type="text" class="form-control"></input>
									<h5>2) Рассмотрите изображение и скажите: где енот?</h5>
									<div class="col-md-6 re-lft">
										<input type="text" class="form-control"></input>
									</div>
									<div class="col-md-6 re-rgt">
										<image src='images/enot.png' style='width: 100%;'></image>
									</div>
									<div class="clearfix"></div>
								</div>
								<br/>
								<button class='btn btn-primary form-control'>Cдать часть 2</button>
							</div>
							<div role="tabpanel" class="tab-pane fade" id="part3" aria-labelledby="part3-tab">
								<h3>Теория</h3>
								<br/>
								<button class='btn btn-primary form-control'>Cдать часть 3</button>
							</div>
							<div role="tabpanel" class="tab-pane fade" id="part4" aria-labelledby="part4-tab">
								<h3>Практика</h3>
								<div class='alert alert-danger' role='alert'>
									<strong>Внимание!</strong>
									Сначало необходимо выполнить тест 3!
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</diV>
		</div>
		<?php include('footer.html'); ?>
	</body>
</html>