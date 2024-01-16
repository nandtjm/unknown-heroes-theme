(function($){
	'use strict';

	$(document).ready(function() {
		$('.search a').on('click', function(e) {
			$('.search').toggleClass('open');
		});
	
		var is_admin = $('body').hasClass('admin-bar') ? true : false;
		var marginTop = 0;
		marginTop += $('#main-header').outerHeight();
		if ( is_admin && $(window).width() < 768 ) {
			// Already added via CSS
			//marginTop += $('#wpadminbar').outerHeight();
		}
		
		$('#main-content').css('margin-top', marginTop + 'px');
		
		$( document.body ).on( 'woocommerce_variation_has_changed', function(e) {
			if ( $('.summary .single_add_to_cart_button.disabled').length > 0 &&
				$('.summary .variable-item.no-stock').length > 0 ) {
				$('.summary .single_add_to_cart_button').text('Nicht vorrätig');
			} else {
				$('.summary .single_add_to_cart_button').text('In den Warenkorb');
			}
		});

		if ( $('.summary .variable-item.selected').length === 0 ) {
			setTimeout(function(){
				$('.summary .variable-item:not(.no-stock):first').trigger('click');
				
				$('.summary .reset_variations').hide();
			}, 300);
		}
		$(".woof_container_inner_produkt-kategorien h4").text("PRODUKTE");
		$(".woof_container_inner_produktbrand h4").text("KOLLEKTIONEN");
		$(".woof_container_inner_produktgeschlecht h4").text("GESCHLECHT");

		$(".open-cookie-bot").click(function(){
			$(".CookiebotWidget-logo").trigger("click");
		});

		if ( $('body').hasClass('woocommerce-shop') ) {
			$('.yith-wcan-reset-filters').text('Zurücksetzen');
		}

		$('#two-cols-view').on('click', function() {
			console.log('two col clicked');
			$('.elementor-grid-4 .elementor-grid').css('grid-template-columns', 'repeat(2,1fr) !important' );
		});

		$('#four-cols-view').on('click', function() {
			console.log('four col clicked');
			$('.elementor-grid-4 .elementor-grid').css('grid-template-columns', 'repeat(4,1fr) !important' );
		});

	});
})(jQuery);