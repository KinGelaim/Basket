<?php
	include('data_bd.php');
	if(isset($_POST['id'])){
		
		$per_result = 'bad';
		
		$link = mysqli_connect("localhost", $login, $password, "archive_bd");
		
		if (mysqli_connect_errno()) {
			$per_result = 'bad';
			exit();
		}
		
		mysqli_set_charset($link, "utf8");
		
		//Обновляем возврат книги
		if ($result = mysqli_query($link, "UPDATE outgoing_books SET date_incoming='" . date('d.m.Y', time()) . "' WHERE id_book=" . $_POST['id'])) {
			$per_result = 'success';
		}
		
		//Исправляем поступление у самой книги
		$query = "UPDATE books SET department=NULL,date_incoming_outgoing='" . date('d.m.Y', time()) . "' WHERE id=" . $_POST['id'];
		mysqli_query($link, $query);
		
		mysqli_close($link);
		
		$result = array(
			'result' => $per_result
		);
		
		echo json_encode($result);
	}
?>