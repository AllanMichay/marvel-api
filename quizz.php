<?php
	require_once 'src/includes/heading.php';
	$pageName .= ' - Challenges';
	$page = 'challenges';
	require_once 'src/class/Quizzes.class.php';
	include 'src/includes/head.php'; ?>
<body>
	<?php include 'src/includes/header.php'; 
		$quizz = new Quizzes($user);
	?>
	<?php include 'src/includes/foot-script.html' ?>
</body>
</html>