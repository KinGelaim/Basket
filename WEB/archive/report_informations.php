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
				<h3 class="bars">Информация о делах</h3>
					<button id='createExcel' class='btn btn-primary' real_name_table='Информация о делах'>Сохранить в Excel</button>
					<br/>
					<br/>
					<table id='resultTable' class="table table-bordered tablePrint" style='margin: 0 auto;'>
						<thead style='text-align: center;'>
							<tr>
								<th rowspan='2' style='vertical-align:middle;text-align:center;'>Номер по описи</th>
								<th rowspan='2' style='vertical-align:middle;text-align:center;'>Индекс дела</th>
								<th rowspan='2' style='vertical-align:middle;text-align:center;'>Заголовок дела</th>
								<th rowspan='2' style='vertical-align:middle;text-align:center;'>Крайние даты</th>
								<th rowspan='2' style='vertical-align:middle;text-align:center;'>Кол-во страниц</th>
								<th rowspan='2' style='vertical-align:middle;text-align:center;'>Примечание</th>
								<th colspan='2' style='vertical-align:middle;text-align:center;'>Местонахождение</th>
								<th rowspan='2' style='vertical-align:middle;text-align:center;'>Дата поступления / Выбытия</th>
							</tr>
							<tr>
								<th>Стеллаж, полка</th>
								<th>Выдано (отдел, цех)</th>
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
								$search_books = [];
								$asd = $_POST['cb'];
								foreach($asd as $key=>$value)
								{
									array_push($search_books, 'books.id=' . $key);
								}
								$comma_separated = implode(" OR ", $search_books);
								if ($result = mysqli_query($link, "SELECT *,books.id,types.id as typeID FROM books JOIN types ON types.id = books.type LEFT JOIN departments ON books.department=departments.id WHERE (" . $comma_separated . ") ORDER BY inventory_number+0")) {
									foreach($result as $t)
									{
										echo "<tr class='hoverRow'>";
											echo '<td>' . $t['inventory_number'] . '</td>';
											echo '<td>' . $t['index_book'] . '</td>';
											echo '<td>' . $t['title_book'] . '</td>';
											echo '<td>' . $t['date_begin_end'] . '</td>';
											echo '<td>' . $t['count'] . '</td>';
											echo '<td>' . $t['comment'] . '</td>';
											echo '<td>' . $t['place_s'] . ' ' . $t['place_p'] . '</td>';
											echo '<td>' . $t['name_department'] . '</td>';
											echo '<td>' . $t['date_incoming_outgoing'] . '</td>';
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