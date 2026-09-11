<?php
/**
 * Content/visual consistency layer for Blog + Yemek Tarifleri.
 * Uses exact post slugs instead of broad keyword matching so a sucuk article
 * can never silently fall back to mantı imagery.
 */
defined('ABSPATH') || exit;

function rb_content_visual_map() {
    return [
        'kayseri-pastirmasi-nasil-saklanir' => [
            'alt' => 'Kayseri pastırmasının saklama ve servis için hazırlanmış ürün görünümü',
            'media' => [
                'ramazan-bozkurt-kayseri-pastirmasi-premium-sunum',
                'ramazan-bozkurt-kayseri-pastirmasi-dilimli-sunum',
                'ramazan-bozkurt-pastirma-dilim-detay',
            ],
        ],
        'pastirma-nasil-servis-edilir' => [
            'alt' => 'Servise hazır ince dilim Kayseri pastırması sunumu',
            'media' => [
                'ramazan-bozkurt-kayseri-pastirmasi-dilimli-sunum',
                'ramazan-bozkurt-kayseri-pastirmasi-premium-sunum',
                'ramazan-bozkurt-pastirma-dilim-detay',
            ],
        ],
        'kayseri-sucugu-nasil-pisirilir' => [
            'alt' => 'Pişirmeye hazır Kayseri sucuğu dilimleri ve sucuk sunumu',
            'media' => [
                'ramazan-bozkurt-kayseri-sucugu-premium-sunum',
                'ramazan-bozkurt-kayseri-sucugu-urun-gorseli',
                'ramazan-bozkurt-kayseri-parmak-sucuk',
            ],
        ],
        'kavurmali-pilav-tarifi' => [
            'alt' => 'Kavurmalı pilav tarifinde kullanılan Kayseri kavurması sunumu',
            'media' => [
                'ramazan-bozkurt-kayseri-kavurma-urun-sunumu',
                'ramazan-bozkurt-kayseri-kavurma-premium-sunum',
                'ramazan-bozkurt-kavurma-kesit-detay',
            ],
        ],
        'kayseri-mantisi-nasil-hazirlanir' => [
            'alt' => 'Kayseri mantısının hazırlanışı ve geleneksel mantı sunumu',
            'media' => [
                'ramazan-bozkurt-kayseri-mantisi-geleneksel',
                'ramazan-bozkurt-kayseri-mantisi-el-yapimi',
                'ramazan-bozkurt-kayseri-mantisi-paket',
            ],
        ],
        'pastirmali-yumurta-tarifi' => [
            'alt' => 'Pastırmalı yumurta tarifi için ince dilim Kayseri pastırması',
            'media' => [
                'ramazan-bozkurt-kayseri-pastirmasi-dilimli-sunum',
                'ramazan-bozkurt-kayseri-pastirmasi-premium-sunum',
                'ramazan-bozkurt-pastirma-dilim-detay',
            ],
        ],
        'sucuklu-yumurta-tarifi' => [
            'alt' => 'Sucuklu yumurta tarifi için Kayseri sucuğu dilimleri',
            'media' => [
                'ramazan-bozkurt-kayseri-sucugu-premium-sunum',
                'ramazan-bozkurt-kayseri-sucugu-urun-gorseli',
                'ramazan-bozkurt-kayseri-parmak-sucuk',
            ],
        ],
    ];
}

function rb_content_visual_id_for_post($post_id) {
    $post = get_post($post_id);
    if (!$post || $post->post_type !== 'post') { return 0; }

    $map = rb_content_visual_map();
    $slug = (string) $post->post_name;
    if (empty($map[$slug]['media'])) { return 0; }

    foreach ($map[$slug]['media'] as $media_slug) {
        $attachment = get_page_by_path($media_slug, OBJECT, 'attachment');
        if ($attachment) { return (int) $attachment->ID; }

        $query = new WP_Query([
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'posts_per_page' => 1,
            'name' => sanitize_title($media_slug),
            'fields' => 'ids',
            'no_found_rows' => true,
        ]);
        if (!empty($query->posts[0])) { return (int) $query->posts[0]; }
    }

    return 0;
}

/**
 * Make every theme call that asks for the featured image use the audited,
 * exact-slug image. This covers blog cards, recipe cards, single articles and
 * related-content cards without relying on old cached featured-image choices.
 */
function rb_filter_content_thumbnail_id($thumbnail_id, $post) {
    $post_id = is_object($post) ? (int) $post->ID : (int) $post;
    if (!$post_id) { return $thumbnail_id; }

    $audited_id = rb_content_visual_id_for_post($post_id);
    return $audited_id ?: $thumbnail_id;
}
add_filter('post_thumbnail_id', 'rb_filter_content_thumbnail_id', 30, 2);

/**
 * One-time repair of already-created posts. This is intentionally a new
 * versioned migration so it also fixes posts seeded before this audit existed.
 */
function rb_repair_content_visuals_v2() {
    if (get_option('rb_content_visuals_v2')) { return; }

    foreach (rb_content_visual_map() as $slug => $config) {
        $post = get_page_by_path($slug, OBJECT, 'post');
        if (!$post) { continue; }

        $image_id = rb_content_visual_id_for_post($post->ID);
        if (!$image_id) { continue; }

        set_post_thumbnail($post->ID, $image_id);

        if (!empty($config['alt'])) {
            update_post_meta($image_id, '_wp_attachment_image_alt', sanitize_text_field($config['alt']));
        }
    }

    update_option('rb_content_visuals_v2', 1);
}
add_action('admin_init', 'rb_repair_content_visuals_v2', 120);
