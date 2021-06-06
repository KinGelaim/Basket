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
	</head>
	<body>
		<?php include('header.php'); ?>		
		<div class="">
			<div class="col-md-12">
				<h3 class="bars">Отчёт</h3>
					<table class="table table-bordered tablePrint" style='margin: 0 auto;'>
						<thead style='text-align: center;'>
							<tr>
								<th rowspan='3'>№ п,п</th>
								<th rowspan='3'>Дата поступления и выбытия документа</th>
								<th rowspan='3'>Наименование организации (структурного подразделения, лица, архива), от которого поступили (или выбыли) документы</th>
								<th rowspan='3'>Наименование, номер и дата документа, по которому поступили или выбыли документы</th>
								<th rowspan='3'>Название и номер фонда</th>
								<th rowspan='3'>Годы поступивших или выбывших документов по описи</th>
								<th rowspan='3' class='vertical'>Вид носителя</th>
								<th colspan='3'>Поступление описанных документов</th>
								<th colspan='3'>Выбытие описанных документов</th>
								<th rowspan='2' colspan='3'>Неописанных дел, документов, листов</th>
								<th rowspan='3'>Примечание</th>
							</tr>
							<tr>
								<th colspan='3'>Количество ед.хр.</th>
								<th colspan='3'>Количество ед.хр.</th>
							</tr>
							<tr>
								<th>Постоянного хранения</th>
								<th>Временного (свыше 10 лет)</th>
								<th>По личному составу</th>
								<th>Постоянного хранения</th>
								<th>Временного (свыше 10 лет)</th>
								<th>По личному составу</th>
								<th>Поступило</th>
								<th>Выбыло</th>
							</tr>
						</thead>
						<tbody>
							<?php
								
							?>
						</tbody>
					</table>
			</div>
		</div>
	</body>
</html>