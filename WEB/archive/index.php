<!DOCTYPE html>
<html>
	<head>
		<title>Архив</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		<!-- CSS -->
		<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
		<link href="css/style.css" rel='stylesheet' type='text/css' />
		<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />
		
		<!-- JS -->
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script>
	</head>
	<body>
		<?php include('header.php'); ?>
		<div class="header">
			
		</div>
		<div class="banner">
			<div class="container">
				<div class="banner-top">
					<section class="slider">
						<div class="flexslider">
							<ul class="slides">
								<li>	
									<div class="banner-text">
										<h2>Архив</h2>
										<p>Просмотр архива</p>
										<a class="hvr-shutter-in-horizontal" href="archive.php">Архив</a>
										<br/><br/><br/><br/><br/>
									</div>
								</li>
								<li>	
									<div class="banner-text">
										<h2>Книга выдачи</h2>
										<p>Печать карточки постеллажного топографического указателя</p>
										<a class="hvr-shutter-in-horizontal" href="outgoing.php">Книга выдачи</a>
										<br/><br/><br/><br/><br/>
									</div>
								<li>	
									<div class="banner-text">
										<h2>Отчёт</h2>
										<p>Формирование отчёта</p>
										<a class="hvr-shutter-in-horizontal" href="report.php">Отчёт</a>
										<br/><br/><br/><br/><br/>
									</div>
								</li>
							</ul>
						</div>
					</section>
					<script defer="" src="js/jquery.flexslider.js"></script>
					<script type="text/javascript">
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
		<div class="footer">
			<div class="container">
				<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
			</div>
		</div>
	</body>
</html>