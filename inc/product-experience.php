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
    echo '<div class="rb-product-description-layout"><div class="rb-product-description-copy">';
    echo do_shortcode(wpautop(wp_kses_post($post->post_content)));
    echo '</div>';
    if ($visual_ids) {
        echo '<aside class="rb-product-description-visuals" aria-label="Ürün detay görselleri">';
        foreach ($visual_ids as $image_id) {
            echo wp_get_attachment_image($image_id, 'large', false, ['loading'=>'lazy','class'=>'rb-product-detail-image']);
        }
        echo '</aside>';
    }
    echo '</div>';
}

function rb_replace_description_tab_callback($tabs) {
    if (isset($tabs['description'])) { $tabs['description']['callback']='rb_product_description_tab_callback'; $tabs['description']['title']='Açıklama'; }
    if (isset($tabs['additional_information'])) { $tabs['additional_information']['title']='Ek Bilgiler'; }
    if (isset($tabs['reviews'])) { $tabs['reviews']['title']='Değerlendirmeler'; }
    return $tabs;
}
add_filter('woocommerce_product_tabs','rb_replace_description_tab_callback',30);

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
