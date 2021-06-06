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
		<script type="text/javascript" src="js/ajax.js"></script>
		<script type="text/javascript" src="js/my.js"></script>
		<script type="text/javascript" src="js/excel.js"></script>
	</head>
	<body>
		<?php include('header.php'); ?>		
		<div class="">
			<div class="col-md-12">
				<h3 class="bars">Дела в сейфе</h3>
					<button id='createExcel' class='btn btn-primary' real_name_table='Дела в сейфе'>Сохранить в Excel</button>
					<br/>
					<br/>
					<table id='resultTable' class="table table-bordered tablePrint" style='margin: 0 auto;'>
						<thead style='text-align: center;'>
							<tr>
								<th>Номер по описи</th>
								<th>Индекс дела</th>
								<th>Заголовок дела</th>
								<th>Полка</th>
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
								if ($result = mysqli_query($link, "SELECT * FROM books WHERE place_s='Сейф' ORDER BY inventory_number+0")) {
									foreach($result as $t)
									{
										echo "<tr class='hoverRow'>";
											echo '<td>' . $t['inventory_number'] . '</td>';
											echo '<td>' . $t['index_book'] . '</td>';
											echo '<td>' . $t['title_book'] . '</td>';
											echo '<td>' . $t['place_p'] . '</td>';
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