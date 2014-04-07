<?php
	require_once 'src/includes/facebook-api-connect.php';
	require_once 'src/includes/functions.php';
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" href="src/css/main.css">
</head>
<body>
	<?php
		postMessage($user, 'www.example.com', 'Mon message de test de fonction');
	?>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="src/js/jquery-1.10.2.min.js"><\/script>')</script>
	<script src="src/js/main.js"></script>
</body>
</html>