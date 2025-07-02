<?php
/**
 * Product select and Add Product button
 *
 * @var array $all_products
 */
?>

<p>
    <select id="wc-pc-product-select">
        <option value=""><?php esc_html_e('Select a product…', 'woocommerce-product-composer'); ?></option>
        <?php foreach ($all_products as $prod): ?>
            <option value="<?php echo esc_attr($prod->ID); ?>">
                <?php echo esc_html($prod->post_title); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="button" class="button" id="wc-pc-add-product">
        <?php esc_html_e('Add Product', 'woocommerce-product-composer'); ?>
    </button>
</p>