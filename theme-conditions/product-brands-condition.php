<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Product_Brands_Condition extends \ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base {

	public static function get_type() {
		return 'singular';
	}

	public function get_name() {
		return 'product_brands';
	}

	public function get_label() {
		return esc_html__( 'Product Brands', 'hello-elementor' );
	}

	public function get_all_label() {
		return esc_html__( 'All', 'hello-elementor' );
	}

	public function register_sub_conditions() {
		$brands = [ 'geometric', 'karacho', 'pure', 'shades', 'tam-tam', 'sparkasse' ];

		foreach ( $brands as $brand ) {
			$this->register_sub_condition( new \Product_Brand_Condition( $brand ) );
		}
	}

	public function check( $args ) {
		return is_product_taxonomy();
	}

}