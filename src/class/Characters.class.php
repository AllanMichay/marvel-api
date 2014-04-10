<?php
	class Characters {
		public function filter($name) {
			require_once './src/class/Marvel.class.php';
			$marvel = new MarvelConnect();
			$content = $marvel->getData('characters',array('nameStartsWith'=>$name,'orderBy' => 'name', 'limit' => '8'));
			
			$this->display($content);
		}
		
		public function displayCharacters() {
			require_once './src/class/Marvel.class.php';
			$marvel = new MarvelConnect();
			$content = $marvel->getData('characters',array('orderBy' => 'name', 'limit' => '8'));
			
			$date = $marvel->extractFrom($content, 'lastModified'); // will serve for the cache;
			
			$this->display($content);
		}
		
		private function display($content) {
			require_once './src/class/Marvel.class.php';
			$marvel = new MarvelConnect();
			$i = 0;
			$max = $content->data->total;
				if($max-$i >= 3) {
					echo '<div class="hex-row">';
					for($j = 0; $j < 3; $j++) {
						echo '<a href="character.php?id='.$marvel->extractFrom($content, 'id', $i).'"><div class="hexagon hexagon2"><div class="hexagon-in1"><div class="hexagon-in2"><img width="170" height="200" src="'.$marvel->extractFrom($content, 'image',$i).'"/><div class="hide">- '.$marvel->extractFrom($content, 'name',$i).' -</div></div></div></div></a>';
						$i++;
					}
					echo '</div>';
				}
				if($max-$i >= 2) {
					echo '<div class="hex-row even">';
					for($j = 0; $j < 2; $j++) {
						echo '<a href="character.php?id='.$marvel->extractFrom($content, 'id', $i).'"><div class="hexagon hexagon2"><div class="hexagon-in1"><div class="hexagon-in2"><img width="170" height="200" src="'.$marvel->extractFrom($content, 'image',$i).'"/><div class="hide">- '.$marvel->extractFrom($content, 'name',$i).' -</div></div></div></div></a>';
						$i++;
					}
					echo '</div>';
				}
				if($max-$i >= 3) {
					echo '<div class="hex-row">';
					for($j = 0; $j < 3; $j++) {
						echo '<a href="character.php?id='.$marvel->extractFrom($content, 'id', $i).'"><div class="hexagon hexagon2"><div class="hexagon-in1"><div class="hexagon-in2"><img width="170" height="200" src="'.$marvel->extractFrom($content, 'image',$i).'"/><div class="hide">- '.$marvel->extractFrom($content, 'name',$i).' -</div></div></div></div></a>';
						$i++;
					}
					echo '</div>';
				}
		}
	}