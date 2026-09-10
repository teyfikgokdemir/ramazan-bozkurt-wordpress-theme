<?php get_header(); ?>
<section class="rb-page-hero rb-page-hero--shop">
  <div class="rb-container">
    <div class="rb-eyebrow">Ramazan Bozkurt Et Ürünleri</div>
    <?php if (function_exists('is_shop') && is_shop()) : ?>
      <h1>Kayseri’nin geleneksel lezzetleri</h1>
      <p>Pastırma, sucuk, kavurma ve mantı ürünlerini inceleyin; ürün detaylarına, fiyatlara ve sipariş seçeneklerine ulaşın.</p>
    <?php elseif (is_product_category()) : ?>
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
