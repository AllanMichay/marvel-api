<?php
	require_once 'src/includes/heading.php';
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
                <div class="input-search"><input type="search" placeholder="SEARCH"></div>

            </div>
        </section>

		<section class="result-browsed">
		</section>
	</div>
	 
	 <?php include 'src/includes/footer.php' ?>
	 <?php include 'src/includes/foot-script.html' ?>
</body>
</html>