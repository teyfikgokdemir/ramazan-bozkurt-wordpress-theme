<?php
/**
 * Premium single product experience.
 */
defined('ABSPATH') || exit;

function rb_product_description_tab_callback() {
    global $post, $product;
    if (!$post || !$product) { return; }

    $gallery_ids = array_values(array_filter($product->get_gallery_image_ids()));
    if (!$gallery_ids && $product->get_image_id()) { $gallery_ids[] = $product->get_image_id(); }
    $visual_ids = array_slice($gallery_ids, 0, 2);

    echo '<div class="rb-product-description-layout">';
    echo '<div class="rb-product-description-copy">';
    echo do_shortcode(wpautop(wp_kses_post($post->post_content)));
    echo '</div>';

    if ($visual_ids) {
        echo '<aside class="rb-product-description-visuals" aria-label="Ürün detay görselleri">';
        foreach ($visual_ids as $image_id) {
            echo wp_get_attachment_image($image_id, 'large', false, [
                'loading' => 'lazy',
                'class' => 'rb-product-detail-image',
            ]);
        }
        echo '</aside>';
    }
    echo '</div>';
}

function rb_replace_description_tab_callback($tabs) {
    if (isset($tabs['description'])) {
        $tabs['description']['callback'] = 'rb_product_description_tab_callback';
        $tabs['description']['title'] = 'Açıklama';
    }
    if (isset($tabs['additional_information'])) { $tabs['additional_information']['title'] = 'Ek Bilgiler'; }
    if (isset($tabs['reviews'])) { $tabs['reviews']['title'] = 'Değerlendirmeler'; }
    return $tabs;
}
add_filter('woocommerce_product_tabs', 'rb_replace_description_tab_callback', 30);

function rb_other_products_section() {
    if (!is_product()) { return; }
    global $product;
    if (!$product) { return; }

    $query = new WP_Query([
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => 3,
        'post__not_in' => [$product->get_id()],
        'orderby' => 'menu_order title',
        'order' => 'ASC',
        'tax_query' => [[
            'taxonomy' => 'product_visibility',
            'field' => 'name',
            'terms' => ['exclude-from-catalog'],
            'operator' => 'NOT IN',
        ]],
    ]);

    if (!$query->have_posts()) { return; }
    echo '<section class="rb-other-products">';
    echo '<div class="rb-other-products__head"><div><span class="rb-eyebrow">Sofranızı tamamlayın</span><h2>Diğer lezzetleri keşfedin</h2></div><a class="rb-text-link" href="' . esc_url(wc_get_page_permalink('shop')) . '">Tüm ürünler →</a></div>';
    woocommerce_product_loop_start();
    while ($query->have_posts()) {
        $query->the_post();
        wc_get_template_part('content', 'product');
    }
    woocommerce_product_loop_end();
    echo '</section>';
    wp_reset_postdata();
}
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
add_action('woocommerce_after_single_product_summary', 'rb_other_products_section', 28);

function rb_product_shipping_note() {
    if (!is_product()) { return; }
    echo '<div class="rb-product-shipping-note"><strong>4.000 TL ve üzeri ücretsiz kargo</strong><span>4.000 TL altındaki siparişlerde kargo ücreti 180 TL’dir.</span></div>';
}
add_action('woocommerce_single_product_summary', 'rb_product_shipping_note', 31);

function rb_update_seeded_variable_product_copy_v1() {
    if (get_option('rb_variable_product_copy_v1') || !class_exists('WooCommerce')) { return; }

    $copy = [
        'kayseri-pastirmasi-250-g' => [
            'short' => 'Kayseri pastırması; 250 g, 500 g ve 1 kg gramaj seçenekleriyle perakende siparişe sunulur. Düzenli ve toplu alımlar için kurumsal teklif alınabilir.',
            'content' => '<h2>Kayseri Pastırması</h2><p>Ramazan Bozkurt Et ve Et Mamulleri pastırma seçkisi, Kayseri pastırmasını kahvaltıdan sıcak yemeklere farklı kullanım biçimleriyle sofraya taşımak isteyen müşteriler için sunulur.</p><h3>Gramaj seçenekleri</h3><ul><li>250 g</li><li>500 g</li><li>1 kg</li></ul><h3>Kullanım önerileri</h3><p>İnce dilim servis, kahvaltı tabağı, yumurta, sandviç ve sıcak yemeklerde değerlendirilebilir. Ürünü gereğinden fazla ısıtmak yerine kısa ve kontrollü kullanım daha dengeli sonuç verir.</p><h3>Saklama</h3><p>Ambalaj üzerinde yer alan saklama, son tüketim ve açıldıktan sonraki kullanım talimatlarını esas alınız.</p><h3>Kurumsal alım</h3><p>Market, şarküteri, HORECA ve düzenli alım yapan işletmeler için sipariş hacmine göre teklif süreci yürütülür.</p>'
        ],
        'kayseri-sucugu-500-g' => [
            'short' => 'Kayseri sucuğu; 250 g, 500 g ve 1 kg seçenekleriyle. Perakende siparişin yanında market, şarküteri ve HORECA alımları için kurumsal teklif alınabilir.',
            'content' => '<h2>Kayseri Sucuğu</h2><p>Kayseri sucuk kültürünü sofraya taşıyan ürün; kahvaltı, tost, yumurta, ızgara ve sıcak yemeklerde kullanılabilir.</p><h3>Gramaj seçenekleri</h3><ul><li>250 g</li><li>500 g</li><li>1 kg</li></ul><h3>Pişirme önerisi</h3><p>Sucuğu orta ateşte kısa ve kontrollü pişirmek, ürünü gereğinden fazla kurutmadan servis etmeyi kolaylaştırır. Pişirme ve tüketim için ambalaj talimatlarını esas alınız.</p><h3>Kurumsal alım</h3><p>Zincir ve yerel marketler, şarküteriler, restoranlar, oteller ve profesyonel mutfaklar için hacme göre teklif hazırlanabilir.</p>'
        ],
        'kayseri-kavurmasi-250-g' => [
            'short' => 'Kayseri kavurması; 250 g, 500 g ve 1 kg seçenekleriyle perakende satışa sunulur. Toplu ve düzenli alımlarda işletmeye özel teklif hazırlanabilir.',
            'content' => '<h2>Kayseri Kavurması</h2><p>Kavurma; pratik servis, pilav, yumurta, sandviç ve sıcak yemeklerde değerlendirilebilen güçlü bir et ürünüdür.</p><h3>Gramaj seçenekleri</h3><ul><li>250 g</li><li>500 g</li><li>1 kg</li></ul><h3>Servis önerisi</h3><p>Ürünü kısa süre kontrollü biçimde ısıtıp servis edebilirsiniz. Saklama, ısıtma ve son tüketim bilgileri için ambalaj üzerindeki talimatları takip ediniz.</p><h3>Kurumsal alım</h3><p>Düzenli veya yüksek hacimli alımlarda ürün miktarı ve teslimat planına göre teklif oluşturulur.</p>'
        ],
        'kayseri-mantisi-500-g' => [
            'short' => 'Kayseri mantısı; 250 g, 500 g ve 1 kg gramaj seçenekleriyle. Ev tipi siparişlerin yanında restoran ve işletme alımları için toplu teklif alınabilir.',
            'content' => '<h2>Kayseri Mantısı</h2><p>Kayseri mutfağının simge lezzetlerinden mantı; yoğurt, sarımsak ve tereyağlı sosla klasik biçimde servis edilebilir.</p><h3>Gramaj seçenekleri</h3><ul><li>250 g</li><li>500 g</li><li>1 kg</li></ul><h3>Hazırlama</h3><p>Pişirme süresi ve saklama koşulları için ürün ambalajındaki talimatları esas alınız. Serviste yoğurt ve sosu damak tadınıza göre dengeleyebilirsiniz.</p><h3>Kurumsal alım</h3><p>Restoran, otel, kafe ve diğer profesyonel mutfakların düzenli ihtiyaçları için hacme göre teklif hazırlanabilir.</p>'
        ],
    ];

    foreach ($copy as $slug => $data) {
        $post = get_page_by_path($slug, OBJECT, 'product');
        if (!$post) { continue; }
        wp_update_post([
            'ID' => $post->ID,
            'post_excerpt' => $data['short'],
            'post_content' => $data['content'],
        ]);
    }

    update_option('rb_variable_product_copy_v1', 1);
}
add_action('admin_init', 'rb_update_seeded_variable_product_copy_v1', 130);
