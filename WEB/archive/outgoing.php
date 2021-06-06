<!DOCTYPE html>
<html>
	<head>
		<title>Архив</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		<!-- CSS -->
		<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
		<link href="css/style.css" rel='stylesheet' type='text/css' />
		<link href="css/arhive_style.css" rel='stylesheet' type='text/css' />
		
		<!-- JS -->
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script>
		<script type="text/javascript" src="js/ajax.js"></script>
		<script type="text/javascript" src="js/my.js"></script>
	</head>
	<body>
		<?php
			include('data_bd.php');
			include('header.php');
		?>
		<div class="container">
			<div class="grid_3 grid_5">
				<h3 class="bars">Книга выдачи</h3>
					<table class="table table-bordered tablePrint" style='margin: 0 auto;'>
						<thead style='text-align: center;'>
							<tr>
								<th>№</th>
								<th>Стеллаж, полка</th>
								<th>Заголовок дела</th>
								<th>ФИО пользователя</th>
								<th>Отдел, цех</th>
								<th>Дата выдачи</th>
								<th>Дата возврата</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$link = mysqli_connect("localhost", $login, $password, "archive_bd");
								
								if (mysqli_connect_errno()) {
									printf("Connect failed: %s\n", mysqli_connect_error());
									exit();
								}
								
								mysqli_set_charset($link, "utf8");
								
								if ($result = mysqli_query($link, "SELECT *,outgoing_books.id FROM outgoing_books JOIN books ON outgoing_books.id_book = books.id LEFT JOIN departments ON departments.id=outgoing_books.place_outgoing")) {
									$k = 0;
									foreach($result as $t)
									{
										echo "<tr class='cursorPointer hoverRow incomingBook' id_book='" . $t['id_book'] . "' date_incoming='" . $t['date_incoming'] . "'>";
											echo '<td>' . ++$k . '</td>';
											echo '<td>' . $t['place_s'] . ' ' . $t['place_p'] . '</td>';
											echo '<td>' . $t['title_book'] . '</td>';
											echo '<td>' . $t['FIO'] . '</td>';
											echo '<td>' . $t['name_department'] . '</td>';
											echo '<td>' . $t['date_outgoing'] . '</td>';
											echo '<td>' . $t['date_incoming'] . '</td>';
										echo '</tr>';
									}
									mysqli_free_result($result);
								}

								mysqli_close($link);
							?>
						</tbody>
					</table>
			</div>
		</div>
	</body>
</html>