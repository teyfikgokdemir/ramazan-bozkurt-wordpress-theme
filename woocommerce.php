<?php get_header(); ?>
<?php
/*
 * Mağaza ve ürün kategori arşivlerinde başlık/açıklama üstteki özel hero
 * alanında zaten gösteriliyor. WooCommerce'in eski ve yeni arşiv header
 * hook'larını kapatıp, tema içindeki varsayılan archive header'ı da CSS ile
 * kesin olarak gizliyoruz.
 */
$rb_is_catalog_archive = function_exists('is_shop') && (
    is_shop() || is_product_category() || (function_exists('is_product_tag') && is_product_tag())
);

if ($rb_is_catalog_archive) {
    add_filter('woocommerce_show_page_title', '__return_false', 999);
    remove_action('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10);
    remove_action('woocommerce_archive_description', 'woocommerce_product_archive_description', 10);
    remove_action('woocommerce_shop_loop_header', 'woocommerce_product_taxonomy_archive_header', 10);
}
?>
<?php if ($rb_is_catalog_archive) : ?>
<style>
.rb-shop-wrap .woocommerce-products-header,
.rb-shop-wrap .woocommerce-products-header__title,
.rb-shop-wrap .term-description{display:none!important}
</style>
<?php endif; ?>
<section class="rb-page-hero rb-page-hero--shop">
  <div class="rb-container">
    <div class="rb-eyebrow">Ramazan Bozkurt Et ve Et Mamulleri</div>
    <?php if (function_exists('is_shop') && is_shop()) : ?>
      <h1>Kayseri’nin geleneksel lezzetleri</h1>
      <p>Pastırma, sucuk, kavurma ve mantı ürünlerini inceleyin; güncel fiyatlara, gramajlara ve sipariş seçeneklerine ulaşın. Düzenli ve toplu alımlarda işletmenize özel teklif isteyin.</p>
    <?php elseif (is_product_category()) : ?>
      <h1><?php single_term_title(); ?></h1>
      <?php $term = get_queried_object(); if ($term && !empty($term->description)) : ?><p><?php echo esc_html(wp_strip_all_tags($term->description)); ?></p><?php endif; ?>
    <?php elseif (function_exists('is_product_tag') && is_product_tag()) : ?>
      <h1><?php single_term_title(); ?></h1>
      <?php $term = get_queried_object(); if ($term && !empty($term->description)) : ?><p><?php echo esc_html(wp_strip_all_tags($term->description)); ?></p><?php endif; ?>
    <?php elseif (is_product()) : ?>
      <div class="rb-shop-kicker">Ürün Detayı</div>
    <?php endif; ?>
  </div>
</section>
<div class="rb-shop-wrap">
  <div class="rb-container">
    <?php woocommerce_content(); ?>
  </div>
</div>
<?php get_footer(); ?>
