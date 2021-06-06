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
		
		if(strlen($_POST['delo']) > 0)
			$delo = "'" . $_POST['delo'] . "'";
		else
			$delo = "NULL";
			
		if(strlen($_POST['opis']) > 0)
			$opis = "'" . $_POST['opis'] . "'";
		else
			$opis = "NULL";
		
		if(strlen($_POST['fond']) > 0)
			$fond = "'" . $_POST['fond'] . "'";
		else
			$fond = "NULL";
			
		if(strlen($_POST['FIO']) > 0)
			$FIO = "'" . $_POST['FIO'] . "'";
		else
			$FIO = "NULL";
			
		if(strlen($_POST['place_outgoing']) > 0)
			$place_outgoing = "'" . $_POST['place_outgoing'] . "'";
		else
			$place_outgoing = "NULL";
			
		if(strlen($_POST['date_outgoing']) > 0)
			$date_outgoing = "'" . $_POST['date_outgoing'] . "'";
		else
			$date_outgoing = "NULL";
		
		//Добавляем выдачу книги
		if ($result = mysqli_query($link, "INSERT INTO outgoing_books (id_book, delo, opis, fond, FIO, place_outgoing, date_outgoing) VALUES (" . $_POST['id'] . "," . $delo . "," . $opis . "," . $fond . "," . $FIO . "," .$place_outgoing . "," . $date_outgoing . ")")) {
			$per_result = 'href';
		}
		
		//Получаем ID выдачи (для формирования двух карточек)
		$id_outgoing = mysqli_insert_id($link);
		
		//Исправляем выдачу у самой книги
		$query = "UPDATE books SET department=" . $place_outgoing . " WHERE id=" . $_POST['id'];
		mysqli_query($link, $query);
		
		mysqli_close($link);
		
		$result = array(
			'result' => $per_result,
			'href' => 'card.php?id_outgoing='.$id_outgoing,
		);
		
		echo json_encode($result);
	}
?>