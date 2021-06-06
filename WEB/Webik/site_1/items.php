<?php
	$items = [
		[
			'img' => '1.jpg',
			'name' => 'Лопата 1',
			'price' => '7000',
			'stars' => 4
		],
		[
			'img' => '2.jpg',
			'name' => 'Лопата 2',
			'price' => '70000',
			'stars' => 7
		],
		[
			'img' => '3.jpg',
			'name' => 'Лопата 3',
			'price' => '1000',
			'stars' => 1
		],
		[
			'img' => '1.jpg',
			'name' => 'Лопата 4',
			'price' => '3000',
			'stars' => 2
		]
	];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Антошка товары</title>
		<meta charset='UTF-8'>
		
		<!-- CSS -->
		<link href='my.css' rel='stylesheet'>
		
		<!-- JS -->
		<script src='jquery-3.0.0.min.js'></script>
		<script src='my.js'></script>
	</head>
	<body>
		<?php include('header.php'); ?>
		<div id='container'>
			<?php
				for($i=0; $i<count($items); $i++){
					echo "<div class='card'>";
					echo "<img src='".$items[$i]['img']."' />";
					echo "<h2>" . $items[$i]['name'] . "</h3>";
					echo "<h3>".$items[$i]['price']." р.</h3>";
					echo "<h3>Оценка ".$items[$i]['stars']."/7</h3>";
					echo "</div>";
				}
			?>
		</div>
	</body>
</html>