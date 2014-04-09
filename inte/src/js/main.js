$(document).ready(function(){

//Header's slider

	isFirst = true;

	$('.left').click(function(){
		if(isFirst == true) {
			$('.slider').css({
				'background-image': 'url("http://www.nyan.cat/cats/original.gif")'
			});
			isFirst = false;
		} else {
			$('.slider').css({
				'background-image': 'url("src/images/header-bg.png")'
			});
			isFirst = true;
		}
		
	})

	$('.right').click(function(){
		if(isFirst == true) {
			$('.slider').css({
				'background-image': 'url("http://www.nyan.cat/cats/original.gif")'
			});
			isFirst = false;
		} else {
			$('.slider').css({
				'background-image': 'url("src/images/header-bg.png")'
			});
			isFirst = true;
		}
		
	})

	//Toggle account

	isToggle = false;
	isInside = false;


	$('.account, .nav li:last-child').click(function(){
		if(isToggle == false) {
			$('.account-wraper').fadeIn(500);
			isToggle = true;
		}
	})
	
	$(document).mouseup(function (e)
	{
		var container = $('.account-wraper');
	    if (!container.is(e.target) && container.has(e.target).length === 0)
	    {
	        container.fadeOut(500);
	        isToggle = false;
	    }
	});



})