<?php
	require_once 'src/class/Vine.class.php';
	require_once 'src/class/Marvel.class.php';
	require_once 'src/class/FacebookUser.class.php';
	
	$user = new FacebookUser();
	$user->init();
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>My Marvel</title>
	<link rel="stylesheet" href="src/css/reset.css">
	<link rel="stylesheet" href="src/css/main.css">
</head>
<body>
	
	<?php if ($user->getId()): ?>
		<a href="<?php echo $user->getlogoutUrl(); ?>">Logout</a>
	<?php else: ?>
		<a href="<?php echo $user->getloginUrl(); ?>">Login with Facebook</a>
	<?php endif ?>
	
	<?php 
		$marvel = new MarvelConnect();
		$content 	= $marvel->getData('characters',array('name'=>'Hulk','limit'=>40));
		$name  		= $marvel->extractFrom($content, 'name');
		$image 		= $marvel->extractFrom($content, 'image');
	?>
	
	<img src="<?php echo $image ?>" alt="<?php echo $name ?>">
	<hr>
	<?php
		$vine 		= new ComicsVineConnect();
		$content2 	= $vine->getData('characters',array('name'=>'Hulk'));
		var_dump($content2);
	?>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="src/js/jquery-1.10.2.min.js"><\/script>')</script>
	<script src="src/js/main.js"></script>
</body>
</html>