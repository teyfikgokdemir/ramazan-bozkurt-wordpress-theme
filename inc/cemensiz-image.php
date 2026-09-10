<?php
/**
 * Assign the client-uploaded cemensiz.webp media item to Çemensiz Pastırma.
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
