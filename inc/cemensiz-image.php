<?php
/**
 * Product image corrections supplied by the client.
 */
defined('ABSPATH') || exit;

function rb_assign_cemensiz_product_image_v1() {
    if (get_option('rb_cemensiz_product_image_v1')) { return; }

    $product = get_page_by_path('cemensiz-pastirma', OBJECT, 'product');
    if (!$product) { return; }

    $image_id = rb_media_id_first(['cemensiz', 'cemensiz-pastirma', 'ramazan-bozkurt-cemensiz-pastirma']);
    if (!$image_id) { return; }

    set_post_thumbnail((int) $product->ID, $image_id);

    $current_gallery = array_filter(array_map('intval', explode(',', (string) get_post_meta($product->ID, '_product_image_gallery', true))));
    $current_gallery = array_values(array_diff($current_gallery, [$image_id]));
    update_post_meta($product->ID, '_product_image_gallery', implode(',', $current_gallery));

    if (function_exists('wc_delete_product_transients')) {
        wc_delete_product_transients((int) $product->ID);
    }

    update_option('rb_cemensiz_product_image_v1', 1);
}
add_action('admin_init', 'rb_assign_cemensiz_product_image_v1', 190);

/**
 * The Sırt and Antrikot images were finalized manually in WooCommerce.
 * Mark the old automatic swap jobs complete before they can run again so
 * the client's final main images and galleries are never overwritten.
 */
function rb_lock_manual_pastirma_images_v1() {
    update_option('rb_swap_sirt_antrikot_images_v1', 1);
    update_option('rb_fix_sirt_antrikot_featured_images_v2', 1);
}
add_action('admin_init', 'rb_lock_manual_pastirma_images_v1', 150);

/**
 * Owner request: set every sellable WooCommerce stock quantity to 2000.
 * Variable products are managed at variation level; simple products are managed directly.
 */
function rb_set_all_product_stock_2000_v1() {
    if (get_option('rb_set_all_product_stock_2000_v1')) { return; }
    if (!class_exists('WooCommerce') || !function_exists('wc_get_products')) { return; }

    $product_ids = wc_get_products([
        'status' => ['publish', 'private', 'draft'],
        'limit'  => -1,
        'return' => 'ids',
    ]);

    foreach ($product_ids as $product_id) {
        $product = wc_get_product((int) $product_id);
        if (!$product) { continue; }

        if ($product->is_type('variable')) {
            $product->set_manage_stock(false);
            $product->set_stock_status('instock');
            $product->save();

            foreach ($product->get_children() as $variation_id) {
                $variation = wc_get_product((int) $variation_id);
                if (!$variation) { continue; }
                $variation->set_manage_stock(true);
                $variation->set_stock_quantity(2000);
                $variation->set_stock_status('instock');
                $variation->save();
            }
        } else {
            $product->set_manage_stock(true);
            $product->set_stock_quantity(2000);
            $product->set_stock_status('instock');
            $product->save();
        }

        if (function_exists('wc_delete_product_transients')) {
            wc_delete_product_transients((int) $product_id);
        }
    }

    update_option('rb_set_all_product_stock_2000_v1', 1);
}
add_action('admin_init', 'rb_set_all_product_stock_2000_v1', 220);
