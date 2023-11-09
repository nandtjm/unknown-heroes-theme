<?php
defined('ABSPATH') || exit;

// Get the current product
$current_product = wc_get_product(get_the_ID());
// Get the product category IDs of the current product
$category_ids = $current_product->get_category_ids();
// Initialize an array to store the subcategory results
$subcategories = array();

$gender_value = $current_product->get_attribute('pa_geschlecht');

// Loop through each category ID
foreach ($category_ids as $category_id) {
    // Get subcategories of the current category
    $sub_categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'child_of' => $category_id,
        'hide_empty' => true
    ));

    // Merge the subcategories into the result array
    $subcategories = array_merge($subcategories, $sub_categories);
}
// Initialize an array to store the product results
$product_results = array();

// Function to fetch products from a category
function get_products_from_category($category_id, $gender_value, $limit = 8) {
   $args = array(
        'category' => array($category_id),
        'limit' => $limit,
        'status' => array( 'publish' ),
        'stock_status' => 'instock',
        'tax_query' => array( array(
            'taxonomy' => 'pa_geschlecht',
            'field'    => 'slug',
            'terms'    => $gender_value
        ) ),
   );
    return wc_get_products( $args );
}

// Loop through each category ID
foreach ($subcategories as $subcategory) {
    $current_category_products = get_products_from_category($subcategory->slug, $gender_value);

    // If there are products in the current category, add them to the result
    if ($current_category_products) {
        $product_results = array_merge($product_results, $current_category_products);
        
        // If we have 8 products, stop the loop
        if (count($product_results) >= 8) {
            break;
        }
    }
}
// If we don't have enough products yet, fetch from parent category
if (count($product_results) < 8) {

    foreach ($category_ids as $category_id) {
        $category = get_term($category_id, 'product_cat');
        if ($category->parent != 0) {
            // Get the parent category products
            $parent_category_products = get_products_from_category($category->slug, $gender_value, 8 - count($product_results));
	
            $product_results = array_merge($product_results, $parent_category_products);
            
            // Stop the loop if we reach 8 products
            if (count($product_results) >= 8) {
                break;
            }
        }
    }
} else {
    $offset = count($product_results) - 8;
    $product_results = array_slice( $product_results, $offset );
}

?>
<ul class="extra-related-product flex" style="flex-wrap: wrap;"><?php
	// Loop through the product IDs
	foreach ($product_results as $product_result) {
	    $product_id = $product_result->get_id();
	    // Get product image URL
	    $product_image_url = get_the_post_thumbnail_url($product_id);

	    // Get product permalink
	    $product_permalink = get_permalink($product_id);

	   	?>
	   	<li id="product-<?php echo esc_attr( $product_id ); ?>" style="flex: 0 0 25%; padding: 10px;">
	   		<a href="<?php echo esc_url( $product_permalink ); ?>" title="<?php echo esc_attr( __( $product_result->get_name(), 'woocommerce' ) ); ?>">
	   			<img src="<?php echo esc_url( $product_image_url ); ?>" alt="<?php echo esc_attr( __( $product_result->get_name(), 'woocommerce' ) ); ?>">
	   		</a>
	   	</li><?php
	} ?>
</ul>
