<?php
/**
 * Final SEO/technical layer. Replaces the lightweight fallback metadata only
 * when a dedicated SEO plugin is not active.
 */
defined('ABSPATH') || exit;

function rb_final_current_url() {
    if (is_front_page()) { return home_url('/'); }
    if (is_singular()) { $url = wp_get_canonical_url(); if ($url) { return $url; } }
    if (function_exists('is_shop') && is_shop()) { return wc_get_page_permalink('shop'); }
    if (is_tax() || is_category() || is_tag()) {
        $url = get_term_link(get_queried_object());
        if (!is_wp_error($url)) { return $url; }
    }
    if (is_home()) {
        $posts_page = (int) get_option('page_for_posts');
        return $posts_page ? get_permalink($posts_page) : home_url('/blog');
    }
    global $wp;
    return home_url(isset($wp->request) ? trailingslashit($wp->request) : '/');
}

function rb_final_meta_description() {
    if (is_front_page()) {
        return 'Ramazan Bozkurt Et ve Et Mamulleri; Kayseri pastırması, sucuk, kavurma ve mantı ürünlerinde perakende sipariş ve işletmelere kurumsal tedarik çözümleri sunar.';
    }
    if (function_exists('is_shop') && is_shop()) {
        return 'Ramazan Bozkurt mağazasında sırt, antrikot, tütünlük ve çemensiz pastırma ile Kayseri sucuğu, kavurma ve mantı ürünlerini gramaj ve güncel fiyatlarıyla inceleyin.';
    }
    if (is_page('toptan-satis')) {
        return 'Kayseri’den zincir market, yerel market, şarküteri, HORECA ve düzenli alım yapan işletmelere pastırma, sucuk, kavurma ve mantıda kurumsal tedarik ve toptan satış teklifi.';
    }
    if (is_page('hakkimizda')) {
        return 'Ramazan Bozkurt Et ve Et Mamulleri’nin Kayseri merkezli marka yaklaşımını, ürün gruplarını ve perakende ile kurumsal tedarik modelini inceleyin.';
    }
    if (is_page('iletisim')) {
        return 'Ramazan Bozkurt Et ve Et Mamulleri ile ürün, sipariş, toptan satış ve kurumsal tedarik için iletişime geçin. Kayseri Talas iletişim bilgileri.';
    }
    if (is_page('sikca-sorulan-sorular')) {
        return 'Ramazan Bozkurt ürünleri, gramaj seçenekleri, ücretsiz kargo limiti, sipariş ve toptan satış hakkında sık sorulan soruların yanıtları.';
    }
    if (is_tax('product_cat')) {
        $term = get_queried_object();
        if ($term && !empty($term->description)) { return wp_strip_all_tags($term->description); }
    }
    if (is_singular('product')) {
        $excerpt = get_the_excerpt();
        return $excerpt ? wp_strip_all_tags($excerpt) : get_the_title() . ' ürününün gramaj, fiyat, ürün bilgisi ve sipariş seçeneklerini inceleyin.';
    }
    if (is_singular()) {
        $excerpt = get_the_excerpt();
        if ($excerpt) { return wp_strip_all_tags($excerpt); }
        $content = get_post_field('post_content', get_queried_object_id());
        if ($content) { return wp_strip_all_tags(strip_shortcodes($content)); }
    }
    return get_bloginfo('description');
}

function rb_final_social_image() {
    return 'https://ramazanbozkurt.com/wp-content/uploads/2026/09/ramazan-bozkurt-et-urunleri-kayseri-logo-1.webp?v=20260911-2';
}

function rb_final_output_meta() {
    if (function_exists('rb_has_seo_plugin') && rb_has_seo_plugin()) { return; }
    $description = trim(wp_strip_all_tags(rb_final_meta_description()));
    $description = wp_trim_words($description, 30, '');
    $url = rb_final_current_url();
    $title = wp_get_document_title();
    $image = rb_final_social_image();

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

function rb_final_output_schema() {
    if (function_exists('rb_has_seo_plugin') && rb_has_seo_plugin()) { return; }

    $logo = rb_media_first(['ramazan-bozkurt-et-urunleri-kayseri-logo','ramazan-bozkurt-et-urunleri-kayseri-logo-1']);
    $phone = function_exists('rb_theme_setting') ? rb_theme_setting('phone', '+90 539 824 82 95') : '+90 539 824 82 95';
    $url = rb_final_current_url();
    $description = wp_trim_words(trim(wp_strip_all_tags(rb_final_meta_description())), 30, '');
    $image = rb_final_social_image();

    $organization = [
        '@type' => 'Organization',
        '@id' => home_url('/#organization'),
        'name' => 'Ramazan Bozkurt Et ve Et Mamulleri',
        'url' => home_url('/'),
        'telephone' => $phone,
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => 'Süleymanlı Mah. 6952. Sk. No: 47',
            'addressLocality' => 'Talas',
            'addressRegion' => 'Kayseri',
            'addressCountry' => 'TR',
        ],
        'contactPoint' => [[
            '@type' => 'ContactPoint',
            'telephone' => $phone,
            'contactType' => 'sales',
            'availableLanguage' => ['tr'],
        ]],
    ];
    if ($logo) { $organization['logo'] = ['@type'=>'ImageObject','url'=>$logo]; }

    $graph = [
        $organization,
        [
            '@type'=>'WebSite',
            '@id'=>home_url('/#website'),
            'url'=>home_url('/'),
            'name'=>'Ramazan Bozkurt Et ve Et Mamulleri',
            'inLanguage'=>'tr-TR',
            'publisher'=>['@id'=>home_url('/#organization')],
            'potentialAction'=>[
                '@type'=>'SearchAction',
                'target'=>['@type'=>'EntryPoint','urlTemplate'=>home_url('/?s={search_term_string}')],
                'query-input'=>'required name=search_term_string'
            ],
        ],
    ];

    if ($url && !is_front_page()) {
        $graph[] = [
            '@type'=>'WebPage',
            '@id'=>$url . '#webpage',
            'url'=>$url,
            'name'=>wp_get_document_title(),
            'description'=>$description,
            'inLanguage'=>'tr-TR',
            'isPartOf'=>['@id'=>home_url('/#website')],
            'about'=>['@id'=>home_url('/#organization')],
        ];
    }

    if (is_singular('post')) {
        $post_id = get_queried_object_id();
        $article = [
            '@type' => 'BlogPosting',
            '@id' => $url . '#article',
            'headline' => get_the_title($post_id),
            'description' => $description,
            'mainEntityOfPage' => ['@id' => $url . '#webpage'],
            'datePublished' => get_the_date(DATE_W3C, $post_id),
            'dateModified' => get_the_modified_date(DATE_W3C, $post_id),
            'inLanguage' => 'tr-TR',
            'author' => ['@type'=>'Organization','@id'=>home_url('/#organization'),'name'=>'Ramazan Bozkurt Et ve Et Mamulleri'],
            'publisher' => ['@id'=>home_url('/#organization')],
        ];
        if ($image) { $article['image'] = $image; }
        $cats = get_the_category($post_id);
        if ($cats) { $article['articleSection'] = wp_list_pluck($cats, 'name'); }
        $graph[] = $article;
    }

    if ($url && (is_singular() || is_tax('product_cat'))) {
        $items = [[
            '@type'=>'ListItem','position'=>1,'name'=>'Ana Sayfa','item'=>home_url('/')
        ]];
        if (is_singular('post')) {
            $cats = get_the_category();
            $is_recipe = false;
            foreach ($cats as $cat) { if ($cat->slug === 'yemek-tarifleri') { $is_recipe = true; break; } }
            $hub_url = $is_recipe ? home_url('/yemek-tarifleri/') : home_url('/blog/');
            $hub_name = $is_recipe ? 'Yemek Tarifleri' : 'Blog';
            $items[] = ['@type'=>'ListItem','position'=>2,'name'=>$hub_name,'item'=>$hub_url];
            $items[] = ['@type'=>'ListItem','position'=>3,'name'=>get_the_title(),'item'=>$url];
        } elseif (is_singular('product')) {
            $shop = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/urunler/');
            $items[] = ['@type'=>'ListItem','position'=>2,'name'=>'Ürünler','item'=>$shop];
            $items[] = ['@type'=>'ListItem','position'=>3,'name'=>get_the_title(),'item'=>$url];
        } elseif (is_tax('product_cat')) {
            $shop = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/urunler/');
            $term = get_queried_object();
            $items[] = ['@type'=>'ListItem','position'=>2,'name'=>'Ürünler','item'=>$shop];
            $items[] = ['@type'=>'ListItem','position'=>3,'name'=>$term ? $term->name : 'Kategori','item'=>$url];
        } else {
            $items[] = ['@type'=>'ListItem','position'=>2,'name'=>wp_get_document_title(),'item'=>$url];
        }
        $graph[] = ['@type'=>'BreadcrumbList','@id'=>$url.'#breadcrumb','itemListElement'=>$items];
    }

    if (is_page('sikca-sorulan-sorular')) {
        $graph[] = [
            '@type'=>'FAQPage',
            '@id'=>$url . '#faq',
            'mainEntity'=>[
                ['@type'=>'Question','name'=>'Hangi ürünler satılıyor?','acceptedAnswer'=>['@type'=>'Answer','text'=>'Pastırma çeşitleri, Kayseri sucuğu, kavurma ve Kayseri mantısı satışa sunulur.']],
                ['@type'=>'Question','name'=>'Pastırmada hangi gramajlar bulunuyor?','acceptedAnswer'=>['@type'=>'Answer','text'=>'Pastırma çeşitlerinde 250 g, 400 g, 700 g ve 1 kg seçenekleri bulunur.']],
                ['@type'=>'Question','name'=>'Toptan sipariş verebilir miyim?','acceptedAnswer'=>['@type'=>'Answer','text'=>'Evet. Market, şarküteri, HORECA ve düzenli alım yapan işletmeler için sipariş hacmine göre kurumsal teklif hazırlanır.']],
                ['@type'=>'Question','name'=>'Ücretsiz kargo limiti nedir?','acceptedAnswer'=>['@type'=>'Answer','text'=>number_format_i18n(function_exists('rb_free_shipping_threshold') ? rb_free_shipping_threshold() : 4000, 0) . ' TL ve üzeri perakende siparişlerde ücretsiz kargo uygulanır.']],
            ],
        ];
    }

    echo '<script type="application/ld+json">' . wp_json_encode(['@context'=>'https://schema.org','@graph'=>$graph], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}

function rb_final_robots($robots) {
    if ((function_exists('is_cart') && is_cart()) || (function_exists('is_checkout') && is_checkout()) || (function_exists('is_account_page') && is_account_page()) || is_search() || is_404()) {
        $robots['noindex'] = true;
        $robots['nofollow'] = true;
        unset($robots['index'], $robots['follow']);
    }
    return $robots;
}
add_filter('wp_robots', 'rb_final_robots');

function rb_final_robots_txt($output, $public) {
    if (!$public) { return $output; }
    $lines = [
        'User-agent: *',
        'Disallow: /wp-admin/',
        'Allow: /wp-admin/admin-ajax.php',
        'Disallow: /sepet/',
        'Disallow: /odeme/',
        'Disallow: /hesabim/',
        'Disallow: /*?add-to-cart=',
        'Sitemap: ' . home_url('/wp-sitemap.xml'),
    ];
    return implode("\n", $lines) . "\n";
}
add_filter('robots_txt', 'rb_final_robots_txt', 20, 2);

function rb_final_sitemap_exclusions($args, $post_type) {
    if ($post_type !== 'page') { return $args; }
    $exclude = [];
    if (function_exists('wc_get_page_id')) {
        foreach (['cart','checkout','myaccount'] as $page_key) {
            $id = (int) wc_get_page_id($page_key);
            if ($id > 0) { $exclude[] = $id; }
        }
    }
    if ($exclude) {
        $existing = isset($args['post__not_in']) && is_array($args['post__not_in']) ? $args['post__not_in'] : [];
        $args['post__not_in'] = array_values(array_unique(array_merge($existing, $exclude)));
    }
    return $args;
}
add_filter('wp_sitemaps_posts_query_args', 'rb_final_sitemap_exclusions', 20, 2);

/* Replace the earlier lightweight fallback after functions.php has registered it. */
if (function_exists('rb_output_seo_meta')) { remove_action('wp_head', 'rb_output_seo_meta', 2); }
if (function_exists('rb_output_schema')) { remove_action('wp_head', 'rb_output_schema', 30); }
add_action('wp_head', 'rb_final_output_meta', 2);
add_action('wp_head', 'rb_final_output_schema', 30);
