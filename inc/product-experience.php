<?php
/**
 * Premium single product experience.
 */
defined('ABSPATH') || exit;

$rb_final_delivery_file = get_template_directory() . '/inc/final-delivery.php';
if (file_exists($rb_final_delivery_file)) { require_once $rb_final_delivery_file; }

function rb_product_description_tab_callback() {
    global $post, $product;
    if (!$post || !$product) { return; }

    /* Render the research-backed, text-first product description directly.
     * Decorative gallery images are intentionally not repeated below the tabs.
     */
    $rich_template = get_template_directory() . '/woocommerce/single-product/tabs/description.php';
    if (file_exists($rich_template)) {
        include $rich_template;
        return;
    }

    echo '<div class="rb-product-description-copy">';
    echo do_shortcode(wpautop(wp_kses_post($post->post_content)));
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
add_filter('woocommerce_product_tabs', 'rb_replace_description_tab_callback', 40);

function rb_other_products_section() {
    if (!is_product()) { return; }
    global $product; if (!$product) { return; }
    $query = new WP_Query([
        'post_type'=>'product','post_status'=>'publish','posts_per_page'=>4,
        'post__not_in'=>[$product->get_id()],'orderby'=>'menu_order title','order'=>'ASC'
    ]);
    if (!$query->have_posts()) { return; }
    echo '<section class="rb-other-products"><div class="rb-other-products__head"><div><span class="rb-eyebrow">Sofranızı tamamlayın</span><h2>Diğer lezzetleri keşfedin</h2></div><a class="rb-text-link" href="'.esc_url(wc_get_page_permalink('shop')).'">Tüm ürünler →</a></div>';
    woocommerce_product_loop_start();
    while($query->have_posts()){ $query->the_post(); wc_get_template_part('content','product'); }
    woocommerce_product_loop_end();
    echo '</section>';
    wp_reset_postdata();
}
remove_action('woocommerce_after_single_product_summary','woocommerce_output_related_products',20);
add_action('woocommerce_after_single_product_summary','rb_other_products_section',28);

function rb_product_shipping_note() {
    if (!is_product()) { return; }
    $threshold = function_exists('rb_free_shipping_threshold') ? rb_free_shipping_threshold() : 4000;
    $label = function_exists('wc_price') ? wp_strip_all_tags(wc_price($threshold, ['decimals'=>0])) : number_format_i18n($threshold, 0) . ' TL';
    echo '<div class="rb-product-shipping-note"><strong>' . esc_html($label) . ' ve üzeri ücretsiz kargo</strong></div>';
}
add_action('woocommerce_single_product_summary','rb_product_shipping_note',31);

function rb_product_tabs_compact_css() {
    if (!is_product()) { return; }
    ?>
    <style>
      .woocommerce div.product .woocommerce-tabs ul.tabs{
        display:flex!important;flex-wrap:nowrap!important;align-items:center!important;gap:22px!important;
        overflow-x:auto!important;white-space:nowrap!important;padding:0 0 10px!important;margin:0 0 28px!important;
        scrollbar-width:thin;-webkit-overflow-scrolling:touch;
      }
      .woocommerce div.product .woocommerce-tabs ul.tabs::before{bottom:0!important}
      .woocommerce div.product .woocommerce-tabs ul.tabs li{
        float:none!important;display:block!important;flex:0 0 auto!important;margin:0!important;padding:0!important;
        border:0!important;background:transparent!important;border-radius:0!important;
      }
      .woocommerce div.product .woocommerce-tabs ul.tabs li::before,
      .woocommerce div.product .woocommerce-tabs ul.tabs li::after{display:none!important}
      .woocommerce div.product .woocommerce-tabs ul.tabs li a{
        display:block!important;padding:8px 0!important;font-size:14px!important;line-height:1.2!important;font-weight:700!important;
      }
      .woocommerce div.product .woocommerce-tabs ul.tabs li.active a{color:var(--rb-gold-soft)!important}
      .woocommerce div.product .woocommerce-tabs .panel{font-size:15px!important;line-height:1.75!important}
      .woocommerce div.product .woocommerce-tabs .panel h2{font-size:28px!important;line-height:1.2!important;margin:0 0 18px!important}
      .woocommerce div.product .woocommerce-tabs .panel h3{font-size:20px!important;line-height:1.3!important;margin:26px 0 10px!important}
      .woocommerce div.product .woocommerce-tabs .panel p{font-size:15px!important;line-height:1.75!important;margin:0 0 16px!important}
      .rb-product-description-visuals{display:none!important}
      .rb-product-description-layout{display:block!important}
      .rb-product-description-copy{max-width:900px!important}
      @media(max-width:700px){
        .woocommerce div.product .woocommerce-tabs ul.tabs{gap:16px!important;margin-bottom:22px!important}
        .woocommerce div.product .woocommerce-tabs ul.tabs li a{font-size:12px!important;padding:7px 0!important}
        .woocommerce div.product .woocommerce-tabs .panel{font-size:14px!important}
        .woocommerce div.product .woocommerce-tabs .panel h2{font-size:24px!important}
        .woocommerce div.product .woocommerce-tabs .panel h3{font-size:18px!important}
        .woocommerce div.product .woocommerce-tabs .panel p{font-size:14px!important;line-height:1.7!important}
      }
    </style>
    <?php
}
add_action('wp_head', 'rb_product_tabs_compact_css', 99);
