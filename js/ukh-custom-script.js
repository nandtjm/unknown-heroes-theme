(function($){
	'use strict';

	$(document).ready(function() {
		$('.search a').on('click', function(e) {
			$('.search').toggleClass('open');
		});
		
		function updateSizeInfo() {
			var is_admin = $('body').hasClass('admin-bar') ? true : false;
			var marginTop = 0;
			marginTop += $('#main-header').outerHeight();
			if ( is_admin && $(window).width() < 768 ) {
				// Already added via CSS
				//marginTop += $('#wpadminbar').outerHeight();
			} else if ($(window).width() < 480) {
				marginTop = 0;
			}
			$('#main-content').css('margin-top', marginTop + 'px');
		}

		updateSizeInfo();

		$(window).resize(function() {
      		updateSizeInfo();
    	});
		
		$( document.body ).on( 'woocommerce_variation_has_changed', function(e) {
			if ( $('.summary .single_add_to_cart_button.disabled').length > 0 &&
				$('.summary .variable-item.no-stock').length > 0 ) {
				$('.summary .single_add_to_cart_button').text('Nicht vorrätig');
			} else {
				$('.summary .single_add_to_cart_button').text('In den Warenkorb');
			}
		});

		let searchParams = new URLSearchParams(window.location.search);
		console.log(searchParams);
		if (searchParams.has('size')) {
			let sizeValue = searchParams.get('size');
			setTimeout(function(){
				console.log($('.summary .variable-item.button-variable-item-' + sizeValue ));
				$('.summary .variable-item').removeClass('selected');
				if ( $('.summary .variable-item.button-variable-item-' + sizeValue ).hasClass('no-stock') ) {
					$('.summary .variable-item:not(.no-stock):first').trigger('click');
				} else {
					$('.summary .variable-item.button-variable-item-' + sizeValue ).addClass('selected');
				}
				$('.summary .reset_variations').hide();
			}, 300);
		} else if ( $('.summary .variable-item.selected').length === 0 ) {
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
			$('.elementor-grid-4 .elementor-grid').css('grid-template-columns', 'repeat(2,1fr)' );
		});

		$('#four-cols-view').on('click', function() {
			$('.elementor-grid-4 .elementor-grid').css('grid-template-columns', 'repeat(4,1fr)' );
		});

		$('#two-cols-view-mobile').on('click', function() {
			$('.elementor-grid-4 .elementor-grid').css('grid-template-columns', 'repeat(2,1fr)' );
		});

		$('#one-col-grid').on('click', function() {
			$('.elementor-grid-4 .elementor-grid').css('grid-template-columns', 'repeat(1,1fr)' );
		});

		$('#one-col-grid-mobile').on('click', function() {
			$('.elementor-grid-4 .elementor-grid').css('grid-template-columns', 'repeat(1,1fr)' );
		});

		$('#two-cols-grid-mobile').on('click', function() {
			$('.elementor-grid-4 .elementor-grid').css('grid-template-columns', 'repeat(2,1fr)' );
		});

		
		$('.single-product .product .related .button-variable-item:not(.no-stock)').on('click', function(e) {
			e.preventDefault();
			if ( $(this).hasClass('no-stock') ) {
				return;
			}
			var sizeValue = $(this).attr('data-value'),
				productUrl = $(this).closest('.product').find('.woocommerce-LoopProduct-link').attr('href'),
				redirectProductUrl = productUrl + '?size=' + sizeValue;

			window.location.href = redirectProductUrl;

		});

		$('.archive .product .button-variable-item:not(.no-stock)').on('click', function(e) {
			e.preventDefault();
			if ( $(this).hasClass('no-stock') ) {
				return;
			}
			var sizeValue = $(this).attr('data-value'),
				productUrl = $(this).closest('.product').find('.woocommerce-LoopProduct-link').attr('href'),
				redirectProductUrl = productUrl + '?size=' + sizeValue;

			window.location.href = redirectProductUrl;

		});
		
		var sizesOrder = ['xs', 's', 'm', 'l', 'xl', 'xxl', '3xl'];
    
	    var $checkboxes = $('#filter_4646_1 .filter-item.checkbox.level-0.no-color');
	    
	    $checkboxes.sort(function(a, b) {
	        var sizeA = $(a).find('input[type="checkbox"]').val();
	        var sizeB = $(b).find('input[type="checkbox"]').val();
	        
	        return sizesOrder.indexOf(sizeA) - sizesOrder.indexOf(sizeB);
	    });
	    
	    $('#filter_4646_1 .filter-items.filter-checkbox.level-0').html($checkboxes);

		$(document).ajaxComplete(function(event, xhr, settings) {
		    if (settings.headers && settings.headers["X-YITH-WCAN"]) {
		        // Perform actions specific to this AJAX request completion
		        console.log("AJAX request with X-YITH-WCAN header completed");
		        
		        //window.location.reload();
		    }
		});

	});
})(jQuery);