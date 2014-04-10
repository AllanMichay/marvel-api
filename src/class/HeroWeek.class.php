<?php

	class HeroWeek {
		private $id;
		private $heroName;
		private $title;
		private $desc;
		private $image;
		private $LargeImage;
		
		public function __construct($id, $name, $title, $desc, $image, $largeImage) {
			$this->setId($id);
			$this->setHeroName($name);
			$this->setTitle($title);
			$this->setDesc($desc);
			$this->setImage($image);
			$this->setLargeImage($largeImage);
		}
		
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