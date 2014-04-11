<?php
	class FacebookUser {
		private $id;
		private $profile;
		private $feed;
		private $loginUrl;
		private $logoutUrl;
		private $facebook;
		private $energy;
		private $fName;
		private $lName;
		private $picture;
		
		
		/***************************************************************
		*	Initialize the facebook user
		***************************************************************/
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
					$this->rechargeEnergy();
					$this->countEnergy();
					$temp = $this->getProfile();
					$this->setFName($temp['first_name']);
					$this->setLName($temp['last_name']);
					$this->setPicture('http://graph.facebook.com/'.$this->getId().'/picture?type=large');
					$this->userConnection();
				} catch (FacebookApiException $e) {
					error_log($e);
					$this->nullify();
				}
			} 
				
		}
		
		/***************************************************************
		*	Nullify the facebook user
		***************************************************************/
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
		
		public function getFName() {
			return $this->fName;
		}
		
		public function setFName($name) {
			$this->fName = $name;
		}
		
		public function getLName() {
			return $this->lName;
		}
		
		public function setLName($name) {
			$this->lName = $name;
		}
		
		public function getPicture() {
			return $this->picture;
		}
		
		public function setPicture($picture) {
			$this->picture = $picture;
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
		
		public function getEnergy() {
			return $this->energy;
		}
		
		public function setEnergy($energy) {
			$this->energy = $energy;
		}
		
		
		/***************************************************************
		*	Post a message from the facebook user
		*	Parameters : a link and the message
		***************************************************************/
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
				echo '<div class="shadow on"></div><div class="pop-up"><div class="warning">Message sent !</div></div>';
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
				echo '<div class="shadow on"></div><div class="pop-up"><a href="'.$login_url.'"><div class="account"><p>Please Login</p></div></a></div>';

			} 
		}
		
		
		/***************************************************************
		*	Create the connection between facebook and our database
		***************************************************************/
		private function userConnection() {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			if(empty($_SESSION['id'])) {
				$query = $pdo->prepare('SELECT id FROM users WHERE idFb = :id');
				$query->bindValue(':id',$this->getId());
				$query->execute();
				$result = $query->fetch();
				if($result) {
					$_SESSION['id'] = $result->id;
					$query = $pdo->prepare('UPDATE users SET lastConnection = NOW() WHERE idFb = :id');
					$query->bindValue(':id',$this->getId());
					$query->execute();
					
				} else {
					$this->userCreation();
				}
			}
		}
		
		
		/***************************************************************
		*	Create a user
		***************************************************************/
		private function userCreation() {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('INSERT INTO users(idFb,activity,points, lastConnection) VALUES (:id, 15, 0, NOW())');
			$query->bindValue(':id',$this->getId());
			$query->execute();
			
			$query = $pdo->prepare('SELECT id FROM users WHERE idFb = :id');
			$query->bindValue(':id',$this->getId());
			$query->execute();
			$result = $query->fetch();
			$_SESSION['id'] = $result->id;
			$this->countEnergy();
		}
		
		/***************************************************************
		*	Count the Energy of the user
		***************************************************************/
		public function countEnergy() {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('SELECT activity FROM users WHERE idFb = :id');
			$query->bindValue(':id',$this->getId());
			$query->execute();
			$result = $query->fetch();
			
			$this->setEnergy($result->activity);
		}
		
		
		/***************************************************************
		*	Recharge the energy everyday.
		***************************************************************/
		public function rechargeEnergy() {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('SELECT lastConnection FROM users WHERE idFb = :id');
			$query->bindValue(':id',$this->getId());
			$query->execute();
			$result = $query->fetch();
			
			$date = getdate();
			$day = $date['mday'];
			$month = $date['mon'];
			$year = $date['year'];
			if(strlen(strval($month)) == 1) {
				$month = '0'.$month;
			}
			if(strlen(strval($day)) == 1) {
				$day = '0'.$day;
			}
			$date = $year.'-'.$month.'-'.$day;
			if($date != $result->lastConnection) {
				$query = $pdo->prepare('UPDATE users SET activity = 15 WHERE idFb = :id');
				$query->bindValue(':id',$this->getId());
				$query->execute();
			}
		}
	}