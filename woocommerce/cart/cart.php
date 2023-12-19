<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart'); ?>

<div class="container mx-auto">
    <h1 class="border-b pb-3 inline-block border-black text-2xl font-bold mb-5"><?php the_title() ?></h1>

    <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
        <?php do_action('woocommerce_before_cart_table'); ?>

        <table class="min-w-full divide-y divide-gray-200 cart border-collapse mb-5">
            <thead class="bg-gray-300 text-black text-left">
            <tr>
                <!--            <th class="px-6 py-5 text-left text-xs font-medium uppercase tracking-wider">&nbsp;</th>-->
                <th class="px-6 py-5 text-left text-xs font-medium uppercase tracking-wider" style="width: 40%;"><?php esc_html_e('Product', 'woocommerce'); ?></th>
                <!--            <th class="px-6 text-left text-xs font-medium uppercase tracking-wider">-->
                <?php //esc_html_e('Price', 'woocommerce'); ?><!--</th>-->
                <th class="text-xs px-10 font-medium uppercase tracking-wider" style="width: 20%"><?php esc_html_e('Quantity', 'woocommerce'); ?></th>
                <th class="px-6 text-right text-xs font-medium uppercase tracking-wider" style="width: 20%"><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php do_action('woocommerce_before_cart_contents'); ?>

            <?php
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                    ?>
                    <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                        <td class="product-name font-bold py-5"
                            data-title="<?php esc_attr_e('Product', 'woocommerce'); ?>">
                            <?php
                            $product = wc_get_product($_product->get_id());
                            $image_id = $product->get_image_id();
                            $image_url = wp_get_attachment_image_url($image_id, 'full');
                            $delivery_time_terms = get_the_terms($_product->get_id(), 'product_delivery_time');
                            $delivery_time = '';
                            
                            if ($delivery_time_terms && !is_wp_error($delivery_time_terms)) {
                                foreach ($delivery_time_terms as $term) {
                                    $delivery_time = $term->name;
                                }
}
                            ?>
                            <a href="<?php echo $product_permalink ?>"
                               class="h-44 inline-block float-left w-44 overflow-hidden">
                                <img style="width: 100%" src="<?php echo $image_url ?>" alt="">
                            </a>
                            <div class="relative left-10 top-20">
                                <?php
                                $pattern = '/\s+(?:women|Women|Men|men)\s*-\s*\S+/i';
                                $product_name = preg_replace($pattern, '', $_product->get_name());

                                if (!$product_permalink) {
                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $product_name, $cart_item, $cart_item_key) . '&nbsp;');
                                } else {
                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $product_name), $cart_item, $cart_item_key));
                                }
                                ?>
                                <p><?php
                                    if ( $delivery_time ) {
                                        echo 'Lieferzeit: ' . $delivery_time;
                                    } else {
                                        $delivery_time = wc_gzd_get_gzd_product( $product )->get_delivery_time_html();
                                        echo str_replace(['{', '}'], '', $delivery_time);
                                    }

                                ?></p>
                                <?php
                                do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

                                // Meta data.

                                echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok. ?>

                                <div>
                                    <?php // Backorder notification.
                                    if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                        echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
                                    }
                                    ?>

                                    <?php
                                    echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok.
                                    ?>
                                </div>
                            </div>
                        </td>

                        <td class="product-quantity px-10"
                            data-title="<?php esc_attr_e('Quantity', 'woocommerce'); ?>">
                            <?php
                            if ($_product->is_sold_individually()) {
                                $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                            } else {
                                $product_quantity = woocommerce_quantity_input(
                                    array(
                                        'input_name' => "cart[{$cart_item_key}][qty]",
                                        'input_type' => 'hidden',
                                        'input_value' => $cart_item['quantity'],
                                        'max_value' => $_product->get_max_purchase_quantity(),
                                        'min_value' => '0',
                                        'product_name' => $_product->get_name(),
                                    ),
                                    $_product,
                                    false
                                );
                            }

                            //                        echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
                            ?>

                            <span class="text-primary text-xl"><?php echo $product_quantity ?></span>

                            <?php
                            echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                'woocommerce_cart_item_remove_link',
                                sprintf(
                                    '<a href="%s"   onclick="if(!confirm(\' Artikel wirklich aus dem Warenkorb entfernen?\')) return false;" class="text-red-500 text-lg font-medium inline-block mt-3" aria-label="%s" data-product_id="%s" data-product_sku="%s">Löschen</a>',
                                    esc_url(wc_get_cart_remove_url($cart_item_key)),
                                    esc_html__('Remove this item', 'woocommerce'),
                                    esc_attr($product_id),
                                    esc_attr($_product->get_sku())
                                ),
                                $cart_item_key
                            );
                            ?>
                        </td>

                        <td class="text-right pr-5 font-bold"
                            data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>">
                            <?php
                            echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // PHPCS: XSS ok.
                            ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>

            <?php do_action('woocommerce_cart_contents'); ?>

            <tr>
                <td colspan="3" class="actions">

                      <button type="submit"
                            class="heroes-hidden1 button" style="margin-left: 0 !important;float: right !important; padding: 15px 20px;
    margin-bottom: 37px;"
                            name="update_cart"
                            value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>">
                        <?php esc_html_e('Update cart', 'woocommerce'); ?>
                    </button>
                    <?php if (wc_coupons_enabled()) { ?>
                        <div class="text-right" style="clear: both !important;">
                            <input type="text"
                                   name="coupon_code"
                                   class="h-14 w-56 rounded border-gray-300"
                                   id="coupon_code"
                                   value=""
                                   placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>"/>
                            <button type="submit"
                                    class="bg-primary p-3 mb-0 text-white rounded ml-5 font-bold"
                                    name="apply_coupon"
                                    value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>"><?php esc_attr_e('Apply coupon', 'woocommerce'); ?></button>
                            <?php do_action('woocommerce_cart_coupon'); ?>
                        </div>
                    <?php } ?>

                  

                    <?php do_action('woocommerce_cart_actions'); ?>

                    <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                </td>
            </tr>

            <?php do_action('woocommerce_after_cart_contents'); ?>
            </tbody>
        </table>
        <?php do_action('woocommerce_after_cart_table'); ?>
    </form>

    <?php do_action('woocommerce_before_cart_collaterals'); ?>

    <div class="cart-collaterals">
        <?php
        /**
         * Cart collaterals hook.
         *
         * @hooked woocommerce_cross_sell_display
         * @hooked woocommerce_cart_totals - 10
         */
        do_action('woocommerce_cart_collaterals');
        ?>
    </div>

    <?php do_action('woocommerce_after_cart'); ?>
</div>
    <script>
        function check_c() {
            
            var result = confirm("Press a button!");
            if (result == true) {
                doc = "OK was pressed.";
            } else {
                doc = "Cancel was pressed.";
            }
            document.getElementById("g").innerHTML = doc;
        }
    </script>