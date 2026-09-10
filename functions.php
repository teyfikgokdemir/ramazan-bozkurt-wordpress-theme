<?php
if (!defined('ABSPATH')) { exit; }

function rb_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('custom-logo', [
        'height' => 160,
        'width' => 420,
        'flex-height' => true,
        'flex-width' => true,
    ]);
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    add_theme_support('woocommerce', [
        'thumbnail_image_width' => 720,
        'single_image_width'    => 1100,
        'product_grid' => [
            'default_rows'    => 3,
            'min_rows'        => 1,
            'max_rows'        => 8,
            'default_columns' => 3,
            'min_columns'     => 1,
            'max_columns'     => 4,
        ],
    ]);
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    register_nav_menus([
        'primary' => __('Ana Menü', 'ramazan-bozkurt'),
        'footer'  => __('Footer Menü', 'ramazan-bozkurt'),
    ]);
}
add_action('after_setup_theme', 'rb_theme_setup');

function rb_enqueue_assets() {
    $version = wp_get_theme()->get('Version');
    wp_enqueue_style('rb-style', get_stylesheet_uri(), [], $version);
    wp_enqueue_style('rb-hero-fix', get_template_directory_uri() . '/assets/css/hero-fix.css', ['rb-style'], $version);
    wp_enqueue_script('rb-site', get_template_directory_uri() . '/assets/js/site.js', [], $version, true);
}
add_action('wp_enqueue_scripts', 'rb_enqueue_assets');

function rb_get_media_url_by_slug($slug) {
    $attachment = get_page_by_path($slug, OBJECT, 'attachment');
    if ($attachment) {
        return wp_get_attachment_image_url($attachment->ID, 'full');
    }

    $query = new WP_Query([
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'posts_per_page' => 1,
        'name'           => sanitize_title($slug),
        'fields'         => 'ids',
    ]);

    if (!empty($query->posts[0])) {
        return wp_get_attachment_image_url((int) $query->posts[0], 'full');
    }

    return '';
}

function rb_media($slug, $fallback = '') {
    $url = rb_get_media_url_by_slug($slug);
    return $url ?: $fallback;
}

function rb_media_first(array $slugs) {
    foreach ($slugs as $slug) {
        $url = rb_media($slug);
        if ($url) { return $url; }
    }
    return '';
}

function rb_product_category_url($slug) {
    if (!taxonomy_exists('product_cat')) { return home_url('/urunler'); }
    $term = get_term_by('slug', $slug, 'product_cat');
    if (!$term || is_wp_error($term)) { return home_url('/urunler'); }
    $url = get_term_link($term);
    return is_wp_error($url) ? home_url('/urunler') : $url;
}

function rb_seed_site_structure() {
    if (get_option('rb_site_seed_v2')) { return; }

    $pages = [
        'hakkimizda' => ['Hakkımızda', 'Kayseri’nin köklü lezzet kültürünü çağdaş sunum anlayışıyla buluşturan Ramazan Bozkurt Et Ürünleri’ni, üretim yaklaşımımızı ve marka hikâyemizi keşfedin.'],
        'iletisim'   => ['İletişim', 'Ramazan Bozkurt Et Ürünleri ile perakende ve toptan satış, ürün bilgisi ve iş birliği konularında iletişime geçin.'],
    ];

    foreach ($pages as $slug => $data) {
        if (!get_page_by_path($slug)) {
            wp_insert_post([
                'post_type'    => 'page',
                'post_status'  => 'publish',
                'post_title'   => $data[0],
                'post_name'    => $slug,
                'post_content' => '<p>' . esc_html($data[1]) . '</p>',
            ]);
        }
    }

    if (class_exists('WooCommerce') && taxonomy_exists('product_cat')) {
        $categories = [
            'pastirma' => ['Pastırma', 'Kayseri pastırması; kendine özgü aroması, dilim yapısı ve geleneksel sofralardaki yeriyle Ramazan Bozkurt Et Ürünleri seçkisinin temel ürün gruplarından biridir. Ürün seçeneklerini, sunum biçimlerini ve güncel satış alternatiflerini inceleyin.'],
            'sucuk'    => ['Sucuk', 'Kayseri sucukları; karakteristik baharat profili ve geleneksel lezzet beklentisini modern sunumla bir araya getirir. Ramazan Bozkurt sucuk seçeneklerini keşfedin.'],
            'kavurma'  => ['Kavurma', 'Kavurma seçkimiz; pratik servis, dengeli doku ve güçlü et lezzeti arayan sofralar için hazırlanır. Ramazan Bozkurt kavurma ürünlerini ve güncel seçenekleri inceleyin.'],
            'manti'    => ['Mantı', 'Kayseri mutfağının simge lezzetlerinden mantı; geleneksel sofra kültürünün vazgeçilmezlerinden biridir. Ramazan Bozkurt mantı seçeneklerini keşfedin.'],
        ];

        foreach ($categories as $slug => $data) {
            $term = get_term_by('slug', $slug, 'product_cat');
            if (!$term) {
                wp_insert_term($data[0], 'product_cat', [
                    'slug' => $slug,
                    'description' => $data[1],
                ]);
            } elseif (empty($term->description)) {
                wp_update_term($term->term_id, 'product_cat', ['description' => $data[1]]);
            }
        }
    }

    if (!has_nav_menu('primary')) {
        $menu_id = wp_create_nav_menu('Ana Menü');
        if (!is_wp_error($menu_id)) {
            wp_update_nav_menu_item($menu_id, 0, [
                'menu-item-title' => 'Ana Sayfa',
                'menu-item-url' => home_url('/'),
                'menu-item-status' => 'publish',
            ]);
            $shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/urunler');
            wp_update_nav_menu_item($menu_id, 0, [
                'menu-item-title' => 'Ürünler',
                'menu-item-url' => $shop_url,
                'menu-item-status' => 'publish',
            ]);
            foreach (['hakkimizda' => 'Hakkımızda', 'iletisim' => 'İletişim'] as $slug => $title) {
                $page = get_page_by_path($slug);
                if ($page) {
                    wp_update_nav_menu_item($menu_id, 0, [
                        'menu-item-title' => $title,
                        'menu-item-object' => 'page',
                        'menu-item-object-id' => $page->ID,
                        'menu-item-type' => 'post_type',
                        'menu-item-status' => 'publish',
                    ]);
                }
            }
            $locations = get_theme_mod('nav_menu_locations', []);
            $locations['primary'] = $menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }
    }

    update_option('rb_site_seed_v2', 1);
}
add_action('admin_init', 'rb_seed_site_structure', 30);

function rb_has_seo_plugin() {
    return defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION') || defined('SEOPRESS_VERSION') || defined('AIOSEO_VERSION');
}

function rb_meta_description() {
    if (is_front_page()) {
        return 'Ramazan Bozkurt Et Ürünleri: Kayseri pastırması, sucuk, kavurma ve mantı seçenekleri. Geleneksel Kayseri lezzetlerini keşfedin; perakende ve toptan satış için iletişime geçin.';
    }
    if (function_exists('is_shop') && is_shop()) {
        return 'Ramazan Bozkurt Et Ürünleri mağazasında Kayseri pastırması, sucuk, kavurma ve mantı seçeneklerini inceleyin.';
    }
    if (is_tax('product_cat')) {
        $term = get_queried_object();
        if ($term && !empty($term->description)) { return wp_strip_all_tags($term->description); }
    }
    if (is_singular('product')) {
        $excerpt = get_the_excerpt();
        if ($excerpt) { return wp_strip_all_tags($excerpt); }
        return get_the_title() . ' hakkında ürün bilgisi, satış ve teslimat seçeneklerini Ramazan Bozkurt Et Ürünleri’nde inceleyin.';
    }
    if (is_singular()) {
        $excerpt = get_the_excerpt();
        if ($excerpt) { return wp_strip_all_tags($excerpt); }
    }
    return get_bloginfo('description');
}

function rb_output_seo_meta() {
    if (rb_has_seo_plugin()) { return; }
    $description = trim(wp_strip_all_tags(rb_meta_description()));
    if ($description) {
        echo '<meta name="description" content="' . esc_attr(wp_trim_words($description, 28, '')) . '">' . "\n";
    }
    $canonical = is_singular() ? wp_get_canonical_url() : '';
    if (!$canonical && is_front_page()) { $canonical = home_url('/'); }
    if ($canonical) { echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "\n"; }

    $title = wp_get_document_title();
    $image = rb_media_first(['ramazan-bozkurt-kayseri-aile-sofrasi-banner','ramazan-bozkurt-kayseri-geleneksel-lezzet-banner']);
    if (is_singular() && has_post_thumbnail()) { $image = get_the_post_thumbnail_url(get_queried_object_id(), 'full'); }
    echo '<meta property="og:locale" content="tr_TR">' . "\n";
    echo '<meta property="og:type" content="' . (is_singular('product') ? 'product' : 'website') . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr(wp_trim_words($description, 28, '')) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url(home_url(add_query_arg([], $GLOBALS['wp']->request ?? ''))) . '">' . "\n";
    if ($image) { echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n"; }
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
}
add_action('wp_head', 'rb_output_seo_meta', 2);

function rb_output_schema() {
    if (rb_has_seo_plugin()) { return; }
    $logo = rb_media_first(['ramazan-bozkurt-et-urunleri-kayseri-logo','ramazan-bozkurt-et-urunleri-kayseri-logo-1']);
    $graph = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Organization',
                '@id' => home_url('/#organization'),
                'name' => 'Ramazan Bozkurt Et Ürünleri',
                'url' => home_url('/'),
                'logo' => $logo ? ['@type' => 'ImageObject', 'url' => $logo] : null,
            ],
            [
                '@type' => 'WebSite',
                '@id' => home_url('/#website'),
                'url' => home_url('/'),
                'name' => 'Ramazan Bozkurt Et Ürünleri',
                'inLanguage' => 'tr-TR',
                'publisher' => ['@id' => home_url('/#organization')],
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => home_url('/?s={search_term_string}'),
                    'query-input' => 'required name=search_term_string',
                ],
            ],
        ],
    ];
    $graph['@graph'][0] = array_filter($graph['@graph'][0]);
    echo '<script type="application/ld+json">' . wp_json_encode($graph, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'rb_output_schema', 30);

function rb_body_classes($classes) {
    if (class_exists('WooCommerce')) { $classes[] = 'rb-woocommerce-ready'; }
    return $classes;
}
add_filter('body_class', 'rb_body_classes');

add_filter('woocommerce_enqueue_styles', function($styles) {
    return $styles;
});

add_filter('loop_shop_per_page', function() { return 12; }, 20);
add_filter('loop_shop_columns', function() { return 3; }, 20);

add_filter('woocommerce_output_related_products_args', function($args) {
    $args['posts_per_page'] = 3;
    $args['columns'] = 3;
    return $args;
});

function rb_cart_count() {
    return (function_exists('WC') && WC()->cart) ? WC()->cart->get_cart_contents_count() : 0;
}

function rb_cart_fragments($fragments) {
    ob_start();
    ?><span class="rb-cart-count"><?php echo esc_html(rb_cart_count()); ?></span><?php
    $fragments['.rb-cart-count'] = ob_get_clean();
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'rb_cart_fragments');
