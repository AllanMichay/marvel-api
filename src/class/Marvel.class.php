<?php
	class MarvelConnect {
		
		public function getData($end_point = '', $params = array()) {
			require_once './src/includes/marvel-api-connect.php';
			
			// Set params
			$params['ts']     = time();
			$params['apikey'] = MARVEL_PUBLIC_KEY;
			$params['hash']   = md5($params['ts'] . MARVEL_PRIVATE_KEY . MARVEL_PUBLIC_KEY);

			// Build URL
			$url = MARVEL_URL . $end_point . '?' . http_build_query($params);

			// Curl call
			$curl = curl_init($url);
			curl_setopt($curl,CURLOPT_FOLLOWLOCATION,1);
			curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
			$content = curl_exec($curl);
			curl_close($curl);

			// Return curl result
			return json_decode($content);
		}
		
		public function extractFrom($content, $extract) {
			switch($extract) {
				case 'name':
					return $content->data->results[0]->name;
				case 'image':
					return ($content->data->results[0]->thumbnail->path).'.'.($content->data->results[0]->thumbnail->extension); 
			}
		}
	}
