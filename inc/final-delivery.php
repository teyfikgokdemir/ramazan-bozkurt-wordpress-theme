<?php
/**
 * Final delivery optimizations for Ramazan Bozkurt Et ve Et Mamulleri.
 * Keeps operational claims conservative and only uses verified business data.
 */
defined('ABSPATH') || exit;

function rb_final_product_content_map() {
    return [
        'kayseri-pastirmasi-250-g' => [
            'title' => 'Sırt Pastırma',
            'sku' => 'RB-SIRT',
            'summary' => 'Kayseri pastırması seçkisinde sırt pastırma; ince dilim, kahvaltı ve soğuk servis sunumları için tercih edilebilen seçeneklerden biridir.',
            'weights' => '250 g, 400 g, 700 g ve 1 kg',
            'serve' => 'İnce dilimleyerek kahvaltı, şarküteri tabağı, sandviç ve sıcak tariflerde kullanabilirsiniz.',
        ],
        'antrikot-pastirma' => [
            'title' => 'Antrikot Pastırma',
            'sku' => 'RB-ANT',
            'summary' => 'Antrikot Pastırma, Ramazan Bozkurt pastırma seçkisinin farklı gramajlarla sunulan ürünlerinden biridir.',
            'weights' => '250 g, 400 g, 700 g ve 1 kg',
            'serve' => 'İnce dilim servis, kahvaltı, şarküteri tabağı ve sıcak tariflerde değerlendirilebilir.',
        ],
        'tutunluk-pastirma' => [
            'title' => 'Tütünlük Pastırma',
            'sku' => 'RB-TUT',
            'summary' => 'Tütünlük Pastırma, farklı gramaj alternatifleriyle perakende siparişe sunulan Kayseri pastırması seçeneklerinden biridir.',
            'weights' => '250 g, 400 g, 700 g ve 1 kg',
            'serve' => 'Kahvaltıda, şarküteri sunumlarında ve sıcak yemeklerde ince dilim olarak kullanılabilir.',
        ],
        'cemensiz-pastirma' => [
            'title' => 'Çemensiz Pastırma',
            'sku' => 'RB-CEM',
            'summary' => 'Çemensiz Pastırma, çemen tercih etmeyen müşteriler için pastırma seçkisinde ayrı bir alternatif olarak sunulur.',
            'weights' => '250 g, 400 g, 700 g ve 1 kg',
            'serve' => 'Kahvaltı, şarküteri tabağı, sandviç ve sıcak tariflerde ince dilim olarak kullanılabilir.',
        ],
        'kayseri-sucugu-500-g' => [
            'title' => 'Kayseri Sucuğu',
            'sku' => 'RB-SUC',
            'summary' => 'Kayseri Sucuğu, 500 g ve 1 kg gramaj seçenekleriyle perakende siparişe sunulur.',
            'weights' => '500 g ve 1 kg',
            'serve' => 'Tavada, ızgarada, kahvaltıda ve sıcak tariflerde pişirilerek tüketilebilir.',
        ],
        'kayseri-kavurmasi-250-g' => [
            'title' => 'Kayseri Kavurması',
            'sku' => 'RB-KAV',
            'summary' => 'Kayseri Kavurması, farklı porsiyon ve kullanım ihtiyaçları için dört gramaj seçeneğiyle sunulur.',
            'weights' => '250 g, 500 g, 750 g ve 1 kg',
            'serve' => 'Kahvaltı, pilav, yumurta ve sıcak yemeklerde ısıtılarak servis edilebilir.',
        ],
        'kayseri-mantisi-500-g' => [
            'title' => 'Kayseri Mantısı 500 g',
            'sku' => 'RB-MAN',
            'summary' => 'Kayseri Mantısı 500 g, tek paket gramajıyla perakende siparişe sunulur.',
            'weights' => '500 g',
            'serve' => 'Pişirme süresi ve hazırlama yöntemi için ürün ambalajındaki talimatları esas alınız.',
        ],
    ];
}

function rb_final_product_description(array $data) {
    $shipping = function_exists('rb_free_shipping_threshold') ? rb_free_shipping_threshold() : 4000;
    $shipping_label = function_exists('wc_price') ? wp_strip_all_tags(wc_price($shipping, ['decimals' => 0])) : number_format_i18n($shipping, 0) . ' TL';
    return '<h2>' . esc_html($data['title']) . '</h2>'
        . '<p>' . esc_html($data['summary']) . '</p>'
        . '<h3>Gramaj seçenekleri</h3><p>' . esc_html($data['weights']) . '</p>'
        . '<h3>Servis ve kullanım önerisi</h3><p>' . esc_html($data['serve']) . '</p>'
        . '<h3>Saklama ve ürün bilgisi</h3><p>Ürünün içerik, alerjen, saklama, son tüketim ve kullanım bilgileri için teslim aldığınız ürünün ambalaj etiketini esas alınız. Ambalaj bütünlüğü bozulmuş ürünleri tüketmeyiniz.</p>'
        . '<h3>Kargo ve teslimat</h3><p>' . esc_html($shipping_label) . ' ve üzeri perakende siparişlerde ücretsiz kargo uygulanır. Teslimat ve sevkiyat detayları için <a href="' . esc_url(home_url('/kargo-ve-teslimat/')) . '">Kargo ve Teslimat</a> sayfasını inceleyebilirsiniz.</p>'
        . '<h3>İade ve sipariş desteği</h3><p>Gıda ürünlerinde iade ve iptal koşulları ürünün niteliğine ve sipariş durumuna göre değerlendirilir. Güncel koşullar için <a href="' . esc_url(home_url('/iade-iptal-politikasi/')) . '">İade ve İptal Politikası</a> sayfasını inceleyin veya sipariş öncesinde bizimle iletişime geçin.</p>';
}

function rb_final_delivery_content_v1() {
    if (get_option('rb_final_delivery_content_v1')) { return; }

    foreach (rb_final_product_content_map() as $slug => $data) {
        $post = get_page_by_path($slug, OBJECT, 'product');
        if (!$post) { continue; }
        wp_update_post([
            'ID' => (int) $post->ID,
            'post_excerpt' => $data['summary'] . ' Gramaj seçenekleri: ' . $data['weights'] . '.',
            'post_content' => rb_final_product_description($data),
        ]);
        $product = function_exists('wc_get_product') ? wc_get_product($post->ID) : null;
        if ($product) {
            if (!$product->get_sku()) {
                try { $product->set_sku($data['sku']); } catch (Exception $e) {}
            }
            $product->save();
        }
    }

    $about = get_page_by_path('hakkimizda', OBJECT, 'page');
    if ($about) {
        $about_content = '<h2>Kayseri’den sofralara ve işletmelere</h2><p>Ramazan Bozkurt Et ve Et Mamulleri, Kayseri / Talas merkezli olarak pastırma, sucuk, kavurma ve Kayseri mantısı ürünlerini perakende müşteriler ve düzenli alım yapan işletmelerle buluşturur.</p>'
            . '<h2>Perakende ve kurumsal satış</h2><p>Web sitesinde bireysel müşteriler için gramaj seçenekleri ve online sipariş akışı; market, şarküteri, restoran, otel, kafe ve diğer işletmeler için ise ayrı bir toptan teklif süreci bulunur.</p>'
            . '<h2>Ürün bilgisinde şeffaflık</h2><p>Ürün sayfalarında gramaj, fiyat, kargo ve kullanım bilgileri açıkça sunulur. İçerik, alerjen, saklama ve son tüketim bilgileri için her zaman teslim edilen ürünün ambalaj etiketi esas alınır.</p>'
            . '<h2>İletişim</h2><p>Ürün, sipariş ve kurumsal tedarik sorularınız için doğrudan telefon veya WhatsApp üzerinden ulaşabilirsiniz. İşletme konumu: Süleymanlı Mah., Talas / Kayseri.</p>';
        wp_update_post(['ID' => (int) $about->ID, 'post_content' => $about_content]);
    }

    $contact = get_page_by_path('iletisim', OBJECT, 'page');
    if ($contact) {
        $contact_content = '<h2>Satış ve destek için doğrudan iletişim</h2><p>Perakende sipariş, ürün bilgisi, mevcut sipariş desteği ve toptan satış talepleri için bize ulaşabilirsiniz.</p>'
            . '<div class="rb-contact-cards"><div><strong>Telefon</strong><a href="tel:+905398248295">+90 539 824 82 95</a></div><div><strong>WhatsApp</strong><a href="https://wa.me/905398248295" target="_blank" rel="noopener noreferrer">WhatsApp’tan yazın</a></div><div><strong>Konum</strong><span>Süleymanlı Mah., Talas / Kayseri</span></div></div>'
            . '<h2>Mesaj gönderin</h2>[rb_contact_form]';
        wp_update_post(['ID' => (int) $contact->ID, 'post_content' => $contact_content]);
    }

    $wholesale = get_page_by_path('toptan-satis', OBJECT, 'page');
    if ($wholesale) {
        wp_update_post(['ID' => (int) $wholesale->ID, 'post_content' => '<h2>Kurumsal teklif formu</h2><p>İşletme, şehir, ürün grubu ve yaklaşık ihtiyacınızı paylaşın. Talebiniz doğrudan satış ekibine iletilsin.</p>[rb_b2b_form]']);
    }

    update_option('rb_final_delivery_content_v1', 1);
}
add_action('admin_init', 'rb_final_delivery_content_v1', 230);

function rb_final_stock_2000_v1() {
    if (get_option('rb_final_stock_2000_v1') || !class_exists('WooCommerce')) { return; }
    $ids = get_posts([
        'post_type' => ['product', 'product_variation'],
        'post_status' => ['publish', 'private'],
        'numberposts' => -1,
        'fields' => 'ids',
    ]);
    foreach ($ids as $id) {
        $product = wc_get_product($id);
        if (!$product) { continue; }
        if ($product->is_type('variable')) {
            $product->set_manage_stock(false);
            $product->save();
            continue;
        }
        $product->set_manage_stock(true);
        $product->set_stock_quantity(2000);
        $product->set_stock_status('instock');
        $product->save();
    }
    if (function_exists('wc_delete_product_transients')) { wc_delete_product_transients(); }
    update_option('rb_final_stock_2000_v1', 1);
}
add_action('admin_init', 'rb_final_stock_2000_v1', 240);

function rb_final_form_notice($key) {
    if (empty($_GET[$key])) { return ''; }
    return '<div class="rb-form-notice">Mesajınız alındı. En kısa sürede dönüş yapılacaktır.</div>';
}

function rb_contact_form_shortcode() {
    $action = esc_url(admin_url('admin-post.php'));
    return rb_final_form_notice('rb_contact_sent') . '<form class="rb-lead-form" method="post" action="' . $action . '">'
        . '<input type="hidden" name="action" value="rb_contact_submit">' . wp_nonce_field('rb_contact_submit', 'rb_contact_nonce', true, false)
        . '<p class="rb-hp"><label>Web sitesi<input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>'
        . '<label>Ad Soyad<input type="text" name="name" required></label>'
        . '<label>Telefon<input type="tel" name="phone" required></label>'
        . '<label>Konu<select name="subject"><option>Ürün bilgisi</option><option>Sipariş desteği</option><option>Toptan satış</option><option>Diğer</option></select></label>'
        . '<label>Mesaj<textarea name="message" rows="5" required></textarea></label>'
        . '<button class="rb-btn rb-btn--primary" type="submit">Mesaj Gönder</button></form>';
}
add_shortcode('rb_contact_form', 'rb_contact_form_shortcode');

function rb_b2b_form_shortcode() {
    $action = esc_url(admin_url('admin-post.php'));
    return rb_final_form_notice('rb_b2b_sent') . '<form class="rb-lead-form rb-lead-form--b2b" method="post" action="' . $action . '">'
        . '<input type="hidden" name="action" value="rb_b2b_submit">' . wp_nonce_field('rb_b2b_submit', 'rb_b2b_nonce', true, false)
        . '<p class="rb-hp"><label>Web sitesi<input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>'
        . '<label>İşletme adı<input type="text" name="company" required></label>'
        . '<label>Yetkili adı<input type="text" name="name" required></label>'
        . '<label>Telefon<input type="tel" name="phone" required></label>'
        . '<label>Şehir<input type="text" name="city" required></label>'
        . '<label>Ürün grubu<select name="product"><option>Pastırma</option><option>Sucuk</option><option>Kavurma</option><option>Mantı</option><option>Birden fazla ürün</option></select></label>'
        . '<label>Tahmini aylık miktar<input type="text" name="volume" placeholder="Örn. 20–50 kg"></label>'
        . '<label>Sipariş sıklığı<select name="frequency"><option>Tek seferlik</option><option>Haftalık</option><option>15 günlük</option><option>Aylık</option><option>Henüz net değil</option></select></label>'
        . '<label>Not<textarea name="message" rows="4"></textarea></label>'
        . '<button class="rb-btn rb-btn--primary" type="submit">Teklif Talebi Gönder</button></form>';
}
add_shortcode('rb_b2b_form', 'rb_b2b_form_shortcode');

function rb_handle_lead_form($type) {
    $is_b2b = $type === 'b2b';
    $nonce_key = $is_b2b ? 'rb_b2b_nonce' : 'rb_contact_nonce';
    $nonce_action = $is_b2b ? 'rb_b2b_submit' : 'rb_contact_submit';
    if (empty($_POST[$nonce_key]) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST[$nonce_key])), $nonce_action)) { wp_die('Güvenlik doğrulaması başarısız.'); }
    if (!empty($_POST['website'])) { wp_safe_redirect(home_url('/')); exit; }

    $name = sanitize_text_field(wp_unslash($_POST['name'] ?? ''));
    $phone = sanitize_text_field(wp_unslash($_POST['phone'] ?? ''));
    $message = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));
    $lines = ['Ad/Yetkili: ' . $name, 'Telefon: ' . $phone];
    if ($is_b2b) {
        $lines[] = 'İşletme: ' . sanitize_text_field(wp_unslash($_POST['company'] ?? ''));
        $lines[] = 'Şehir: ' . sanitize_text_field(wp_unslash($_POST['city'] ?? ''));
        $lines[] = 'Ürün: ' . sanitize_text_field(wp_unslash($_POST['product'] ?? ''));
        $lines[] = 'Tahmini miktar: ' . sanitize_text_field(wp_unslash($_POST['volume'] ?? ''));
        $lines[] = 'Sipariş sıklığı: ' . sanitize_text_field(wp_unslash($_POST['frequency'] ?? ''));
    } else {
        $lines[] = 'Konu: ' . sanitize_text_field(wp_unslash($_POST['subject'] ?? ''));
    }
    $lines[] = 'Mesaj: ' . $message;
    $to = get_option('admin_email');
    $subject = $is_b2b ? 'Yeni Kurumsal Teklif Talebi - Ramazan Bozkurt' : 'Yeni İletişim Mesajı - Ramazan Bozkurt';
    wp_mail($to, $subject, implode("\n", $lines));

    $target = $is_b2b ? home_url('/toptan-satis/?rb_b2b_sent=1#kurumsal-form') : home_url('/iletisim/?rb_contact_sent=1');
    wp_safe_redirect($target); exit;
}
function rb_handle_contact_submit() { rb_handle_lead_form('contact'); }
function rb_handle_b2b_submit() { rb_handle_lead_form('b2b'); }
add_action('admin_post_nopriv_rb_contact_submit', 'rb_handle_contact_submit');
add_action('admin_post_rb_contact_submit', 'rb_handle_contact_submit');
add_action('admin_post_nopriv_rb_b2b_submit', 'rb_handle_b2b_submit');
add_action('admin_post_rb_b2b_submit', 'rb_handle_b2b_submit');

function rb_final_product_tabs($tabs) {
    global $product;
    if ($product && (int) $product->get_review_count() === 0) { unset($tabs['reviews']); }
    $tabs['rb_delivery'] = [
        'title' => 'Kargo ve Teslimat',
        'priority' => 25,
        'callback' => function() { echo '<h2>Kargo ve Teslimat</h2><p>4.000 TL ve üzeri perakende siparişlerde ücretsiz kargo uygulanır. Toptan siparişlerin sevkiyat koşulları sipariş miktarı ve teslimat planına göre ayrıca belirlenir.</p><p><a href="' . esc_url(home_url('/kargo-ve-teslimat/')) . '">Kargo ve teslimat detayları</a></p>'; },
    ];
    $tabs['rb_product_info'] = [
        'title' => 'Saklama ve Ürün Bilgisi',
        'priority' => 26,
        'callback' => function() { echo '<h2>Saklama ve Ürün Bilgisi</h2><p>İçerik, alerjen, saklama, son tüketim ve kullanım bilgileri için teslim aldığınız ürünün ambalaj etiketini esas alınız. Ürün ambalajı üzerindeki üretici/tedarikçi beyanı önceliklidir.</p>'; },
    ];
    return $tabs;
}
add_filter('woocommerce_product_tabs', 'rb_final_product_tabs', 30);

function rb_final_delivery_css() {
    ?>
    <style>
      .rb-lead-form{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px;margin-top:20px}.rb-lead-form label{display:grid;gap:7px;color:var(--rb-cream);font-size:13px}.rb-lead-form input,.rb-lead-form select,.rb-lead-form textarea{width:100%;border:1px solid var(--rb-border-strong);border-radius:12px;background:#17100d;color:var(--rb-cream);padding:12px 13px;font:inherit}.rb-lead-form textarea,.rb-lead-form button{grid-column:1/-1}.rb-lead-form label:has(textarea){grid-column:1/-1}.rb-hp{position:absolute!important;left:-9999px!important}.rb-form-notice{padding:12px 14px;margin:12px 0;border:1px solid rgba(199,160,98,.35);border-radius:12px;background:rgba(199,160,98,.09);color:var(--rb-cream)}.rb-contact-cards{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin:18px 0 32px}.rb-contact-cards>div{padding:18px;border:1px solid var(--rb-border);border-radius:14px;background:rgba(255,255,255,.025)}.rb-contact-cards strong,.rb-contact-cards a,.rb-contact-cards span{display:block}.rb-contact-cards strong{margin-bottom:7px;color:var(--rb-gold-soft)}
      .rb-cookie{left:12px!important;right:12px!important;bottom:12px!important}.rb-cookie__inner{max-width:840px!important;padding:14px 16px!important;border-radius:14px!important;gap:16px!important}.rb-cookie strong{font-size:14px!important}.rb-cookie p{font-size:11px!important;line-height:1.45!important;margin-top:3px!important}.rb-cookie__actions .rb-btn{min-height:36px!important;padding:8px 12px!important;font-size:11px!important}
      @media(max-width:700px){.rb-lead-form,.rb-contact-cards{grid-template-columns:1fr}.rb-lead-form label,.rb-lead-form textarea,.rb-lead-form button{grid-column:1}.rb-cookie__inner{padding:12px!important}.rb-cookie p{font-size:10.5px!important}.rb-cookie__actions{display:grid!important;grid-template-columns:1fr 1fr!important;gap:8px!important}}
    </style>
    <?php
}
add_action('wp_head', 'rb_final_delivery_css', 90);
