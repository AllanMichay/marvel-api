<?php
	class FacebookUser {
		private $id;
		private $profile;
		private $feed;
		private $loginUrl;
		private $logoutUrl;
		private $facebook;
		
		public function init() {
			require_once './src/includes/facebook-api-connect.php';
			// Get User ID
			$this->setFacebook($facebook);
			$this->setId($facebook->getUser());
			$this->setLogoutUrl($facebook->getLogoutUrl());
			$this->setLoginUrl($facebook->getLoginUrl( array( 'scope' => 'publish_actions' ) ));
			if ($this->getId()) {
				try {
					// Proceed knowing you have a logged in user who's authenticated.
					$this->setProfile($facebook->api('/me'));
					$this->setFeed($facebook->api('/me/feed'));
					$this->userConnection();
				} catch (FacebookApiException $e) {
					error_log($e);
					$this->nullify();
				}
			} 
				
		}
		
		public function nullify() {
			$this->id = null;
			$this->profile = null;
			$this->feed = null;
			$this->loginUrl = null;
			$this->logoutUrl = null;
		}
		
		public function getFacebook() {
			return $this->facebook;
		}
		
		public function setFacebook($facebook) {
			$this->facebook = $facebook;
		}
		
		public function getId() {
			return $this->id;
		}
		
		public function setID($id) {
			$this->id = $id;
		}
		
		public function getProfile() {
			return $this->profile;
		}
		
		public function setProfile($profile) {
			$this->profile = $profile;
		}
		
		public function getFeed() {
			return $this->feed;
		}
		
		public function setFeed($feed) {
			$this->feed = $feed;
		}
		
		public function getLoginUrl() {
			return $this->loginUrl;
		}
		
		public function setLoginUrl($loginUrl) {
			$this->loginUrl = $loginUrl;
		}
		
		public function getLogoutUrl() {
			return $this->logoutUrl;
		}
		
		public function setLogoutUrl($logoutUrl) {
			$this->logoutUrl = $logoutUrl;
		}
		
		public function postMessage($link, $message) {
			$facebook = $this->getFacebook();		
			
			if($this->getId()) {

				// We have a user ID, so probably a logged in user.
				// If not, we'll get an exception, which we handle below.
				try {
					$ret_obj = $facebook->api('/me/feed', 'POST',
											array(
											  'link' => $link,
											  'message' => $message
										 ));
				echo 'Message sent !';
				} catch(FacebookApiException $e) {
					// If the user is logged out, you can have a 
					// user ID even though the access token is invalid.
					// In this case, we'll get an exception, so we'll
					// just ask the user to login again here.
					$login_url = $facebook->getLoginUrl( array(
								   'scope' => 'publish_actions'
								   )); 
					echo 'Please <a href="' . $login_url . '">login.</a>';
					print_r($e->getType());
					print_r($e->getMessage());
				}   
			} else {

				// No user, so print a link for the user to login
				// To post to a user's wall, we need publish_actions permission
				// We'll use the current URL as the redirect_uri, so we don't
				// need to specify it here.
				$login_url = $facebook->getLoginUrl( array( 'scope' => 'publish_actions' ) );
				echo 'Please <a href="' . $login_url . '">login.</a>';

			} 
		}
		
		private function userConnection() {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			if(empty($_SESSION['id'])) {
				$query = $pdo->prepare('SELECT id FROM users WHERE id_fb = :id');
				$query->bindValue(':id',$this->getId());
				$query->execute();
				$result = $query->fetch();
				if($result) {
					$_SESSION['id'] = $result->id;
				} else {
					$this->userCreation();
				}
			}
		}
		
		private function userCreation() {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('INSERT INTO users(idFb,activity,points) VALUES (:id, 50, 0)');
			$query->bindValue(':id',$this->getId());
			$query->execute();
			
			$query = $pdo->prepare('SELECT id FROM users WHERE idFb = :id');
			$query->bindValue(':id',$this->getId());
			$query->execute();
			$result = $query->fetch();
			$_SESSION['id'] = $result->id;
		}
	}