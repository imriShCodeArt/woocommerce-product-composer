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
        add_action('add_meta_boxes', [$this, 'add_used_in_metabox']);
        add_action('wp_ajax_wc_pc_scan_components', [$this, 'handle_scan_components']);
    }

    public function enqueue_admin_assets($hook)
    {
        global $post_type;

        if (($hook === 'post.php' || $hook === 'post-new.php') && $post_type === 'product') {
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
                'unlimited_label' => __('Unlimited', 'woocommerce-product-composer'),
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('wc_pc_scan_nonce'),
            ]);
        }
    }

    public function add_used_in_metabox()
    {
        add_meta_box(
            'wc_pc_used_in',
            __('Used In Other Products', 'woocommerce-product-composer'),
            [$this, 'render_used_in_metabox'],
            'product',
            'side',
            'default'
        );
    }

    public function render_used_in_metabox($post)
    {
        $current_id = $post->ID;

        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => '_associated_products',
                    'compare' => 'EXISTS',
                ],
            ],
            'fields' => 'ids',
        ];

        $products = get_posts($args);
        $used_in = [];

        foreach ($products as $product_id) {
            $associations = get_post_meta($product_id, '_associated_products', true);

            if (!is_array($associations)) {
                $associations = $associations ? [
                    [
                        'product_id' => (int) $associations,
                        'min_qty' => 0,
                        'max_qty' => 0,
                    ]
                ] : [];
            }

            foreach ($associations as $assoc) {
                $assoc_id = is_array($assoc) ? intval($assoc['product_id']) : intval($assoc);
                if ($assoc_id === $current_id) {
                    $used_in[] = $product_id;
                    break;
                }
            }
        }

        if (empty($used_in)) {
            echo '<p>' . esc_html__('This product is not used as an accessory in any other product.', 'woocommerce-product-composer') . '</p>';
        } else {
            echo '<ul>';
            foreach ($used_in as $pid) {
                $prod = get_post($pid);
                if ($prod) {
                    echo '<li><a href="' . esc_url(get_edit_post_link($pid)) . '">' . esc_html($prod->post_title) . '</a></li>';
                }
            }
            echo '</ul>';
        }
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

        $all_products = get_posts([
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'post__not_in' => [$post->ID],
        ]);

        $table_path = plugin_dir_path(__FILE__) . '../templates/admin/association-table.php';
        $controls_path = plugin_dir_path(__FILE__) . '../templates/admin/association-controls.php';
        $scan_path = plugin_dir_path(__FILE__) . '../templates/admin/scan-button.php';

        if (file_exists($table_path)) {
            $assoc_products = $associated_products;
            include $table_path;
        }

        if (file_exists($controls_path)) {
            include $controls_path;
        }

        if (file_exists($scan_path)) {
            $product_id = $post->ID;
            include $scan_path;
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

    public function handle_scan_components()
    {
        check_ajax_referer('wc_pc_scan_nonce', 'nonce');

        $main_product_id = intval($_POST['product_id'] ?? 0);

        if (!$main_product_id) {
            wp_send_json_error(['message' => 'Invalid product ID.']);
        }

        $product_cats = wp_get_post_terms($main_product_id, 'product_cat', ['fields' => 'slugs']);
        if (!in_array('drones', $product_cats, true)) {
            wp_send_json_error(['message' => 'This product is not in the "Drones" category.']);
        }

        $tags = wp_get_post_terms($main_product_id, 'product_tag', ['fields' => 'slugs']);
        if (empty($tags)) {
            wp_send_json_error(['message' => 'This product has no tags.']);
        }

        $main_tag = $tags[0];

        $query_args = [
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'post__not_in' => [$main_product_id],
            'tax_query' => [
                [
                    'taxonomy' => 'product_tag',
                    'field' => 'slug',
                    'terms' => $main_tag,
                ]
            ]
        ];

        $query = new \WP_Query($query_args);
        $associated = [];

        if ($query->have_posts()) {
            foreach ($query->posts as $prod) {
                $associated[] = [
                    'product_id' => intval($prod->ID),
                    'min_qty' => 0,
                    'max_qty' => 0,
                ];
            }

            update_post_meta($main_product_id, '_associated_products', $associated);

            wp_send_json_success([
                'message' => sprintf(
                    'Linked %d products as components for this product.',
                    count($associated)
                )
            ]);
        } else {
            wp_send_json_success(['message' => 'No compatible products found.']);
        }
    }
}
