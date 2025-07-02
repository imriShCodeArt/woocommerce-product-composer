<?php
/**
 * Product Composer Section Template
 *
 * @var array $associated_products
 */

if (empty($associated_products)) {
    return;
}
?>

<div class="wc-pc-composer">
    <h3><?php esc_html_e('Build Your Package', 'woocommerce-product-composer'); ?></h3>
    <ul class="wc-pc-products" style="list-style: none; padding: 0;">
        <?php foreach ($associated_products as $item): ?>
            <?php
            $product = $item['product'];
            $min_qty = $item['min_qty'];
            $max_qty = $item['max_qty'];

            if (!$product instanceof \WC_Product) {
                continue;
            }

            $product_id = $product->get_id();
            ?>
            <li class="wc-pc-product" style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                <a href="<?php echo esc_url(get_permalink($product_id)); ?>"
                    style="display: inline-flex; align-items: center; gap: 10px; text-decoration: none;">
                    <?php echo $product->get_image('thumbnail', ['style' => 'width:50px; height:auto;']); ?>
                    <span style="font-weight: bold;"><?php echo esc_html($product->get_name()); ?></span>
                    <span><?php echo wp_kses_post($product->get_price_html()); ?></span>
                </a>
                <label style="display: inline-flex; align-items: center; gap: 5px; margin-left: auto;">
                    <input type="checkbox"
                        name="wc_pc_accessories[<?php echo esc_attr($product_id); ?>][selected]"
                        value="1" class="wc-pc-accessory-checkbox"
                        data-product-id="<?php echo esc_attr($product_id); ?>" />
                    <?php esc_html_e('Add to package', 'woocommerce-product-composer'); ?>
                </label>
                <div class="ux-quantity quantity buttons_added" style="margin-left: 10px;">
                    <input type="button" value="-"
                        class="ux-quantity__button ux-quantity__button--minus button minus is-form"
                        data-product-id="<?php echo esc_attr($product_id); ?>">

                    <label class="screen-reader-text" for="wc_pc_accessory_qty_<?php echo esc_attr($product_id); ?>">
                        <?php esc_html_e('Quantity', 'woocommerce-product-composer'); ?>
                    </label>

                    <input step="1" type="number" id="wc_pc_accessory_qty_<?php echo esc_attr($product_id); ?>"
                        name="wc_pc_accessories[<?php echo esc_attr($product_id); ?>][quantity]"
                        value="<?php echo esc_attr(max(1, $min_qty)); ?>"
                        min="<?php echo esc_attr($min_qty); ?>"
                        <?php if (!empty($max_qty)) : ?>
                            max="<?php echo esc_attr($max_qty); ?>"
                        <?php endif; ?>
                        style="width: 60px;" class="input-text qty text wc-pc-accessory-qty"
                        data-product-id="<?php echo esc_attr($product_id); ?>">

                    <input type="button" value="+" class="ux-quantity__button ux-quantity__button--plus button plus is-form"
                        data-product-id="<?php echo esc_attr($product_id); ?>">
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
