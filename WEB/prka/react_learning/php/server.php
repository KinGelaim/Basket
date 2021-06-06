<?php
	$arr = array(
		array('userId'=>1, 'id'=>1, 'title'=>'Купи молока', 'completed'=>false),
		array('userId'=>1, 'id'=>2, 'title'=>'Купи Хлеб', 'completed'=>false),
		array('userId'=>1, 'id'=>3, 'title'=>'Приберись дома', 'completed'=>false),
		array('userId'=>1, 'id'=>4, 'title'=>'Найди себе уже девушку!', 'completed'=>false),
		array('userId'=>1, 'id'=>5, 'title'=>'Не будь лохом!', 'completed'=>false)
	);
	header('Content-Type:application/json; charset=UTF-8');
	echo json_encode($arr);