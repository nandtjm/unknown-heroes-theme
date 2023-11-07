(function($){
	'use strict';

	$(document).ready(function() {
		$('.search a').on('click', function(e) {
			$('.search').toggleClass('open');
		});
	
		var is_admin = $('body').hasClass('admin-bar') ? true : false;
		var marginTop = $('#main-header').outerHeight();
		if ( is_admin ) {
			marginTop += $('#wpadminbar').outerHeight();
		}
		
		if ( $('.single-product > .woocommerce-notices-wrapper:visible').length > 0 ) {
			
			$('.single-product > .woocommerce-notices-wrapper').css('margin-top', marginTop + 'px');
		} else {
			$('.single-product main').css('margin-top', marginTop + 'px');
		}

	});
})(jQuery);