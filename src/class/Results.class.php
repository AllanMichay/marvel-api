<?php
	class Results {
		private $id;
		private $points;
		private $fails;
		private $success;
		private $tests;
		
		public function __construct($id) {
			$this->retrieveId($id);
			$this->countPoints();
			$this->countFails();
			$this->countSuccess();
			$this->countTests();
		}
		
		public function getId() {
			return $this->id;
		}
		
		public function setId($id) {
			$this->id = $id;
		}
		
		public function getPoints() {
			return $this->points;
		}
		
		public function setPoints($points) {
			$this->points = $points;
		}
		
		public function getFails() {
			return $this->fails;
		}
		
		public function setFails($fails) {
			$this->fails = $fails;
		}
		
		public function getSuccess() {
			return $this->success;
		}
		
		public function setSuccess($success) {
			$this->success = $success;
		}
		
		public function getTests() {
			return $this->tests;
		}
		
		public function setTests($tests) {
			$this->tests = $tests;
		}
		
		/***************************************************************
		*	Retrieve id of the user
		*	parameters : id of the facebook user
		***************************************************************/
		private function retrieveId($id) {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('SELECT id FROM users WHERE idFb = :id');
			$query->bindValue(':id', $id);
			$query->execute();
			$result = $query->fetch();
			$this->setId($result->id);
		}
		
		/***************************************************************
		*	Count Points of the user
		***************************************************************/
		private function countPoints() {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('SELECT points FROM users WHERE id = :id');
			$query->bindValue(':id', $this->getId());
			$query->execute();
			$result = $query->fetch();
			$this->setPoints($result->points);
		}
		
		/***************************************************************
		*	Count Fails of the user
		***************************************************************/
		private function countFails() {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('SELECT COUNT(*) AS nb FROM fails WHERE idUser = :id');
			$query->bindValue(':id', $this->getId());
			$query->execute();
			$result = $query->fetch();
			if($result)
				$this->setFails($result->nb);
			else
				$this->setFails(0);
		}
		
		/***************************************************************
		*	Count Success of the user
		***************************************************************/
		private function countSuccess() {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('SELECT COUNT(*) AS nb FROM success WHERE idUser = :id');
			$query->bindValue(':id', $this->getId());
			$query->execute();
			$result = $query->fetch();
			if($result)
				$this->setSuccess($result->nb);
			else
				$this->setSuccess(0);
		}
		
		/***************************************************************
		*	Count the number of challenges faced of the user
		***************************************************************/
		private function countTests() {
			$nb = $this->getSuccess() + $this->getFails();
			$this->setTests($nb);
		}
	}