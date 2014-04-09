<?php
	class Quizzes {
		private $pdo;
	
		public function __construct($user) {
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
						$quizz->results($_GET['answer'], $user);
					} else {
						$quizz->answer();
					}
				}
			} else {
				$this->addContent('none');
			}
			
		}
		
		public function addContent($data) {
			$pdo = $this->pdo;
			echo '	<a href="?action=solo">Solo</a>
					<a href="?action=waiting">Waiting</a>
					<a href="?action=invite">Invite</a>';
			if($data != 'none') {
				$query = $pdo->query('SELECT * FROM quizz');
				while($quizz = $query->fetch()) {
					$class = 'quizz-'.$quizz->id;
					$query2 = $pdo->prepare('SELECT COUNT(*) AS nb FROM success WHERE idUser = :id AND idQuizz = :idQuizz');
					$query2->bindValue(':id',$_SESSION['id']);
					$query2->bindValue(':idQuizz', $quizz->id);
					$query2->execute();
					$result = $query2->fetch();
					if($result->nb)
						$class .= ' done';
					$query2 = $pdo->prepare('SELECT date FROM fails WHERE idUser = :id AND idQuizz = :idQuizz ORDER BY id DESC LIMIT 1');
					$query2->bindValue(':id',$_SESSION['id']);
					$query2->bindValue(':idQuizz', $quizz->id);
					$query2->execute();
					$result = $query2->fetch();
					if($result) {
						$date = getdate();
						$day = $date['mday'];
						$month = $date['mon'];
						$year = $date['year'];
						if(count($month == 1)) {
							$month = '0'.$month;
						}
						if(count($day == 1)) {
							$day = '0'.$day;
						}
						$date = $year.'-'.$month.'-'.$day;
						if($date == $result->date) {
							$class .= ' fail';
						}
					}
					
					echo '<div><a class="'.$class.'" href="?answer='.$data.'&quizz='.$quizz->id.'">'.$quizz->name.'</a></div>';
				}
			}
		}
		
	}