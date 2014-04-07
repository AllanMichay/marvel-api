<?php
	require_once 'src/class/FacebookUser.class.php';
	
	$user = new FacebookUser();
	$user->init();
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
			$user->postMessage('www.example.com','Message de test');
	?>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="src/js/jquery-1.10.2.min.js"><\/script>')</script>
	<script src="src/js/main.js"></script>
</body>
</html>