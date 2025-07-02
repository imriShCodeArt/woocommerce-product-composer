<?php
/**
 * Plugin Name: WooCommerce Product Composer
 * Description: Allows customers to compose product packages by selecting related accessories directly on product pages.
 * Version: 1.0.0
 * Author: M.L Web Solutions
 * Text Domain: woocommerce-product-composer
 * Domain Path: /languages
 */

namespace WC_Product_Composer;

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define(__NAMESPACE__ . '\PATH', plugin_dir_path(__FILE__));
define(__NAMESPACE__ . '\URL', plugin_dir_url(__FILE__));

// Include required files
require_once PATH . 'includes/class-product-composer-admin.php';
require_once PATH . 'includes/class-product-composer-frontend.php';
require_once PATH . 'includes/class-product-composer-cart.php';
require_once PATH . 'includes/class-product-composer-logger.php';
require_once PATH . 'includes/helpers.php';

// Init plugin
add_action('plugins_loaded', __NAMESPACE__ . '\init_plugin');

function init_plugin()
{
    if (class_exists('\WooCommerce')) {
        // Initialize logger early
        Logger::get_instance()->info('WooCommerce Product Composer plugin initialized.');

        // Admin
        if (is_admin()) {
            new \WC_Product_Composer\Admin();
        }

        // Frontend
        new \WC_Product_Composer\Frontend();

        // Cart Handling
        new \WC_Product_Composer\Cart();
    }
}
