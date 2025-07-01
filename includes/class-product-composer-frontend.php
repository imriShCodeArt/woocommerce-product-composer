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
        // TODO: Load template and display associated products
    }
}
