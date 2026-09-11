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
    if ($attachment) { return wp_get_attachment_image_url($attachment->ID, 'full'); }
    $query = new WP_Query([
        'post_type' => 'attachment','post_status' => 'inherit','posts_per_page' => 1,
        'name' => sanitize_title($slug),'fields' => 'ids',
    ]);
    if (!empty($query->posts[0])) { return wp_get_attachment_image_url((int) $query->posts[0], 'full'); }
    return '';
}

function rb_media($slug, $fallback = '') { $url = rb_get_media_url_by_slug($slug); return $url ?: $fallback; }
function rb_media_first(array $slugs) { foreach ($slugs as $slug) { $url = rb_media($slug); if ($url) { return $url; } } return ''; }
function rb_media_id_first(array $slugs) {
    foreach ($slugs as $slug) {
        $attachment = get_page_by_path($slug, OBJECT, 'attachment');
        if ($attachment) { return (int) $attachment->ID; }
    }
    return 0;
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
        'hakkimizda' => ['Hakkımızda', 'Kayseri’nin köklü lezzet kültürünü çağdaş sunum anlayışıyla buluşturan Ramazan Bozkurt Et ve Et Mamulleri’ni, üretim yaklaşımımızı ve marka hikâyemizi keşfedin.'],
        'iletisim'   => ['İletişim', 'Ramazan Bozkurt Et ve Et Mamulleri ile perakende ve toptan satış, ürün bilgisi ve iş birliği konularında iletişime geçin.'],
    ];
    foreach ($pages as $slug => $data) {
        if (!get_page_by_path($slug)) {
            wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_title'=>$data[0],'post_name'=>$slug,'post_content'=>'<p>' . esc_html($data[1]) . '</p>']);
        }
    }
    if (class_exists('WooCommerce') && taxonomy_exists('product_cat')) {
        $categories = [
            'pastirma' => ['Pastırma', 'Kayseri pastırması; kendine özgü aroması, dilim yapısı ve geleneksel sofralardaki yeriyle Ramazan Bozkurt Et ve Et Mamulleri seçkisinin temel ürün gruplarından biridir. Ürün seçeneklerini, sunum biçimlerini ve güncel satış alternatiflerini inceleyin.'],
            'sucuk' => ['Sucuk', 'Kayseri sucukları; karakteristik baharat profili ve geleneksel lezzet beklentisini modern sunumla bir araya getirir. Ramazan Bozkurt sucuk seçeneklerini keşfedin.'],
            'kavurma' => ['Kavurma', 'Kavurma seçkimiz; pratik servis, dengeli doku ve güçlü et lezzeti arayan sofralar için hazırlanır. Ramazan Bozkurt kavurma ürünlerini ve güncel seçenekleri inceleyin.'],
            'manti' => ['Mantı', 'Kayseri mutfağının simge lezzetlerinden mantı; geleneksel sofra kültürünün vazgeçilmezlerinden biridir. Ramazan Bozkurt mantı seçeneklerini keşfedin.'],
        ];
        foreach ($categories as $slug => $data) {
            $term = get_term_by('slug', $slug, 'product_cat');
            if (!$term) { wp_insert_term($data[0], 'product_cat', ['slug'=>$slug,'description'=>$data[1]]); }
            elseif (empty($term->description)) { wp_update_term($term->term_id, 'product_cat', ['description'=>$data[1]]); }
        }
    }
    update_option('rb_site_seed_v2', 1);
}
add_action('admin_init', 'rb_seed_site_structure', 30);

function rb_seed_premium_demo_v3() {
    if (get_option('rb_site_seed_v3')) { return; }

    if (get_option('blogname') === 'Ramazan Bozkurt Et Ürünleri') {
        update_option('blogname', 'Ramazan Bozkurt Et ve Et Mamulleri');
    }
    if (!get_option('blogdescription') || get_option('blogdescription') === 'Just another WordPress site') {
        update_option('blogdescription', 'Kayseri pastırması, sucuk, kavurma ve mantı | Perakende ve toptan satış');
    }

    $pages = [
        'toptan-satis' => [
            'Toptan Satış',
            '<p>Ramazan Bozkurt Et ve Et Mamulleri; restoran, şarküteri, market, otel, kafe ve düzenli ürün tedariği yapan işletmelere pastırma, sucuk, kavurma ve mantıda toptan satış çözümleri sunar.</p><h2>İşletmenize özel teklif</h2><p>Sipariş miktarı, ürün grubu ve teslimat planına göre özel fiyatlandırma için bizimle iletişime geçebilirsiniz. Düzenli alımlarda tedarik planı ayrıca değerlendirilir.</p><h2>Nasıl ilerliyoruz?</h2><p>İhtiyacınızı, ürün grubunu ve yaklaşık sipariş miktarını paylaşın. Ekibimiz size uygun teklif ve sevkiyat planı için dönüş yapsın.</p>'
        ],
        'kargo-ve-teslimat' => [
            'Kargo ve Teslimat',
            '<p>Perakende siparişlerde 4.000 TL ve üzeri sepetlerde ücretsiz kargo avantajı uygulanır. Gönderim ve teslimat süreçleri ürünlerin niteliğine uygun biçimde planlanır.</p><h2>Teslimat bilgileri</h2><p>Siparişiniz hazırlanıp kargoya verildiğinde takip bilgileri, kullanılan altyapının desteklediği kanallar üzerinden paylaşılır. Toptan siparişlerde sevkiyat koşulları sipariş miktarına ve teslimat adresine göre ayrıca belirlenir.</p>'
        ],
        'sikca-sorulan-sorular' => [
            'Sıkça Sorulan Sorular',
            '<h2>Hangi ürünleri satıyorsunuz?</h2><p>Kayseri pastırması, sucuk, kavurma ve mantı ana ürün gruplarımızdır.</p><h2>Toptan satış yapıyor musunuz?</h2><p>Evet. İşletmelere ve düzenli alım yapan müşterilere sipariş miktarına göre özel teklif hazırlanır.</p><h2>Ücretsiz kargo limiti nedir?</h2><p>Perakende alışverişlerde 4.000 TL ve üzeri siparişlerde ücretsiz kargo avantajı sunulur.</p><h2>Ürün bilgilerini nereden görebilirim?</h2><p>Gramaj, fiyat ve mevcut satış seçenekleri her ürünün kendi sayfasında yer alır.</p>'
        ],
    ];

    foreach ($pages as $slug => $data) {
        if (!get_page_by_path($slug)) {
            wp_insert_post([
                'post_type' => 'page',
                'post_status' => 'publish',
                'post_title' => $data[0],
                'post_name' => $slug,
                'post_content' => $data[1],
            ]);
        }
    }

    if (class_exists('WooCommerce') && taxonomy_exists('product_cat')) {
        $product_data = [
            'kayseri-pastirmasi-250-g' => [
                'name' => 'Kayseri Pastırması 250 g',
                'price' => '710',
                'cat' => 'pastirma',
                'short' => 'Kayseri mutfak kültürünün simge ürünlerinden pastırma. 250 g paket seçeneğiyle perakende siparişe uygundur; toplu alımlar için özel teklif alınabilir.',
                'content' => '<h2>Kayseri Pastırması 250 g</h2><p>Ramazan Bozkurt Et ve Et Mamulleri pastırma seçkisi, Kayseri pastırma geleneğini sofralara taşımak isteyen müşteriler için hazırlanır. İnce dilim servis, kahvaltı sofraları, sandviç ve sıcak yemek sunumlarında değerlendirilebilir.</p><h3>Ürün bilgisi</h3><ul><li>Net miktar: 250 g</li><li>Ürün grubu: Pastırma</li><li>Perakende satışa uygundur</li><li>Toptan ve düzenli alımlar için özel fiyat teklifi alınabilir</li></ul><h3>Saklama ve servis</h3><p>Ürünün ambalajında belirtilen saklama koşullarına uyunuz. Açıldıktan sonra ürün etiketindeki tüketim ve muhafaza talimatlarını takip ediniz.</p>',
                'images' => ['ramazan-bozkurt-kayseri-pastirmasi-premium-sunum','ramazan-bozkurt-kayseri-pastirmasi-dilimli-sunum','ramazan-bozkurt-pastirma-makro-detay'],
            ],
            'kayseri-sucugu-500-g' => [
                'name' => 'Kayseri Sucuğu 500 g',
                'price' => '680',
                'cat' => 'sucuk',
                'short' => 'Geleneksel Kayseri sucuk lezzetini 500 g paket seçeneğiyle keşfedin. Perakende siparişin yanında işletmelere özel toptan satış desteği sunulur.',
                'content' => '<h2>Kayseri Sucuğu 500 g</h2><p>Kayseri sucuk kültürünü sofranıza taşıyan bu ürün; kahvaltı, tost, yumurta, ızgara ve sıcak yemeklerde kullanıma uygundur. Karakteristik sucuk deneyimini pratik 500 g paketle sunar.</p><h3>Ürün bilgisi</h3><ul><li>Net miktar: 500 g</li><li>Ürün grubu: Sucuk</li><li>Perakende satışa uygundur</li><li>Restoran, market ve şarküterilere toptan teklif verilebilir</li></ul><h3>Saklama ve servis</h3><p>Ambalaj üzerinde yer alan muhafaza ve tüketim talimatlarını esas alınız. Pişirerek tüketiniz.</p>',
                'images' => ['ramazan-bozkurt-kayseri-sucugu-premium-sunum','ramazan-bozkurt-kayseri-sucugu-urun-gorseli','ramazan-bozkurt-kayseri-parmak-sucuk'],
            ],
            'kayseri-kavurmasi-250-g' => [
                'name' => 'Kayseri Kavurması 250 g',
                'price' => '470',
                'cat' => 'kavurma',
                'short' => '250 g Kayseri kavurması; pratik servis ve yoğun et lezzeti arayan sofralar için. Toptan alımlarda işletmeye özel teklif seçeneği bulunur.',
                'content' => '<h2>Kayseri Kavurması 250 g</h2><p>Kavurma, hızlı servis gerektiren öğünlerden geleneksel sofralara kadar geniş kullanım alanına sahip bir et ürünüdür. Ramazan Bozkurt Et ve Et Mamulleri kavurma seçkisi 250 g paket seçeneğiyle sunulur.</p><h3>Ürün bilgisi</h3><ul><li>Net miktar: 250 g</li><li>Ürün grubu: Kavurma</li><li>Perakende satışa uygundur</li><li>Toplu siparişlerde özel teklif alınabilir</li></ul><h3>Saklama ve servis</h3><p>Ürün etiketindeki saklama, ısıtma ve son tüketim bilgilerini takip ediniz.</p>',
                'images' => ['ramazan-bozkurt-kayseri-kavurma-premium-sunum','ramazan-bozkurt-kayseri-kavurma-urun-sunumu','ramazan-bozkurt-kavurma-kesit-detay'],
            ],
            'kayseri-mantisi-500-g' => [
                'name' => 'Kayseri Mantısı 500 g',
                'price' => '340',
                'cat' => 'manti',
                'short' => 'Kayseri mutfağının klasiklerinden mantı. 500 g paket seçeneğiyle pratik servis için sunulur; işletmelere toptan sipariş desteği verilir.',
                'content' => '<h2>Kayseri Mantısı 500 g</h2><p>Kayseri mantısı, geleneksel sofra kültürünün öne çıkan yemeklerinden biridir. 500 g paket seçeneği; evde pratik hazırlık ve porsiyon planlaması için uygundur.</p><h3>Ürün bilgisi</h3><ul><li>Net miktar: 500 g</li><li>Ürün grubu: Mantı</li><li>Perakende satışa uygundur</li><li>Toplu sipariş ve kurumsal tedarik talebi alınabilir</li></ul><h3>Pişirme ve saklama</h3><p>Ürün paketindeki pişirme, muhafaza ve tüketim talimatlarını takip ediniz.</p>',
                'images' => ['ramazan-bozkurt-kayseri-mantisi-geleneksel','ramazan-bozkurt-kayseri-mantisi-paket','ramazan-bozkurt-kayseri-mantisi-el-yapimi'],
            ],
        ];

        foreach ($product_data as $slug => $data) {
            if (get_page_by_path($slug, OBJECT, 'product')) { continue; }
            $product = new WC_Product_Simple();
            $product->set_name($data['name']);
            $product->set_slug($slug);
            $product->set_status('publish');
            $product->set_catalog_visibility('visible');
            $product->set_regular_price($data['price']);
            $product->set_price($data['price']);
            $product->set_short_description($data['short']);
            $product->set_description($data['content']);
            $product->set_stock_status('instock');
            $product->set_featured(true);

            $term = get_term_by('slug', $data['cat'], 'product_cat');
            if ($term && !is_wp_error($term)) { $product->set_category_ids([(int) $term->term_id]); }

            $main_image = rb_media_id_first([$data['images'][0], $data['images'][1]]);
            if ($main_image) { $product->set_image_id($main_image); }
            $gallery = [];
            foreach (array_slice($data['images'], 1) as $image_slug) {
                $image_id = rb_media_id_first([$image_slug]);
                if ($image_id && $image_id !== $main_image) { $gallery[] = $image_id; }
            }
            if ($gallery) { $product->set_gallery_image_ids($gallery); }
            $product->save();
        }
    }

    update_option('rb_site_seed_v3', 1);
}
add_action('admin_init', 'rb_seed_premium_demo_v3', 40);

function rb_has_seo_plugin() { return defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION') || defined('SEOPRESS_VERSION') || defined('AIOSEO_VERSION'); }

function rb_meta_description() {
    if (is_front_page()) { return 'Ramazan Bozkurt Et ve Et Mamulleri: Kayseri pastırması, sucuk, kavurma ve mantı seçenekleri. Geleneksel Kayseri lezzetlerini keşfedin; perakende ve toptan satış için iletişime geçin.'; }
    if (function_exists('is_shop') && is_shop()) { return 'Ramazan Bozkurt Et ve Et Mamulleri mağazasında Kayseri pastırması, sucuk, kavurma ve mantı seçeneklerini inceleyin.'; }
    if (is_tax('product_cat')) { $term = get_queried_object(); if ($term && !empty($term->description)) { return wp_strip_all_tags($term->description); } }
    if (is_singular('product')) { $excerpt = get_the_excerpt(); if ($excerpt) { return wp_strip_all_tags($excerpt); } return get_the_title() . ' hakkında ürün bilgisi, satış ve teslimat seçeneklerini Ramazan Bozkurt Et ve Et Mamulleri’nde inceleyin.'; }
    if (is_singular()) { $excerpt = get_the_excerpt(); if ($excerpt) { return wp_strip_all_tags($excerpt); } }
    return get_bloginfo('description');
}

function rb_output_seo_meta() {
    if (rb_has_seo_plugin()) { return; }
    $description = trim(wp_strip_all_tags(rb_meta_description()));
    if ($description) { echo '<meta name="description" content="' . esc_attr(wp_trim_words($description, 28, '')) . '">' . "\n"; }
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
    $graph = ['@context'=>'https://schema.org','@graph'=>[
        ['@type'=>'Organization','@id'=>home_url('/#organization'),'name'=>'Ramazan Bozkurt Et ve Et Mamulleri','url'=>home_url('/'),'logo'=>$logo ? ['@type'=>'ImageObject','url'=>$logo] : null],
        ['@type'=>'WebSite','@id'=>home_url('/#website'),'url'=>home_url('/'),'name'=>'Ramazan Bozkurt Et ve Et Mamulleri','inLanguage'=>'tr-TR','publisher'=>['@id'=>home_url('/#organization')],'potentialAction'=>['@type'=>'SearchAction','target'=>home_url('/?s={search_term_string}'),'query-input'=>'required name=search_term_string']],
    ]];
    $graph['@graph'][0] = array_filter($graph['@graph'][0]);
    echo '<script type="application/ld+json">' . wp_json_encode($graph, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'rb_output_schema', 30);

function rb_body_classes($classes) { if (class_exists('WooCommerce')) { $classes[] = 'rb-woocommerce-ready'; } return $classes; }
add_filter('body_class', 'rb_body_classes');
add_filter('woocommerce_enqueue_styles', function($styles) { return $styles; });
add_filter('loop_shop_per_page', function() { return 12; }, 20);
add_filter('loop_shop_columns', function() { return 3; }, 20);
add_filter('woocommerce_output_related_products_args', function($args) { $args['posts_per_page']=3; $args['columns']=3; return $args; });

function rb_cart_count() { return (function_exists('WC') && WC()->cart) ? WC()->cart->get_cart_contents_count() : 0; }
function rb_cart_fragments($fragments) { ob_start(); ?><span class="rb-cart-count"><?php echo esc_html(rb_cart_count()); ?></span><?php $fragments['.rb-cart-count'] = ob_get_clean(); return $fragments; }
add_filter('woocommerce_add_to_cart_fragments', 'rb_cart_fragments');

require_once get_template_directory() . '/inc/delivery-setup.php';
require_once get_template_directory() . '/inc/commerce-config.php';
require_once get_template_directory() . '/inc/product-experience.php';
require_once get_template_directory() . '/inc/navigation-setup.php';
require_once get_template_directory() . '/inc/seo-final.php';
require_once get_template_directory() . '/inc/ai-discovery.php';
