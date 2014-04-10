$(document).ready(function(){

//Header's slider


	//Toggle account

	isToggle = false;
	isInside = false;


	$('.account, .nav li:last-child').click(function(){
		if(isToggle == false) {
			$('.account-wraper').stop().fadeIn(500);
			$('.shadow').stop().fadeIn(500);
			isToggle = true;
		}
	})
	
	$(document).mouseup(function (e)
	{
		var container = $('.account-wraper');
		if (!container.is(e.target) && container.has(e.target).length === 0)
		{
			container.stop().fadeOut(500);
			$('.shadow').stop().fadeOut(500);
			isToggle = false;
		}
	});

	function scrollChallenges(){

		var faced = parseInt($('.stats-challenges div:first-child p:last-child').text());
		var succeed = parseInt($('.stats-challenges div:nth-child(2) p:last-child').text());
		var ratio = (succeed/faced)*100;
		var rest = 100-ratio;

		$('.challenges-wraper').animate({
			left: 0
		})
		$('.notifs-wraper').animate({
			left: -700
		})
		$('.success-wraper').animate({
			left: 700
		})
		$('.follow-wraper').animate({
			left: 1400
		})
		$('.infos-wraper').animate({
			left: 2100
		})
		$('.notifs .title h1').text('Challenges');

		var data = [
			{
				value: rest,
				color:"#fff"
			},
			{
				value : ratio,
				color : "#304673"
			}
		]
		var ctx = $('#graph').get(0).getContext("2d");
		new Chart(ctx).Pie(data);

	}


	function scrollSuccess(){

		$('.challenges-wraper').animate({
			left: -700
		})
		$('.notifs-wraper').animate({
			left: -1400
		})
		$('.success-wraper').animate({
			left: 0
		})
		$('.follow-wraper').animate({
			left: 700
		})
		$('.infos-wraper').animate({
			left: 1400
		})
		$('.notifs .title h1').text('Success');

	}


	function scrollNotifs(){

		$('.challenges-wraper').animate({
			left: 700
		})
		$('.notifs-wraper').animate({
			left: 0
		})
		$('.success-wraper').animate({
			left: 1400
		})
		$('.follow-wraper').animate({
			left: 2100
		})
		$('.infos-wraper').animate({
			left: 2800
		})
		$('.notifs .title h1').text('Notifications');

	}


	function scrollFollow(){

		$('.challenges-wraper').animate({
			left: -1400
		})
		$('.notifs-wraper').animate({
			left: -2100
		})
		$('.success-wraper').animate({
			left: -700
		})
		$('.follow-wraper').animate({
			left: 0
		})
		$('.infos-wraper').animate({
			left: 700
		})
		$('.notifs .title h1').text('Following');

	}


	function scrollInfos(){

		$('.challenges-wraper').animate({
			left: -2100
		})
		$('.notifs-wraper').animate({
			left: -2800
		})
		$('.success-wraper').animate({
			left: -1400
		})
		$('.follow-wraper').animate({
			left: -700
		})
		$('.infos-wraper').animate({
			left: 0
		})
		$('.notifs .title h1').text('Informations');
	}

	$('#noti').click(function(){
		scrollNotifs();
	})
	$('#chal').click(function(){
		scrollChallenges();
		console.log('ok');
	})
	$('#succ').click(function(){
		scrollSuccess();
	})
	$('#foll').click(function(){
		scrollFollow();
	})
	$('#info').click(function(){
		scrollInfos();
	})

	//Slider-heros

	$('.slider-hero .hexagon:first-child').animate({
			left: 400
		})
		$('.hexamid').animate({
			left: 65
		})
		$('.slider-hero .hexagon:last-child').animate({
			left: -400
		})


	$('input[type=radio][name=check]').change(function() {
			if (this.value == '1') {
				$('.slider-hero .hexagon:first-child').animate({
					left: 400
				})
				$('.hexamid').animate({
					left: 400
				})
				$('.slider-hero .hexagon:last-child').animate({
					left: 65
				})
			}
			else if (this.value == '2') {
				$('.slider-hero .hexagon:first-child').animate({
					left: 400
				})
				$('.hexamid').animate({
					left: 65
				})
				$('.slider-hero .hexagon:last-child').animate({
					left: -400
				})
			} else {
				$('.slider-hero .hexagon:first-child').animate({
					left: 65
				})
				$('.hexamid').animate({
					left: -400
				})
				$('.slider-hero .hexagon:last-child').animate({
					left: -400
				})
			}
	});

	//Toggle quizz

	var isToggleQ = false;
	var isInsideQ = false;


	$('.challenge-btt-start').click(function(){
		console.log('ok');
		if(isToggle == false) {
			$('.quizz-wraper').stop().fadeIn(500);
			$('.shadow').stop().fadeIn(500);
			isToggle = true;
		}
	})

	$('.goback').click(function(){
		$('.quizz-wraper').stop().fadeOut(500);
		$('.shadow').stop().fadeOut(500);
		isToggle = false;
	})
	
	$(document).mouseup(function (e)
	{
		var container = $('.quizz-wraper');
		if (!container.is(e.target) && container.has(e.target).length === 0)
		{
			container.stop().fadeOut(500);
			$('.shadow').stop().fadeOut(500);
			isToggle = false;
		}
	});

})