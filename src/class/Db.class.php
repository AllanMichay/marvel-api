<?php
	class Db {
		private $dbName;
		private $dbHost;
		private $dbUser;
		private $dbPass;
		private $pdo;
		
		public function __construct() {
			$this->dbName = 'allanmic_marvel_api';
			$this->dbHost = 'localhost';
			$this->dbUser = 'allanmic_user';
			$this->dbPass = '6gkw+LHJXI&$';
			$this->connect();
		}
		
		public function connect() {
			try
			{
				$pdo = new PDO('mysql:dbname='.$this->getDbName().';host='.$this->getDbHost(),$this->getDbUser(),$this->getDbPass());
				$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
				$this->setPDO($pdo);
			}
			catch (PDOException $e)
			{
				die($e->getMessage());
			}
		}
		
		public function getPDO() {
			return $this->pdo;
		}
		
		public function getDbName() {
			return $this->dbName;
		}
		
		public function getDbHost() {
			return $this->dbHost;
		}
		
		public function getDbUser() {
			return $this->dbUser;
		}
		
		public function getDbPass() {
			return $this->dbPass;
		}
		
		public function setPDO($pdo) {
			$this->pdo = $pdo;
		}
	}