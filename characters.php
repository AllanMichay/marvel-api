<?php
	require_once 'src/includes/heading.php';
	require_once 'src/class/Characters.class.php';
	$pageName .= ' - Characters';
	include 'src/includes/head.php'; ?>
<body>
	<?php $page = 'characters'; include 'src/includes/header.php'; ?>
	<div class="main">
		<section class="browse">
            <div class="title">
                <div></div>
                <h1>Browse Characters</h1>
            </div>
            <div class="search">
                <div class="input-search"><form method="post" action="#"><input type="text" name="name" placeholder="SEARCH"></form></div>
            </div>
        </section>

		<section class="result-browsed">
		<?php
			$characters = new Characters();
			if(!empty($_POST))
				$characters->filter($_POST['name']);
			else
				$characters->displayCharacters();
		?>
		</section>
	</div>
	 
	 <?php include 'src/includes/footer.php' ?>
	 <?php include 'src/includes/foot-script.html' ?>
</body>
</html>