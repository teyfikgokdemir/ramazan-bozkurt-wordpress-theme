<?php
/**
 * WooCommerce commercial configuration for Ramazan Bozkurt.
 * Keeps the storefront Turkish and applies the current shipping/grammage rules.
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

function rb_shipping_progress_markup() {
    if (!function_exists('WC') || !WC()->cart || WC()->cart->is_empty()) { return; }

    $threshold = rb_free_shipping_threshold();
    $subtotal = rb_cart_merchandise_subtotal();
    $remaining = max(0, $threshold - $subtotal);
    $percent = min(100, ($subtotal / $threshold) * 100);

    echo '<div class="rb-shipping-progress" role="status">';
    if ($remaining > 0) {
        echo '<div class="rb-shipping-progress__copy"><strong>Ücretsiz kargoya ' . wp_kses_post(wc_price($remaining)) . ' kaldı.</strong><span>Sepetinize biraz daha ürün ekleyin; 4.000 TL ve üzeri siparişlerde kargo ücretsiz.</span></div>';
    } else {
        echo '<div class="rb-shipping-progress__copy"><strong>Ücretsiz kargo kazandınız.</strong><span>Sepetiniz 4.000 TL ücretsiz kargo sınırını geçti.</span></div>';
    }
    echo '<div class="rb-shipping-progress__track" aria-hidden="true"><span style="width:' . esc_attr($percent) . '%"></span></div>';
    echo '</div>';
}
add_action('woocommerce_before_cart_totals', 'rb_shipping_progress_markup', 5);
add_action('woocommerce_before_checkout_form', 'rb_shipping_progress_markup', 7);
add_action('woocommerce_before_mini_cart', 'rb_shipping_progress_markup', 5);

function rb_storefront_turkish_strings($translated, $text, $domain) {
    $map = [
        'Free shipping' => 'Ücretsiz Kargo',
        'Shipping' => 'Kargo',
        'Cart totals' => 'Sepet Toplamları',
        'Coupon code' => 'Kupon kodu',
        'Apply coupon' => 'Kuponu uygula',
        'Proceed to checkout' => 'Ödemeye Geç',
        'Subtotal' => 'Ara Toplam',
        'Total' => 'Toplam',
        'Update cart' => 'Sepeti Güncelle',
        'Your order' => 'Siparişiniz',
        'Place order' => 'Siparişi Tamamla',
        'Billing details' => 'Fatura Bilgileri',
        'Ship to a different address?' => 'Farklı bir adrese gönderilsin mi?',
        'Product' => 'Ürün',
        'Price' => 'Fiyat',
        'Quantity' => 'Adet',
        'Add to cart' => 'Sepete Ekle',
        'View cart' => 'Sepeti Görüntüle',
        'Checkout' => 'Ödeme',
    ];
    return isset($map[$text]) ? $map[$text] : $translated;
}
add_filter('gettext', 'rb_storefront_turkish_strings', 50, 3);

function rb_convert_products_to_weight_variations_v1() {
    if (get_option('rb_weight_variations_v1')) { return; }
    if (!class_exists('WooCommerce')) { return; }

    $catalog = [
        'kayseri-pastirmasi-250-g' => [
            'name' => 'Kayseri Pastırması',
            'prices' => ['250 g' => '710', '500 g' => '1420', '1 kg' => '2840'],
            'sku' => 'RB-PAST',
        ],
        'kayseri-sucugu-500-g' => [
            'name' => 'Kayseri Sucuğu',
            'prices' => ['250 g' => '340', '500 g' => '680', '1 kg' => '1360'],
            'sku' => 'RB-SUC',
        ],
        'kayseri-kavurmasi-250-g' => [
            'name' => 'Kayseri Kavurması',
            'prices' => ['250 g' => '470', '500 g' => '940', '1 kg' => '1880'],
            'sku' => 'RB-KAV',
        ],
        'kayseri-mantisi-500-g' => [
            'name' => 'Kayseri Mantısı',
            'prices' => ['250 g' => '175', '500 g' => '350', '1 kg' => '700'],
            'sku' => 'RB-MAN',
        ],
    ];

    foreach ($catalog as $slug => $data) {
        $post = get_page_by_path($slug, OBJECT, 'product');
        if (!$post) { continue; }
        $product_id = (int) $post->ID;

        wp_set_object_terms($product_id, 'variable', 'product_type');
        clean_post_cache($product_id);
        $product = new WC_Product_Variable($product_id);
        $product->set_name($data['name']);
        $product->set_manage_stock(false);

        $attribute = new WC_Product_Attribute();
        $attribute->set_id(0);
        $attribute->set_name('Gramaj');
        $attribute->set_options(array_keys($data['prices']));
        $attribute->set_position(0);
        $attribute->set_visible(true);
        $attribute->set_variation(true);
        $product->set_attributes([$attribute]);
        $product->set_default_attributes(['gramaj' => '250 g']);
        $product->save();

        foreach ($product->get_children() as $child_id) {
            wp_delete_post($child_id, true);
        }

        foreach ($data['prices'] as $label => $price) {
            $variation = new WC_Product_Variation();
            $variation->set_parent_id($product_id);
            $variation->set_attributes(['gramaj' => $label]);
            $variation->set_regular_price($price);
            $variation->set_price($price);
            $variation->set_status('publish');
            $variation->set_manage_stock(true);
            $variation->set_stock_quantity(10);
            $variation->set_stock_status('instock');
            $suffix = $label === '1 kg' ? '1000' : str_replace(' g', '', $label);
            try { $variation->set_sku($data['sku'] . '-' . $suffix); } catch (Exception $e) {}
            $variation->save();
        }

        WC_Product_Variable::sync($product_id);
        wc_delete_product_transients($product_id);
    }

    update_option('rb_weight_variations_v1', 1);
}
add_action('admin_init', 'rb_convert_products_to_weight_variations_v1', 120);
