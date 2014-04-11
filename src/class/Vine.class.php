<?php
	class ComicsVineConnect {
		
		public function getData($end_point = '', $filters = array(), $params = array()) {
			require_once './src/includes/comicvine-api-connect.php';
			// Build URL
			$url = COMIC_VINE_URL . $end_point . '/?api_key=' . COMIC_VINE_KEY .'&filter=';
			foreach($filters as $_filter => $_value) {
				$url .= $_filter.':'.$_value;
				if(end($filters) !== $_value){
					$url .= ','; 
				}
			}
			$url .= '&'.http_build_query($params);
			$url .= '&format=json';
			
			// Curl call
			$curl = curl_init($url);
			//curl_setopt($curl,CURLOPT_FOLLOWLOCATION,1);
			curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
			$content = curl_exec($curl);
			curl_close($curl);
			// Return curl result
			return json_decode($content);
		}
		
		/***************************************************************
		*	Gives back the number of real character you're looking for :
		*	If looking for hulk, will not send She Hulk.
		*	Parameters : Parameters for getData + a string to compare
		*	Return : array with content to extract from and the number of the cell.
		***************************************************************/
		public function chooseRelevant($end_point = '', $filters = array(), $params = array(), $toCompare) {
			$content = $this->getData($end_point, $filters, $params);
			if($params['limit'])
				$n = $params['limit'];
			else
				$n = 0;
			$good = 0;
			for($i = 0; $i < $n; $i++) {
				if($content) {
					$name = $content->results[$i]->name;
					if(strcasecmp($name, $toCompare) == 0) {
						$good = $i;
						break;
					}
				} else {
					return 'error';
				}
			}
			return array($content,$good);
		}
		
		/***************************************************************
		*	Extract the data from the content
		*	Parameters : content + what to extract + number of the cell
		*	Return : string or array - what you asked for
		***************************************************************/
		public function extractFrom($content, $extract, $i = 0) {
			switch($extract) {
				case 'id':
					return $content->results[$i]->id;
				case 'name':
					return $content->results[$i]->name;
				case 'image':
					return ($content->results[$i]->image->super_url); 
				case 'real_nameV2':
					return $content->results[$i]->real_name;
				case 'genderV2':
					return $content->results[$i]->gender;
				case 'raceV2':
					return $content->results[$i]->origin->name;
				case 'real_name':
					return $content->results->real_name;
				case 'gender':
					return $content->results->gender;
				case 'race':
					return $content->results->origin->name;
				case 'powers':
					return array($content->results->powers[0]->name,$content->results->powers[1]->name);
			}
		}
		
		/***************************************************************
		*	Extract the data from the content. Does generate the content before.
		*	Parameters : Parameters for getData + what to extract + number of the cell
		*	Return : string or array - what you asked for
		***************************************************************/
		public function DirectExtractFrom($end_point = '', $filters = array(), $params = array(), $extract, $i = 0) {
			$content = $this->getData($end_point, $filters, $params);
			switch($extract) {
				case 'id':
					return $content->results[0]->name;
				case 'name':
					return $content->results[0]->name;
				case 'image':
					return ($content->results[0]->image->super_url); 
				case 'real_nameV2':
					return $content->results[$i]->real_name;
				case 'genderV2':
					return $content->results[$i]->gender;
				case 'raceV2':
					return $content->results[$i]->origin->name;
				case 'real_name':
					return $content->results->real_name;
				case 'gender':
					return $content->results->gender;
				case 'race':
					return $content->results->origin->name;
				case 'powers':
					return array($content->results->powers[0]->name,$content->results->powers[1]->name);
			}
		}
	}