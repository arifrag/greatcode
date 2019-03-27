$(window).on("scroll", function(){
	var scroll = $(window).scrollTop();
	if(scroll >= 75){
		$('.navgc').addClass('white');
	}
	else{
		$('.navgc').removeClass('white');
	}
	if(scroll >= 75){
		$('.menunav a').addClass('black');
	}
	else{
		$('.menunav a').removeClass('black');
	}
	if(scroll >= 75){
		$('.icon-nav').addClass('none');
	}
	else{
		$('.icon-nav').removeClass('none');
	}
	if(scroll >= 75){
		$('.icon-scroll').addClass('block');
	}
	else{
		$('.icon-scroll').removeClass('block');
	}
})

// one scroll

$(document).ready(function(){
  	$('a[href*="#"]:not([href="#"])').click(function() {
	  	if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
	    	var target = $(this.hash);
	    	target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
	    	if (target.length) {
	      		$('html, body').animate({
	        		scrollTop: target.offset().top
	      		},1000);
	      	return false;
	    	}
	  	}
	});
});

// nav

$(document).ready(function(){
	$('.cover').hide();
	$('.menu-close').hide();
	$('.menu-open').on("click", function(){
		$('.menu-web').animate({left: '300px'});
		$('.cover').show();
		$('.menu-open').hide();
		$('.menu-close').show();

	})
	$('.cover').on("click", function(){
		$('.menu-web').animate({left: '-300px'});
		$('.cover').hide();
		$('.menu-open').show();
		$('.menu-close').hide();
	})
	$('.menu-web .clickme').on("click", function(){
		$('.menu-web').animate({left: '-300px'});
		$('.cover').hide();
		$('.menu-open').show();
		$('.menu-close').hide();

	})
})