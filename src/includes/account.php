<?php
<div class="shadow"></div>
	<section class="account-wraper">
		<div class="head-account">
			<div class="perso-picture">
				<div class="hexagon hexagon2"><div class="hexagon-in1"><div class="hexagon-in2"><img src="<?php echo $user->getPicture(); ?>" alt="Me" width="120"></div></div></div>
				<div class="name-wraper">
					<p><?php echo $user->getFName(); ?></p>
					<p><?php echo $user->getLName(); ?></p>
				</div>
			</div>
			<div class="trophy">
				<img src="src/images/trophy.png" alt="trophy">
				<p><?php if($user->getId()) echo $results->getSuccess(); ?></p>
			</div>
			<div class="challenge-points">
				<img src="src/images/challenge-points.png" alt="challenge-points">
				<p><?php if($user->getId()) echo $results->getPoints(); ?></p>
			</div>
			<div class="thunder-points">
				<img src="src/images/thunder.png" alt="thunder-points">
				<p><?php echo $user->getEnergy();?>/15</p>
			</div>
			<ul class="nav-account">
				<li id="foll"><div></div></li>
				<li id="chal"><div></div></li>
				<li id="succ"><div></div></li>
				<li id="info"><div>?</div></li>
			</ul>
		</div>
		<div class="notifs">
			<div class="wraper">
				<div class="account-bot-wraper notifs-wraper">
					<ul>
						<?php if($user->getId()) $user->displayFollow($_SESSION['id']); ?>
					</ul>
				</div>
				<div class="account-bot-wraper challenges-wraper">
					<canvas id="graph"></canvas>
					<div class="stats-challenges">
						<div class="faced"><p>Challenges faced</p><p><?php if($user->getId()) echo $results->getTests(); ?></p></div>
						<div class="faced"><p>Challenges succeeded</p><p><?php if($user->getId()) echo $results->getSuccess(); ?></p></div>
					</div>
				</div>
				<div class="account-bot-wraper success-wraper">
					<ul>
						<li><div id="hulk"></div><p>Hulk</p></li>
						<li><div id="thor"></div><p>Thor</p></li>
						<li><div id="stan"></div><p>Stan Lee</p></li>
						<li><div id="captain"></div><p>Captain America</p></li>
						<li><div id="spider"></div><p>Spider Man</p></li>
						<li><div id="nick"></div><p>Nick Fury</p></li>
						<li><div id="iron"></div><p>Iron Man</p></li>
						<li><div id="devil"></div><p>Dardevil</p></li>
						<li><div id="wolverine"></div><p>Wolverine</p></li>
						<li><div id="shield"></div><p>The shield</p></li>
					</ul>
				</div>
				<div class="account-bot-wraper infos-wraper">
					<div class="wraper-info">
						<div></div><p>This is your energy. You'll need it in order to make challenges. You do not have a lot of them so use them wisely... You will recover your energy each day.</p>
					</div>
					<div class="wraper-info">
						<div></div><p>This represents the challenges. Go in the challenges section and try to do some in order to unlock badges and win marvel point.</p>
					</div>
					<div class="wraper-info">
						<div></div><p>This is Marvel point. Every time you do challenges you will won some marvel point. Your ranking depends on the number of points you have.</p>
					</div>
				</div>
			</div>
		</div>	

	</section>