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
		
		//Удаляем книгу
		if ($result = mysqli_query($link, "DELETE FROM books WHERE id=" . $_POST['id'])) {
			$per_result = 'success';
		}
		
		mysqli_close($link);
		
		$result = array(
			'result' => $per_result
		);
		
		echo json_encode($result);
	}
?>