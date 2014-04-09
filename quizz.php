<?php
	require_once 'src/includes/heading.php';
	$pageName .= ' - Challenges';
	$page = 'challenges';
	include 'src/includes/head.php'; ?>
?>
<body>
	<?php include 'src/includes/header.php'; ?>
	<?php
		
		$quizz = new Quizzes($user);
	?>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="src/js/jquery-1.11.0.min.js"><\/script>')</script>
	<script src="src/js/main.js"></script>
</body>
</html>