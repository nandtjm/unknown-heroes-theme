<?php
defined('ABSPATH') || exit;

$current_product_id = get_the_ID();
$current_product = wc_get_product( $current_product_id );


$categories = get_the_terms( $current_product_id, 'product_cat' );
$primary_term = false;

if ( class_exists('WPSEO_Primary_Term') ) {
    $wpseo_primary_term = new WPSEO_Primary_Term( 'product_cat', $current_product_id );
    $wpseo_primary_term = $wpseo_primary_term->get_primary_term();
    $primary_term = get_term( $wpseo_primary_term );
}

$sub_categories = array();
$parent_id = 0;

$gender_value = $current_product->get_attribute('pa_geschlecht');

if ( $primary_term && ! is_wp_error( $primary_term ) ) {
    $parent_id = $primary_term->term_id;
}

foreach ($categories as $category) {
    if ( $parent_id && $parent_id === $category->parent ) {
        $sub_categories[] = $category;
    }
}

$product_results = array();

function get_products_from_category($category_id, $gender_value, $limit = 8) {
   $args = array(
        'category' => array($category_id),
        'limit' => $limit,
        'status' => array( 'publish' ),
        'stock_status' => 'instock',
        'return' => 'ids',
        'tax_query' => array( array(
            'taxonomy' => 'pa_geschlecht',
            'field'    => 'slug',
            'terms'    => $gender_value
        ) ),
   );
    return wc_get_products( $args );
}

if ( ! empty( $sub_categories ) ) {
    foreach ($sub_categories as $subcategory) {
        $current_category_products = get_products_from_category($subcategory->slug, $gender_value);

        if ($current_category_products) {
            $product_results = array_merge($product_results, $current_category_products);
            
            foreach ($product_results as $key => $id) {
                if ( $current_product_id === $id ) {
                    unset($product_results[$key]);
                }
            }

            if (count($product_results) >= 8) {
                break;
            }
        }
    }
} else {
    if ( ! is_wp_error( $primary_term ) ) :
        $current_category_products = get_products_from_category($primary_term->slug, $gender_value);

        if ($current_category_products) {
            $product_results = array_merge($product_results, $current_category_products);
            
            foreach ($product_results as $key => $id) {
                if ( $current_product_id === $id ) {
                    unset($product_results[$key]);
                }
            }

            if (count($product_results) > 8) {
               array_shift( $product_results );
            }
        }
    endif;
    
} ?>
<ul class="extra-related-product flex" style="flex-wrap: wrap; flex-direction: row; align-items: flex-start;"><?php
    
    foreach ($product_results as $__product_id) {
        $prod = wc_get_product( $__product_id );
        $product_image_url = get_the_post_thumbnail_url($__product_id);
        $product_permalink = get_permalink($__product_id); ?>
        <li id="product-<?php echo esc_attr( $__product_id ); ?>" style="width: 25%;padding: 10px;">
            <a href="<?php echo esc_url( $product_permalink ); ?>" title="<?php echo esc_attr( __( $prod->get_name(), 'woocommerce' ) ); ?>">
                <img src="<?php echo esc_url( $product_image_url ); ?>" alt="<?php echo esc_attr( __( $prod->get_name(), 'woocommerce' ) ); ?>">
            </a>
        </li><?php
    } ?>
</ul>
