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

function custom_woocommerce_category_permalink($url, $term, $taxonomy) {
    if ($taxonomy == 'product_cat') {
        $url = str_replace('/product-category/', '/', $url);
    }
    return $url;
}

add_filter('term_link', 'custom_woocommerce_category_permalink', 10, 3);

function custom_woocommerce_rewrite_rules($rules) {
    $new_rules = array();

    // Remove product-category base from category URLs
    foreach ($rules as $key => $rule) {
        $new_rules[ str_replace('product-category/', '', $key) ] = $rule;
    }

    return $new_rules;
}

add_filter('rewrite_rules_array', 'custom_woocommerce_rewrite_rules');

// Flush rewrite rules on activation
function custom_woocommerce_flush_rewrite_rules() {
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'custom_woocommerce_flush_rewrite_rules');

function add_custom_endpoint() {
 	add_rewrite_rule('^brand/([^/]+)/?', 'index.php?brand=$matches[1]', 'top');
}
add_action('init', 'add_custom_endpoint');

function custom_brand_endpoint_redirect() {
    global $wp_query;

    if (isset($wp_query->query_vars['brand'])) {
        $brand_slug = sanitize_text_field($wp_query->query_vars['brand']);
        $tax_query = array(
            array(
                'taxonomy' => 'pa_brand',
                'field' => 'slug',
                'terms' => $brand_slug,
            ),
        );

        $args = array(
            'post_type' => 'product',
            'tax_query' => $tax_query,
        );

        query_posts($args);
    }
}
//add_action('template_redirect', 'custom_brand_endpoint_redirect');






