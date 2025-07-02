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
        add_action('woocommerce_add_to_cart', [$this, 'add_accessories_to_cart'], 10, 6);
    }

    /**
     * Store accessory selections in the main product's cart item data.
     */
    public function add_accessory_cart_data($cart_item_data, $product_id, $variation_id)
    {
        $logger = Logger::get_instance();

        $logger->info('add_accessory_cart_data triggered for product ID: ' . $product_id);

        if (isset($_POST['wc_pc_accessories']) && is_array($_POST['wc_pc_accessories'])) {
            $accessory_ids = array_map('intval', $_POST['wc_pc_accessories']);
            $logger->info('Accessories submitted: ' . implode(', ', $accessory_ids));
            $cart_item_data['wc_pc_accessories'] = $accessory_ids;
        } else {
            $logger->info('No accessories submitted in POST.');
        }

        return $cart_item_data;
    }


    /**
     * Add selected accessories to the cart as separate line items.
     */
    public function add_accessories_to_cart($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data)
    {
        if (empty($cart_item_data['wc_pc_accessories'])) {
            return;
        }

        foreach ($cart_item_data['wc_pc_accessories'] as $accessory_id) {
            // Check if accessory already in cart
            $already_in_cart = false;

            foreach (WC()->cart->get_cart() as $item) {
                if (intval($item['product_id']) === intval($accessory_id)) {
                    $already_in_cart = true;
                    break;
                }
            }

            if (!$already_in_cart) {
                WC()->cart->add_to_cart($accessory_id, 1);
            }
        }
    }
}
