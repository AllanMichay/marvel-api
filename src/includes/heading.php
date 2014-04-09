<?php
	session_start();
	require_once 'src/includes/config.php';
	require_once 'src/class/Vine.class.php';
	require_once 'src/class/Marvel.class.php';
	require_once 'src/class/FacebookUser.class.php';
	require_once 'src/class/Quizzes.class.php';
	$user = new FacebookUser();
	$user->init();