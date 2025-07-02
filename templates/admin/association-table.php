<?php
echo '<table class="wc-pc-associated-table widefat">';
echo '<thead><tr><th>' . esc_html__('Product', 'woocommerce-product-composer') . '</th>';
echo '<th>' . esc_html__('Min Qty', 'woocommerce-product-composer') . '</th>';
echo '<th>' . esc_html__('Max Qty', 'woocommerce-product-composer') . '</th>';
echo '<th></th></tr></thead>';
echo '<tbody>';

if (!empty($assoc_products)) {
    foreach ($assoc_products as $assoc) {
        $product_id = intval($assoc['product_id']);
        $product = get_post($product_id);
        if (!$product)
            continue;
        $name = esc_html($product->post_title);
        $min = intval($assoc['min_qty']);
        $max = intval($assoc['max_qty']);

        echo '<tr>';
        echo '<td><input type="hidden" name="wc_pc_associated_products[product_id][]" value="' . $product_id . '" />' . $name . '</td>';
        echo '<td><input type="number" name="wc_pc_associated_products[min_qty][]" value="' . $min . '" min="0" style="width:70px;" /></td>';
        echo '<td><input type="number" name="wc_pc_associated_products[max_qty][]" value="' . ($max ?: '') . '" min="0" style="width:95px;" placeholder="' . esc_attr__('Unlimited', 'woocommerce-product-composer') . '" /></td>';
        echo '<td><button type="button" class="button wc-pc-remove-row">' . esc_html__('Remove', 'woocommerce-product-composer') . '</button></td>';
        echo '</tr>';
    }
} else {
    echo '<tr class="no-items"><td colspan="4">' . esc_html__('No associated products yet.', 'woocommerce-product-composer') . '</td></tr>';
}

echo '</tbody>';
echo '</table>';
