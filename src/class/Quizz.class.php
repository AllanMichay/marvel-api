<?php
	class Quizz {
		private $id;
		private $score; // Number of good answers.
		private $success; // Percentage of success.
		private $maxPoints;
		private $nbQuestions;
		private $difficulty;
		private $category;
		private $activity;
		
		public function __construct($id) {
			$this->setId($id);
			$this->setScore(0);
			$this->setSuccess(0);
			$this->initMaxPoints();
			$this->initDifficulty();
			$this->initCategory();
			$this->countQuestions();
			$this->selectActivity();
		}
		
		
		/***************************************************************
		*	Construct the form in which the user will answer.
		***************************************************************/
		public function answer() {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('SELECT id, text FROM questions WHERE idQuizz = :id ORDER BY RAND() LIMIT 10');
			$query->bindValue(':id',$this->getId());
			$query->execute();
			
			echo '<section class="quizz-wraper">';
			echo '<div class="quizz"><div class="left"></div><div class="right"></div><form action="#" method="post">';
			$i = 1;
			while($question = $query->fetch()) {
				$query2 = $pdo->prepare('SELECT id, text FROM answers WHERE idQuestion = :id ORDER BY RAND()');
				$query2->bindValue(':id',$question->id);
				$query2->execute();
				echo '<div id="question'.$i.'" class="individual-question">';
				echo '<div class="question"><p class="question-text">'.$question->text.'</p></div>';
				echo '<div class="answers">';
				while($answer = $query2->fetch()) {
					echo '<input type="radio" name="'.$question->id.'" value="'.$answer->id.'" id="'.$answer->id.'" required="required"/>
						<label for="'.$answer->id.'" class="choices"><p>'.$answer->text.'</p></label>';
				}
				echo '</div></div>';
				$i++;
			}
			echo '<div class="submit-quizz"><input type="submit" value="Give me that success !"/></div><div class="question_indicator">1/10</div></form></div>';
			echo '</section>';
		}
		
		/***************************************************************
		*	Calculates the results of the form.
		*	parameters : mode:string = game mode, user: user in db; $userFb = facebook user
		***************************************************************/
		public function results($mode, $user, $userFb) {
			require_once './src/class/Db.class.php';
			require_once './src/class/FacebookUser.class.php';
			
			$db = new Db();
			$pdo = $db->getPDO();
			
			if($this->isFirstTime($_SESSION['id']) && $userFb->getEnergy() > $this->getActivity()) {
			
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
						$this->checkWaiting($user);
						break;
					case 'invite' :
						$this->sendInvitation($userFb, $user);
						break;
					case 'invitation' :
						if(!empty($_GET['duals']))
							$this->answerDual($user, $_GET['duals']);
						break;
				}
			} else if($userFb->getEnergy() < $this->getActivity()) {
				echo '<div class="warning">Not enough energy</div>';
			} else {
				echo '<div class="warning">You already won this success !</div>';
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
			return $this->maxPoints;
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
		
		public function getActivity() {
			return $this->activity;
		}
		
		public function setActivity($activity) {
			$this->activity = $activity;
		}
		
		/***************************************************************
		*	Set the activity for quizz
		***************************************************************/
		private function selectActivity() {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('SELECT activity FROM quizz WHERE id = :id');
			$query->bindValue(':id',$this->getId());
			$query->execute();
			$result = $query->fetch();
			
			$this->setActivity($result->activity);
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
		
		/***************************************************************
		*	Init the difficulty of quizz
		***************************************************************/
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
		
		/***************************************************************
		*	Init the category of quizz
		***************************************************************/
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
		
		/***************************************************************
		*	Tells if user already have this success
		*	parameters : id of the user
		*	return : boolean
		***************************************************************/
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
		
		/***************************************************************
		*	Insert the quizz in the success of the user
		*	parameters : id of the user
		***************************************************************/
		private function insertInSuccess($id) {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('INSERT INTO success(idUser,idQuizz,date) VALUES (:id, :idQuizz, NOW())');
			$query->bindValue(':id',$id);
			$query->bindValue(':idQuizz',$this->getId());
			$query->execute();
		}
		
		/***************************************************************
		*	Insert the quizz in the fails of the user
		*	parameters : id of the user
		***************************************************************/
		private function insertInFail($id) {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('INSERT INTO fails(idUser,idQuizz,date) VALUES (:id, :idQuizz, NOW())');
			$query->bindValue(':id',$id);
			$query->bindValue(':idQuizz',$this->getId());
			$query->execute();
		}
		
		/***************************************************************
		*	Count the number of questionfor the quizz
		***************************************************************/
		private function countQuestions() {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('SELECT COUNT(*) AS nb FROM questions WHERE idQuizz = :id');
			$query->bindValue(':id',$this->getId());
			$query->execute();
			$this->setNbQuestions((int)($query->fetch()->nb));
		}
		
		/***************************************************************
		*	Add the points for the user
		*	parameters : points: int = number of points we must add , id of the user
		***************************************************************/
		private function addUserPoints($points, $id) {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('UPDATE users SET points = points + :points WHERE id = :id');
			$query->bindValue(':points',$points);
			$query->bindValue(':id', $id);
			$query->execute();
		}
		
		/***************************************************************
		*	Remove activity points of the user
		*	parameters :  id of the user
		***************************************************************/
		private function removeActivityPoint($id) {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('UPDATE users SET activity = activity - :points WHERE id = :id');
			$query->bindValue(':points',$this->getActivity());
			$query->bindValue(':id', $id);
			$query->execute();
		}
		
		/***************************************************************
		*	Check the waiting list
		*	parameters : id of the user
		***************************************************************/
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
		
		/***************************************************************
		*	Set the dual in the waiting list as done
		*	parameters : idWait : id of the dual in the waiting list / id of the user
		***************************************************************/
		private function setWaitingDone($idWait, $id) {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$pdo = $db->getPDO();
			
			$query = $pdo->prepare('UPDATE versusWaiting SET done = 1, dateFinished = NOW() WHERE id = :id');
			$query->bindValue(':id',$idWait);
			$query->execute();
			
			$this->addVersusAfterWaiting($idWait, $id);
		}
		
		/***************************************************************
		*	Add the waiting dual in the versus list when found somebody
		*	parameters : idWait : id of the dual in the waiting list / id of the user
		***************************************************************/
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
		
		/***************************************************************
		*	Insert in waiting list if no opponent
		*	parameters : id of the user
		***************************************************************/
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
		
		/***************************************************************
		*	Does everything that must be do at the end of the form =
		*	Add points, remove activity points and display results
		*	parameters : id of the user
		***************************************************************/
		private function checkEndForm($id) {
			$points = (int)($this->getMaxPoints()*$this->getSuccess())/100;
			$this->addUserPoints($points, $id);
			$this->removeActivityPoint($id);
			if($this->getSuccess() > 80) {
				$this->insertInSuccess($id);
				echo 	'<div class="wraper-title-result"><p class="title-result">Result</p></div>
						<div class="wraper-title-result"><p class="correct-result">'.$this->getSuccess().' % correct answers</p></div>
						<div class="wraper-title-result"><p class="points-earned">+ '.$points.'</p><div></div></div>
						<div class="answers">
							<a href=""><div class="won"><p>You won !</p></div></a>
							<a href=""><div class="challenge-friends"><p>Pro level !</p></div></a>
						</div>';
			} else {
				$this->insertInFail($id);
				echo 	'<div class="wraper-title-result"><p class="title-result">Result</p></div>
						<div class="wraper-title-result"><p class="correct-result">'.$this->getSuccess().' % correct answers</p></div>
						<div class="wraper-title-result"><p class="points-earned">+ '.$points.'</p><div></div></div>
						<div class="answers">
							<a href=""><div class="won"><p>Try again</p></div></a>
							<a href=""><div class="challenge-friends"><p>Tomorrow !</p></div></a>
						</div>';
			}

			
		}
		
		/***************************************************************
		*	Send an invitation in the news feed
		*	parameters : user : facebook user / id of the user
		***************************************************************/
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
		
		/***************************************************************
		*	Answer to a dual : select the winner and put in the versus table
		*	parameters : id : id of the person who answer the dual /
		*	idDual : id of the dual
		***************************************************************/
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