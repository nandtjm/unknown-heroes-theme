<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'UNKWON_HEROS_VERSION', '2.0.0' );

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function unkwon_heros_scripts_styles() {

	wp_enqueue_style(
		'unkwon-heros-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		UNKWON_HEROS_VERSION
	);

	wp_enqueue_script( 'ukh_custom', get_stylesheet_directory_uri() . '/js/ukh-custom-script.js', array('jquery'), UNKWON_HEROS_VERSION, true );

}
add_action( 'wp_enqueue_scripts', 'unkwon_heros_scripts_styles', 20 );

function ukh_header_right_menu_output($atts, $content = null) {
    ob_start();
    include_once get_stylesheet_directory() . '/header-right-menu.php';
    return ob_get_clean();
}
add_shortcode('ukh_header_right_menu', 'ukh_header_right_menu_output');

function ukh_product_title( $title, $post_id ) {
	
	if ( get_post_type( $post_id ) === 'product' && ! is_admin() ) {

		$gender = $color = $_title = '';

		if (preg_match('/\b(?:Men|Women)\b/i', $title, $genderMatches)) {
		    $gender = $genderMatches[0];
		    // Remove the gender from the title
		    if ( $gender ) {
		      $title = trim(str_ireplace($gender, "", $title));
		    }
		   
		}
		$commonColors = ["Red", "Blue", "Green", "Dark", "Light", "Black", "White", "Yellow", "Orange", "Taupe Blue", "Pink", "Rose", "Black Rose", "White Rose", "Taupe Blue", "Purple", "Taupe", "Midnight" ];

		$words = explode(" ", $title);
		// Iterate through the words to separate color and title
		foreach ($words as $word) {
		    if (in_array(trim($word), $commonColors, true)) {
		        $color .= $word . " ";
		     }
		}

		if ( $color ) {
		  $title = str_ireplace(rtrim($color), "", $title);
		}

		$current_product = wc_get_product($post_id);

		$gender_value = $current_product->get_attribute('pa_geschlecht');
		$color_value = $current_product->get_attribute('pa_color');

		if ( $color_value && $gender_value ) :
	      $title .= sprintf('<span class="product-subtitle"><span>%s</span>', esc_html( trim( $gender_value ) ) );
	      $title .= '<span> | </span>';
	      $title .= sprintf( '<span>%s</span></span>', esc_html( trim( $color_value) ) );
	    elseif( $gender_value ) :
	      $title .= sprintf('<span class="product-subtitle"><span>%s<span></span>', esc_html( trim( $gender_value ) ) );
	    elseif( $color_value ) :
	      $title .= sprintf('<span class="product-subtitle"><span>%s</span></span>', esc_html( trim( $color_value ) ) );
	    endif;

	}

	return $title;
}

add_filter( 'the_title', 'ukh_product_title', 10, 2 );


function extra_related_products_output($atts, $content = null) {
    ob_start();
    include_once get_stylesheet_directory() . '/extra-related-products.php';
    return ob_get_clean();
}
add_shortcode('ukh_extra_related_products', 'extra_related_products_output');

remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);

add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 20);

function add_product_brand_conditions( $conditions_manager ) {

	require_once( __DIR__ . '/theme-conditions/product-brands-condition.php' );
	require_once( __DIR__ . '/theme-conditions/product-brand-condition.php' );

	$conditions_manager->get_condition( 'archive' )->register_sub_condition( new \Product_Brands_Condition() );
}
//add_action( 'elementor/theme/register_conditions', 'add_product_brand_conditions' );

function ukh_render_paypal_button_output($atts, $content = null) {
    
	$gateway_ids = [ 'ppcp-gateway', 'ppcp-card-button-gateway' ];
	$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
	//$payment_method     = isset( $available_gateways[ 'ppcp-gateway' ] ) ? $available_gateways[ 'ppcp-gateway' ] : false;
	rand();
	foreach ( $gateway_ids as $gateway_id ) {
		if ( isset( $available_gateways[ $gateway_id ] ) ) {
			// The wrapper is needed for the loading spinner,
			// otherwise jQuery block() prevents buttons rendering.
			echo '<div class="ppc-button-wrapper">';
			$rand_id = rand(10,99);
			$hook_gateway_id = str_replace( '-', '_', $gateway_id );
			/**
			 * A hook executed after rendering of the opening tag for the PCP wrapper (before the inner wrapper for the buttons).
			 *
			 * For the PayPal gateway the hook name is ppcp_start_button_wrapper_ppcp_gateway.
			 */
			do_action( 'ppcp_start_button_wrapper_' . $hook_gateway_id );

			echo '<div id="ppc-button-' . esc_attr( $gateway_id ) . $rand_id . '"></div>';

			/**
			 * A hook executed before rendering of the closing tag for the PCP wrapper (before the inner wrapper for the buttons).
			 *
			 * For the PayPal gateway the hook name is ppcp_end_button_wrapper_ppcp_gateway.
			 */
			do_action( 'ppcp_end_button_wrapper_' . $hook_gateway_id );

			if ( 'ppcp-gateway' === $gateway_id ) { 
				do_action( 'woocommerce_paypal_payments_checkout_button_render' );
			}
			
			echo '</div>';
		}
	}
	
}
add_shortcode('ukh_render_paypal_button', 'ukh_render_paypal_button_output');


function ukh_the_password_form( $output, $post ) {

    $label  = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );

    $heading =  sprintf( __( 'Dieser Artikel kann nur von Mitarbeitern des SVB mittels gesondert zirkuliertem Passwort bestellt werden. Bestellungen sind bis einschließlich %s, möglich. Sodann werden die Shirts produziert und %s über den Sparkassenverbands Bayern an die Bestellenden verteilt. Alternativ können Sie sich diesen Artikel und/oder weitere Artikel aus unserem Shop auch über DHL zusenden lassen. Eine entsprechende Option finden Sie im Warenkorb.' ), '<strong>22. ' . __( 'Dezember' ) . ' 2023, 24:00 Uhr</strong>', '<strong>' . __( 'Ende Januar' ) . ' 2024</strong>' );
    $output = '<script>
        function calculateSHA1(inputString) {
            return new Promise(async (resolve) => {
                // Convert string to ArrayBuffer
                const encoder = new TextEncoder();
                const data = encoder.encode(inputString);

                // Calculate SHA-1 hash
                const buffer = await crypto.subtle.digest("SHA-1", data);

                // Convert ArrayBuffer to hexadecimal string
                const hashArray = Array.from(new Uint8Array(buffer));
                const sha1Hash = hashArray.map(byte => byte.toString(16).padStart(2, "0")).join("");

                resolve(sha1Hash);
            });
        }

        function validateForm() {
            var pass = jQuery("input[name=post_password]").val();

            calculateSHA1(pass).then((hashPass) => {
                var phpHash = "' . sha1($post->post_password) . '";

                if (hashPass !== phpHash) {
                    alert("Incorrect Password!");
                    return false;
                }
            });
        }
    </script>';

    $output .= '<style>
        @media (max-width: 496px) {
            #pass-submit {
                margin: 15px 0;
                margin-left: 75px;
            }
        }
    </style>';

    $output .= '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post" onsubmit="return validateForm()">';
    $output .= '<p style="color: #050505FC; font-size: 18px; font-weight: 400;">' . $heading . '</p>';
     $output .= '<br>';
    $output .= '<p><label for="' . $label . '">' . __( 'Password:' ) . ' <input name="post_password" id="' . $label . '" type="password" spellcheck="false" size="20" /></label> <input id="pass-submit" type="submit" name="Submit" value="' . esc_attr_x( 'Enter', 'post password form' ) . '" style="cursor: pointer;color:#ffffff;background-color:#b39740;border-radius:5px;padding:9px 17px;" /></p></form>';

    return $output;
    
}

add_filter( 'the_password_form', 'ukh_the_password_form', 10, 2 );

function ukh_title_format($prepend, $post) {
    
    return __('%s');
}

add_filter( 'protected_title_format', 'ukh_title_format', 10, 2 );

function has_password_protected_product() {
   
    if ( isset( WC()->cart ) ) {
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            
            $product_id = $cart_item['product_id'];
            $post_visibility = get_post_field('post_password', $product_id) ? 'password-protected' : get_post_status($product_id);
            
            if ( 'password-protected' === $post_visibility ) {
               return true;

            }
        }
    }

    return false;
}


function change_shipping_class_based_on_password_protected( $available_shipping_methods, $package ) {
    
    if ( ! has_password_protected_product() ) {
        
        foreach ( $available_shipping_methods as $shipping_method_key => $shipping_method ) {
            
            if ( 6 === $shipping_method->get_instance_id() ) {
                unset($available_shipping_methods[ $shipping_method_key ]);
            }
        }
        
    }
    
    return $available_shipping_methods;
}
//add_filter( 'woocommerce_package_rates', 'change_shipping_class_based_on_password_protected', 10, 2 );


function change_cart_item_name( $product_name ) {

    $pattern = '/\s+(?:women|Women|Men|men)\s*-\s*\S+/i';
    $product_name = preg_replace( $pattern, '', $product_name );
    return $product_name;
}

add_filter( 'woocommerce_cart_item_name', 'change_cart_item_name' );

add_action( 'template_redirect', 'ukh_track_product_view', 9999 );
 
function ukh_track_product_view() {
   if ( ! is_singular( 'product' ) ) return;
   global $post;
   if ( empty( $_COOKIE['ukh_recently_viewed'] ) ) {
      $viewed_products = array();
   } else {
      $viewed_products = wp_parse_id_list( (array) explode( '|', wp_unslash( $_COOKIE['ukh_recently_viewed'] ) ) );
   }
   $keys = array_flip( $viewed_products );
   if ( isset( $keys[ $post->ID ] ) ) {
      unset( $viewed_products[ $keys[ $post->ID ] ] );
   }
   $viewed_products[] = $post->ID;
   if ( count( $viewed_products ) > 8 ) {
      array_shift( $viewed_products );
   }
   wc_setcookie( 'ukh_recently_viewed', implode( '|', $viewed_products ) );
}
 
add_shortcode( 'ukh_recently_viewed_products', 'ukh_recently_viewed_products_shortcode' );
  
function ukh_recently_viewed_products_shortcode() {
   $viewed_products = ! empty( $_COOKIE['ukh_recently_viewed'] ) ? (array) explode( '|', wp_unslash( $_COOKIE['ukh_recently_viewed'] ) ) : array();
   if ( is_singular() ) {
    global $post;
    $keys = array_flip( $viewed_products );
    if ( isset( $keys[ $post->ID ] ) ) {
        unset( $viewed_products[ $keys[ $post->ID ] ] );
    }
   }
   $viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );
   if ( empty( $viewed_products ) ) return;
   $title = '<h2 class="uppercase inline-block pb-1 mt-12 border-b border-black text-2xl font-bold">KÜRZLICH ANGESEHENE ARTIKEL</h2>';
   $product_ids = implode( ",", $viewed_products );
   return $title . do_shortcode('[products ids="'.$product_ids.'" paginate="false" class="ukh-recently-viewed"]');
}

function recently_viewed_after_single_product() {
    echo do_shortcode('[ukh_recently_viewed_products]');
}

add_action( 'woocommerce_after_single_product', 'recently_viewed_after_single_product', 20 );

function ukh_shipping_packages( $packages ) {

    foreach ($packages as $package_key => $package) {
        $rates = $package['rates'];
        foreach ($rates as $method_key => $rate) {
           if (  has_password_protected_product() && 'flat_rate:6' !== $method_key ) {
                    //$rates[$method_key]['flat_rate:6'] = $new_method;
            } elseif ( ! has_password_protected_product() && 'flat_rate:6' === $method_key ) {
                    unset($rates[$method_key]);
            }

            if ( 'free_shipping:2' === $method_key ) {
                unset( $rates['flat_rate:1'] );
            }
        }
        $package['rates'] = $rates;
        $packages[ $package_key ] = $package;
    }

    return $packages;
}

add_filter( 'woocommerce_shipping_packages', 'ukh_shipping_packages' );

function uhk_display_item_meta( $html, $item, $args ) {

    $strings = array();
    $html = '';
    $args    = wp_parse_args(
        $args,
        array(
            'before'       => '<ul class="wc-item-meta"><li>',
            'after'        => '</li></ul>',
            'separator'    => '</li><li>',
            'echo'         => true,
            'autop'        => false,
            'label_before' => '<strong class="wc-item-meta-label">',
            'label_after'  => ',</strong> ',
        )
    );

    $args['label_before'] = '<span class="wc-item-meta-label">';
    $args['label_after'] = '</span>';

    $product_id = $item->get_product_id();
    $product = wc_get_product($product_id);
    $gender = $product->get_attribute('pa_geschlecht');

    foreach ( $item->get_all_formatted_meta_data() as $meta_id => $meta ) {
        if ( 'Size' === $meta->display_key ) {
            $value     = $args['autop'] ? wp_kses_post( $meta->display_value ) : wp_kses_post( make_clickable( trim( $meta->display_value ) ) );
            $strings[] = $args['label_before'] . $gender . ', ' . $args['label_after'] . $value;
        }
    }

    if ( $strings ) {
        $html = $args['before'] . implode( $args['separator'], $strings ) . $args['after'];
    }

    return $html;
}

add_filter( 'woocommerce_display_item_meta', 'uhk_display_item_meta', 10, 3 );


if( ! function_exists( 'yith_wcan_content_selector' ) ){
    function yith_wcan_content_selector( $selector ){
        $selector = '.elementor-location-archive.product';
        return $selector;
    }
    add_filter( 'yith_wcan_content_selector', 'yith_wcan_content_selector' );
}

function ukh_order_size_filter( $template_path, $template, $atts, $echo ) {

	$item_name = $atts['item_name'];
	if ( 'filter[4646][1]' == $item_name ) {

	}

	return $template_path;
}

//add_filter( 'yith_wcan_template_path', 'ukh_order_size_filter', 10, 4 );


// Custom sorting function for WooCommerce product attributes
function custom_product_attribute_sorting( $terms, $taxonomies, $args ) {
    // Check if the taxonomy is 'pa_size'
    if ( in_array( 'pa_size', $taxonomies ) ) {
        // Define the desired order of size attributes
        $desired_order = array( 'XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL' );
        var_dump($terms);
        // Sort the terms based on the desired order
        usort( $terms, function ( $a, $b ) use ( $desired_order ) {
            $a_index = array_search( $a, $desired_order );
            $b_index = array_search( $b, $desired_order );

            // If both terms are found in the desired order array, compare their positions
            if ( $a_index !== false && $b_index !== false ) {
                return $a_index - $b_index;
            } elseif ( $a_index !== false ) { // If only $a is found in the desired order array, it should come first
                return -1;
            } elseif ( $b_index !== false ) { // If only $b is found in the desired order array, it should come first
                return 1;
            } else { // If neither $a nor $b is found in the desired order array, maintain their original order
                return 0;
            }
        } );
    }

    return $terms;
}
add_filter( 'get_terms', 'custom_product_attribute_sorting', 10, 3 );

