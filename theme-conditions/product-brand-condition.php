<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Product_Brand_Condition extends \ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base {

	
	public function __construct( $brand ) {
		parent::__construct();

		$this->product_brand = $brand;
	}

	public static function get_type() {
		return 'product_brands';
	}

	public function get_name() {
		return strtolower( $this->product_brand . '_brand' );
	}

	public function get_label() {
		/* translators: %s: User role label. */
		return sprintf( esc_html__( '%s brand', 'hello-elementor' ), $this->product_brand );
	}

	public function check( $args ) {
		return in_array( $this->product_brand, 'karacho' );
	}

}