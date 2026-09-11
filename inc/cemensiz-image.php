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
 * Ramazan Bey confirmed that the Sırt and Antrikot image sets were reversed.
 * Swap both the main image and gallery sets once, without touching prices,
 * variations or product content.
 */
function rb_swap_sirt_antrikot_images_v1() {
    if (get_option('rb_swap_sirt_antrikot_images_v1')) { return; }

    $sirt = get_page_by_path('kayseri-pastirmasi-250-g', OBJECT, 'product');
    $antrikot = get_page_by_path('antrikot-pastirma', OBJECT, 'product');
    if (!$sirt || !$antrikot) { return; }

    $sirt_images = [
        'ramazan-bozkurt-kayseri-pastirmasi-premium-sunum',
        'ramazan-bozkurt-pastirma-makro-detay',
        'ramazan-bozkurt-pastirma-dilim-detay-1',
    ];
    $antrikot_images = [
        'ramazan-bozkurt-kayseri-pastirmasi-dilimli-sunum',
        'ramazan-bozkurt-pastirma-kesit-detay',
        'ramazan-bozkurt-pastirma-dilim-detay',
    ];

    $apply_images = function($product_id, array $slugs) {
        $main = rb_media_id_first([$slugs[0]]);
        if ($main) { set_post_thumbnail((int) $product_id, $main); }

        $gallery = [];
        foreach (array_slice($slugs, 1) as $slug) {
            $image_id = rb_media_id_first([$slug]);
            if ($image_id && $image_id !== $main) { $gallery[] = $image_id; }
        }
        update_post_meta((int) $product_id, '_product_image_gallery', implode(',', $gallery));
        if (function_exists('wc_delete_product_transients')) {
            wc_delete_product_transients((int) $product_id);
        }
    };

    $apply_images((int) $sirt->ID, $sirt_images);
    $apply_images((int) $antrikot->ID, $antrikot_images);

    update_option('rb_swap_sirt_antrikot_images_v1', 1);
}
add_action('admin_init', 'rb_swap_sirt_antrikot_images_v1', 200);
