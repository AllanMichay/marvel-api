<?php
	class Comic {
		private $id;
		private $image;
		private $name;
		
		public function __construct($id) {
			require_once './src/class/Marvel.class.php';
			$marvel = new MarvelConnect();
			$this->setId($id);
			
			$content = $marvel->getData('comics/'.$id);
			$this->setName($marvel->extractFrom($content, 'title', 0));	
			$this->setImage($marvel->extractFrom($content, 'image', 0));
		}
		
		public function getId() {
			return $this->id;
		}
		
		public function setId($id) {
			$this->id = $id;
		}
		
		public function getName() {
			return $this->name;
		}
		
		public function setName($name) {
			$this->name = $name;
		}
		
		public function getImage() {
			return $this->image;
		}
		
		public function setImage($image) {
			$this->image = $image;
		}
	}