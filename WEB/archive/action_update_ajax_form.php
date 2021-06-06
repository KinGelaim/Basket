<?php
	include('data_bd.php');
	if(isset($_POST['index_book']) && isset($_POST['id'])){
		
		$per_result = 'bad';
		
		if(strlen($_POST['type']) > 0){
			$link = mysqli_connect("localhost", $login, $password, "archive_bd");
			
			if (mysqli_connect_errno()) {
				$per_result = 'bad';
				exit();
			}
			
			mysqli_set_charset($link, "utf8");
			
			$type = $_POST['type'];
			
			//Номер по описи
			if(strlen($_POST['inventory_number']) > 0)
				$inventory_number = "'" . $_POST['inventory_number'] . "'";
			else
				$inventory_number = "NULL";

			if($inventory_number == "NULL"){
				$result = array(
					'result' => 'error',
					'message' => 'Заполните номер по описи!'
				);
			
				echo json_encode($result);
				return 0;
			}
			
			$search_index = mysqli_query($link, "SELECT inventory_number FROM books WHERE inventory_number=" . $inventory_number . " AND id!=" . $_POST['id'] . ' AND type=' . $type);
			$count = 0;
			foreach($search_index as $in_search)
				$count++;
			if($count > 0){
				$result = array(
					'result' => 'error',
					'message' => 'Изменить не удалось! Такой номер по описи уже существует!'
				);
			
				echo json_encode($result);
				return 0;
			}
			
			//Индекс дела
			if(strlen($_POST['index_book']) > 0)
				$index_book = "'" . $_POST['index_book'] . "'";
			else
				$index_book = "NULL";
			
			//Заголовок
			if(strlen($_POST['title_book']) > 0)
				$title_book = "'" . $_POST['title_book'] . "'";
			else
				$title_book = "NULL";
			
			if(strlen($_POST['date_begin_end']) > 0)
				$date_begin_end = "'" . $_POST['date_begin_end'] . "'";
			else
				$date_begin_end = "NULL";
				
			if(strlen($_POST['count']) > 0)
				$count = "'" . $_POST['count'] . "'";
			else
				$count = "NULL";
				
			if(strlen($_POST['comment']) > 0)
				$comment = "'" . $_POST['comment'] . "'";
			else
				$comment = "NULL";
				
			if(strlen($_POST['place_s']) > 0)
				$place_s = "'" . $_POST['place_s'] . "'";
			else
				$place_s = "NULL";
				
			if(strlen($_POST['place_p']) > 0)
				$place_p = "'" . $_POST['place_p'] . "'";
			else
				$place_p = "NULL";
				
			if(strlen($_POST['department']) > 0)
				$department = "'" . $_POST['department'] . "'";
			else
				$department = "NULL";
				
			if(strlen($_POST['date_incoming_outgoing']) > 0)
				$date_incoming_outgoing = "'" . $_POST['date_incoming_outgoing'] . "'";
			else
				$date_incoming_outgoing = "NULL";
				
			if(strlen($_POST['counterpartie']) > 0)
				$counterpartie = "'" . $_POST['counterpartie'] . "'";
			else
				$counterpartie = "NULL";
				
			if(strlen($_POST['document_incoming']) > 0)
				$document_incoming = "'" . $_POST['document_incoming'] . "'";
			else
				$document_incoming = "NULL";
				
			if(strlen($_POST['fond']) > 0)
				$fond = "'" . $_POST['fond'] . "'";
			else
				$fond = "NULL";
			
			if ($result = mysqli_query($link, "UPDATE books SET type=" . $type . ",inventory_number=" . $inventory_number . ",index_book=" . $index_book . ",title_book=" . $title_book . ",date_begin_end=" . $date_begin_end . ",count=" . $count . ",comment=" . $comment . ",place_s=" . $place_s . ",place_p=" . $place_p . ",department=" . $department . ",date_incoming_outgoing=" . $date_incoming_outgoing . ",counterpartie=" . $counterpartie . ",document_incoming=" . $document_incoming . ",fond=" . $fond . " WHERE id=" . $_POST['id'])) {
				$per_result = 'success';
			}

			mysqli_close($link);
		}
		
		$result = array(
			'result' => $per_result
		);
		
		echo json_encode($result);
	}
?>