$(function() {
	
	/***********************************************************
	*
	*	Display the quizz
	*
	***********************************************************/
	
	var question = $('.individual-question');
	var choices = $('.choices');
	var left = $('.quizz-wraper .left');
	var right = $('.quizz-wraper .right');
	var nbQuestion = question.length;
	var i = 1;
	//var offset = $('#question1').offset().top;
	var questionIndicator = $('.question_indicator');
	
	left.fadeOut();
	left.on('click', function() {
		$('#question'+i).css('margin-top', '200');
		i--;
		$('#question'+i).slideDown();
		if(i === 1)
			left.fadeOut();
		right.fadeIn();
		questionIndicator.html(i+' / '+nbQuestion);
	});
	
	right.on('click', function() {
		left.fadeIn();
		$('#question'+i).slideUp();
		i++;
		$('#question'+i).css('margin-top', '0');
		if(i === nbQuestion)
			right.fadeOut();
		questionIndicator.html(i+' / '+nbQuestion);
	});
	
	choices.on('click', function() {
		left.fadeIn();
		$('#question'+i).slideUp();
		i++;
		$('#question'+i).css('margin-top', '0');
		if(i === nbQuestion)
			right.fadeOut();
		questionIndicator.html(i+' / '+nbQuestion);
	});
	
	
	/***********************************************************
	*
	*	Display the Account page information
	*
	***********************************************************/
	isToggle = false;
	isInside = false;


	$('.access-informations').click(function(e){
		e.preventDefault();
		if(isToggle == false) {
			$('.account-wraper').stop().fadeIn(500);
			$('.shadow').stop().fadeIn(500);
			isToggle = true;
		}
	});
	
	$(document).mouseup(function (e)
	{
		var container = $('.account-wraper');
		if (!container.is(e.target) && container.has(e.target).length === 0)
		{
			container.stop().fadeOut(500);
			$('.shadow').stop().fadeOut(500);
			$('.pop-up').stop().fadeOut(500);
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
		
	})
	$('#chal').click(function(){
		scrollChallenges();
		console.log('ok');
	})
	$('#succ').click(function(){
		scrollSuccess();
	})
	$('#foll').click(function(){
		scrollNotifs();
	})
	$('#info').click(function(){
		scrollInfos();
	})
});