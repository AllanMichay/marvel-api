<?php
	require_once 'src/includes/heading.php';
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Quizz</title>
	<link rel="stylesheet" href="src/css/main.css">
</head>
<body>
	<?php
		$quizz = new Quizzes($user);
	?>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="src/js/jquery-1.11.0.min.js"><\/script>')</script>
	<script src="src/js/main.js"></script>
</body>
</html>