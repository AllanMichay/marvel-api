<?php

	class News {
		
		public function displayNewsAll() {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->query('SELECT * FROM news ORDER BY id DESC LIMIT 5');
			$result = $query->fetchAll();
			$i = 0;
			echo 	'<div class="prez-news" id="#all">';
				if(5-$i >= 3) {
					echo '<div class="hex-row">';
					for($j = 0; $j < 3; $j++) {
						echo '<div class="hexagon hexagon2"><div class="hexagon-in1"><div class="hexagon-in2" id=news-'.$result[$i]->id.'></div></div></div>';
						$i++;
					}
					echo '</div>';
				}
				if(5-$i >= 2) {
					echo '<div class="hex-row even">';
					for($j = 0; $j < 2; $j++) {
						echo '<div class="hexagon hexagon2"><div class="hexagon-in1"><div class="hexagon-in2" id=news-'.$result[$i]->id.'></div></div></div>';
						$i++;
					}
					echo '</div>';
				}
			echo 	'</div>';
		}
	}