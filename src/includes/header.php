<header>
	<div class="slider">
		<a href="#" class="logo"></a>
		<?php if($user->getId()): ?>
		<a href="#"><div class="account"><p>My Account</p></div></a>
		<a href="<?php echo $user->getlogoutUrl(); ?>"><div class="logout"><p>Logout</p></div></a>
		<?php else: ?>
		<a href="<?php echo $user->getloginUrl(); ?>"><div class="account"><p>Login</p></div></a>
		<?php endif ?>
		<?php if($page == 'character'): ?>
		<div class="left"></div>
		<div class="right"></div>
		<?php endif; ?>
		<ul class="rslides">
			<?php 
				$slider = new Slider($page);
			?>
		</ul>
	</div>
	<ul class="nav">
		   <a href="index.php"><li <?php if($page == 'index') echo 'class="current"'?>><div></div></li></a><!--
		   --><a href="#"><li><p>Comics</p></li></a><!--span à mettre
		   --><a href="characters.php"><li <?php if($page == 'characters' || $page == 'character') echo 'class="current"'?>><p>Characters</p></li></a><!--
		   --><a href="#"><li><p>Creators</p></li></a><!--
		   --><a href="#"><li><p>Movies</p></li></a><!--
		   --><a href="quizz.php"><li <?php if($page == 'challenges') echo 'class="current"'?>><p>Challenges</p></li></a><!--
		   --><a href="#"><li><div></div></li></a>
	</ul>   
</header>