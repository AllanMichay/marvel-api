<?php

	class HeroWeek {
		
		public function displayHeroesIndex() {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->query('SELECT * FROM heroesWeek ORDER BY id DESC LIMIT 3');
			$result = $query->fetchAll();
			echo 	'<div class="prez-news">';
				echo '<div class="hex-row">';
				for($j = 0; $j < count($result); $j++) {
					echo '<div class="hexagon hexagon2"><div class="hexagon-in1"><div class="hexagon-in2" id=hero-week-'.$result[$j]->id.'></div></div></div>';
				}
				echo '</div>';
			echo 	'</div>';
		}
	}