<!DOCTYPE html>
<?php include('data_bd.php'); ?>
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
		<script type="text/javascript" src="js/my.js"></script>
	</head>
	<body>
		<div class='print-hidden'>
			<?php
				include('data_bd.php');
				include('header.php');
				
				$link = mysqli_connect("localhost", $login, $password, "archive_bd");
				
				if (mysqli_connect_errno()) {
					printf("Connect failed: %s\n", mysqli_connect_error());
					exit();
				}
				
				mysqli_set_charset($link, "utf8");
				
				if ($result = mysqli_query($link, "SELECT * FROM outgoing_books WHERE id=" . $_GET['id_outgoing'])) {
					$row = $result->fetch_row();
					$delo = [
						'id' => $row[0],
						'id_book' => $row[1],
						'delo' => $row[2],
						'opis' => $row[3],
						'fond' => $row[4],
						'FIO' => $row[5],
						'place_outgoing' => $row[6],
						'date_outgoing' => $row[7],
						'date_incoming' => $row[8]
					];
					mysqli_free_result($result);
				}
				
				$query = "SELECT * FROM departments WHERE id=" . $delo['place_outgoing'];
				$place = mysqli_query($link, $query)->fetch_row()[1];
				
				//print_r($place);
				
				mysqli_close($link);
			?>
		</div>
		<div class="col-md-12 print">
			<div class="col-md-5">
				<h3 class="bars">КАРТА-ЗАМЕСТИТЕЛЬ ДЕЛА</h3>
				<h4 class="bars">Дело: <?php echo $delo['delo'];?></h3>
				<h4 class="bars">Опись: <?php echo $delo['opis'];?></h3>
				<h4 class="bars">Фонд: <?php echo $delo['fond'];?></h3>
				<h4 class="bars">Выдано во временное пользование</h3>
				<table class="table table-bordered tablePrint" style='margin: 0 auto;'>
					<thead style='text-align: center;'>
						<tr>
							<th>№ п/п</th>
							<th>ФИО пользователя</th>
							<th>Дата выдачи</th>
							<th>Дата возврата</th>
							<th>Подпись выдавшего дело</th>
							<th>Подпись получившего дело</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1</td>
							<td>2</td>
							<td>3</td>
							<td>4</td>
							<td>5</td>
							<td>6</td>
						</tr>
						<?php
							$k = 0;
							echo "<tr>";
								echo '<td>' . ++$k . '</td>';
								echo '<td>' . $delo['FIO'] . '</td>';
								echo '<td>' . $delo['date_outgoing'] . '</td>';
								echo '<td>' . $delo['date_incoming'] . '</td>';
								echo '<td></td>';
								echo '<td></td>';
							echo '</tr>';
						?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-md-12 print">
			<div class="col-md-7">
				<h3 class="bars">Книга выдачи дел из архива</h3>
				<table class="table table-bordered tablePrint" style='margin: 0 auto;'>
					<thead style='text-align: center;'>
						<tr>
							<th>№ п/п</th>
							<th>Дата выдачи</th>
							<th>Опись, номер</th>
							<th>Индекс дела</th>
							<th>Кому выдано</th>
							<th>Расписка в получении</th>
							<th>Дата возврата дела</th>
							<th>Расписка в возвращении дела</th>
							<th>Примечание</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$k = 0;
							echo "<tr>";
								echo '<td>' . ++$k . '</td>';
								echo '<td>' . $delo['date_outgoing'] . '</td>';
								echo '<td>' . $delo['opis'] . '</td>';
								echo '<td>' . $delo['delo'] . '</td>';
								echo '<td>' . $place . '<br/>' . $delo['FIO'] . '</td>';
								echo '<td></td>';
								echo '<td>' . $delo['date_incoming'] . '</td>';
								echo '<td></td>';
								echo '<td></td>';
							echo '</tr>';
						?>
					</tbody>
				</table>
			</div>
		</div>
	</body>
</html>