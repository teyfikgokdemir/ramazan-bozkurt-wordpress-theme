<?php
get_header();
?>
<section class="rb-blog-hero">
  <div class="rb-container rb-blog-hero__inner">
    <div>
      <span class="rb-eyebrow">Blog & Rehber</span>
      <h1>Kayseri lezzetleri hakkında gerçek hayatta işinize yarayacak içerikler.</h1>
    </div>
    <p>Saklama, servis, pişirme ve sofra önerileri. Pastırma, sucuk, kavurma ve mantıyı daha iyi tanımak için hazırlanan detaylı rehberler.</p>
  </div>
</section>
<section class="rb-blog-archive">
  <div class="rb-container">
    <div class="rb-blog-grid">
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article <?php post_class('rb-blog-card'); ?>>
          <a class="rb-blog-card__media" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
            <?php if (has_post_thumbnail()) the_post_thumbnail('large', ['loading'=>'lazy']); ?>
          </a>
          <div class="rb-blog-card__body">
            <div class="rb-blog-card__meta"><?php echo esc_html(get_the_date('d F Y')); ?></div>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <p><?php echo esc_html(get_the_excerpt()); ?></p>
            <a class="rb-text-link" href="<?php the_permalink(); ?>">Yazıyı Oku →</a>
          </div>
        </article>
      <?php endwhile; else : ?>
        <p>Henüz yayınlanmış yazı bulunmuyor.</p>
      <?php endif; ?>
    </div>
    <div class="rb-blog-pagination"><?php the_posts_pagination(['mid_size'=>1,'prev_text'=>'← Önceki','next_text'=>'Sonraki →']); ?></div>
  </div>
</section>
<?php get_footer(); ?>
