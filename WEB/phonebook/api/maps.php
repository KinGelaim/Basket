<?php
	require_once("db_func.php");
	
	$out_content = [];
	
	if(isset($_GET['id']))
	{
		if(strlen($_GET['id']) > 0)
		{
			db_connect();
		
			$out = db_select('SELECT maps.id,maps.title,maps.name as map_name,path,path_bulk,contacts.surname,contacts.name as contract_name,contacts.patronymic,
				positions.name as position_name,contacts.telephone,contacts.photo_path,contacts.x,contacts.y 
				FROM contacts 
				JOIN positions ON contacts.id_position=positions.id 
				JOIN maps ON contacts.id_map=maps.id 
				WHERE id_map=' . $_GET['id']);
			
			db_disconnect();
			
			if($out != null)
			{
				$contacts = [];
				foreach($out as $data)
				{
					$new_data = [
						'FIO' => $data[5] . ' ' . $data[6] . ' ' . $data[7],
						'position' => $data[8],
						'telephone' => $data[9],
						'imgHref' => $data[10],
						'x' => (int)$data[11],
						'y' => (int)$data[12]
					];
					array_push($contacts, $new_data);
				}
				$out_content = [
					'id' => $data[0],
					'title' => $data[1] . ' (' . $data[2] . ')',
					'imgHref' => $data[3],
					'href3D' => $data[4],
					'contacts' => $contacts
				];
			}
		}
	}
	else
	{
		db_connect();
		
		$out = db_select('SELECT id,title,name,path,path_bulk FROM maps');
		
		db_disconnect();
		
		foreach($out as $data)
		{
			if(!in_array($out[1],array_keys($out_content)))
				$out_content += [$data[1] => [
					'floors' => [
						[
							'id' => (int)$data[0],
							'title' => $data[2]
						]
					]
				]];
			else
				array_push($out_content[$data[1]['floors']], ['id'=>$data[0],'title'=>$data[2]]);
		}
	}
	
	header('Content-Type:application/json; charset=UTF-8');
	echo json_encode($out_content);