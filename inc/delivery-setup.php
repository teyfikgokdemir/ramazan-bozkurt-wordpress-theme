<?php
/**
 * Delivery-ready site setup.
 * Creates the initial structure once, without taking control away from WordPress.
 */
defined('ABSPATH') || exit;

function rb_find_attachment_id_by_slug($slug) {
    $attachment = get_page_by_path(sanitize_title($slug), OBJECT, 'attachment');
    if ($attachment) { return (int) $attachment->ID; }

    $query = new WP_Query([
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'posts_per_page' => 1,
        'name'           => sanitize_title($slug),
        'fields'         => 'ids',
    ]);
    return !empty($query->posts[0]) ? (int) $query->posts[0] : 0;
}

function rb_ensure_page($slug, $title, $content = '') {
    $page = get_page_by_path($slug, OBJECT, 'page');
    if ($page) { return (int) $page->ID; }

    $id = wp_insert_post([
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_title'   => $title,
        'post_name'    => $slug,
        'post_content' => $content,
    ]);
    return is_wp_error($id) ? 0 : (int) $id;
}

function rb_menu_has_object($menu_id, $object_id) {
    $items = wp_get_nav_menu_items($menu_id);
    if (!$items) { return false; }
    foreach ($items as $item) {
        if ($item->type === 'post_type' && (int) $item->object_id === (int) $object_id) { return true; }
    }
    return false;
}

function rb_delivery_setup_v4() {
    if (get_option('rb_delivery_setup_v4')) { return; }

    $sample = get_page_by_path('sample-page', OBJECT, 'page');
    if ($sample && $sample->post_title === 'Sample Page') { wp_trash_post($sample->ID); }
    $hello = get_page_by_path('hello-world', OBJECT, 'post');
    if ($hello && $hello->post_title === 'Hello world!') { wp_trash_post($hello->ID); }

    $home_id = rb_ensure_page('ana-sayfa', 'Ana Sayfa', '');
    $about_id = rb_ensure_page('hakkimizda', 'Hakkımızda', '<p>Ramazan Bozkurt Et ve Et Mamulleri; Kayseri’nin pastırma, sucuk, kavurma ve mantı geleneğini çağdaş satış ve sunum anlayışıyla buluşturur.</p>');
    $wholesale_id = rb_ensure_page('toptan-satis', 'Toptan Satış', '<h2>İşletmelere özel toptan satış</h2><p>Restoran, şarküteri, market, otel, kafe ve düzenli alım yapan işletmeler için pastırma, sucuk, kavurma ve mantıda toplu sipariş talepleri alınır.</p><p>Sipariş miktarı, ürün seçimi ve teslimat planına göre işletmenize özel teklif için bizimle iletişime geçebilirsiniz.</p>');
    $shipping_id = rb_ensure_page('kargo-ve-teslimat', 'Kargo ve Teslimat', '<h2>Kargo ve teslimat</h2><p>4.000 TL altındaki perakende siparişlerde 180 TL kargo ücreti uygulanır. 4.000 TL ve üzeri perakende siparişlerde kargo ücretsizdir.</p><p>Toptan siparişlerde sevkiyat koşulları sipariş miktarı ve teslimat planına göre ayrıca belirlenir.</p>');
    $faq_id = rb_ensure_page('sikca-sorulan-sorular', 'Sıkça Sorulan Sorular', '<h2>Sipariş ve ürünler hakkında sık sorulan sorular</h2><h3>Hangi ürünler satılıyor?</h3><p>Pastırma, sucuk, kavurma ve Kayseri mantısı ürünleri satışa sunulur.</p><h3>Toptan sipariş verebilir miyim?</h3><p>Evet. İşletmeler için toplu sipariş ve düzenli alım talepleri ayrıca değerlendirilir.</p><h3>Kargo ücreti nedir?</h3><p>4.000 TL altındaki perakende siparişlerde 180 TL kargo ücreti uygulanır. 4.000 TL ve üzerindeki siparişlerde kargo ücretsizdir.</p>');
    $contact_id = rb_ensure_page('iletisim', 'İletişim', '<h2>Satış ve iş birliği için iletişim</h2><p>Perakende siparişler, toptan satış talepleri, ürün bilgisi ve iş birliği konularında Ramazan Bozkurt Et ve Et Mamulleri ile iletişime geçebilirsiniz.</p>');

    if ($home_id) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $home_id);
    }

    $locations = get_theme_mod('nav_menu_locations', []);
    $menu_id = !empty($locations['primary']) ? (int) $locations['primary'] : 0;
    if (!$menu_id) {
        $menu = wp_create_nav_menu('Ana Menü');
        if (!is_wp_error($menu)) {
            $menu_id = (int) $menu;
            $locations['primary'] = $menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }
    }

    if ($menu_id) {
        $menu_pages = array_filter([$home_id, $wholesale_id, $about_id, $faq_id, $contact_id]);
        $shop_id = function_exists('wc_get_page_id') ? (int) wc_get_page_id('shop') : 0;
        if ($shop_id > 0) { array_splice($menu_pages, 1, 0, [$shop_id]); }

        foreach ($menu_pages as $page_id) {
            if (!rb_menu_has_object($menu_id, $page_id)) {
                wp_update_nav_menu_item($menu_id, 0, [
                    'menu-item-title'     => get_the_title($page_id),
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $page_id,
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ]);
            }
        }
    }

    if (class_exists('WooCommerce')) {
        $products = [
            'kayseri-pastirmasi-250-g' => [
                'sku' => 'RB-PAST-250', 'stock' => 10,
                'gallery' => ['ramazan-bozkurt-kayseri-pastirmasi-premium-sunum','ramazan-bozkurt-kayseri-pastirmasi-dilimli-sunum','ramazan-bozkurt-kayseri-pastirmasi-urun-gorseli','ramazan-bozkurt-pastirma-makro-detay'],
            ],
            'kayseri-sucugu-500-g' => [
                'sku' => 'RB-SUC-500', 'stock' => 10,
                'gallery' => ['ramazan-bozkurt-kayseri-sucugu-premium-sunum','ramazan-bozkurt-kayseri-sucugu-urun-gorseli','ramazan-bozkurt-kayseri-parmak-sucuk','ramazan-bozkurt-sucuk-geleneksel-premium-sunum'],
            ],
            'kayseri-kavurmasi-250-g' => [
                'sku' => 'RB-KAV-250', 'stock' => 10,
                'gallery' => ['ramazan-bozkurt-kayseri-kavurma-premium-sunum','ramazan-bozkurt-kayseri-kavurma-urun-sunumu','ramazan-bozkurt-kavurma-kalip-ve-dilim','ramazan-bozkurt-kavurma-makro-detay'],
            ],
            'kayseri-mantisi-500-g' => [
                'sku' => 'RB-MAN-500', 'stock' => 10,
                'gallery' => ['ramazan-bozkurt-kayseri-mantisi-geleneksel','ramazan-bozkurt-kayseri-mantisi-paket','ramazan-bozkurt-kayseri-mantisi-el-yapimi','ramazan-bozkurt-kayseri-mantisi-ambalaj'],
            ],
        ];

        foreach ($products as $slug => $data) {
            $post = get_page_by_path($slug, OBJECT, 'product');
            if (!$post) { continue; }
            $product = wc_get_product($post->ID);
            if (!$product) { continue; }

            if (!$product->get_sku()) {
                try { $product->set_sku($data['sku']); } catch (Exception $e) {}
            }
            $product->set_manage_stock(true);
            $product->set_stock_quantity((int) $data['stock']);
            $product->set_stock_status('instock');
            $product->set_catalog_visibility('visible');

            $ids = [];
            foreach ($data['gallery'] as $media_slug) {
                $attachment_id = rb_find_attachment_id_by_slug($media_slug);
                if ($attachment_id) { $ids[] = $attachment_id; }
            }
            $ids = array_values(array_unique($ids));
            if ($ids) {
                if (!$product->get_image_id()) { $product->set_image_id(array_shift($ids)); }
                if (!$product->get_gallery_image_ids() && $ids) { $product->set_gallery_image_ids($ids); }
            }
            $product->save();
        }
    }

    update_option('blogname', 'Ramazan Bozkurt Et ve Et Mamulleri');
    update_option('blogdescription', 'Kayseri pastırması, sucuk, kavurma ve mantı | Perakende ve toptan satış');
    update_option('rb_delivery_setup_v4', 1);
}
add_action('admin_init', 'rb_delivery_setup_v4', 80);

/**
 * Shipping rule: 180 TL below 4,000 TL; free shipping from 4,000 TL.
 * Replaces conflicting standard rates with one clear customer-facing option.
 */
function rb_shipping_rates($rates, $package) {
    if (!class_exists('WooCommerce') || !WC()->cart) { return $rates; }

    $subtotal = (float) WC()->cart->get_displayed_subtotal();
    $is_free = $subtotal >= 4000;

    $new_rate = new WC_Shipping_Rate(
        'rb_shipping',
        $is_free ? 'Ücretsiz Kargo' : 'Kargo',
        $is_free ? 0 : 180,
        [],
        'rb_shipping'
    );

    return ['rb_shipping' => $new_rate];
}
add_filter('woocommerce_package_rates', 'rb_shipping_rates', 999, 2);

/** Turkish storefront text fallback for untranslated WooCommerce strings. */
function rb_storefront_translations($translated, $text, $domain) {
    $map = [
        'Free shipping'   => 'Ücretsiz Kargo',
        'Shipping'        => 'Kargo',
        'Cart totals'     => 'Sepet Toplamları',
        'Proceed to checkout' => 'Ödemeye Geç',
        'Estimated total' => 'Tahmini Toplam',
        'Subtotal'        => 'Ara Toplam',
        'Apply coupon'    => 'Kuponu Uygula',
        'Coupon code'     => 'Kupon Kodu',
        'Update cart'     => 'Sepeti Güncelle',
        'Remove item'     => 'Ürünü Kaldır',
        'View cart'       => 'Sepeti Görüntüle',
        'Checkout'        => 'Ödeme',
    ];

    return isset($map[$text]) ? $map[$text] : $translated;
}
add_filter('gettext', 'rb_storefront_translations', 20, 3);
