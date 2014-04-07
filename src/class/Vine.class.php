<?php
	class ComicsVineConnect {
		
		public function getData($end_point = '', $filters = array()) {
			require_once './src/includes/comicvine-api-connect.php';
			// Build URL
			$url = COMIC_VINE_URL . $end_point . '/?api_key=' . COMIC_VINE_KEY .'&filter=';
			foreach($filters as $_filter => $_value) {
				$url .= $_filter.':'.$_value.',';
			}

			$url .= '&format=json';
			
			// Curl call
			$curl = curl_init($url);
			curl_setopt($curl,CURLOPT_FOLLOWLOCATION,1);
			curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
			$content = curl_exec($curl);
			curl_close($curl);

			
			// Return curl result
			return json_decode($content);
		}
	}
