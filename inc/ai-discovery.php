<?php
/**
 * AI discovery and crawl-health layer.
 * Serves /llms.txt and /llms-full.txt from WordPress, keeps AI/search crawlers
 * explicitly allowed for public content, and adds a Site Health HTTP check.
 */
defined('ABSPATH') || exit;

function rb_ai_plain_response($content) {
    status_header(200);
    header('Content-Type: text/plain; charset=UTF-8');
    header('X-Robots-Tag: noindex, follow', true);
    header('Cache-Control: public, max-age=3600', true);
    echo $content;
    exit;
}

function rb_ai_url($path = '/') {
    return home_url($path);
}

function rb_ai_md_link($label, $url) {
    return '[' . str_replace([']','['], '', wp_strip_all_tags((string)$label)) . '](' . esc_url_raw($url) . ')';
}

function rb_ai_product_lines($limit = 20) {
    if (!post_type_exists('product')) { return []; }
    $ids = get_posts([
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => $limit,
        'orderby' => 'menu_order title',
        'order' => 'ASC',
        'fields' => 'ids',
        'no_found_rows' => true,
    ]);
    $lines = [];
    foreach ($ids as $id) {
        $url = get_permalink($id);
        if (!$url) { continue; }
        $title = wp_strip_all_tags(get_the_title($id));
        $summary = trim(wp_strip_all_tags(get_post_field('post_excerpt', $id)));
        $lines[] = '- ' . rb_ai_md_link($title, $url) . ($summary ? ' — ' . $summary : '');
    }
    return $lines;
}

function rb_ai_post_lines($category_slug, $limit = 12) {
    $cat = get_category_by_slug($category_slug);
    if (!$cat) { return []; }
    $ids = get_posts([
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $limit,
        'category' => (int) $cat->term_id,
        'orderby' => 'date',
        'order' => 'DESC',
        'fields' => 'ids',
        'no_found_rows' => true,
    ]);
    $lines = [];
    foreach ($ids as $id) {
        $url = get_permalink($id);
        if (!$url) { continue; }
        $title = wp_strip_all_tags(get_the_title($id));
        $excerpt = trim(wp_strip_all_tags(get_post_field('post_excerpt', $id)));
        if (!$excerpt) {
            $excerpt = wp_trim_words(wp_strip_all_tags(strip_shortcodes(get_post_field('post_content', $id))), 24, '…');
        }
        $lines[] = '- ' . rb_ai_md_link($title, $url) . ($excerpt ? ' — ' . $excerpt : '');
    }
    return $lines;
}

function rb_ai_llms_txt() {
    $shop = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : rb_ai_url('/urunler/');
    $pastirma = function_exists('rb_product_category_url') ? rb_product_category_url('pastirma') : rb_ai_url('/urun-kategori/pastirma/');
    $sucuk = function_exists('rb_product_category_url') ? rb_product_category_url('sucuk') : rb_ai_url('/urun-kategori/sucuk/');
    $kavurma = function_exists('rb_product_category_url') ? rb_product_category_url('kavurma') : rb_ai_url('/urun-kategori/kavurma/');
    $manti = function_exists('rb_product_category_url') ? rb_product_category_url('manti') : rb_ai_url('/urun-kategori/manti/');

    $lines = [
        '# Ramazan Bozkurt Et ve Et Mamulleri',
        '',
        '> Kayseri merkezli pastırma, sucuk, kavurma ve mantı markası. Perakende siparişin yanında market, şarküteri, HORECA ve düzenli alım yapan işletmelere kurumsal tedarik görüşmesi sunar.',
        '',
        '## Canonical site',
        '- ' . rb_ai_md_link('Ramazan Bozkurt Et ve Et Mamulleri', rb_ai_url('/')),
        '',
        '## Primary pages',
        '- ' . rb_ai_md_link('Online Mağaza', $shop),
        '- ' . rb_ai_md_link('Toptan Satış / Kurumsal Tedarik', rb_ai_url('/toptan-satis/')),
        '- ' . rb_ai_md_link('Yemek Tarifleri', rb_ai_url('/yemek-tarifleri/')),
        '- ' . rb_ai_md_link('Blog & Rehber', rb_ai_url('/blog/')),
        '- ' . rb_ai_md_link('Hakkımızda', rb_ai_url('/hakkimizda/')),
        '- ' . rb_ai_md_link('İletişim', rb_ai_url('/iletisim/')),
        '- ' . rb_ai_md_link('Sıkça Sorulan Sorular', rb_ai_url('/sikca-sorulan-sorular/')),
        '',
        '## Product categories',
        '- ' . rb_ai_md_link('Pastırma', $pastirma),
        '- ' . rb_ai_md_link('Sucuk', $sucuk),
        '- ' . rb_ai_md_link('Kavurma', $kavurma),
        '- ' . rb_ai_md_link('Mantı', $manti),
        '',
        '## Discovery',
        '- ' . rb_ai_md_link('XML Sitemap', rb_ai_url('/wp-sitemap.xml')),
        '- ' . rb_ai_md_link('Robots', rb_ai_url('/robots.txt')),
        '- ' . rb_ai_md_link('Extended AI reference', rb_ai_url('/llms-full.txt')),
        '',
        'Use canonical website pages as the primary source for current product, price, shipping and company information.',
        '',
    ];
    return implode("\n", $lines);
}

function rb_ai_llms_full_txt() {
    $phone = function_exists('rb_theme_setting') ? rb_theme_setting('phone', '+90 539 824 82 95') : '+90 539 824 82 95';
    $threshold = function_exists('rb_free_shipping_threshold') ? rb_free_shipping_threshold() : 4000;
    $lines = [
        '# Ramazan Bozkurt Et ve Et Mamulleri — Extended AI Reference',
        '',
        'Canonical domain: ' . rb_ai_md_link('ramazanbozkurt.com', rb_ai_url('/')),
        'Language: tr-TR',
        'Location: Talas, Kayseri, Türkiye',
        'Phone / WhatsApp: ' . $phone,
        '',
        '## Business summary',
        'Ramazan Bozkurt Et ve Et Mamulleri; Kayseri pastırması, sucuk, kavurma ve Kayseri mantısı ürünlerini perakende olarak sunar. Market, şarküteri, restoran, otel, kafe ve diğer düzenli alım yapan işletmeler için kurumsal/toptan tedarik görüşmesi yapılabilir.',
        '',
        '## Current commercial notes',
        '- Perakende siparişlerde ücretsiz kargo eşiği: ' . number_format_i18n((float)$threshold, 0) . ' TL.',
        '- Eşik altındaki kargo ücreti ödeme aşamasında hesaplanır ve gösterilir.',
        '- Kurumsal/toptan fiyatlandırma sipariş hacmi, ürün karması ve teslimat planına göre görüşülür.',
        '',
        '## Pastırma varieties',
        '- Sırt Pastırma',
        '- Antrikot Pastırma',
        '- Tütünlük Pastırma',
        '- Çemensiz Pastırma',
        '- Pastırma gramaj seçenekleri: 250 g, 400 g, 700 g, 1 kg.',
        '',
        '## Other products',
        '- Kayseri Sucuğu: 500 g ve 1 kg.',
        '- Kayseri Kavurması: 250 g, 500 g, 750 g ve 1 kg.',
        '- Kayseri Mantısı: 1 paket = 500 g.',
        '',
        '## Published product pages',
    ];
    $lines = array_merge($lines, rb_ai_product_lines(24));
    $lines[] = '';
    $lines[] = '## Blog & guides';
    $lines = array_merge($lines, rb_ai_post_lines('rehber', 20));
    $lines[] = '';
    $lines[] = '## Recipes';
    $lines = array_merge($lines, rb_ai_post_lines('yemek-tarifleri', 20));
    $lines = array_merge($lines, [
        '',
        '## Important pages',
        '- ' . rb_ai_md_link('Kurumsal Tedarik', rb_ai_url('/toptan-satis/')),
        '- ' . rb_ai_md_link('SSS', rb_ai_url('/sikca-sorulan-sorular/')),
        '- ' . rb_ai_md_link('Kargo ve Teslimat', rb_ai_url('/kargo-ve-teslimat/')),
        '- ' . rb_ai_md_link('Hakkımızda', rb_ai_url('/hakkimizda/')),
        '- ' . rb_ai_md_link('İletişim', rb_ai_url('/iletisim/')),
        '',
        '## Machine-readable discovery',
        '- ' . rb_ai_md_link('XML Sitemap', rb_ai_url('/wp-sitemap.xml')),
        '- ' . rb_ai_md_link('Robots', rb_ai_url('/robots.txt')),
        '- ' . rb_ai_md_link('Concise AI reference', rb_ai_url('/llms.txt')),
        '',
        'Freshness note: product prices, stock, shipping and availability can change. Prefer the canonical product/page URL at answer time rather than relying on cached copies.',
        '',
    ]);
    return implode("\n", $lines);
}

function rb_ai_serve_discovery_files() {
    $path = wp_parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    $path = '/' . ltrim((string)$path, '/');
    if ($path === '/llms.txt' || $path === '/llms.txt/') {
        rb_ai_plain_response(rb_ai_llms_txt());
    }
    if ($path === '/llms-full.txt' || $path === '/llms-full.txt/') {
        rb_ai_plain_response(rb_ai_llms_full_txt());
    }
}
add_action('template_redirect', 'rb_ai_serve_discovery_files', 0);

function rb_ai_robots_txt($output, $public) {
    if (!$public) { return "User-agent: *\nDisallow: /\n"; }

    $ai_agents = [
        'OAI-SearchBot',
        'ChatGPT-User',
        'GPTBot',
        'ClaudeBot',
        'Claude-SearchBot',
        'PerplexityBot',
        'Google-Extended',
    ];
    $lines = [];
    foreach ($ai_agents as $agent) {
        $lines[] = 'User-agent: ' . $agent;
        $lines[] = 'Allow: /';
        $lines[] = 'Disallow: /wp-admin/';
        $lines[] = 'Disallow: /sepet/';
        $lines[] = 'Disallow: /odeme/';
        $lines[] = 'Disallow: /hesabim/';
        $lines[] = '';
    }
    $lines = array_merge($lines, [
        'User-agent: *',
        'Disallow: /wp-admin/',
        'Allow: /wp-admin/admin-ajax.php',
        'Disallow: /sepet/',
        'Disallow: /odeme/',
        'Disallow: /hesabim/',
        'Disallow: /*?add-to-cart=',
        '',
        'Sitemap: ' . rb_ai_url('/wp-sitemap.xml'),
        '',
        '# AI-readable site references',
        '# llms.txt: ' . rb_ai_url('/llms.txt'),
        '# llms-full.txt: ' . rb_ai_url('/llms-full.txt'),
        '',
    ]);
    return implode("\n", $lines);
}
add_filter('robots_txt', 'rb_ai_robots_txt', 40, 2);

function rb_ai_check_url($url, $label) {
    $response = wp_remote_get($url, [
        'timeout' => 8,
        'redirection' => 3,
        'user-agent' => 'RamazanBozkurt-SiteHealth/1.0; ' . home_url('/'),
    ]);
    if (is_wp_error($response)) {
        return [
            'label' => $label . ' erişilemiyor',
            'status' => 'critical',
            'badge' => ['label' => 'SEO / AI', 'color' => 'red'],
            'description' => '<p>' . esc_html($response->get_error_message()) . '</p>',
            'actions' => '<p><code>' . esc_html($url) . '</code></p>',
            'test' => 'rb_' . sanitize_key($label),
        ];
    }
    $code = (int) wp_remote_retrieve_response_code($response);
    $body = trim((string) wp_remote_retrieve_body($response));
    $ok = $code === 200 && $body !== '';
    return [
        'label' => $ok ? $label . ' erişilebilir' : $label . ' HTTP kontrolü başarısız',
        'status' => $ok ? 'good' : 'critical',
        'badge' => ['label' => 'SEO / AI', 'color' => $ok ? 'green' : 'red'],
        'description' => '<p>' . esc_html($ok ? 'HTTP 200 ve içerik doğrulandı.' : 'Beklenen HTTP 200/içerik alınamadı. HTTP kodu: ' . $code) . '</p>',
        'actions' => '<p><code>' . esc_html($url) . '</code></p>',
        'test' => 'rb_' . sanitize_key($label),
    ];
}

function rb_ai_site_health_tests($tests) {
    $tests['direct']['rb_sitemap_http'] = [
        'label' => 'XML sitemap HTTP durumu',
        'test' => function() { return rb_ai_check_url(rb_ai_url('/wp-sitemap.xml'), 'XML sitemap'); },
    ];
    $tests['direct']['rb_robots_http'] = [
        'label' => 'robots.txt HTTP durumu',
        'test' => function() { return rb_ai_check_url(rb_ai_url('/robots.txt'), 'robots.txt'); },
    ];
    $tests['direct']['rb_llms_http'] = [
        'label' => 'llms.txt HTTP durumu',
        'test' => function() { return rb_ai_check_url(rb_ai_url('/llms.txt'), 'llms.txt'); },
    ];
    $tests['direct']['rb_llms_full_http'] = [
        'label' => 'llms-full.txt HTTP durumu',
        'test' => function() { return rb_ai_check_url(rb_ai_url('/llms-full.txt'), 'llms-full.txt'); },
    ];
    return $tests;
}
add_filter('site_status_tests', 'rb_ai_site_health_tests');
