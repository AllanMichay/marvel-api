<?php

	class Slider {
		
		public function __construct($page) {
			switch($page) {
				case 'characters' :
					$this->displaySliderHero();
					break;
				case 'character' :
					if(!empty($_GET['id']))
						$this->displayHero($_GET['id']);
					break;
				default :
					$this->displaySlider();
					break;
			}
		}
		
		/***************************************************************
		*	Display hero of the page in the slider
		*	parameters : id of the hero
		***************************************************************/
		private function displayHero($id) {
			require_once './src/class/Marvel.class.php';
			$marvel = new MarvelConnect();
			$content = $marvel->getData('characters/'.$id);
			$name = $marvel->extractFrom($content, 'name', 0);
			$image = $marvel->extractFrom($content, 'image', 0);
			if($marvel->extractFrom($content, 'description', 0)) {
				$bio = $marvel->extractFrom($content, 'description', 0);
				$pos = strpos($bio, '.');
				$bio = substr($bio, 0, $pos+1);
			} else {
				$bio = 'No biography.';
			}echo '<li>
				<img src="'.$image.'" alt="'.$name.'"/>
				<div class="slide-description">
					<h3>'.$name.'</h3>
					<p>'.$bio.'</p>
				</div>
			</li>';
		}
		
		/***************************************************************
		*	Display heroes of the week in the slider
		***************************************************************/
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
						<p>'.$hero->description.'</p>
						<a href="character.php?id='.$marvel->extractFrom($content, 'id').'" class="action"><span>Read More</span></a>
					</div>
				</li>';
			}
		}
		
		/***************************************************************
		*	Display the news in the slider
		***************************************************************/
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
						<p>'.$slide->description.'</p>
						<a href="'.$slide->link.'" class="action"><span>'.$slide->textLink.'</span></a>
					</div>
				</li>';
			}
		}
	}