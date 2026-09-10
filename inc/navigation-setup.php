<?php
/**
 * Premium navigation setup.
 * Creates a clean sales-focused menu once, then only fills missing content-hub links.
 */
defined('ABSPATH') || exit;

function rb_navigation_setup_v1() {
    if (get_option('rb_navigation_setup_v1')) { return; }

    $locations = get_theme_mod('nav_menu_locations', []);
    $menu_id = !empty($locations['primary']) ? (int) $locations['primary'] : 0;
    if (!$menu_id) {
        $created = wp_create_nav_menu('Ana Menü');
        if (is_wp_error($created)) { return; }
        $menu_id = (int) $created;
        $locations['primary'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }

    $existing_items = wp_get_nav_menu_items($menu_id);
    if ($existing_items) {
        foreach ($existing_items as $item) { wp_delete_post($item->ID, true); }
    }

    $home_id = (int) get_option('page_on_front');
    $shop_id = function_exists('wc_get_page_id') ? (int) wc_get_page_id('shop') : 0;
    $wholesale = get_page_by_path('toptan-satis', OBJECT, 'page');
    $about = get_page_by_path('hakkimizda', OBJECT, 'page');
    $contact = get_page_by_path('iletisim', OBJECT, 'page');
    $blog = get_page_by_path('blog', OBJECT, 'page');
    $recipes = get_page_by_path('yemek-tarifleri', OBJECT, 'page');

    $add_page = function($page_id, $title, $parent = 0) use ($menu_id) {
        if (!$page_id) { return 0; }
        $item_id = wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-title' => $title,
            'menu-item-object' => 'page',
            'menu-item-object-id' => (int) $page_id,
            'menu-item-type' => 'post_type',
            'menu-item-parent-id' => (int) $parent,
            'menu-item-status' => 'publish',
        ]);
        return is_wp_error($item_id) ? 0 : (int) $item_id;
    };

    $add_url = function($url, $title, $parent = 0) use ($menu_id) {
        $item_id = wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-title' => $title,
            'menu-item-url' => $url,
            'menu-item-type' => 'custom',
            'menu-item-parent-id' => (int) $parent,
            'menu-item-status' => 'publish',
        ]);
        return is_wp_error($item_id) ? 0 : (int) $item_id;
    };

    $add_page($home_id, 'Ana Sayfa');
    $products_parent = $add_page($shop_id, 'Ürünler');

    if ($products_parent && taxonomy_exists('product_cat')) {
        foreach (['pastirma'=>'Pastırma','sucuk'=>'Sucuk','kavurma'=>'Kavurma','manti'=>'Mantı'] as $slug => $label) {
            $term = get_term_by('slug', $slug, 'product_cat');
            if ($term && !is_wp_error($term)) {
                $url = get_term_link($term);
                if (!is_wp_error($url)) { $add_url($url, $label, $products_parent); }
            }
        }
    }

    if ($wholesale) { $add_page($wholesale->ID, 'Toptan Satış'); }
    if ($recipes) { $add_page($recipes->ID, 'Yemek Tarifleri'); }
    if ($blog) { $add_page($blog->ID, 'Blog'); }
    if ($about) { $add_page($about->ID, 'Hakkımızda'); }
    if ($contact) { $add_page($contact->ID, 'İletişim'); }

    update_option('rb_navigation_setup_v1', 1);
}
add_action('admin_init', 'rb_navigation_setup_v1', 140);

function rb_navigation_content_hub_v2() {
    if (get_option('rb_navigation_content_hub_v2')) { return; }
    $locations = get_theme_mod('nav_menu_locations', []);
    $menu_id = !empty($locations['primary']) ? (int) $locations['primary'] : 0;
    if (!$menu_id) { return; }

    $items = wp_get_nav_menu_items($menu_id) ?: [];
    $object_ids = array_map(function($item){ return (int) $item->object_id; }, $items);

    foreach (['yemek-tarifleri'=>'Yemek Tarifleri','blog'=>'Blog'] as $slug => $title) {
        $page = get_page_by_path($slug, OBJECT, 'page');
        if (!$page || in_array((int) $page->ID, $object_ids, true)) { continue; }
        wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-title' => $title,
            'menu-item-object' => 'page',
            'menu-item-object-id' => (int) $page->ID,
            'menu-item-type' => 'post_type',
            'menu-item-status' => 'publish',
        ]);
    }

    update_option('rb_navigation_content_hub_v2', 1);
}
add_action('admin_init', 'rb_navigation_content_hub_v2', 150);
