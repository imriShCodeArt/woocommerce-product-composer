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
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }

    public function add_association_metabox()
    {
        add_meta_box(
            'wc_pc_associations',
            __('Associated Products', 'woocommerce-product-composer'),
            [$this, 'render_association_metabox'],
            'product',
            'normal',
            'default'
        );
    }

    public function render_association_metabox($post)
    {
        wp_nonce_field('wc_pc_save_associations', 'wc_pc_nonce');

        $associated_products = get_post_meta($post->ID, '_associated_products', true);
        if (!is_array($associated_products)) {
            $associated_products = [];
        }

        $products = get_posts([
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'post__not_in' => [$post->ID],
        ]);

        $template_path = plugin_dir_path(__FILE__) . '../templates/admin/association-metabox.php';

        if (file_exists($template_path)) {
            include $template_path;
        }
    }

    public function save_association_meta($post_id)
    {
        if (!isset($_POST['wc_pc_nonce']) || !wp_verify_nonce($_POST['wc_pc_nonce'], 'wc_pc_save_associations')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!empty($_POST['wc_pc_associated_products']['product_id'])) {
            $associated = [];
            $product_ids = $_POST['wc_pc_associated_products']['product_id'];
            $min_qtys = $_POST['wc_pc_associated_products']['min_qty'];
            $max_qtys = $_POST['wc_pc_associated_products']['max_qty'];

            foreach ($product_ids as $i => $pid) {
                $associated[] = [
                    'product_id' => intval($pid),
                    'min_qty' => intval($min_qtys[$i]),
                    'max_qty' => intval($max_qtys[$i]),
                ];
            }

            update_post_meta($post_id, '_associated_products', $associated);
        } else {
            delete_post_meta($post_id, '_associated_products');
        }
    }

    public function enqueue_admin_assets($hook)
    {
        global $post_type;

        if ($hook === 'post.php' || $hook === 'post-new.php') {
            if ($post_type === 'product') {
                wp_enqueue_script(
                    'wc-pc-admin-associations',
                    plugins_url('../assets/js/admin-associations.js', __FILE__),
                    ['jquery'],
                    '1.0',
                    true
                );

                wp_localize_script('wc-pc-admin-associations', 'wc_pc_admin', [
                    'remove_label' => __('Remove', 'woocommerce-product-composer'),
                    'no_items_label' => __('No associated products yet.', 'woocommerce-product-composer'),
                ]);
            }
        }
    }

}
