<?php
	require_once 'src/includes/heading.php';
	require_once 'src/class/Character.class.php';
	if(!empty($_GET) && !empty($_GET['id'])) {
		$char = new Character($_GET['id']);
		$pageName .= ' - '.$char->getName();
	}
	if(!empty($_GET) && !empty($_GET['action'])) {
		if($_GET['action'] == 'share') {
			$user->postMessage('http://allanmichay.fr/preprod/marvel/character.php?id='.$_GET['id'], 'I really like this dude, check this out !');
		} else if($_GET['action'] == 'follow') {
			$user->addFollow($_SESSION['id'], $_GET['id']);
		}
	}
	include 'src/includes/head.php'; ?>
<body>
	<?php $page = 'character'; include 'src/includes/header.php'; ?>
	<?php include 'src/includes/account.php'; ?>
	<div class="main">
		<section class="character">
			<div class="title">
                <div></div>
                <h1><?php echo $char->getName(); ?></h1>
            </div>
            <div class="character-wraper">
            	<div class="character-informations">
					<div><h2>Gender : </h2><p> <?php echo $char->getGender() ?></p></div>
					<div><h2>Origin : </h2><p> <?php echo $char->getRace() ?></p></div>
					<div><h2>Name : </h2><p> <?php echo $char->getRealName() ?></p></div>
					<div><h2>Powers : </h2><p> <?php echo $char->getPowers() ?></p></div>
            	</div>
            	<div class="character-picture">
                    <div class="hexagon hexagon2"><div class="hexagon-in1"><div class="hexagon-in2"><img src="<?php echo $char->getImage(); ?>" alt="<?php echo $char->getName(); ?>" width="170" height="200"/></div></div></div>
                </div>
                <div class="character-social">
                    <a href="?action=follow&id=<?php echo $char->getId(); ?>">+ FOLLOW</a>
                    <a href="?action=share&id=<?php echo $char->getId(); ?>">SHARE</a>
                </div>
            </div>
            <div class="biography">
                <h3>Biography</h3>
                <p>
                	<?php echo $char->getBio(); ?>
                </p>
            </div>
		</section>
		
		<div class="prez-apparitions">
			
			<div class="title">
				<div></div>
				<h1>Appearances</h1>
			</div>
			<!--<div class="left"></div>-->
			<div class="hex-row">
				<?php $char->displayAppearances(7); ?>
			</div>
			<!--<div class="right"></div>-->
		</div>
	</div>
	 
	 <?php include 'src/includes/footer.php' ?>
	 <?php include 'src/includes/foot-script.html' ?>
</body>
</html>