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
				<h3 class="bars">Сводный отчёт о делах в сейфе</h3>
					<button id='createExcel' class='btn btn-primary' real_name_table='Сводный отчёт о делах в сейфе'>Сохранить в Excel</button>
					<br/>
					<br/>
					<table id='resultTable' class="table table-bordered tablePrint" style='margin: 0 auto;'>
						<thead style='text-align: center;'>
							<tr>
								<th>Полка</th>
								<th>Номер по описи</th>
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
									$result_sort = [];
									foreach($result as $t)
									{
										if(!in_array($t['place_p'],array_keys($result_sort)))
											$result_sort += [$t['place_p'] => [$t['inventory_number']]];
										else
											array_push($result_sort[$t['place_p']],$t['inventory_number']);
									}
									foreach($result_sort as $key=>$value)
									{
										echo "<tr class='hoverRow'>";
											echo '<td>' . $key . '</td>';
											$k = '';
											foreach($value as $t)
											{
												$k .= ' ' . $t . ',';
											}
											echo '<td>' . trim($k,',') . '</td>';
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