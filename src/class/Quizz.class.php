<?php
	class Quizz {
		private $id;
		private $score; // Number of good answers.
		private $success; // Percentage of success.
		private $maxPoints;
		private $nbQuestions;
		private $difficulty;
		private $category;
		
		public function __construct($id) {
			$this->setId($id);
			$this->setScore(0);
			$this->setSuccess(0);
			$this->initMaxPoints();
			$this->initDifficulty();
			$this->initCategory();
			$this->countQuestions();
		}
		
		public function answer() {
			
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('SELECT id, text FROM questions WHERE idQuizz = :id ORDER BY RAND() LIMIT 10');
			$query->bindValue(':id',$this->getId());
			$query->execute();
			echo '<form action="#" method="post" class="quizz">';
			
			while($question = $query->fetch()) {
				$query2 = $pdo->prepare('SELECT id, text FROM answers WHERE idQuestion = :id ORDER BY RAND()');
				$query2->bindValue(':id',$question->id);
				$query2->execute();
				echo '<fieldset><legend>'.$question->text.'</legend>';
				while($answer = $query2->fetch()) {
					echo '<input type="radio" name="'.$question->id.'" value="'.$answer->id.'" id="'.$answer->id.'"/>
						<label for="'.$answer->id.'">'.$answer->text.'</label>';
				}
				echo '</fieldset>';
			}
			echo '<input type="submit"/></form>';
		}
		
		public function results($mode, $user) {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			
			if($this->isFirstTime($_SESSION['id'])) {
			
				$nbQuestions = $this->getNbQuestions();

				$query = $pdo->prepare('SELECT id FROM questions WHERE idQuizz = :id');
				$query->bindValue(':id',$this->id);
				$query->execute();

				while($question = $query->fetch()) {
					$query2 = $pdo->prepare('SELECT id FROM answers WHERE idQuestion = :id and good = 1');
					$query2->bindValue(':id',$question->id);
					$query2->execute();
					while($answer = $query2->fetch()) {
						if($_POST[$question->id] == $answer->id) {
							$this->incScore(1);
						}
					}
				}
				$percent = (int)(($this->getScore()/$nbQuestions)*100);
				$this->setSuccess($percent);
				
				$this->checkEndForm($_SESSION['id']);
				switch($mode) {
					case 'solo' :
						break;
					case 'waiting' :
						$this->checkWaiting($_SESSION['id']);
						break;
					case 'invite' :
						$this->sendInvitation($user, $_SESSION['id']);
						break;
					case 'invitation' :
						if(!empty($_GET['duals']))
							$this->answerDual($_SESSION['id'], $_GET['duals']);
						break;
				}
			}
		}
		
		public function getId() {
			return $this->id;
		}
		
		public function setId($id) {
			$this->id = $id;
		}
		
		public function getScore() {
			return $this->score;
		}
		
		public function setScore($score) {
			$this->score = $score;
		}
		
		public function incScore($score) {
			$this->score += $score;
		}
		
		public function getSuccess() {
			return $this->success;
		}
		
		public function setSuccess($success) {
			$this->success = $success;
		}
		
		public function getMaxPoints() {
			return $this->success;
		}
		
		public function setMaxPoints($points) {
			$this->maxPoints = $points;
		}
		
		public function getNbQuestions() {
			return $this->nbQuestions;
		}
		
		public function setNbQuestions($nbQuestions) {
			$this->nbQuestions = $nbQuestions;
		}
		
		public function getDifficulty() {
			return $this->difficulty;
		}
		
		public function setDifficulty($difficulty) {
			$this->difficulty = $difficulty;
		}
		
		public function getCategory() {
			return $this->category;
		}
		
		public function setCategory($category) {
			$this->category = $category;
		}
		
		private function initMaxPoints() {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('SELECT difficulty FROM quizz WHERE id = :id');
			$query->bindValue(':id',$this->getId());
			$query->execute();
			$result = $query->fetch();
			
			$this->setMaxPoints($result->difficulty*70);
		}
		
		private function initDifficulty() {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('SELECT difficulty FROM quizz WHERE id = :id');
			$query->bindValue(':id',$this->getId());
			$query->execute();
			$result = $query->fetch();
			
			$this->setDifficulty($result->difficulty);
		}
		
		private function initCategory() {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('SELECT category FROM quizz WHERE id = :id');
			$query->bindValue(':id',$this->getId());
			$query->execute();
			$result = $query->fetch();
			
			$this->setCategory($result->category);
		}
		
		private function isFirstTime($id) {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('SELECT id FROM success WHERE idQuizz = :idQuizz AND idUser = :id');
			$query->bindValue(':idQuizz',$this->getId());
			$query->bindValue(':id',$id);
			$query->execute();
			$result = $query->fetch();
			
			if($result) return false;
			return true;
		}
		
		private function insertInSuccess($id) {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('INSERT INTO success(idUser,idQuizz,date) VALUES (:id, :idQuizz, NOW())');
			$query->bindValue(':id',$id);
			$query->bindValue(':idQuizz',$this->getId());
			$query->execute();
		}
		
		private function insertInFail($id) {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('INSERT INTO fails(idUser,idQuizz,date) VALUES (:id, :idQuizz, NOW())');
			$query->bindValue(':id',$id);
			$query->bindValue(':idQuizz',$this->getId());
			$query->execute();
		}
		
		private function countQuestions() {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('SELECT COUNT(*) AS nb FROM questions WHERE idQuizz = :id');
			$query->bindValue(':id',$this->getId());
			$query->execute();
			$this->setNbQuestions((int)($query->fetch()->nb));
		}
		
		private function addUserPoints($points, $id) {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('UPDATE users SET points = points + :points WHERE id = :id');
			$query->bindValue(':points',$points);
			$query->bindValue(':id', $id);
			$query->execute();
		}
		
		private function checkWaiting($id) {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('SELECT id, idUser, score, difficulty, category FROM versusWaiting WHERE idQuizz = :id AND done = 0 ORDER BY id');
			$query->bindValue(':id',$this->getId());
			$query->execute();
			while($result = $query->fetch()) {
				if($id != $result->idUser) {
					$this->setWaitingDone($result->id, $id);
					return 1; // exit the function
				}
			}
			
			$this->insertInWaiting($id);
		}
		
		private function setWaitingDone($idWait, $id) {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('UPDATE versusWaiting SET done = 1, dateFinished = NOW() WHERE id = :id');
			$query->bindValue(':id',$idWait);
			$query->execute();
			
			$this->addVersusAfterWaiting($idWait, $id);
		}
		
		private function addVersusAfterWaiting($idWait, $id) {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('SELECT idUser, score FROM versusWaiting WHERE id = :id');
			$query->bindValue(':id', $idWait);
			$query->execute();
			$result = $query->fetch();
			
			if($result->score == $this->getSuccess()) {
				$winner = 0;
			} else if ($result->score > $this->getSuccess()) {
				$winner = $result->idUser;
			} else {
				$winner = $id;
			}
			
			$query = $pdo->prepare('INSERT INTO versus(idUser1, idUser2, idQuizz, scoreUser1, scoreUser2, winner, difficulty, category, date) 
												VALUES (:idUn, :idDeux, :idQuizz,:scoreUn, :scoreDeux, :winner, :difficulty, :category, NOW())');
			$query->bindValue(':idUn',$result->idUser);
			$query->bindValue(':idDeux',$id);
			$query->bindValue(':idQuizz',$this->getId());
			$query->bindValue(':scoreUn',$result->score);
			$query->bindValue(':scoreDeux', $this->getSuccess());
			$query->bindValue(':winner', $winner);
			$query->bindValue(':difficulty',$this->getDifficulty());
			$query->bindValue(':category',$this->getCategory());
			$query->execute();
			}
		
		private function insertInWaiting($id) {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('INSERT INTO versusWaiting(idQuizz, idUser, difficulty, category, score, done, dateAdded) 
									VALUES (:id, :idUser, :difficulty, :category, :score, 0, NOW())');
			$query->bindValue(':id',$this->getId());
			$query->bindValue(':idUser',$id);
			$query->bindValue(':difficulty',$this->getDifficulty());
			$query->bindValue(':category',$this->getCategory());
			$query->bindValue(':score',$this->getSuccess());
			$query->execute();
		}
		
		private function checkEndForm($id) {
			if($this->getSuccess() == 100) {
				$this->insertInSuccess($id);
			} else {
				$this->insertInFail($id);
			}

			$points = (int)($this->getMaxPoints()*$this->getSuccess())/100;
			$this->addUserPoints($points, $id);
		}
		
		private function sendInvitation($user, $id) {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('INSERT INTO duals(idUser, idQuizz, score, category, difficulty) 
			VALUES (:idUser, :idQuizz, :score, :category, :difficulty)');
			
			$query->bindValue(':idUser', $id, PDO::PARAM_INT);
			$query->bindValue(':idQuizz', $this->getId(), PDO::PARAM_INT);
			$query->bindValue(':score', $this->getSuccess(), PDO::PARAM_INT);
			$query->bindValue(':category', $this->getCategory(), PDO::PARAM_INT);
			$query->bindValue(':difficulty', $this->getDifficulty(), PDO::PARAM_INT);

			$result = $query->execute();
			
			if($result) {
				$user->postMessage('http://allanmichay.fr/preprod/marvel/quizz.php?answer=invitation&quizz='.$this->getId().'&duals='.$pdo->lastInsertId(), 'I have scored '.$this->getSuccess().' at this quizz. Challenge me if you dare !');
			}
		}
		
		private function answerDual($id, $idDual) {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
						
			$query = $pdo->prepare('SELECT idUser, score FROM duals WHERE id = :id');
			$query->bindValue(':id', $idDual);
			$query->execute();
			
			$result = $query->fetch();
			if($result->idUser != $id) {
				if($result->score == $this->getSuccess()) {
					$winner = 0;
				} else if ($result->score > $this->getSuccess()) {
					$winner = $result->idUser;
				} else {
					$winner = $id;
				}

				$query = $pdo->prepare('INSERT INTO versus(idUser1, idUser2, idQuizz, scoreUser1, scoreUser2, winner, difficulty, category, date) 
													VALUES (:idUn, :idDeux, :idQuizz,:scoreUn, :scoreDeux, :winner, :difficulty, :category, NOW())');
				$query->bindValue(':idUn',$result->idUser);
				$query->bindValue(':idDeux',$id);
				$query->bindValue(':idQuizz',$this->getId());
				$query->bindValue(':scoreUn',$result->score);
				$query->bindValue(':scoreDeux', $this->getSuccess());
				$query->bindValue(':winner', $winner);
				$query->bindValue(':difficulty',$this->getDifficulty());
				$query->bindValue(':category',$this->getCategory());
				$query->execute();
			}
		}
	}