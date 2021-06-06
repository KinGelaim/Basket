<?php
	$FIO = trim($_POST['FIO']);
	$date = trim($_POST['date']);
	$phone = trim($_POST['telephone']);
	$city = trim($_POST['city']);
	$login = trim($_POST['login']);
	$password = trim($_POST['password']);
	$dt = date('d.m.Y H:i:s', time());
	
	if($FIO == '' || $phone == ''){
		echo 'Заполните необходимые поля!';
	}else{
		file_put_contents('users.txt', "$FIO,$date,$phone,$city,$login,$password,$dt;\n", FILE_APPEND);
		echo 'Пользователь зарегистрирован!';
	}
?>