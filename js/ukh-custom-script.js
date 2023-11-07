(function($){
	'use strict';

	$(document).ready(function() {
		$('.search a').on('click', function(e) {
			$('.search').toggleClass('open');
		});
	
		var is_admin = $('body').hasClass('admin-bar') ? true : false;
		var marginTop = 10;
		marginTop += $('#main-header').outerHeight();
		if ( is_admin ) {
			//marginTop += $('#wpadminbar').outerHeight();
		}
		
		if ( $('.single > .woocommerce-notices-wrapper:visible').length > 0 ) {
			
			$('.single > .woocommerce-notices-wrapper').css('margin-top', marginTop + 'px');
		} else {
			$('.single main').css('margin-top', marginTop + 'px');
		}

	});
})(jQuery);