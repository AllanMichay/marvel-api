<?php

	class Slider {
		
		public function __construct($page) {
			switch($page) {
				case 'characters' :
					$this->displaySliderHero();
					break;
				case 'character' :
					break;
				default :
					$this->displaySlider();
					break;
			}
		}
		
		private function displaySliderHero() {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->query('SELECT * FROM heroesWeek ORDER BY id DESC LIMIT 3');
			while($hero = $query->fetch()) {
				require_once './src/class/Marvel.class.php';
				$marvel = new MarvelConnect();
				$content = $marvel->getData('characters',array('name'=>$hero->heroName,'orderBy' => 'name', 'limit' => '1'));
				echo '<li>
					<img src="'.$hero->largeImage.'" alt="'.$hero->title.'"/>
					<div class="slide-description">
						<h3>'.$hero->title.'</h3>
						<h4>'.$hero->heroName.'</h4>
						<p>'.$hero->desc.'</p>
						<a href="character.php?id='.$marvel->extractFrom($content, 'id').'" class="action"><span>Read More</span></a>
					</div>
				</li>';
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