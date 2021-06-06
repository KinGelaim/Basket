<!DOCTYPE html>
<?php
	include('data_bd.php');
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
	</head>
	<body>
		<?php include('header.php'); ?>	
		<div class="container">
			<div class='row'>
				<div class='col-md-3'>
					<button class='btn-primary' data-toggle='modal' data-target='#add_new_book'>Добавить документ</button>
				</div>
				<div class='col-md-9'>
					<div class="search-bar">
						<!--<form>-->
							<input type="text" value="Поиск документа" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Поиск документа';}">
							<input type="submit" value="" disabled>
						<!--</form>-->
					</div>
				</div>
			</div>
			<div class="grid_3 grid_5">
				<h3 class="bars">Архив</h3>
				<div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
					<ul id="myTab" class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active"><a href="#home" id="home-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">Постоянного хранения</a></li>
						<li role="presentation"><a href="#profile" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile">Временного</a></li>
						<li role="presentation"><a href="#lich" role="tab" id="profile-tab" data-toggle="tab" aria-controls="lich">По личному составу</a></li>
					</ul>
					<div id="myTabContent" class="tab-content">
						<div role="tabpanel" class="tab-pane fade in active" id="home" aria-labelledby="home-tab">
							<table class="table table-bordered tablePrint" style='margin: 0 auto;'>
								<thead style='text-align: center;'>
									<tr>
										<th rowspan='2'>№</th>
										<th rowspan='2'>Индекс дела</th>
										<th rowspan='2'>Заголовок дела</th>
										<th rowspan='2'>Крайние даты</th>
										<th rowspan='2'>Кол-во страниц</th>
										<th rowspan='2'>Примечание</th>
										<th colspan='2'>Местонахождение</th>
										<th rowspan='2'>Дата поступления / Выбытия</th>
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
										
										if ($result = mysqli_query($link, "SELECT *,books.id FROM books JOIN types ON types.id = books.type WHERE types.type='Постоянного хранения'")) {
											$k = 0;
											foreach($result as $t)
											{
												echo "<tr class='cursorPointer hoverRow bookRow' data-toggle='modal' data-target='#modaling' book='".json_encode($t)."'>";
													echo '<td>' . ++$k . '</td>';
													echo '<td>' . $t['index_book'] . '</td>';
													echo '<td>' . $t['title_book'] . '</td>';
													echo '<td>' . $t['date_begin_end'] . '</td>';
													echo '<td>' . $t['count'] . '</td>';
													echo '<td>' . $t['comment'] . '</td>';
													echo '<td>' . $t['place_s'] . '</td>';
													echo '<td>' . $t['department'] . '</td>';
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
						<div role="tabpanel" class="tab-pane fade" id="profile" aria-labelledby="profile-tab">
							<table class="table table-bordered tablePrint" style='margin: 0 auto;'>
								<thead style='text-align: center;'>
									<tr>
										<th rowspan='2'>№</th>
										<th rowspan='2'>Индекс дела</th>
										<th rowspan='2'>Заголовок дела</th>
										<th rowspan='2'>Крайние даты</th>
										<th rowspan='2'>Кол-во страниц</th>
										<th rowspan='2'>Примечание</th>
										<th colspan='2'>Местонахождение</th>
										<th rowspan='2'>Дата поступления / Выбытия</th>
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
										
										if ($result = mysqli_query($link, "SELECT *,books.id FROM books JOIN types ON types.id = books.type WHERE types.type='Временного хранения'")) {
											$k = 0;
											foreach($result as $t)
											{
												echo "<tr class='cursorPointer hoverRow bookRow' data-toggle='modal' data-target='#modaling' book='".json_encode($t)."'>";
													echo '<td>' . ++$k . '</td>';
													echo '<td>' . $t['index_book'] . '</td>';
													echo '<td>' . $t['title_book'] . '</td>';
													echo '<td>' . $t['date_begin_end'] . '</td>';
													echo '<td>' . $t['count'] . '</td>';
													echo '<td>' . $t['comment'] . '</td>';
													echo '<td>' . $t['place_s'] . '</td>';
													echo '<td>' . $t['department'] . '</td>';
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
						<div role="tabpanel" class="tab-pane fade" id="lich" aria-labelledby="lich-tab">
							<table class="table table-bordered tablePrint" style='margin: 0 auto;'>
								<thead style='text-align: center;'>
									<tr>
										<th rowspan='2'>№</th>
										<th rowspan='2'>Индекс дела</th>
										<th rowspan='2'>Заголовок дела</th>
										<th rowspan='2'>Крайние даты</th>
										<th rowspan='2'>Кол-во страниц</th>
										<th rowspan='2'>Примечание</th>
										<th colspan='2'>Местонахождение</th>
										<th rowspan='2'>Дата поступления / Выбытия</th>
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
										
										if ($result = mysqli_query($link, "SELECT *,books.id FROM books JOIN types ON types.id = books.type WHERE types.type='По личному составу'")) {
											$k = 0;
											foreach($result as $t)
											{
												echo "<tr class='cursorPointer hoverRow bookRow' data-toggle='modal' data-target='#modaling' book='".json_encode($t)."'>";
													echo '<td>' . ++$k . '</td>';
													echo '<td>' . $t['index_book'] . '</td>';
													echo '<td>' . $t['title_book'] . '</td>';
													echo '<td>' . $t['date_begin_end'] . '</td>';
													echo '<td>' . $t['count'] . '</td>';
													echo '<td>' . $t['comment'] . '</td>';
													echo '<td>' . $t['place_s'] . '</td>';
													echo '<td>' . $t['department'] . '</td>';
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
				</div>
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
													echo '<option value="'.$type['id'].'">' . $type['type'] . '</option>';
											}

											mysqli_close($link);
										?>
									</select>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Индекс дела:</label>
								</div>
								<div class="col-md-7">
									<input class='form-control' type='text' name='index_book' value=""/>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Заголовок дела:</label>
								</div>
								<div class="col-md-7">
									<input name='title_book' class='form-control' type='text' value=''/>
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
									<label>Стеллаж, полка:</label>
								</div>
								<div class="col-md-7">
									<input name='place' class='form-control' type='text' value=''/>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Выдано (отдел, цех):</label>
								</div>
								<div class="col-md-7">
									<input name='department' class='form-control' type='text' value=''/>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Дата поступления:</label>
								</div>
								<div class="col-md-7">
									<input name='date_incoming_outgoing' class='form-control datepicker' type='text' value=''/>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Наименование организации от которого поступили:</label>
								</div>
								<div class="col-md-7">
									<input name='counterpartie' class='form-control' type='text' value=''/>
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
									<input name='fond' class='form-control' type='text' value=''/>
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
									<input id='updateTitle' name='title_book' class='form-control' type='text' value=''/>
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
									<label>Стеллаж, полка:</label>
								</div>
								<div class="col-md-7">
									<input id='updatePlace' name='place' class='form-control' type='text' value=''/>
								</div>
							</div>
							<div class='form-group row'>
								<div class="col-md-3">
									<label>Выдано (отдел, цех):</label>
								</div>
								<div class="col-md-7">
									<input id='updateDepartment' name='department' class='form-control' type='text' value=''/>
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
									<label>Наименование организации от которого поступили:</label>
								</div>
								<div class="col-md-7">
									<input id='updateCounterpartie' name='counterpartie' class='form-control' type='text' value=''/>
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