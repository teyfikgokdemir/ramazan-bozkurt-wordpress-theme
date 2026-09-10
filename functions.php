<?php
if (!defined('ABSPATH')) { exit; }

function rb_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height' => 120,
        'width' => 320,
        'flex-height' => true,
        'flex-width' => true,
    ]);
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    add_theme_support('woocommerce');

    register_nav_menus([
        'primary' => __('Ana Menü', 'ramazan-bozkurt'),
        'footer'  => __('Footer Menü', 'ramazan-bozkurt'),
    ]);
}
add_action('after_setup_theme', 'rb_theme_setup');

function rb_enqueue_assets() {
    wp_enqueue_style('rb-style', get_stylesheet_uri(), [], wp_get_theme()->get('Version'));
}
add_action('wp_enqueue_scripts', 'rb_enqueue_assets');

function rb_get_media_url_by_slug($slug) {
    $attachment = get_page_by_path($slug, OBJECT, 'attachment');
    if ($attachment) {
        return wp_get_attachment_image_url($attachment->ID, 'full');
    }
    return '';
}
