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
