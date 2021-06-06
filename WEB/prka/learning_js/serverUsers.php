<?php
	class User
	{
		public $id;
		public $name;
		public $username;
		public $address;
		public $phone;
		
		function User($id, $name, $username, $address, $phone)
		{
			$this->id = $id;
			$this->name = $name;
			$this->username = $username;
			$this->address = $address;
			$this->phone = $phone;
		}
	}
	
	class Address
	{
		public $street;
		public $city;
		
		function Address($street, $city)
		{
			$this->street = $street;
			$this->city = $city;
		}
	}

	$arr = array(
		new User(1, "Mihail", "Kin", new Address("Gagarina", "Nijniy Tagil"), "43-43-43"),
		new User(2, "Elena", "Laim", new Address("Lenina", "Nijniy Tagil"), "8-800-555-35-35"),
		new User(3, "Denis", "Dark", new Address(null, "Москва"), "84659"),
		new User(3, "Artem", "Technar", new Address(null, "Nijniy Tagil"), null)
	);
	header('Content-Type:application/json; charset=UTF-8');
	echo json_encode($arr);