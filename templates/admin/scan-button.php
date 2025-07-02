<?php
/**
 * Scan Components button
 *
 * @var int $product_id
 */
?>

<p>
    <button type="button" class="button button-primary" id="wc_pc_scan_components_button"
        data-product-id="<?php echo esc_attr($product_id); ?>">
        <?php esc_html_e('Scan Components Automatically', 'woocommerce-product-composer'); ?>
    </button>
</p>