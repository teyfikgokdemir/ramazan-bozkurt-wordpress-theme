<?php get_header(); ?>
<?php while (have_posts()) : the_post(); ?>
<?php
$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/urunler');
$wholesale_page = get_page_by_path('toptan-satis');
$wholesale_url = $wholesale_page ? get_permalink($wholesale_page) : home_url('/iletisim');
?>
<section class="rb-page-hero">
  <div class="rb-container">
    <div class="rb-eyebrow">Ramazan Bozkurt Et ve Et Mamulleri</div>
    <h1><?php the_title(); ?></h1>
    <?php if (has_excerpt()) : ?><p><?php echo esc_html(get_the_excerpt()); ?></p><?php endif; ?>
  </div>
</section>
<section class="rb-page-copy">
  <div class="rb-container rb-page-shell">
    <article <?php post_class('rb-entry'); ?>>
      <?php the_content(); ?>
    </article>
    <aside class="rb-page-aside">
      <div class="rb-eyebrow">Doğrudan Satış</div>
      <h2>Perakende ya da toptan ihtiyacınız için doğru kanala geçin.</h2>
      <p>Kayseri pastırması, sucuk, kavurma ve mantı ürünlerini mağazadan inceleyebilir; işletme alımlarında özel teklif isteyebilirsiniz.</p>
      <a class="rb-btn rb-btn--primary" href="<?php echo esc_url($shop_url); ?>">Ürünleri İncele</a>
      <a class="rb-btn" href="<?php echo esc_url($wholesale_url); ?>">Toptan Teklif Al</a>
      <nav class="rb-page-links" aria-label="Bilgi sayfaları">
        <?php
        foreach ([
          'hakkimizda' => 'Hakkımızda',
          'kargo-ve-teslimat' => 'Kargo ve Teslimat',
          'sikca-sorulan-sorular' => 'Sıkça Sorulan Sorular',
          'iletisim' => 'İletişim',
        ] as $slug => $label) {
          $page = get_page_by_path($slug);
          if ($page && (int) $page->ID !== (int) get_the_ID()) {
            echo '<a href="' . esc_url(get_permalink($page)) . '">' . esc_html($label) . '</a>';
          }
        }
        ?>
      </nav>
    </aside>
  </div>
</section>
<?php endwhile; ?>
<?php get_footer(); ?>
