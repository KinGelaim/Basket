<?php
	require_once("db_func.php");
	
	db_connect();
	
	$out = db_select('SELECT contacts.id,contacts.surname,contacts.name,contacts.patronymic,contacts.telephone,
		contacts.mobile,contacts.email,positions.name as name_positions,departments.name as name_department,
		maps.title, maps.name as name_map, maps.path,
		contacts.x, contacts.y,
		contacts.photo_path 
		FROM contacts 
		LEFT JOIN positions ON contacts.id_position=positions.id 
		LEFT JOIN departments ON contacts.id_department=departments.id 
		LEFT JOIN maps ON contacts.id_map=maps.id 
		ORDER BY surname, name, patronymic ASC');
	
	db_disconnect();
	
	$out_content = [];
	
	foreach($out as $data)
	{
		$new_data = [
			'id' => (int)$data[0],
			'surname' => $data[1],
			'name' => $data[2],
			'patronymic' => $data[3],
			'FIO' => $data[1] . ' ' . $data[2] . ' ' . $data[3],
			'telephone' => $data[4],
			'mobile' => $data[5] ? $data[5] : '',
			'email' => $data[6] ? $data[6] : '',
			'position' => $data[7],
			'department' => $data[8],
			'imgHref' => $data[14] ? $data[14] : '',
			'map' => [
				'title' => $data[9] . ' (' . $data[10] . ')',
				'imgHref' => $data[11] ? $data[11] : '',
				'x' => (int)$data[12],
				'y' => (int)$data[13]
			]
		];
		array_push($out_content, $new_data);
	}
	
	header('Content-Type:application/json; charset=UTF-8');
	echo json_encode($out_content);