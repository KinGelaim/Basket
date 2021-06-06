<!DOCTYPE html>
<?php
	include('data_bd.php');
	
	$name_type = '';
	if(isset($_GET['type'])){
		switch($_GET['type']){
			case '2':
				$name_type = 'Постоянного хранения';
				break;
			case '3':
				$name_type = 'Временного хранения';
				break;
			case '4':
				$name_type = 'По личному составу';
				break;
			default:
				break;
		}
	}
?>
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
		<script type="text/javascript" src="js/cb.js"></script>
	</head>
	<body>
		<?php include('header.php'); ?>		
		<div class="">
			<div class="col-md-12">
				<h3 class="bars">Информация о делах <?php echo $name_type; ?></h3>
				<form method='POST' action='report_informations.php'>
					<button class='btn btn-primary' type='submit'>Просмотреть отчёт</button>
					<br/>
					<br/>
					<table class="table table-bordered tablePrint" style='margin: 0 auto;'>
						<thead style='text-align: center;'>
							<tr>
								<th>Отметить</th>
								<th>Номер по описи</th>
								<th>Индекс дела</th>
								<th>Заголовок дела</th>
								<th>Местонахождение</th>
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
								if ($result = mysqli_query($link, "SELECT *,books.id FROM books JOIN types ON types.id = books.type WHERE types.type='" . $name_type . "' ORDER BY inventory_number+0")) {
									$k = 1;
									foreach($result as $t)
									{
										echo "<tr class='cbChose hoverRow cursorPointer' forID='#cb" . $t['id'] . "'>";
											echo '<td><input id="cb' . $t['id'] . '" type="checkbox" name="cb[' . $t['id'] . ']"/></td>';
											echo '<td>' . $t['inventory_number'] . '</td>';
											echo '<td>' . $t['index_book'] . '</td>';
											echo '<td>' . $t['title_book'] . '</td>';
											echo '<td>' . $t['place_s'] . ' ' . $t['place_p'] . '</td>';
										echo '</tr>';
									}
									mysqli_free_result($result);
								}
								mysqli_close($link);
							?>
						</tbody>
					</table>
				</form>
			</div>
		</div>
	</body>
</html>