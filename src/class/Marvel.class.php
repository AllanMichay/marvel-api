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
			//curl_setopt($curl,CURLOPT_FOLLOWLOCATION,1);
			curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
			$content = curl_exec($curl);
			curl_close($curl);

			// Return curl result
			return json_decode($content);
		}
		
		/***************************************************************
		*	Extract the data you want from the content
		*	parameters : content, what you want to extract (string) and the number of the cell
		*	in which you want to extract.
		*	return : string or array = what you want
		***************************************************************/
		public function extractFrom($content, $extract, $i = 0) {
			switch($extract) {
				case 'id':
					return $content->data->results[$i]->id;
				case 'name':
					return $content->data->results[$i]->name;
				case 'title':
					return $content->data->results[$i]->title;
				case 'image':
					return ($content->data->results[$i]->thumbnail->path).'.'.($content->data->results[0]->thumbnail->extension);
				case 'description':
					return $content->data->results[$i]->description;
				case 'comics':
					return $content->data->results[$i]->comics->items;
				case 'lastModified':
					return substr($content->data->results[0]->modified,0,10);
			}
		}
	}
