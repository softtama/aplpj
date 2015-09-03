	// Scroll to top button
	var toper = $('a#top');
	$(window).scroll(function(){
		if ($(this).scrollTop() > $('div.logo').height() + $('nav#account-nav').height()) {
			toper.fadeIn( 200 );
		} else {
			toper.fadeOut( 200 );
		}
	});

	 toper.click(function(){
		$('html, body').animate({scrollTop:0}, 300);
		return false;
	}); 