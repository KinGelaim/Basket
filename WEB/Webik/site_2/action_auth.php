<?php
	$login = trim($_GET['login']);
	$password = trim($_GET['password']);
	
	if($login == '' || $password == ''){
		echo 'Заполните необходимые поля!';
	}else{
		$all = file_get_contents('users.txt');
		$d = explode(';', $all);
		for($i = 0; $i < count($d); $i++){
			$k = explode(',',$d[$i]);
			if(count($k) > 1){
				if($k[4] == $login && $k[5] == $password){
					echo 'Пользователь авторизован! ' . $k[0];
					setcookie("MySiteCookie", $k[0], time() + 120);
					return;
				}
			}
		}
		echo 'Не найден пользователь с такой комбинацией логина и пароля!';
	}
?>