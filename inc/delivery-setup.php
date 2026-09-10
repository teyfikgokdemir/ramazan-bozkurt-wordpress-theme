<?php
/**
 * Delivery-ready site setup.
 * Runs once and keeps future client edits intact.
 */
defined('ABSPATH') || exit;

function rb_find_attachment_id_by_slug($slug) {
    $attachment = get_page_by_path(sanitize_title($slug), OBJECT, 'attachment');
    if ($attachment) { return (int) $attachment->ID; }

    $query = new WP_Query([
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'posts_per_page' => 1,
        'name'           => sanitize_title($slug),
        'fields'         => 'ids',
    ]);
    return !empty($query->posts[0]) ? (int) $query->posts[0] : 0;
}

function rb_ensure_page($slug, $title, $content = '') {
    $page = get_page_by_path($slug, OBJECT, 'page');
    if ($page) { return (int) $page->ID; }

    return (int) wp_insert_post([
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_title'   => $title,
        'post_name'    => $slug,
        'post_content' => $content,
    ]);
}

function rb_menu_has_object($menu_id, $object_id, $type = 'post_type') {
    $items = wp_get_nav_menu_items($menu_id);
    if (!$items) { return false; }
    foreach ($items as $item) {
        if ($item->type === $type && (int) $item->object_id === (int) $object_id) { return true; }
    }
    return false;
}

function rb_delivery_setup_v4() {
    if (get_option('rb_delivery_setup_v4')) { return; }

    // Clean only untouched WordPress demo content.
    $sample = get_page_by_path('sample-page', OBJECT, 'page');
    if ($sample && trim(wp_strip_all_tags($sample->post_content)) === 'This is an example page. It’s different from a blog post because it will stay in one place and will show up in your site navigation (