<?php
	require_once 'src/includes/heading.php';
	$pageName .= ' - Challenges';
	$page = 'challenges';
	require_once 'src/class/Quizzes.class.php';
	include 'src/includes/head.php'; ?>
<body>
	<?php
		include 'src/includes/header.php'; 
		if(!$user->getId()) {
			$login_url = $facebook->getLoginUrl( array( 'scope' => 'publish_actions' ) );
			echo '<div class="shadow on"></div><div class="pop-up"><a href="'.$login_url.'"><div class="account"><p>Please Login</p></div></a></div>';
		}
	?>
	<div class="main">
		<section class="browse">
            <div class="title">
                <div></div>
                <h1>Challenges</h1>
            </div>
            <div class="solo_versus">
                <ul class="nav_challenges">
                       <a href="?action=solo"><li <?php if(!empty($_GET['action']) && $_GET['action'] == 'solo') echo 'class="selected"'; ?>><p>Solo</p></li></a><!--
                       --><a href="?action=waiting"><li <?php if(!empty($_GET['action']) && $_GET['action'] == 'waiting') echo 'class="selected"'; ?>><p>Versus a random opponent</p></li></a><!--
                       --><a href="?action=invite"><li <?php if(!empty($_GET['action']) && $_GET['action'] == 'invite') echo 'class="selected"'; ?>><p>Versus your friends</p></li></a>
                </ul>
            </div>   
            <div class="credit">
                <div class="credit-logo"></div>
                <div class="credit-score"> <?php echo $user->getEnergy(); ?> / 15</div>
            </div>  
        </section>
		<section class="challenges_result">
            <div class="challenges-lists">
                <?php 
                	if($user->getId())
                		$quizz = new Quizzes($_SESSION['id'], $user);
                ?>
            </div>
        </section>
	</div>
	<?php include 'src/includes/footer.php' ?>
	<?php include 'src/includes/foot-script.html' ?>
</body>
</html>