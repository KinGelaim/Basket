<!DOCTYPE html>
<html>
	<head>
		<title>Выдача</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		<!-- CSS -->
		<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
		<link href="css/style.css" rel='stylesheet' type='text/css' />
		<link href="css/jquery-ui.min.css" rel='stylesheet' type='text/css' />
		<link href="css/arhive_style.css" rel='stylesheet' type='text/css' />
		
		<!-- JS -->
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script>
		<script type="text/javascript" src="js/ajax.js"></script>
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/my.js"></script>
	</head>
	<body>
		<?php
			include('data_bd.php');
			include('header.php');
			
			$link = mysqli_connect("localhost", $login, $password, "archive_bd");
			
			if (mysqli_connect_errno()) {
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
			}
			
			mysqli_set_charset($link, "utf8");
			
			if ($result = mysqli_query($link, "SELECT books.id,types.type,index_book FROM books JOIN types ON types.id=books.type WHERE books.id=" . $_GET['id'])) {
				$row = $result->fetch_row();
				$delo = [
					'id' => $row[0],
					'type' => $row[1],
					'index_book' => $row[2]
				];
				mysqli_free_result($result);
			}
			
			//print_r($delo);
			
			mysqli_close($link);
		?>
		<div class="container">
			<form id='ajax_form_out_book' method='POST'>
				<div class='form-group row' style='display: none;'>
					<div class="col-md-1">
						<label>id:</label>
					</div>
					<div class="col-md-4">
						<input name='id' class='form-control' type='text' value="<?php echo $_GET['id']; ?>" required/>
					</div>
				</div>
				<div class='form-group row'>
					<div class="col-md-1">
						<label>Дело:</label>
					</div>
					<div class="col-md-4">
						<input name='delo' class='form-control' type='text' value='<?php echo $delo['index_book']; ?>' required/>
					</div>
				</div>
				<div class='form-group row'>
					<div class="col-md-1">
						<label>Опись:</label>
					</div>
					<div class="col-md-4">
						<input name='opis' class='form-control' type='text' value='<?php if($delo['type']=='Постоянного хранения')echo 1; elseif($delo['type']=='Временного хранения')echo '1-Л'; else echo '2-Л'; ?>'/>
					</div>
				</div>
				<div class='form-group row'>
					<div class="col-md-1">
						<label>Фонд:</label>
					</div>
					<div class="col-md-4">
						<input name='fond' class='form-control' type='text' value='638'/>
					</div>
				</div>
				<div class='form-group row'>
					<div class="col-md-1">
						<label>ФИО:</label>
					</div>
					<div class="col-md-4">
						<input name='FIO' class='form-control' type='text' value=''/>
					</div>
				</div>
				<div class='form-group row'>
					<div class="col-md-1">
						<label>Отдел, цех:</label>
					</div>
					<div class="col-md-4">
						<select name='place_outgoing' class='form-control'>
							<?php
								$link = mysqli_connect("localhost", $login, $password, "archive_bd");

								if (mysqli_connect_errno()) {
									exit();
								}

								mysqli_set_charset($link, "utf8");

								if ($result = mysqli_query($link, "SELECT * FROM departments")) {
									foreach($result as $department)
										echo '<option value="'.$department['id'].'">' . $department['name_department'] . '</option>';
								}

								mysqli_close($link);
							?>
						</select>
					</div>
				</div>
				<div class='form-group row'>
					<div class="col-md-1">
						<label>Дата выдачи:</label>
					</div>
					<div class="col-md-4">
						<input name='date_outgoing' class='form-control datepicker' type='text' value='<?php echo date('d.m.Y', time()); ?>'/>
					</div>
				</div>
				<button type='btn' class='btn btn-primary ajax-submit' id_form='#ajax_form_out_book' action='action_outgoing_ajax_form.php'>Выдать</button>
			</form>
		</div>
	</body>
</html>