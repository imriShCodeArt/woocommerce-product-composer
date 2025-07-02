<?php

namespace WC_Product_Composer;

if (!defined('ABSPATH')) {
    exit;
}



class Admin
{

    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'add_association_metabox']);
        add_action('save_post', [$this, 'save_association_meta']);
    }

    public function add_association_metabox()
    {
        add_meta_box(
            'wc_pc_associations',
            __('Associated Products', 'woocommerce-product-composer'),
            [$this, 'render_association_metabox'],
            'product',
            'side',
            'default'
        );
    }

    public function render_association_metabox($post)
    {
        wp_nonce_field('wc_pc_save_associations', 'wc_pc_nonce');

        $associated_ids = get_post_meta($post->ID, '_associated_products', true);
        if (!is_array($associated_ids)) {
            $associated_ids = [];
        }

        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'post__not_in' => [$post->ID],
        ];

        $products = get_posts($args);

        echo '<p>' . esc_html__('Select products that should be suggested as accessories for this product.', 'woocommerce-product-composer') . '</p>';
        echo '<select name="wc_pc_associated_products[]" multiple style="width:100%;height:150px;">';

        foreach ($products as $product) {
            $selected = in_array($product->ID, $associated_ids) ? 'selected' : '';
            printf(
                '<option value="%d" %s>%s</option>',
                $product->ID,
                $selected,
                esc_html($product->post_title)
            );
        }

        echo '</select>';
    }

    public function save_association_meta($post_id)
    {
        if (!isset($_POST['wc_pc_nonce']) || !wp_verify_nonce($_POST['wc_pc_nonce'], 'wc_pc_save_associations')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (isset($_POST['wc_pc_associated_products'])) {
            $ids = array_map('intval', $_POST['wc_pc_associated_products']);
            update_post_meta($post_id, '_associated_products', $ids);
        } else {
            delete_post_meta($post_id, '_associated_products');
        }
    }
}
