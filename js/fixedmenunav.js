// Fixed menu navigation

var mainNav = $('nav#main-menu-nav');
var mainCon = $('div#main-content');

$(window).bind('scroll', function() {
	 if ($(window).scrollTop() > $('div.logo').height() + $('nav#account-nav').height()) {
		 mainNav.addClass('fixed');
         mainCon.addClass('fixed');
	 }
	 else {
		 mainNav.removeClass('fixed');
         mainCon.removeClass('fixed');
	 }
});
