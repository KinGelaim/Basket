<!DOCTYPE html>
<html>
	<head>
		<title>Регистрация</title>
		<meta charset='UTF-8'>
		
		<!-- CSS -->
		<link href='css/jquery-ui.min.css' rel='stylesheet'>
		<link href='css/bootstrap.min.css' rel='stylesheet'>
		<link href='css/my.css' rel='stylesheet'>
		
		<!-- JS -->
		<script src='js/jquery-3.0.0.min.js'></script>
		<script src='js/jquery-ui.min.js'></script>
		<script src='js/jquery.validate.min.js'></script>
		<script src='js/bootstrap.min.js'></script>
		<script src='js/my.js'></script>
		
		<!-- ICON -->
		<link rel="shortcut icon" href="css/images/favicon.png" type="image/png" />
	</head>
	<body>
        <nav class="navbar navbar-default navbar-static-top">
			<a class="navbar-brand" title='Нажмите, чтобы вернуться' onclick='history.back();'>
				Главная
			</a>
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
						<li><a href="auth.php">Авторизация</a></li>
						<li><a href="register.php">Регистрация</a></li>
					</ul>
				</div>
			</div>
		</nav>
		<div class='container'>
			<form id='myForm' class='form-horizontal' method='POST' action='action_register.php'>
				<div class='row'>
					<div class='col-md-5'>
						<label>ФИО</label>
						<input name='FIO' class='form-control' required />
					</div>
				</div>
				<div class='row'>
					<div class='col-md-5'>
						<label>Дата рождения</label>
						<input name='date' id='datepicker' class='form-control' />
					</div>
				</div>
				<div class='row'>
					<div class='col-md-5'>
						<label>Телефон</label>
						<input name='telephone' class='form-control' />
					</div>
				</div>
				<div class='row'>
					<div class='col-md-5'>
						<label>Город</label>
						<select name='city' class='form-control'>
							<?php
								include('cities.php');
								foreach($cities as $city){
									echo '<option>' . $city . '</option>';
								}
							?>
						</select>
					</div>
				</div>
				<div class='row'>
					<div class='col-md-5'>
						<label>Логин</label>
						<input name='login' class='form-control' />
					</div>
				</div>
				<div class='row'>
					<div class='col-md-5'>
						<label>Пароль</label>
						<input name='password' class='form-control' />
					</div>
				</div>
				<div class='row'>
					<div class='col-md-5'>
						<button class='btn btn-primary'>Зарегаться</button>
					</div>
				</div>
			</form>
		</div>
	</body>
</html>