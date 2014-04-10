<?php
	require_once 'src/includes/heading.php';
	include 'src/includes/head.php'; ?>
<body>
	<?php $page = 'index'; include 'src/includes/header.php'; ?>
	<div class="main">
	
		<section class="news">
			<div class="title">
				<div></div>
				<h1>News</h1>
			</div>
			<ul class="nav">
			   <a href="#"><li class="current"><p>All</p></li></a><!--
			   --><a href="#"><li><p>Comics</p></li></a><!--
			   --><a href="#"><li><p>Characters</p></li></a><!--
			   --><a href="#"><li><p>Movies</p></li></a><!--
			   --><a href="#"><li><p>Events</p></li></a>
			</ul>
			
			<?php
				$news = new News();
				$news->displayNewsAll();
			?>

		</section>

		<section class="hero-of-the-week">
			<div class="title">
				<div></div>
				<h1>Hero Of The Week</h1>
			</div>

			<?php
				$news = new HeroWeek();
				$news->displayHeroesIndex();
			?>
		</section>
	 </div>
	 
	 <?php include 'src/includes/footer.php' ?>
	 <?php include 'src/includes/foot-script.html' ?>
</body>
</html>