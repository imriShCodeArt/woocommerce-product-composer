<?php

namespace WC_Product_Composer;

if (!defined('ABSPATH')) {
    exit;
}

function get_associated_products($product_id)
{
    $rows = get_post_meta($product_id, '_associated_products', true);

    if (!is_array($rows)) {
        return [];
    }

    $products = [];

    foreach ($rows as $assoc) {
        if (is_array($assoc)) {
            $pid = intval($assoc['product_id']);
            $min_qty = isset($assoc['min_qty']) ? intval($assoc['min_qty']) : 0;
            $max_qty = isset($assoc['max_qty']) ? intval($assoc['max_qty']) : 0;
        } else {
            // backward compatibility
            $pid = intval($assoc);
            $min_qty = 0;
            $max_qty = 0;
        }

        $product = wc_get_product($pid);

        if ($product && $product->is_visible()) {
            $products[] = [
                'product' => $product,
                'min_qty' => $min_qty,
                'max_qty' => $max_qty,
            ];
        }
    }

    return $products;
}
