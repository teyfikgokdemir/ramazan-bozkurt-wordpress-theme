<?php
/**
 * Growth OS audit hardening layer.
 * Keeps titles, descriptions and canonicals unique across public page types
 * without overriding a dedicated SEO plugin when one is active.
 */
defined('ABSPATH') || exit;

function rb_audit_has_seo_plugin() {
    return function_exists('rb_has_seo_plugin') && rb_has_seo_plugin();
}

function rb_audit_page_slug() {
    if (!is_page()) { return ''; }
    $post = get_queried_object();
    return ($post && !empty($post->post_name)) ? (string) $post->post_name : '';
}

function rb_audit_title() {
    $brand = 'Ramazan Bozkurt';

    if (is_front_page()) {
        return 'Ramazan Bozkurt | Kayseri Pastırması, Sucuk ve Kavurma';
    }
    if (function_exists('is_shop') && is_shop()) {
        return 'Kayseri Pastırması, Sucuk ve Kavurma | Ramazan Bozkurt';
    }
    if (is_page('toptan-satis')) {
        return 'Toptan Et Ürünleri ve Kurumsal Tedarik | Ramazan Bozkurt';
    }
    if (is_page('blog') || is_home()) {
        return 'Kayseri Et Ürünleri Rehberi ve Blog | Ramazan Bozkurt';
    }
    if (is_page('yemek-tarifleri')) {
        return 'Kayseri Yemek Tarifleri | Ramazan Bozkurt';
    }
    if (is_page('hakkimizda')) {
        return 'Hakkımızda | Ramazan Bozkurt Et ve Et Mamulleri';
    }
    if (is_page('iletisim')) {
        return 'İletişim | Ramazan Bozkurt Kayseri';
    }
    if (is_page('sikca-sorulan-sorular')) {
        return 'Sıkça Sorulan Sorular | Ramazan Bozkurt';
    }
    if (is_page('kargo-ve-teslimat')) {
        return 'Kargo ve Teslimat | Ramazan Bozkurt';
    }
    if (is_tax('product_cat')) {
        $term = get_queried_object();
        return ($term && !empty($term->name)) ? $term->name . ' | Kayseri Et Ürünleri | ' . $brand : 'Ürünler | ' . $brand;
    }
    if (is_singular('product')) {
        return get_the_title(get_queried_object_id()) . ' | ' . $brand;
    }
    if (is_singular('post')) {
        return get_the_title(get_queried_object_id()) . ' | ' . $brand;
    }
    if (is_page()) {
        return get_the_title(get_queried_object_id()) . ' | ' . $brand;
    }
    if (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        return ($term && !empty($term->name)) ? $term->name . ' | ' . $brand : $brand;
    }
    if (is_archive()) {
        return wp_strip_all_tags(get_the_archive_title()) . ' | ' . $brand;
    }

    return $brand . ' | Kayseri Et ve Et Mamulleri';
}

function rb_audit_document_title_parts($parts) {
    if (rb_audit_has_seo_plugin()) { return $parts; }
    $parts['title'] = rb_audit_title();
    unset($parts['tagline'], $parts['site']);
    return $parts;
}
add_filter('document_title_parts', 'rb_audit_document_title_parts', 100);

function rb_audit_description() {
    if (is_front_page()) {
        return 'Ramazan Bozkurt Et ve Et Mamulleri; Kayseri pastırması, sucuk, kavurma ve mantıda perakende sipariş ile işletmelere kurumsal tedarik çözümleri sunar.';
    }
    if (function_exists('is_shop') && is_shop()) {
        return 'Sırt, antrikot, tütünlük ve çemensiz pastırma ile Kayseri sucuğu, kavurma ve mantı ürünlerini gramaj ve güncel fiyat seçenekleriyle inceleyin.';
    }
    if (is_page('toptan-satis')) {
        return 'Market, şarküteri, restoran, otel ve düzenli alım yapan işletmelere Kayseri pastırması, sucuk, kavurma ve mantıda kurumsal tedarik ve toptan satış teklifi.';
    }
    if (is_page('blog') || is_home()) {
        return 'Kayseri pastırması, sucuk, kavurma ve mantı hakkında saklama, servis, pişirme ve ürün seçimi rehberlerini Ramazan Bozkurt blogunda inceleyin.';
    }
    if (is_page('yemek-tarifleri')) {
        return 'Pastırma, sucuk, kavurma ve Kayseri mantısıyla hazırlanabilecek tarifleri, pişirme önerilerini ve servis fikirlerini keşfedin.';
    }
    if (is_page('hakkimizda')) {
        return 'Ramazan Bozkurt Et ve Et Mamulleri’nin Kayseri merkezli marka yaklaşımını, ürün gruplarını ve perakende ile kurumsal tedarik modelini inceleyin.';
    }
    if (is_page('iletisim')) {
        return 'Ramazan Bozkurt Et ve Et Mamulleri ile ürün, sipariş, toptan satış ve kurumsal tedarik için iletişime geçin. Talas, Kayseri iletişim bilgileri.';
    }
    if (is_page('sikca-sorulan-sorular')) {
        return 'Ramazan Bozkurt ürünleri, gramaj seçenekleri, kargo, sipariş ve toptan satış süreçleri hakkında sık sorulan soruların yanıtlarını inceleyin.';
    }
    if (is_page('kargo-ve-teslimat')) {
        return 'Ramazan Bozkurt siparişlerinde kargo, ücretsiz kargo limiti, hazırlık ve teslimat süreçleri hakkında güncel bilgileri inceleyin.';
    }
    if (is_tax('product_cat')) {
        $term = get_queried_object();
        if ($term && !empty($term->description)) {
            return wp_html_excerpt(wp_strip_all_tags($term->description), 155, '…');
        }
        if ($term && !empty($term->name)) {
            return $term->name . ' çeşitlerini güncel gramaj, fiyat ve sipariş seçenekleriyle Ramazan Bozkurt mağazasında inceleyin.';
        }
    }
    if (is_singular('product')) {
        $post_id = get_queried_object_id();
        $excerpt = trim(wp_strip_all_tags(get_post_field('post_excerpt', $post_id)));
        return $excerpt ? wp_html_excerpt($excerpt, 155, '…') : get_the_title($post_id) . ' gramaj, fiyat, ürün bilgisi ve sipariş seçeneklerini inceleyin.';
    }
    if (is_singular()) {
        $post_id = get_queried_object_id();
        $excerpt = trim(wp_strip_all_tags(get_post_field('post_excerpt', $post_id)));
        if ($excerpt) { return wp_html_excerpt($excerpt, 155, '…'); }
        $content = trim(wp_strip_all_tags(strip_shortcodes(get_post_field('post_content', $post_id))));
        $prefix = get_the_title($post_id) . ': ';
        return wp_html_excerpt($prefix . $content, 155, '…');
    }
    if (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        $name = ($term && !empty($term->name)) ? $term->name : 'İçerikler';
        return $name . ' hakkında Ramazan Bozkurt tarafından hazırlanan güncel içerikleri ve rehberleri inceleyin.';
    }

    return 'Ramazan Bozkurt Et ve Et Mamulleri’nin Kayseri pastırması, sucuk, kavurma, mantı, tarif ve kurumsal tedarik içeriklerini keşfedin.';
}

function rb_audit_canonical_url() {
    if (is_front_page()) { return home_url('/'); }
    if (function_exists('is_shop') && is_shop()) { return wc_get_page_permalink('shop'); }
    if (is_singular()) {
        $url = get_permalink(get_queried_object_id());
        return $url ? $url : home_url('/');
    }
    if (is_home()) {
        $posts_page = (int) get_option('page_for_posts');
        return $posts_page ? get_permalink($posts_page) : home_url('/blog/');
    }
    if (is_tax() || is_category() || is_tag()) {
        $url = get_term_link(get_queried_object());
        if (!is_wp_error($url)) {
            $paged = max(1, (int) get_query_var('paged'));
            return $paged > 1 ? get_pagenum_link($paged) : $url;
        }
    }
    if (is_archive()) {
        $paged = max(1, (int) get_query_var('paged'));
        return get_pagenum_link($paged);
    }
    global $wp;
    $path = isset($wp->request) ? trailingslashit($wp->request) : '';
    return home_url('/' . ltrim($path, '/'));
}

function rb_audit_output_meta() {
    if (rb_audit_has_seo_plugin()) { return; }

    $description = trim(wp_strip_all_tags(rb_audit_description()));
    $url = rb_audit_canonical_url();
    $title = rb_audit_title();
    $image = function_exists('rb_final_social_image') ? rb_final_social_image() : '';

    if ($description) { echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n"; }
    if ($url) { echo '<link rel="canonical" href="' . esc_url($url) . '">' . "\n"; }
    echo '<meta property="og:locale" content="tr_TR">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
    echo '<meta property="og:type" content="' . (is_singular('product') ? 'product' : (is_singular('post') ? 'article' : 'website')) . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
    if ($description) { echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n"; }
    if ($url) { echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n"; }
    if ($image) {
        echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
        echo '<meta property="og:image:secure_url" content="' . esc_url($image) . '">' . "\n";
    }
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
    if ($description) { echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n"; }
    if ($image) { echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . "\n"; }
}

if (function_exists('rb_final_output_meta')) {
    remove_action('wp_head', 'rb_final_output_meta', 2);
}
add_action('wp_head', 'rb_audit_output_meta', 2);
