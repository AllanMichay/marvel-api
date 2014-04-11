<?php
	require_once 'src/includes/heading.php';
	session_destroy();
	header("Location: ".$user->getlogoutUrl());
?>