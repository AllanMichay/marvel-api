<?php

	class Character {
		private $id;
		private $idVine;
		private $name;
		private $gender;
		private $race;
		private $powers;
		private $realName;
		private $bio;
		private $image;
		private $comics;
		
		public function __construct($id) {
			require_once './src/class/Marvel.class.php';
			$marvel = new MarvelConnect();
			require_once './src/class/Vine.class.php';
			$vine = new ComicsVineConnect();
			
			$this->setId($id);
			$content = $marvel->getData('characters/'.$id);
			$this->setName($marvel->extractFrom($content, 'name', 0));
			$this->setImage($marvel->extractFrom($content, 'image', 0));
			$this->setComics($marvel->extractFrom($content, 'comics', 0));
			$this->setRealName('Unkown');
			$this->setGender('Unknown');
			$this->setRace('Unknown');
			$this->setPowers('Unknown');
			if($marvel->extractFrom($content, 'description', 0)) {
				$this->setBio($marvel->extractFrom($content, 'description', 0));
			} else {
				$this->setBio('No biography');
			}
			
			$this->calculateVineId();
			// if we manage to have his id
			if($this->getIdVine()) {
				$this->retrieveFromVine();
			}
			
		}
		
		private function retrieveFromVine() {
			require_once './src/class/Marvel.class.php';
			$marvel = new MarvelConnect();
			require_once './src/class/Vine.class.php';
			$vine = new ComicsVineConnect();
			
			// Trying with an other method --> Works better than the first one
			$contentVine = $vine->getData('characters', array('id'=>$this->getIdVine()));
			if($contentVine) {
				$realName = $vine->ExtractFrom($contentVine,'real_nameV2');
				if($realName)
					$this->setRealName($realName);

				$gender = $vine->ExtractFrom($contentVine,'genderV2');
				if($gender == 1) {
					$this->setGender('Male');
				} else if($gender == 2) {
					$this->setGender('Female');
				} 

				$race = $vine->ExtractFrom($contentVine,'raceV2');
				if($race)
					$this->setRace($race);
			} else {
				$contentVine = $vine->getData('character/4005-'.$this->getIdVine());
				// Seems that the getData above does not work with every super hero
				if($contentVine) {
					$realName = $vine->ExtractFrom($contentVine,'real_name');
					if($realName)
						$this->setRealName($realName);

					$gender = $vine->ExtractFrom($contentVine,'gender');
					if($gender == 1) {
						$this->setGender('Male');
					} else if($gender == 2) {
						$this->setGender('Female');
					} 

					$race = $vine->ExtractFrom($contentVine,'race');
					if($race)
						$this->setRace($race);

					$powers = $vine->ExtractFrom($contentVine,'powers');
					if($powers)
						$this->setPowers(implode(", ", $powers));
				}
			}	
			 
		}
		
		/***************************************************************
		*	Calculate the vine Id if possible
		***************************************************************/
		private function calculateVineId() {
			require_once './src/class/Vine.class.php';
			$vine = new ComicsVineConnect();

			$good = $vine->chooseRelevant('characters', array('name'=>$this->getName()), array('limit'=>'3'), $this->getName());
			if($good != 'error') {
				$this->setIdVine($vine->ExtractFrom($good[0], 'id', $good[1]));
			}
			else
				$this->setIdVine(0);
		}
		
		public function getId() {
			return $this->id;
		}
		
		public function setId($id) {
			$this->id = $id;
		}
		
		public function getIdVine() {
			return $this->idVine;
		}
		
		public function setIdVine($id) {
			$this->idVine = $id;
		}
		
		public function getName() {
			return $this->name;
		}
		
		public function setName($name) {
			$this->name = $name;
		}
		
		public function getGender() {
			return $this->gender;
		}
		
		public function setGender($gender) {
			$this->gender = $gender;
		}
		
		public function getRace() {
			return $this->race;
		}
		
		public function setRace($race) {
			$this->race = $race;
		}
		
		public function getPowers() {
			return $this->powers;
		}
		
		public function setPowers($powers) {
			$this->powers = $powers;
		}
		
		public function getRealName() {
			return $this->realName;
		}
		
		public function setRealName($realName) {
			$this->realName = $realName;
		}
		
		public function getBio() {
			return $this->bio;
		}
		
		public function setBio($bio) {
			$this->bio = $bio;
		}
		
		public function getImage() {
			return $this->image;
		}
		
		public function setImage($image) {
			$this->image = $image;
		}
		
		public function getComics() {
			return $this->comics;
		}
		
		public function setComics($comics) {
			$this->comics = $comics;
		}
		
		
		/***************************************************************
		*	Display appearances of a character
		*	Parameters : $limit = number of appearances we want to see
		***************************************************************/
		public function displayAppearances($limit) {
			require_once './src/class/Marvel.class.php';
			require_once './src/class/Comic.class.php';
			$marvel = new MarvelConnect();
			
			$n = count($this->getComics());
			if ($n < $limit) $limit = $n;
			for($i = 0; $i < $limit; $i++) {
				$comics = $this->getComics();
				$url = $comics[$i]->resourceURI;
				$id = str_replace("http://gateway.marvel.com/v1/public/comics/", "", $url);
				$comic = new Comic($id);
				
				echo '<a href="comic.php?id='.$comic->getId().'"><div class="hexagon hexagon2"><div class="hexagon-in1"><div class="hexagon-in2"><img class="img-apparition" width="70" height="100" src="'.$comic->getImage().'" alt="'.$comic->getName().'" title="'.$comic->getName().'"/></div></div></div></a>';
			}
		}
	}