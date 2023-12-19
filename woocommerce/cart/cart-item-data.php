<?php
/**
 * Cart item data (when outputting non-flat)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-item-data.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version 	2.4.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$terms_color = get_terms( array(
    'taxonomy' => 'pa_color',
    'hide_empty' => false,
) );


foreach ($terms_color as $term_color) {
	//print_r($term);
	$term_vals = get_term_meta($term_color->term_id);
	foreach($term_vals as $key=>$val){
		//echo $key;
		if($key=='color')
		{
			$colors[$term_color->name]=$val[0];
		}
    	
	}
}




?>
<dl class="variation">
	<?php foreach ( $item_data as $data ) : ?>

		<?php if($data['key']=="Color") { 

			$selected_color=$colors[$data['display']]

			?>

			
		<!--<dt class="variation-<?php echo sanitize_html_class( $data['key'] ); ?>"><?php echo wp_kses_post( $data['key'] ); ?></dt>-->
		<dd class="variation-<?php echo sanitize_html_class( $data['key'] ); ?>"><?php echo wp_kses_post( wpautop('<span style="background-color:'.$selected_color.'" class="cart-pa-color"></span>')); ?></dd>

		<?php }else{
			?>

		
		<!--<dt class="variation-<?php echo sanitize_html_class( $data['key'] ); ?>"><?php echo wp_kses_post( $data['key'] ); ?></dt>-->
		<dd class="variation-<?php echo sanitize_html_class( $data['key'] ); ?>"><?php echo wp_kses_post( wpautop( $data['display'] ) ); ?></dd>

		<?php } ?>
		
	<?php endforeach; ?>
</dl>
