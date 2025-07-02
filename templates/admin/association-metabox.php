<?php
/**
 * Association Metabox Template
 *
 * @var array $associated_products
 * @var array $products
 * @var int $post_id
 */

if (!defined('ABSPATH')) {
    exit;
}

?>

<p><?php esc_html_e('Add products as accessories. Define minimum and maximum quantities if needed.', 'woocommerce-product-composer'); ?>
</p>

<table class="widefat wc-pc-associated-table" style="margin-bottom: 15px;">
    <thead>
        <tr>
            <th><?php esc_html_e('Product', 'woocommerce-product-composer'); ?></th>
            <th><?php esc_html_e('Min Qty', 'woocommerce-product-composer'); ?></th>
            <th><?php esc_html_e('Max Qty', 'woocommerce-product-composer'); ?></th>
            <th><?php esc_html_e('Remove', 'woocommerce-product-composer'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($associated_products)): ?>
            <?php foreach ($associated_products as $assoc):
                if (is_array($assoc)) {
                    $product_id = intval($assoc['product_id']);
                    $min_qty = isset($assoc['min_qty']) ? intval($assoc['min_qty']) : '';
                    $max_qty = isset($assoc['max_qty']) ? intval($assoc['max_qty']) : '';
                } else {
                    // fallback for older meta (int only)
                    $product_id = intval($assoc);
                    $min_qty = '';
                    $max_qty = '';
                }

                $product = get_post($product_id);
                if (!$product)
                    continue;

                $min_qty = isset($assoc['min_qty']) ? intval($assoc['min_qty']) : '0';
                $max_qty = isset($assoc['max_qty']) ? intval($assoc['max_qty']) : '';
                ?>
                <tr>
                    <td>
                        <input type="hidden" name="wc_pc_associated_products[product_id][]"
                            value="<?php echo esc_attr($product_id); ?>" />
                        <?php echo esc_html($product->post_title); ?>
                    </td>
                    <td><input type="number" name="wc_pc_associated_products[min_qty][]"
                            value="<?php echo esc_attr($min_qty); ?>" min="0" style="width:70px;" /></td>
                    <td><input type="number" placeholder=<?php esc_html_e('Unlimited', 'woocommerce-product-composer') ?>
                            name="wc_pc_associated_products[max_qty][]" value="<?php echo esc_attr($max_qty); ?>" min="0"
                            style="width:95px;" /></td>
                    <td><button type="button"
                            class="button wc-pc-remove-row"><?php esc_html_e('Remove', 'woocommerce-product-composer'); ?></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr class="no-items">
                <td colspan="4"><?php esc_html_e('No associated products yet.', 'woocommerce-product-composer'); ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<div style="margin-bottom: 10px;">
    <select id="wc-pc-product-select" style="width: 50%; margin-right: 10px;">
        <option value=""><?php esc_html_e('Select a product...', 'woocommerce-product-composer'); ?></option>
        <?php foreach ($products as $product): ?>
            <option value="<?php echo esc_attr($product->ID); ?>"><?php echo esc_html($product->post_title); ?></option>
        <?php endforeach; ?>
    </select>
    <button type="button" class="button"
        id="wc-pc-add-product"><?php esc_html_e('Add Product', 'woocommerce-product-composer'); ?></button>
</div>