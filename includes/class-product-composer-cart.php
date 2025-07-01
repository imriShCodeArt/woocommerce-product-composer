<?php

namespace WC_Product_Composer;

if (!defined('ABSPATH')) {
    exit;
}

class Cart
{

    public function __construct()
    {
        add_filter('woocommerce_add_cart_item_data', [$this, 'add_accessory_cart_data'], 10, 3);
        add_action('woocommerce_before_calculate_totals', [$this, 'adjust_cart_items']);
    }

    public function add_accessory_cart_data($cart_item_data, $product_id, $variation_id)
    {
        // TODO: Store accessory selections in cart item data
        return $cart_item_data;
    }

    public function adjust_cart_items($cart)
    {
        // TODO: Handle any adjustments if necessary
    }
}
