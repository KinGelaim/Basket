<?php
	class People{
		public $id, $login;
		
		function People($id, $login) {
			$this->id = $id;
			$this->login = $login;
		}
	}

	$arr = array(
		new People(1, 'psych'),
		new People(2, 'ariya'),
		new People(3, 'delphin'),
		new People(4, 'painter')
	);
	header('Content-Type:application/json; charset=UTF-8');
	echo json_encode($arr);