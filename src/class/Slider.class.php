<?php

	class Slider {
		
		public function __construct($page) {
			switch($page) {
				case 'characters' :
					break;
				case 'character' :
					break;
				default :
					$this->displaySlider();
					break;
			}
		}
		
		
		private function displaySlider() {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->query('SELECT * FROM slider ORDER BY id DESC LIMIT 3');
			while($slide = $query->fetch()) {
				echo '<li>
					<img src="'.$slide->image.'" alt="'.$slide->title.'"/>
					<div class="slide-description">
						<h3>'.$slide->title.'</h3>
						<h4>'.$slide->subTitle.'</h4>
						<p>'.$slide->desc.'</p>
						<a href="'.$slide->link.'" class="action"><span>'.$slide->textLink.'</span></a>
					</div>
				</li>';
			}
		}
	}