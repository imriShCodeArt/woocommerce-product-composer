<?php
/**
 * Product Composer Section Template
 *
 * @var WC_Product[] $associated_products
 */

if (empty($associated_products)) {
    return;
}

?>
<div class="wc-pc-composer">
    <h3><?php esc_html_e('Build Your Package', 'woocommerce-product-composer'); ?></h3>
    <ul class="wc-pc-products">
        <?php foreach ($associated_products as $product): ?>
            <li class="wc-pc-product">
                <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>">
                    <?php echo $product->get_image('thumbnail'); ?>
                    <p><?php echo esc_html($product->get_name()); ?></p>
                    <p><?php echo wp_kses_post($product->get_price_html()); ?></p>
                </a>
                <label>
                    <input type="checkbox" name="wc_pc_accessories[]"
                        value="<?php echo esc_attr($product->get_id()); ?>" />
                    <?php esc_html_e('Add to package', 'woocommerce-product-composer'); ?>
                </label>
            </li>
        <?php endforeach; ?>
    </ul>
</div>