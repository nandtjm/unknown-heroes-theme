<?php
defined('ABSPATH') || exit;

$services_path = WP_PLUGIN_DIR . '/woocommerce-paypal-payments/modules/ppcp-button/services.php';
if ( file_exists( $services_path ) ) {
    require_once $services_path;
}

