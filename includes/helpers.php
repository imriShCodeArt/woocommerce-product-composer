<?php

namespace WC_Product_Composer;

if (!defined('ABSPATH')) {
    exit;
}

function get_associated_products($product_id)
{
    $ids = get_post_meta($product_id, '_associated_products', true);
    if (!is_array($ids)) {
        return [];
    }

    $products = [];
    foreach ($ids as $id) {
        $product = wc_get_product($id);
        if ($product && $product->is_visible()) {
            $products[] = $product;
        }
    }
    return $products;
}
