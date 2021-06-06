<?php
	$array = array(
		[
			'id' => 1,
			'name' => 'Льзя',
			'url' => 'http://localhost/prka/react/React_Learn_2/img/lzy.png'
		],
		[
			'id' => 2,
			'name' => 'Фиаско',
			'url' => 'http://localhost/prka/react/React_Learn_2/img/fiasco.png'
		]
	);
	$json = json_encode($array);
	echo $json;