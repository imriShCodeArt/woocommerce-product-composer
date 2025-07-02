<?php

namespace WC_Product_Composer;

if (!defined('ABSPATH')) {
    exit;
}

class Frontend
{

    public function __construct()
    {
        add_action('woocommerce_before_add_to_cart_button', [$this, 'render_composer_section'], 10);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script(
            'wc-product-composer',
            URL . 'assets/js/product-composer.js',
            [],
            VERSION,
            true
        );
    }


    public function render_composer_section()
    {
        global $product;

        $logger = Logger::get_instance();

        if (!$product) {
            $logger->error('No global $product object found in Frontend::render_composer_section.');
            return;
        }

        $logger->info('Rendering composer section for product ID: ' . $product->get_id());

        $associated_products = get_associated_products($product->get_id());

        $logger->info('Found ' . count($associated_products) . ' associated products for product ID: ' . $product->get_id());

        if (empty($associated_products)) {
            $logger->info('No associated products. Composer section will not render.');
            return;
        }

        wc_get_template(
            'product-composer-section.php',
            ['associated_products' => $associated_products],
            '',
            PATH . 'templates/'
        );

        $logger->info('Composer section rendered successfully for product ID: ' . $product->get_id());
    }
}
