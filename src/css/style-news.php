<?php
	header('content-type: text/css');
	require_once '../class/Db.class.php';
	$db = new Db();
	$pdo = $db->getPDO();
	
	$query = $pdo->query('SELECT id, image FROM news ORDER BY id');
	while($result = $query->fetch()) {
		echo '#news-'.$result->id.' {
			background : url("'.$result->image.'") center center no-repeat;
		}';
	}

	$query = $pdo->query('SELECT id, image FROM heroesWeek ORDER BY id');
	while($result = $query->fetch()) {
		echo '#hero-week-'.$result->id.' {
			background : url("'.$result->image.'") center center no-repeat;
		}';
	}