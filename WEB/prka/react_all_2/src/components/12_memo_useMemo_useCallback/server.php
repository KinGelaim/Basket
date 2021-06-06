<?php
	class Position {
		public $id, $item;
		
		function Position($id, $item) {
			$this->id = $id;
			$this->item = $item;
		}
	}
	
	class Item {
		public $company, $price, $name;
		
		public function Item($company, $price, $name) {
			$this->company = $company;
			$this->price = $price;
			$this->name = $name;
		}
	}
	
	$arr = array(
		new Position(1, new Item('Ikea', 700, 'Table')),
		new Position(2, new Item('Smak', 100, 'Bread')),
		new Position(3, new Item('Sitilink', 2000, 'Telephone')),
		new Position(4, new Item('Ikea', 9000, 'Door'))
	);
	header('Content-Type:application/json; charset=UTF-8');
	echo json_encode($arr);