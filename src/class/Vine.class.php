<?php
	class ComicsVineConnect {
		
		private function getData($end_point = '', $filters = array(), $params = array()) {
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
		
		
		public function extractFrom($end_point = '', $filters = array(), $params = array(), $extracts = array(), $i = 0) {
			$content = $this->getData($end_point, $filters, $params);
			foreach($extracts as $extract) {
				switch($extract) {
					case 'name':
						return $content->results[0]->name;
					case 'image':
						return ($content->results[0]->image->super_url); 
				}
			}
		}
	}