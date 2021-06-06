<?php
	//headr('Content-')
	$array = array(
		'surname' => 'Иванов',
		'name' => 'Иван'
	);
	$json = json_encode($array);
	sleep(10);	//Задержка 10 секунд
	print($json);