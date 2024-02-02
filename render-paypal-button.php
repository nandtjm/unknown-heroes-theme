<?php
defined('ABSPATH') || exit;

$services_path = WP_PLUGIN_DIR . '/woocommerce-paypal-payments/modules/ppcp-button/module.php';
if ( file_exists( $services_path ) ) {
    var_dump($services_path);
    include $services_path;
}

