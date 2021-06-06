<?php
	class Position {
		public $id, $item;
		
		function Position($id, $item) {
			$this->id = $id;
			$this->item = $item;
		}
	}
	
	class Price {
		public $name, $cost;
		
		function Price($name, $cost) {
			$this->name = $name;
			$this->cost = $cost;
		}
	}
	
	class Item {
		public $company, $price, $name;
		
		function Item() {
			
		}
		
		public function SetFullItem($company, $price, $name) {
			$this->company = $company;
			$this->price = new Price('Rub', $price);
			$this->name = $name;
		}
		
		public function SetItem($company, $name) {
			$this->company = $company;
			$this->name = $name;
		}
	}
	
	class MiniItem {
		public $company, $name;
		
		function MiniItem($company, $name) {
			$this->company = $company;
			$this->name = $name;
		}
	}

	$i1 = new Item();
	$i1->SetFullItem('Ikea', 700, 'Table');
	
	$i2 = new Item();
	$i2->SetFullItem('Smak', 100, 'Bread');
	
	$i3 = new Item();
	$i3->SetFullItem('Sitilink', 2000, 'Telephone');
	
	$i4 = new Item();
	$i4->SetFullItem('Ikea', 9000, 'Door');
	
	$i5 = new MiniItem('Ikea', 'Vase');
	
	$arr = array(
		new Position(1, $i1),
		new Position(2, $i2),
		new Position(3, $i3),
		new Position(4, $i4)
	);
	header('Content-Type:application/json; charset=UTF-8');
	echo json_encode($arr);