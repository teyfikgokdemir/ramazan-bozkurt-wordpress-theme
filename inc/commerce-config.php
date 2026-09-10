<?php
/**
 * WooCommerce commercial configuration for Ramazan Bozkurt.
 */
defined('ABSPATH') || exit;

function rb_free_shipping_threshold() { return 4000.0; }
function rb_standard_shipping_cost() { return 180.0; }

function rb_cart_merchandise_subtotal() {
    if (!function_exists('WC') || !WC()->cart) { return 0.0; }
    return (float) WC()->cart->get_subtotal();
}

function rb_adjust_shipping_rates($rates, $package) {
    if (!function_exists('WC') || !WC()->cart) { return $rates; }
    $subtotal = rb_cart_merchandise_subtotal();
    $free = $subtotal >= rb_free_shipping_threshold();
    foreach ($rates as $rate_id => $rate) {
        if (!is_object($rate) || !method_exists($rate, 'set_cost')) { continue; }
        $rate->set_label($free ? 'Ücretsiz Kargo' : 'Kargo');
        $rate->set_cost($free ? 0 : rb_standard_shipping_cost());
        if (method_exists($rate, 'set_taxes')) { $rate->set_taxes([]); }
        $rates[$rate_id] = $rate;
    }
    return $rates;
}
add_filter('woocommerce_package_rates', 'rb_adjust_shipping_rates', 100, 2);

/* Shipping fee is intentionally not promoted on product/cart/mini-cart screens.
 * The customer sees the actual shipping charge during checkout. */
function rb_free_shipping_checkout_notice() {
    if (!function_exists('WC') || !WC()->cart || WC()->cart->is_empty()) { return; }
    echo '<div class="rb-shipping-progress rb-shipping-progress--simple" role="status"><div class="rb-shipping-progress__copy"><strong>4.000 TL ve üzeri ücretsiz kargo</strong></div></div>';
}
add_action('woocommerce_before_checkout_form', 'rb_free_shipping_checkout_notice', 7);

function rb_storefront_turkish_strings($translated, $text, $domain) {
    $map = [
        'Free shipping' => 'Ücretsiz Kargo','Shipping' => 'Kargo','Cart totals' => 'Sepet Toplamları','Coupon code' => 'Kupon kodu','Apply coupon' => 'Kuponu uygula','Proceed to checkout' => 'Ödemeye Geç','Subtotal' => 'Ara Toplam','Total' => 'Toplam','Update cart' => 'Sepeti Güncelle','Your order' => 'Siparişiniz','Place order' => 'Siparişi Tamamla','Billing details' => 'Fatura Bilgileri','Ship to a different address?' => 'Farklı bir adrese gönderilsin mi?','Product' => 'Ürün','Price' => 'Fiyat','Quantity' => 'Adet','Add to cart' => 'Sepete Ekle','View cart' => 'Sepeti Görüntüle','Checkout' => 'Ödeme',
    ];
    return isset($map[$text]) ? $map[$text] : $translated;
}
add_filter('gettext', 'rb_storefront_turkish_strings', 50, 3);

function rb_apply_variable_product($product_id, $name, array $prices, $sku_prefix, $default_label = '') {
    wp_set_object_terms($product_id, 'variable', 'product_type');
    clean_post_cache($product_id);
    $product = new WC_Product_Variable($product_id);
    $product->set_name($name);
    $product->set_manage_stock(false);

    $attribute = new WC_Product_Attribute();
    $attribute->set_id(0);
    $attribute->set_name('Gramaj');
    $attribute->set_options(array_keys($prices));
    $attribute->set_position(0);
    $attribute->set_visible(true);
    $attribute->set_variation(true);
    $product->set_attributes([$attribute]);
    $first = $default_label ?: array_key_first($prices);
    $product->set_default_attributes(['gramaj' => $first]);
    $product->save();

    foreach ($product->get_children() as $child_id) { wp_delete_post($child_id, true); }
    foreach ($prices as $label => $price) {
        $variation = new WC_Product_Variation();
        $variation->set_parent_id($product_id);
        $variation->set_attributes(['gramaj' => $label]);
        $variation->set_regular_price((string)$price);
        $variation->set_price((string)$price);
        $variation->set_status('publish');
        $variation->set_manage_stock(true);
        $variation->set_stock_quantity(10);
        $variation->set_stock_status('instock');
        $suffix = sanitize_title(str_replace([' ', 'kg'], ['', '1000'], $label));
        try { $variation->set_sku($sku_prefix . '-' . strtoupper($suffix)); } catch (Exception $e) {}
        $variation->save();
    }
    WC_Product_Variable::sync($product_id);
    wc_delete_product_transients($product_id);
}

/* Owner-approved catalog, September 2026.
 * Pastırma prices are the owner's 1 kg prices; smaller gramages are proportional.
 * Sucuk: 1 kg 1.300 TL. Mantı: one 500 g pack, 325 TL. */
function rb_owner_catalog_v2() {
    if (get_option('rb_owner_catalog_v2') || !class_exists('WooCommerce')) { return; }

    $pastirma_category = taxonomy_exists('product_cat') ? get_term_by('slug', 'pastirma', 'product_cat') : null;
    $pastirma_products = [
        'kayseri-pastirmasi-250-g' => ['Sırt Pastırma', ['250 g'=>'750','400 g'=>'1200','700 g'=>'2100','1 kg'=>'3000'], 'RB-SIRT', ['ramazan-bozkurt-kayseri-pastirmasi-dilimli-sunum','ramazan-bozkurt-pastirma-kesit-detay','ramazan-bozkurt-pastirma-dilim-detay']],
        'antrikot-pastirma' => ['Antrikot Pastırma', ['250 g'=>'800','400 g'=>'1280','700 g'=>'2240','1 kg'=>'3200'], 'RB-ANT', ['ramazan-bozkurt-kayseri-pastirmasi-premium-sunum','ramazan-bozkurt-pastirma-makro-detay','ramazan-bozkurt-pastirma-dilim-detay-1']],
        'tutunluk-pastirma' => ['Tütünlük Pastırma', ['250 g'=>'850','400 g'=>'1360','700 g'=>'2380','1 kg'=>'3400'], 'RB-TUT', ['ramazan-bozkurt-pastirma-butun-parca-dilimli','ramazan-bozkurt-kayseri-pastirmasi-sunum-1','ramazan-bozkurt-pastirma-kesit-sunumu']],
        'cemensiz-pastirma' => ['Çemensiz Pastırma', ['250 g'=>'925','400 g'=>'1480','700 g'=>'2590','1 kg'=>'3700'], 'RB-CEM', ['ramazan-bozkurt-pastirma-butun-parca-dilimli-1','ramazan-bozkurt-kayseri-pastirmasi-sunum-2','ramazan-bozkurt-pastirma-kesit-detay']],
    ];

    foreach ($pastirma_products as $slug => $data) {
        $post = get_page_by_path($slug, OBJECT, 'product');
        if (!$post) {
            $id = wp_insert_post(['post_type'=>'product','post_status'=>'publish','post_title'=>$data[0],'post_name'=>$slug,'post_excerpt'=>'250 g, 400 g, 700 g ve 1 kg gramaj seçenekleriyle Kayseri pastırması.','post_content'=>'<h2>'.esc_html($data[0]).'</h2><p>Ramazan Bozkurt Et ve Et Mamulleri pastırma seçkisinde 250 g, 400 g, 700 g ve 1 kg gramaj seçenekleriyle sunulur.</p><h3>Gramaj seçenekleri</h3><ul><li>250 g</li><li>400 g</li><li>700 g</li><li>1 kg</li></ul><p>Ambalaj üzerindeki saklama ve tüketim talimatlarını esas alınız.</p>']);
            if (is_wp_error($id) || !$id) { continue; }
            $post = get_post($id);
        }
        $id = (int)$post->ID;
        if ($pastirma_category && !is_wp_error($pastirma_category)) { wp_set_object_terms($id, [(int)$pastirma_category->term_id], 'product_cat'); }
        $main = rb_media_id_first([$data[3][0]]); if ($main) { set_post_thumbnail($id, $main); }
        $gallery=[]; foreach(array_slice($data[3],1) as $img){ $iid=rb_media_id_first([$img]); if($iid && $iid!==$main){$gallery[]=$iid;} }
        update_post_meta($id, '_product_image_gallery', implode(',', $gallery));
        rb_apply_variable_product($id, $data[0], $data[1], $data[2], '250 g');
    }

    $sucuk = get_page_by_path('kayseri-sucugu-500-g', OBJECT, 'product');
    if ($sucuk) {
        wp_update_post(['ID'=>$sucuk->ID,'post_title'=>'Kayseri Sucuğu','post_excerpt'=>'500 g ve 1 kg seçenekleriyle Kayseri sucuğu.','post_content'=>'<h2>Kayseri Sucuğu</h2><p>500 g ve 1 kg gramaj seçenekleriyle sunulur.</p><h3>Gramaj seçenekleri</h3><ul><li>500 g</li><li>1 kg</li></ul><p>Pişirme, saklama ve tüketim için ambalaj üzerindeki talimatları esas alınız.</p>']);
        rb_apply_variable_product((int)$sucuk->ID, 'Kayseri Sucuğu', ['500 g'=>'650','1 kg'=>'1300'], 'RB-SUC', '500 g');
    }

    $manti = get_page_by_path('kayseri-mantisi-500-g', OBJECT, 'product');
    if ($manti) {
        wp_set_object_terms((int)$manti->ID, 'simple', 'product_type'); clean_post_cache((int)$manti->ID);
        $product = new WC_Product_Simple((int)$manti->ID);
        $product->set_name('Kayseri Mantısı 500 g'); $product->set_regular_price('325'); $product->set_price('325');
        $product->set_manage_stock(true); $product->set_stock_quantity(10); $product->set_stock_status('instock');
        $product->set_short_description('1 paket 500 g Kayseri mantısı.');
        $product->set_description('<h2>Kayseri Mantısı 500 g</h2><p>Tek paket seçeneği 500 gramdır.</p><h3>Paket bilgisi</h3><ul><li>1 paket: 500 g</li></ul><p>Pişirme ve saklama koşulları için ambalaj üzerindeki talimatları esas alınız.</p>');
        $product->save();
        foreach (get_posts(['post_type'=>'product_variation','post_parent'=>$manti->ID,'numberposts'=>-1,'fields'=>'ids']) as $vid) { wp_delete_post($vid,true); }
    }

    update_option('rb_owner_catalog_v2', 1);
}
add_action('admin_init', 'rb_owner_catalog_v2', 160);
