<?php
	class Quizzes {
		private $pdo;
	
		public function __construct($user, $userFb) {
			require_once './src/class/Db.class.php';
			$db = new Db();
			$this->pdo = $db->getPDO();
			if(!empty($_GET) && !empty($_GET['action'])) {
				$this->addContent($_GET['action']);
			} else if (!empty($_GET) && !empty($_GET['answer'])){
				require_once './src/class/Quizz.class.php';
				if(!empty($_GET['quizz'])) {
					$quizz = new Quizz($_GET['quizz']);
					if(!empty($_POST)) {
						$quizz->results($_GET['answer'], $user, $userFb);
					} else {
						$quizz->answer();
					}
				}
			} else {
				$this->addContent('none');
			}
			
		}
		
		/***************************************************************
		*	Add the content of the page
		*	$data : mode chosen -> will interfer with the quizz it is possible to do
		***************************************************************/
		public function addContent($data) {
			require_once './src/class/Quizz.class.php';
			$pdo = $this->pdo;
			if($data != 'none') {
				$query = $pdo->query('SELECT * FROM quizz');
				while($quizzes = $query->fetch()) {
					$class = '';
					$text = 'Start';
					$quizz = new Quizz($quizzes->id);
					$query2 = $pdo->prepare('SELECT COUNT(*) AS nb FROM success WHERE idUser = :id AND idQuizz = :idQuizz');
					$query2->bindValue(':id',$_SESSION['id']);
					$query2->bindValue(':idQuizz', $quizz->getId());
					$query2->execute();
					$result = $query2->fetch();
					if($result->nb) {
						$class .= ' done';
						$text = 'Success';
					}
						
					$query2 = $pdo->prepare('SELECT date FROM fails WHERE idUser = :id AND idQuizz = :idQuizz ORDER BY id DESC LIMIT 1');
					$query2->bindValue(':id',$_SESSION['id']);
					$query2->bindValue(':idQuizz', $quizz->getId());
					$query2->execute();
					$result = $query2->fetch();
					if($result) {
						$temp = $this->checkDate($result->date);
						if(!empty($temp)) {
							$text = $temp;
							$class .= ' fail';
						}
					}
					$type = $this->CheckCategory($quizz->getCategory());
					
					echo '<div class="challenge-start">
						<div class="challenge-text">
							<div class="chal_type">'.$type.' Quizz</div>
							<div class="chal_name">'.$quizzes->name.'</div>
						</div>
						<div class="challenge-stats">
							<div class="m-chall"></div>
							<div class="m-chall-text"><p>'.$quizz->getActivity().'</p></div>
							<div class="thunder-chall"></div>
							<div class="thunder-chall-text"><p>'.$quizz->getMaxPoints().'</p></div>
						</div>
						<a class="'.$class.'" href="?answer='.$data.'&quizz='.$quizz->getId().'"><div class="challenge-btt'.$class.'">
							<p>'.$text.'<p>
						</div></a>
					</div>';
					//echo '<div><a class="'.$class.'" href="?answer='.$data.'&quizz='.$quizz->id.'">'.$quizz->name.'</a></div>';
				}
			} else {
				echo '<div class="warning">Select your game mode.</div>';
			}
		}
		
		
		/***************************************************************
		*	Check if the date is today
		*	parameters : dateResult = the date to check
		***************************************************************/
		private function checkDate($dateResult) {
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
			if($date == $dateResult) {
				return 'fail';
			}
		}
		
		/***************************************************************
		*	Check the category
		*	parameters : category = int of the category
		***************************************************************/
		private function checkCategory($category) {
			switch($category) {
				case 1 :
					return 'Comic';
				case 2 :
					return 'Character';
				case 3 :
					return 'Creator';
				default :
					return 'All';
			}
		}
		
	}