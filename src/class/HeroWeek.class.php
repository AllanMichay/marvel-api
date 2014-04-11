<?php

	class HeroWeek {
		private $id;
		private $heroName;
		private $title;
		private $desc;
		private $image;
		private $LargeImage;
		
		public function init($id, $name, $title, $desc, $image, $largeImage) {
			$this->setId($id);
			$this->setHeroName($name);
			$this->setTitle($title);
			$this->setDesc($desc);
			$this->setImage($image);
			$this->setLargeImage($largeImage);
		}
		
		/***************************************************************
		*	Display the heroes of the week on the index page
		***************************************************************/
		public function displayHeroesIndex() {
			require_once './src/class/Db.class.php';
			require_once './src/class/Marvel.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			$marvel = new MarvelConnect();
			
			
			$query = $pdo->query('SELECT * FROM heroesWeek ORDER BY id DESC LIMIT 3');
			$result = $query->fetchAll();
			$n = count($result);
			echo 	'<div class="prez-news">';
			echo 		'<div class="hex-row">';
			for($j = 0; $j < $n; $j++) {
				$content = $marvel->getData('characters',array('name'=>$result[$j]->heroName,'orderBy' => 'name', 'limit' => '8'));
				$id = $marvel->extractFrom($content, 'id', 0);
				echo '<a href="character.php?id='.$id.'"><div class="hexagon hexagon2"><div class="hexagon-in1"><div class="hexagon-in2" id=hero-week-'.$result[$j]->id.'><div class="hide">- '.$result[$j]->heroName.' -</div></div></div></div></a>';
			}
			echo 		'</div>';
			echo 	'</div>';
		}
		
		public function getId() {
			return $this->id;
		}
		
		public function setId($id) {
			$this->id = $id;
		}
		
		public function getHeroName() {
			return $this->heroName;
		}
		
		public function setHeroName($name) {
			$this->heroName = $name;
		}
		
		public function getTitle() {
			return $this->title;
		}
		
		public function setTitle($title) {
			$this->title = $title;
		}
		
		public function getDesc() {
			return $this->desc;
		}
		
		public function setDesc($desc) {
			$this->desc = $desc;
		}
		
		public function getImage() {
			return $this->image;
		}
		
		public function setImage($image) {
			$this->image = $image;
		}
		
		public function getLargeImage() {
			return $this->largeImage;
		}
		
		public function setLargeImage($largeImage) {
			$this->largeImage = $largeImage;
		}
	}