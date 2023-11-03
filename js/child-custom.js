(function($) {
	'use strict';
	
	$(document).ready(function() {
		$('.search a').on('click', function(e) {
			console.log('clicked....');
			$('.search').toggleClass('open');
		});
	});

})(jQuery);