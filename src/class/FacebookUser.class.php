<?php
	class FacebookUser {
		private $id;
		private $profile;
		private $feed;
		private $loginUrl;
		private $logoutUrl;
		
		
		public function init() {
			require_once './src/includes/facebook-api-connect.php';
			// Get User ID
			$this->setId($facebook->getUser());

			if ($this->getId()) {
				try {
					// Proceed knowing you have a logged in user who's authenticated.
					$this->setProfile($facebook->api('/me'));
				} catch (FacebookApiException $e) {
					error_log($e);
					$this->nullify();
				}
			}
				$this->setLogoutUrl($facebook->getLogoutUrl());
				$this->setLoginUrl($facebook->getLoginUrl());
		}
		
		public function nullify() {
			$this->id = null;
			$this->profile = null;
			$this->feed = null;
			$this->loginUrl = null;
			$this->logoutUrl = null;
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
		
		public function postMessage($user, $link, $message) {
			global $facebook;
			if($user) {

				// We have a user ID, so probably a logged in user.
				// If not, we'll get an exception, which we handle below.
				try {
					$ret_obj = $facebook->api('/me/feed', 'POST',
											array(
											  'link' => $link,
											  'message' => $message
										 ));
					echo '<pre>Post ID: ' . $ret_obj['id'] . '</pre>';

					// Give the user a logout link 
					echo '<br /><a href="' . $facebook->getLogoutUrl() . '">logout</a>';
				} catch(FacebookApiException $e) {
					// If the user is logged out, you can have a 
					// user ID even though the access token is invalid.
					// In this case, we'll get an exception, so we'll
					// just ask the user to login again here.
					$login_url = $facebook->getLoginUrl( array(
								   'scope' => 'publish_actions'
								   )); 
					echo 'Please <a href="' . $login_url . '">login.</a>';
					error_log($e->getType());
					error_log($e->getMessage());
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
	}