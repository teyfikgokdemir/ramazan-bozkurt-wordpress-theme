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
 * Confirmed from the live REST API on 2026-09-11:
 * - Product 81 = Sırt Pastırma; its correct main image is attachment 47.
 * - Product 124 = Antrikot Pastırma; its correct main image is attachment 48.
 *
 * Apply directly by IDs so media slug matching and previous migration flags
 * cannot reverse or block the correction.
 */
function rb_fix_sirt_antrikot_featured_images_v2() {
    if (get_option('rb_fix_sirt_antrikot_featured_images_v2')) { return; }

    $sirt = get_post(81);
    $antrikot = get_post(124);
    if (!$sirt || !$antrikot || $sirt->post_type !== 'product' || $antrikot->post_type !== 'product') { return; }

    set_post_thumbnail(81, 47);
    set_post_thumbnail(124, 48);

    clean_post_cache(81);
    clean_post_cache(124);
    if (function_exists('wc_delete_product_transients')) {
        wc_delete_product_transients(81);
        wc_delete_product_transients(124);
    }

    update_option('rb_fix_sirt_antrikot_featured_images_v2', 1);
}

/* This file is loaded by the front-end header as well as during normal site use,
 * so execute immediately once. This avoids relying on admin_init for the repair.
 */
rb_fix_sirt_antrikot_featured_images_v2();
