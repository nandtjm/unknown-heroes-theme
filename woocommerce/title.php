<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @package    WooCommerce\Templates
 * @version    1.6.4
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
global $product;
?>
<?php

// Sample product title
$title = get_the_title();
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
        // This word is a color adjective
        $color .= $word . " ";
        //$isColor = true;
    } //else {
    //   // This word is part of the title
    //   $_title .= $word . " ";
    // }
}

if ( $color ) {
  $title = str_ireplace(rtrim($color), "", $title);
}

$current_product = wc_get_product(get_the_ID());

// Get the gender attribute value for the current product
$gender_value = $current_product->get_attribute('pa_geschlecht');
$color_value = $current_product->get_attribute('pa_color');

// if ( strtolower( $gender_value ) == 'men' ) {
//       $gender_value = __( 'Herren', 'woocommerce' );
//   } else {
//       $gender_value = __( 'Damen', 'woocommerce' );
//   }
?>
<div class="single-product-title mb-8 flex flex-col text-center" style="gap: 0.5rem;">
  <h1 class="text-2xl mb-0 font-bold"><?php echo esc_html( trim($title) ); ?></h1>
  <div class="product-subtitle text-xl product-subtitle flex justify-center" style="gap: 1rem; font-weight: 500;">
    <?php if ( $color_value && $gender_value ) : ?>
      <span><?php echo esc_html( trim( $gender_value ) ); ?></span>
      <span>|</span>
      <span><?php echo esc_html( trim( $color_value) ); ?></span>
    <?php elseif( $gender_value ) : ?>
      <span><?php echo esc_html( trim( $gender_value ) ); ?></span>
    <?php elseif( $color_value ) : ?>
      <span><?php echo esc_html( trim( $color_value ) ); ?></span>
    <?php endif; ?>
  </div>
</div>
