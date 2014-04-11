<?php
	session_start();
	require_once 'src/includes/config.php';
	require_once 'src/class/Vine.class.php';
	require_once 'src/class/Marvel.class.php';
	require_once 'src/class/FacebookUser.class.php';
	require_once 'src/class/Slider.class.php';
	require_once 'src/class/News.class.php';
	require_once 'src/class/HeroWeek.class.php';
	require_once 'src/class/Results.class.php';
	$user = new FacebookUser();
	$user->init();
	$pageName = 'Marvel Challenges';
	if($user->getId())
			$results = new Results($user->getId());