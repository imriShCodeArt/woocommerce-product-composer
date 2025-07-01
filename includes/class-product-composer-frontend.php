<?php

namespace WC_Product_Composer;

if (!defined('ABSPATH')) {
    exit;
}

class Frontend
{

    public function __construct()
    {
        add_action('woocommerce_single_product_summary', [$this, 'render_composer_section'], 25);
    }

    public function render_composer_section()
    {
        global $product;

        $associated_products = get_associated_products($product->get_id());

        if (empty($associated_products)) {
            return;
        }

        wc_get_template(
            'product-composer-section.php',
            ['associated_products' => $associated_products],
            '',
            WC_PC_PATH . 'templates/'
        );
    }
}
