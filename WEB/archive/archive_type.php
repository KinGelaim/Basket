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
	
	//Переменные для пагинации
	$paginate_count = 10;
	if (isset($_GET["page"])) {
		$page  = $_GET["page"];
	} else {
		$page=1;
	};
	$start = ($page-1) * $paginate_count;
	
	//Поиск
	$name_search = 'books.id';
	$text_search = '';
	if(isset($_GET['text_search'])){
		$text_search = $_GET['text_search'];
		if($text_search != '')
			if(isset($_GET['name_search'])){
				$name_search = $_GET['name_search'];
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
		<?php include('header.php'); ?>	
		<div class="container">
			<div class='row'>
				<div class='col-md-3'>
					<button class='btn btn-primary' data-toggle='modal' data-target='#add_new_book'>Добавить документ</button>
				</div>
				<div class='col-md-9'>
					<div class="search-bar">
						<form>
							<input name='page' value='<?php if(isset($_GET['page'])) echo $_GET['page']; else echo 1; ?>' style='display: none;'/>
							<input name='type' value='<?php if(isset($_GET['type'])) echo $_GET['type']; else echo 1; ?>' style='display: none;'/>
							<div class='col-md-3'>
								<select name='name_search' class='form-control'>
									<option value='inventory_number' <?php if(isset($_GET['name_search'])) if($_GET['name_search']=='inventory_number') echo 'selected'; ?>>Номер по описи</option>
									<option value='index_book' <?php if(isset($_GET['name_search'])) if($_GET['name_search']=='index_book') echo 'selected'; ?>>Индекс дела</option>
									<option value='title_book' <?php if(isset($_GET['name_search'])) if($_GET['name_search']=='title_book') echo 'selected'; ?>>Заголовок дела</option>
									<option value='date_begin_end' <?php if(isset($_GET['name_search'])) if($_GET['name_search']=='date_begin_end') echo 'selected'; ?>>Крайние даты</option>
									<option value='comment' <?php if(isset($_GET['name_search'])) if($_GET['name_search']=='comment') echo 'selected'; ?>>Примечание</option>
								</select>
							</div>
							<div class='col-md-9'>
								<input name='text_search' type="text" value="<?php echo $text_search; ?>" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = '<?php echo $text_search; ?>';}" />
								<input type="submit" value="" style='margin-right: 10px;'/>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="grid_3 grid_5">
				<h3 class="bars">Архив <?php echo $name_type; ?></h3>
				<div>
					<table class="table table-bordered tablePrint" style='margin: 0 auto;'>
						<thead style='text-align: center;'>
							<tr>
								<th rowspan='2' style='vertical-align:middle;text-align:center;'>№</th>
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
								
								if ($result = mysqli_query($link, "SELECT *,books.id,types.id as typeID FROM books JOIN types ON types.id = books.type LEFT JOIN departments ON books.department=departments.id WHERE types.type='" . $name_type . "' AND " . $name_search . " LIKE '%" .$text_search . "%'" . " ORDER BY inventory_number+0" . " LIMIT " . $paginate_count . " OFFSET " . $start)) {
									$k = $start;
									foreach($result as $t)
									{
										echo "<tr class='cursorPointer hoverRow bookRow' data-toggle='modal' data-target='#modaling' book='".json_encode($t)."'>";
											echo '<td>' . ++$k . '</td>';
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
								
								$count_paginate = mysqli_query($link, "SELECT count(books.id) as count_books FROM books JOIN types ON types.id = books.type WHERE types.type='" . $name_type . "' AND " . $name_search . " LIKE '%" .$text_search . "%'")->fetch_row()[0] / $paginate_count;

								mysqli_close($link);
							?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-12" style="text-align: center;">
				<?php
					if($count_paginate){
						echo '<nav aria-label="Page navigation example">';
						echo '<ul class="pagination justify-content-center">';

						if($page <= 1){
							echo '<li class="page-item disabled"><a class="page-link" tabindex="-1">Предыдущая</a></li>';
						}else{
							echo '<li class="page-item"><a class="page-link" href="?type='.$_GET['type'].'&page='.($page-1).'&name_search='.$name_search.'&text_search='.$text_search.'" tabindex="-1">Предыдущая</a></li>';
						}

						if($count_paginate <= 9)
							for($i = 1; $i < $count_paginate+1; $i++){
								if($i == $page)
									echo '<li class="page-item active"><a class="page-link" href="?type='.$_GET['type'].'&page='.$i.'&name_search='.$name_search.'&text_search='.$text_search.'">'.$i.'</a></li>';
								else
									echo '<li class="page-item"><a class="page-link" href="?type='.$_GET['type'].'&page='.$i.'&name_search='.$name_search.'&text_search='.$text_search.'">'.$i.'</a></li>';
							}
						else{
							if($page < 7){
								for($i = 1; $i < 8; $i++){
									if($i == $page)
										echo '<li class="page-item active"><a class="page-link" href="?type='.$_GET['type'].'&page='.$i.'&name_search='.$name_search.'&text_search='.$text_search.'">'.$i.'</a></li>';
									else
										echo '<li class="page-item"><a class="page-link" href="?type='.$_GET['type'].'&page='.$i.'&name_search='.$name_search.'&text_search='.$text_search.'">'.$i.'</a></li>';
								}
								echo '<li class="page-item"><a class="page-link">...</a></li>';
								echo '<li class="page-item"><a class="page-link" href="?type='.$_GET['type'].'&page='.ceil($count_paginate).'&name_search='.$name_search.'&text_search='.$text_search.'">'.ceil($count_paginate).'</a></li>';
							}else if($page > $count_paginate - 5){
								echo '<li class="page-item"><a class="page-link" href="?type='.$_GET['type'].'&page=1&name_search='.$name_search.'&text_search='.$text_search.'">1</a></li>';
								echo '<li class="page-item"><a class="page-link">...</a></li>';
								for($i = ceil($count_paginate)-6; $i < $count_paginate+1; $i++){
									if($i == $page)
										echo '<li class="page-item active"><a class="page-link" href="?type='.$_GET['type'].'&page='.$i.'&name_search='.$name_search.'&text_search='.$text_search.'">'.$i.'</a></li>';
									else
										echo '<li class="page-item"><a class="page-link" href="?type='.$_GET['type'].'&page='.$i.'&name_search='.$name_search.'&text_search='.$text_search.'">'.$i.'</a></li>';
								}
							}else{
								echo '<li class="page-item"><a class="page-link" href="?type='.$_GET['type'].'&page=1&name_search='.$name_search.'&text_search='.$text_search.'">1</a></li>';
								echo '<li class="page-item"><a class="page-link">...</a></li>';
								for($i = $page-3; $i < $page+4; $i++){
									if($i == $page)
										echo '<li class="page-item active"><a class="page-link" href="?type='.$_GET['type'].'&page='.$i.'&name_search='.$name_search.'&text_search='.$text_search.'">'.$i.'</a></li>';
									else
										echo '<li class="page-item"><a class="page-link" href="?type='.$_GET['type'].'&page='.$i.'&name_search='.$name_search.'&text_search='.$text_search.'">'.$i.'</a></li>';
								}
								echo '<li class="page-item"><a class="page-link">...</a></li>';
								echo '<li class="page-item"><a class="page-link" href="?type='.$_GET['type'].'&page='.ceil($count_paginate).'&name_search='.$name_search.'&text_search='.$text_search.'">'.ceil($count_paginate).'</a></li>';
							}
						}

						if($page > $count_paginate){
							echo '<li class="page-item disabled"><a class="page-link">Следующая</a></li>';
						}
						else{
							echo '<li class="page-item"><a class="page-link" href="?type='.$_GET['type'].'&page='.($page+1).'&name_search='.$name_search.'&text_search='.$text_search.'">Следующая</a></li>';
						}

						echo '</ul>';
						echo '</nav>';
					}
				?>
			</div>
		</div>
		<!-- Модальное окно новых документов -->
		<div class="modal fade" id="add_new_book" tabindex="-1" role="dialog" aria-labelledby="newBookModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<form method='POST' id='ajax_form_new_book' action=''>
						<div class="modal-header">
							<h5 class="modal-title" id="newBookModalLabel">Новый документ</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Тип документа:</label>
								</div>
								<div class="col-md-7">
									<select name='type' class='form-control' required>
										<?php
											$link = mysqli_connect("localhost", $login, $password, "archive_bd");

											if (mysqli_connect_errno()) {
												exit();
											}

											mysqli_set_charset($link, "utf8");

											if ($result = mysqli_query($link, "SELECT * FROM types")) {
												foreach($result as $type)
													if($type['type'] == $name_type)
														echo '<option value="'.$type['id'].'" selected>' . $type['type'] . '</option>';
													else
														echo '<option value="'.$type['id'].'">' . $type['type'] . '</option>';
											}

											mysqli_close($link);
										?>
									</select>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Номер по описи:</label>
								</div>
								<div class="col-md-7">
									<input class='form-control' type='text' name='inventory_number' value="" required />
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Индекс дела:</label>
								</div>
								<div class="col-md-7">
									<input class='form-control' type='text' name='index_book' value="" required />
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Заголовок дела:</label>
								</div>
								<div class="col-md-7">
									<textarea name='title_book' class='form-control' type='text' value='' rows=3></textarea>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Крайние даты:</label>
								</div>
								<div class="col-md-7">
									<input name='date_begin_end' class='form-control' type='text' value=''/>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Кол-во страниц:</label>
								</div>
								<div class="col-md-7">
									<input name='count' class='form-control' type='text' value=''/>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Примечание:</label>
								</div>
								<div class="col-md-7">
									<input name='comment' class='form-control' type='text' value=''/>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Стеллаж:</label>
								</div>
								<div class="col-md-3">
									<select name='place_s' class='form-control'>
										<option></option>
										<option>1</option>
										<option>2</option>
										<option>3</option>
										<option>4</option>
										<option>5</option>
										<option>6</option>
										<option>7</option>
										<option>8</option>
										<option>9</option>
										<option>10</option>
										<option>11</option>
										<option>12</option>
										<option>13</option>
										<option>Сейф</option>
									</select>
								</div>
								<div class="col-md-1">
									<label>Полка:</label>
								</div>
								<div class="col-md-3">
									<select name='place_p' class='form-control'>
										<option></option>
										<option>1</option>
										<option>2</option>
										<option>3</option>
										<option>4</option>
										<option>5</option>
									</select>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Выдано (отдел, цех):</label>
								</div>
								<div class="col-md-7">
									<select name='department' class='form-control'>
										<option></option>
										<?php
											$link = mysqli_connect("localhost", $login, $password, "archive_bd");

											if (mysqli_connect_errno()) {
												exit();
											}

											mysqli_set_charset($link, "utf8");

											if ($result = mysqli_query($link, "SELECT * FROM departments ORDER BY name_department ASC")) {
												foreach($result as $department)
													echo '<option value="'.$department['id'].'">' . $department['name_department'] . '</option>';
											}

											mysqli_close($link);
										?>
									</select>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Дата поступления:</label>
								</div>
								<div class="col-md-7">
									<input name='date_incoming_outgoing' class='form-control datepicker' type='text' value='<?php echo date('d.m.Y', time()); ?>'/>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Наименование цеха, отдела от которого поступили:</label>
								</div>
								<div class="col-md-7">
									<select name='counterpartie' class='form-control'>
										<option></option>
										<?php
											$link = mysqli_connect("localhost", $login, $password, "archive_bd");

											if (mysqli_connect_errno()) {
												exit();
											}

											mysqli_set_charset($link, "utf8");

											if ($result = mysqli_query($link, "SELECT * FROM departments ORDER BY name_department ASC")) {
												foreach($result as $department)
													echo '<option value="'.$department['id'].'">' . $department['name_department'] . '</option>';
											}

											mysqli_close($link);
										?>
									</select>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Наименование, номер и дата документа, по которому поступили:</label>
								</div>
								<div class="col-md-7">
									<input name='document_incoming' class='form-control' type='text' value=''/>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Наименование и номер фонда:</label>
								</div>
								<div class="col-md-7">
									<input name='fond' class='form-control' type='text' value='638'/>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type='button' class='btn btn-primary ajax-submit' id_form='#ajax_form_new_book' action='action_add_ajax_form.php' id_modal='#add_new_book' style='width: 122px;'>Добавить</button>
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Модальное окно редактирование документов -->
		<div class="modal fade" id="update_new_book" tabindex="-1" role="dialog" aria-labelledby="updateBookModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<form method='POST' id='ajax_form_update_book' action=''>
						<div class="modal-header">
							<h5 class="modal-title" id="updateBookModalLabel">Редактировать документ</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class='form-group row' style='display: none;'>
								<div class="col-md-3">
									<label>id:</label>
								</div>
								<div class="col-md-7">
									<input id='updateId' class='form-control' type='text' name='id' value=""/>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Тип документа:</label>
								</div>
								<div class="col-md-7">
									<select id='updateType' name='type' class='form-control' required>
										<?php
											$link = mysqli_connect("localhost", $login, $password, "archive_bd");
											
											if (mysqli_connect_errno()) {
												exit();
											}
											
											mysqli_set_charset($link, "utf8");
											
											if ($result = mysqli_query($link, "SELECT * FROM types")) {
												foreach($result as $type)
													echo '<option value="'.$type['id'].'">' . $type['type'] . '</option>';
											}

											mysqli_close($link);
										?>
									</select>
								</div>
								<div class="col-md-2">
									<button id='btnOutgoing' type='button' class='btn btn-primary' style='width: 122px;'>Выдать</button>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Номер по описи:</label>
								</div>
								<div class="col-md-7">
									<input id='updateInventoryNumber' class='form-control' type='text' name='inventory_number' value=""/>
								</div>
								<div class="col-md-2">
									<button id='btnAjaxDeleting' type='button' class='btn btn-danger' style='width: 122px;'>Удалить</button>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Индекс дела:</label>
								</div>
								<div class="col-md-7">
									<input id='updateIndex' class='form-control' type='text' name='index_book' value=""/>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Заголовок дела:</label>
								</div>
								<div class="col-md-7">
									<textarea id='updateTitle' name='title_book' class='form-control' type='text' value='' rows=3></textarea>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Крайние даты:</label>
								</div>
								<div class="col-md-7">
									<input id='updateDateBeginEnd' name='date_begin_end' class='form-control' type='text' value=''/>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Кол-во страниц:</label>
								</div>
								<div class="col-md-7">
									<input id='updateCount' name='count' class='form-control' type='text' value=''/>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Примечание:</label>
								</div>
								<div class="col-md-7">
									<input id='updateComment' name='comment' class='form-control' type='text' value=''/>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Стеллаж:</label>
								</div>
								<div class="col-md-3">
									<select id='updatePlaceS' name='place_s' class='form-control'>
										<option></option>
										<option>1</option>
										<option>2</option>
										<option>3</option>
										<option>4</option>
										<option>5</option>
										<option>6</option>
										<option>7</option>
										<option>8</option>
										<option>9</option>
										<option>10</option>
										<option>11</option>
										<option>12</option>
										<option>13</option>
										<option>Сейф</option>
									</select>
								</div>
								<div class="col-md-1">
									<label>Полка:</label>
								</div>
								<div class="col-md-3">
									<select id='updatePlaceP' name='place_p' class='form-control'>
										<option></option>
										<option>1</option>
										<option>2</option>
										<option>3</option>
										<option>4</option>
										<option>5</option>
									</select>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Выдано (отдел, цех):</label>
								</div>
								<div class="col-md-7">
									<select id='updateDepartment' name='department' class='form-control'>
										<option></option>
										<?php
											$link = mysqli_connect("localhost", $login, $password, "archive_bd");

											if (mysqli_connect_errno()) {
												exit();
											}

											mysqli_set_charset($link, "utf8");

											if ($result = mysqli_query($link, "SELECT * FROM departments ORDER BY name_department ASC")) {
												foreach($result as $department)
													echo '<option value="'.$department['id'].'">' . $department['name_department'] . '</option>';
											}

											mysqli_close($link);
										?>
									</select>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Дата поступления:</label>
								</div>
								<div class="col-md-7">
									<input id='updateDateIncomingOutgoing' name='date_incoming_outgoing' class='form-control datepicker' type='text' value=''/>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Наименование цеха, отдела от которого поступили:</label>
								</div>
								<div class="col-md-7">
									<select id='updateCounterpartie' name='counterpartie' class='form-control'>
										<option></option>
										<?php
											$link = mysqli_connect("localhost", $login, $password, "archive_bd");

											if (mysqli_connect_errno()) {
												exit();
											}

											mysqli_set_charset($link, "utf8");

											if ($result = mysqli_query($link, "SELECT * FROM departments ORDER BY name_department ASC")) {
												foreach($result as $department)
													echo '<option value="'.$department['id'].'">' . $department['name_department'] . '</option>';
											}

											mysqli_close($link);
										?>
									</select>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Наименование, номер и дата документа, по которому поступили:</label>
								</div>
								<div class="col-md-7">
									<input id='updateDocumentIncoming' name='document_incoming' class='form-control' type='text' value=''/>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Наименование и номер фонда:</label>
								</div>
								<div class="col-md-7">
									<input id='updateFond' name='fond' class='form-control' type='text' value=''/>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type='button' class='btn btn-primary ajax-submit' id_form='#ajax_form_update_book' action='action_update_ajax_form.php' id_modal='#update_new_book' style='width: 122px;'>Редактировать</button>
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>